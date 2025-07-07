<?php

namespace App\Filament\Widgets;

use App\Models\Chapter;
use App\Models\User;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

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
                        $total = $this->getTotalAmount($record);
                        return 'Total (₹' . number_format($total, 2) . ')';
                    }),

                TextColumn::make('tyfcb')
                    ->label('Total TYFCB (Last 15 Days)')
                    ->getStateUsing(function (Chapter $record): int {
                        return $this->getTotalTYFCB($record);
                    }),

                TextColumn::make('referrals')
                    ->label('Total Referrals (Last 15 Days)')
                    ->getStateUsing(function (Chapter $record): int {
                        return $this->getTotalReferrals($record);
                    }),

                TextColumn::make('oto_requests')
                    ->label('Total One to One Req. (Last 15 Days)')
                    ->getStateUsing(function (Chapter $record): int {
                        return $this->getTotalOTORequests($record);
                    }),
            ]);
    }

    private function getFifteenDaysAgo(): Carbon
    {
        return Carbon::now()->subDays(14)->startOfDay();
    }

    private function getChapterUserIds(Chapter $record): array
    {
        return User::where('chapter_id', $record->id)
            ->where('active', 1)
            ->pluck('id')
            ->toArray();
    }

    private function getTotalAmount(Chapter $record): float
    {
        $fifteenDaysAgo = $this->getFifteenDaysAgo();
        $userIds = $this->getChapterUserIds($record);

        if (empty($userIds)) {
            return 0.0;
        }

        return DB::table('tyfcb_requests')
            ->whereIn('from', $userIds)
            ->where('active', 1)
            ->where('created_at', '>=', $fifteenDaysAgo)
            ->sum('amount') ?? 0.0;
    }

    private function getTotalTYFCB(Chapter $record): int
    {
        $fifteenDaysAgo = $this->getFifteenDaysAgo();
        $userIds = $this->getChapterUserIds($record);

        if (empty($userIds)) {
            return 0;
        }

        return DB::table('tyfcb_requests')
            ->whereIn('from', $userIds)
            ->where('active', 1)
            ->where('created_at', '>=', $fifteenDaysAgo)
            ->count();
    }

    private function getTotalReferrals(Chapter $record): int
    {
        $fifteenDaysAgo = $this->getFifteenDaysAgo();
        $userIds = $this->getChapterUserIds($record);

        if (empty($userIds)) {
            return 0;
        }

        return DB::table('referrals')
            ->whereIn('from', $userIds)
            ->where('created_at', '>=', $fifteenDaysAgo)
            ->count();
    }

    private function getTotalOTORequests(Chapter $record): int
    {
        $fifteenDaysAgo = $this->getFifteenDaysAgo();
        $userIds = $this->getChapterUserIds($record);

        if (empty($userIds)) {
            return 0;
        }

        return DB::table('oto_requests')
            ->whereIn('user_id', $userIds)
            ->where('active', 1)
            ->where('created_at', '>=', $fifteenDaysAgo)
            ->count();
    }
}