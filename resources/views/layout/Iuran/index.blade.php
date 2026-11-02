@extends('master.app')

@section('content')
<style>
    #iuran-page #table_primary thead th,
    #iuran-detail-modal table thead th {
        font-size: calc(1rem + 2px);
    }

    #iuran-page #table_primary {
        table-layout: fixed;
        min-width: 860px;
    }

    #iuran-page #table_primary th,
    #iuran-page #table_primary td {
        padding: 1rem;
        vertical-align: middle;
    }

    #iuran-page #table_primary th:first-child {
        width: 6% !important;
    }

    #iuran-page #table_primary th:nth-child(2) {
        width: 28% !important;
    }

    #iuran-page #table_primary th:nth-child(3),
    #iuran-page #table_primary th:nth-child(4) {
        width: 20% !important;
    }

    #iuran-page #table_primary th:last-child {
        width: 26% !important;
    }

    #iuran-page #table_primary th:first-child,
    #iuran-page #table_primary td:first-child {
        text-align: center !important;
    }

    #iuran-page #table_primary th:nth-child(2),
    #iuran-page #table_primary td:nth-child(2) {
        text-align: left !important;
        overflow-wrap: anywhere;
    }

    #iuran-page #table_primary th:nth-child(n+3),
    #iuran-page #table_primary td:nth-child(n+3) {
        text-align: right !important;
        font-variant-numeric: tabular-nums;
    }

    #iuran-page #table_primary td:nth-child(n+3) {
        white-space: nowrap;
    }

    #iuran-page #table_primary thead th {
        background: var(--kt-gray-100, #f5f8fa);
        color: var(--kt-gray-700, #5e6278);
        font-weight: 600;
    }

    #iuran-page .dataTables_filter input,
    #iuran-page .dataTables_length select {
        border: 1px solid var(--kt-gray-300, #e4e6ef);
        border-radius: .475rem;
        padding: .5rem .75rem;
        background-color: var(--kt-body-bg, #fff);
        color: inherit;
    }

    #iuran-page .dataTables_filter input {
        max-width: 100%;
    }

    #iuran-page .dataTables_info,
    #iuran-page .dataTables_length,
    #iuran-page .dataTables_filter {
        color: var(--kt-gray-600, #7e8299);
    }
</style>
<div id="iuran-page" class="d-flex flex-column flex-column-fluid">
    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container container-fluid">
            <div class="card mb-6 mb-xl-8">
                <div class="table_data" style="display:block">
                    <div class="card-header border-0 pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold fs-3 mb-1">Iuran</span>
                            <span class="text-muted fw-normal fs-6 mt-1" id="total_peserta_aktif">Daftar Peserta 0</span>
                        </h3>
                        <div class="card-toolbar">
                            <!-- <a href="javascript:onAdd()" class="btn btn-sm btn-light-primary">
                                <span class="svg-icon svg-icon-2">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <rect opacity="0.5" x="11.364" y="20.364" width="16" height="2" rx="1"
                                            transform="rotate(-90 11.364 20.364)" fill="currentColor" />
                                        <rect x="4.36396" y="11.364" width="16" height="2" rx="1" fill="currentColor" />
                                    </svg>
                                </span>
                                Tambah Data</a> -->
                                <!--end::Svg Icon-->
                            <div class="fv-row mb-3">
                                <label for="year" class="form-label fw-semibold">Tahun</label>
                                <input type="number" class="form-control form-control-solid" id="year" name="year" min="1900" max="2099" step="1" value="{{ $iuranYear ?? now('Asia/Jakarta')->year }}">
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-3 pb-5">
                        <div class="alert alert-info mb-4">
                            <div>PHDP pada rekap adalah rata-rata PHDP dari bulan yang memiliki data pada tahun terpilih. Iuran peserta adalah jumlah PHDP bulanan × 7,5%.</div>
                            <div class="mt-1">Iuran + pengembangan dihitung dari dasar pengembangan × 7,5% × (1 + 4,5% ÷ 12). Jika seluruh data pengembangan tahun tersebut belum tersedia, sistem memakai estimasi iuran peserta × (1 + 4,5% ÷ 12).</div>
                            <div class="mt-1">Data kosong ditampilkan sebagai “Belum ada data”; nilai nol tetap Rp0. Klik nama peserta untuk melihat rincian bulanan.</div>
                        </div>
                        <p id="iuran-load-status" class="text-muted" role="status"></p>
                        <div class="table-responsive">
                            <table id="table_primary" class="table table-striped" style="width:100%">
                                <thead>
                                    <tr>
                                        <th class="text-left"><strong>No.</strong></th>
                                        <th class="text-center"><strong>Nama Peserta</strong></th>
                                        <th class="text-center"><strong>Rata-rata PHDP</strong></th>
                                        <th class="text-center"><strong>Iuran Peserta</strong></th>
                                        <th class="text-center"><strong>Iuran Peserta + Pengembangan</strong></th>
                                        <!-- <th class="text-center">Action</th> -->
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>

                @include('layout.Iuran.form')
            </div>
        </div>
        <!--end::Content container-->
    </div>
    <!--end::Content-->
</div>
<div class="modal fade" id="iuran-detail-modal" tabindex="-1" aria-labelledby="iuran-detail-title" aria-hidden="true"><div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable"><div class="modal-content">
<div class="modal-header"><h3 id="iuran-detail-title" class="modal-title">Rincian iuran peserta</h3><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button></div>
<div class="modal-body"><p id="iuran-detail-status" role="status">Memuat rincian...</p><div id="iuran-detail-content" class="table-responsive" hidden><table class="table table-striped"><thead><tr><th>Bulan</th><th class="text-end">PHDP</th><th class="text-end">Iuran peserta (7,5%)</th><th class="text-end">Iuran + pengembangan</th></tr></thead><tbody id="iuran-detail-rows"></tbody></table></div></div>
<div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button></div></div></div></div>
@endsection

@push('javascript')
    @include('layout.Iuran.js')
@endpush
