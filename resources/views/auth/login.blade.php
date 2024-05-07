<!DOCTYPE html>
<!--
Author: Keenthemes
Product Name: Metronic | Bootstrap HTML, VueJS, React, Angular, Asp.Net Core, Rails, Spring, Blazor, Django, Flask & Laravel Admin Dashboard Theme
Purchase: https://1.envato.market/EA4JP
Website: http://www.keenthemes.com
Contact: support@keenthemes.com
Follow: www.twitter.com/keenthemes
Dribbble: www.dribbble.com/keenthemes
Like: www.facebook.com/keenthemes
License: For each use you must have a valid license purchased only from above link in order to legally use the theme for your project.
-->
<html lang="en">
<!--begin::Head-->

<head>
    <base href="../../../" />
    <title>SIDIG UMM</title>
    <meta charset="utf-8" />
    <meta name="description"
        content="The most advanced Bootstrap Admin Theme on Themeforest trusted by 100,000 beginners and professionals. Multi-demo, Dark Mode, RTL support and complete React, Angular, Vue, Asp.Net Core, Rails, Spring, Blazor, Django, Flask & Laravel versions. Grab your copy now and get life-time updates for free." />
    <meta name="keywords"
        content="metronic, bootstrap, bootstrap 5, angular, VueJs, React, Asp.Net Core, Rails, Spring, Blazor, Django, Flask & Laravel starter kits, admin themes, web design, figma, web development, free templates, free admin themes, bootstrap theme, bootstrap template, bootstrap dashboard, bootstrap dak mode, bootstrap button, bootstrap datepicker, bootstrap timepicker, fullcalendar, datatables, flaticon" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="article" />
    <meta property="og:title"
        content="Metronic | Bootstrap HTML, VueJS, React, Angular, Asp.Net Core, Rails, Spring, Blazor, Django, Flask & Laravel Admin Dashboard Theme" />
    <meta property="og:url" content="https://keenthemes.com/metronic" />
    <meta property="og:site_name" content="Keenthemes | Metronic" />
    <link rel="canonical" href="https://preview.keenthemes.com/metronic8" />
    <link rel="shortcut icon" href="{{asset('public/assets/media/logos/favicon.ico')}}" />
    <!--begin::Fonts(mandatory for all pages)-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <!--end::Fonts-->
    <!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
    <link href="{{asset('public/assets/plugins/global/plugins.bundle.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('public/assets/css/style.bundle.css')}}" rel="stylesheet" type="text/css" />
    <!--end::Global Stylesheets Bundle-->
</head>
<!--end::Head-->
<!--begin::Body-->

