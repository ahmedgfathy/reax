<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $report->name }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #1a56db;
            margin: 0;
            padding: 0;
        }
        .meta {
            margin-bottom: 20px;
            color: #666;
            font-size: 0.9em;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f3f4f6;
        }
        .summary {
            margin-top: 30px;
            padding: 15px;
            background-color: #f9fafb;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $report->name }}</h1>
    </div>

    <div class="meta">
        <p>{{ __('Generated on') }}: {{ now()->format('Y-m-d H:i:s') }}</p>
        <p>{{ __('Data Source') }}: {{ ucfirst($report->data_source) }}</p>
        @if($report->description)
            <p>{{ $report->description }}</p>
        @endif
    </div>

    @if(isset($reportData['data']) && is_array($reportData['data']))
        <table>
            <thead>
                <tr>
                    @foreach($reportData['columns'] as $column)
                        <th>{{ $column }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($reportData['data'] as $row)
                    <tr>
                        @foreach($reportData['columns'] as $key => $label)
                            <td>{{ $row[$key] ?? '' }}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    @if(isset($reportData['summary']))
        <div class="summary">
            <h3>{{ __('Summary') }}</h3>
            @foreach($reportData['summary'] as $key => $value)
                <p><strong>{{ __(Str::title(str_replace('_', ' ', $key))) }}:</strong> 
                    @if(is_array($value))
                        <br>
                        @foreach($value as $k => $v)
                            {{ Str::title($k) }}: {{ $v }}<br>
                        @endforeach
                    @else
                        {{ $value }}
                    @endif
                </p>
            @endforeach
        </div>
    @endif
</body>
</html>
