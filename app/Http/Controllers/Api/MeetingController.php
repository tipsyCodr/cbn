<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Meeting;
use App\Models\ThankYou;
use App\Models\User;
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
} 