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
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
class TopUsers extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 3;


    protected function getTableHeading(): string
    {
        return 'Top Users';
    }
    public function table(Table $table): Table
    {
        return $table
            ->query(
                User::query()
                    ->where('role', 'user')
                    ->selectRaw('users.*, 
                        (SELECT COUNT(*) FROM tyfcb_requests WHERE tyfcb_requests.from = users.id) as total_tyfcb,
                        (SELECT COUNT(*) FROM referrals WHERE referrals.from = users.id) as total_referrals,
                        (SELECT COUNT(*) FROM oto_requests WHERE oto_requests.user_id = users.id) as total_oto,
                        ((SELECT COUNT(*) FROM tyfcb_requests WHERE tyfcb_requests.from = users.id) +
                         (SELECT COUNT(*) FROM referrals WHERE referrals.from = users.id) +
                         (SELECT COUNT(*) FROM oto_requests WHERE oto_requests.user_id = users.id)) as total_requests'
                    )
                    ->orderByDesc('total_requests')
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
            ->defaultSort('total_requests', 'desc')
            ->columns([
                TextColumn::make('name')->label('Member')->searchable()->sortable(),
                TextColumn::make('total_requests')->label('Total Requests')->sortable(),
                TextColumn::make('total_tyfcb')->label('Total TYFCB')->sortable(),
                TextColumn::make('total_referrals')->label('Total Referrals')->sortable(),
                TextColumn::make('total_oto')->label('Total One To One')->sortable(),
            ]);
    }
}
