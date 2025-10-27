<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>New Leave Request</title>
</head>
<body>
    <p>Hello Admin,</p>

    <p>A new leave request has been submitted by <strong>{{ $leave->user->name }}</strong>.</p>

    <p><strong>Leave Details:</strong></p>
    <ul>
                @php
            $isOneDayLeave = $leave->start_date == $leave->end_date;
        @endphp
        <li>Employee Name: {{ $leave->user->name }}</li>
        <li>Employee Email: {{ $leave->user->email }}</li>
        <li>Leave Type: {{ ucfirst($leave->type) }}</li>
        <li>{{ !$isOneDayLeave ? "Start Date:" : "Date:" }} {{ $leave->start_date }}</li>
        {{-- {{ \Carbon\Carbon::parse($leave->start_date)->format('d-m-Y') }} --}}
        @if(!$isOneDayLeave)
            <li>End Date: {{ $leave->end_date }}</li>
        @endif
        @if($leave->request_reason)
            <li>Reason: {{ $leave->request_reason }}</li>
        @endif
    </ul>

    <p>Please review and update the status of the leave request.</p>

    <p>Thanks,<br>Employee Leave Management System</p>
</body>
</html>
