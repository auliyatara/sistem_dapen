<div class="form_data" style="display:none">
    <div class="card-header border-0 pt-5">
        <h2 class="card-title align-items-start flex-column">
            <span class="card-label fw-bold fs-3 mb-1">Tambah Informasi</span>
        </h2>
        <div class="card-toolbar">
            <a href="javascript:onBack()" class="btn btn-sm btn-danger">Kembali</a>
        </div>
    </div>
    
    <div class="card-body py-3 mt-3">
        <form action="javascript:onSave()" id="formData" method="POST" autocomplete="off">
            @csrf
            <input type="hidden" name="informasi_id" placeholder="" value="" />
            <div class="col-12">
                <div class="form-floating mb-7">
                    <input type="text" class="form-control form-control-solid"
                        name="informasi_judul" placeholder="" value=""
                        required />
                    <label for="floatingInput"><span class="required">Judul</span></label>
                </div>
            </div>
            <div class="col-12">
                <div class="form-floating mb-7">
                    <input type="date" class="form-control form-control-solid"
                        name="informasi_tanggal" placeholder="" value=""
                        required />
                    <label for="floatingInput"><span class="required">Tanggal</span></label>
                </div>
            </div>
            <div class="col-12">
                <div class="form-floating mb-7">
                    <input type="text" class="form-control form-control-solid"
                        name="informasi_deskripsi" placeholder="" value=""
                        required />
                    <label for="floatingInput"><span class="required">Deskripsi</span></label>
                </div>
            </div>


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

