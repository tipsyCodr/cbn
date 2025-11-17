<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentDetailsResource\Pages;
use App\Filament\Resources\PaymentDetailsResource\RelationManagers;
use App\Models\PaymentDetails;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class PaymentDetailsResource extends Resource
{
    protected static ?string $model = PaymentDetails::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    protected static ?string $navigationLabel = 'Payment Details';

    protected static ?string $navigationGroup = 'Membership Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('User')
                    ->options(User::pluck('name', 'id'))
                    ->searchable()
                    ->required(),

                Forms\Components\Textarea::make('payment_details')
                    ->label('Payment Details')
                    ->required()
                    ->columnSpanFull(),

                Forms\Components\FileUpload::make('payment_screenshot')
                    ->label('Payment Screenshot')
                    ->image()
                    ->directory('payment_screenshots')
                    ->required()
                    ->columnSpanFull(),

                Forms\Components\Textarea::make('other_details')
                    ->label('Other Details')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Member Name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('payment_details')
                    ->label('Payment Details')
                    ->limit(50)
                    ->searchable()
                    ->wrap(),

                Tables\Columns\ImageColumn::make('payment_screenshot')
                    ->label('Payment Screenshot')
                    ->height(50)
                    ->circular(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Submission Date')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('user_id')
                    ->label('Member')
                    ->options(User::pluck('name', 'id'))
                    ->searchable(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPaymentDetails::route('/'),
            'create' => Pages\CreatePaymentDetails::route('/create'),
            'edit' => Pages\EditPaymentDetails::route('/{record}/edit'),
        ];
    }
}
