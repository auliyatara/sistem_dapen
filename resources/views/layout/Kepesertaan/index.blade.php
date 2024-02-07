@extends('master.app')

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container container-fluid">
            <div class="row">
                <div class="col-12">
                    @include('layout.Kepesertaan.form')
                </div>
                <div class="col-12" style="margin-top:20px">
                    <div class="card shadow-sm table_data" style="display:block">
                        <div class="card-header">
                            <h3 class="card-title">Kepesertaan</h3>
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
                                    <!--end::Svg Icon-->Tambah Data</a>
                            </div>
                            @endif
                           
                            
                        </div>
                        <div class="card-body pt-3 py-3">
                            <div class="card-body pt-3 py-3">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="card shadow-sm mt-1" style="background-color:rgb(80,205,137);">
                                            <div class="card-body pt-3 p-1">
                                                <h3 style="color:white;text-align:center">Peserta</h3>
                                                <h1 id="count_peserta" style="color:white;font-size:50px;text-align:center">
                                                    0</h1>
                                                    <div class="d-flex justify-content-around p-1">
                                                        <div class="card shadow-sm"
                                                    style="width:33%;background-color:rgb(56, 116, 83);color:white;cursor:pointer"
                                                    onclick="onInitFilter(1,'Guru Besar')">
                                                    <div class="card-body pt-3 p-1">
                                                        <h5 style="color:white;text-align:center">Guru Besar</h5>
                                                        <h4 id="count_guru_besar" style="color:white;text-align:center">0</h4>
                                                    </div>
                                                </div>
                                                <div class="card shadow-sm"
                                                    style="width:33%;background-color:rgb(56, 116, 83);color:white;cursor:pointer"
                                                    onclick="onInitFilter(1,'Dosen')">
                                                    <div class="card-body pt-3 p-1">
                                                        <h5 style="color:white;text-align:center">Dosen</h5>
                                                        <h4 id="count_dosen" style="color:white;text-align:center">0</h4>
                                                    </div>
                                                </div>
                                                <div class="card shadow-sm"
                                                    style="width:33%;background-color:rgb(56, 116, 83);color:white;cursor:pointer"
                                                    onclick="onInitFilter(1,'Karyawan')">
                                                    <div class="card-body pt-3 p-1">
                                                        <h5 style="color:white;text-align:center">Karyawan</h5>
                                                        <h4 id="count_karyawan" style="color:white;text-align:center">0</h4>
                                                    </div>
                                                </div>
                                                    </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card shadow-sm mt-1" style="background-color:rgb(132, 159, 248);">
                                            <div class="card-body pt-3 p-1">
                                                <h3 style="color:white;text-align:center">Pensiun</h3>
                                                <h1 id="count_pensiunan"
                                                    style="color:white;font-size:50px;text-align:center">0</h1>
                                                    <div class="d-flex justify-content-around p-1">
                                                        <div class="card shadow-sm"
                                                            style="width:33%;background-color:rgb(54, 73, 134);color:white;cursor:pointer"
                                                            onclick="onInitFilter(2,'Pensiun Normal')">
                                                            <div class="card-body pt-3 p-1">
                                                                <h5 style="color:white;text-align:center">Normal</h5>
                                                                <h4 id="count_pensiun_normal" style="color:white;text-align:center">0
                                                                </h4>
                                
                                                            </div>
                                                        </div>
                                                        <div class="card shadow-sm"
                                                            style="width:33%;background-color:rgb(54, 73, 134);color:white;cursor:pointer"
                                                            onclick="onInitFilter(2,'Pensiun Anak')">
                                                            <div class="card-body pt-3 p-1">
                                                                <h5 style="color:white;text-align:center">Anak</h5>
                                                                <h4 id="count_pensiun_anak" style="color:white;text-align:center">0
                                                                </h4>
                                                            </div>
                                                        </div>
                                                        <div class="card shadow-sm"
                                                            style="width:33%;background-color:rgb(54, 73, 134);color:white;cursor:pointer"
                                                            onclick="onInitFilter(2,'Pensiun Janda Duda')">
                                                            <div class="card-body pt-3 p-1">
                                                                <h5 style="color:white;text-align:center">Janda/Duda</h5>
                                                                <h4 id="count_pensiun_janda_duda"
                                                                    style="color:white;text-align:center">0</h4>
                                
                                                            </div>
                                                        </div>
                                                    </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card shadow-sm mt-1" style="background-color:rgb(249, 103, 103);">
                                            <div class="card-body pt-3 py-3">
                                                <h3 style="color:white;text-align:center">Pensiunan Ditunda</h3>
                                                <h2 id="count_pensiun_ditunda"
                                                    style="color:white;font-size:50px;text-align:center">0</h2>
                                            </div>
                                        </div>
                                        <div class="card shadow-sm mt-1" style="background-color:rgb(93, 92, 59);">
                                            <div class="card-body pt-3 py-3">
                                                <h3 style="color:white;text-align:center">Pensiun Sekaligus</h3>
                                                <h2 id="count_pensiunan_sekaligus"
                                                    style="color:white;font-size:50px;text-align:center">0</h2>
                                                
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">

                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <label style="font-weight: bold">Filter Tampilan:</label>
                                    </div>
                                    <div class="col-9">
                                        <select class="form-control" id="tipe_filter" onchange="onInitFilter()" style="width:100%">
                                            <option value="">Pilih Salah Satu</option>
                                            <option value="0">Semua</option>
                                            <option value="1">Peserta</option>
                                            <option value="2">Pensiunan</option>
                                            <option value="3">Pensiun Ditunda</option>
                                            <option value="4">Pensiunan Sekaligus</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            
                            <div class="table-responsive">
                                <table id="table_primary" class="table table-striped" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th class="text-center">No</th>
                                            <th class="text-center">Nama</th>
                                            <th class="text-center">NIP</th>
                                            <th class="text-center">Tempat, Tanggal Lahir</th>
                                            <th class="text-center">Jenis Kelamin</th>
                                            {{-- <th class="text-center">Email</th>
                                            <th class="text-center">Telephone</th> --}}
                                            <th class="text-center" style="width:250px">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
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

