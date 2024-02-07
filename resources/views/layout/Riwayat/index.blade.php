@extends('master.app')

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container container-fluid">
            <div class="card shadow-sm table_data" style="display:block">
                <div class="card-header">
                    <h3 class="card-title">Riwayat System Log</h3>
                    @if($edit)
                        <div class="card-toolbar">
                            <a href="javascript:onDelete()" class="btn btn-sm btn-danger">
                                Reset Riwayat</a>
                        </div>
                    @endif
                    
                </div>
                <div class="card-body pt-3 py-3">
                    <div class="card-body pt-3 py-3">
                        <div class="table-responsive">
                            <table id="table_primary" class="table table-striped" style="width:100%">
                                <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th class="text-center">Nama</th>
                                        <th class="text-center">Role</th>
                                        <th class="text-center">Aktifitas</th>
                                        <th class="text-center">Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>   
        </div>
        <!--end::Content container-->
    </div>
    <!--end::Content-->
</div>

@endsection

@push('javascript')
    @include('layout.riwayat.js')
@endpush