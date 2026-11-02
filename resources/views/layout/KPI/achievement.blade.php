<section aria-labelledby="kpi-achievement-title">

    <style>
        .kpi-achievement-table {min-width:760px;width:100%}
        .kpi-achievement-table thead th {text-align:center!important;vertical-align:middle!important;white-space:nowrap;padding:18px 14px!important}
        .kpi-achievement-table tbody td {vertical-align:middle}
    </style>
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-4 mb-5">
        <div><h3 id="kpi-achievement-title" class="mb-1">Pencapaian KPI {{ $selectedYear }}</h3><span class="text-muted">{{ $selectedRole->role_name }} · Januari–Desember</span></div>
        <form method="GET" action="{{ route('kpi.index') }}" class="d-flex flex-wrap align-items-center gap-3">
            <input type="hidden" name="role_id" value="{{ $selectedRole->role_id }}">
            <input type="hidden" name="tab" value="achievement">
            <label for="achievement-year" class="visually-hidden">Tahun pencapaian</label>
            <input id="achievement-year" name="year" type="number" min="1900" max="2099" required value="{{ $selectedYear }}" class="form-control form-control-solid" style="width:110px" onchange="if(this.form.reportValidity()) this.form.submit()">
            <noscript><button class="btn btn-primary">Tampilkan</button></noscript>
        </form>
    </div>
    <p class="text-muted">Pencapaian dihitung dari rata-rata progres semua pelaksanaan program pada tahun ini. Nilai 1 tertinggi, nilai 5 terendah. Program tanpa pelaksanaan mendapat nilai 5.</p>
    <div class="d-flex flex-wrap gap-2 mb-5">
        @foreach([1 => '100% / selesai', 2 => '80–99,9%', 3 => '60–79,9%', 4 => '40–59,9%', 5 => '0–39,9% / tanpa pelaksanaan'] as $grade => $range)
        <span class="badge badge-light px-3 py-2">{{ $grade }} · {{ \App\Services\KpiAchievement::LABELS[$grade] }}: {{ $range }}</span>
        @endforeach
    </div>
    <div class="table-responsive">
        <table class="table table-row-dashed align-middle kpi-achievement-table">
            <thead style="background:#102a43;color:#fff;font-size:calc(1rem + 2px)"><tr>
                <th class="fw-bold text-white" style="width:60px">No.</th><th class="fw-bold text-white">Program kerja</th><th class="fw-bold text-white" style="width:180px">Pelaksanaan</th><th class="fw-bold text-white" style="width:150px">Pencapaian</th><th class="fw-bold text-white" style="width:120px">Nilai KPI</th>
            </tr></thead>
            <tbody>
            @forelse($achievements as $row)
                <tr><td class="text-center py-4">{{ $loop->iteration }}</td>
                <td class="py-4 text-break">{{ $row['program'] }}@if($row['archived'])<div class="text-muted fs-7">Program asal sudah dihapus</div>@endif</td>
                <td class="text-center">{{ $row['count'] ? $row['count'].' pelaksanaan' : 'Belum ada pelaksanaan' }}</td>
                <td class="text-center text-nowrap fw-semibold">{{ number_format($row['percentage'], 1, ',', '.') }}%</td>
                <td class="text-center"><span class="badge fs-5 {{ [1 => 'badge-light-success', 2 => 'badge-light-primary', 3 => 'badge-light-info', 4 => 'badge-light-warning', 5 => 'badge-light-danger'][$row['grade']] }}">{{ $row['grade'] }}</span></td></tr>
            @empty
                <tr><td colspan="5" class="text-center text-muted py-8">Belum ada program kerja untuk dinilai pada bidang ini.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @php($achievementSummary = \App\Services\KpiAchievement::summary($achievements))
    <div class="row g-3 mt-3 text-center">
        <div class="col-md-4"><div class="border rounded p-4 h-100 kpi-summary-total"><div class="fw-semibold mb-2">Total rata-rata pencapaian</div><div class="fs-2 fw-bold">{{ $achievementSummary['percentage'] === null ? '—' : number_format($achievementSummary['percentage'], 1, ',', '.').'%' }}</div></div></div>
        <div class="col-md-4"><div class="border rounded p-4 h-100 kpi-summary-completed"><div class="fw-semibold mb-2">Skor KPI</div><div class="fs-2 fw-bold">{{ $achievementSummary['grade'] ?? '—' }}</div></div></div>
        <div class="col-md-4"><div class="border rounded p-4 h-100 kpi-summary-in_progress"><div class="fw-semibold mb-2">Keterangan</div><div class="fs-2 fw-bold">{{ $achievementSummary['label'] }}</div></div></div>
    </div>
    <p class="text-muted fs-7 mt-3">Total rata-rata memakai pencapaian setiap program dengan bobot yang sama, termasuk program tanpa pelaksanaan (0%). Skor KPI ditentukan dari persentase total rata-rata.</p>
    <p class="text-muted fs-7 mt-3">Persentase rata-rata ditampilkan sampai satu angka desimal tanpa pembulatan ke atas.</p>
</section>
