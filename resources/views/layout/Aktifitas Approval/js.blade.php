<script>
    const authEdit = "<?php echo $edit;?>"
    var urlpath ={
        update: "{{ route('aktifitas_api.update', ['aktifitas_api' => ':id']) }}",
        select: "{{ route('approval_aktifitas_api.show', ['approval_aktifitas_api' => ':id']) }}",
        show: "{{ route('aktifitas_api.show', ['aktifitas_api' => ':id']) }}",
    }

    $(function(){
        onInitTable();
    })

    onInitTable = (filter=0) =>{
        table.clear();
        $.ajax({
            url: urlpath.select.replace(':id', filter),
            type: 'GET',
            success: function(response){
                if(response.status == true){
                    var array = []
                    $.each( response.data, function( k, v ){
                        let approve = v.aktifitas_status
                        let html_action = `
                            <a href="javascript:onDetail('${v.aktifitas_id}','${v.aktifitas_status}')" class="btn btn-sm ${approve?'btn-info':'btn-primary'}">
                                    ${approve?'Detail':'Verifikasi'}
                                </a>
                        `
                        if(v.aktifitas_status == 2){
                            
                        }
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

    onRefresh = ()=>{
        onInitTable();
        onClear();
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
                var form = $('#formData').serialize();
                $.ajax({
                    url: urlpath.update.replace(':id', $('[name=aktifitas_id]').val()),
                    data: form,
                    contentType: false,
                    processData: false,
                    type: 'PATCH',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    } ,
                    success: function(response){
                        if(response.status == true){
                            swal("Success !", response.message, "success");
                            onRefresh()
                            $('#detail_modal').modal('hide')

                        } else{
                            swal("Warning", response.message, "warning");
                        }
                    }
                })
            }
        }); 
    }

    onDetail = (id,status) =>{
        $.ajax({
            url: urlpath.show.replace(':id', id),
            type: 'GET',
            success: function(response){
                onClear()
                if(response.status == true){
                    
                    data = response.data
                    $('[name=aktifitas_id]').val(id)
                    $('[name=aktifitas_nama]').val(data.aktifitas_nama)
                    $('[name=aktifitas_user_id]').val(data.aktifitas_user_id)
                    
                    if(data.aktifitas_dokumen.length == 0){
                        $('#detail_dokumen_list').html('')
                    }
                    var firstloop = true
                    $.each(data.aktifitas_dokumen, function( key, value ){
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
                                <td class="text-center">${status}</td>
                                <td class="text-center">${htmlpreview}</td>
                            </tr>
                        `)
                    })

                        $('[name=user_nama]').val(data.user_nama)
                        $('[name=role_name]').val(data.role_name)
                    if(data.aktifitas_status == 0){
                        $('.button_submit').show()
                        $('[name=aktifitas_komentar]').prop('disabled', false);
                        $('[name=aktifitas_status]').prop('disabled', false);
                    } else{
                        $('.button_submit').hide()
                        $('[name=aktifitas_komentar]').val(data.aktifitas_komentar)
                        $('[name=aktifitas_status]').val(data.aktifitas_status)
                        $('[name=aktifitas_komentar]').prop('disabled', true);
                        $('[name=aktifitas_status]').prop('disabled', true);

                    }
                    $('#detail_modal').modal('show')
                } 
            }
        })
    }

    onPreviewList = (file) =>{
        $('.preview_file').html(`<embed src="{{ asset('public/storage/file_aktifitas/${file}') }}" style="width:100%" height="500px">`)
        $('#detail_list_preview').modal('show')
    }
</script>