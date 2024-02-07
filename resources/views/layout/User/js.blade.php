<script>
    var urlpath ={
        insert: "{{ route('users_api.store') }}",
        update: "{{ route('users_api.update', ['users_api' => ':id']) }}",
        delete: "{{ route('users_api.destroy', ['users_api' => ':id']) }}",
        select: "{{ route('users_api.index') }}",
        show: "{{ route('users_api.show', ['users_api' => ':id']) }}",
        role: "{{ route('role_api.index') }}"
    }

    $(function(){
        onInitTable();
        onRole();
    })

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
												<a href="javascript:onEdit('${v.user_id}')" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
													<!--begin::Svg Icon | path: icons/duotune/art/art005.svg-->
													<span class="svg-icon svg-icon-3">
														<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
															<path opacity="0.3" d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z" fill="currentColor"></path>
															<path d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z" fill="currentColor"></path>
														</svg>
													</span>
													<!--end::Svg Icon-->
												</a>
												<a href="javascript:onDelete('${v.user_id}')" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm">
													<!--begin::Svg Icon | path: icons/duotune/general/gen027.svg-->
													<span class="svg-icon svg-icon-3">
														<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
															<path d="M5 9C5 8.44772 5.44772 8 6 8H18C18.5523 8 19 8.44772 19 9V18C19 19.6569 17.6569 21 16 21H8C6.34315 21 5 19.6569 5 18V9Z" fill="currentColor"></path>
															<path opacity="0.5" d="M5 5C5 4.44772 5.44772 4 6 4H18C18.5523 4 19 4.44772 19 5V5C19 5.55228 18.5523 6 18 6H6C5.44772 6 5 5.55228 5 5V5Z" fill="currentColor"></path>
															<path opacity="0.5" d="M9 4C9 3.44772 9.44772 3 10 3H14C14.5523 3 15 3.44772 15 4V4H9V4Z" fill="currentColor"></path>
														</svg>
													</span>
													<!--end::Svg Icon-->
												</a>
											</td>
                        `
                        let push_array = [v.user_nama,v.user_username,v.user_email,v.role['role_name'],html_action]
                        array.push(push_array);
                    });

                    table.rows.add(array).draw();    
                } 
            }
        })
        table.draw();
    }

    onAdd = () => {
        $('.table_data').hide()
        $('.form_data').show()
    };

    onBack = () => {
        $('.table_data').show()
        $('.form_data').hide()
        onClear();
    };

    onEdit = (id) => {
        onAdd()
        $.ajax({
            url: urlpath.show.replace(':id', id),
            type: 'GET',
            success: function(response){

                if(response.status == true){
                    $.each(response.data[0], function( key, value ){
                        if(key != 'password'){
                            $('[name='+key+']').val(value)
                        }
                    })
                    $('[name=password]').val('').attr("placeholder", "Isi Jika Ingin Mengganti Password");
                } 
            }
        })
    };

    onRole = () => {
        $.ajax({
            url: urlpath.role,
            type: 'GET',
            success: function(response){
                if(response.status == true){
                    $('#user_role').html('<option value="" selected disabled>Pilih Role</option>')

                    $.each(response.data, function( key, value ){
                        $('#user_role').append('<option value="'+value['role_id']+'">'+value['role_name']+'</option>')
                    })
                } 
            }
        })
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
                const formElement = $('#formData')[0];
                var content_type = {}
                if($('[name=user_id]').val() == ''){
                    var form = new FormData(formElement);
                    urlsave = urlpath.insert
                    urltype = 'POST'
                } else{
                    var form = $('#formData').serialize();
                    urlsave = urlpath.update.replace(':id', $('[name=user_id]').val())
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


    onDelete = (id) =>{
        swal({
            title: "Peringatan",
            text: "Apakah Anda Yakin Untuk Menghapus Data?",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((response) => {
            if (response) {
                $.ajax({
                    url: urlpath.delete.replace(':id', id),
                    type: 'DELETE',
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

    onRefresh = ()=>{
        onBack();
        onInitTable();
    }

    onClear = ()=>{
        $('#formData')[0].reset();
        window.location.reload()
    }
</script>