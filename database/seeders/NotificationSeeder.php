<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some users to create notifications for
        $users = User::take(5)->get();
        
        if ($users->isEmpty()) {
            $this->command->info('No users found. Please run the user seeder first.');
            return;
        }
        
        // Generate some example notifications for testing
        foreach ($users as $user) {
            // Skip the first user (usually admin)
            if ($user->id === 1) continue;
            
            // Create a referral notification
            Notification::create([
                'from' => 21, // From the first user (admin)
                'to' => $user->id,
                'type' => 'referral',
                'related_id' => rand(1, 10),
                'title' => 'New Referral Received',
                'message' => 'You have received a new referral from Admin. Please check your referrals.',
                'read' => false,
                'cleared' => false,
                'created_at' => Carbon::now()->subDays(rand(0, 14)),
                'updated_at' => Carbon::now(),
            ]);
            
            // Create a thank you notification
            Notification::create([
                'from' => 21, // From the first user (admin)
                'to' => $user->id,
                'type' => 'tyfcb',
                'related_id' => rand(1, 10),
                'title' => 'New Thank You Received',
                'message' => 'You have received a thank you of ₹5000 from Admin for new business.',
                'read' => rand(0, 1) === 1,
                'cleared' => false,
                'created_at' => Carbon::now()->subDays(rand(0, 10)),
                'updated_at' => Carbon::now(),
            ]);
            
            // Create a one-to-one notification
            Notification::create([
                'from' => 21, // From the first user (admin)
                'to' => $user->id,
                'type' => 'oto',
                'related_id' => rand(1, 10),
                'title' => 'New One-to-One Meeting Request',
                'message' => 'Admin has scheduled a one-to-one meeting with you on ' . Carbon::now()->addDays(rand(1, 14))->format('j M Y') . '.',
                'read' => false,
                'cleared' => false,
                'created_at' => Carbon::now()->subDays(rand(0, 7)),
                'updated_at' => Carbon::now(),
            ]);
            
            // Create a system notification
            Notification::create([
                'from' => 21, // From the first user (admin)
                'to' => $user->id,
                'type' => 'ceu',
                'related_id' => null,
                'title' => 'Welcome to the Platform',
                'message' => 'Welcome to the CBN platform. Please complete your profile to get started.',
                'read' => rand(0, 1) === 1,
                'cleared' => false,
                'created_at' => Carbon::now()->subDays(rand(15, 30)),
                'updated_at' => Carbon::now(),
            ]);
        }
        
        $this->command->info('Test notification data created successfully.');
    }
} 