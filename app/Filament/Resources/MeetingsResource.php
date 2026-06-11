<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MeetingsResource\Pages;
use App\Filament\Resources\MeetingsResource\RelationManagers;
use App\Models\Meeting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class MeetingsResource extends Resource
{
    protected static ?string $model = Meeting::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required(),
                    Forms\Components\DatePicker::make('date')
                    ->required(),
                    Forms\Components\TimePicker::make('start_time')
                    ->nullable(),
                    Forms\Components\TimePicker::make('end_time')
                    ->nullable(),
                    Forms\Components\TextInput::make('location')
                    ->required(),
                Forms\Components\TextInput::make('tyfcb')
                    ->type('number')
                    ->nullable(),
                Forms\Components\TextInput::make('visitors')
                    ->type('number')
                    ->nullable(),
                Forms\Components\TextInput::make('speakers')
                    ->nullable(),
                Forms\Components\Toggle::make('is_recurring')
                    ->label('Is Recurring')
                    ->default(false)
                    ->live(),
                Forms\Components\Select::make('interval')
                    ->label('Recurring Interval')
                    ->options([
                        'weekly' => 'Weekly',
                        'monthly' => 'Monthly',
                        'yearly' => 'Yearly',
                    ])
                    ->live()
                    ->visible(fn (Forms\Get $get) => $get('is_recurring'))
                    ->nullable(),
                Forms\Components\Select::make('recurring_day')
                    ->label(fn (Forms\Get $get) => $get('interval') === 'weekly' ? 'Day of Week' : 'Date of Month')
                    ->options(fn (Forms\Get $get) => match ($get('interval')) {
                        'weekly' => [
                            0 => 'Sunday',
                            1 => 'Monday',
                            2 => 'Tuesday',
                            3 => 'Wednesday',
                            4 => 'Thursday',
                            5 => 'Friday',
                            6 => 'Saturday',
                        ],
                        'monthly' => array_combine(range(1, 31), range(1, 31)),
                        default => [],
                    })
                    ->visible(fn (Forms\Get $get) => $get('is_recurring') && in_array($get('interval'), ['weekly', 'monthly']))
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('date'),
                Tables\Columns\TextColumn::make('start_time'),
                Tables\Columns\TextColumn::make('end_time'),
                Tables\Columns\TextColumn::make('tyfcb'),
                Tables\Columns\TextColumn::make('visitors'),
                Tables\Columns\TextColumn::make('speakers'),
            ])
            ->filters([
                //
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMeetings::route('/'),
            'create' => Pages\CreateMeetings::route('/create'),
            'edit' => Pages\EditMeetings::route('/{record}/edit'),
        ];
    }
}
