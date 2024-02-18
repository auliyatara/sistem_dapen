<!--begin::Javascript-->
<script>var hostUrl = "assets/";</script>
<!--begin::Global Javascript Bundle(mandatory for all pages)-->
<script src="{{ asset('public/assets/plugins/global/plugins.bundle.js')}}"></script>
<script src="{{ asset('public/assets/js/scripts.bundle.js')}}"></script>
<!--end::Global Javascript Bundle-->
<!--begin::Vendors Javascript(used for this page only)-->
<script src="{{ asset('public/assets/plugins/custom/fullcalendar/fullcalendar.bundle.js')}}"></script>
<script src="https://cdn.amcharts.com/lib/5/index.js"></script>
<script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
<script src="https://cdn.amcharts.com/lib/5/percent.js"></script>
<script src="https://cdn.amcharts.com/lib/5/radar.js"></script>
<script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
<script src="https://cdn.amcharts.com/lib/5/map.js"></script>
<script src="https://cdn.amcharts.com/lib/5/geodata/worldLow.js"></script>
<script src="https://cdn.amcharts.com/lib/5/geodata/continentsLow.js"></script>
<script src="https://cdn.amcharts.com/lib/5/geodata/usaLow.js"></script>
<script src="https://cdn.amcharts.com/lib/5/geodata/worldTimeZonesLow.js"></script>
<script src="https://cdn.amcharts.com/lib/5/geodata/worldTimeZoneAreasLow.js"></script>
<script src="{{ asset('public/assets/plugins/custom/datatables/datatables.bundle.js')}}"></script>
<!--end::Vendors Javascript-->
<!--begin::Custom Javascript(used for this page only)-->
<script src="{{ asset('public/assets/js/widgets.bundle.js')}}"></script>
<script src="{{ asset('public/assets/js/custom/widgets.js')}}"></script>
<script src="{{ asset('public/assets/js/custom/apps/chat/chat.js')}}"></script>
<script src="{{ asset('public/assets/js/custom/utilities/modals/upgrade-plan.js')}}"></script>
<script src="{{ asset('public/assets/js/custom/utilities/modals/create-app.js')}}"></script>
<script src="{{ asset('public/assets/js/custom/utilities/modals/new-target.js')}}"></script>
<script src="{{ asset('public/assets/js/custom/utilities/modals/users-search.js')}}"></script>

<!--end::Javascript-->
<!-- include jQuery and select2 JavaScript -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
		
<!-- include the Moment.js library from the jsDelivr CDN -->
<script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script src="{{ asset('public/assets/plugins/custom/jstree/jstree.bundle.js')}}"></script>

