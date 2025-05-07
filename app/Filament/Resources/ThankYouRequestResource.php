<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ThankYouRequestResource\Pages;
use App\Filament\Resources\ThankYouRequestResource\RelationManagers;
use App\Models\ThankYou;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ThankYouRequestResource extends Resource
{
    protected static ?string $model = ThankYou::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('from')
                    ->label('User')
                    ->relationship('user', 'name')
                    ->required(),
                Forms\Components\Select::make('to')
                    ->relationship('user', 'name')
                    ->required(),
                Forms\Components\TextInput::make('amount')
                    ->required()
                    ->numeric()
                    ->minValue(0),
                Forms\Components\Select::make('business_type')
                    ->required()
                    ->options([
                        'new' => 'New',
                        'repeat' => 'Repeat',
                    ])
                    ->default('new'),
                Forms\Components\Select::make('referral_type')
                    ->required()
                    ->options([
                        'inside' => 'Inside',
                        'outside' => 'Outside',
                        'tier3' => 'Tier 3',
                    ])
                    ->default('outside'),
                Forms\Components\Textarea::make('comments')
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')->label('From')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('to')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('amount')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('business_type')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('referral_type')->sortable()->searchable(),
            ])
            ->filters([
                // Add filters if needed
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            // Define any relation managers here if needed
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListThankYouRequests::route('/'),
            'create' => Pages\CreateThankYouRequest::route('/create'),
            'edit' => Pages\EditThankYouRequest::route('/{record}/edit'),
        ];
    }
}
