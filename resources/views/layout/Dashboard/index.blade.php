@extends('master.app')

@section('content')
    <div class="d-flex flex-column flex-column-fluid">
        <!--begin::Content-->
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <!--begin::Content container-->
            <div id="kt_app_content_container" class="app-container container-fluid">
                <div class="row d-none">
                    <div class="col-8 d-none">
                        <div class="card mb-5 mb-xxl-8">
                            <div class="card-body pt-9 pb-9">
                                <!--begin::Details-->
                                <div class="d-flex flex-wrap flex-sm-nowrap">
                                    <!--begin: Pic-->
                                    {{-- <div class="me-7 mb-4">
                                        <div class="symbol symbol-100px symbol-lg-160px symbol-fixed position-relative">
                                            <img src="{{asset('public/assets/media/avatars/300-1.jpg')}}" alt="image">
                                            <div
                                                class="position-absolute translate-middle bottom-0 start-100 mb-6 bg-success rounded-circle border border-4 border-body h-20px w-20px">
                                            </div>
                                        </div>
                                    </div> --}}
                                    <!--end::Pic-->
                                    <!--begin::Info-->
                                    <div class="flex-grow-1">
                                        <!--begin::Title-->
                                        <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                                            <!--begin::User-->
                                            <div class="d-flex flex-column">
                                                <!--begin::Name-->
                                                <div class="d-flex align-items-center mb-2">
                                                    <a href="" class="text-gray-900 text-hover-primary fs-1 fw-bold me-1">Selamat Datang, <span id="user_nama">User</span></a>
                                                    <a href="#">
                                                        <!--begin::Svg Icon | path: icons/duotune/general/gen026.svg-->
                                                        <span class="svg-icon svg-icon-1 svg-icon-primary">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px"
                                                                viewBox="0 0 24 24">
                                                                <path
                                                                    d="M10.0813 3.7242C10.8849 2.16438 13.1151 2.16438 13.9187 3.7242V3.7242C14.4016 4.66147 15.4909 5.1127 16.4951 4.79139V4.79139C18.1663 4.25668 19.7433 5.83365 19.2086 7.50485V7.50485C18.8873 8.50905 19.3385 9.59842 20.2758 10.0813V10.0813C21.8356 10.8849 21.8356 13.1151 20.2758 13.9187V13.9187C19.3385 14.4016 18.8873 15.491 19.2086 16.4951V16.4951C19.7433 18.1663 18.1663 19.7433 16.4951 19.2086V19.2086C15.491 18.8873 14.4016 19.3385 13.9187 20.2758V20.2758C13.1151 21.8356 10.8849 21.8356 10.0813 20.2758V20.2758C9.59842 19.3385 8.50905 18.8873 7.50485 19.2086V19.2086C5.83365 19.7433 4.25668 18.1663 4.79139 16.4951V16.4951C5.1127 15.491 4.66147 14.4016 3.7242 13.9187V13.9187C2.16438 13.1151 2.16438 10.8849 3.7242 10.0813V10.0813C4.66147 9.59842 5.1127 8.50905 4.79139 7.50485V7.50485C4.25668 5.83365 5.83365 4.25668 7.50485 4.79139V4.79139C8.50905 5.1127 9.59842 4.66147 10.0813 3.7242V3.7242Z"
                                                                    fill="currentColor"></path>
                                                                <path
                                                                    d="M14.8563 9.1903C15.0606 8.94984 15.3771 8.9385 15.6175 9.14289C15.858 9.34728 15.8229 9.66433 15.6185 9.9048L11.863 14.6558C11.6554 14.9001 11.2876 14.9258 11.048 14.7128L8.47656 12.4271C8.24068 12.2174 8.21944 11.8563 8.42911 11.6204C8.63877 11.3845 8.99996 11.3633 9.23583 11.5729L11.3706 13.4705L14.8563 9.1903Z"
                                                                    fill="white"></path>
                                                            </svg>
                                                        </span>
                                                        <!--end::Svg Icon-->
                                                    </a>
                                                </div>
                                                <!--end::Name-->
                                                <!--begin::Info-->
                                                <div class="d-flex flex-wrap fw-semibold fs-6 mb-4 pe-2">
                                                    <a href="#"
                                                        class="d-flex align-items-center text-gray-400 text-hover-primary me-5 mb-2">
                                                        <!--begin::Svg Icon | path: icons/duotune/communication/com006.svg-->
                                                        <span class="svg-icon svg-icon-4 me-1">
                                                            <svg width="18" height="18" viewBox="0 0 18 18" fill="none"
                                                                xmlns="http://www.w3.org/2000/svg">
                                                                <path opacity="0.3"
                                                                    d="M16.5 9C16.5 13.125 13.125 16.5 9 16.5C4.875 16.5 1.5 13.125 1.5 9C1.5 4.875 4.875 1.5 9 1.5C13.125 1.5 16.5 4.875 16.5 9Z"
                                                                    fill="currentColor"></path>
                                                                <path
                                                                    d="M9 16.5C10.95 16.5 12.75 15.75 14.025 14.55C13.425 12.675 11.4 11.25 9 11.25C6.6 11.25 4.57499 12.675 3.97499 14.55C5.24999 15.75 7.05 16.5 9 16.5Z"
                                                                    fill="currentColor"></path>
                                                                <rect x="7" y="6" width="4" height="4"
                                                                    rx="2" fill="currentColor"></rect>
                                                            </svg>
                                                        </span>
                                                        <!--end::Svg Icon--> <span id="role_name">Developer</span></a>
                                                    <a href="#"
                                                        class="d-flex align-items-center text-gray-400 text-hover-primary mb-2">
                                                        <!--begin::Svg Icon | path: icons/duotune/communication/com011.svg-->
                                                        <span class="svg-icon svg-icon-4 me-1">
                                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                                xmlns="http://www.w3.org/2000/svg">
                                                                <path opacity="0.3"
                                                                    d="M21 19H3C2.4 19 2 18.6 2 18V6C2 5.4 2.4 5 3 5H21C21.6 5 22 5.4 22 6V18C22 18.6 21.6 19 21 19Z"
                                                                    fill="currentColor"></path>
                                                                <path
                                                                    d="M21 5H2.99999C2.69999 5 2.49999 5.10005 2.29999 5.30005L11.2 13.3C11.7 13.7 12.4 13.7 12.8 13.3L21.7 5.30005C21.5 5.10005 21.3 5 21 5Z"
                                                                    fill="currentColor"></path>
                                                            </svg>
                                                        </span>
                                                        <!--end::Svg Icon--><span id="user_email">max@kt.com</span></a>
                                                </div>
                                                <!--end::Info-->
                                            </div>
                                            {{-- <em>"Satu-satunya cara untuk melakukan pekerjaan hebat yaitu dengan mencintai apa yang sedang kamu lakukan."</em>
                                             <br> -Steve Jobs --}}
                                            <!--end::User-->

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
                                                <div class="col-12">
                                                    <div class="row">

                                                            <div class="col-12 mb-7">
                                                                <label>Nama User</label>
                                                                <input type="text" class="form-control form-control-solid"
                                                                name="user_nama" placeholder="Nama User" disabled />                
                                                            </div>
                                                            <div class="col-12 mb-7">
                                                                <label>Jabatan</label>

                                                                <input type="text" class="form-control form-control-solid"
                                                                        name="role_name" placeholder="Jabatan" disabled/>
                                                            </div>
                                                            <div class="col-12 mb-7">
                                                                <label>Nomor Kontak</label>
                                                                <input type="text" class="form-control form-control-solid" name="user_kontak" placeholder="" disabled/>
                                                            </div>
                                                            <div class="col-4 mb-7">
                                                                <label>Tempat Lahir</label>
                                                                <input type="text" class="form-control form-control-solid"
                                                                        name="user_tempat_lahir" placeholder="Tempat Lahir" value=""
                                                                        required disabled/>
                                                            </div>
                                                            <div class="col-8 mb-7">
                                                                <label>Tanggal Lahir</label>
                                                                <input type="date" class="form-control form-control-solid"
                                                                        name="user_tanggal_lahir" placeholder="Tanggal Lahir" value=""
                                                                        required disabled/>
                                                            </div>
                    
                                                            <div class="col-12 mb-7">
                                                                <label>Alamat</label>
                                                                <input type="text" class="form-control form-control-solid"
                                                                        name="user_alamat" placeholder="Alamat" value=""
                                                                        required disabled/>
                                                            </div>
                    
                                                            <div class="col-12  mb-7">
                                                                <label>Username</label>

                                                                <input type="text" class="form-control form-control-solid"
                                                                name="user_username" placeholder="Nama Username" value=""
                                                                required disabled/>
                                                            </div>
                    
                                                            <div class="col-12">
                                                                <label>Email</label>
                                                                <input type="text" class="form-control form-control-solid"
                                                                        name="user_email" placeholder="Email" value="" required disabled/>
                                                            </div>
                                                    </div>

                                                </div>
                                            </div>

                                        </div>
                                        <!--end::Title-->
                                    </div>
                                    <!--end::Info-->
                                </div>
                                <!--end::Details-->
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card mb-5 mb-xxl-8 bg-light">
                            <div class="card-body pt-9 pb-9">
                                <h3>Informasi Umum</h3>
                                <div class="list_informasi" style="margin-top:20px">
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-4">
                        <div class="card mb-5 mb-xxl-8 h-100">
                            <div class="card-header border-0 pt-5">
                                <h3 class="card-title fw-bold">Statistik Peserta Aktif</h3>
                            </div>
                            <div class="card-body pt-2 pb-6">
                                <div id="chart_peserta_dashboard" style="height: 300px;"></div>
                                <div id="legend_peserta_dashboard" class="d-flex flex-wrap justify-content-center gap-3 mt-3"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card mb-5 mb-xxl-8 h-100">
                            <div class="card-header border-0 pt-5">
                                <h3 class="card-title fw-bold">Statistik Pensiunan</h3>
                            </div>
                            <div class="card-body pt-2 pb-6">
                                <div id="chart_pensiunan_dashboard" style="height: 300px;"></div>
                                <div id="legend_pensiunan_dashboard" class="d-flex flex-wrap justify-content-center gap-3 mt-3"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card mb-5 mb-xxl-8 h-100 bg-light-primary">
                            <div class="card-header border-0 pt-5">
                                <h3 class="card-title fw-bold">Informasi Umum</h3>
                            </div>
                            <div class="card-body pt-2 pb-6">
                                <div id="dashboard_informasi_list">
                                    <div class="text-muted">Memuat informasi...</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mt-8 mb-5 mb-xxl-8">
                    <div class="card-header border-0 pt-5">
                        <h3 class="card-title fw-bold">Statistik Pensiun Lainnya</h3>
                    </div>
                    <div class="card-body pt-2 pb-6">
                        <div id="chart_pensiun_lain_dashboard" style="height: 340px;"></div>
                        <div id="legend_pensiun_lain_dashboard" class="d-flex flex-wrap justify-content-center gap-3 mt-3"></div>
                    </div>
                </div>

            </div>
            <!--end::Content container-->
        </div>
        <!--end::Content-->
    </div>
@endsection

@push('javascript')
    @include('layout.Dashboard.js')
@endpush
