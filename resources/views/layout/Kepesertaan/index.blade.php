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
                            <div class="card-toolbar gap-3">
                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#kepesertaan-import-modal">Import Data</button>
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
                                        <div class="card shadow-sm mt-1" style="background-color:#fc936d;">
                                            <div class="card-body pt-3 p-1">
                                                <h3 style="color:white;text-align:center">Peserta</h3>
                                                <h1 id="count_peserta"
                                                    style="color:white;font-size:50px;text-align:center">
                                                    0</h1>
                                                <div class="d-flex justify-content-around p-1">
                                                    <div class="card shadow-sm"
                                                        style="width:32%;background-color:#bd6f53;color:white;cursor:pointer"
                                                        onclick="onInitFilter(1,'Guru Besar')">
                                                        <div class="card-body pt-3 p-1">
                                                            <h5 style="color:white;text-align:center">Guru Besar</h5>
                                                            <h4 id="count_guru_besar"
                                                                style="color:white;text-align:center">0</h4>
                                                        </div>
                                                    </div>
                                                    <div class="card shadow-sm"
                                                        style="width:32%;background-color:#bd6f53;color:white;cursor:pointer"
                                                        onclick="onInitFilter(1,'Dosen')">
                                                        <div class="card-body pt-3 p-1">
                                                            <h5 style="color:white;text-align:center">Dosen</h5>
                                                            <h4 id="count_dosen" style="color:white;text-align:center">0
                                                            </h4>
                                                        </div>
                                                    </div>
                                                    <div class="card shadow-sm"
                                                        style="width:32%;background-color:#bd6f53;color:white;cursor:pointer"
                                                        onclick="onInitFilter(1,'Karyawan')">
                                                        <div class="card-body pt-3 p-1">
                                                            <h5 style="color:white;text-align:center">Karyawan</h5>
                                                            <h4 id="count_karyawan"
                                                                style="color:white;text-align:center">0</h4>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="card shadow-sm mt-1" style="background-color:#45b2d6;">
                                            <div class="card-body pt-2 p-1">
                                                <h3 style="color:white;text-align:center">Pensiun</h3>
                                                <h1 id="count_pensiunan"
                                                    style="color:white;font-size:55px;text-align:center">0</h1>
                                                <div class="d-flex justify-content-around p-1">
                                                    <div class="card shadow-sm"
                                                        style="width:24%;background-color:#188fc7;color:white;cursor:pointer"
                                                        onclick="onInitFilter(2,'Pensiun Normal')">
                                                        <div class="card-body pt-3 p-1">
                                                            <h5 style="color:white;text-align:center">Normal</h5>
                                                            <h4 id="count_pensiun_normal"
                                                                style="color:white;text-align:center">0
                                                            </h4>

                                                        </div>
                                                    </div>
                                                    <div class="card shadow-sm"
                                                        style="width:24%;background-color:#188fc7;color:white;cursor:pointer"
                                                        onclick="onInitFilter(2,'Pensiun Anak')">
                                                        <div class="card-body pt-3 p-1">
                                                            <h5 style="color:white;text-align:center">Anak</h5>
                                                            <h4 id="count_pensiun_anak"
                                                                style="color:white;text-align:center">0
                                                            </h4>
                                                        </div>
                                                    </div>
                                                    <div class="card shadow-sm"
                                                        style="width:24%;background-color:#188fc7;color:white;cursor:pointer"
                                                        onclick="onInitFilter(2,'Pensiun Janda Duda')">
                                                        <div class="card-body pt-3 p-1">
                                                            <h5 style="color:white;text-align:center">Janda/Duda</h5>
                                                            <h4 id="count_pensiun_janda_duda"
                                                                style="color:white;text-align:center">0</h4>
                                                        </div>
                                                    </div>
                                                    <div class="card shadow-sm"
                                                        style="width:24%;background-color:#188fc7;color:white;cursor:pointer"
                                                        onclick="onInitFilter(2,'Pensiun Dipercepat')">
                                                        <div class="card-body pt-3 p-1">
                                                            <h5 style="color:white;text-align:center">Dipercepat</h5>
                                                            <h4 id="count_pensiun_dipercepat"
                                                                style="color:white;text-align:center">0</h4>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card shadow-sm mt-1" style="background-color:#a967eb;">
                                            <div class="card-body pt-13 py-13">
                                                <h3 style="color:white;text-align:center">Pensiunan Ditunda</h3>
                                                <h2 id="count_pensiun_ditunda"
                                                    style="color:white;font-size:50px;text-align:center">0</h2>
                                            </div>
                                        </div>

                                        <div class="col-md-2">

                                        </div>
                                    </div>



                                    <div class="col-md-4">
                                        <div class="card shadow-sm mt-1" style="background-color:#f25e7a;">
                                            <div class="card-body pt-13 py-13">
                                                <h3 style="color:white;text-align:center">Pensiun Sekaligus</h3>
                                                <h2 id="count_pensiunan_sekaligus"
                                                    style="color:white;font-size:50px;text-align:center">0</h2>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card shadow-sm mt-1" style="background-color:#d0718c;">
                                            <div class="card-body pt-10 py-10">
                                                <h3 style="color:white;text-align:center">Pensiunan Yang Sudah Tidak
                                                    Berhak Menerima MP</h3>
                                                <h2 id="count_pensiunan_yang_tdk_terima_mp"
                                                    style="color:white;font-size:50px;text-align:center">0</h2>

                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="row g-4 mt-3 mb-4">
                                    <div class="col-12 col-md-6">
                                        <label for="tipe_filter" class="form-label fw-bold">Filter Tampilan:</label>
                                        <select class="form-control" id="tipe_filter" onchange="onInitFilter()"
                                            style="width:100%">
                                            <option value="">Pilih Salah Satu</option>
                                            <option value="0">Semua</option>
                                            <option value="1">Peserta</option>
                                            <option value="2">Pensiunan</option>
                                            <option value="3">Pensiun Ditunda</option>
                                            <option value="4">Pensiunan Sekaligus</option>
                                            <option value="5">Pensiunan Yang Sudah Tidak Berhak Menerima MP</option>
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label for="kepesertaan-search" class="form-label fw-bold">Cari Peserta / Pensiunan</label>
                                        <input id="kepesertaan-search" type="search" class="form-control" placeholder="Ketik nama atau NIP, contoh: Abdul Haris" autocomplete="off">
                                    </div>
                                    <div class="col-12 mt-2"><small class="text-muted">Pencarian mengikuti Filter Tampilan yang dipilih. Pilih Semua untuk mencari seluruh peserta dan pensiunan.</small></div>
                                </div>
                            </div>


                            <div class="table-responsive">
                                <div class="d-flex align-items-center gap-3 mb-3"><span id="kepesertaan-load-status" role="status" class="text-muted">Memuat data kepesertaan...</span><button type="button" id="kepesertaan-retry" class="btn btn-sm btn-light-primary" hidden onclick="onInitTable()">Coba lagi</button></div>
                                <table id="table_primary" class="table table-striped" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th class="text-center">No</th>
                                            <th class="text-center">Nama</th>
                                            <th class="text-center">NIP</th>
                                            <th class="text-center" style="width:150px">TTL</th>
                                            <th class="text-center">TMT Peserta</th>
                                            <th class="text-center">Status Peserta</th>
                                            <th class="text-center">Status Pekerjaan</th>
                                            <th class="text-center">Kelengkapan</th>
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

