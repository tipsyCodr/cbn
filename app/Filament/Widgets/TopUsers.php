<?php

namespace App\Filament\Widgets;

use App\Models\Chapter;
use App\Models\User;
use App\Exports\TopUsersExport;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Filament\Tables\Actions\Action;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

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
                    ->label(' Revenue Received')
                    ->getStateUsing(function (User $record, $livewire): string {
                        return '₹' . $this->formatIndianCurrency($this->getTotalBusiness($record, $livewire));
                    })
                    ->sortable(query: function (Builder $query, string $direction) use ($table): Builder {
                        return $this->applySortingWithFilters($query, $direction, 'total_business');
                    }),

                TextColumn::make('revenue_received')
                    ->label('Business Given')
                    ->getStateUsing(function (User $record, $livewire): string {
                        return '₹' . $this->formatIndianCurrency($this->getRevenueReceived($record, $livewire));
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
            ->headerActions([
                Action::make('export')
                    ->label('Export to Excel')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action('exportToExcel')
                    ->color('success'),
            ])
            ->defaultSort('total_requests', 'desc');
    }

    public function exportToExcel()
    {
        $fileName = 'top-users-' . Carbon::now()->format('Y-m-d-H-i-s') . '.xlsx';

        return Excel::download(
            new TopUsersExport($this->getFilterDates(null)),
            $fileName
        );
    }

    public function getExportData(): Collection
    {
        [$from, $until] = $this->getFilterDates(null);
        $users = User::where('role', 'user')->get();

        $data = $users->map(function (User $user) use ($from, $until) {
            return [
                'name' => $user->name,
                'total_requests' => $this->getTotalRequestsWithDates($user, $from, $until),
                'total_tyfcb' => $this->getTotalTYFCBWithDates($user, $from, $until),
                'revenue_received' => $this->getTotalBusinessWithDates($user, $from, $until),
                'business_given' => $this->getRevenueReceivedWithDates($user, $from, $until),
                'total_referrals' => $this->getTotalReferralsWithDates($user, $from, $until),
                'total_oto' => $this->getTotalOTOWithDates($user, $from, $until),
            ];
        });

        return $data;
    }

    private function getTotalRequestsWithDates(User $record, ?Carbon $from, ?Carbon $until): int
    {
        return $this->getTotalTYFCBWithDates($record, $from, $until) +
            $this->getTotalReferralsWithDates($record, $from, $until) +
            $this->getTotalOTOWithDates($record, $from, $until);
    }

    private function getTotalTYFCBWithDates(User $record, ?Carbon $from, ?Carbon $until): int
    {
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

    private function getTotalBusinessWithDates(User $record, ?Carbon $from, ?Carbon $until): float
    {
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

    private function getRevenueReceivedWithDates(User $record, ?Carbon $from, ?Carbon $until): float
    {
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

    private function getTotalReferralsWithDates(User $record, ?Carbon $from, ?Carbon $until): int
    {
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

    private function getTotalOTOWithDates(User $record, ?Carbon $from, ?Carbon $until): int
    {
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

    private function getFilterDates($livewire): array
    {
        $filter = $this->getTable()?->getFilter('created_at');
        $state = $filter?->getState() ?? [];

        $from = !empty($state['from']) ? Carbon::parse($state['from'])->startOfDay() : null;
        $until = !empty($state['until']) ? Carbon::parse($state['until'])->endOfDay() : null;

        return [$from, $until];
    }

    private function formatIndianCurrency(float $number): string
    {
        $number = round($number, 2);
        $isNegative = $number < 0;
        $number = abs($number);

        $decimal = '';
        if (strpos((string) $number, '.') !== false) {
            $parts = explode('.', (string) $number);
            $number = (float) $parts[0];
            $decimal = '.' . str_pad($parts[1] ?? '00', 2, '0');
        } else {
            $decimal = '.00';
        }

        $number = (int) $number;

        if ($number < 1000) {
            return ($isNegative ? '-' : '') . $number . $decimal;
        }

        // Last 3 digits
        $lastThree = substr((string) $number, -3);
        $remaining = substr((string) $number, 0, -3);

        // Add commas for every 2 digits in the remaining part
        if ($remaining !== '' && $remaining !== false) {
            $remaining = preg_replace('/\B(?=(\d{2})+(?!\d))/', ',', $remaining);
            return ($isNegative ? '-' : '') . $remaining . ',' . $lastThree . $decimal;
        }

        return ($isNegative ? '-' : '') . $lastThree . $decimal;
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
        $filter = $this->getTable()->getFilter('created_at');
        $state = $filter?->getState() ?? [];

        $from = !empty($state['from']) ? Carbon::parse($state['from'])->startOfDay() : null;
        $until = !empty($state['until']) ? Carbon::parse($state['until'])->endOfDay() : null;

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
