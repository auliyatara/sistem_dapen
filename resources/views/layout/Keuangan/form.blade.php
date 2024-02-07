<div class="card shadow-sm form_data" style="display:none">
    <div class="card-header">
        <h3 class="card-title">Tambah Data</h3>
        <div class="card-toolbar">
            <a href="javascript:onBack()" class="btn btn-sm btn-danger">Kembali</a>
        </div>
    </div>
    <form action="javascript:onSave()" id="formData" method="POST" autocomplete="off">
        <div class="card-body py-3 mt-3">

            @csrf
            <input type="hidden" name="keuangan_id" placeholder="" value="" />
            <div class="form-floating mb-7">
                <input type="text" class="form-control form-control-solid" name="keuangan_nama"
                    placeholder="Nama Data" value="" required />
                <label for="floatingInput"><span class="required">Nama Data</span></label>
            </div>

            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body py-3 mt-3">
                        <label class="d-flex align-items-center fs-5 fw-semibold mb-2">
                            <span>Nama Sub-data</span>
                        </label>
                        <div id="list_subdata"></div>
                        <button type="button" class="btn btn-sm btn-success" style="width: 100%"
                            onclick="onAddList()">
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
                </div> 
            </div>

         


        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-lg btn-primary mt-6" style="width: 100%">Simpan Data
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
        </div>
    </form>
</div>
