<!DOCTYPE html>
<html lang="id"><head><meta charset="UTF-8"><style>
@page { margin: 155px 30px 40px; }
body { font-family: 'Times New Roman', serif; font-size: 10pt; }
header { position: fixed; top: -135px; left: 0; right: 0; text-align: center; }
header img { width: 690px; height: auto; }
.report-title { text-align: center; font-size: 12pt; }
table { width: 100%; border-collapse: collapse; table-layout: fixed; }
th, td { border: 1px solid #aaa; padding: 5px; vertical-align: top; overflow-wrap: break-word; word-wrap: break-word; }
th { background: #edf3f7; } thead { display: table-header-group; }
.report-line { white-space: pre; line-height: 12pt; min-height: 12pt; } .continuation { font-style: italic; line-height: 12pt; } tr { page-break-inside: avoid; } a { color: #165a96; }
</style></head><body>
<header><img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/kop_surat.png'))) }}" alt="Kop Dana Pensiun Pegawai Universitas Muhammadiyah Malang"></header>
<h1 class="report-title">LAPORAN KINERJA {{ $reportPeriod->type === 'bulanan' ? 'BULAN ' : '' }}{{ mb_strtoupper($reportPeriod->label, 'UTF-8') }}<br>BIDANG {{ mb_strtoupper($selectedRole->role_name, 'UTF-8') }}</h1>
<p><strong>Periode:</strong> {{ $reportPeriod->label }} ({{ $reportPeriod->starts_at->locale('id')->translatedFormat('j F Y') }}–{{ $reportPeriod->ends_at->locale('id')->translatedFormat('j F Y') }})</p>
<p><strong>Dicetak:</strong> {{ $generatedAt->locale('id')->translatedFormat('j F Y H:i') }} WIB. <strong>Cakupan:</strong> {{ request('scope') === 'all' ? 'Semua tugas periode ini' : 'Hasil filter' }}. @if($search !== '') Pencarian: {{ $search }}. @endif @if($status !== '') Status: {{ ['completed' => 'Selesai', 'in_progress' => 'Sedang dikerjakan', 'not_started' => 'Belum dimulai'][$status] }}. @endif</p>
<p><strong>Ringkasan seluruh periode:</strong> {{ $summary['total'] }} tugas; {{ $summary['completed'] }} selesai; {{ $summary['in_progress'] }} sedang dikerjakan; {{ $summary['not_started'] }} belum dimulai. <strong>Tugas dalam laporan:</strong> {{ $tasks->count() }}.</p>
<table><thead><tr><th style="width:12%">Periode asal</th><th style="width:26%">Tugas / Keterangan</th><th style="width:13%">Progres / Status</th><th style="width:23%">Evaluasi</th><th style="width:26%">Bukti / Link</th></tr></thead><tbody>
@forelse($pdfRows as $row)<tr>
@foreach($row['cells'] as $index => $lines)
<td>@if($index === 0 && $row['continued'])<div class="continuation">Lanjutan</div>@endif
@foreach($lines as $line)<div class="report-line">@if($line['url'])<a href="{{ $line['url'] }}">{{ $line['text'] === '' ? ' ' : $line['text'] }}</a>@else{{ $line['text'] === '' ? ' ' : $line['text'] }}@endif</div>@endforeach
</td>
@endforeach
</tr>@empty<tr><td colspan="5">Tidak ada tugas dalam cakupan laporan ini.</td></tr>@endforelse
</tbody></table>
</body></html>
