<div class="modal fade bd-example-modal-lg" id="detail_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Detail Data <span id="modal_title"></span></h3>
          </div>
          <div class="modal-body">
            <div class="row">
                <div class="col-12">
                    <table>
                        <tr>
                            <td style="font-weight:bold">Jenis Investasi</td>
                            <td>: <span id="detail_investasi_jenis"></span></td>
                        </tr>
                        <tr>
                            <td style="font-weight:bold">Nilai Perolehan</td>
                            <td>: <span id="detail_investasi_nilai_perolehan"></span></td>
                        </tr>
                        <tr>
                            <td style="font-weight:bold">Tanggal Nilai Perolehan</td>
                            <td>: <span id="detail_investasi_tanggal_nilai_perolehan"></span></td>
                        </tr>
                        <tr>
                            <td style="font-weight:bold">Hasil Pemantauan</td>
                            <td>: <span id="detail_investasi_hasil_pemantauan"></span></td>
                        </tr>
                    </table>
                </div>
                <div class="col-12" style="margin-top:40px">
                    <h4>Bukti Dokumen</h4>
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
          </div>
      </div>
    </div>
</div>

<div class="modal fade bd-example-modal-lg" id="detail_modal_subdata" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Detail Subdata<span id="modal_title"></span></h3>
          </div>
          <div class="modal-body">
            <div class="row">
                <div class="col-12">
                    <table>
                        <tr>
                            <td style="font-weight:bold">Jenis Investasi</td>
                            <td>: <span id="detail_investasi_subdata_nama"></span></td>
                        </tr>
                        <tr>
                            <td style="font-weight:bold">Nilai Perolehan</td>
                            <td>: <span id="detail_investasi_subdata_nilai_perolehan"></span></td>
                        </tr>
                        <tr>
                            <td style="font-weight:bold">Tanggal Nilai Perolehan</td>
                            <td>: <span id="detail_investasi_subdata_tanggal_nilai_perolehan"></span></td>
                        </tr>
                        <tr>
                            <td style="font-weight:bold">Hasil Pemantauan</td>
                            <td>: <span id="detail_investasi_subdata_hasil_pemantauan"></span></td>
                        </tr>
                    </table>
                </div>
            </div>
          </div>
      </div>
    </div>
</div>

