@extends('master.app')

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container container-fluid">
            @include('layout.Program.form')
            <div class="card shadow-sm table_data" style="display:block">
                <div class="card-header">
                    <h3 class="card-title">Data Program</h3>
                </div>
                <div class="card-body pt-3 py-3">
                    <div class="row">
                        <div class="col-4" id="program_list">
                            <ul class="nav nav-tabs nav-pills flex-row border-0 flex-md-column me-5 mb-3 mb-md-0 fs-6 min-w-lg-200px"></ul>
                        </div>
        
                        <div class="card shadow-sm col-8">
                            <div class="card-body">
                                <div class="tab-content" id="myTabContent">
                                    <div class="tab-pane fade show active" id="tab_table" role="tabpanel">
                                        <div class="button_cetak"></div>
                                        <div class="table-responsive">
                                            <table id="table_primary" class="table table-striped" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center">No</th>
                                                        <th class="text-center">Nama Program</th>
                                                        <th class="text-center" style="width:300px">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="table_list"></tbody>
                                            </table>
                                        </div>
                                    </div>
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

<div class="modal fade bd-example-modal-lg" id="detail_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Detail Program <span id="modal_title"></span></h3>
          </div>
          <div class="modal-body">
            <div class="row">
                <div class="col-4">
                    <ul class="nav nav-tabs nav-pills flex-row border-0 flex-md-column me-5 mb-3 mb-md-0 fs-6 min-w-lg-200px">
                        <li class="nav-item w-10 me-0 mb-md-2">
                            <a class="nav-link w-100 active btn btn-flex btn-active-light-success" data-bs-toggle="tab" href="#tab_pelaksanaan">
                                <span class="svg-icon fs-2"><svg>...</svg></span>
                                <span class="d-flex flex-column align-items-start">
                                    <span class="fs-4 fw-bold">Pelaksanaan</span>
                                    {{-- <span class="fs-7">Description</span> --}}
                                </span>
                            </a>
                        </li>
                        <li class="nav-item w-100">
                            <a class="nav-link w-100 btn btn-flex btn-active-light-success" data-bs-toggle="tab" href="#tab_evaluasi">
                                <span class="svg-icon fs-2"><svg>...</svg></span>
                                <span class="d-flex flex-column align-items-start">
                                    <span class="fs-4 fw-bold">Evaluasi</span>
                                    {{-- <span class="fs-7">Description</span> --}}
                                </span>
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="card shadow-sm col-8">
                    <div class="card-body">
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="tab_pelaksanaan" role="tabpanel">
                                <ol id="list_tab_pelaksanaan"></ol>
                            </div>
                            <div class="tab-pane fade" id="tab_evaluasi" role="tabpanel">
                                <ol id="list_tab_evaluasi"></ol>
                            </div>
                        </div>
                    </div>
                </div>     
            </div>
          </div>
      </div>
    </div>
</div>

<div class="modal fade bd-example-modal-md" id="print_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Cetak Dokumen<span id="modal_title"></span></h3>
          </div>
          <div class="modal-body">
            <input type="checkbox" name="" id="select_all" onclick="onTriggerChange()"> <label for="select_all"><b>Pilih Semua</b></label>
            <table class="table table-striped" style="width:100%">
                <thead>
                    <tr>
                        <th class="text-center;" style="font-weight:bold">Checklist</th>
                        <th class="text-center;" style="font-weight:bold">Nama Program</th>
                    </tr>
                </thead>
                <tbody id="list_print_program"></tbody>
            </table>
            <button type="button" class="btn btn-sm btn-success" style="width: 100%"
                onclick="onPrint()">
                <span class="svg-icon svg-icon-2">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <rect opacity="0.5" x="11.364" y="20.364" width="16" height="2"
                            rx="1" transform="rotate(-90 11.364 20.364)" fill="currentColor" />
                        <rect x="4.36396" y="11.364" width="16" height="2" rx="1"
                            fill="currentColor" />
                    </svg>
                </span>Cetak Dokumen PDF
            </button>
            <button type="button" class="btn btn-sm btn-primary" style="width: 100%"
                onclick="exportExcel()">
                <span class="svg-icon svg-icon-2">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <rect opacity="0.5" x="11.364" y="20.364" width="16" height="2"
                            rx="1" transform="rotate(-90 11.364 20.364)" fill="currentColor" />
                        <rect x="4.36396" y="11.364" width="16" height="2" rx="1"
                            fill="currentColor" />
                    </svg>
                </span>Cetak Dokumen Excel
            </button>
          </div>
      </div>
    </div>
</div>


<div class="modal fade bd-example-modal-lg" id="preview_pdf" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Preview Dokumen<span id="modal_title"></span></h3>
          </div>
          <div class="modal-body">
            <div id="pdf-container"></div>
          </div>
      </div>
    </div>
</div>
@endsection

@push('javascript')
    @include('layout.Program.js')
@endpush