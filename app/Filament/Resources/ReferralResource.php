<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReferralResource\Pages;
use App\Filament\Resources\ReferralResource\RelationManagers;
use App\Models\Referral;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ReferralResource extends Resource
{
    protected static ?string $model = Referral::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('from')
                    ->relationship('user', 'name')
                    ->label('From')
                    ->required(),
                Forms\Components\Select::make('to')
                    ->label('To')
                    ->relationship('user', 'name')
                    ->required(),
                Forms\Components\Select::make('referral_type')
                    ->options([
                        'inside' => 'Inside',
                        'outside' => 'Outside',
                        'tier3' => 'Tier 3',
                    ])
                    ->required(),
                Forms\Components\Select::make('referral_status')
                    ->options([
                        'told_them_you_would_call' => 'Told Them You Would Call',
                        'given_card' => 'Given Card',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('referral_contact_name')
                    ->required(),
                Forms\Components\TextInput::make('referral_contact_no')
                    ->nullable(),
                Forms\Components\TextInput::make('referral_contact_email')
                    ->email()
                    ->nullable(),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->nullable(),
                Forms\Components\Textarea::make('address')
                    ->nullable(),
                Forms\Components\Textarea::make('comments')
                    ->nullable(),
                Forms\Components\TextInput::make('referral_hotness')
                    ->numeric(),
               
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')->label('Referring')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('user.name')->label('Referred')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('referral_type')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('referral_status')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('referral_contact_name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('referral_contact_no')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('email')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('mobile')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('address')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('comments')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('referral_hotness')->sortable()->searchable(),
            ])
            ->filters([
                //
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReferrals::route('/'),
            'create' => Pages\CreateReferral::route('/create'),
            'edit' => Pages\EditReferral::route('/{record}/edit'),
        ];
    }
   
}
