<!DOCTYPE html><html lang="id"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Rekap Iuran {{ $tahun }}</title><style>
@if($pdf)
@page { margin: 125px 30px 35px; }
body { font-family: 'Times New Roman', serif; font-size: 10pt; }
header { position: fixed; top: -110px; left: 0; right: 0; } header img { width: 100%; height: auto; }
@else
body { font-family: 'Times New Roman', serif; font-size: 14px; padding: 16px; }
@endif
h1 { font-size: 12pt; text-align: center; } table { width: 100%; border-collapse: collapse; } th,td { border: 1px solid #aaa; padding: 6px; } th { background: #eef3f7; } thead { display: table-header-group; } .number { text-align: right; } tr { page-break-inside: avoid; }
</style></head><body>
@if($pdf)<header><img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/kop_surat.png'))) }}" alt="Kop DPP UMM"></header>@endif
<h1>REKAP IURAN<br>TAHUN {{ $tahun }}</h1>
<p>Dicetak: {{ $generatedAt->locale('id')->translatedFormat('j F Y H:i') }} WIB.</p>
<p>Iuran peserta: 7,5% PHDP. Iuran pemberi kerja: 5,7% PHDP. {{ $jumlah_bulan_terisi }} dari 12 bulan memiliki data. Total menjumlahkan data yang tersedia.</p>
@php($rupiah = fn ($value) => 'Rp '.number_format((float) $value, 2, ',', '.'))
<table><thead><tr><th>Bulan</th><th>Iuran peserta</th><th>Iuran pemberi kerja</th></tr></thead><tbody>
@foreach($bulan as $month)<tr><td>{{ \App\Models\KpiPeriod::MONTHS[$month['bulan'] - 1] }}</td><td class="number">{{ $month['has_data'] ? $rupiah($month['iuran_peserta']) : 'Belum ada data' }}</td><td class="number">{{ $month['has_data'] ? $rupiah($month['iuran_pemberi_kerja']) : 'Belum ada data' }}</td></tr>@endforeach
<tr><th><strong>Total</strong></th><td class="number"><strong>{{ $jumlah_bulan_terisi ? $rupiah($total_iuran_peserta) : 'Belum ada data' }}</strong></td><td class="number"><strong>{{ $jumlah_bulan_terisi ? $rupiah($total_iuran_pemberi_kerja) : 'Belum ada data' }}</strong></td></tr>
</tbody></table>
</body></html>
