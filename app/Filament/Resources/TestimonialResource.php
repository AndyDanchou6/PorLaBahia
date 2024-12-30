<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TestimonialResource\Pages;
use App\Filament\Resources\TestimonialResource\RelationManagers;
use App\Models\GuestInfo;
use App\Models\Testimonial;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TestimonialResource extends Resource
{
    protected static ?string $model = Testimonial::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-ellipsis';

    protected static ?string $navigationGroup = 'Data';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                \Filament\Forms\Components\Group::make()
                    ->schema([
                        \Filament\Forms\Components\Section::make()
                            ->schema([
                                \Filament\Forms\Components\Select::make('guest_id')
                                    ->label('Guest Name')
                                    ->relationship('guest', 'id')
                                    ->options(fn() => \App\Models\GuestInfo::all()->mapWithKeys(fn($guest) => [
                                        $guest->id => $guest->first_name . ' ' . $guest->last_name,
                                    ]))
                                    ->required()
                                    ->columnSpan('2'),
                                Forms\Components\MarkdownEditor::make('comment')
                                    ->required()
                                    ->toolbarButtons([
                                        'blockquote',
                                        'bold',
                                        'bulletList',
                                        'codeBlock',
                                        'heading',
                                        'italic',
                                        'link',
                                        'orderedList',
                                        'redo',
                                        'strike',
                                        'table',
                                        'undo',
                                    ])
                                    ->columnSpan('2'),
                            ])
                    ])->columnSpan([
                        'md' => 2,
                        'lg' => 2,
                    ])->columns(2),

                \Filament\Forms\Components\Fieldset::make('Guest Image')
                    ->schema([
                        Forms\Components\FileUpload::make('profile_image')
                            ->label('')
                            ->image()
                            ->columnSpan('full')
                    ])->columnSpan([
                        'md' => 1,
                        'lg' => 1,
                    ]),

            ])->columns([
                'md' => 3,
                'lg' => 3,
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('profile_image')
                    ->label('Profile Image')
                    ->square(),
                Tables\Columns\TextColumn::make('guest.full_name')
                    ->searchable(['first_name', 'last_name'])
                    ->numeric()
                    ->label('Guest Name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
                \Filament\Tables\Filters\TrashedFilter::make()
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->visible(fn($record) => !$record->trashed()),
                Tables\Actions\EditAction::make()
                    ->visible(fn($record) => !$record->trashed()),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make()
                    ->color('success')
                    ->visible(fn($record) => $record->trashed() && auth()->user()->role === 1),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make()
                        ->color('success')
                        ->visible(fn() => auth()->user()->role === 1),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTestimonials::route('/'),
            'create' => Pages\CreateTestimonial::route('/create'),
            'view' => Pages\ViewTestimonial::route('/{record}'),
            'edit' => Pages\EditTestimonial::route('/{record}/edit'),
        ];
    }
}
