<?php

namespace App\Filament\Widgets;

use App\Models\Chapter;
use App\Models\User;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class TopUsers extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';
    protected static ?int $sort = 3;

    protected function getTableHeading(): string
    {
        return 'Top Users';
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(function (): Builder {
                return User::query()->where('role', 'user');
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
                TextColumn::make('name')
                    ->label('Member')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('total_requests')
                    ->label('Total Requests')
                    ->getStateUsing(function (User $record, $livewire): int {
                        return $this->getTotalRequests($record, $livewire);
                    })
                    ->sortable(query: function (Builder $query, string $direction) use ($table): Builder {
                        return $this->applySortingWithFilters($query, $direction, 'total_requests');
                    }),

                TextColumn::make('total_tyfcb')
                    ->label('Total TYFCB')
                    ->getStateUsing(function (User $record, $livewire): int {
                        return $this->getTotalTYFCB($record, $livewire);
                    })
                    ->sortable(query: function (Builder $query, string $direction) use ($table): Builder {
                        return $this->applySortingWithFilters($query, $direction, 'total_tyfcb');
                    }),

                TextColumn::make('total_business')
                    ->label('Total Business')
                    ->getStateUsing(function (User $record, $livewire): string {
                        return '₹' . number_format($this->getTotalBusiness($record, $livewire), 2);
                    })
                    ->sortable(query: function (Builder $query, string $direction) use ($table): Builder {
                        return $this->applySortingWithFilters($query, $direction, 'total_business');
                    }),

                TextColumn::make('revenue_received')
                    ->label('Revenue Received')
                    ->getStateUsing(function (User $record, $livewire): string {
                        return '₹' . number_format($this->getRevenueReceived($record, $livewire), 2);
                    })
                    ->sortable(query: function (Builder $query, string $direction) use ($table): Builder {
                        return $this->applySortingWithFilters($query, $direction, 'revenue_received');
                    }),

                TextColumn::make('total_referrals')
                    ->label('Total Referrals')
                    ->getStateUsing(function (User $record, $livewire): int {
                        return $this->getTotalReferrals($record, $livewire);
                    })
                    ->sortable(query: function (Builder $query, string $direction) use ($table): Builder {
                        return $this->applySortingWithFilters($query, $direction, 'total_referrals');
                    }),

                TextColumn::make('total_oto')
                    ->label('Total One To One')
                    ->getStateUsing(function (User $record, $livewire): int {
                        return $this->getTotalOTO($record, $livewire);
                    })
                    ->sortable(query: function (Builder $query, string $direction) use ($table): Builder {
                        return $this->applySortingWithFilters($query, $direction, 'total_oto');
                    }),
            ])
            ->defaultSort('total_requests', 'desc');
    }

    private function getFilterDates($livewire): array
    {
        $filters = $livewire->tableFilters ?? [];
        $dateFilter = $filters['created_at'] ?? [];

        $from = !empty($dateFilter['from']) ? Carbon::parse($dateFilter['from'])->startOfDay() : null;
        $until = !empty($dateFilter['until']) ? Carbon::parse($dateFilter['until'])->endOfDay() : null;

        return [$from, $until];
    }

    private function getTotalTYFCB(User $record, $livewire): int
    {
        [$from, $until] = $this->getFilterDates($livewire);

        $query = DB::table('tyfcb_requests')
            ->where('from', $record->id)
            ->where('active', 1);

        if ($from) {
            $query->where('created_at', '>=', $from);
        }
        if ($until) {
            $query->where('created_at', '<=', $until);
        }

        return $query->count();
    }

    private function getTotalBusiness(User $record, $livewire): float
    {
        [$from, $until] = $this->getFilterDates($livewire);

        $query = DB::table('tyfcb_requests')
            ->where('from', $record->id)
            ->where('active', 1);

        if ($from) {
            $query->where('created_at', '>=', $from);
        }
        if ($until) {
            $query->where('created_at', '<=', $until);
        }

        return (float) $query->sum('amount');
    }

    private function getRevenueReceived(User $record, $livewire): float
    {
        [$from, $until] = $this->getFilterDates($livewire);

        $query = DB::table('tyfcb_requests')
            ->where('to', $record->id)
            ->where('active', 1);

        if ($from) {
            $query->where('created_at', '>=', $from);
        }
        if ($until) {
            $query->where('created_at', '<=', $until);
        }

        return (float) $query->sum('amount');
    }

    private function getTotalReferrals(User $record, $livewire): int
    {
        [$from, $until] = $this->getFilterDates($livewire);

        $query = DB::table('referrals')
            ->where('from', $record->id);

        if ($from) {
            $query->where('created_at', '>=', $from);
        }
        if ($until) {
            $query->where('created_at', '<=', $until);
        }

        return $query->count();
    }

    private function getTotalOTO(User $record, $livewire): int
    {
        [$from, $until] = $this->getFilterDates($livewire);

        $query = DB::table('oto_requests')
            ->where('user_id', $record->id)
            ->where('active', 1);

        if ($from) {
            $query->where('created_at', '>=', $from);
        }
        if ($until) {
            $query->where('created_at', '<=', $until);
        }

        return $query->count();
    }

    private function getTotalRequests(User $record, $livewire): int
    {
        return $this->getTotalTYFCB($record, $livewire) +
            $this->getTotalReferrals($record, $livewire) +
            $this->getTotalOTO($record, $livewire);
    }

    private function applySortingWithFilters(Builder $query, string $direction, string $column): Builder
    {
        // Get current filters from the widget
        $filters = $this->tableFilters ?? [];
        $dateFilter = $filters['created_at'] ?? [];

        $from = !empty($dateFilter['from']) ? Carbon::parse($dateFilter['from'])->startOfDay() : null;
        $until = !empty($dateFilter['until']) ? Carbon::parse($dateFilter['until'])->endOfDay() : null;

        // Build the date condition for subqueries
        $dateCondition = '';
        $bindings = [];

        if ($from) {
            $dateCondition .= ' AND created_at >= ?';
            $bindings[] = $from;
        }
        if ($until) {
            $dateCondition .= ' AND created_at <= ?';
            $bindings[] = $until;
        }

        switch ($column) {
            case 'total_tyfcb':
                return $query->selectRaw('users.*, (SELECT COUNT(*) FROM tyfcb_requests WHERE tyfcb_requests.from = users.id AND active = 1' . $dateCondition . ') as total_tyfcb_count', $bindings)
                    ->orderBy('total_tyfcb_count', $direction);

            case 'total_referrals':
                return $query->selectRaw('users.*, (SELECT COUNT(*) FROM referrals WHERE referrals.from = users.id' . $dateCondition . ') as total_referrals_count', $bindings)
                    ->orderBy('total_referrals_count', $direction);

            case 'total_oto':
                return $query->selectRaw('users.*, (SELECT COUNT(*) FROM oto_requests WHERE oto_requests.user_id = users.id AND active = 1' . $dateCondition . ') as total_oto_count', $bindings)
                    ->orderBy('total_oto_count', $direction);

            case 'total_business':
                return $query->selectRaw('users.*, (SELECT COALESCE(SUM(amount), 0) FROM tyfcb_requests WHERE tyfcb_requests.from = users.id AND active = 1' . $dateCondition . ') as total_business_amount', $bindings)
                    ->orderBy('total_business_amount', $direction);

            case 'revenue_received':
                return $query->selectRaw('users.*, (SELECT COALESCE(SUM(amount), 0) FROM tyfcb_requests WHERE tyfcb_requests.to = users.id AND active = 1' . $dateCondition . ') as revenue_received_amount', $bindings)
                    ->orderBy('revenue_received_amount', $direction);

            case 'total_requests':
            default:
                return $query->selectRaw('users.*, 
                    ((SELECT COUNT(*) FROM tyfcb_requests WHERE tyfcb_requests.from = users.id AND active = 1' . $dateCondition . ') +
                     (SELECT COUNT(*) FROM referrals WHERE referrals.from = users.id' . $dateCondition . ') +
                     (SELECT COUNT(*) FROM oto_requests WHERE oto_requests.user_id = users.id AND active = 1' . $dateCondition . ')) as total_requests_count',
                    array_merge($bindings, $bindings, $bindings)
                )
                    ->orderBy('total_requests_count', $direction);
        }
    }
}