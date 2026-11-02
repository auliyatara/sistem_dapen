@extends('master.app')

@section('content')
<style>
    #table_primary.total-table th,
    #table_primary.total-table td {
        padding: 1rem 1.25rem;
        vertical-align: middle;
    }

    #table_primary.total-table {
        min-width: 640px;
        table-layout: fixed;
    }

    #table_primary.total-table thead th:first-child {
        width: 160px !important;
    }

    #table_primary.total-table thead th:nth-child(2) {
        width: 260px !important;
    }

    #table_primary.total-table thead th:nth-child(3) {
        width: auto !important;
    }

    #table_primary.total-table tbody td:not(:first-child) {
        font-variant-numeric: tabular-nums;
    }

    #table_primary.total-table thead th:first-child,
    #table_primary.total-table tfoot th:first-child {
        text-align: center !important;
    }

    #table_primary.total-table thead th:not(:first-child),
    #table_primary.total-table tbody td:not(:first-child),
    #table_primary.total-table tfoot th:not(:first-child),
    #table_primary.total-table tfoot .total-amount {
        text-align: left !important;
    }

    #table_primary.total-table tfoot th {
        background: var(--kt-primary-light, #eef6ff);
        border-top: 2px solid var(--kt-primary, #009ef7);
        padding-top: 1.5rem;
        padding-bottom: 1.5rem;
    }

    .total-table .total-amount {
        display: block;
        color: var(--kt-gray-900, #181c32);
        font-size: inherit;
        font-weight: 700;
        font-variant-numeric: tabular-nums;
        text-align: right;
        white-space: nowrap;
    }

    .total-table .input-group {
        max-width: 160px;
        margin-left: 0;
    }
</style>
<div class="d-flex flex-column flex-column-fluid">
    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container container-fluid">
            <div class="card mb-6 mb-xl-8">
                <div class="table_data" style="display:block">
                    <div class="card-header border-0 pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold fs-3 mb-1">Total</span>
                            <span class="text-muted fw-normal fs-6 mt-1">Rekap Iuran Seluruh Peserta</span>
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
                    <div class="card-body pt-3 py-3">
                        <form id="total-export-form" method="GET" action="{{ route('total.export') }}" class="d-flex flex-wrap align-items-center gap-3 mb-4">
                            <input type="hidden" name="preview" value="1">
                            <label class="fw-semibold mb-0">Ekspor tahun yang dipilih</label>
                            <button class="btn btn-sm btn-light-primary" type="submit" name="format" value="xlsx">Preview Excel</button>
                            <button class="btn btn-sm btn-light-primary" type="submit" name="format" value="pdf">Preview PDF</button>
                        </form>
                        <div id="total-reconciliation" class="alert alert-secondary mb-4" role="status">Memuat pencocokan dengan menu Iuran...</div>
                        <form id="total-export-form" method="GET" action="{{ route('total.export') }}" class="d-flex flex-wrap align-items-center gap-3 mb-4">
                            <input type="hidden" name="preview" value="1">
                            <label class="fw-semibold mb-0">Ekspor tahun yang dipilih</label>
                            <button class="btn btn-sm btn-light-primary" type="submit" name="format" value="xlsx">Preview Excel</button>
                            <button class="btn btn-sm btn-light-primary" type="submit" name="format" value="pdf">Preview PDF</button>
                        </form>
                        <div id="total-reconciliation" class="alert alert-secondary mb-4" role="status">Memuat pencocokan dengan menu Iuran...</div>
                        <div class="alert alert-info mb-4">
                            <div class="fw-semibold mb-1">Dasar perhitungan iuran pemberi kerja</div>
                            <div>Iuran pemberi kerja dihitung otomatis sebesar 5,7% dari PHDP, sedangkan iuran peserta sebesar 7,5% dari PHDP.</div>
                        </div>
                        <div class="table-responsive">
                            <p id="total-data-status" class="text-muted" role="status"></p>
                            <table id="table_primary" class="table table-striped total-table" style="width:100%">
                                <thead>
                                    <tr>
                                        <th class="text-left">Bulan</th>
                                        <th class="text-end">Iuran Seluruh Peserta</th>
                                        <th class="text-end">Iuran Pemberi Kerja</th>
                                        <!-- <th class="text-center">Action</th> -->
                                    </tr>
                                </thead>
                                <tbody></tbody>
                                <tfoot>
                                    <tr>
                                        <th scope="row" class="fw-bold text-nowrap">Total Iuran</th>
                                        <th>
                                            <span id="total_iuran_peserta" class="total-amount" aria-live="polite">Rp 0</span>
                                        </th>
                                        <th>
                                            <span id="total_iuran_pemberi_kerja" class="total-amount" aria-live="polite">Rp 0</span>
                                        </th>
                                    </tr>
                                </tfoot>
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
<div class="modal fade" id="total-detail-modal" tabindex="-1" aria-labelledby="total-detail-title" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable"><div class="modal-content">
        <div class="modal-header"><h3 id="total-detail-title" class="modal-title">Rincian iuran bulanan</h3><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button></div>
        <div class="modal-body"><p id="total-detail-status" role="status">Memuat rincian...</p><div id="total-detail-content" class="table-responsive" hidden>
            <table class="table table-striped"><thead><tr><th>Nama peserta</th><th>NIP</th><th class="text-end">PHDP</th><th class="text-end">Iuran peserta (7,5%)</th><th class="text-end">Iuran pemberi kerja (5,7%)</th></tr></thead><tbody id="total-detail-rows"></tbody><tfoot><tr><th colspan="3">Total bulan ini</th><th id="total-detail-peserta" class="text-end"></th><th id="total-detail-pemberi" class="text-end"></th></tr></tfoot></table>
        </div></div><div class="modal-footer"><button class="btn btn-light" type="button" data-bs-dismiss="modal">Tutup</button></div>
    </div></div>
</div>
<div class="modal fade" id="total-export-modal" tabindex="-1" aria-labelledby="total-export-title" aria-hidden="true"><div class="modal-dialog modal-xl modal-dialog-centered"><div class="modal-content">
<div class="modal-header"><h3 id="total-export-title" class="modal-title">Preview laporan Total</h3><button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Tutup"></button></div>
<div class="modal-body"><p id="total-export-status" role="status">Memuat preview...</p><iframe id="total-export-frame" title="Preview laporan Total" class="w-100 border rounded" style="height:65vh" hidden></iframe></div>
<div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button><a id="total-export-download" class="btn btn-primary" hidden>Download</a></div>
</div></div></div>
<div class="modal fade" id="total-export-modal" tabindex="-1" aria-labelledby="total-export-title" aria-hidden="true"><div class="modal-dialog modal-xl modal-dialog-centered"><div class="modal-content">
<div class="modal-header"><h3 id="total-export-title" class="modal-title">Preview laporan Total</h3><button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Tutup"></button></div>
<div class="modal-body"><p id="total-export-status" role="status">Memuat preview...</p><iframe id="total-export-frame" title="Preview laporan Total" class="w-100 border rounded" style="height:65vh" hidden></iframe></div>
<div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button><a id="total-export-download" class="btn btn-primary" hidden>Download</a></div>
</div></div></div>
@endsection

@push('javascript')
    @include('layout.Total.js')
@endpush
