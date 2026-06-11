<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Meeting;
use Carbon\Carbon;

use Illuminate\Support\Facades\Schema;

class MeetingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing meetings
        Schema::disableForeignKeyConstraints();
        Meeting::truncate();
        Schema::enableForeignKeyConstraints();
        
        // Create a past meeting
        Meeting::create([
            'name' => 'Monthly Chapter Meeting',
            'date' => Carbon::now()->subDays(15)->format('Y-m-d'),
            'start_time' => '10:00:00',
            'end_time' => '12:00:00',
            'tyfcb' => '₹ 50,000',
            'visitors' => '5',
            'speakers' => 'John Doe, Jane Smith'
        ]);
        
        // Create a meeting for today
        Meeting::create([
            'name' => 'Weekly Networking Session',
            'date' => Carbon::now()->format('Y-m-d'),
            'start_time' => '14:00:00',
            'end_time' => '16:00:00',
            'tyfcb' => '₹ 25,000',
            'visitors' => '3',
            'speakers' => 'Bob Johnson'
        ]);
        
        // Create upcoming meetings
        Meeting::create([
            'name' => 'Leadership Training Workshop',
            'date' => Carbon::now()->addDays(7)->format('Y-m-d'),
            'start_time' => '09:00:00',
            'end_time' => '17:00:00',
            'tyfcb' => '₹ 75,000',
            'visitors' => '10',
            'speakers' => 'Michael Scott, Dwight Schrute'
        ]);
        
        Meeting::create([
            'name' => 'Annual General Meeting',
            'date' => Carbon::now()->addDays(30)->format('Y-m-d'),
            'start_time' => '10:00:00',
            'end_time' => '18:00:00',
            'tyfcb' => '₹ 150,000',
            'visitors' => '20',
            'speakers' => 'CEO, CFO, Chapter President'
        ]);
        
        Meeting::create([
            'name' => 'Business Growth Symposium',
            'date' => Carbon::now()->addDays(60)->format('Y-m-d'),
            'start_time' => '09:30:00',
            'end_time' => '16:30:00',
            'tyfcb' => '₹ 100,000',
            'visitors' => '15',
            'speakers' => 'Industry Experts'
        ]);
        
        $this->command->info('Test meeting data created successfully.');
    }
} 