<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Leaves Report</title>
    <style>
        body {
            font-family: "DejaVu Sans", sans-serif;
            font-size: 12px;
            color: #333;
        }

        h2 {
            text-align: center;
            margin-bottom: 10px;
            color: #222;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            border: 1px solid #999;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #fafafa;
        }

        .footer {
            text-align: right;
            margin-top: 20px;
            font-size: 11px;
            color: #666;
        }

        .status-approved {
            color: green;
            font-weight: bold;
        }
        .status-rejected {
            color: red;
            font-weight: bold;
        }
        .status-pending {
            color: orange;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <h2>Leave Report - {{ now()->format('Y') }}</h2>

    <table>
        <thead>
            <tr>
                @if(isset($leaves[0]->user))
                    <th>Employee Name</th>
                @endif
                <th>Leave Type</th>
                <th>Reason</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Status</th>
                <th>Remarks</th>
                <th>Applied Date</th>
                <th>Updated Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($leaves as $leave)
                <tr>
                    @if(isset($leave->user))
                        <td>{{ $leave->user->name }}</td>
                    @endif
                    <td>{{ ucfirst($leave->type) }}</td>
                    <td>{{ $leave->request_reason?? "N/A" }}</td>
                    <td>{{ \Carbon\Carbon::parse($leave->start_date)->format('d M Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($leave->end_date)->format('d M Y') }}</td>
                    <td class="status-{{ strtolower($leave->status) }}">{{ ucfirst($leave->status) }}</td>
                    <td>{{ $leave->admin_remarks?? "N/A" }}</td>
                    <td>{{ $leave->created_at->format('d M Y') }}</td>
                    <td>{{ $leave->updated_at ? $leave->updated_at->format('d M Y') : "N/A" }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="{{ isset($leaves[0]->user) ? 6 : 5 }}">No leave records found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Generated on {{ now()->format('d M Y, h:i A') }}
    </div>

</body>
</html>
