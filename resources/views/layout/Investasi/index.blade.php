@extends('master.app')

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container container-fluid">
            @include('layout.Investasi.form')
            <div class="card shadow-sm table_data" style="display:block">
                <div class="card-header">
                    <h3 class="card-title">Data Investasi</h3>
                    @if($edit)
                    <div class="card-toolbar">
                        <a href="javascript:onAdd()" class="btn btn-sm btn-primary">
                            <span class="svg-icon svg-icon-2">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <rect opacity="0.5" x="11.364" y="20.364" width="16" height="2" rx="1"
                                        transform="rotate(-90 11.364 20.364)" fill="currentColor" />
                                    <rect x="4.36396" y="11.364" width="16" height="2" rx="1"
                                        fill="currentColor" />
                                </svg>
                            </span>
                           Tambah Data</a>
                    </div>
                    @endif
                   
                </div>
                <div class="card-body pt-3 py-3">
                    <div class="card-body pt-3 py-3">
                        <div class="table-responsive">
                            <table id="table_investasi" class="table table-striped" style="width:100%;border-collapse: collapse;">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="vertical-align: middle;min-width:400px;" rowspan="2">Jenis Investasi</th>
                                        <th class="text-center" style="vertical-align: middle;min-width:150px;" rowspan="2">Action</th>

                                        <th class="text-center p-2" colspan="12">
                                            <div class="form-floating" style="width: 20%; margin:0 auto"> 
                                                <input type="number" class="form-control form-control-solid"
                                                    id="investasi_tahun_table" placeholder="" value="" oninput="onInitTable()"/>
                                                <label for="floatingInput"><span>Tahun</span></label>
                                            </div>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th class="text-center">Jan</th>
                                        <th class="text-center">Feb</th>
                                        <th class="text-center">Mar</th>
                                        <th class="text-center">Apr</th>
                                        <th class="text-center">Mei</th>
                                        <th class="text-center">Jun</th>
                                        <th class="text-center">Jul</th>
                                        <th class="text-center">Agu</th>
                                        <th class="text-center">Sep</th>
                                        <th class="text-center">Okt</th>
                                        <th class="text-center">Nov</th>
                                        <th class="text-center">Des</th>
                                    </tr>
                                </thead>
                                <tbody id="table_body"></tbody>
                            </table>

                            <style>
                                .table_investasi th,
                                .table_investasi td {
                                    border: 1px solid #ddd;
                                    padding: 10px;
                                    text-align: left;
                                }

                            </style>
                        </div>
                    </div>
                </div>
            </div>   
        </div>
        <!--end::Content container-->
    </div>
    <!--end::Content-->
    @include('layout.Investasi.modal')
</div>

@endsection

@push('javascript')
    @include('layout.investasi.js')
@endpush