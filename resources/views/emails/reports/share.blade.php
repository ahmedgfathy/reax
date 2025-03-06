@component('mail::message')
# {{ $reportName }}

@if($message)
{{ $message }}
@else
Please find attached your requested report.
@endif

@if(isset($reportData['data']))
@component('mail::table')
| @foreach(array_keys(reset($reportData['data'])) as $header) {{ $header }} | @endforeach
| @foreach(array_keys(reset($reportData['data'])) as $header) --- | @endforeach
@foreach($reportData['data'] as $row)
| @foreach($row as $value) {{ $value }} | @endforeach
@endforeach
@endcomponent
@elseif(isset($reportData['leads']) && isset($reportData['properties']))
## Leads Data
@if(!empty($reportData['leads']['data']))
@component('mail::table')
| @foreach(array_keys(reset($reportData['leads']['data'])) as $header) {{ $header }} | @endforeach
| @foreach(array_keys(reset($reportData['leads']['data'])) as $header) --- | @endforeach
@foreach($reportData['leads']['data'] as $row)
| @foreach($row as $value) {{ $value }} | @endforeach
@endforeach
@endcomponent
@endif

## Properties Data
@if(!empty($reportData['properties']['data']))
@component('mail::table')
| @foreach(array_keys(reset($reportData['properties']['data'])) as $header) {{ $header }} | @endforeach
| @foreach(array_keys(reset($reportData['properties']['data'])) as $header) --- | @endforeach
@foreach($reportData['properties']['data'] as $row)
| @foreach($row as $value) {{ $value }} | @endforeach
@endforeach
@endcomponent
@endif
@endif

@component('mail::button', ['url' => route('reports.show', $report->id)])
View Full Report
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
