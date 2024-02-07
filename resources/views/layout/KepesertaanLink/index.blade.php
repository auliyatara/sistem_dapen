@extends('master.app')

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container container-fluid">
            <div class="row">
                <div class="col-12" style="margin-top:20px">
                    <div class="card shadow-sm table_data" style="display:block">
                        <div class="card-header">
                            <h3 class="card-title">Kepesertaan Link</h3>
                        </div>
                        <div class="card-body pt-3 py-3">
                            <div class="card-body pt-3 py-3">
                                <div class="table-responsive">
                                    <form action="javascript:onSave()" id="formData" method="POST" autocomplete="off">
                                        <table id="table_primary" class="table table-striped" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">No</th>
                                                    <th class="text-center">Nama Link</th>
                                                    <th class="text-center">Url Link</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>

                                        @if($edit)
                                            <button type="submit" class="btn btn-sm btn-success" style="width: 100%">
                                                <span class="svg-icon svg-icon-2">
                                                </span>Simpan Link Data
                                            </button>
                                        @endif
                                        
                                    </form>
                                </div>
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
    @include('layout.KepesertaanLink.js')
@endpush