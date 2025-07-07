<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ThankYouRequestResource\Pages;
use App\Filament\Resources\ThankYouRequestResource\RelationManagers;
use App\Models\ThankYou;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ThankYouRequestResource extends Resource
{
    protected static ?string $model = ThankYou::class;

    protected static ?string $navigationIcon = 'heroicon-o-heart';

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
                Tables\Columns\TextColumn::make('created_at')->label('Referred At')->date("d-M-y -  m:s:A")->sortable()->searchable(),
                Tables\Columns\TextColumn::make('user.name')->label('From')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('toUser.name')->label('To')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('amount')
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(fn($state) => 'Rs. '. number_format($state). '/-' ),

                Tables\Columns\TextColumn::make('business_type')->sortable()->searchable()->formatStateUsing(fn($state) => match ($state) {
                    'new' => 'New',
                    'repeat' => 'Repear',
                    '' => 'N/A',
                    default => ucfirst($state),
                }),
                Tables\Columns\TextColumn::make('referral_type')->sortable()->searchable()->formatStateUsing(fn($state) => match ($state) {
                    'outside' => 'Outside',
                    'inside' => 'Inside',
                    'tier3' => 'Tier 3',
                    default => ucfirst($state),
                }),
            ])
            ->filters([
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from')->label('From'),
                        DatePicker::make('created_until')->label('To'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['created_from'], fn ($query, $date) =>
                                $query->whereDate('created_at', '>=', $date)
                            )
                            ->when($data['created_until'], fn ($query, $date) =>
                                $query->whereDate('created_at', '<=', $date)
                            );
                    }),
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
