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
            ->query(
                User::query()->where('role', 'user')
            )
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('total_requests')->sortable()
                    ->getStateUsing(function (User $record): int {
                        $userIds = User::where('chapter_id', $record->chapter_id)
                            ->where('active', 1)
                            ->where('role', 'user')
                            ->pluck('id')
                            ->toArray();

                        if (empty($userIds)) {
                            return 0;
                        }

                        $tyfcb = ThankYou::whereIn('from', $userIds)->count();
                        $referral = Referral::whereIn('from', $userIds)->count();
                        $oto = OneToOne::whereIn('user_id', $userIds)->count();

                        return $tyfcb + $referral + $oto;
                    }),
                TextColumn::make('total_tyfcb')->label('Total TYFCB')->sortable()
                    ->getStateUsing(function (User $record): int {
                        $userIds = User::where('chapter_id', $record->chapter_id)
                            ->where('active', 1)
                            ->pluck('id')
                            ->toArray();

                        if (empty($userIds)) {
                            return 0;
                        }

                        return ThankYou::whereIn('from', $userIds)->count();
                    }),

                TextColumn::make('total_referrals')->label('Total Referrals')->sortable()
                    ->getStateUsing(function (User $record): int {
                        $userIds = User::where('chapter_id', $record->chapter_id)
                            ->where('active', 1)
                            ->pluck('id')
                            ->toArray();

                        if (empty($userIds)) {
                            return 0;
                        }

                        return Referral::whereIn('from', $userIds)->count();
                    }),

                TextColumn::make('total_oto')->label('Total One To One')->sortable()
                    ->getStateUsing(function (User $record): int {
                        $userIds = User::where('chapter_id', $record->chapter_id)
                            ->where('active', 1)
                            ->pluck('id')
                            ->toArray();

                        if (empty($userIds)) {
                            return 0;
                        }

                        return OneToOne::whereIn('user_id', $userIds)->count();
                    }),


            ]);


    }
}
