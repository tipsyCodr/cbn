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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class LastFifteenDaysWidget extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';
    protected static ?int $sort = 2;

    protected function getTableHeading(): string
    {
        return 'Last Fifteen Days';
    }

    public function table(Table $table): Table
    {
        return $table->query(function (): Builder {
            return Chapter::query();
        })
            ->columns([
                TextColumn::make('title')->label('Title'),
                TextColumn::make('users')
                    ->label('Last 15 Days Total')
                    ->getStateUsing(function (Chapter $record) {
                        $userIds = User::where('chapter_id', $record->id)->pluck('id');
                        
                        // Get total for last 15 days
                        $fifteenDaysAgo = Carbon::now()->subDays(15)->startOfDay();
                        
                        $total = ThankYou::whereIn('from', $userIds)
                            ->where('created_at', '>=', $fifteenDaysAgo)
                            ->sum('amount');
                        
                        return 'Total (' . $total . ')';
                    }),

                TextColumn::make('tyfcb')
                    ->label('Total TYFCB (Last 15 Days)')
                    ->getStateUsing(function (Chapter $record): int {
                        $fifteenDaysAgo = Carbon::now()->subDays(15)->startOfDay();
                        
                        $userIds = User::where('chapter_id', $record->id)->where('active', 1)->pluck('id');

                        return ThankYou::whereIn('from', $userIds)
                            ->where('created_at', '>=', $fifteenDaysAgo)
                            ->count();
                    }),

                TextColumn::make('referrals')
                    ->label('Total Referrals (Last 15 Days)')
                    ->getStateUsing(function (Chapter $record): int {
                        $fifteenDaysAgo = Carbon::now()->subDays(15)->startOfDay();
                        
                        $userIds = User::where('chapter_id', $record->id)->where('active', 1)->pluck('id');

                        return Referral::whereIn('from', $userIds)
                            ->where('created_at', '>=', $fifteenDaysAgo)
                            ->count();
                    }),

                TextColumn::make('oto_requests')
                    ->label('Total One to One Req. (Last 15 Days)')
                    ->getStateUsing(function (Chapter $record): int {
                        $fifteenDaysAgo = Carbon::now()->subDays(15)->startOfDay();
                        
                        $userIds = User::where('chapter_id', $record->id)->where('active', 1)->pluck('id');

                        return OneToOne::whereIn('user_id', $userIds)
                            ->where('created_at', '>=', $fifteenDaysAgo)
                            ->count();
                    }),
            ])
        ;
    }
}