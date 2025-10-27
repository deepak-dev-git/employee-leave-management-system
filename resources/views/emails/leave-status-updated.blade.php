<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Leave Status Updated</title>
</head>
<body>
    <p>Hello {{ $leave->user->name }},</p>

    <p>Your leave request has been updated by the admin.</p>

    <p><strong>Leave Details:</strong></p>
    <ul>
        @php
            $isOneDayLeave = $leave->start_date == $leave->end_date;
        @endphp
        <li>Type: {{ ucfirst($leave->type) }}</li>
        <li>{{ !$isOneDayLeave ? "Start Date:" : "Date:" }} {{ $leave->start_date }}</li>
        @if(!$isOneDayLeave)
            <li>End Date: {{ $leave->end_date }}</li>
        @endif
        @if($leave->request_reason)
            <li>Reason: {{ $leave->request_reason }}</li>
        @endif
        <li>Status: {{ ucfirst($leave->status) }}</li>
        @if($leave->admin_remarks)
            <li>Admin Remarks: {{ $leave->admin_remarks }}</li>
        @endif
    </ul>

    <p>Thanks,<br>Employee Leave Management System</p>
</body>
</html>
