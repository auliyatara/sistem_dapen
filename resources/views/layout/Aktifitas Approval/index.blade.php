@extends('master.app')

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container container-fluid">
            <div class="card shadow-sm table_data" style="display:block">
                <div class="card-header">
                    <h3 class="card-title">Persetujuan Aktifitas</h3>
                    <div class="card-toolbar"></div>
                </div>
                <div class="card-body pt-3 py-3">
                    <div class="card-body pt-3 py-3">
                        <div class="table-responsive">
                            <table id="table_primary" class="table table-striped" style="width:100%">
                                <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th class="text-center">Nama Aktifitas</th>
                                        <th class="text-center">Status</th>
                                        @if($edit)
                                            <th class="text-center" style="width:300px">Action</th>
                                        @endif
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

<div class="modal fade bd-example-modal-lg" id="detail_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Verifikasi Dokumen <span id="modal_title"></span></h3>
          </div>
          <div class="modal-body">
            <table class="table table-striped">
                <tr>
                    <th class="text-center">No</th>
                    <th class="text-center">Nama Dokumen</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Preview Dokumen</th>
                </tr>
                <tbody id="detail_dokumen_list">
                    <tr>
                        <td class="text-center" colspan="2">No Data Available</td>
                    </tr>
                </tbody>
            </table>
            <form action="javascript:onSave()" id="formData" method="POST" autocomplete="off">
                <input type="hidden" name="aktifitas_id">
                <input type="hidden" name="aktifitas_user_id">
                <input type="hidden" name="aktifitas_nama">
                <div class="row mb-7 mt-7">
                    <div class="col-6  mb-7">
                        <label for="">Nama Pengaju</label>
                        <input type="text" class="form-control form-control-solid" name="user_nama" disabled placeholder="Nama Pengaju">
                    </div>
                    <div class="col-6  mb-7">
                        <label for="">Bidang</label>
                        <input type="text" class="form-control form-control-solid" name="role_name" disabled placeholder="Bidang">
                    </div>
                    <div class="col-12  mb-7">
                        <label for="">Komentar</label>
                        <textarea name="aktifitas_komentar" id="" cols="10" rows="5" class="form-control form-control-solid" placeholder="Komentar Verifikasi"></textarea>
                    </div>
                    <div class="col-12  mb-7">
                        <label for="">Status</label>

                        <select name="aktifitas_status" id="aktifitas_status" class="form-control form-control-solid mt-3">
                            <option value="" disabled selected>Pilih Status Verifikasi</option>
                            <option value="1">Disetujui</option>
                            <option value="2">Ditolak</option>
                            <option value="3">Direvisi</option>
                        </select>
                    </div>
                </div>
                
                
                <button type="submit" class="btn btn-lg btn-primary mt-1 button_submit" style="width: 100%">Simpan Data
                    <span class="svg-icon svg-icon-3 ms-1 me-0">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <rect opacity="0.5" x="18" y="13" width="13" height="2"
                                rx="1" transform="rotate(-180 18 13)" fill="currentColor"></rect>
                            <path
                                d="M15.4343 12.5657L11.25 16.75C10.8358 17.1642 10.8358 17.8358 11.25 18.25C11.6642 18.6642 12.3358 18.6642 12.75 18.25L18.2929 12.7071C18.6834 12.3166 18.6834 11.6834 18.2929 11.2929L12.75 5.75C12.3358 5.33579 11.6642 5.33579 11.25 5.75C10.8358 6.16421 10.8358 6.83579 11.25 7.25L15.4343 11.4343C15.7467 11.7467 15.7467 12.2533 15.4343 12.5657Z"
                                fill="currentColor"></path>
                        </svg>
                    </span>
                </button>     
            </form>   
          </div>
      </div>
    </div>
</div>

<div class="modal fade bd-example-modal-lg" id="detail_list_preview" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true" style="z-index:9999999">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Preview Dokumen <span id="modal_title"></span></h3>
            </div>
            <div class="modal-body">
                <div class="preview_file"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('javascript')
    @include('layout.Aktifitas Approval.js')
@endpush

