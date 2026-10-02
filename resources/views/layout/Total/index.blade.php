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
                                <input type="number" class="form-control form-control-solid" id="year" name="year" min="1900" max="2099" step="1" value="{{ $iuranYear ?? now()->year }}">
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-3 py-3">
                        <div class="table-responsive">
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
@endsection

@push('javascript')
    @include('layout.Total.js')
@endpush