<div class="modal fade bd-example-modal-xl" id="detail_modal" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Detail Data <span id="modal_title"></span></h3>
            </div>
            <div class="modal-body">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="informasi_detail-tab" data-bs-toggle="tab" data-bs-target="#informasi_detail"
                            type="button" role="tab" aria-controls="informasi_detail" aria-selected="true"><span
                                style="font-weight: bold">Informasi Kepesertaan</span></button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="iuran_detail-tab" data-bs-toggle="tab" data-bs-target="#iuran_detail" type="button" role="tab"
                            aria-controls="iuran_detail" aria-selected="false"><span style="font-weight: bold">Iuran
                                Kepesertaan</span></button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="dokumen_detail-tab" data-bs-toggle="tab" data-bs-target="#dokumen_detail" type="button"
                            role="tab" aria-controls="dokumen_detail" aria-selected="false"><span
                                style="font-weight: bold">Dokumen</span></button>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active pt-3" id="informasi_detail" role="tabpanel" aria-labelledby="informasi_detail-tab">
                        <div class="row">
                            <div class="col-4 detail_kepesertaan_foto_show">
                                <div class="me-7 mb-4">
                                    <div class="symbol symbol-100px symbol-lg-160px symbol-fixed position-relative">
                                        <img src="{{asset('public/assets/images/profile_default.png')}}" alt="image"
                                            id="detail_kepesertaan_foto">
                                        <div
                                            class="position-absolute translate-middle bottom-0 start-100 mb-6 bg-success rounded-circle border border-4 border-body h-20px w-20px">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="row">
                                    <h3 class="mt-7">Data Personal</h3>
                                    <hr class="hr hr-blurry" />
                                    <div class="col-6">
                                        <table class=" table table-striped">
                                            <tr>
                                                <td style="font-weight:bold">Nama Peserta</td>
                                                <td>: <span id="detail_kepesertaan_nama"></span></td>
                                            </tr>
                                            <tr>
                                                <td style="font-weight:bold">Jenis Kelamin</td>
                                                <td>: <span id="detail_kepesertaan_jenis_kelamin"></span></td>
                                            </tr>
                                            <tr>
                                                <td style="font-weight:bold">Tempat, Tanggal Lahir</td>
                                                <td>: <span id="detail_kepesertaan_tempat_lahir"></span>,<span
                                                        id="detail_kepesertaan_tanggal_lahir"></span></td>
                                            </tr>
                                            
                                            <tr>
                                                <td style="font-weight:bold">NIK</td>
                                                <td>: <span id="detail_kepesertaan_nik"></span></td>
                                            </tr>
                                            <tr>
                                                <td style="font-weight:bold">NIP</td>
                                                <td>: <span id="detail_kepesertaan_nip"></span></td>
                                            </tr>
                                            <tr>
                                                <td style="font-weight:bold">NPWP</td>
                                                <td>: <span id="detail_kepesertaan_npwp"></span></td>
                                            </tr>
                                            </table>
                                    </div>
                                    <div class="col-6">
                                        <table class=" table table-striped">
                                            <tr>
                                                <td style="font-weight:bold">Alamat</td>
                                                <td>: <span id="detail_kepesertaan_alamat"></span></td>
                                            </tr>
                                            <tr>
                                                <td style="font-weight:bold">Telpon 1</td>
                                                <td>: <span id="detail_kepesertaan_telphone_1"></span></td>
                                            </tr>
                                            <tr>
                                                <td style="font-weight:bold">Telpon 2</td>
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
                                        </table>
                                    </div>

                                    <h3 class="mt-7">Data karyawan</h3>
                                    <hr class="hr hr-blurry" />
                                    <div class="col-6">
                                        <table class=" table table-striped">
                                            <tr>
                                                <td style="font-weight:bold">PHDP (Gaji Pokok)</td>
                                                <td>: <span id="detail_kepesertaan_gaji_pokok"></span></td>
                                            </tr>
                                            <tr>
                                                <td style="font-weight:bold">TMT Peserta</td>
                                                <td>: <span id="detail_kepesertaan_terhitung_mulai_tanggal"></span></td>
                                            </tr>
                                            <tr>
                                                <td style="font-weight:bold">Status Jabatan</td>
                                                <td>: <span id="detail_kepesertaan_status_jabatan"></span></td>
                                            </tr>
                                            <tr>
                                                <td style="font-weight:bold">Status Kepesertaan</td>
                                                <td>: <span id="detail_kepesertaan_status_kepesertaan"></span></td>
                                            </tr>
                                            <tr>
                                                <td style="font-weight:bold">Jenis Pensiun</td>
                                                <td>: <span id="detail_kepesertaan_jenis_pensiun"></span></td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-6">
                                        <table class=" table table-striped">
                                            <tr>
                                                <td style="font-weight:bold">Tanggal Pensiun</td>
                                                <td>: <span id="detail_kepesertaan_terhitung_mulai_tanggal_pensiun"></span><span id="detail_kepesertaan_tanggal_pensiun"></span></td>
                                            </tr>
                                            <tr>
                                                <td style="font-weight:bold">Masa Kerja Sekarang</td>
                                                <td>: <span id="detail_masa_kerja_sekarang"></span></td>
                                            </tr>
                                            <tr>
                                                <td style="font-weight:bold">Masa Pensiun</td>
                                                <td>: <span id="detail_masa_pensiun"></span></td>
                                            </tr>
                                            <tr>
                                                <td style="font-weight:bold">Masa Kerja Pensiun</td>
                                                <td>: <span id="detail_masa_kerja_pensiun"></span></td>
                                            </tr>
                                        </table>
                                    </div>

                                    <h3 class="mt-7">Data Estimasi MP (Manfaat Pensiun) Normal</h3>
                                    <hr class="hr hr-blurry" />
                                    <div class="col-6">
                                        <table class=" table table-striped">
                                            <tr>
                                                <td style="font-weight:bold">Sekaligus</td>
                                                <td>: <span id="detail_sekaligus"></span></td>
                                            </tr>
                                            <tr>
                                                <td style="font-weight:bold">20%</td>
                                                <td>: <span id="detail_20persen"></span></td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-6">
                                        <table class=" table table-striped">
                                            <tr>
                                                <td style="font-weight:bold">Pajak</td>
                                                <td>: <span id="detail_pajak"></span></td>
                                            </tr>
                                            <tr>
                                                <td style="font-weight:bold">Bulanan</td>
                                                <td>: <span id="detail_bulanan"></span></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    <div class="tab-pane fade pt-3" id="iuran_detail" role="tabpanel" aria-labelledby="iuran_detail-tab">
                        <div class="col-12 mb-7">
                            <label class="align-items-center fs-5 fw-bold mb-2">
                                <span>Tahun Iuran</span>
                            </label>
                            <select class="form-control" onchange="onChangeTahunIuranDetail()" id="detail_iuran_tahun"></select>
                            <input type="hidden" id="detail_kepesertaan_id">
                        </div>

                        <h3 class="mt-7">Data Iuran Dan Pengembangan Tahun Sebelumnya</h3>
                        <hr class="hr hr-blurry" />
                        <div class="row">
                            <div class="col-6">
                                <table class=" table table-striped">
                                    <tr>
                                        <td style="font-weight:bold">Total keseluruhan Iuran Total sampai Tahun <span class="year_choose">...</span></td>
                                        <td>: <span id="detail_iuran_year_before">Rp 0</span></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-6">
                                <table class=" table table-striped">
                                    <tr>
                                        <td style="font-weight:bold">Total keseluruhan Iuran + Total Pengembangan sampai Tahun <span class="year_choose">...</span></td>
                                        <td>: <span id="detail_iuran_pengembangan_year_before">Rp 0</span></td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <h3 class="mt-7">Total Data Iuran Dan Pengembangan Tahun Ini</h3>
                        <hr class="hr hr-blurry" />
                        <div class="row">
                            <div class="col-6">
                                <table class=" table table-striped">
                                    <tr>
                                        <td style="font-weight:bold">Total keseluruhan Iuran Peserta Sampai Dengan Tahun <span class="year_choose_now">...</span></td>
                                        <td>: <span id="detail_iuran_year_now">Rp 0</span></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-6">
                                <table class=" table table-striped">
                                    <tr>
                                        <td style="font-weight:bold">Total keseluruhan Iuran + Pengembangan Peserta Sampai Dengan Tahun <span class="year_choose_now">...</span></td>
                                        <td>: <span id="detail_iuran_pengembangan_year_now">Rp 0</span></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                       
                        <div class="row">
                            <div class="col-6">
                                <h3 class="mt-7">Data Iuran Total</h3>
                                <hr class="hr hr-blurry" />
                                <table class="table table-striped">
                                    <tr>
                                        <td class="fw-bold p-1 col-5">Januari</td>
                                        <td class="fw-normal col-7 detail_iuran_total_list">Rp 0</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold p-1 col-5">Februari</td>
                                        <td class="fw-normal col-7 detail_iuran_total_list">Rp 0</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold p-1 col-5">Maret</td>
                                        <td class="fw-normal col-7 detail_iuran_total_list">Rp 0</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold p-1 col-5">April</td>
                                        <td class="fw-normal col-7 detail_iuran_total_list">Rp 0</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold p-1 col-5">Mei</td>
                                        <td class="fw-normal col-7 detail_iuran_total_list">Rp 0</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold p-1 col-5">Juni</td>
                                        <td class="fw-normal col-7 detail_iuran_total_list">Rp 0</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold p-1 col-5">Juli</td>
                                        <td class="fw-normal col-7 detail_iuran_total_list">Rp 0</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold p-1 col-5">Agustus</td>
                                        <td class="fw-normal col-7 detail_iuran_total_list">Rp 0</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold p-1 col-5">September</td>
                                        <td class="fw-normal col-7 detail_iuran_total_list">Rp 0</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold p-1 col-5">Oktober</td>
                                        <td class="fw-normal col-7 detail_iuran_total_list">Rp 0</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold p-1 col-5">November</td>
                                        <td class="fw-normal col-7 detail_iuran_total_list">Rp 0</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold p-1 col-5">Desember</td>
                                        <td class="fw-normal col-7 detail_iuran_total_list">Rp 0</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold p-1 col-5 bg-primary">Jumlah Iuran Total</td>
                                        <td class="fw-normal col-7 detail_jumlah_iuran_total_list bg-primary">Rp 0</td>
                                    </tr>
                                </table>
                            </div>

                            <div class="col-6">
                                <h3 class="mt-7">Data Iuran + Total Pengembangan</h3>
                                <hr class="hr hr-blurry" />
                                <table class="table table-striped">
                                    <tr>
                                        <td class="fw-bold p-1 col-7">Januari</td>
                                        <td class="fw-normal col-5 detail_iuran_pengembangan_list">Rp 0</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold p-1 col-7">Februari</td>
                                        <td class="fw-normal col-5 detail_iuran_pengembangan_list">Rp 0</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold p-1 col-7">Maret</td>
                                        <td class="fw-normal col-5 detail_iuran_pengembangan_list">Rp 0</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold p-1 col-7">April</td>
                                        <td class="fw-normal col-5 detail_iuran_pengembangan_list">Rp 0</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold p-1 col-7">Mei</td>
                                        <td class="fw-normal col-5 detail_iuran_pengembangan_list">Rp 0</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold p-1 col-7">Juni</td>
                                        <td class="fw-normal col-5 detail_iuran_pengembangan_list">Rp 0</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold p-1 col-7">Juli</td>
                                        <td class="fw-normal col-5 detail_iuran_pengembangan_list">Rp 0</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold p-1 col-7">Agustus</td>
                                        <td class="fw-normal col-5 detail_iuran_pengembangan_list">Rp 0</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold p-1 col-7">September</td>
                                        <td class="fw-normal col-5 detail_iuran_pengembangan_list">Rp 0</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold p-1 col-7">Oktober</td>
                                        <td class="fw-normal col-5 detail_iuran_pengembangan_list">Rp 0</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold p-1 col-7">November</td>
                                        <td class="fw-normal col-5 detail_iuran_pengembangan_list">Rp 0</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold p-1 col-7">Desember</td>
                                        <td class="fw-normal col-5 detail_iuran_pengembangan_list">Rp 0</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold p-1 col-7 bg-primary">Jumlah Iuran + Total Pengembangan</td>
                                        <td class="fw-normal col-5 detail_jumlah_iuran_pengembangan_list bg-primary">Rp 0</td>
                                    </tr>
                                </table>
                            </div>

                            <div class="d-none">
                                <h3 class="mb-7 mt-7">Total Keseluruhan Sampai Tahun <span class="year_choose">...</span></h3>
                                <hr class="hr hr-blurry" />
                                <div class="row">
                                    <div class="col-12 mb-4">
                                        <table class=" table table-striped">
                                            <tr>
                                                <td style="font-weight:bold">Total Keseluruhan</td>
                                                <td>: <span id="detail_iuran_keseluruhan">Rp 0</span></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>  
                        </div>
                    </div>
                    <div class="tab-pane fade pt-3" id="dokumen_detail" role="tabpanel" aria-labelledby="dokumen_detail-tab">
                        <div class="col-12" style="margin-top:40px">
                            <table class="table table-striped">
                                <tr>
                                    <th class="text-center" style="font-weight: bold">Nama Dokumen</th>
                                    <th class="text-center" style="font-weight: bold">Preview Dokumen</th>
                                </tr>
                                <tbody id="detail_dokumen_list">
                                    <tr>
                                        <td class="text-center" colspan="2">No Data Available</td>
                                    </tr>
                                </tbody>
    
                            </table>
    
                        </div>
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
@if($edit) @include('layout.Kepesertaan.import') @endif
@include('layout.Kepesertaan.js')
@endpush
