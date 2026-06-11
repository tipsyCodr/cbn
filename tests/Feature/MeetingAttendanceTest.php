<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MeetingAttendanceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        \App\Models\Chapter::create(['id' => 1, 'title' => 'Chapter 1', 'is_active' => 1]);
        \App\Models\Chapter::create(['id' => 2, 'title' => 'Chapter 2', 'is_active' => 1]);
    }

    public function test_cannot_get_attendance_without_authentication(): void
    {
        $response = $this->getJson('/api/meetings/1/attendance');
        $response->assertStatus(401);
    }

    public function test_cannot_mark_attendance_without_authentication(): void
    {
        $response = $this->postJson('/api/meetings/1/attendance', []);
        $response->assertStatus(401);
    }

    public function test_get_attendance_returns_chapter_members_with_default_status(): void
    {
        $user1 = \App\Models\User::factory()->create([
            'chapter_id' => 1,
            'role' => 'user',
            'active' => 1
        ]);
        $user2 = \App\Models\User::factory()->create([
            'chapter_id' => 1,
            'role' => 'user',
            'active' => 1
        ]);
        // User from another chapter
        $user3 = \App\Models\User::factory()->create([
            'chapter_id' => 2,
            'role' => 'user',
            'active' => 1
        ]);

        $meeting = \App\Models\Meeting::create([
            'name' => 'Weekly Meeting',
            'date' => '2026-06-11',
            'start_time' => '10:00:00',
            'end_time' => '11:00:00'
        ]);

        \Laravel\Sanctum\Sanctum::actingAs($user1);

        $response = $this->getJson("/api/meetings/{$meeting->id}/attendance");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    '*' => [
                        'user_id',
                        'name',
                        'email',
                        'company',
                        'designation',
                        'status',
                        'notes'
                    ]
                ],
                'message'
            ]);

        $data = $response->json('data');
        $this->assertCount(2, $data); // Only user1 and user2 who are in chapter 1
        
        $userIds = collect($data)->pluck('user_id')->toArray();
        $this->assertContains($user1->id, $userIds);
        $this->assertContains($user2->id, $userIds);
        $this->assertNotContains($user3->id, $userIds);

        // Statuses should be null
        $this->assertNull($data[0]['status']);
        $this->assertNull($data[1]['status']);
    }

    public function test_can_mark_and_retrieve_attendance(): void
    {
        $user1 = \App\Models\User::factory()->create([
            'chapter_id' => 1,
            'role' => 'user',
            'active' => 1
        ]);
        $user2 = \App\Models\User::factory()->create([
            'chapter_id' => 1,
            'role' => 'user',
            'active' => 1
        ]);

        $meeting = \App\Models\Meeting::create([
            'name' => 'Weekly Meeting',
            'date' => '2026-06-11',
            'start_time' => '10:00:00',
            'end_time' => '11:00:00'
        ]);

        \Laravel\Sanctum\Sanctum::actingAs($user1);

        $attendanceData = [
            'attendances' => [
                [
                    'user_id' => $user1->id,
                    'status' => 'present',
                    'notes' => 'On time'
                ],
                [
                    'user_id' => $user2->id,
                    'status' => 'late',
                    'notes' => 'Traffic'
                ]
            ]
        ];

        $postResponse = $this->postJson("/api/meetings/{$meeting->id}/attendance", $attendanceData);
        $postResponse->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Attendance marked successfully'
            ]);

        // Verify in DB
        $this->assertDatabaseHas('meeting_attendances', [
            'meeting_id' => $meeting->id,
            'user_id' => $user1->id,
            'status' => 'present',
            'notes' => 'On time'
        ]);
        $this->assertDatabaseHas('meeting_attendances', [
            'meeting_id' => $meeting->id,
            'user_id' => $user2->id,
            'status' => 'late',
            'notes' => 'Traffic'
        ]);

        // Get attendance again to verify
        $getResponse = $this->getJson("/api/meetings/{$meeting->id}/attendance");
        $getResponse->assertStatus(200);

        $data = $getResponse->json('data');
        $user1Attendance = collect($data)->firstWhere('user_id', $user1->id);
        $user2Attendance = collect($data)->firstWhere('user_id', $user2->id);

        $this->assertEquals('present', $user1Attendance['status']);
        $this->assertEquals('On time', $user1Attendance['notes']);
        $this->assertEquals('late', $user2Attendance['status']);
        $this->assertEquals('Traffic', $user2Attendance['notes']);
    }

    public function test_validation_fails_on_invalid_attendance_status(): void
    {
        $user = \App\Models\User::factory()->create([
            'chapter_id' => 1,
            'role' => 'user',
            'active' => 1
        ]);

        $meeting = \App\Models\Meeting::create([
            'name' => 'Weekly Meeting',
            'date' => '2026-06-11'
        ]);

        \Laravel\Sanctum\Sanctum::actingAs($user);

        $attendanceData = [
            'attendances' => [
                [
                    'user_id' => $user->id,
                    'status' => 'super-present', // Invalid status
                ]
            ]
        ];

        $response = $this->postJson("/api/meetings/{$meeting->id}/attendance", $attendanceData);
        $response->assertStatus(422)
            ->assertJsonStructure([
                'success',
                'message',
                'errors'
            ]);
    }
}
