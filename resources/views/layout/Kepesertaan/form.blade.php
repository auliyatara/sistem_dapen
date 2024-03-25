<div class="card shadow-sm form_data" style="display:none">
    <div class="card-header">
        <h3 class="card-title">Tambah Kepesertaan</h3>
        <div class="card-toolbar">
            <a href="javascript:onBack()" class="btn btn-sm btn-danger">Kembali</a>
        </div>
    </div>
    <div class="card-body py-3 mt-3">
        <form action="javascript:onSave()" id="formData" method="POST" autocomplete="off">
            @csrf
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="informasi-tab" data-bs-toggle="tab" data-bs-target="#informasi"
                        type="button" role="tab" aria-controls="informasi" aria-selected="true"><span
                            style="font-weight: bold">Informasi Kepesertaan</span></button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="iuran-tab" data-bs-toggle="tab" data-bs-target="#iuran" type="button"
                        role="tab" aria-controls="iuran" aria-selected="false"><span style="font-weight: bold">Iuran
                            Kepesertaan</span></button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="dokumen-tab" data-bs-toggle="tab" data-bs-target="#dokumen"
                        type="button" role="tab" aria-controls="dokumen" aria-selected="false"><span
                            style="font-weight: bold">Dokumen</span></button>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="informasi" role="tabpanel" aria-labelledby="informasi-tab">
                    <input type="hidden" name="kepesertaan_id" placeholder="" value="" />
                    <div class="card shadow-sm mt-3 data_personal">
                        <div class="card-body mt-3 row">
                            <h3 class="mb-7">Data Personal</h3>
                            <hr class="hr hr-blurry" />
                            <div class="col-3 kepesertaan_foto_show" style="display:none">
                                <div class="me-7 mb-4">
                                    <div class="symbol symbol-100px symbol-lg-160px symbol-fixed position-relative">
                                        <img src="{{asset('public/profile_default.png')}}" alt="image"
                                            id="kepesertaan_foto">
                                        <div
                                            class="position-absolute translate-middle bottom-0 start-100 mb-6 bg-success rounded-circle border border-4 border-body h-20px w-20px">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mb-7">
                                <label for="file">Foto Profil</label>
                                <input type="file" class="form-control" name="file" placeholder="" value=""
                                    accept="image/png, image/gif, image/jpeg" />
                            </div>
                            <div class="col-12">
                                <div class="form-floating mb-7">
                                    <input type="text" class="form-control form-control-solid" name="kepesertaan_nama"
                                        placeholder="" value="" required />
                                    <label for="floatingInput"><span class="required">Nama
                                            Peserta</span></label>
                                </div>
                            </div>
                            <div class="col-12 mb-7">
                                <label class="d-flex align-items-center fs-5 fw-normal mb-2">
                                    <span class="required">Jenis Kelamin</span>
                                </label>
                                <select class="form-control" name="kepesertaan_jenis_kelamin"
                                    id="kepesertaan_jenis_kelamin" required>
                                    <option value="" selected disabled>Pilih Jenis Kelamin</option>
                                    <option value="Laki-Laki">Laki-Laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                            </div>
                            <div class="col-6 mb-4">
                                <div class="form-floating mb-7">
                                    <input type="text" class="form-control form-control-solid"
                                        name="kepesertaan_tempat_lahir" placeholder="" value="" required />
                                    <label for="floatingInput"><span class="required">Tempat Lahir</span></label>
                                </div>
                            </div>
                            <div class="col-6 mb-4">
                                <div class="form-floating mb-7">
                                    <input type="date" class="form-control form-control-solid"
                                        name="kepesertaan_tanggal_lahir" id="kepesertaan_tanggal_lahir" placeholder="" 
                                        value="" required onchange="onMasaKerjaPensiun();setTanggalPensiun();" />
                                    <label for="floatingInput"><span class="required">Tanggal
                                            Lahir</span></label>
                                </div>
                            </div>
                            <div class="col-6 mb-4">
                                <div class="form-floating mb-7">
                                    <input type="number" class="form-control form-control-solid" name="kepesertaan_nik"
                                        placeholder="NIK" value="" required />
                                    <label for="floatingInput"><span class="required">NIK</span></label>
                                </div>
                            </div>
                            <div class="col-6 mb-4">
                                <div class="form-floating mb-7">
                                    <input type="number" class="form-control form-control-solid" name="kepesertaan_npwp"
                                        placeholder="NPWP" value=""  />
                                    <label for="floatingInput"><span class="">NPWP</span></label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating mb-7">
                                    <input type="number" class="form-control form-control-solid" name="kepesertaan_nip"
                                        placeholder="" value="" required />
                                    <label for="floatingInput"><span class="required">NIP UMM</span></label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-floating mb-7">
                                    <input type="text" class="form-control form-control-solid"
                                        name="kepesertaan_telphone_1" placeholder="" value="" required />
                                    <label for="floatingInput"><span class="required">Telpon 1</span></label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-floating mb-7">
                                    <input type="text" class="form-control form-control-solid"
                                        name="kepesertaan_telphone_2" placeholder="" value="" />
                                    <label for="floatingInput"><span class="">Telpon 2</span></label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-floating mb-7">
                                    <input type="email" class="form-control form-control-solid"
                                        name="kepesertaan_email_1" placeholder="" value="" required />
                                    <label for="floatingInput"><span class="required">Email 1</span></label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-floating mb-7">
                                    <input type="email" class="form-control form-control-solid"
                                        name="kepesertaan_email_2" placeholder="" value=""  />
                                    <label for="floatingInput"><span class="">Email 2</span></label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating mb-7">
                                    <input type="text" class="form-control form-control-solid" name="kepesertaan_alamat"
                                        placeholder="" value="" required />
                                    <label for="floatingInput"><span class="required">Alamat</span></label>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="card shadow-sm mt-3 data_karyawan">
                        <div class="card-body mt-3 row">
                            <h3 class="mb-7">Data Karyawan</h3>
                            <hr class="hr hr-blurry" />
                            <div class="col-6 mb-4">
                                <label class="d-flex align-items-center fs-5 fw-normal mb-2">
                                    <span class="required">PHDP (Gaji Pokok)</span></label>
                                    <input type="number" class="form-control form-control-solid"
                                        name="kepesertaan_gaji_pokok" id="kepesertaan_gaji_pokok" placeholder=""
                                        value="" required />
                            </div>
                            <div class="col-6 mb-4">
                                <label class="d-flex align-items-center fs-5 fw-normal mb-2">
                                    <span class="required"> Estimasi Terhitung Mulai Tanggal Peserta</span>
                                </label>
                                <input type="date" class="form-control form-control-solid"
                                    name="kepesertaan_terhitung_mulai_tanggal" id="kepesertaan_terhitung_mulai_tanggal"
                                    placeholder="" value="" required onchange="onMasaKerjaSekarang()" />
                            </div>
                            <div class="col-6 mb-4">
                                <label class="d-flex align-items-center fs-5 fw-normal mb-2">
                                    <span class="required">Terhitung Mulai Tanggal Pensiun</span>
                                </label>
                                <input type="date" class="form-control form-control-solid" id="kepesertaan_terhitung_mulai_tanggal_pensiun"
                                    name="kepesertaan_terhitung_mulai_tanggal_pensiun" placeholder="" value=""
                                    required disabled/>
                            </div>
                            <div class="col-6 mb-4">
                                <label class="d-flex align-items-center fs-5 fw-normal mb-2">
                                    <span>Masa Kerja Sekarang <code>(Maksimal 32 tahun 0 bulan 0 hari)</code></span>
                                    <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip"
                                        title="Estimasi TMT Peserta - Tanggal Sekarang"></i>
                                </label>
                                <input type="text" class="form-control form-control-solid" id="auto_masa_kerja_sekarang"
                                    placeholder="" value="0 Tahun" disabled />
                            </div>
                            <div class="col-8 mb-7">
                                <label class="d-flex align-items-center fs-5 fw-normal mb-2">
                                    <span class="required">Status Kepesertaan</span>
                                </label>
                                <select class="form-control" name="kepesertaan_status_kepesertaan"
                                    id="kepesertaan_status_kepesertaan" required onchange="showJenisPensiunan();onCheckReadOnly()">
                                    <option value="" selected disabled>Pilih Status Kepesertaan</option>
                                    <option value="Peserta">Peserta</option>
                                    <option value="Pensiunan">Pensiunan</option>
                                    <option value="Pensiun Ditunda">Pensiun Ditunda</option>
                                    <option value="Pensiun Sekaligus">Pensiun Sekaligus</option>
                                    <option value="Pensiunan Yang Sudah Tidak Berhak Menerima MP">Pensiunan Yang Sudah
                                        Tidak Berhak Menerima MP</option>
                                </select>
                            </div>
                            <div class="col-4 mb-7">
                                <label class="d-flex align-items-center fs-5 fw-normal mb-2">
                                    <span>Masa Kerja Pensiun </span>
                                    <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip"
                                        title="Estimasi TMT Peserta - TMT Pensiun"></i>
                                </label>
                                <input type="text" class="form-control form-control-solid" id="auto_masa_kerja_pensiun"
                                    placeholder="" value="0 Tahun" disabled />
                            </div>
                            <div class="col-12 mb-7" id="display_jenis_pensiunan" style="display:none">
                                <label class="d-flex align-items-center fs-5 fw-normal mb-2">
                                    <span class="required">Jenis Pensiun</span>
                                </label>
                                <select class="form-control" name="kepesertaan_jenis_pensiun"
                                    id="kepesertaan_jenis_pensiun">
                                    <option value="" selected disabled>Pilih Jenis Pensiun</option>
                                    <option value="Pensiun Normal">Pensiun Normal</option>
                                    <option value="Pensiun Janda/Duda">Pensiun Janda/Duda</option>
                                    <option value="Pensiun Anak">Pensiun Anak</option>
                                </select>
                            </div>
                            <div class="col-8 mb-7">
                                <label class="d-flex align-items-center fs-5 fw-normal mb-2">
                                    <span class="required">Status Jabatan</span>
                                </label>
                                <select class="form-control" name="kepesertaan_status_jabatan"
                                    id="kepesertaan_status_jabatan" required
                                    onchange="onMasaPensiun();onMasaKerjaPensiun();setTanggalPensiun();">
                                    <option value="" selected disabled>Pilih Status Jabatan</option>
                                    <option value="Guru Besar">Guru Besar</option>
                                    <option value="Dosen">Dosen</option>
                                    <option value="Karyawan">Karyawan</option>
                                </select>
                            </div>
                            <div class="col-4 mb-7">
                                <label class="d-flex align-items-center fs-5 fw-normal mb-2">
                                    <span>Masa Pensiun</span>
                                </label>
                                <input type="text" class="form-control form-control-solid" id="auto_masa_pensiun"
                                    placeholder="" value="0 Tahun" disabled />
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-sm mt-3 data_estimasi_mp">
                        <div class="card-body mt-3">

                            <h3 class="mb-7">Data Estimasi MP (Manfaat Pensiun) Normal</h3>
                            <hr class="hr hr-blurry" />
                            <div class="row">
                                <div class="col-6 mb-4">
                                    <label class="d-flex align-items-center fs-5 fw-normal mb-2">
                                        <span>Sekaligus</span>
                                    </label>
                                    <input type="text" class="form-control form-control-solid" id="auto_sekaligus"
                                        placeholder="" value="Rp 0" disabled />
                                </div>
                                <div class="col-6 mb-4">
                                    <label class="d-flex align-items-center fs-5 fw-normal mb-2">
                                        <span>Pajak</span>
                                    </label>
                                    <input type="text" class="form-control form-control-solid" id="auto_pajak"
                                        placeholder="" value="Rp 0" disabled />
                                </div>
                                <div class="col-6 mb-4">
                                    <label class="d-flex align-items-center fs-5 fw-normal mb-2">
                                        <span>20 %</span>
                                    </label>
                                    <input type="text" class="form-control form-control-solid" id="auto_20persen"
                                        placeholder="" value="Rp 0" disabled />
                                </div>
                                <div class="col-6 mb-4">
                                    <label class="d-flex align-items-center fs-5 fw-normal mb-2">
                                        <span>Bulanan</span>
                                    </label>
                                    <input type="text" class="form-control form-control-solid" id="auto_bulanan"
                                        placeholder="" value="Rp 0" disabled />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="iuran" role="tabpanel" aria-labelledby="iuran-tab">
                    <div class="card shadow-sm mt-3 data_tahun_iuran">
                        <div class="card-body mt-3">

                            <h3 class="mb-7">Data Tahun Iuran</h3>
                            <hr class="hr hr-blurry" />
                            <div class="row">
                                <div class="form-floating mb-7">
                                    <input type="number" class="form-control form-control-solid"
                                        name="kepesertaan_iuran_tahun" id="kepesertaan_iuran_tahun" placeholder=""
                                        value="" oninput="onRegenerateIuran()" />
                                    <label for="floatingInput"><span>Masukkan Tahun</span></label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card shadow-sm mt-3 data_iuran_pengembangan">
                        <div class="card-body mt-3">

                            <h3 class="mb-7">Data Iuran Dan Pengembangan Tahun Sebelumnya</h3>
                            <hr class="hr hr-blurry" />
                            <div class="row">
                                <div class="col-6 mb-4">
                                    <label class="d-flex align-items-center fs-5 fw-normal mb-2">
                                        <span>Total keseluruhan Iuran Total sampai Tahun</span><span class="year_choose">...</span>
                                    </label>
                                    <input type="text" class="form-control form-control-solid" id="year_before_iuran_total"
                                        placeholder="" value="Rp 0" disabled />
                                </div>
                                <div class="col-6 mb-4">
                                    <label class="d-flex align-items-center fs-5 fw-normal mb-2">
                                        <span>Total keseluruhan Iuran + Total Pengembangan sampai Tahun</span><span class="year_choose">...</span>
                                    </label>
                                    <input type="text" class="form-control form-control-solid"
                                        id="year_before_iuran_pengembangan" placeholder="" value="Rp 0" disabled />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 p-2">
                            <div class="card shadow-sm mt-3 data_iuran_total col-12">
                                <div class="card-header">
                                    <h3 class="card-title">Data Iuran</h3>
                                    <div class="card-toolbar">
                                        <a href="javascript:setIuranGajiPokok()" class="btn btn-sm btn-success">Set Sesuai Gaji Pokok</a>
                                    </div>
                                </div>
                                <div class="card-body mt-3">
                                    <div class="row">
                                        <div class="col-12">
                                            <table class="table table-striped">
                                                <tr>
                                                    <th class="col-2 fw-bold text-center bg-primary">
                                                        <h4>Bulan</h4>
                                                    </th>
                                                    <th class="col-4 fw-bold text-center bg-primary">
                                                        <h4>PHDP Bulanan</h4>
                                                    </th>
                                                    <th class="col-3 fw-bold text-center bg-primary">
                                                        <h4>Data Iuran Total</h4>
                                                    </th>
                                                    <th class="col-3 fw-bold text-center bg-primary">
                                                        <h4>Data Iuran + Total Pengembangan</h4>
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <td class="p-2 fw-bold">Januari</td>
                                                    <td><input type="number" class="form-control iuran_total_list"
                                                            id="phdp_total_1" name="iuran_total_list[]"
                                                            oninput="onChangeIuranTotal('total_1')" placeholder="" /></td>
                                                    <td><input type="text" class="form-control iuran_total_list iuran_total_perhitungan"
                                                            id="iuran_total_1" placeholder="" disabled /></td>
                                                    <td><input type="text" class="form-control iuran_pengembangan_list iuran_pengembangan_perhitungan"
                                                            id="iuran_pengembangan_1" placeholder="" disabled /></td>
                                                </tr>
                                                <tr>
                                                    <td class="p-2 fw-bold">Februari</td>
                                                    <td><input type="number" class="form-control iuran_total_list"
                                                            id="phdp_total_2" name="iuran_total_list[]"
                                                            oninput="onChangeIuranTotal('total_2')" placeholder="" /></td>
                                                    <td><input type="text" class="form-control iuran_total_list iuran_total_perhitungan"
                                                            id="iuran_total_2" placeholder="" disabled /></td>
                                                            <td><input type="text" class="form-control iuran_pengembangan_list iuran_pengembangan_perhitungan"
                                                                id="iuran_pengembangan_2" placeholder="" disabled /></td>
                                                </tr>
                                                <tr>
                                                    <td class="p-2 fw-bold">Maret</td>
                                                    <td><input type="number" class="form-control iuran_total_list"
                                                            id="phdp_total_3" name="iuran_total_list[]"
                                                            oninput="onChangeIuranTotal('total_3')" placeholder="" /></td>
                                                    <td><input type="text" class="form-control iuran_total_list iuran_total_perhitungan"
                                                            id="iuran_total_3" placeholder="" disabled /></td>
                                                            <td><input type="text" class="form-control iuran_pengembangan_list iuran_pengembangan_perhitungan"
                                                                id="iuran_pengembangan_3" placeholder="" disabled /></td>
                                                </tr>
                                                <tr>
                                                    <td class="p-2 fw-bold">April</td>
                                                    <td><input type="number" class="form-control iuran_total_list"
                                                            id="phdp_total_4" name="iuran_total_list[]"
                                                            oninput="onChangeIuranTotal('total_4')" placeholder="" /></td>
                                                    <td><input type="text" class="form-control iuran_total_list iuran_total_perhitungan"
                                                            id="iuran_total_4" placeholder="" disabled /></td>
                                                            <td><input type="text" class="form-control iuran_pengembangan_list iuran_pengembangan_perhitungan"
                                                                id="iuran_pengembangan_4" placeholder="" disabled /></td>
                                                </tr>
                                                <tr>
                                                    <td class="p-2 fw-bold">Mei</td>
                                                    <td><input type="number" class="form-control iuran_total_list"
                                                            id="phdp_total_5" name="iuran_total_list[]"
                                                            oninput="onChangeIuranTotal('total_5')" placeholder="" /></td>
                                                    <td><input type="text" class="form-control iuran_total_list iuran_total_perhitungan"
                                                            id="iuran_total_5" placeholder="" disabled /></td>
                                                            <td><input type="text" class="form-control iuran_pengembangan_list iuran_pengembangan_perhitungan"
                                                                id="iuran_pengembangan_5" placeholder="" disabled /></td>
                                                </tr>
                                                <tr>
                                                    <td class="p-2 fw-bold">Juni</td>
                                                    <td><input type="number" class="form-control iuran_total_list"
                                                            id="phdp_total_6" name="iuran_total_list[]"
                                                            oninput="onChangeIuranTotal('total_6')" placeholder="" /></td>
                                                    <td><input type="text" class="form-control iuran_total_list iuran_total_perhitungan"
                                                            id="iuran_total_6" placeholder="" disabled /></td>
                                                            <td><input type="text" class="form-control iuran_pengembangan_list iuran_pengembangan_perhitungan"
                                                                id="iuran_pengembangan_6" placeholder="" disabled /></td>
                                                </tr>
                                                <tr>
                                                    <td class="p-2 fw-bold">Juli</td>
                                                    <td><input type="number" class="form-control iuran_total_list"
                                                            id="phdp_total_7" name="iuran_total_list[]"
                                                            oninput="onChangeIuranTotal('total_7')" placeholder="" /></td>
                                                    <td><input type="text" class="form-control iuran_total_list iuran_total_perhitungan"
                                                            id="iuran_total_7" placeholder="" disabled /></td>
                                                            <td><input type="text" class="form-control iuran_pengembangan_list iuran_pengembangan_perhitungan"
                                                                id="iuran_pengembangan_7" placeholder="" disabled /></td>
                                                </tr>
                                                <tr>
                                                    <td class="p-2 fw-bold">Agustus</td>
                                                    <td><input type="number" class="form-control iuran_total_list"
                                                            id="phdp_total_8" name="iuran_total_list[]"
                                                            oninput="onChangeIuranTotal('total_8')" placeholder="" /></td>
                                                    <td><input type="text" class="form-control iuran_total_list iuran_total_perhitungan"
                                                            id="iuran_total_8" placeholder="" disabled /></td>
                                                            <td><input type="text" class="form-control iuran_pengembangan_list iuran_pengembangan_perhitungan"
                                                                id="iuran_pengembangan_8" placeholder="" disabled /></td>
                                                </tr>
                                                <tr>
                                                    <td class="p-2 fw-bold">September</td>
                                                    <td><input type="number" class="form-control iuran_total_list"
                                                            id="phdp_total_9" name="iuran_total_list[]"
                                                            oninput="onChangeIuranTotal('total_9')" placeholder="" /></td>
                                                    <td><input type="text" class="form-control iuran_total_list iuran_total_perhitungan"
                                                            id="iuran_total_9" placeholder="" disabled /></td>
                                                            <td><input type="text" class="form-control iuran_pengembangan_list iuran_pengembangan_perhitungan"
                                                                id="iuran_pengembangan_9" placeholder="" disabled /></td>
                                                </tr>
                                                <tr>
                                                    <td class="p-2 fw-bold">Oktober</td>
                                                    <td><input type="number" class="form-control iuran_total_list"
                                                            id="phdp_total_10" name="iuran_total_list[]"
                                                            oninput="onChangeIuranTotal('total_10')" placeholder="" /></td>
                                                    <td><input type="text" class="form-control iuran_total_list iuran_total_perhitungan"
                                                            id="iuran_total_10" placeholder="" disabled /></td>
                                                            <td><input type="text" class="form-control iuran_pengembangan_list iuran_pengembangan_perhitungan"
                                                                id="iuran_pengembangan_10" placeholder="" disabled /></td>
                                                </tr>
                                                <tr>
                                                    <td class="p-2 fw-bold">November</td>
                                                    <td><input type="number" class="form-control iuran_total_list"
                                                            id="phdp_total_11" name="iuran_total_list[]"
                                                            oninput="onChangeIuranTotal('total_11')" placeholder="" /></td>
                                                    <td><input type="text" class="form-control iuran_total_list iuran_total_perhitungan"
                                                            id="iuran_total_11" placeholder="" disabled /></td>
                                                            <td><input type="text" class="form-control iuran_pengembangan_list iuran_pengembangan_perhitungan"
                                                                id="iuran_pengembangan_11" placeholder="" disabled /></td>
                                                </tr>
                                                <tr>
                                                    <td class="p-2 fw-bold">Desember</td>
                                                    <td><input type="number" class="form-control iuran_total_list"
                                                            id="phdp_total_12" name="iuran_total_list[]"
                                                            oninput="onChangeIuranTotal('total_12')" placeholder="" /></td>
                                                    <td><input type="text" class="form-control iuran_total_list iuran_total_perhitungan"
                                                            id="iuran_total_12" placeholder="" disabled /></td>
                                                            <td><input type="text" class="form-control iuran_pengembangan_list iuran_pengembangan_perhitungan"
                                                                id="iuran_pengembangan_12" placeholder="" disabled /></td>
                                                </tr>
                                                <tr style="background-color: #b8e2f9">
                                                    <td class="p-2 fw-bold" colspan="2">
                                                        <h4>Jumlah Keseluruhan</h4>
                                                    </td>
                                                    <td><input type="text" class="form-control" id="jumlah_iuran_total"
                                                            placeholder="" disabled value="Rp 0" /></td>
                                                            <td><input type="text" class="form-control"
                                                                    id="jumlah_iuran_pengembangan" placeholder="" disabled
                                                                    value="Rp 0" /></td>
                                                            <td></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                            
                        <div class="col-6 p-2 d-none">
                            <div class="card shadow-sm mt-3 data_iuran_pengembangan col-12">
                                <div class="card-header">
                                    <h3 class="card-title">Data Iuran + Total Pengembangan</h3>
                                </div>
                                <div class="card-body mt-3">
                                    <div class="row">
                                        <div class="col-12">
                                            <table class="table table-striped">
                                                <tr>
                                                    <th class="col-3 fw-bold text-center bg-primary">
                                                        <h4>Bulan</h4>
                                                    </th>
                                                    <th class="col-5 fw-bold text-center bg-primary">
                                                        <h4>PHDP Bulanan</h4>
                                                    </th>
                                                    <th class="col-4 fw-bold text-center bg-primary">
                                                        <h4>Iuran Per Bulan</h4>
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <td class="p-2 fw-bold">Januari</td>
                                                    <td><input type="number" class="form-control iuran_pengembangan_list"
                                                            id="phdp_pengembangan_1" name="iuran_pengembangan_list[]"
                                                            oninput="onChangeIuranPengembangan('pengembangan_1')" placeholder="" />
                                                    </td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td class="p-2 fw-bold">Februari</td>
                                                    <td><input type="number" class="form-control iuran_pengembangan_list"
                                                            id="phdp_pengembangan_2" name="iuran_pengembangan_list[]"
                                                            oninput="onChangeIuranPengembangan('pengembangan_2')" placeholder="" />
                                                    </td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td class="p-2 fw-bold">Maret</td>
                                                    <td><input type="number" class="form-control iuran_pengembangan_list"
                                                            id="phdp_pengembangan_3" name="iuran_pengembangan_list[]"
                                                            oninput="onChangeIuranPengembangan('pengembangan_3')" placeholder="" />
                                                    </td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td class="p-2 fw-bold">April</td>
                                                    <td><input type="number" class="form-control iuran_pengembangan_list"
                                                            id="phdp_pengembangan_4" name="iuran_pengembangan_list[]"
                                                            oninput="onChangeIuranPengembangan('pengembangan_4')" placeholder="" />
                                                    </td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td class="p-2 fw-bold">Mei</td>
                                                    <td><input type="number" class="form-control iuran_pengembangan_list"
                                                            id="phdp_pengembangan_5" name="iuran_pengembangan_list[]"
                                                            oninput="onChangeIuranPengembangan('pengembangan_5')" placeholder="" />
                                                    </td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td class="p-2 fw-bold">Juni</td>
                                                    <td><input type="number" class="form-control iuran_pengembangan_list"
                                                            id="phdp_pengembangan_6" name="iuran_pengembangan_list[]"
                                                            oninput="onChangeIuranPengembangan('pengembangan_6')" placeholder="" />
                                                    </td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td class="p-2 fw-bold">Juli</td>
                                                    <td><input type="number" class="form-control iuran_pengembangan_list"
                                                            id="phdp_pengembangan_7" name="iuran_pengembangan_list[]"
                                                            oninput="onChangeIuranPengembangan('pengembangan_7')" placeholder="" />
                                                    </td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td class="p-2 fw-bold">Agustus</td>
                                                    <td><input type="number" class="form-control iuran_pengembangan_list"
                                                            id="phdp_pengembangan_8" name="iuran_pengembangan_list[]"
                                                            oninput="onChangeIuranPengembangan('pengembangan_8')" placeholder="" />
                                                    </td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td class="p-2 fw-bold">September</td>
                                                    <td><input type="number" class="form-control iuran_pengembangan_list"
                                                            id="phdp_pengembangan_9" name="iuran_pengembangan_list[]"
                                                            oninput="onChangeIuranPengembangan('pengembangan_9')" placeholder="" />
                                                    </td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td class="p-2 fw-bold">Oktober</td>
                                                    <td><input type="number" class="form-control iuran_pengembangan_list"
                                                            id="phdp_pengembangan_10" name="iuran_pengembangan_list[]"
                                                            oninput="onChangeIuranPengembangan('pengembangan_10')"
                                                            placeholder="" />
                                                    </td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td class="p-2 fw-bold">November</td>
                                                    <td><input type="number" class="form-control iuran_pengembangan_list"
                                                            id="phdp_pengembangan_11" name="iuran_pengembangan_list[]"
                                                            oninput="onChangeIuranPengembangan('pengembangan_11')"
                                                            placeholder="" />
                                                    </td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td class="p-2 fw-bold">Desember</td>
                                                    <td><input type="number" class="form-control iuran_pengembangan_list"
                                                            id="phdp_pengembangan_12" name="iuran_pengembangan_list[]"
                                                            oninput="onChangeIuranPengembangan('pengembangan_12')"
                                                            placeholder="" />
                                                    </td>
                                                    <td></td>
                                                </tr>
                                                <tr style="background-color: #b8e2f9">
                                                    <td class="p-2 fw-bold" colspan="2">
                                                        <h4>Jumlah Keseluruhan Iuran + Total Pengembangan</h4>
                                                    </td>
                                                    <td></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>

                    <div class="card shadow-sm mt-3 data_jumlah_keseluruhan d-none">
                        <div class="card-body mt-3">

                            <h3 class="mb-7">Total Keseluruhan Sampai Tahun <span class="year_choose">...</span></h3>
                            <hr class="hr hr-blurry" />
                            <div class="row">
                                <div class="col-12 mb-4">
                                    <input type="text" class="form-control form-control-solid" id="jumlah_keseluruhan"
                                        placeholder="" value="Rp 0" disabled />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="dokumen" role="tabpanel" aria-labelledby="dokumen-tab">
                    <div class="col-12">
                        <div class="card shadow-sm">
                            <div class="card-body py-3 mt-3 data_dokumen_kepesertaan">
                                <label class="d-flex align-items-center fs-5 fw-normal mb-2">
                                    <span>Dokumen Kepesertaan</span>
                                </label>
                                <div id="list_kepesertaan_dokumen"></div>
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

                <button type="submit" class="btn btn-lg btn-primary py-3 mt-3" style="width: 100%">Simpan Data
                    <span class="svg-icon svg-icon-3 ms-1 me-0">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
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
