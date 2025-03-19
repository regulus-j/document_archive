<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Analytics Report</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            padding: 20px;
            color: #333;
        }
        h1, h2, h3 {
            color: #2563eb;
        }
        .header {
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .report-meta {
            font-size: 0.9em;
            color: #6b7280;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            border: 1px solid #e5e7eb;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f3f4f6;
            font-weight: bold;
        }
        .metrics-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 30px;
        }
        .metric-card {
            flex: 1;
            min-width: 200px;
            padding: 15px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            background-color: #f9fafb;
        }
        .metric-title {
            font-size: 0.9em;
            color: #6b7280;
            margin-bottom: 5px;
        }
        .metric-value {
            font-size: 1.8em;
            font-weight: bold;
            color: #1e40af;
        }
        .footer {
            margin-top: 30px;
            font-size: 0.8em;
            color: #6b7280;
            text-align: center;
            border-top: 1px solid #e5e7eb;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Analytics Report</h1>
    </div>

    <div class="report-meta">
        <p><strong>Period:</strong> {{ $startDate }} to {{ $endDate }}</p>
        @if($userDetails)
            <p><strong>User:</strong> {{ $userDetails->name }}</p>
        @endif
        @if($officeDetails)
            <p><strong>Office:</strong> {{ $officeDetails->name }}</p>
        @endif
        <p><strong>Generated at:</strong> {{ $generatedAt }}</p>
    </div>

    <h2>Summary Metrics</h2>
    <div class="metrics-container">
        <div class="metric-card">
            <div class="metric-title">Average Time to Receive</div>
            <div class="metric-value">{{ $averageTimeToReceive }}</div>
        </div>
        <div class="metric-card">
            <div class="metric-title">Average Time to Review</div>
            <div class="metric-value">{{ $averageTimeToReview }}</div>
        </div>
        <div class="metric-card">
            <div class="metric-title">Documents Forwarded</div>
            <div class="metric-value">{{ $averageDocsForwarded }}</div>
        </div>
        <div class="metric-card">
            <div class="metric-title">Documents Uploaded</div>
            <div class="metric-value">{{ $documentsUploaded }}</div>
        </div>
    </div>

    <h2>Monthly Trends</h2>
    <table>
        <thead>
            <tr>
                <th>Month</th>
                <th>Avg. Time to Receive</th>
                <th>Avg. Time to Review</th>
                <th>Docs Forwarded</th>
                <th>Docs Uploaded</th>
            </tr>
        </thead>
        <tbody>
            @foreach($monthlyData as $month => $data)
            <tr>
                <td>{{ $month }}</td>
                <td>{{ $data['receive_time'] }}</td>
                <td>{{ $data['review_time'] }}</td>
                <td>{{ $data['docs_forwarded'] }}</td>
                <td>{{ $data['docs_uploaded'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Document Archive System &copy; {{ date('Y') }} - All Rights Reserved</p>
    </div>
</body>
</html>
