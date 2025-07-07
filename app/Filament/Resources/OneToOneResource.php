<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OneToOneResource\Pages;
use App\Models\OneToOne;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
class OneToOneResource extends Resource
{
    protected static ?string $model = OneToOne::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    
    protected static ?string $navigationLabel = 'One to One Meetings';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                    ->label('Request Owner')
                    ->relationship('user', 'name')
                    ->required(),
                Select::make('met_with')
                    ->label('Met With')
                    ->relationship('metWithUser', 'name')
                    ->required(),
                Select::make('initiated_by')
                    ->label('Initiated By')
                    ->relationship('initiator', 'name')
                    ->required(),
                TextInput::make('meeting_location')
                    ->label('Meeting Location')
                    ->required()
                    ->maxLength(255),
                DatePicker::make('meeting_date')
                    ->label('Meeting Date')
                    ->required(),
                Textarea::make('topics')
                    ->label('Topics Discussed')
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')->label('Requested  At')->date("d-M-y -  m:s:A")->sortable()->searchable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Request Owner')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('metWithUser.name')
                    ->label('Met With')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('initiator.name')
                    ->label('Initiated By')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('meeting_location')
                    ->label('Location')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('meeting_date')
                    ->label('Date')
                    ->date()
                    ->sortable(),
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
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListOneToOnes::route('/'),
            'create' => Pages\CreateOneToOne::route('/create'),
            'edit' => Pages\EditOneToOne::route('/{record}/edit'),
        ];
    }
} 