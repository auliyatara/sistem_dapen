@extends('master.app')

@section('content')
    <div class="d-flex flex-column flex-column-fluid">
        <!--begin::Content-->
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <!--begin::Content container-->
            <div id="kt_app_content_container" class="app-container container-fluid">
                <div class="card mb-5 mb-xxl-8">
                    <div class="card-body pt-9 pb-9">
                        <div class="row">
                            <div class="col-3">
                                <div class="me-7 mb-4">
                                    <div class="symbol symbol-100px symbol-lg-160px symbol-fixed position-relative">
                                        <img src="{{asset('public/assets/images/profile_default.png')}}" alt="image" id="profile_image">
                                        <div
                                            class="position-absolute translate-middle bottom-0 start-100 mb-6 bg-success rounded-circle border border-4 border-body h-20px w-20px">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-9">
                                <!--begin::Name-->
                                <div class="d-flex align-items-center mb-2">
                                    <a href="" class="text-gray-900 text-hover-primary fs-1 fw-bold me-1">My
                                        Profile</span></a>
                                    <!--end::Svg Icon-->
                                    </a>
                                </div>
                                <div class="separator border-dark my-10"></div>
                                <!--end::Name-->

                                <form action="javascript:onSave()" id="formData" method="POST" autocomplete="off" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="user_id" placeholder="" value="" />
                                    <div class="row">
                                        <div class="col-12 mb-7">
                                            <label for="file">Foto Profil</label>
                                            <input type="file" class="form-control" name="file" placeholder="" value="" required accept="image/png, image/gif, image/jpeg"/>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-floating mb-7">
                                                <input type="text" class="form-control form-control-solid"
                                                    name="user_nama" placeholder="Nama User" required />
                                                <label for="floatingInput"><span class="required">Nama User</span></label>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-floating mb-7">
                                                <input type="text" class="form-control form-control-solid"
                                                    name="role_name" placeholder="Nama User" required disabled/>
                                                <label for="floatingInput"><span class="required">Jabatan</span></label>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-floating mb-7">
                                                <input type="text" class="form-control form-control-solid"
                                                    name="user_kontak" placeholder="" required/>
                                                <label for="floatingInput"><span class="required">Nomor Kontak</span></label>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-floating mb-7">
                                                <input type="text" class="form-control form-control-solid"
                                                    name="user_tempat_lahir" placeholder="Tempat Lahir" value=""
                                                    required />
                                                <label for="floatingInput"><span class="required">Tempat Lahir</span></label>
                                            </div>
                                        </div>
                                        <div class="col-8">
                                            <div class="form-floating mb-7">
                                                <input type="date" class="form-control form-control-solid"
                                                    name="user_tanggal_lahir" placeholder="Tanggal Lahir" value=""
                                                    required />
                                                <label for="floatingInput"><span class="required">Tanggal Lahir</span></label>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="form-floating mb-7">
                                                <input type="text" class="form-control form-control-solid"
                                                    name="user_alamat" placeholder="Alamat" value=""
                                                    required />
                                                <label for="floatingInput"><span class="required">Alamat</span></label>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="form-floating mb-7">
                                                <input type="text" class="form-control form-control-solid"
                                                    name="user_username" placeholder="Nama Username" value=""
                                                    required />
                                                <label for="floatingInput"><span class="required">Username</span></label>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="form-floating mb-7">
                                                <input type="text" class="form-control form-control-solid"
                                                    name="user_email" placeholder="Email" value="" required />
                                                <label for="floatingInput"><span class="required">Email</span></label>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-floating mb-7">
                                                <input type="text" class="form-control form-control-solid"
                                                    name="password" placeholder=""
                                                    value=""/>
                                                <label for="floatingInput"><span>Password (Isi Jika Inging Mengganti)</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-lg btn-primary" style="width: 100%">Simpan Data
                                        <span class="svg-icon svg-icon-3 ms-1 me-0">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <rect opacity="0.5" x="18" y="13" width="13"
                                                    height="2" rx="1" transform="rotate(-180 18 13)"
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
                    </div>
                </div>
            </div>
            <!--end::Content container-->
        </div>
        <!--end::Content-->
    </div>
@endsection

@push('javascript')
    @include('layout.Profile.js')
@endpush
