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
            ->defaultSort('total_requests', 'desc')
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('total_requests')->sortable(),
                TextColumn::make('total_tyfcb')->label('Total TYFCB')->sortable(),
                TextColumn::make('total_referrals')->label('Total Referrals')->sortable(),
                TextColumn::make('total_oto')->label('Total One To One')->sortable(),
            ]);
    }
}
