<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FaqsResource\Pages;
use App\Filament\Resources\FaqsResource\RelationManagers;
use App\Models\Faqs;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FaqsResource extends Resource
{
    protected static ?string $model = Faqs::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'FAQS';

    protected static ?string $navigationGroup = 'Settings';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('category')
                    ->required()
                    ->options([
                        "booking" => "Booking",
                        "services" => "Services",
                        "policies" => "Policies",
                        "payments" => "Payments",
                    ]),
                Forms\Components\TextInput::make('questions')
                    ->required()
                    ->maxLength(255),
                Forms\Components\MarkdownEditor::make('answer')
                    ->toolbarButtons([
                        'attachFiles',
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
                    ->columnSpan(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('category')
                    ->searchable(),
                Tables\Columns\TextColumn::make('questions')
                    ->searchable()
                    ->sortable(),
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
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->visible(fn(Faqs $record) => !$record->trashed()),
                Tables\Actions\EditAction::make()
                    ->visible(fn(Faqs $record) => !$record->trashed())
                    ->color('warning'),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make()
                    ->visible(function (Faqs $record) {
                        return $record->trashed() && auth()->user()->role == 1;
                    })
                    ->color('success'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListFaqs::route('/'),
            'create' => Pages\CreateFaqs::route('/create'),
            'view' => Pages\ViewFaqs::route('/{record}'),
            'edit' => Pages\EditFaqs::route('/{record}/edit'),
        ];
    }
}
