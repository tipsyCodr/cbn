<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Referral;
use App\Models\ThankYou;
use App\Models\OneToOne;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Get all notifications for the authenticated user
     */
    public function index(Request $request)
    {
        try {
            $user = $request->user();
            
            // Get notifications where the user is the recipient and not cleared
            $notifications = Notification::where('to', $user->id)
                ->where('cleared', false)
                ->with(['fromUser:id,name,email,profile_photo'])
                ->orderBy('created_at', 'desc')
                ->get();
            
            return response()->json([
                'success' => true,
                'data' => $notifications,
                'message' => 'Notifications retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch notifications: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Mark a notification as read
     */
    public function markAsRead(Request $request, $id)
    {
        try {
            $user = $request->user();
            
            $notification = Notification::where('id', $id)
                ->where('to', $user->id)
                ->first();
            
            if (!$notification) {
                return response()->json([
                    'success' => false,
                    'message' => 'Notification not found'
                ], 404);
            }
            
            $notification->read = true;
            $notification->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Notification marked as read'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark notification as read: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Mark all notifications as read for the authenticated user
     */
    public function markAllAsRead(Request $request)
    {
        try {
            $user = $request->user();
            
            Notification::where('to', $user->id)
                ->where('read', false)
                ->update(['read' => true]);
            
            return response()->json([
                'success' => true,
                'message' => 'All notifications marked as read'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark all notifications as read: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Clear a notification (soft delete)
     */
    public function clearNotification(Request $request, $id)
    {
        try {
            $user = $request->user();
            
            $notification = Notification::where('id', $id)
                ->where('to', $user->id)
                ->first();
            
            if (!$notification) {
                return response()->json([
                    'success' => false,
                    'message' => 'Notification not found'
                ], 404);
            }
            
            $notification->cleared = true;
            $notification->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Notification cleared'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to clear notification: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get count of unread notifications
     */
    public function getUnreadCount(Request $request)
    {
        try {
            $user = $request->user();
            
            $count = Notification::where('to', $user->id)
                ->where('read', false)
                ->where('cleared', false)
                ->count();
            
            return response()->json([
                'success' => true,
                'data' => [
                    'count' => $count
                ],
                'message' => 'Unread notifications count retrieved'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get unread count: ' . $e->getMessage()
            ], 500);
        }
    }
} 