<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReferralResource\Pages;
use App\Filament\Resources\ReferralResource\RelationManagers;
use App\Models\Referral;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Filters\Filter;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class ReferralResource extends Resource
{
    protected static ?string $model = Referral::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

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
                Forms\Components\DateTimePicker::make('created_at')
                    ->label("Happened At")
                    ->disabled(true)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('created_at')->label('Referred At')->date("d-M-y -  m:s:A")->sortable()->searchable(),
                Tables\Columns\TextColumn::make('user.name')->label('From')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('referredUser.name')->label('To')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('referral_type')->sortable()->searchable()->formatStateUsing(fn ($state) => match ($state) {
                    'outside' => 'Outside',
                    'inside' => 'Inside',
                    'tier3' => 'Tier 3',
                    default => ucfirst($state),
                }),
                // Tables\Columns\TextColumn::make('referral_status')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('referral_contact_name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('referral_contact_no')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('email')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('mobile')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('address')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('comments')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('referral_hotness')->sortable()->searchable(),
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
                ExportBulkAction::make(),

                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [

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
