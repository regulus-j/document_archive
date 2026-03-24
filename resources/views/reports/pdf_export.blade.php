<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            color: #333;
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }
        .container {
            padding: 20px;
        }
        .header {
            border-bottom: 2px solid #4b5563;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }
        .logo-text {
            font-size: 24px;
            font-weight: bold;
            color: #1e40af;
        }
        .report-title {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .report-meta {
            color: #6b7280;
            margin-bottom: 20px;
        }
        .report-date {
            font-style: italic;
            color: #6b7280;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 25px 0;
        }
        table, th, td {
            border: 1px solid #d1d5db;
        }
        th {
            background-color: #f3f4f6;
            color: #1f2937;
            font-weight: bold;
            text-align: left;
            padding: 10px;
        }
        td {
            padding: 10px;
        }
        tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #d1d5db;
            font-size: 12px;
            color: #6b7280;
        }
        .page-number {
            text-align: right;
            font-size: 12px;
            color: #6b7280;
        }
        .text-wrap {
            word-break: break-word;
            max-width: 300px; /* Adjust this value as needed */
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <span class="logo-text">Document Archive System</span>
            <div class="page-number">Page 1</div>
        </div>

        <div class="report-title">{{ $title }}</div>
        <div class="report-meta">{{ $dateRange }}</div>
        <div class="report-date">Generated on: {{ now()->format('F j, Y h:i A') }}</div>

        @if($reportType == 'audit_history')
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Document</th>
                        <th>User</th>
                        <th>Action</th>
                        <th>Status</th>
                        <th>Details</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->document ? $item->document->title : "Document #{$item->document_id}" }}</td>
                        <td>{{ $item->user ? ($item->user->first_name . ' ' . $item->user->last_name) : "User #{$item->user_id}" }}</td>
                        <td>{{ ucfirst($item->action) }}</td>
                        <td>{{ ucfirst($item->status) }}</td>
                        <td class="text-wrap">{{ $item->details }}</td>
                        <td>{{ $item->created_at->format('Y-m-d H:i') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align: center;">No data available</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        @elseif($reportType == 'company_performance')
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Document</th>
                        <th>Sender</th>
                        <th>Recipient</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Received</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>Document #{{ $item->document_id }}</td>
                        <td>{{ $item->sender ? ($item->sender->first_name . ' ' . $item->sender->last_name) : "User #{$item->sender_id}" }}</td>
                        <td>
                            @if($item->recipient)
                                {{ $item->recipient->first_name . ' ' . $item->recipient->last_name }}
                            @elseif($item->recipientOffice)
                                {{ $item->recipientOffice->name }} (Office)
                            @else
                                Office #{{ $item->recipient_office }}
                            @endif
                        </td>
                        <td>{{ ucfirst($item->status) }}</td>
                        <td>{{ $item->created_at->format('Y-m-d H:i') }}</td>
                        <td>{{ $item->received_at ? \Carbon\Carbon::parse($item->received_at)->format('Y-m-d H:i') : 'Not received' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align: center;">No data available</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        @endif

        <div class="footer">
            <p>This report is automatically generated from the Document Archive System</p>
            <p>&copy; {{ date('Y') }} Document Archive - All rights reserved</p>
        </div>
    </div>
</body>
</html>