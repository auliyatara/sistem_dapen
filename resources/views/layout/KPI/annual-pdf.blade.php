<!DOCTYPE html><html lang="id"><head><meta charset="utf-8"><style>
@page { margin: 128pt 25pt 30pt; }
body { font-family:'Times New Roman',serif;font-size:10pt;margin:0;padding:0; }
header {position:fixed;top:-108pt;left:0;right:0;text-align:center} header img{width:690px;height:auto}
h1{text-align:center;font-size:12pt;margin:0 0 6pt} p{margin:0 0 6pt} table{border-collapse:collapse;width:100%;table-layout:fixed;line-height:1.1} th,td{border:1px solid #777;padding:3pt 4pt;vertical-align:middle;overflow-wrap:break-word} th{background:#edf3f7} thead{display:table-header-group} td{white-space:pre-wrap}
.achievement th,.achievement td{vertical-align:top;text-align:left;padding:2pt 4pt;line-height:1.08;white-space:normal;word-wrap:break-word}
.achievement th{background:#fff}
.achievement .achievement-total td{background:#dbeafe;font-weight:bold}
.programs {margin-left:0;margin-right:0;width:100%}
.programs th,.programs td {word-wrap:break-word;white-space:normal}
.programs td:nth-child(6),.programs td:nth-child(7) {word-break:break-all}
</style></head><body>
<header><img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/kop_surat.png'))) }}"></header>
<h1>{{ $report === 'achievement' ? 'LAPORAN KPI' : 'LAPORAN PROGRAM KERJA TAHUN '.$selectedYear }}<br>BIDANG {{ mb_strtoupper($selectedRole->role_name) }}</h1>
<p>Periode: Januari–Desember {{ $selectedYear }} · Dicetak {{ $generatedAt->locale('id')->translatedFormat('j F Y H:i') }} WIB</p>
<table class="{{ $report === 'achievement' ? 'achievement' : 'programs' }}">
@if($report === 'achievement')
<colgroup><col style="width:52%"><col style="width:13%"><col style="width:15%"><col style="width:9%"><col style="width:11%"></colgroup>
@else
<colgroup><col style="width:30%"><col style="width:18%"><col style="width:15%"><col style="width:7%"><col style="width:14%"><col style="width:8%"><col style="width:8%"></colgroup>
@endif
<thead><tr>@foreach($rows[5] as $heading)<th style="width:{{ ($report === 'achievement' ? [52,13,15,9,11] : [30,18,15,7,14,8,8])[$loop->index] }}%">{{ $heading }}</th>@endforeach</tr></thead><tbody>
@forelse(array_slice($rows,6) as $row)<tr class="{{ $report === 'achievement' && $loop->last ? 'achievement-total' : '' }}">@foreach($row as $value)<td>{{ $value }}</td>@endforeach</tr>@empty<tr><td colspan="{{ count($rows[5]) }}">Belum ada data.</td></tr>@endforelse
</tbody></table></body></html>
