<?php

namespace App\Listeners;

use App\Events\LeaveCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use App\Mail\LeaveCreatedMail;
use App\Models\User;

class SendLeaveCreatedMail implements ShouldQueue
{
    use InteractsWithQueue;
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(LeaveCreated $event): void
    {
        $leave = $event->leave;
        try {
            $admins = User::role('admin')->get();
            foreach ($admins as $admin) {
                if ($admin->email) {
                    Mail::to($admin->email)->send(new LeaveCreatedMail($leave));
                }
            }
        } catch (\Exception $e) {
            \Log::error('Failed to send leave notification email', [
                'error' => $e->getMessage(),
                'leave_id' => $event->leave->id ?? null,
            ]);
        }
    }
}
