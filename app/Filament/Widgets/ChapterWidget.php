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

class ChapterWidget extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 2;

    protected function getTableHeading(): string
    {
        return 'Chapters';
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
                        return $query; // no need to filter Chapter::query(), only used for displaying filter indicators
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
                    ->getStateUsing(function (Chapter $record): string {
                        $filters = request()->input('tableFilters') ?? [];
                        $from = !empty($filters['created_at']['from']) ? Carbon::parse($filters['created_at']['from'])->startOfDay() : null;
                        $until = !empty($filters['created_at']['until']) ? Carbon::parse($filters['created_at']['until'])->endOfDay() : null;

                        $userIds = User::where('chapter_id', $record->id)->where('active', 1)->pluck('id');

                        $query = ThankYou::whereIn('from', $userIds);

                        if ($from) $query->where('created_at', '>=', $from);
                        if ($until) $query->where('created_at', '<=', $until);

                        return '₹ ' . number_format($query->sum('amount'), 2);
                    }),

                TextColumn::make('tyfcb')
                    ->label('Total TYFCB')
                    ->getStateUsing(function (Chapter $record): int {
                        $filters = request()->input('tableFilters') ?? [];
                        $from = !empty($filters['created_at']['from']) ? Carbon::parse($filters['created_at']['from'])->startOfDay() : null;
                        $until = !empty($filters['created_at']['until']) ? Carbon::parse($filters['created_at']['until'])->endOfDay() : null;

                        $userIds = User::where('chapter_id', $record->id)->where('active', 1)->pluck('id');

                        $query = ThankYou::whereIn('from', $userIds);
                        if ($from) $query->where('created_at', '>=', $from);
                        if ($until) $query->where('created_at', '<=', $until);

                        return $query->count();
                    }),

                TextColumn::make('referrals')
                    ->label('Total Referrals')
                    ->getStateUsing(function (Chapter $record): int {
                        $filters = request()->input('tableFilters') ?? [];
                        $from = !empty($filters['created_at']['from']) ? Carbon::parse($filters['created_at']['from'])->startOfDay() : null;
                        $until = !empty($filters['created_at']['until']) ? Carbon::parse($filters['created_at']['until'])->endOfDay() : null;

                        $userIds = User::where('chapter_id', $record->id)->where('active', 1)->pluck('id');

                        $query = Referral::whereIn('from', $userIds);
                        if ($from) $query->where('created_at', '>=', $from);
                        if ($until) $query->where('created_at', '<=', $until);

                        return $query->count();
                    }),

                TextColumn::make('oto_requests')
                    ->label('Total One to One Req.')
                    ->getStateUsing(function (Chapter $record): int {
                        $filters = request()->input('tableFilters') ?? [];
                        $from = !empty($filters['created_at']['from']) ? Carbon::parse($filters['created_at']['from'])->startOfDay() : null;
                        $until = !empty($filters['created_at']['until']) ? Carbon::parse($filters['created_at']['until'])->endOfDay() : null;

                        $userIds = User::where('chapter_id', $record->id)->where('active', 1)->pluck('id');

                        $query = OneToOne::whereIn('user_id', $userIds);
                        if ($from) $query->where('created_at', '>=', $from);
                        if ($until) $query->where('created_at', '<=', $until);

                        return $query->count();
                    }),

                TextColumn::make('created_at')->dateTime()->sortable(),
            ]);
    }
}