<body id="kt_body" class="app-blank">
    <!--begin::Theme mode setup on page load-->
    <script>
        var defaultThemeMode = "light";
        var themeMode;
        if (document.documentElement) {
            if (document.documentElement.hasAttribute("data-theme-mode")) {
                themeMode = document.documentElement.getAttribute("data-theme-mode");
            } else {
                if (localStorage.getItem("data-theme") !== null) {
                    themeMode = localStorage.getItem("data-theme");
                } else {
                    themeMode = defaultThemeMode;
                }
            }
            if (themeMode === "system") {
                themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
            }
            document.documentElement.setAttribute("data-theme", themeMode);
        }

    </script>
    <!--end::Theme mode setup on page load-->
    <!--begin::Main-->
    <!--begin::Root-->
    <div class="d-flex flex-column flex-root">
        <!--begin::Authentication - Sign-in -->
        <div class="d-flex flex-column flex-lg-row flex-column-fluid">
            <!--begin::Body-->
            <div class="d-flex flex-column flex-lg-row-fluid w-lg-50 p-10 order-2 order-lg-1 position-relative" style="background-image:url({{asset('public/images/utama.jpg')}});background-repeat:no-repeat;background-size: cover; ">
                <div class="position-absolute top-0 start-0 w-100 h-100" style="background-color: rgba(255, 255, 255, 0.5);z-index:0"></div>
                <!--begin::Form-->
                <div class="d-flex flex-center flex-column flex-lg-row-fluid" style="z-index: 10">
                    <!--begin::Wrapper-->
                    <div class="w-lg-500px">
                        <!--begin::Form-->
                            <!--begin::Heading-->
                            <div class="text-center">
                                <img alt="Logo" src="{{asset('public/images/logo_dapen.png')}}" width=400px/>
                                <!--begin::Title-->
                                {{-- <h1 class="text-dark fw-bolder mb-3">Sign In</h1>
                                <!--end::Title-->
                                <!--begin::Subtitle-->
                                <div class="text-gray-500 fw-semibold fs-6">SIDIG UMM</div> --}}
                                <!--end::Subtitle=-->
                            </div>
                            <h1 class="text-dark fw-bolder mb-3">Selamat Datang di</h1>
                            <h1 class="text-dark fw-bolder mb-3">SIDIG UMM</h1>
                            <!--begin::Heading-->

                            @if(session('error'))
                                <div class="alert alert-danger">
                                    {{ session('error') }}
                                </div>
                            @endif
                            <form method="POST" action="{{ route('login') }}">
                                @csrf

                                <!--begin::Input group=-->
                                <div class="fv-row mb-8">
                                    <!--begin::Email-->
                                    <div class="col-12 mb-7">
                                        <div class="form-floating ">
                                            <input type="text" class="form-control form-control-solid"
                                                name="user_username" placeholder="" required/>
                                            <label for="floatingInput"><span>User Name</span></label>
                                        </div>
                                        {{-- <label style="color:red;display:none" id="alert_user_name"><span>Kolom Ini Perlu Diisi</span></label> --}}
                                    </div>
                                    {{-- <input type="text" placeholder="Username" name="user_username" autocomplete="off"
                                        class="form-control bg-transparent" value="user"/> --}}
                                    <!--end::Email-->
                                </div>
                                <!--end::Input group=-->
                                <div class="fv-row mb-3">
                                    <!--begin::Password-->
                                    <div class="form-floating">
                                        <input type="password" class="form-control form-control-solid"
                                            name="password" placeholder=""
                                            value="" required/>
                                        <label for="floatingInput"><span>Password</span></label>
                                    </div>
                                    {{-- <label style="color:red;display:none" id="alert_password"><span>Kolom Ini Perlu Diisi</span></label> --}}
                                    {{-- <input type="password" placeholder="Password" name="password" autocomplete="off"
                                        class="form-control bg-transparent" value="@user12345"/> --}}
                                    <!--end::Password-->
                                </div>
                                <!--end::Input group=-->
                
                                <!--begin::Submit button-->
                                <div class="d-grid mb-10">
                                    <button type="submit" class="btn btn-primary">
                                        <!--begin::Indicator label-->
                                        <span class="indicator-label">Sign In</span>
                           
                                    </button>
                                </div>
                                <!--end::Submit button-->
                            </form>                         
                        
                        <!--end::Form-->
                    </div>
                    <!--end::Wrapper-->
                </div>
                <!--end::Form-->
                <!--begin::Footer-->
                <div class="d-flex flex-center flex-wrap px-5">
                    <!--begin::Links-->
                    <div class="d-flex fw-semibold text-primary fs-base">
                        <a href="../../demo11/dist/pages/team.html" class="px-5" target="_blank">Terms</a>
                        <a href="../../demo11/dist/pages/pricing/column.html" class="px-5" target="_blank">Plans</a>
                        <a href="../../demo11/dist/pages/contact.html" class="px-5" target="_blank">Contact Us</a>
                    </div>
                    <!--end::Links-->
                </div>
                <!--end::Footer-->
            </div>
            <!--end::Body-->
            <!--begin::Aside-->
            <div class="d-flex flex-lg-row-fluid w-lg-50 bgi-size-cover bgi-position-center order-1 order-lg-2"
                style="background-image: url({{asset('public/images/portal.jpeg')}})">

            </div>
            <!--end::Aside-->
        </div>
        <!--end::Authentication - Sign-in-->
    </div>
    <!--end::Root-->
    <!--end::Main-->
    <!--begin::Javascript-->
    <script>
        var hostUrl = "assets/";

    </script>
    <!--begin::Global Javascript Bundle(mandatory for all pages)-->
    <script src="{{asset('public/assets/plugins/global/plugins.bundle.js')}}"></script>
    <script src="{{asset('public/assets/js/scripts.bundle.js')}}"></script>
    <!--end::Global Javascript Bundle-->
    <!--begin::Custom Javascript(used for this page only)-->
    <script src="{{asset('public/assets/js/custom/authentication/sign-in/general.js')}}"></script>
    <!--end::Custom Javascript-->
    <!--end::Javascript-->
</body>
<!--end::Body-->

</html>