<div class="modal fade bd-example-modal-lg" id="subdata_update_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Investasi Subdata Update <span id="modal_title"></span></h3>
          </div>
          <div class="modal-body">
            <div class="row">
                <form action="javascript:onSaveSubdata()" id="formDataSubdata" method="POST" autocomplete="off" enctype="multipart/form-data">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="investasiModal-tab" data-bs-toggle="tab"
                                data-bs-target="#tabInvestasiModal" type="button" role="tab" aria-controls="investasiModal"
                                aria-selected="true"><span style="font-weight: bold">Investasi</span></button>
                        </li>
                        <li class="nav-item" role="presentation" id="profile_li">
                            <button class="nav-link" id="nilaiWajarModal-tab" data-bs-toggle="tab" data-bs-target="#tabNilaiWajarModal"
                                type="button" role="tab" aria-controls="nilaiWajar" aria-selected="false"><span
                                    style="font-weight: bold">Nilai Wajar</span></button>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active pt-3" id="tabInvestasiModal" role="tabpanel" aria-labelledby="investasiModal-tab">
                            <input type="hidden" name="investasi_subdata_id" placeholder="" value="" />
                            <input type="hidden" name="investasi_subdata_investasi_id" placeholder="" value="" />
                            <div class="form-floating mb-7">
                                <input type="text" class="form-control form-control-solid" name="investasi_subdata_nama" value=""
                                    required />
                                <label for="floatingInput"><span class="required">Subdata Investasi Nama</span></label>
                            </div>
                            <div class="row">
                                <div class="form-floating mb-7 col-md-4">
                                    <input type="number" class="form-control form-control-solid"
                                        name="investasi_subdata_nilai_perolehan" value="" required />
                                    <label for="floatingInput"><span class="required">Nilai Perolehan</span></label>
                                </div>
                                <div class="form-floating mb-7 col-md-8">
                                    <input type="date" class="form-control form-control-solid"
                                        name="investasi_subdata_tanggal_nilai_perolehan" value="" required />
                                    <label for="floatingInput"><span class="required">Tanggal Nilai Perolehan</span></label>
                                </div>
                            </div>
        
                            <div class="form-floating mb-7">
                                <input type="text" class="form-control form-control-solid" name="investasi_subdata_hasil_pemantauan"
                                    value="" required />
                                <label for="floatingInput"><span class="required">Hasil Pemantauan</span></label>
                            </div>
                        </div>
                        <div class="tab-pane fade pt-3" id="tabNilaiWajarModal" role="tabpanel" aria-labelledby="nilaiWajarModal-tab">
                            <div class="row">
                                <div class="form-floating mb-7">
                                    <input type="number" class="form-control form-control-solid"
                                        name="investasi_subdata_tahun" id="investasi_subdata_tahun" placeholder="" value="" required oninput="onRegenerateMonthSub()"/>
                                    <label for="floatingInput"><span class="required">Masukkan Tahun</span></label>
                                </div>
                                <div class="col-6">
                                    <div class="form-floating mb-7">
                                        <input type="number" class="form-control form-control-solid investasi_subdata_bulan_list"
                                            name="investasi_subdata_bulan_list[]" placeholder=""/>
                                        <label for="floatingInput"><span>Januari</span></label>
                                    </div>
                                    <div class="form-floating mb-7">
                                        <input type="number" class="form-control form-control-solid investasi_subdata_bulan_list"
                                            name="investasi_subdata_bulan_list[]" placeholder=""/>
                                        <label for="floatingInput"><span>Februari</span></label>
                                    </div>
                                    <div class="form-floating mb-7">
                                        <input type="number" class="form-control form-control-solid investasi_subdata_bulan_list"
                                            name="investasi_subdata_bulan_list[]" placeholder=""/>
                                        <label for="floatingInput"><span>Maret</span></label>
                                    </div>
                                    <div class="form-floating mb-7">
                                        <input type="number" class="form-control form-control-solid investasi_subdata_bulan_list"
                                            name="investasi_subdata_bulan_list[]" placeholder=""/>
                                        <label for="floatingInput"><span>April</span></label>
                                    </div>
                                    <div class="form-floating mb-7">
                                        <input type="number" class="form-control form-control-solid investasi_subdata_bulan_list"
                                            name="investasi_subdata_bulan_list[]" placeholder=""/>
                                        <label for="floatingInput"><span>Mei</span></label>
                                    </div>
                                    <div class="form-floating mb-7">
                                        <input type="number" class="form-control form-control-solid investasi_subdata_bulan_list"
                                            name="investasi_subdata_bulan_list[]" placeholder=""/>
                                        <label for="floatingInput"><span>Juni</span></label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-floating mb-7">
                                        <input type="number" class="form-control form-control-solid investasi_subdata_bulan_list"
                                            name="investasi_subdata_bulan_list[]" placeholder=""/>
                                        <label for="floatingInput"><span>Juli</span></label>
                                    </div>
                                    <div class="form-floating mb-7">
                                        <input type="number" class="form-control form-control-solid investasi_subdata_bulan_list"
                                            name="investasi_subdata_bulan_list[]" placeholder=""/>
                                        <label for="floatingInput"><span>Agustus</span></label>
                                    </div>
                                    <div class="form-floating mb-7">
                                        <input type="number" class="form-control form-control-solid investasi_subdata_bulan_list"
                                            name="investasi_subdata_bulan_list[]" placeholder=""/>
                                        <label for="floatingInput"><span>September</span></label>
                                    </div>
                                    <div class="form-floating mb-7">
                                        <input type="number" class="form-control form-control-solid investasi_subdata_bulan_list"
                                            name="investasi_subdata_bulan_list[]" placeholder=""/>
                                        <label for="floatingInput"><span>Oktober</span></label>
                                    </div>
                                    <div class="form-floating mb-7">
                                        <input type="number" class="form-control form-control-solid investasi_subdata_bulan_list"
                                            name="investasi_subdata_bulan_list[]" placeholder=""/>
                                        <label for="floatingInput"><span>November</span></label>
                                    </div>
                                    <div class="form-floating mb-7">
                                        <input type="number" class="form-control form-control-solid investasi_subdata_bulan_list"
                                            name="investasi_subdata_bulan_list[]" placeholder=""/>
                                        <label for="floatingInput"><span>Desember</span></label>
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
          </div>
      </div>
    </div>
</div>