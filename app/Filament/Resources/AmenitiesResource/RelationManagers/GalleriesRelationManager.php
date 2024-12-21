<?php

namespace App\Filament\Resources\AmenitiesResource\RelationManagers;

use App\Filament\Resources\AmenitiesResource;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GalleriesRelationManager extends RelationManager
{
    protected static string $relationship = 'galleries';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Fieldset::make('Galleries')
                    ->schema([
                        Forms\Components\FileUpload::make('image')
                            ->image()
                            ->columnSpan(2),
                    ])
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('category_name')
            ->columns([
                Tables\Columns\Layout\Stack::make([
                    Tables\Columns\ImageColumn::make('image')
                        ->label('Thumbnail')
                        ->square()
                        ->size(150)
                        ->extraAttributes([
                            'style' => 'margin: 25px;'
                        ]),
                    Tables\Columns\TextColumn::make('featured_text')
                        ->label('')
                        ->getStateUsing(function ($record) {
                            return 'is featured?';
                        }),

                    Tables\Columns\ToggleColumn::make('is_featured')
                        ->label('Is featured')
                        ->onColor('success')
                        ->offColor('danger')
                        ->onIcon('heroicon-m-check')
                        ->offIcon('heroicon-m-x-mark')
                        ->beforeStateUpdated(function ($record, $state) {
                            return $record->is_featured = $state;
                        })
                        ->afterStateUpdated(function ($record, $state) {
                            return $record->is_featured = $state;
                        })
                        ->extraAttributes(['style' => 'margin-top: 10px;']),

                ])->space(1),
            ])
            ->contentGrid([
                'md' => 3,
                'xl' => 3,
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make()
                //     ->label('New Galleries'),
                Tables\Actions\Action::make('galleries')
                    ->label('Create New Galleries')
                    ->form([
                        \Filament\Forms\Components\Fieldset::make('galleries')
                            ->schema([
                                \Filament\Forms\Components\Repeater::make('images')
                                    ->schema([
                                        \Filament\Forms\Components\FileUpload::make('image')
                                            ->image()
                                            ->columnSpan('full'),
                                    ])
                                    ->grid(2)
                                    ->columns(2)
                                    ->reorderableWithButtons()
                                    ->collapsed(false)
                                    ->addActionLabel('Add another image'),
                            ])
                            ->columns(1),
                    ])
                    ->action(function (array $data, $record): void {
                        $record = $this->getOwnerRecord();
                        if (isset($data['images']) && is_array($data['images'])) {
                            foreach ($data['images'] as $array) {
                                $imageArray = null;

                                if (!empty($array['image'])) {
                                    $imageArray = $array['image'];
                                }

                                \App\Models\Galleries::create([
                                    'image' => $imageArray,
                                    'galleries_type' => \App\Models\Amenities::class,
                                    'galleries_id' => $record->id,
                                    'is_featured' => null,
                                ]);
                            }

                            Notification::make()
                                ->success()
                                ->title('Gallery Updated')
                                ->body('Images have been successfully uploaded.')
                                ->send();

                            $this->redirect(AmenitiesResource::getUrl('edit', ['record' => $record->id]));
                        }
                    })
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->color('warning'),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
