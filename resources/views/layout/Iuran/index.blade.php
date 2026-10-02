@extends('master.app')

@section('content')
<style>
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
                                <input type="number" class="form-control form-control-solid" id="year" name="year" min="1900" max="2099" step="1" value="{{ $iuranYear ?? now()->year }}">
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-3 pb-5">
                        <div class="table-responsive">
                            <table id="table_primary" class="table table-striped" style="width:100%">
                                <thead>
                                    <tr>
                                        <th class="text-left">No.</th>
                                        <th class="text-center">Nama Peserta</th>
                                        <th class="text-center">PHDP</th>
                                        <th class="text-center">Iuran Peserta</th>
                                        <th class="text-center">Iuran Peserta + Pengembangan</th>
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
@endsection

@push('javascript')
    @include('layout.Iuran.js')
@endpush
