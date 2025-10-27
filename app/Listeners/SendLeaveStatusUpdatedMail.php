<?php

namespace App\Listeners;

use App\Events\LeaveStatusUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Mail\LeaveStatusUpdatedMail;
use Illuminate\Support\Facades\Mail;


class SendLeaveStatusUpdatedMail implements ShouldQueue
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
    public function handle(LeaveStatusUpdated $event): void
    {
        $leave = $event->leave;
        try {
            if ($leave && $leave->user && $leave->user->email) {
                Mail::to($leave->user->email)->send(new LeaveStatusUpdatedMail($leave));
            }
        } catch (\Exception $e) {
            \Log::error('Failed to send leave notification email', [
                'error' => $e->getMessage(),
                'leave_id' => $event->leave->id ?? null,
            ]);
        }
    }
}
