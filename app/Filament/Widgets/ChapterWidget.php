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
                        DatePicker::make('from')
                            ->label('From')
                            ->placeholder('Select start date'),
                        DatePicker::make('until')
                            ->label('Until')
                            ->placeholder('Select end date'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when(
                                !empty($data['from']), 
                                fn ($query) => $query->whereDate('created_at', '>=', $data['from'])
                            )
                            ->when(
                                !empty($data['until']), 
                                fn ($query) => $query->whereDate('created_at', '<=', $data['until'])
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        
                        if (!empty($data['from'])) {
                            $indicators[] = 'From: ' . \Carbon\Carbon::parse($data['from'])->format('M d, Y');
                        }
                        
                        if (!empty($data['until'])) {
                            $indicators[] = 'Until: ' . \Carbon\Carbon::parse($data['until'])->format('M d, Y');
                        }
                        
                        return $indicators;
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
                            return '₹ 0';
                        }
                       
                        $amount = '₹ ' . number_format(ThankYou::whereIn('from', $userIds)->sum('amount'), 2);
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
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
                // TextColumn::make('updated_at')->dateTime(),
            ]);
    }
}