<script>
        
        function logout(){
			swal({
				title: "Peringatan",
				text: "Apakah Anda Yakin Untuk Sign Out?",
				icon: "warning",
				buttons: true,
				dangerMode: true,
			})
			.then((response) => {
				if (response) {
					$.ajax({
						type: 'POST',
						url: 'logout',
						dataType: 'json', 
						headers: {
							'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
						},
						success: function(data) {
							window.location.href = getRoute()+'login'; 
						},
						error: function(xhr, status, error) {
							alert('Error: ' + error);
						}
					});
				}
			});
			
		}

        $.ajax({
            url: "{{ route('dashboard_api.index') }}",
            type: 'GET',
            success: function(response){
                if(response.status == true){
                    data = response.data[0]
                    
                    $('#header_name').html(data.user_nama)
                    $('#header_email').html(data.user_email)
                    $('[name=role_name]').val(data.role.role_name)

                    $.each(data, function( key, value ){
                        if(key == "user_foto"){
                            if(value != null){
                                onCheckUrl(`{{ asset('public/storage/user_foto/${value}') }}`, function(exist) {
                                    if(exist){
                                        $("#profile_image").attr("src", `{{ asset('public/storage/user_foto/${value}') }}`);
                                    }
                                });

                                onCheckUrl(`{{ asset('public/storage/user_foto/${data.user_foto}') }}`, function(exist) {
                                    if(exist){
                                        $("#header_image").attr("src", `{{ asset('public/storage/user_foto/${data.user_foto}') }}`);
                                    }
                                });   
                            }
                        }
                    })
                } 
            }
        })

        onCheckUrl = (parse_url, callback) =>{
            let exist = true
            $.ajax({
                url: parse_url,
                method: "GET"
            })
            .done(function() {
                callback(true);
            })
            .fail(function() {
                callback(false);
            });
        }
        
        

        $.ajax({
            url: "{{ route('notifikasi_api.index') }}",
            type: 'GET',
            success: function(response){
                if(response.status == true){
                    data = response.data
                    $('#notification_total').html(response.total)
                    if(data.length){
                        $.each( response.data, function(key, value){
                            let encode = btoa(JSON.stringify(value))
                            $('#notification_list').append(`                                
                                <div class="card shadow-sm" style="cursor:pointer" onclick="onNotification('${encode}')">
                                    <div class="card-body">
                                      <h4>${value.notifikasi_judul}</h4>
                                      <p>${value.notifikasi_keterangan}</p>
                                    </div>
                                </div>`)
                        })
                    } else{
                        $('#notification_list').html(`                                
                            <div class="card">
                                <div class="card-body">
                                  Notifikasi Belum Tersedia
                                </div>
                            </div>`)
                    }

                } 
            }
        })

        checkStorage()
        function checkStorage(){
            $.ajax({
                url: "{{ route('menu_storage_api.index') }}",
                type: 'GET',
                success: function(response){
                    if(response.status){
                        if(localStorage.getItem("menuStorage") === null){
                            localStorage.setItem("menuStorage", response.data.menu_storage_value);
                            menuAccess()
                        } else{
                            let check = (localStorage.getItem("menuStorage") === response.data.menu_storage_value)
                            if(check){
                                const menuList = localStorage.getItem("menuList")
                                const data = JSON.parse(atob(menuList))
                                generateMenuSideBar(data)
                            } else{
                                localStorage.setItem("menuStorage", response.data.menu_storage_value);
                                menuAccess()
                            }
                        }
                    }
                }
            })
        }

        function menuAccess(){
            $.ajax({
                url: "{{ route('roleaccess_api.index') }}",
                type: 'GET',
                success: function(response){
                    if(response.status){
                        generateMenuSideBar(response.data)
                        const arrayString = JSON.stringify(response.data);
                        const encodedString = btoa(arrayString);
                        localStorage.setItem("menuList", encodedString);
                    }
                }
            }) 
        }

        function getRoute(){
            let route = window.location.pathname
            let split = route.split('/')
            return '/'+split[1]+'/'
        }

        setUrlProfile()
        function setUrlProfile(){
            let route = window.location.pathname
            let split = route.split('/')
            $("#profile_url").attr("href", getRoute()+'profile');
        }

        function generateMenuSideBar(data){
            $.each( data, function(key, value){
                let menu = value;
                let route = window.location.origin+getRoute()+menu['menu_route'];

                if(menu['menu_has_child']){
                                $('.sidebar_list').append(`
                                    <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                                        <!--begin:Menu link-->
                                        <span class="menu-link">
                                            <span class="menu-icon">
                                                <!--begin::Svg Icon | path: icons/duotune/communication/com005.svg-->
                                                <span class="svg-icon svg-icon-2">
                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M20 14H18V10H20C20.6 10 21 10.4 21 11V13C21 13.6 20.6 14 20 14ZM21 19V17C21 16.4 20.6 16 20 16H18V20H20C20.6 20 21 19.6 21 19ZM21 7V5C21 4.4 20.6 4 20 4H18V8H20C20.6 8 21 7.6 21 7Z"
                                                            fill="currentColor" />
                                                        <path opacity="0.3"
                                                            d="M17 22H3C2.4 22 2 21.6 2 21V3C2 2.4 2.4 2 3 2H17C17.6 2 18 2.4 18 3V21C18 21.6 17.6 22 17 22ZM10 7C8.9 7 8 7.9 8 9C8 10.1 8.9 11 10 11C11.1 11 12 10.1 12 9C12 7.9 11.1 7 10 7ZM13.3 16C14 16 14.5 15.3 14.3 14.7C13.7 13.2 12 12 10.1 12C8.10001 12 6.49999 13.1 5.89999 14.7C5.59999 15.3 6.19999 16 7.39999 16H13.3Z"
                                                            fill="currentColor" />
                                                    </svg>
                                                </span>
                                                <!--end::Svg Icon-->
                                            </span>
                                            <span class="menu-title">${menu.menu_nama}</span>
                                            <span class="menu-arrow"></span>
                                        </span>
                                        <!--end:Menu link-->
                                        <!--begin:Menu sub-->
                                        <div class="menu-sub menu-sub-accordion" id="parent_sidebar_${menu.menu_id}"></div>
                                        <!--end:Menu sub-->
                                    </div>
                                `);

                                $.each(menu.children, function(keyChild, valueChild){
                                    let route = window.location.origin+getRoute()+valueChild['menu_route'];

                                    $('#parent_sidebar_'+valueChild.menu_parent).append(`<div class="menu-item">
                                        <a class="menu-link" href="${route}">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot"></span>
                                            </span>
                                            <span class="menu-title">${valueChild.menu_nama}</span>
                                        </a>
                                    </div>`)
                                })
                } else{
                                $('.sidebar_list').append(`
                                    <div class="menu-item">
                                        <!--begin:Menu link-->
                                        <a class="menu-link" href="${route}">
                                            <span class="menu-icon">
                                                <!--begin::Svg Icon | path: icons/duotune/general/gen002.svg-->
                                                <span class="svg-icon svg-icon-2">
                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path opacity="0.3"
                                                            d="M4.05424 15.1982C8.34524 7.76818 13.5782 3.26318 20.9282 2.01418C21.0729 1.98837 21.2216 1.99789 21.3618 2.04193C21.502 2.08597 21.6294 2.16323 21.7333 2.26712C21.8372 2.37101 21.9144 2.49846 21.9585 2.63863C22.0025 2.7788 22.012 2.92754 21.9862 3.07218C20.7372 10.4222 16.2322 15.6552 8.80224 19.9462L4.05424 15.1982ZM3.81924 17.3372L2.63324 20.4482C2.58427 20.5765 2.5735 20.7163 2.6022 20.8507C2.63091 20.9851 2.69788 21.1082 2.79503 21.2054C2.89218 21.3025 3.01536 21.3695 3.14972 21.3982C3.28408 21.4269 3.42387 21.4161 3.55224 21.3672L6.66524 20.1802L3.81924 17.3372ZM16.5002 5.99818C16.2036 5.99818 15.9136 6.08615 15.6669 6.25097C15.4202 6.41579 15.228 6.65006 15.1144 6.92415C15.0009 7.19824 14.9712 7.49984 15.0291 7.79081C15.0869 8.08178 15.2298 8.34906 15.4396 8.55884C15.6494 8.76862 15.9166 8.91148 16.2076 8.96935C16.4986 9.02723 16.8002 8.99753 17.0743 8.884C17.3484 8.77046 17.5826 8.5782 17.7474 8.33153C17.9123 8.08486 18.0002 7.79485 18.0002 7.49818C18.0002 7.10035 17.8422 6.71882 17.5609 6.43752C17.2796 6.15621 16.8981 5.99818 16.5002 5.99818Z"
                                                            fill="currentColor" />
                                                        <path
                                                            d="M4.05423 15.1982L2.24723 13.3912C2.15505 13.299 2.08547 13.1867 2.04395 13.0632C2.00243 12.9396 1.9901 12.8081 2.00793 12.679C2.02575 12.5498 2.07325 12.4266 2.14669 12.3189C2.22013 12.2112 2.31752 12.1219 2.43123 12.0582L9.15323 8.28918C7.17353 10.3717 5.4607 12.6926 4.05423 15.1982ZM8.80023 19.9442L10.6072 21.7512C10.6994 21.8434 10.8117 21.9129 10.9352 21.9545C11.0588 21.996 11.1903 22.0083 11.3195 21.9905C11.4486 21.9727 11.5718 21.9252 11.6795 21.8517C11.7872 21.7783 11.8765 21.6809 11.9402 21.5672L15.7092 14.8442C13.6269 16.8245 11.3061 18.5377 8.80023 19.9442ZM7.04023 18.1832L12.5832 12.6402C12.7381 12.4759 12.8228 12.2577 12.8195 12.032C12.8161 11.8063 12.725 11.5907 12.5653 11.4311C12.4057 11.2714 12.1901 11.1803 11.9644 11.1769C11.7387 11.1736 11.5205 11.2583 11.3562 11.4132L5.81323 16.9562L7.04023 18.1832Z"
                                                            fill="currentColor" />
                                                    </svg>
                                                </span>
                                                <!--end::Svg Icon-->
                                            </span>
                                            <span class="menu-title">${menu.menu_nama}</span>
                                        </a>
                                        <!--end:Menu link-->
                                    </div>
                                `)
                }

            })
        }

        function onNotification(data){
            var data = JSON.parse(atob(data))

            var urlpath ={
                update: "{{ route('notifikasi_api.update', ['notifikasi_api' => ':id']) }}",
            }

            $.ajax({
                url: urlpath.update.replace(':id', data.notifikasi_id),
                data: {},
                contentType: false,
                processData: false,
                type: 'PATCH',
                headers:{
                        'Content-Type': 'application/x-www-form-urlencoded' // Set the custom Content-Type header
                } ,
                success: function(response){
                    if(response.status == true){
                        let base = window.location.origin
                        window.location.href = base+getRoute()+data.notifikasi_url;
                    } 
                }
            })
        }

        var table = $('#table_primary').DataTable({
            createdRow: function(row, data, dataIndex) {
                $(row).css('text-align', 'center');
                $(row).attr('id', 'row_' + data[0]);
            },
            columnDefs: [
                { targets: 1, className: 'text-left' } // Apply 'text-left' class to column 1
            ]
        });

        var table_secondary = $('#table_secondary').DataTable({
            createdRow: function(row, data, dataIndex) {
                $(row).css('text-align', 'center');
                $(row).attr('id', 'row_' + data[0]);
            },
            columnDefs: [
                { targets: 1, className: 'text-left' } // Apply 'text-left' class to column 1
            ]
        });

</script>
<!--end::Custom Javascript-->
<!--end::Javascript-->