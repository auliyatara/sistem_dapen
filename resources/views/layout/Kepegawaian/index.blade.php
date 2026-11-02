@extends('master.app')

@section('content')
<style>
    #pegawai-page #table_primary {table-layout:fixed;min-width:1150px}
    #pegawai-page #table_primary th,#pegawai-page #table_primary td {padding:14px 18px;vertical-align:middle;overflow-wrap:anywhere}
    #pegawai-page #table_primary thead th {background:#102a43!important;color:#fff!important;font-weight:700!important;text-align:center;border-bottom:2px solid #254b70}
    #pegawai-page #table_primary th:nth-child(1) {width:18%!important}
    #pegawai-page #table_primary th:nth-child(2) {width:20%!important}
    #pegawai-page #table_primary th:nth-child(3) {width:14%!important}
    #pegawai-page #table_primary th:nth-child(4) {width:15%!important}
    #pegawai-page #table_primary th:nth-child(5) {width:21%!important}
    #pegawai-page #table_primary th:nth-child(6) {width:12%!important}
    #pegawai-page #table_primary td:last-child {min-width:120px;white-space:nowrap}
    #pegawai-page #table_primary td:nth-child(1),#pegawai-page #table_primary td:nth-child(5) {text-align:left!important}
</style>
<div id="pegawai-page" class="d-flex flex-column flex-column-fluid">
    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container container-fluid">
            <div class="card mb-6 mb-xl-8">
                <div class="table_data" style="display:block">
                    <div class="card-header border-0 pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold fs-3 mb-1">Kepegawaian</span>
                        </h3>
                        <div class="card-toolbar">
                            @if($edit)
                            <a href="javascript:onAdd()" class="btn btn-sm btn-light-primary">
                                <span class="svg-icon svg-icon-2">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <rect opacity="0.5" x="11.364" y="20.364" width="16" height="2" rx="1"
                                            transform="rotate(-90 11.364 20.364)" fill="currentColor" />
                                        <rect x="4.36396" y="11.364" width="16" height="2" rx="1" fill="currentColor" />
                                    </svg>
                                </span>
                                <!--end::Svg Icon-->Tambah Data</a>
                            @endif
                        </div>
                    </div>
                    <div class="card-body pt-3 py-3">
                        <div class="table-responsive">
                            <table id="table_primary" class="table table-striped" style="width:100%">
                                <thead>
                                    <tr>
                                        <th class="text-center">Nama</th>
                                        <th class="text-center">Email</th>
                                        <th class="text-center">Nomor Telepon</th>
                                        <th class="text-center">Tanggal Pengangkatan</th>
                                        <th class="text-center">Jabatan</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>

                @include('layout.Kepegawaian.form')
            </div>
        </div>
        <!--end::Content container-->
    </div>
    <!--end::Content-->
</div>
@endsection

@push('javascript')
    @include('layout.Kepegawaian.js')
@endpush
