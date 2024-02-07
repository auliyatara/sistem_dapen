@extends('master.app')

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container container-fluid">
            @include('layout.HakAksesUser.form')
            <div class="card shadow-sm table_data" style="display:block">
                <div class="card-header">
                    <h3 class="card-title">Hak Akses Pengguna</h3>
                    <div class="card-toolbar">
                    </div>
                </div>
                <div class="card-body pt-3 py-3">
                    <div class="row">
                        <div class="col-6">
                                                    <a href="javascript:onAdd()" class="btn btn-sm btn-primary">
                            <span class="svg-icon svg-icon-2">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <rect opacity="0.5" x="11.364" y="20.364" width="16" height="2" rx="1"
                                        transform="rotate(-90 11.364 20.364)" fill="currentColor" />
                                    <rect x="4.36396" y="11.364" width="16" height="2" rx="1" fill="currentColor" />
                                </svg>
                            </span>
                            <!--end::Svg Icon-->Tambah Data</a>
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="tab_table" role="tabpanel">
                                    <div class="table-responsive">
                                        <table id="table_primary" class="table table-striped" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">No</th>
                                                    <th class="text-center">Nama Role</th>
                                                    <th class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
        
                        <div class="card shadow-sm col-6">
                            <div class="card-body">
                                <h4>Daftar Menu</h4>
                                <form action="javascript:onSave()" id="formDataAccess" method="POST" autocomplete="off">
                                        @csrf
                                        <input type="hidden" name="user_id" id="user_id">
                                        <div class="menu_list">Pilih Salah Satu Role Terlebih Dahulu</div>
                                        <div id="list_menu_1" style="width:10px"></div>
                                        <div class="button_form"></div>
                                </form>
                            </div>
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
    @include('layout.HakAksesUser.js')
@endpush