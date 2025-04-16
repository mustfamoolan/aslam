<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\DentalClinic;
use Illuminate\Http\Request;
use Carbon\Carbon;

class NotificationController extends Controller
{
    /**
     * عرض قائمة التنبيهات
     */
    public function index()
    {
        $notifications = Notification::latest()->paginate(10);

        return view('notifications.index', compact('notifications'));
    }

    /**
     * تخزين تنبيه جديد
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:info,warning,danger',
        ]);

        $validated['dental_clinic_id'] = DentalClinic::first()->id;

        $notification = Notification::create($validated);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'notification' => $notification]);
        }

        return redirect()->route('notifications.index')
            ->with('success', 'تم إضافة التنبيه بنجاح');
    }

    /**
     * تحديث حالة قراءة التنبيه
     */
    public function markAsRead(Notification $notification)
    {
        $notification->update([
            'is_read' => true,
            'read_at' => Carbon::now(),
        ]);

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()
            ->with('success', 'تم تحديث حالة التنبيه بنجاح');
    }

    /**
     * تحديث حالة قراءة جميع التنبيهات
     */
    public function markAllAsRead()
    {
        Notification::where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => Carbon::now(),
            ]);

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()
            ->with('success', 'تم تحديث حالة جميع التنبيهات بنجاح');
    }

    /**
     * حذف تنبيه محدد
     */
    public function destroy(Notification $notification)
    {
        $notification->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('notifications.index')
            ->with('success', 'تم حذف التنبيه بنجاح');
    }

    /**
     * الحصول على عدد التنبيهات غير المقروءة
     */
    public function getUnreadCount()
    {
        $count = Notification::where('is_read', false)->count();

        return response()->json(['count' => $count]);
    }

    /**
     * الحصول على آخر التنبيهات
     */
    public function getLatestNotifications()
    {
        $notifications = Notification::latest()->take(5)->get();

        return response()->json(['notifications' => $notifications]);
    }
}
