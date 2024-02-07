<script>
    const authEdit = "<?php echo $edit;?>"

    var urlpath ={
        insert: "{{ route('aktifitas_api.store') }}",
        updatealternate: "{{ route('aktifitas_api.updatealternate') }}",
        update: "{{ route('aktifitas_api.update', ['aktifitas_api' => ':id']) }}",
        delete: "{{ route('aktifitas_api.destroy', ['aktifitas_api' => ':id']) }}",
        select: "{{ route('aktifitas_api.index') }}",
        show: "{{ route('aktifitas_api.show', ['aktifitas_api' => ':id']) }}",
    }

    $(function(){
        onInitTable();
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
                                <a href="javascript:onDetail('${v.aktifitas_id}')" class="btn btn-sm btn-secondary">
                                    <span class="svg-icon svg-icon-3">
									</span>Detail
                                </a>
                                <a href="javascript:onDelete('${v.aktifitas_id}')" class="btn btn-sm btn-danger">
                                    <span class="svg-icon svg-icon-3">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path d="M5 9C5 8.44772 5.44772 8 6 8H18C18.5523 8 19 8.44772 19 9V18C19 19.6569 17.6569 21 16 21H8C6.34315 21 5 19.6569 5 18V9Z" fill="currentColor"></path>
											<path opacity="0.5" d="M5 5C5 4.44772 5.44772 4 6 4H18C18.5523 4 19 4.44772 19 5V5C19 5.55228 18.5523 6 18 6H6C5.44772 6 5 5.55228 5 5V5Z" fill="currentColor"></path>
											<path opacity="0.5" d="M9 4C9 3.44772 9.44772 3 10 3H14C14.5523 3 15 3.44772 15 4V4H9V4Z" fill="currentColor"></path>
										</svg>
									</span>Delete
                                </a>
							</td>
                        `

                        let status =''
                        if(v.aktifitas_status == 2){
                            status = '<span class="badge badge-danger">Ditolak</span>'    
                        } else if(v.aktifitas_status == 1){
                            status = '<span class="badge badge-success">Disetujui</span>'    
                        } else if(v.aktifitas_status == 3){
                            status = '<span class="badge badge-warning">Direvisi</span>'    
                        } else{
                            status = '<span class="badge badge-primary">Diproses</span>'    
                        }

                        if(authEdit){
                            var push_array = [k+1,v.aktifitas_nama,status,html_action]
                        } else{
                            var push_array = [k+1,v.aktifitas_nama,status]
                        }
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

    onRefresh = ()=>{
        onInitTable();
        onBack()
        window.location.reload()
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
                const formElement = $('#formData')[0];
                var form = new FormData(formElement);
                var urlsave = ($('[name=aktifitas_id]').val() == '')? urlpath.insert:urlpath.update.replace(':id', $('[name=aktifitas_id]').val()) ;

                $.ajax({
                    url: urlsave,
                    type: 'POST',
                    data: form,
                    contentType: false,
                    processData: false,
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

    onUpdate = () =>{
        swal({
            title: "Peringatan",
            text: "Apakah Anda Yakin Untuk Menyimpan Data?",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((response) => {
            if (response) {
                var form = new FormData($('#formDataSecondary')[0]);

                $.ajax({
                    url: urlpath.updatealternate,
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

    onDetail = (id) =>{
        $('.revisi_button').html('')
        $.ajax({
            url: urlpath.show.replace(':id', id),
            type: 'GET',
            success: function(response){
                if(response.status == true){
                    data = response.data
                    $('.preview_file').html(`<embed src="{{ asset('public/storage/file_aktifitas/${data.aktifitas_file}') }}" style="width:100%" height="500px">`)

                    $('.revisi_button').html(`
                        <textarea name="aktifitas_komentar" id="aktifitas_komentar" cols="10" rows="5" class="form-control form-control-solid" placeholder="Komentar Verifikasi" disabled>${data.aktifitas_komentar == null? '':data.aktifitas_komentar}</textarea>
                    `)

                    if(data.aktifitas_status == 3){
                        let encode = btoa(JSON.stringify(data))
                        $('.revisi_button').append(`
                        <button type="button" class="btn btn-lg btn-primary mt-1" style="width: 100%" onclick="onChangeModal('${encode}')">Revisi Data
                            <span class="svg-icon svg-icon-3 ms-1 me-0" >
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

                    var firstloop = true
                    $.each(response.data.aktifitas_dokumen, function( key, value ){
                        if(firstloop){
                            $('#detail_dokumen_list').html('')
                            firstloop = false
                        }

                        if(value.investasi_dokumen_file != ''){
                            htmlpreview = `<a href="#" onclick="onPreviewList('${value.aktifitas_dokumen_file}')"class="btn btn-icon btn-info btn-active-color-light btn-sm">
                                        <!--begin::Svg Icon | path: icons/duotune/general/gen027.svg-->
                                        <svg fill="#ffffff" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" 
                                        width="24" height="24" viewBox="0 0 480.606 480.606" xml:space="preserve">
                                            <g>
                                                <rect x="85.285" y="192.5" width="200" height="30"/>
                                                <path d="M439.108,480.606l21.213-21.213l-71.349-71.349c12.528-16.886,19.949-37.777,19.949-60.371
                                                    c0-40.664-24.032-75.814-58.637-92.012V108.787L241.499,0H20.285v445h330v-25.313c6.188-2.897,12.04-6.396,17.475-10.429
                                                    L439.108,480.606z M250.285,51.213L299.072,100h-48.787V51.213z M50.285,30h170v100h100v96.957
                                                    c-4.224-0.538-8.529-0.815-12.896-0.815c-31.197,0-59.148,14.147-77.788,36.358H85.285v30h126.856
                                                    c-4.062,10.965-6.285,22.814-6.285,35.174c0,1.618,0.042,3.226,0.117,4.826H85.285v30H212.01
                                                    c8.095,22.101,23.669,40.624,43.636,52.5H50.285V30z M307.389,399.208c-39.443,0-71.533-32.09-71.533-71.533
                                                    s32.089-71.533,71.533-71.533s71.533,32.089,71.533,71.533S346.832,399.208,307.389,399.208z"/>
                                            </g>
                                        </svg>
                                        <!--end::Svg Icon-->
                                    </a>`
                        }

                        let status =''
                        if(value.aktifitas_dokumen_status == 2){
                            status = '<span class="badge badge-danger">Ditolak</span>'    
                        } else if(value.aktifitas_dokumen_status == 1){
                            status = '<span class="badge badge-success">Disetujui</span>'    
                        } else if(value.aktifitas_dokumen_status == 3){
                            status = '<span class="badge badge-warning">Direvisi</span>'    
                        } else{
                            status = '<span class="badge badge-primary">Diproses</span>'    
                        }

                        $('#detail_dokumen_list').append(`
                            <tr>
                                <td class="text-center">${key+1}</td>
                                <td class="p-2">${value.aktifitas_dokumen_judul}</td>
                                <td class="text-center">${htmlpreview}</td>
                            </tr>
                        `)
                    })
                    $('#detail_modal').modal('show')
                } 
            }
        })
    }
    
    onPreviewList = (file) =>{
        $('.preview_file').html(`<embed src="{{ asset('public/storage/file_aktifitas/${file}') }}" style="width:100%" height="500px">`)
        $('#detail_list_preview').modal('show')
    }

    onChangeModal =(data)=>{
        $('#detail_modal').modal('hide')
        $('#detail_modal_revisi').modal('show')

        var data = JSON.parse(atob(data))
        $('[name=revisi_aktifitas_id]').val(data.aktifitas_id)
        $('[name=revisi_aktifitas_nama]').val(data.aktifitas_nama)
        $.each(data.aktifitas_dokumen, function( key, value ){
            onAddList(value.aktifitas_dokumen_id, value.aktifitas_dokumen_judul, value.aktifitas_dokumen_file,true)
        })
    }

    onChoosePrint = () =>{
        $('#list_print_program').html('')
        $.ajax({
            url: urlpath.select,
            type: 'GET',
            success: function(response){
                if(response.status == true){
                    $.each( response.data, function( k, v ){
                        $('#list_print_program').append(
                            '<tr>'+
                                '<td style="width:20px;padding:0;text-align:center;vertical-align: middle;"><input type="checkbox"/></td>'+
                                '<td style="padding:9px">'+v.program_nama+'</td>'+
                            '</tr>'
                        )
                    }); 
                } 
                $('#print_modal').modal('show')
            }
        })
    }

    onAddList =(id="",nama="",file="",revisi="")=>{
        var htmlpreview = ``
        if(file != ''){
            htmlpreview = `<a href="#" onclick="onPreviewList('${file}')"class="btn btn-icon btn-info btn-active-color-light btn-sm">
						<!--begin::Svg Icon | path: icons/duotune/general/gen027.svg-->
                        <svg fill="#ffffff" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" 
                        width="24" height="24" viewBox="0 0 480.606 480.606" xml:space="preserve">
                            <g>
                                <rect x="85.285" y="192.5" width="200" height="30"/>
                                <path d="M439.108,480.606l21.213-21.213l-71.349-71.349c12.528-16.886,19.949-37.777,19.949-60.371
                                    c0-40.664-24.032-75.814-58.637-92.012V108.787L241.499,0H20.285v445h330v-25.313c6.188-2.897,12.04-6.396,17.475-10.429
                                    L439.108,480.606z M250.285,51.213L299.072,100h-48.787V51.213z M50.285,30h170v100h100v96.957
                                    c-4.224-0.538-8.529-0.815-12.896-0.815c-31.197,0-59.148,14.147-77.788,36.358H85.285v30h126.856
                                    c-4.062,10.965-6.285,22.814-6.285,35.174c0,1.618,0.042,3.226,0.117,4.826H85.285v30H212.01
                                    c8.095,22.101,23.669,40.624,43.636,52.5H50.285V30z M307.389,399.208c-39.443,0-71.533-32.09-71.533-71.533
                                    s32.089-71.533,71.533-71.533s71.533,32.089,71.533,71.533S346.832,399.208,307.389,399.208z"/>
                            </g>
                        </svg>
						<!--end::Svg Icon-->
					</a>`
        }

        var revisi = revisi?"_revisi":"";
        $('#list_dokumen_aktifitas'+revisi).append(`
            <div class="row">
                <div class="col-5">
                    <input type="hidden" class="form-control" name="list_dokumen_id[]" placeholder="" value="${id}"/>
                    <input class="form-control  form-control-solid w-100 mb-1" type="text" name='list_dokumen_nama[]' placeholder="Isikan Nama Dokumen" value="${nama}" required>
                </div>
                <div class="col-5">
                    <input type="file" class="form-control" name="list_file_dokumen[]" placeholder="" value=""/>
                </div>
                <div class="col-2">
                    <a href="#" onclick="onDeleteList(this)"class="btn btn-icon btn-danger btn-active-color-light btn-sm">
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
                    ${htmlpreview}
                </div>
            </div> 
        `)
    }

    
    onDeleteList = (element) =>{
        event.preventDefault()
        $(element).closest(".row").remove();
    }

</script>