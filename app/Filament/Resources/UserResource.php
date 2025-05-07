<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('bio')
                    ->nullable()
                    ->maxLength(255),
                Forms\Components\TextInput::make('mobile')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('occupation')
                    ->nullable()
                    ->maxLength(255),
                Forms\Components\TextInput::make('department')
                    ->nullable()
                    ->maxLength(255),
                Forms\Components\TextInput::make('category')
                    ->nullable()
                    ->maxLength(255),
                Forms\Components\TextInput::make('designation')
                    ->nullable()
                    ->maxLength(255),
                Forms\Components\TextInput::make('company')
                    ->nullable()
                    ->maxLength(255),
                Forms\Components\TextInput::make('address')
                    ->nullable()
                    ->maxLength(255),
                Forms\Components\FileUpload::make('profile_photo')
                    ->nullable(),
                Forms\Components\TextInput::make('password')
                    ->nullable()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('due_date')
                    ->nullable(),
                Forms\Components\Select::make('active')
                    ->required()
                    ->default('1')
                    ->options([
                        '1' => 'Active',
                        '0' => 'Inactive',
                    ]),
                Forms\Components\Select::make('role')
                    ->required()
                    ->default('user')
                    ->options([
                        'admin' => 'Admin',
                        'user' => 'User',
                    ]),
            ]); 
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('mobile')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('occupation')->sortable()->searchable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('active')
                    ->options([
                        '1' => 'Active',
                        '0' => 'Inactive',
                    ]),
                Tables\Filters\SelectFilter::make('role')
                    ->options([
                        'admin' => 'Admin',
                        'user' => 'User',
                    ]),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
