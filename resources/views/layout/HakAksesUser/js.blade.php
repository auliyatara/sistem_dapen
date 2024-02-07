{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/jstree.min.js"></script> --}}
<script>
    var urlpath ={
        insert: "{{ route('roleaccess_api.store') }}",
        insertRole: "{{ route('role_api.store') }}",
        updateRole: "{{ route('role_api.update', ['role_api' => ':id']) }}",
        select: "{{ route('role_api.index') }}",
        show: "{{ route('roleaccess_api.show', ['roleaccess_api' => ':id']) }}",
        showmenu: "{{ route('menu_api.show', ['menu_api' => ':id']) }}",
        showEdit: "{{ route('role_api.read', ['role_api' => ':id']) }}",
    }

    $(function(){
        onInitTable();
    })

    onAdd = () => {
        $('.table_data').hide()
        $('.form_data').show()
    };

    onBack = () => {
        $('.table_data').show()
        $('.form_data').hide()
        onClear();
    };

    onInitTable = () =>{
        table.clear();
        $.ajax({
            url: urlpath.select,
            type: 'GET',
            success: function(response){
                if(response.status == true){
                    var array = []
                    $.each( response.data, function( k, v ){
                        let html_action = `
                            <td class="text-center">
                                <a href="javascript:onEdit('${v.role_id}')" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
									<!--begin::Svg Icon | path: icons/duotune/art/art005.svg-->
									<span class="svg-icon svg-icon-3">
										<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path opacity="0.3" d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z" fill="currentColor"></path>
											<path d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z" fill="currentColor"></path>
										</svg>
									</span>
									<!--end::Svg Icon-->
								</a>
                                <a href="javascript:onDetail('${v.role_id}','${v.role_name}','${k+1}')" class="btn btn-sm btn-primary" style="padding:1px 3px">
                                    <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo8/dist/../src/media/svg/icons/Navigation/Right-2.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="10px" height="10px" viewBox="0 0 24 24" version="1.1">
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <polygon points="0 0 24 0 24 24 0 24"/>
                                            <rect fill="#ffffff" opacity="0.3" transform="translate(8.500000, 12.000000) rotate(-90.000000) translate(-8.500000, -12.000000) " x="7.5" y="7.5" width="2" height="9" rx="1"/>
                                            <path d="M9.70710318,15.7071045 C9.31657888,16.0976288 8.68341391,16.0976288 8.29288961,15.7071045 C7.90236532,15.3165802 7.90236532,14.6834152 8.29288961,14.2928909 L14.2928896,8.29289093 C14.6714686,7.914312 15.281055,7.90106637 15.675721,8.26284357 L21.675721,13.7628436 C22.08284,14.136036 22.1103429,14.7686034 21.7371505,15.1757223 C21.3639581,15.5828413 20.7313908,15.6103443 20.3242718,15.2371519 L15.0300721,10.3841355 L9.70710318,15.7071045 Z" fill="#ffffff" fill-rule="nonzero" transform="translate(14.999999, 11.999997) scale(1, -1) rotate(90.000000) translate(-14.999999, -11.999997) "/>
                                        </g>
                                    </svg><!--end::Svg Icon--></span>
                                </a>
							</td>
                        `
                        let push_array = [k+1,v.role_name,html_action]
                        array.push(push_array);
                    });

                    table.rows.add(array).draw();    
                } 
            }
        })
        table.draw();
    }

    onRefresh = ()=>{
        // onInitTable();
        window.location.reload();
    }

    onClear = ()=>{
        $('#formData')[0].reset();
    }

    onSave = () =>{
        swal({
            title: "Peringatan",
            text: "Apakah Anda Yakin Untuk Menyimpan Data?",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((response) => {
            if (response) {
                const formElement = $('#formDataAccess')[0];
                var form = new FormData(formElement);

                var selected = $('#list_menu_1').jstree(true).get_selected();
                form.append('role_id', btoa(selected.join(',')));
                
                $.ajax({
                    url: urlpath.insert,
                    data: form,
                    contentType: false,
                    processData: false,
                    type: 'POST',
                    success: function(response){
                        if(response.status == true){
                            swal("Success !", response.message, "success");
                            onRefresh()
                        } else{
                            swal("Warning", response.message, "warning");
                        }
                    }
                })
            }
        }); 
    }


    onSaveRole = () =>{
        swal({
            title: "Peringatan",
            text: "Apakah Anda Yakin Untuk Menyimpan Data?",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((response) => {
            if (response) {
                const formElement = $('#formData')[0];
                var content_type = {}
                if($('[name=role_id]').val() == ''){
                    var form = new FormData(formElement);
                    urlsave = urlpath.insertRole
                    urltype = 'POST'
                } else{
                    var form = $('#formData').serialize();
                    urlsave = urlpath.updateRole.replace(':id', $('[name=role_id]').val())
                    urltype = 'PATCH'
                    var content_type = {
                        'Content-Type': 'application/x-www-form-urlencoded' // Set the custom Content-Type header
                    } 
                }

                $.ajax({
                    url: urlsave,
                    data: form,
                    contentType: false,
                    processData: false,
                    type: urltype,
                    headers: content_type,
                    success: function(response){
                        if(response.status == true){
                            swal("Success !", response.message, "success");
                            onRefresh()
                        } else{
                            swal("Warning", response.message, "warning");
                        }
                    }
                })
            }
        }); 
    }


    onEdit = (id) => {
        onAdd()
        $.ajax({
            url: urlpath.showEdit.replace(':id', id),
            type: 'GET',
            success: function(response){
                if(response.status == true){
                    $.each(response.data, function( key, value ){
                        $('[name='+key+']').val(value)
                    })
                } 
            }
        })
    };

    onDetail = (id,name,index) =>{
        $.ajax({
            url: urlpath.showmenu.replace(':id', id),
            type: 'GET',
            success: function(response){
                if(response.status == true){
                    $("tr").css("background-color","");
                    $("tr:eq("+index+")").css("background-color","#b8bafb");
                    $('#role_title').html(name)
                    $('#user_id').val(id)
                    $('.menu_list').html('')
                    $('.button_form').html('')

                    $("#list_menu_1").jstree("destroy");
                    $('#list_menu_1').jstree({
                        'plugins': ["wholerow", "checkbox", "types"],
                        'core': {
                            "themes" : {
                                "responsive": false
                            },
                            'data': response.data
                        },
                        "types" : {
                            "default" : {
                                "icon" : "ki-solid ki-folder text-warning"
                            },
                            "file" : {
                                "icon" : "ki-solid ki-file  text-warning"
                            }
                        },
                    });
                } 
            },
            complete: function(xhr, status) {
                $('.button_form').html(`
                    <button type="submit" class="btn btn-lg btn-primary mt-3" style="width: 100%">Simpan Data
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
                `)
            }
        })

    }
</script>