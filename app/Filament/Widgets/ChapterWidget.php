<?php

namespace App\Filament\Widgets;

use App\Models\Chapter;
use App\Models\OneToOne;
use App\Models\Referral;
use App\Models\ThankYou;
use App\Models\User;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;

class ChapterWidget extends BaseWidget
{

    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 2;


    protected function getTableHeading(): string
    {
        return 'Chapters ';
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Chapter::query()
            )
            ->filters([
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('from')->label('From'),
                        DatePicker::make('until')->label('Until'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['from'] ?? null, fn ($query, $date) => $query->whereDate('created_at', '>=', $date))
                            ->when($data['until'] ?? null, fn ($query, $date) => $query->whereDate('created_at', '<=', $date));
                    }),
            ])
            ->columns([
                TextColumn::make('id'),
                TextColumn::make('title')->label('Chapters')->searchable()->sortable(),
                TextColumn::make('total_amount')
                ->getStateUsing(function (Chapter $record): string {
                    $userIds = User::where('chapter_id', $record->id)
                        ->where('active', 1)
                        ->pluck('id')
                        ->toArray();
                        
                    if (empty($userIds)) {
                        return 0;
                    }
                   
                    $amount = '₹ ' . ThankYou::whereIn('from', $userIds)->sum('amount');
                    return $amount;
                })
                ->sortable(),
                TextColumn::make('tyfcb')->label('Total TYFCB')
                    ->getStateUsing(function (Chapter $record): int {
                        $userIds = User::where('chapter_id', $record->id)
                            ->where('active', 1)
                            ->pluck('id')
                            ->toArray();
                            
                        if (empty($userIds)) {
                            return 0;
                        }
                        
                        return ThankYou::whereIn('from', $userIds)->count();
                    })
                    ->sortable(),
                TextColumn::make('referrals')->label('Total Referrals')
                    ->getStateUsing(function (Chapter $record): int {
                        $userIds = User::where('chapter_id', $record->id)
                            ->where('active', 1)
                            ->pluck('id')
                            ->toArray();
                            
                        if (empty($userIds)) {
                            return 0;
                        }
                        
                        return Referral::whereIn('from', $userIds)->count();
                    })
                    ->sortable(),
                TextColumn::make('oto_requests')->label('Total One to One Req.')
                    ->getStateUsing(function (Chapter $record): int {
                        $userIds = User::where('chapter_id', $record->id)
                            ->where('active', 1)
                            ->pluck('id')
                            ->toArray();
                            
                        if (empty($userIds)) {
                            return 0;
                        }
                        
                        return OneToOne::whereIn('user_id', $userIds)->count();
                    })
                    ->sortable(),
                // TextColumn::make('created_at')->dateTime(),
                // TextColumn::make('updated_at')->dateTime(),
            ]);
    }
}
