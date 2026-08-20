@extends('master.app')

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container container-fluid">
            <div class="card mb-6 mb-xl-8">
                <div class="table_data" style="display:block">
                    <div class="card-header border-0 pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold fs-3 mb-1">Iuran</span>
                            <span class="card-label fw-bold fs-3 mb-1" id="total_peserta_aktif">Daftar Peserta 0</span>
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
                            <div class="fv-row mb-5">
                                <input type="number" class="form-control form-control-solid" id="year" name="year" min="1900" max="2099" step="1" value="{{ $iuranYear ?? now()->year }}">
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-3 py-3">
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
