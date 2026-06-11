<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Meeting;
use App\Models\ThankYou;
use App\Models\User;
use App\Models\MeetingAttendance;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class MeetingController extends Controller
{
    /**
     * Get the next meeting from current date
     */
    public function getNextMeeting(Request $request)
    {
        try {
            $user = $request->user();
            $meeting = Meeting::getNextMeeting();
            $chapterID = $user->chapter_id;
            
            if (!$meeting) {
                return response()->json([
                    'success' => false,
                    'message' => 'No upcoming meetings found'
                ], 404);
            }
            
            // Add tyfcbThisMonth to meeting object
            $userIds = User::where('chapter_id', $chapterID)->pluck('id');
            $meeting->tyfcbThisMonth = number_format(ThankYou::whereMonth('created_at', date('m'))->whereIn('from', $userIds)->sum('amount'), 2);
            
            return response()->json([
                'success' => true,
                'data' => $meeting,
                'message' => 'Next meeting retrieved successfully'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch next meeting: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get all upcoming meetings
     */
    public function getUpcomingMeetings(Request $request)
    {
        try {
            $today = date('Y-m-d');
            
            $meetings = Meeting::whereDate('date', '>=', $today)
                ->orderBy('date', 'asc')
                ->get();
            
            return response()->json([
                'success' => true,
                'data' => $meetings,
                'message' => 'Upcoming meetings retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch upcoming meetings: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get attendance list for a meeting
     */
    public function getAttendance(Request $request, $meetingId)
    {
        try {
            $meeting = Meeting::find($meetingId);
            if (!$meeting) {
                return response()->json([
                    'success' => false,
                    'message' => 'Meeting not found'
                ], 404);
            }

            $user = $request->user();
            $chapterId = $user->chapter_id;

            // Get users in the same chapter, or all users if chapterId is null (e.g., admin)
            $query = User::where('role', 'user');
            if ($chapterId) {
                $query->where('chapter_id', $chapterId);
            }
            $users = $query->orderBy('name', 'asc')->get();

            // Get attendance records for this meeting
            $attendances = MeetingAttendance::where('meeting_id', $meetingId)
                ->get()
                ->keyBy('user_id');

            $data = $users->map(function ($u) use ($attendances) {
                $attendance = $attendances->get($u->id);
                return [
                    'user_id' => $u->id,
                    'name' => $u->name,
                    'email' => $u->email,
                    'company' => $u->company,
                    'designation' => $u->designation,
                    'status' => $attendance ? $attendance->status : null,
                    'notes' => $attendance ? $attendance->notes : null,
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Meeting attendance retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch meeting attendance: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mark attendance for a meeting
     */
    public function markAttendance(Request $request, $meetingId)
    {
        try {
            $meeting = Meeting::find($meetingId);
            if (!$meeting) {
                return response()->json([
                    'success' => false,
                    'message' => 'Meeting not found'
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'attendances' => 'required|array',
                'attendances.*.user_id' => 'required|exists:users,id',
                'attendances.*.status' => 'required|in:present,absent,late,excused',
                'attendances.*.notes' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $attendances = $request->input('attendances');

            foreach ($attendances as $att) {
                MeetingAttendance::updateOrCreate(
                    [
                        'meeting_id' => $meetingId,
                        'user_id' => $att['user_id']
                    ],
                    [
                        'status' => $att['status'],
                        'notes' => $att['notes'] ?? null
                    ]
                );
            }

            return response()->json([
                'success' => true,
                'message' => 'Attendance marked successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark attendance: ' . $e->getMessage()
            ], 500);
        }
    }
} 