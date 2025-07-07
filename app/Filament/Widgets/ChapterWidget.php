<?php

namespace App\Filament\Widgets;

use App\Models\Chapter;
use App\Models\Referral;
use App\Models\ThankYou;
use App\Models\OneToOne;
use App\Models\User;
use Carbon\Carbon;
use DB;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DatePicker;

class ChapterWidget extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 2;

    protected function getTableHeading(): string
    {
        return 'Total Requests Till Now';
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(function (): Builder {
                return Chapter::query();
            })
            ->filters([
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('from')->label('From'),
                        DatePicker::make('until')->label('Until'),
                    ])
                    ->query(function ($query, array $data) {
                        // We don't need to modify the main query since we're handling filtering in columns
                        return $query;
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];

                        if (!empty($data['from'])) {
                            $indicators[] = 'From: ' . Carbon::parse($data['from'])->format('M d, Y');
                        }

                        if (!empty($data['until'])) {
                            $indicators[] = 'Until: ' . Carbon::parse($data['until'])->format('M d, Y');
                        }

                        return $indicators;
                    }),
            ])
            ->columns([
                TextColumn::make('id')->sortable(),

                TextColumn::make('title')
                    ->label('Chapters')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('total_amount')
                    ->label('Total Amount')
                    ->getStateUsing(function (Chapter $record, $livewire): string {
                        return $this->getTotalAmount($record, $livewire);
                    }),

                TextColumn::make('tyfcb')
                    ->label('Total TYFCB')
                    ->getStateUsing(function (Chapter $record, $livewire): int {
                        return $this->getTotalTYFCB($record, $livewire);
                    }),

                TextColumn::make('referrals')
                    ->label('Total Referrals')
                    ->getStateUsing(function (Chapter $record, $livewire): int {
                        return $this->getTotalReferrals($record, $livewire);
                    }),

                TextColumn::make('oto_requests')
                    ->label('Total One to One Req.')
                    ->getStateUsing(function (Chapter $record, $livewire): int {
                        return $this->getTotalOTORequests($record, $livewire);
                    }),

                TextColumn::make('created_at')->dateTime()->sortable(),
            ]);
    }

    private function getFilterDates($livewire): array
    {
        $filters = $livewire->tableFilters ?? [];
        $dateFilter = $filters['created_at'] ?? [];
        
        $from = !empty($dateFilter['from']) ? Carbon::parse($dateFilter['from'])->startOfDay() : null;
        $until = !empty($dateFilter['until']) ? Carbon::parse($dateFilter['until'])->endOfDay() : null;

        return [$from, $until];
    }

    private function getChapterUserIds(Chapter $record): array
    {
        return User::where('chapter_id', $record->id)
            ->where('active', 1)
            ->pluck('id')
            ->toArray();
    }

    private function getTotalAmount(Chapter $record, $livewire): string
    {
        [$from, $until] = $this->getFilterDates($livewire);
        $userIds = $this->getChapterUserIds($record);

        if (empty($userIds)) {
            return '₹ 0.00';
        }

        $query = DB::table('tyfcb_requests')
            ->whereIn('from', $userIds)
            ->where('active', 1);

        if ($from) {
            $query->where('created_at', '>=', $from);
        }
        if ($until) {
            $query->where('created_at', '<=', $until);
        }

        $total = $query->sum('amount') ?? 0;
        return '₹ ' . number_format($total, 2);
    }

    private function getTotalTYFCB(Chapter $record, $livewire): int
    {
        [$from, $until] = $this->getFilterDates($livewire);
        $userIds = $this->getChapterUserIds($record);

        if (empty($userIds)) {
            return 0;
        }

        $query = DB::table('tyfcb_requests')
            ->whereIn('from', $userIds)
            ->where('active', 1);

        if ($from) {
            $query->where('created_at', '>=', $from);
        }
        if ($until) {
            $query->where('created_at', '<=', $until);
        }

        return $query->count();
    }

    private function getTotalReferrals(Chapter $record, $livewire): int
    {
        [$from, $until] = $this->getFilterDates($livewire);
        $userIds = $this->getChapterUserIds($record);

        if (empty($userIds)) {
            return 0;
        }

        $query = DB::table('referrals')
            ->whereIn('from', $userIds);

        if ($from) {
            $query->where('created_at', '>=', $from);
        }
        if ($until) {
            $query->where('created_at', '<=', $until);
        }

        return $query->count();
    }

    private function getTotalOTORequests(Chapter $record, $livewire): int
    {
        [$from, $until] = $this->getFilterDates($livewire);
        $userIds = $this->getChapterUserIds($record);

        if (empty($userIds)) {
            return 0;
        }

        $query = DB::table('oto_requests')
            ->whereIn('user_id', $userIds)
            ->where('active', 1);

        if ($from) {
            $query->where('created_at', '>=', $from);
        }
        if ($until) {
            $query->where('created_at', '<=', $until);
        }

        return $query->count();
    }
}
