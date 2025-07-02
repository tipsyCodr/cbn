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
use Carbon\Carbon;

class ChapterWidget extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';
    protected static ?int $sort = 2;

    protected function getTableHeading(): string
    {
        return 'Chapters';
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Chapter::query())
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
                            ->when($data['from'] ?? null, fn($query, $date) => $query->whereDate('created_at', '>=', Carbon::parse($date)->startOfDay()))
                            ->when($data['until'] ?? null, fn($query, $date) => $query->whereDate('created_at', '<=', Carbon::parse($date)->endOfDay()));
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
                    ->getStateUsing(function (Chapter $record, $state, $column) {
                        $livewire = method_exists($column->getTable(), 'getLivewire') ? $column->getTable()->getLivewire() : null;
                        $filters = $livewire?->filters['created_at'] ?? [];

                        $from = !empty($filters['from']) ? Carbon::parse($filters['from'])->startOfDay() : null;
                        $until = !empty($filters['until']) ? Carbon::parse($filters['until'])->endOfDay() : null;

                        $userIds = User::where('chapter_id', $record->id)->where('active', 1)->pluck('id')->toArray();

                        $query = ThankYou::whereIn('from', $userIds);
                        

                        if ($from)
                            $query->where('created_at', '>=', $from);
                        if ($until)
                            $query->where('created_at', '<=', $until);

                        return '₹ ' . number_format($query->sum('amount'), 2);
                    })
                    ->sortable(),


TextColumn::make('tyfcb')
                    ->getStateUsing(function (Chapter $record, $state, $column) {
                        $livewire = method_exists($column->getTable(), 'getLivewire') ? $column->getTable()->getLivewire() : null;
                        $filters = $livewire?->filters['created_at'] ?? [];

                        $from = !empty($filters['from']) ? Carbon::parse($filters['from'])->startOfDay() : null;
                        $until = !empty($filters['until']) ? Carbon::parse($filters['until'])->endOfDay() : null;

                        $userIds = User::where('chapter_id', $record->id)->where('active', 1)->pluck('id')->toArray();

                        $query = ThankYou::whereIn('from', $userIds);
                        if ($from)
                            $query->where('created_at', '>=', $from);
                        if ($until)
                            $query->where('created_at', '<=', $until);

                        return $query->count();
                    })
                    ->sortable(),


TextColumn::make('referrals')
                    ->getStateUsing(function (Chapter $record, $state, $column) {
                        $livewire = method_exists($column->getTable(), 'getLivewire') ? $column->getTable()->getLivewire() : null;
                        $filters = $livewire?->filters['created_at'] ?? [];

                        $from = !empty($filters['from']) ? Carbon::parse($filters['from'])->startOfDay() : null;
                        $until = !empty($filters['until']) ? Carbon::parse($filters['until'])->endOfDay() : null;

                        $userIds = User::where('chapter_id', $record->id)->where('active', 1)->pluck('id')->toArray();

                        $query = Referral::whereIn('from', $userIds);
                        if ($from)
                            $query->where('created_at', '>=', $from);
                        if ($until)
                            $query->where('created_at', '<=', $until);

                        return $query->count();
                    })
                    ->sortable(),


TextColumn::make('oto_requests')
                    ->getStateUsing(function (Chapter $record, $state, $column) {
                        $livewire = method_exists($column->getTable(), 'getLivewire') ? $column->getTable()->getLivewire() : null;
                        $filters = $livewire?->filters['created_at'] ?? [];

                        $from = !empty($filters['from']) ? Carbon::parse($filters['from'])->startOfDay() : null;
                        $until = !empty($filters['until']) ? Carbon::parse($filters['until'])->endOfDay() : null;

                        $userIds = User::where('chapter_id', $record->id)->where('active', 1)->pluck('id')->toArray();

                        $query = OneToOne::whereIn('user_id', $userIds);
                        if ($from)
                            $query->where('created_at', '>=', $from);
                        if ($until)
                            $query->where('created_at', '<=', $until);

                        return $query->count();
                    })
                    ->sortable(),

                TextColumn::make('created_at')->dateTime()->sortable(),
            ]);
    }
}
