<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Meeting;
use App\Models\ThankYou;
use Illuminate\Http\Request;

class MeetingController extends Controller
{
    /**
     * Get the next meeting from current date
     */
    public function getNextMeeting(Request $request)
    {
        try {
            $meetingData = [
                'meeting' => Meeting::getNextMeeting(),
                'tyfcbThisMonth' => ThankYou::whereMonth('created_at', date('m'))->sum('amount')
            ];
            if (!$meetingData) {
                return response()->json([
                    'success' => false,
                    'message' => 'No upcoming meetings found'
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'data' => $meetingData,
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