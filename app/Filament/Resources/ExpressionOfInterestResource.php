<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExpressionOfInterestResource\Pages;
use App\Filament\Resources\ExpressionOfInterestResource\RelationManagers;
use App\Models\ExpressionOfInterest;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
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
                Radio::make('form_type') // Added form_type radio group
                    ->options([
                        'expression_of_interest' => "Yes, I'm ready to get started",
                        'request_for_information' => "Maybe, I would like more information",
                        'feedback_form' => "No, not as of now",
                    ])
                    ->required(),
                TextInput::make('full_name')
                    ->required(),
                TextInput::make('email')
                    ->email()
                    ->required(),
                TextInput::make('mobile')
                    ->tel()
                    ->required(),
                TextInput::make('professional_classification')
                    ->required(),
                DatePicker::make('visit_date')
                    ->required(),
                Textarea::make('product_service_feedback')
                    ->required(), // Added required validation

                TextInput::make('chapter_name') // Added chapter_name field
                    ->required(),
                    TextInput::make('company_name')
                    ->label('Company Name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('region') // Added region field
                    ->required(),
                TextInput::make('invited_by') // Added invited_by field
                    ->required(),
                Select::make('previous_member') // Added previous_member field
                    ->options([
                        true => 'Yes',
                        false => 'No',
                    ])
                    ->required(),
                Radio::make('experience_rating') // Added experience_rating field
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
                Textarea::make('product_service_feedback') // Added product_service_feedback field
                    ->required(),
                TextInput::make('referral_source') // Added referral_source field
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('form_type')
                ->badge()->label('Interested?')
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
                Tables\Columns\TextColumn::make('full_name'),
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\TextColumn::make('mobile'),
                Tables\Columns\TextColumn::make('professional_classification'),
                Tables\Columns\TextColumn::make('visit_date'),
               
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('form_type')
                    ->options([
                        'expression_of_interest' => "Yes, I'm ready to get started",
                        'request_for_information' => "Maybe, I would like more information",
                        'feedback_form' => "No, not as of now",
                    ]),
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
