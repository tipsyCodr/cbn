<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExpressionOfInterestResource\Pages;
use App\Filament\Resources\ExpressionOfInterestResource\RelationManagers;
use App\Models\ExpressionOfInterest;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ExpressionOfInterestResource extends Resource
{
    protected static ?string $model = ExpressionOfInterest::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope-open';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Radio::make('form_type') // Added form_type radio group
                    ->options([
                        'expression_of_interest' => "Yes, I'm ready to get started",
                        'request_for_information' => "Maybe, I would like more information",
                        'feedback_form' => "No, not as of now",
                    ])
                    ->required(),
                Forms\Components\TextInput::make('full_name')
                    ->required(),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required(),
                Forms\Components\TextInput::make('mobile')
                    ->tel()
                    ->required(),
                Forms\Components\TextInput::make('professional_classification')
                    ->required(),
                Forms\Components\DatePicker::make('visit_date')
                    ->required(),
                Forms\Components\Textarea::make('product_service_feedback')
                    ->required(), // Added required validation

                Forms\Components\TextInput::make('chapter_name') // Added chapter_name field
                    ->required(),
                Forms\Components\TextInput::make('region') // Added region field
                    ->required(),
                Forms\Components\TextInput::make('invited_by') // Added invited_by field
                    ->required(),
                Forms\Components\Select::make('previous_member') // Added previous_member field
                    ->options([
                        true => 'Yes',
                        false => 'No',
                    ])
                    ->required(),
                Forms\Components\Radio::make('experience_rating') // Added experience_rating field
                    ->options([
                        1 => '1',
                        2 => '2',
                        3 => '3',
                        4 => '4',
                        5 => '5',
                        6 => '6',
                        7 => '7',
                        8 => '8',
                        9 => '9',
                        10 => '10',
                    ])
                    ->inline()
                    ->inlineLabel(false)
                    ->required(),
                Forms\Components\Textarea::make('product_service_feedback') // Added product_service_feedback field
                    ->required(),
                Forms\Components\TextInput::make('referral_source') // Added referral_source field
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('full_name'),
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\TextColumn::make('mobile'),
                Tables\Columns\TextColumn::make('professional_classification'),
                Tables\Columns\TextColumn::make('visit_date'),
                Tables\Columns\TextColumn::make('form_type')
                    ->badge()
                    ->sortable()
                    ->colors([
                        'success' => 'expression_of_interest',
                        'warning' => 'request_for_information',
                        'danger' => 'feedback_form',
                    ])
                    ->formatStateUsing(fn($state) => match ($state) {
                        'expression_of_interest' => 'Yes',
                        'request_for_information' => 'Maybe',
                        'feedback_form' => 'No',
                        default => ucfirst(str_replace('_', ' ', $state)),
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('form_type')
                    ->options([
                        'expression_of_interest' => "Yes, I'm ready to get started",
                        'request_for_information' => "Maybe, I would like more information",
                        'feedback_form' => "No, not as of now",
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
            'index' => Pages\ListExpressionOfInterests::route('/'),
            'create' => Pages\CreateExpressionOfInterest::route('/create'),
            'edit' => Pages\EditExpressionOfInterest::route('/{record}/edit'),
        ];
    }
}
