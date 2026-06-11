<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateRecurringMeetings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'meetings:generate-recurring';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate upcoming occurrences for recurring meetings';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $templates = \App\Models\Meeting::where('is_recurring', true)->get();
        $startDate = \Carbon\Carbon::now();
        $endDate = \Carbon\Carbon::now()->addDays(30);

        foreach ($templates as $template) {
            if (!$template->interval) continue;

            $templateDate = \Carbon\Carbon::parse($template->date);

            $currentDate = $startDate->copy();
            
            while ($currentDate->lte($endDate)) {
                $shouldGenerate = false;

                if ($template->interval === 'weekly') {
                    if ($currentDate->dayOfWeek == $templateDate->dayOfWeek) {
                        $shouldGenerate = true;
                    }
                } elseif ($template->interval === 'monthly') {
                    $lastDayOfMonth = $currentDate->copy()->endOfMonth()->day;
                    $targetDay = min($templateDate->day, $lastDayOfMonth);
                    if ($currentDate->day == $targetDay) {
                        $shouldGenerate = true;
                    }
                } elseif ($template->interval === 'yearly') {
                    if ($currentDate->month == $templateDate->month && $currentDate->day == $templateDate->day) {
                        $shouldGenerate = true;
                    }
                }

                if ($shouldGenerate) {
                    $exists = \App\Models\Meeting::where('parent_id', $template->id)
                                ->whereDate('date', $currentDate->format('Y-m-d'))
                                ->exists();
                    
                    // Also avoid creating occurrence if the template itself falls on this exact date and is treated as the first occurrence
                    if ($currentDate->format('Y-m-d') === $template->date) {
                         $exists = true;
                    }

                    if (!$exists) {
                        $occurrence = $template->replicate();
                        $occurrence->is_recurring = false;
                        $occurrence->interval = null;
                        $occurrence->parent_id = $template->id;
                        $occurrence->date = $currentDate->format('Y-m-d');
                        $occurrence->save();
                        
                        $this->info("Created occurrence for {$template->name} on {$occurrence->date}");
                    }
                }

                $currentDate->addDay();
            }
        }
        $this->info('Recurring meetings generated.');
    }
}
