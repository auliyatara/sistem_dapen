@if($edit)
<div class="form_data" style="display:none">
    <div class="card-header border-0 pt-5">
        <h2 class="card-title align-items-start flex-column">
            <span class="card-label fw-bold fs-3 mb-1" id="menu-title">Tambah Pegawai</span>
        </h2>
        <div class="card-toolbar">
            <a href="javascript:onBack()" class="btn btn-sm btn-danger">Kembali</a>
        </div>
    </div>
    
    <div class="card-body py-3 mt-3">
        <form action="javascript:onSave()" id="formData" method="POST" autocomplete="off" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id" placeholder="" value="" />
            <div class="row">
                <div class="col-md-4">
                    <div class="fv-row mb-5">
                        <label class="d-flex align-items-center fs-5 fw-semibold mb-2">
                            <span class="required">Nama</span>
                        </label>
                        <input type="text" class="form-control form-control-solid" name="nama" required/>
                    </div>

                    <div class="fv-row mb-5">
                        <label class="d-flex align-items-center fs-5 fw-semibold mb-2">
                            <span>NIP (opsional)</span>
                        </label>
                        <input type="text" class="form-control form-control-solid" name="nip"/>
                    </div>

                    <div class="fv-row mb-5">
                        <label class="d-flex align-items-center fs-5 fw-semibold mb-2">
                            <span class="required">NIK</span>
                        </label>
                        <input type="text" class="form-control form-control-solid" name="nik" required/>
                    </div>

                    <div class="fv-row mb-5">
                        <label class="d-flex align-items-center fs-5 fw-semibold mb-2">
                            <span class="required">Tempat Lahir</span>
                        </label>
                        <input type="text" class="form-control form-control-solid" name="tempat_lahir" required/>
                    </div>

                    <div class="fv-row mb-5">
                        <label class="d-flex align-items-center fs-5 fw-semibold mb-2">
                            <span class="required">Tanggal Lahir</span>
                        </label>
                        <input type="date" class="form-control form-control-solid" name="tanggal_lahir" required/>
                    </div>

                    <div class="fv-row mb-5">
                        <label class="d-flex align-items-center fs-5 fw-semibold mb-2">
                            <span class="required">Usia</span>
                        </label>
                        <input type="text" class="form-control form-control-solid" name="usia" readonly/>
                    </div>

                    <div class="fv-row mb-5">
                        <label class="d-flex align-items-center fs-5 fw-semibold mb-2">
                            <span class="required">Jenis Kelamin</span>
                        </label>
                        <select class="form-control" name="jenis_kelamin" required>
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                        <!-- <label class="d-flex align-items-center fs-5 fw-semibold mb-2">
                            <span class="required">Jenis Kelamin</span>
                        </label>
                        <input type="text" class="form-control form-control-solid" name="jenis_kelamin" required/> -->
                    </div>

                    <div class="fv-row mb-5">
                        <label class="d-flex align-items-center fs-5 fw-semibold mb-2">
                            <span class="required">Agama</span>
                        </label>
                        <select class="form-control" name="agama" required>
                            <option value="">Pilih Jenis Agama</option>
                            <option value="Islam">Islam</option>
                            <option value="Katolik">Katolik</option>
                            <option value="Protestan">Protestan</option>
                            <option value="Hindu">Hindu</option>
                            <option value="Budha">Budha</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="fv-row mb-5">
                        <label class="d-flex align-items-center fs-5 fw-semibold mb-2">
                            <span class="required">Alamat (KTP)</span>
                        </label>
                        <input type="text" class="form-control form-control-solid" name="alamat_ktp" required/>
                    </div>

                    <div class="fv-row mb-5">
                        <label class="d-flex align-items-center fs-5 fw-semibold mb-2">
                            <span class="required">Alamat Saat Ini</span>
                        </label>
                        <input type="text" class="form-control form-control-solid" name="alamat_saat_ini" required/>
                        <label class="d-flex align-items-center gap-2 mt-2"><input type="checkbox" id="pegawai-alamat-sama" class="form-check-input"> Sama dengan alamat KTP</label>
                    </div>

                    <div class="fv-row mb-5">
                        <label class="d-flex align-items-center fs-5 fw-semibold mb-2">
                            <span class="required">Nomor HP 1</span>
                        </label>
                        <input type="text" class="form-control form-control-solid" name="nomor_hp_1" required/>
                    </div>

                    <div class="fv-row mb-5">
                        <label class="d-flex align-items-center fs-5 fw-semibold mb-2">
                            <span>Nomor HP 2 (opsional)</span>
                        </label>
                        <input type="text" class="form-control form-control-solid" name="nomor_hp_2"/>
                    </div>

                    <div class="fv-row mb-5">
                        <label class="d-flex align-items-center fs-5 fw-semibold mb-2">
                            <span class="required">Email 1</span>
                        </label>
                        <input type="email" class="form-control form-control-solid" name="email_1" required/>
                    </div>

                    <div class="fv-row mb-5">
                        <label class="d-flex align-items-center fs-5 fw-semibold mb-2">
                            <span class="required">Email 2</span>
                        </label>
                        <input type="email" class="form-control form-control-solid" name="email_2"/>
                    </div>

                    <div class="fv-row mb-5">
                        <label class="d-flex align-items-center fs-5 fw-semibold mb-2">
                            <span class="required">Tanggal Pengangkatan</span>
                        </label>
                        <input type="date" class="form-control form-control-solid" name="tanggal_pengangkatan" required/>
                    </div>

                    <div class="fv-row mb-5">
                        <label class="d-flex align-items-center fs-5 fw-semibold mb-2">
                            <span class="required">Perkiraan Pensiun</span>
                        </label>
                        <input type="date" class="form-control form-control-solid" name="perkiraan_pensiun" required/>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="fv-row mb-5">
                        <div class="col-12">
                            <label class="d-flex align-items-center fs-5 fw-semibold mb-2">
                                <span class="required">Jabatan</span>
                            </label>
                            <select class="form-control" name="jabatan" required>
                                <option value="">Pilih Jabatan</option>
                                @foreach(config('kepegawaian.jabatan') as $jabatan)
                                <option value="{{ $jabatan }}">{{ $jabatan }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>


                    <div class="fv-row mb-5">
                        <label class="d-flex align-items-center fs-5 fw-semibold mb-2">
                            <span class="required">Masa Kerja</span>
                        </label>
                        <input type="date" class="form-control form-control-solid" name="masa_kerja" required/>
                    </div>

                    <div class="fv-row mb-5">
                        <label class="d-flex align-items-center fs-5 fw-semibold mb-2">
                            <span class="required">Sisa Masa Kerja</span>
                        </label>
                        <input type="text" class="form-control form-control-solid" name="sisa_masa_kerja" readonly/>
                    </div>

                    <div class="fv-row mb-5">
                        <label class="d-flex align-items-center fs-5 fw-semibold mb-2">
                            <span class="required">Lampiran</span>
                        </label>
                        <input type="file" class="form-control form-control-solid" id="lampiran" name="lampiran[]" multiple accept=".pdf,.jpg,.jpeg,.png" required/>
                        <div class="form-text">Saat edit, lampiran baru ditambahkan ke lampiran yang sudah tersimpan.</div>
                        <small class="text-muted">PDF/JPG/JPEG/PNG hingga 40 MB dapat diunggah. File di atas 2 MB akan dikompres otomatis.</small>
                        <div id="lampiran_view" class="mt-2" style="display: none;"></div>
                    </div>

                    <div class="fv-row mb-5">
                        <label for="pegawai-link-first" class="fs-5 fw-semibold mb-2">Link (opsional)</label>
                        <div id="pegawai-links" class="d-flex flex-column gap-2">
                            <div class="d-flex gap-2 pegawai-link-row">
                                <input type="url" id="pegawai-link-first" name="links[]" class="form-control form-control-solid" placeholder="https://..." maxlength="2048" aria-label="Link 1">
                                <button type="button" class="btn btn-light-primary btn-icon" id="add-pegawai-link" aria-label="Tambah link" title="Tambah link">+</button>
                            </div>
                        </div>
                        <small class="text-muted">Klik + untuk menambahkan link lain.</small>
                    </div>
                </div>
            </div>
            <!-- <div class="row">
                <div class="col-md-4">
                    <div class="fv-row mb-5">
                        <label class="d-flex align-items-center fs-5 fw-semibold mb-2">
                            <span class="required">Nama</span>
                        </label>
                        <input type="text" class="form-control form-control-solid" name="user_nama"
                            placeholder="Nama" value="" required/>
                    </div>

                    <div class="fv-row mb-5">
                        <label class="d-flex align-items-center fs-5 fw-semibold mb-2">
                            <span class="required">NIP</span>
                        </label>
                        <input type="text" class="form-control form-control-solid" name="user_nip"
                            placeholder="Nomor" value="" required/>
                    </div>

                    <div class="fv-row mb-5">
                        <label class="d-flex align-items-center fs-5 fw-semibold mb-2">
                            <span class="required">NIK</span>
                        </label>
                        <input type="text" class="form-control form-control-solid" name="user_nik"
                            placeholder="Nomor" value="" required/>
                    </div>

                    <div class="fv-row mb-5">
                        <label class="d-flex align-items-center fs-5 fw-semibold mb-2">
                            <span class="required">Tempat Lahir</span>
                        </label>
                        <input type="text" class="form-control form-control-solid" name="tempat_lahir"
                            placeholder="Nama" value="" required/>
                    </div>

                    <div class="fv-row mb-5">
                        <label class="d-flex align-items-center fs-5 fw-semibold mb-2">
                            <span class="required">Tanggal Lahir</span>
                        </label>
                        <input type="date" class="form-control form-control-solid" name="tanggal_lahir"
                            placeholder="Nama" value="" required/>
                    </div>

                    <div class="fv-row mb-5">
                        <label class="d-flex align-items-center fs-5 fw-semibold mb-2">
                            <span class="required">Usia</span>
                        </label>
                        <input type="text" class="form-control form-control-solid" name="user_usia"
                            placeholder="Nomor" value="" required/>
                    </div>

                    <div class="fv-row mb-5">
                        <label class="d-flex align-items-center fs-5 fw-semibold mb-2">
                            <span class="required">Jenis Kelamin</span>
                        </label>
                        <input type="text" class="form-control form-control-solid" name="jenis_kelamin"
                            placeholder="Nomor" value="" required/>
                    </div>

                    <div class="fv-row mb-5">
                        <label class="d-flex align-items-center fs-5 fw-semibold mb-2">
                            <span class="required">Agama</span>
                        </label>
                        <input type="text" class="form-control form-control-solid" name="user_agama"
                            placeholder="Nomor" value="" required/>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="fv-row mb-5">
                        <label class="d-flex align-items-center fs-5 fw-semibold mb-2">
                            <span class="required">Alamat (KTP)</span>
                        </label>
                        <input type="text" class="form-control form-control-solid" name="alamat_ktp"
                            placeholder="Nomor" value="" required/>
                    </div>

                    <div class="fv-row mb-5">
                        <label class="d-flex align-items-center fs-5 fw-semibold mb-2">
                            <span class="required">Alamat Saat Ini</span>
                        </label>
                        <input type="text" class="form-control form-control-solid" name="alamat_saat_ini"
                            placeholder="Nomor" value="" required/>
                    </div>

                    <div class="fv-row mb-5">
                        <label class="d-flex align-items-center fs-5 fw-semibold mb-2">
                            <span class="required">Nomor HP 1</span>
                        </label>
                        <input type="text" class="form-control form-control-solid" name="nomor_telepon1"
                            placeholder="Nomor" value="" required/>
                    </div>

                    <div class="fv-row mb-5">
                        <label class="d-flex align-items-center fs-5 fw-semibold mb-2">
                            <span class="required">Nomor HP 2</span>
                        </label>
                        <input type="text" class="form-control form-control-solid" name="nomor_telepon2"
                            placeholder="Nomor" value="" required/>
                    </div>

                    <div class="fv-row mb-5">
                        <label class="d-flex align-items-center fs-5 fw-semibold mb-2">
                            <span class="required">Email 1</span>
                        </label>
                        <input type="email" class="form-control form-control-solid" name="user_email1"
                            placeholder="Email" value="" required/>
                    </div>

                    <div class="fv-row mb-5">
                        <label class="d-flex align-items-center fs-5 fw-semibold mb-2">
                            <span class="required">Email 2</span>
                        </label>
                        <input type="email" class="form-control form-control-solid" name="user_email2"
                            placeholder="Email" value="" required/>
                    </div>

                    <div class="fv-row mb-5">
                        <label class="d-flex align-items-center fs-5 fw-semibold mb-2">
                            <span class="required">Tanggal Pengangkatan</span>
                        </label>
                        <input type="date" class="form-control form-control-solid" name="tanggal_pengangkatan"
                            placeholder="Nama" value="" required/>
                    </div>

                    <div class="fv-row mb-5">
                        <label class="d-flex align-items-center fs-5 fw-semibold mb-2">
                            <span class="required">Perkiraan Pensiun</span>
                        </label>
                        <input type="date" class="form-control form-control-solid" name="perkiraan_pensiun"
                            placeholder="Nama" value="" required/>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="fv-row mb-5">
                        <label class="d-flex align-items-center fs-5 fw-semibold mb-2">
                            <span class="required">Masa Kerja</span>
                        </label>
                        <input type="date" class="form-control form-control-solid" name="masa_kerja"
                            placeholder="Nama" value="" required/>
                    </div>

                    <div class="fv-row mb-5">
                        <label class="d-flex align-items-center fs-5 fw-semibold mb-2">
                            <span class="required">Sisa Masa Kerja</span>
                        </label>
                        <input type="date" class="form-control form-control-solid" name="sisa_masa_kerja"
                            placeholder="Nama" value="" required/>
                    </div>

                    <div class="fv-row mb-5">
                        <label class="d-flex align-items-center fs-5 fw-semibold mb-2">
                            <span class="required">Lampiran</span>
                        </label>
                        <input type="file" class="form-control form-control-solid" name="nomor_telepon"
                            placeholder="Nomor" value="" required/>
                    </div>

                    <div class="fv-row mb-5 row">
                        <div class="col-12">
                            <label class="d-flex align-items-center fs-5 fw-semibold mb-2">
                                <span class="required">Jabatan</span>
                                <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip"
                                    title="Isikan Nama Jabatan"></i>
                            </label>
                            <select class="form-control" name="user_role_id" id="user_role" required>
                            </select>
                        </div>
                    </div>
                </div>
            </div> -->

            

            



            <button type="submit" class="btn btn-lg btn-primary" style="width: 100%">Simpan Data
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
        </form>
    </div>
</div>

@endif
<div class="modal fade bd-example-modal-lg" id="lampiran_preview_modal" tabindex="-1" role="dialog"
    aria-labelledby="lampiran_preview_title" aria-hidden="true" style="z-index:9999999">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="lampiran_preview_title">Preview Lampiran</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body" id="lampiran_preview_content"></div>
        </div>
    </div>
</div>

<div class="modal fade" id="pegawai_detail_modal" tabindex="-1" role="dialog"
    aria-labelledby="pegawai_detail_title" aria-hidden="true" style="z-index:9999998">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="pegawai_detail_title">Detail Pegawai</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <div class="row" id="pegawai_detail_content"></div>
                <hr>
                <h5 class="mb-3">Lampiran</h5>
                <div id="pegawai_detail_lampiran"></div>
            </div>
        </div>
    </div>
</div>
