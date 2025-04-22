<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $company->company_name }} - Dashboard Report</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            color: #333;
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            padding: 20px;
        }
        .header {
            border-bottom: 2px solid #4b5563;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }
        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #1e40af;
        }
        .report-meta {
            color: #6b7280;
            font-size: 14px;
            margin: 10px 0;
        }
        .section {
            margin-bottom: 30px;
        }
        .section-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 15px;
            color: #1f2937;
            border-bottom: 1px solid #d1d5db;
            padding-bottom: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0 20px 0;
            font-size: 12px;
        }
        table, th, td {
            border: 1px solid #d1d5db;
        }
        th {
            background-color: #f3f4f6;
            color: #1f2937;
            font-weight: bold;
            text-align: left;
            padding: 8px;
        }
        td {
            padding: 8px;
            vertical-align: top;
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
        .metrics-grid {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -10px;
        }
        .metric-box {
            background-color: #f3f4f6;
            border-radius: 8px;
            padding: 15px;
            margin: 10px;
            width: calc(25% - 20px);
            box-sizing: border-box;
        }
        .metric-title {
            font-size: 14px;
            color: #6b7280;
            margin-bottom: 8px;
        }
        .metric-value {
            font-size: 20px;
            font-weight: bold;
            color: #1f2937;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="company-name">{{ $company->company_name }}</div>
            <div class="report-meta">Dashboard Report | Date Range: {{ $startDate }} to {{ $endDate }}</div>
            <div class="report-meta">Generated on: {{ $generatedAt }}</div>
        </div>
        
        <div class="section">
            <div class="section-title">Summary Metrics</div>
            <table>
                <tr>
                    <td><strong>Total Documents:</strong> {{ $storageMetrics['document_count'] }}</td>
                    <td><strong>Total Attachments:</strong> {{ $storageMetrics['attachment_count'] }}</td>
                    <td><strong>Storage Used:</strong> {{ $storageMetrics['formatted_total_size'] }}</td>
                    <td><strong>Average Size:</strong> {{ $storageMetrics['formatted_average_size'] }}/doc</td>
                </tr>
                <tr>
                    <td><strong>Users:</strong> {{ count($companyUsers) }}</td>
                    <td><strong>Offices:</strong> {{ count($companyOffices) }}</td>
                    <td colspan="2">
                        <strong>Date Range:</strong> {{ $startDate }} to {{ $endDate }}
                    </td>
                </tr>
            </table>
        </div>
        
        <div class="section">
            <div class="section-title">User Performance</div>
            <table>
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Uploads</th>
                        <th>Forwarded</th>
                        <th>Processed</th>
                        <th>Avg Response Time</th>
                        <th>Avg Processing Time</th>
                        <th>Approval Rate</th>
                        <th>Score</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($userPerformanceMetrics as $metric)
                        <tr>
                            <td>{{ $metric['user']->first_name }} {{ $metric['user']->last_name }}</td>
                            <td>{{ $metric['uploads_count'] }}</td>
                            <td>{{ $metric['forwarded_count'] }}</td>
                            <td>{{ $metric['processed_count'] }}</td>
                            <td>{{ $metric['avg_response_time'] }}</td>
                            <td>{{ $metric['avg_processing_time'] }}</td>
                            <td>{{ $metric['approval_rate'] }}%</td>
                            <td>{{ $metric['performance_score'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="section">
            <div class="section-title">Office Performance</div>
            <table>
                <thead>
                    <tr>
                        <th>Office</th>
                        <th>Users</th>
                        <th>Documents Originated</th>
                        <th>Documents Received</th>
                        <th>Workflows Processed</th>
                        <th>Avg Processing Time</th>
                        <th>Efficiency Score</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($officePerformanceMetrics as $metric)
                        <tr>
                            <td>{{ $metric['office']->name }}</td>
                            <td>{{ $metric['user_count'] }}</td>
                            <td>{{ $metric['documents_originated'] }}</td>
                            <td>{{ $metric['documents_received'] }}</td>
                            <td>{{ $metric['workflows_processed'] }}</td>
                            <td>{{ $metric['avg_processing_time'] }}</td>
                            <td>{{ $metric['efficiency_score'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="section">
            <div class="section-title">User Storage Details</div>
            <table>
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Documents</th>
                        <th>Storage Used</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($storageMetrics['user_storage'] as $userStorage)
                        <tr>
                            <td>{{ $userStorage['user']->first_name }} {{ $userStorage['user']->last_name }}</td>
                            <td>{{ $userStorage['count'] }}</td>
                            <td>{{ $userStorage['formatted_size'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="section">
            <div class="section-title">Office Storage Details</div>
            <table>
                <thead>
                    <tr>
                        <th>Office</th>
                        <th>Documents</th>
                        <th>Storage Used</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($storageMetrics['office_storage'] as $officeStorage)
                        <tr>
                            <td>{{ $officeStorage['office']->name }}</td>
                            <td>{{ $officeStorage['count'] }}</td>
                            <td>{{ $officeStorage['formatted_size'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="footer">
            <p>This report is automatically generated from the Document Archive System</p>
            <p>&copy; {{ date('Y') }} {{ $company->company_name }} - All rights reserved</p>
        </div>
    </div>
</body>
</html>