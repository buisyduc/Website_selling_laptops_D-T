<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\DatabaseNotification;

class AdminNotificationController extends Controller
{
    /**
     * Mark the given notification as read and redirect to its target link.
     */
    public function redirect(string $id)
    {
        $user = Auth::user();



        $notification = DatabaseNotification::where('id', $id)
            ->where('notifiable_id', $user->id)
            ->where('notifiable_type', get_class($user))
            ->firstOrFail();
        if (is_null($notification->read_at)) {
            $notification->markAsRead();
        }

        $data = $notification->data ?? [];
        $target = $data['link']
            ?? (isset($data['order_id']) ? route('admin.orders.show', $data['order_id']) : null)
            ?? route('admin.orders.index');

        return redirect()->to($target);
    }
}
