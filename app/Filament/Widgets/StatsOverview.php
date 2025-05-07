<?php

namespace App\Filament\Widgets;

use App\Models\Meeting;
use App\Models\OneToOne;
use App\Models\Referral;
use App\Models\ThankYou;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    
    protected function getStats(): array
    {
        $now = Carbon::now();

        $totalMeetings = Meeting::count();
        $completedMeetings = Meeting::where('date', '<', $now)->count();
        $upcomingMeetings = Meeting::where('date', '>=', $now)->count();

        return [
            Card::make('Total Referrals', Referral::count())
                ->description('All time referrals')
                ->descriptionIcon('heroicon-o-users')
                ->color('success'),

            Card::make('One To One Meetings', OneToOne::count())
            ->description('One to One Meeting Conducted')
            ->descriptionIcon('heroicon-o-check-circle')
            ->color('success'),

            Card::make('Thank You Request Sent', ThankYou::count())
                ->descriptionIcon('heroicon-o-heart')
                ->description('No. of Users who sent Thank You ')
                ->color('muted'),

                Card::make('Total Meetings', $totalMeetings)
                ->description('Meetings added')
                ->descriptionIcon('heroicon-o-calendar')
                ->color('primary'),
    
            Card::make('Completed Meetings', $completedMeetings)
                ->description('Meetings before now')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success'),
    
            Card::make('Upcoming Meetings', $upcomingMeetings)
                ->description('Scheduled in future')
                ->descriptionIcon('heroicon-o-clock')
                ->color('warning'),
                
        ];
    }
}