<div class="modal fade bd-example-modal-lg" id="detail_modal" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Detail Data <span id="modal_title"></span></h3>
            </div>
            <div class="modal-body">
                {{-- <h4>Informasi Umum</h4> --}}
                <div class="row">
                    <div class="col-4 detail_kepesertaan_foto_show">
                        <div class="me-7 mb-4">
                            <div class="symbol symbol-100px symbol-lg-160px symbol-fixed position-relative">
                                <img src="{{asset('public/profile_default.png')}}" alt="image"
                                    id="detail_kepesertaan_foto">
                                <div
                                    class="position-absolute translate-middle bottom-0 start-100 mb-6 bg-success rounded-circle border border-4 border-body h-20px w-20px">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-8">
                        <div class="row">
                            <div class="col-12">
                                <table>
                                    <tr>
                                        <td style="font-weight:bold">Nama Peserta</td>
                                        <td>: <span id="detail_kepesertaan_nama"></span></td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight:bold">Jenis Kelamin</td>
                                        <td>: <span id="detail_kepesertaan_jenis_kelamin"></span></td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight:bold">NIP</td>
                                        <td>: <span id="detail_kepesertaan_nip"></span></td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight:bold">Tempat, Tanggal Lahir</td>
                                        <td>: <span id="detail_kepesertaan_tempat_lahir"></span>,<span
                                                id="detail_kepesertaan_tanggal_lahir"></span></td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight:bold">Status Kepesertaan</td>
                                        <td>: <span id="detail_kepesertaan_status_kepesertaan"></span></td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight:bold">Jenis Pensiunan</td>
                                        <td>: <span id="detail_kepesertaan_jenis_pensiun"></span></td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight:bold">Status Jabatan</td>
                                        <td>: <span id="detail_kepesertaan_status_jabatan"></span></td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight:bold">Terhitung Mulai Tanggal</td>
                                        <td>: <span id="detail_kepesertaan_terhitung_mulai_tanggal"></span></td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight:bold">Telephone 1</td>
                                        <td>: <span id="detail_kepesertaan_telphone_1"></span></td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight:bold">Telephone 2</td>
                                        <td>: <span id="detail_kepesertaan_telphone_2"></span></td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight:bold">Email 1</td>
                                        <td>: <span id="detail_kepesertaan_email_1"></span></td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight:bold">Email 2</td>
                                        <td>: <span id="detail_kepesertaan_email_2"></span></td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight:bold">Alamat</td>
                                        <td>: <span id="detail_kepesertaan_alamat"></span></td>
                                    </tr>
        
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-12" style="margin-top:40px">
                        <h4>Kepesertaan Dokumen</h4>
                        <table class="table table-striped">
                            <tr>
                                <th class="text-center">Nama Dokumen</th>
                                <th class="text-center">Preview Dokumen</th>
                            </tr>
                            <tbody id="detail_dokumen_list">
                                <tr>
                                    <td class="text-center" colspan="2">No Data Available</td>
                                </tr>
                            </tbody>
                            
                        </table>
    
                    </div>
                </div>
                <div id="detail_kepesertaan_link_url"></div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade bd-example-modal-lg" id="detail_modal_dokumen" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Preview Dokumen <span id="modal_title"></span></h3>
            </div>
            <div class="modal-body">
                <div class="preview_file"></div>
                <div class="revisi_button"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('javascript')
@include('layout.Kepesertaan.js')
@endpush
