@extends('master.app')
@section('content')
<style>
#dapen-dashboard .welcome {background:linear-gradient(115deg,#203e59,#326f92);color:white;border-radius:16px}
#dapen-dashboard .card {border:1px solid var(--kt-gray-200,#eff2f5);border-radius:14px;height:100%}
#dapen-dashboard .stat {border-top:3px solid var(--accent,#3995c6)}
#dapen-dashboard .stat-value {font-size:clamp(1.4rem,2vw,2rem);font-weight:700;font-variant-numeric:tabular-nums;overflow-wrap:anywhere}
#dapen-dashboard .monthly-chart {display:flex;align-items:end;gap:12px;height:240px;min-width:560px;padding:15px 8px 0}
#dapen-dashboard .month-column {flex:1;height:100%;display:flex;flex-direction:column;justify-content:end;align-items:center;gap:10px}
#dapen-dashboard .month-bar {width:100%;max-width:42px;background:#3995c6;border:0;border-radius:6px 6px 0 0;min-height:3px;padding:0}
#dapen-dashboard .month-bar:hover,#dapen-dashboard .month-bar:focus-visible {background:#225775;outline:2px solid #8bc9ed;outline-offset:3px}
#dapen-dashboard .month-name {font-size:.85rem;color:var(--kt-gray-600,#7e8299)}
#dapen-dashboard .retirement-row + .retirement-row {border-top:1px solid var(--kt-gray-200,#eff2f5)}
#dapen-dashboard #dashboard_informasi_list {display:grid;grid-auto-flow:column;grid-auto-columns:calc((100% - 32px)/3);gap:16px;overflow-x:auto;scroll-snap-type:x mandatory;scroll-padding-inline:0;padding-bottom:12px}
#dapen-dashboard .notice-item {display:flex;align-items:flex-start;gap:16px;padding:20px;background:var(--kt-body-bg,#fff);border:1px solid var(--kt-gray-200,#eff2f5);border-radius:12px;min-width:0;scroll-snap-align:start}
#dapen-dashboard .notice-item:last-child {margin-bottom:0}
#dapen-dashboard .notice-calendar {flex:0 0 72px;text-align:center;border:1px solid var(--kt-gray-300,#e4e6ef);border-radius:10px;overflow:hidden;color:var(--kt-gray-800,#3f4254);background:var(--kt-gray-100,#f5f8fa)}
#dapen-dashboard .notice-month {display:block;background:#326f92;color:white;font-size:.75rem;font-weight:600;letter-spacing:.08em;padding:5px}
#dapen-dashboard .notice-day {display:block;font-size:1.9rem;font-weight:700;line-height:1.2;padding-top:6px;font-variant-numeric:tabular-nums}
#dapen-dashboard .notice-year {display:block;font-size:.8rem;color:var(--kt-gray-600,#7e8299);padding:2px 0 7px}
#dapen-dashboard .notice-content {min-width:0;flex:1;overflow-wrap:anywhere}
#dapen-dashboard .notice-title {font-size:1.15rem;line-height:1.5;font-weight:600;margin:0 0 6px;color:var(--kt-gray-900,#181c32)}
#dapen-dashboard .notice-description {margin:0;color:var(--kt-gray-700,#5e6278);line-height:1.7;white-space:pre-line}
@media(max-width:575px) {#dapen-dashboard .notice-item {gap:12px;padding:14px} #dapen-dashboard .notice-calendar {flex-basis:60px}}
@media(max-width:1199px) {#dapen-dashboard #dashboard_informasi_list {grid-auto-columns:calc((100% - 16px)/2)}}
@media(max-width:767px) {#dapen-dashboard #dashboard_informasi_list {grid-auto-columns:100%}}
</style>
<div id="kt_app_content" class="app-content flex-column-fluid"><div id="kt_app_content_container" class="app-container container-fluid"><div id="dapen-dashboard">
    <div class="welcome p-6 p-lg-8 mb-6 d-flex flex-wrap justify-content-between align-items-center gap-4">
        <div><div class="d-flex align-items-center gap-3 mb-4">
            <img src="{{ asset('public/images/dapen.png') }}" alt="Logo Dana Pensiun UMM" width="56" height="56" class="flex-shrink-0 rounded-circle" style="object-fit:contain;">
            <span class="fw-semibold">SIDIG UMM · Sistem Informasi Dana Pensiun Terintegrasi Dana Pensiun Pegawai Universitas Muhammadiyah Malang</span>
        </div>
        <div><h1 class="text-white mb-3">Selamat datang, <strong>{{ auth()->user()->user_nama ?? 'User' }}</strong>.</h1></div></div>
        <div class="rounded-3 p-4" style="background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.18)">
            <div id="dashboard-live-date" class="opacity-75 mb-2">{{ now('Asia/Jakarta')->translatedFormat('d F Y') }}</div>
            <div class="d-flex align-items-baseline gap-2">
                <time id="dashboard-live-clock" class="fw-bold" style="font-size:2rem;letter-spacing:.05em;font-variant-numeric:tabular-nums">{{ now('Asia/Jakarta')->format('H:i:s') }}</time>
                <span class="fs-7 opacity-75">WIB</span>
            </div>
        </div>
    </div>
    <section class="card mb-6 bg-light-primary" aria-labelledby="dashboard-informasi-title" style="border-left:4px solid #3995c6">
        <div class="card-header border-0 py-4 d-flex flex-nowrap align-items-center justify-content-between gap-3" style="min-height:0">
            <h2 class="card-title fw-bold m-0" id="dashboard-informasi-title">
                <span class="svg-icon svg-icon-2 text-primary me-3" aria-hidden="true">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 10v5h4l10 4V6L8 10H4Z" stroke-linejoin="round"/><path d="m8 15 2 6H6l-2-6M21 10v5" stroke-linecap="round"/></svg>
                </span>
                Informasi
            </h2>
            <div class="d-flex align-items-center gap-2 flex-shrink-0">
                <span class="text-muted fs-7 me-2 d-none d-md-inline">Geser untuk informasi lainnya</span>
                <button type="button" class="btn btn-sm btn-icon btn-light-primary" aria-label="Informasi sebelumnya" aria-controls="dashboard_informasi_list" onclick="document.getElementById('dashboard_informasi_list').scrollBy({left: -document.getElementById('dashboard_informasi_list').clientWidth, behavior: 'smooth'})">&lsaquo;</button>
                <button type="button" class="btn btn-sm btn-icon btn-light-primary" aria-label="Informasi berikutnya" aria-controls="dashboard_informasi_list" onclick="document.getElementById('dashboard_informasi_list').scrollBy({left: document.getElementById('dashboard_informasi_list').clientWidth, behavior: 'smooth'})">&rsaquo;</button>
            </div>
        </div>
        <div class="card-body pt-2 pb-4">
            <div id="dashboard_informasi_list" aria-live="polite" tabindex="0" aria-label="Daftar informasi"><div class="text-muted">Memuat informasi...</div></div>
        </div>
    </section>
    <div class="row g-5 mb-6" aria-live="polite">
        <div class="col-sm-6 col-xl-3"><div class="card stat"><div class="card-body p-5"><div class="text-muted mb-3">Peserta aktif</div><div class="stat-value" id="dashboard-active">—</div><div class="text-muted fs-7 mt-2">Guru besar, dosen, dan karyawan</div></div></div></div>
        <div class="col-sm-6 col-xl-3"><div class="card stat" style="--accent:#8b71c4"><div class="card-body p-5"><div class="text-muted mb-3">Pensiunan</div><div class="stat-value" id="dashboard-retired">—</div><div class="text-muted fs-7 mt-2">Normal, janda/duda, anak, dan dipercepat</div></div></div></div>
        <div class="col-sm-6 col-xl-3"><div class="card stat" style="--accent:#39a58c"><div class="card-body p-5"><div class="text-muted mb-3">Iuran peserta · <span class="dashboard-year-label">{{ $iuranYear }}</span></div><div class="stat-value" id="dashboard-total">—</div><div class="text-muted fs-7 mt-2">Akumulasi pada tahun pilihan</div></div></div></div>
        <div class="col-sm-6 col-xl-3"><div class="card stat" style="--accent:#dda34d"><div class="card-body p-5"><div class="text-muted mb-3">Mendekati pensiun</div><div class="stat-value">{{ number_format($upcomingCount,0,',','.') }}</div><div class="text-muted fs-7 mt-2">Dalam 12 bulan dari hari ini</div></div></div></div>
    </div>
    <div class="row g-5 mb-6">
        <div class="col-xl-8"><div class="card"><div class="card-header border-0 pt-5 align-items-center gap-3"><div><h3 class="mb-2">Perkembangan iuran peserta</h3><div class="text-muted fs-7">Jumlah iuran per bulan dalam rupiah</div></div><div class="d-flex align-items-center gap-3"><label for="dashboard-year">Tahun</label><input type="number" id="dashboard-year" class="form-control form-control-solid w-100px" min="1900" max="2099" value="{{ $iuranYear }}"></div></div>
        <div class="card-body pt-4"><div id="dashboard-chart-status" class="text-muted" role="status">Memuat iuran...</div><div class="overflow-auto"><div id="dashboard-monthly-chart" class="monthly-chart" aria-label="Grafik iuran bulanan"></div></div><div id="dashboard-chart-caption" class="text-muted fs-7 mt-4" aria-live="polite" style="min-height:24px"></div>
        <details class="mt-3"><summary class="text-primary cursor-pointer">Lihat rincian bulanan</summary><table class="table table-row-dashed mt-3"><thead><tr><th>Bulan</th><th class="text-end">Iuran peserta</th></tr></thead><tbody id="dashboard-monthly-table"></tbody></table></details></div></div></div>
        <div class="col-xl-4"><div class="card"><div class="card-header border-0 pt-5"><div><h3 class="mb-2">Mendekati masa pensiun</h3><div class="text-muted fs-7">Lima jadwal terdekat dalam 12 bulan</div></div></div><div class="card-body pt-3">
        @forelse($upcoming as $peserta)
            <div class="retirement-row py-4"><div class="fw-semibold mb-1">{{ $peserta->kepesertaan_nama }}</div><div class="d-flex flex-wrap justify-content-between gap-2 fs-7"><span class="text-muted">{{ $peserta->kepesertaan_status_jabatan }}</span><span class="badge badge-light-warning">{{ \Carbon\Carbon::parse($peserta->kepesertaan_terhitung_mulai_tanggal_pensiun)->translatedFormat('d M Y') }}</span></div></div>
        @empty
            <div class="rounded bg-light p-5 text-muted">Belum ada peserta dengan jadwal pensiun tercatat dalam 12 bulan ke depan.</div>
        @endforelse
        <p class="text-muted fs-8 mt-4 mb-0">Berdasarkan tanggal pensiun yang tercatat pada data peserta.</p></div></div></div>
    </div>
    <div class="row g-5 mb-6">
    @foreach([['peserta','Komposisi peserta aktif'],['pensiunan','Komposisi pensiunan'],['pensiun_lain','Pensiun lainnya']] as [$key,$title])
        <div class="col-lg-4"><div class="card"><div class="card-header border-0 pt-5"><h3 class="card-title fw-bold">{{ $title }}</h3></div><div class="card-body pt-2"><div id="chart_{{ $key }}_dashboard" style="height:230px"></div><div id="legend_{{ $key }}_dashboard" class="d-flex flex-wrap gap-3 mt-3"></div></div></div></div>
    @endforeach
    </div>
</div></div></div>
@endsection
@push('javascript')
    @include('layout.Dashboard.js')
@endpush
