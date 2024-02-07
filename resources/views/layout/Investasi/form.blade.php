<div class="card shadow-sm form_data" style="display:none">
    <div class="card-header">
        <h3 class="card-title">Data Investasi</h3>
        <div class="card-toolbar">
            <a href="javascript:onBack()" class="btn btn-sm btn-danger">Kembali</a>
        </div>
    </div>
    <form action="javascript:onSave()" id="formData" method="POST" autocomplete="off" enctype="multipart/form-data">
        <div class="card-body py-3 mt-3">
            @csrf
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="investasi-tab" data-bs-toggle="tab"
                        data-bs-target="#tabInvestasi" type="button" role="tab" aria-controls="investasi"
                        aria-selected="true"><span style="font-weight: bold">Investasi</span></button>
                </li>
                <li class="nav-item" role="presentation" id="profile_li">
                    <button class="nav-link" id="subdata-tab" data-bs-toggle="tab" data-bs-target="#tabSubdata"
                        type="button" role="tab" aria-controls="tabSubdata" aria-selected="false"><span
                            style="font-weight: bold">Subdata Investasi</span></button>
                </li>
                <li class="nav-item" role="presentation" id="profile_li">
                    <button class="nav-link" id="nilaiWajar-tab" data-bs-toggle="tab" data-bs-target="#tabNilaiWajar"
                        type="button" role="tab" aria-controls="nilaiWajar" aria-selected="false"><span
                            style="font-weight: bold">Nilai Wajar</span></button>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active pt-3" id="tabInvestasi" role="tabpanel" aria-labelledby="investasi-tab">
                    <input type="hidden" name="investasi_id" placeholder="" value="" />
                    <div class="form-floating mb-7">
                        <input type="text" class="form-control form-control-solid" name="investasi_jenis" value=""
                            required />
                        <label for="floatingInput"><span class="required">Jenis Investasi</span></label>
                    </div>
                    <div class="row">
                        <div class="form-floating mb-7 col-md-4">
                            <input type="number" class="form-control form-control-solid"
                                name="investasi_nilai_perolehan" value="" required />
                            <label for="floatingInput"><span class="required">Nilai Perolehan</span></label>
                        </div>
                        <div class="form-floating mb-7 col-md-8">
                            <input type="date" class="form-control form-control-solid"
                                name="investasi_tanggal_nilai_perolehan" value="" required />
                            <label for="floatingInput"><span class="required">Tanggal Nilai Perolehan</span></label>
                        </div>
                    </div>

                    <div class="form-floating mb-7">
                        <input type="text" class="form-control form-control-solid" name="investasi_hasil_pemantauan"
                            value="" required />
                        <label for="floatingInput"><span class="required">Hasil Pemantauan</span></label>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="card shadow-sm">
                                <div class="card-body py-3 mt-3">
                                    <label class="d-flex align-items-center fs-5 fw-semibold mb-2">
                                        <span>Bukti Dokumen</span>
                                    </label>
                                    <div id="list_investasi"></div>
                                    <button type="button" class="btn btn-sm btn-success" style="width: 100%"
                                        onclick="onAddList()">
                                        <span class="svg-icon svg-icon-2">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <rect opacity="0.5" x="11.364" y="20.364" width="16" height="2" rx="1"
                                                    transform="rotate(-90 11.364 20.364)" fill="currentColor" />
                                                <rect x="4.36396" y="11.364" width="16" height="2" rx="1"
                                                    fill="currentColor" />
                                            </svg>
                                        </span>Tambah Data
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade pt-3" id="tabSubdata" role="tabpanel" aria-labelledby="nilaiWajar-tab">
                    <label class="d-flex align-items-center fs-5 fw-semibold mb-2">
                        <span>List Subdata</span>
                    </label>
                    <div id="list_subdata"></div>
                    <button type="button" class="btn btn-sm btn-success" style="width: 100%"
                        onclick="onAddListSubdata()">
                        <span class="svg-icon svg-icon-2">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <rect opacity="0.5" x="11.364" y="20.364" width="16" height="2"
                                    rx="1" transform="rotate(-90 11.364 20.364)" fill="currentColor" />
                                <rect x="4.36396" y="11.364" width="16" height="2" rx="1"
                                    fill="currentColor" />
                            </svg>
                        </span>Tambah Data
                    </button>
                </div>
                <div class="tab-pane fade pt-3" id="tabNilaiWajar" role="tabpanel" aria-labelledby="nilaiWajar-tab">
                    <div class="row">
                        <div class="form-floating mb-7">
                            <input type="number" class="form-control form-control-solid"
                                name="investasi_tahun" id="investasi_tahun" placeholder="" value="" required oninput="onRegenerateMonth()"/>
                            <label for="floatingInput"><span class="required">Masukkan Tahun</span></label>
                        </div>
                        <div class="col-6">
                            <div class="form-floating mb-7">
                                <input type="number" class="form-control form-control-solid investasi_bulan_list"
                                    name="investasi_bulan_list[]" placeholder=""/>
                                <label for="floatingInput"><span>Januari</span></label>
                            </div>
                            <div class="form-floating mb-7">
                                <input type="number" class="form-control form-control-solid investasi_bulan_list"
                                    name="investasi_bulan_list[]" placeholder=""/>
                                <label for="floatingInput"><span>Februari</span></label>
                            </div>
                            <div class="form-floating mb-7">
                                <input type="number" class="form-control form-control-solid investasi_bulan_list"
                                    name="investasi_bulan_list[]" placeholder=""/>
                                <label for="floatingInput"><span>Maret</span></label>
                            </div>
                            <div class="form-floating mb-7">
                                <input type="number" class="form-control form-control-solid investasi_bulan_list"
                                    name="investasi_bulan_list[]" placeholder=""/>
                                <label for="floatingInput"><span>April</span></label>
                            </div>
                            <div class="form-floating mb-7">
                                <input type="number" class="form-control form-control-solid investasi_bulan_list"
                                    name="investasi_bulan_list[]" placeholder=""/>
                                <label for="floatingInput"><span>Mei</span></label>
                            </div>
                            <div class="form-floating mb-7">
                                <input type="number" class="form-control form-control-solid investasi_bulan_list"
                                    name="investasi_bulan_list[]" placeholder=""/>
                                <label for="floatingInput"><span>Juni</span></label>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-floating mb-7">
                                <input type="number" class="form-control form-control-solid investasi_bulan_list"
                                    name="investasi_bulan_list[]" placeholder=""/>
                                <label for="floatingInput"><span>Juli</span></label>
                            </div>
                            <div class="form-floating mb-7">
                                <input type="number" class="form-control form-control-solid investasi_bulan_list"
                                    name="investasi_bulan_list[]" placeholder=""/>
                                <label for="floatingInput"><span>Agustus</span></label>
                            </div>
                            <div class="form-floating mb-7">
                                <input type="number" class="form-control form-control-solid investasi_bulan_list"
                                    name="investasi_bulan_list[]" placeholder=""/>
                                <label for="floatingInput"><span>September</span></label>
                            </div>
                            <div class="form-floating mb-7">
                                <input type="number" class="form-control form-control-solid investasi_bulan_list"
                                    name="investasi_bulan_list[]" placeholder=""/>
                                <label for="floatingInput"><span>Oktober</span></label>
                            </div>
                            <div class="form-floating mb-7">
                                <input type="number" class="form-control form-control-solid investasi_bulan_list"
                                    name="investasi_bulan_list[]" placeholder=""/>
                                <label for="floatingInput"><span>November</span></label>
                            </div>
                            <div class="form-floating mb-7">
                                <input type="number" class="form-control form-control-solid investasi_bulan_list"
                                    name="investasi_bulan_list[]" placeholder=""/>
                                <label for="floatingInput"><span>Desember</span></label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-lg btn-primary mt-6" style="width: 100%">Simpan Data
                <span class="svg-icon svg-icon-3 ms-1 me-0">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect opacity="0.5" x="18" y="13" width="13" height="2" rx="1" transform="rotate(-180 18 13)"
                            fill="currentColor"></rect>
                        <path
                            d="M15.4343 12.5657L11.25 16.75C10.8358 17.1642 10.8358 17.8358 11.25 18.25C11.6642 18.6642 12.3358 18.6642 12.75 18.25L18.2929 12.7071C18.6834 12.3166 18.6834 11.6834 18.2929 11.2929L12.75 5.75C12.3358 5.33579 11.6642 5.33579 11.25 5.75C10.8358 6.16421 10.8358 6.83579 11.25 7.25L15.4343 11.4343C15.7467 11.7467 15.7467 12.2533 15.4343 12.5657Z"
                            fill="currentColor"></path>
                    </svg>
                </span>
            </button>
        </div>
    </form>
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
                <div class="revisi_button"></div>
            </div>
        </div>
    </div>
</div>
