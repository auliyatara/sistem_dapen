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
                            <span class="card-label fw-bold fs-3 mb-1">Akuntasi Dan Keuangan</span>
                        </h3>
                        @if($edit)
                            <div class="card-toolbar">
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
                            </div>
                        @endif
                       
                    </div>
                    <div class="card-body pt-3 py-3">
                        <div class="table-responsive" style="width:100%;overflow-x: auto;">
                            <h3 class="text-center">PROGRAM PENSIUN MANFAAT PASTI <br>
                                RINGKASAN EKSEKUTIF
                                </h3>
                            <table id="table_keuangan" class="table table-striped" style="width:100%;border-collapse: collapse;">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="vertical-align: middle;min-width:400px;" rowspan="2"
                                            colspan="2">Deskripsi</th>
                                        <th class="text-center p-2" colspan="12">
                                            <div class="form-floating" style="width: 20%; margin:0 auto"> 
                                                <input type="number" class="form-control form-control-solid"
                                                    id="keuangan_tahun_table" placeholder="" value="" oninput="onInitTable()"/>
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

                                th,
                                td {
                                    border: 1px solid #ddd;
                                    padding: 10px;
                                    text-align: left;
                                }

                            </style>
                        </div>
                    </div>
                </div>

                @include('layout.Keuangan.form')
            </div>
        </div>
        <!--end::Content container-->
    </div>
    <!--end::Content-->
    <div class="modal fade bd-example-modal-lg" id="modalform" tabindex="-1" role="dialog"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Tambahkan Data Bulanan</h3>
                </div>
                <div class="modal-body">
                    <form action="javascript:onSaveBulan()" id="formBulan" method="POST" autocomplete="off">
                        <div class="card-body py-3 mt-3">
                            @csrf
                            <input type="hidden" name="keuangan_detail_id" id="keuangan_detail_id" placeholder=""
                                value="" />
                            <div class="row">
                                <div class="form-floating mb-7">
                                    <input type="number" class="form-control form-control-solid"
                                        name="keuangan_tahun" id="keuangan_tahun" placeholder="" value="" required oninput="onRegenerateMonth()"/>
                                    <label for="floatingInput"><span class="required">Masukkan Tahun</span></label>
                                </div>
                                <div class="col-6">
                                    <div class="form-floating mb-7">
                                        <input type="number" class="form-control form-control-solid keuangan_bulan_list"
                                            name="keuangan_bulan_list[]" placeholder=""/>
                                        <label for="floatingInput"><span>Januari</span></label>
                                    </div>
                                    <div class="form-floating mb-7">
                                        <input type="number" class="form-control form-control-solid keuangan_bulan_list"
                                            name="keuangan_bulan_list[]" placeholder=""/>
                                        <label for="floatingInput"><span>Februari</span></label>
                                    </div>
                                    <div class="form-floating mb-7">
                                        <input type="number" class="form-control form-control-solid keuangan_bulan_list"
                                            name="keuangan_bulan_list[]" placeholder=""/>
                                        <label for="floatingInput"><span>Maret</span></label>
                                    </div>
                                    <div class="form-floating mb-7">
                                        <input type="number" class="form-control form-control-solid keuangan_bulan_list"
                                            name="keuangan_bulan_list[]" placeholder=""/>
                                        <label for="floatingInput"><span>April</span></label>
                                    </div>
                                    <div class="form-floating mb-7">
                                        <input type="number" class="form-control form-control-solid keuangan_bulan_list"
                                            name="keuangan_bulan_list[]" placeholder=""/>
                                        <label for="floatingInput"><span>Mei</span></label>
                                    </div>
                                    <div class="form-floating mb-7">
                                        <input type="number" class="form-control form-control-solid keuangan_bulan_list"
                                            name="keuangan_bulan_list[]" placeholder=""/>
                                        <label for="floatingInput"><span>Juni</span></label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-floating mb-7">
                                        <input type="number" class="form-control form-control-solid keuangan_bulan_list"
                                            name="keuangan_bulan_list[]" placeholder=""/>
                                        <label for="floatingInput"><span>Juli</span></label>
                                    </div>
                                    <div class="form-floating mb-7">
                                        <input type="number" class="form-control form-control-solid keuangan_bulan_list"
                                            name="keuangan_bulan_list[]" placeholder=""/>
                                        <label for="floatingInput"><span>Agustus</span></label>
                                    </div>
                                    <div class="form-floating mb-7">
                                        <input type="number" class="form-control form-control-solid keuangan_bulan_list"
                                            name="keuangan_bulan_list[]" placeholder=""/>
                                        <label for="floatingInput"><span>September</span></label>
                                    </div>
                                    <div class="form-floating mb-7">
                                        <input type="number" class="form-control form-control-solid keuangan_bulan_list"
                                            name="keuangan_bulan_list[]" placeholder=""/>
                                        <label for="floatingInput"><span>Oktober</span></label>
                                    </div>
                                    <div class="form-floating mb-7">
                                        <input type="number" class="form-control form-control-solid keuangan_bulan_list"
                                            name="keuangan_bulan_list[]" placeholder=""/>
                                        <label for="floatingInput"><span>November</span></label>
                                    </div>
                                    <div class="form-floating mb-7">
                                        <input type="number" class="form-control form-control-solid keuangan_bulan_list"
                                            name="keuangan_bulan_list[]" placeholder=""/>
                                        <label for="floatingInput"><span>Desember</span></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-lg btn-primary mt-6" style="width: 100%">Simpan Data
                                <span class="svg-icon svg-icon-3 ms-1 me-0">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <rect opacity="0.5" x="18" y="13" width="13" height="2" rx="1"
                                            transform="rotate(-180 18 13)" fill="currentColor"></rect>
                                        <path
                                            d="M15.4343 12.5657L11.25 16.75C10.8358 17.1642 10.8358 17.8358 11.25 18.25C11.6642 18.6642 12.3358 18.6642 12.75 18.25L18.2929 12.7071C18.6834 12.3166 18.6834 11.6834 18.2929 11.2929L12.75 5.75C12.3358 5.33579 11.6642 5.33579 11.25 5.75C10.8358 6.16421 10.8358 6.83579 11.25 7.25L15.4343 11.4343C15.7467 11.7467 15.7467 12.2533 15.4343 12.5657Z"
                                            fill="currentColor"></path>
                                    </svg>
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection

@push('javascript')
@include('layout.Keuangan.js')
@endpush
