<script>
    var temp_type=''
    var temp_view = ''
    var temp_edit = ''
    var urlpath ={
        insert: "{{ route('program_api.store') }}",
        update: "{{ route('program_api.update', ['program_api' => ':id']) }}",
        delete: "{{ route('program_api.destroy', ['program_api' => ':id']) }}",
        select: "{{ route('program_api.index') }}",
        show: "{{ route('program_api.show', ['program_api' => ':id']) }}",
        checkstatus: "{{ route('role_api.show', ['role_api' => ':id']) }}",
        print: "{{ route('program_api.pdf') }}",
        export: "{{ route('program_api.excel') }}",
        programList: "{{ route('program_api.programList') }}",
    }

    $(function(){
        onProgramList();
    })

    onProgramList = () =>{
        $.ajax({
            url: urlpath.programList,
            type: 'GET',
            success: function(response){
                if(response.status == true){
                    $('#program_list ul').html('')
                    var active="active";
                    $.each(response.data, function( k, v ){
                        viewauth = (v.view != undefined)?v.view:"";
                        editauth = (v.edit != undefined)?v.edit:"";

                        $('#program_list ul').append(`
                            <li class="nav-item w-10 me-0 mb-md-2">
                                <a class="nav-link w-100 ${active} btn btn-flex btn-active-primary" id="${v.menu_id}" data-bs-toggle="tab" href="javascript:void(0);" onclick="onInitTable('${v.menu_kode}',${viewauth},${editauth})">
                                    <span class="svg-icon fs-2"><svg>...</svg></span>
                                    <span class="d-flex flex-column align-items-start">
                                        <span class="fs-5 fw-bold text-left">${v.menu_nama}</span>
                                    </span>
                                </a>
                            </li>
                            <div class="separator my-1"></div>
                        `)

                        active="";
                    })
                    
                    setTimeout(function(){
                        $("#"+response.data[0].menu_id).click();
                    }, 500);
                }
            }})
    }

    onInitTable = (type="",viewauth="",editauth="") =>{
        $.ajax({
            url: urlpath.checkstatus.replace(':id', type),
            type: 'GET',
            success: function(response){
                if(response.status == true){
                    var edit = editauth;

                    if(edit){
                        $('.button_cetak').html(`
                        <a href="javascript:onChoosePrint()" class="btn btn-sm btn-info" style="margin-right: 10px;">
                            <span class="svg-icon svg-icon-2">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <rect opacity="0.5" x="11.364" y="20.364" width="16" height="2" rx="1"
                                        transform="rotate(-90 11.364 20.364)" fill="currentColor" />
                                    <rect x="4.36396" y="11.364" width="16" height="2" rx="1" fill="currentColor" />
                                </svg>
                            </span>
                            <!--end::Svg Icon-->Cetak Data</a>
                        `)

                        var tambah = `
                        <a href="javascript:onAdd()" class="btn btn-sm btn-primary">
                            <span class="svg-icon svg-icon-2">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <rect opacity="0.5" x="11.364" y="20.364" width="16" height="2" rx="1"
                                        transform="rotate(-90 11.364 20.364)" fill="currentColor" />
                                    <rect x="4.36396" y="11.364" width="16" height="2" rx="1" fill="currentColor" />
                                </svg>
                            </span>
                            <!--end::Svg Icon-->Tambah Data</a>
                        `
                    } else{
                        $('.button_cetak').html('')
                    }
                    table.clear();
                    $.ajax({
                        url: urlpath.select,
                        type: 'GET',
                        data:{
                            type:type
                        },
                        success: function(response){
                            if(response.status == true){
                                var array = []
                                num = 0;
                                $.each( response.data, function( k, v ){
                                    let html = `
                                        <a href="javascript:onDetail('${v.program_id}')" class="btn btn-sm btn-secondary">
                                            Detail
                                        </a>
                                    `
                                    
                                    if(edit){
                                        html += `
                                            <a href="javascript:onEdit('${v.program_id}')" class="btn btn-sm btn-success">
                                                Edit
                                            </a>
                                            <a href="javascript:onDelete('${v.program_id}')" class="btn btn-sm btn-danger">
                                                Delete
                                            </a>
                                        ` 
                                    }

                                    let html_action = `
                                        <td class="text-center">
                                            `+html+`
                                        </td>
                                    `
                                    
                                    
                                    let push_array = [k+1,v.program_nama,html_action]
                                    array.push(push_array);
                                    num = k+1;
                                });
                                if(edit){
                                    let button_add = `<a href="javascript:onAdd(true)" class="btn btn-sm btn-primary">
                                    <span class="svg-icon svg-icon-2">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <rect opacity="0.5" x="11.364" y="20.364" width="16" height="2" rx="1"
                                                transform="rotate(-90 11.364 20.364)" fill="currentColor" />
                                            <rect x="4.36396" y="11.364" width="16" height="2" rx="1" fill="currentColor" />
                                        </svg>
                                    </span>Tambah Data</a>`
                                    array.push([num+1,button_add,'']);
                                }
                                table.rows.add(array).draw();    
                            } 
                        }
                    })
                    table.draw();    
                    temp_type = type
                    temp_view = viewauth
                    temp_edit = editauth
                } 
            }
        })

    }

    onAdd = (add=false) => {
        $('.table_data').hide()
        $('.form_data').show()
        
        if(add){
            $('#program_type').val(temp_type)
            $('.pelaksanaan_evaluasi').hide()
        } else{
            $('.pelaksanaan_evaluasi').show()
        }
    };

    onBack = () => {
        $('.table_data').show()
        $('.form_data').hide()
        onClear();
    };

    onRefresh = ()=>{
        onBack();
        onInitTable(temp_type,temp_view,temp_edit);
    }

    onClear = ()=>{
        $('#formData')[0].reset();
        $('[name=program_id]').val("")
        $('#list_program_kegiatan').empty()
        $('#list_program_pelaksanaan').empty()
        $('#list_program_evaluasi').empty()
    }

    onAddList =(type, value="")=>{
        $('#list_program_'+type).append(`
            <div class="row">
                <div class="col-1 p-1">
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
                </div>
                <div class="col-11">
                    <input class="form-control  form-control-solid w-100 mb-1" type="text" name='program_${type}[]' placeholder="Isikan ${type}" value="${value}">
                </div>
            </div> 
        `)
    }

    onDeleteList = (element) =>{
        event.preventDefault()
        $(element).closest(".row").remove();
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
                if($('[name=program_id]').val() == ''){
                    var form = new FormData(formElement);
                    urlsave = urlpath.insert
                    urltype = 'POST'
                } else{
                    var form = $('#formData').serialize();
                    urlsave = urlpath.update.replace(':id', $('[name=program_id]').val())
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
            url: urlpath.show.replace(':id', id),
            type: 'GET',
            success: function(response){
                if(response.status == true){
                    $.each(response.data, function( key, value ){
                        $('[name='+key+']').val(value)
                    })

                    $.each(response.data.program_kegiatan, function( key, value ){
                        onAddList('kegiatan',value.program_kegiatan_nama)
                    })

                    $.each(response.data.program_pelaksanaan, function( key, value ){
                        onAddList('pelaksanaan',value.program_pelaksanaan_nama)
                    })

                    $.each(response.data.program_evaluasi, function( key, value ){
                        onAddList('evaluasi',value.program_evaluasi_nama)
                    })

                } 
            }
        })
    };

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
        $.ajax({
            url: urlpath.show.replace(':id', id),
            type: 'GET',
            success: function(response){
                if(response.status == true){
                    data = response.data
                    $('#list_tab_pelaksanaan').html('')
                    $('#list_tab_evaluasi').html('')

                    $('#modal_title').html(data.program_nama)

                    $.each(response.data.program_pelaksanaan, function( key, value ){
                        $('#list_tab_pelaksanaan').append('<li>'+value.program_pelaksanaan_nama+'</li>')
                    })

                    $.each(response.data.program_evaluasi, function( key, value ){
                        $('#list_tab_evaluasi').append('<li>'+value.program_evaluasi_nama+'</li>')
                    })


                    $('#detail_modal').modal('show')
                } 
            }
        })
    }

    onChoosePrint = () =>{
        $('#list_print_program').html('')
        $.ajax({
            url: urlpath.select,
            type: 'GET',
            data:{
                type: temp_type
            },
            success: function(response){
                if(response.status == true){
                    $.each( response.data, function( k, v ){
                        $('#list_print_program').append(
                            '<tr>'+
                                '<td style="width:20px;padding:0;text-align:center;vertical-align: middle;"><input class="list_check" type="checkbox" name="list_program_id[]" value="'+v.program_id+'"/></td>'+
                                '<td style="padding:9px">'+v.program_nama+'</td>'+
                            '</tr>'
                        )
                    }); 
                } 
                $('#print_modal').modal('show')
            }
        })
    }

    onPrint = () =>{
        var selectedValues = [];
        $("input[name='list_program_id[]']:checked").each(function() {
            selectedValues.push($(this).val());
        });

        $.ajax({
            url: urlpath.print,
            type: 'GET',
            data:{
                list: selectedValues
            },
            success: function(response){
                var $embedElement = $('<embed>', {
                    src: 'data:application/pdf;base64,' + response,
                    type: 'application/pdf',
                    width: '100%',
                    height: '500px'
                });

                // Append the <embed> element to a container in your HTML
                $('#pdf-container').empty().append($embedElement);
                $('#print_modal').modal('hide')
                $('#preview_pdf').modal('show')
            }
        })
    }

    function exportExcel(){
        var selectedValues = [];
        $("input[name='list_program_id[]']:checked").each(function() {
            selectedValues.push($(this).val());
        });

        var form_data = {
            list: selectedValues,
        };

        // // Open a new window or tab
        var win = window.open(urlpath.export);

        // // Construct a form to submit the data
        var form = $('<form method="post" action="' + urlpath.export + '"></form>');
        for (var key in form_data) {
            $('<input>').attr({
                type: 'hidden',
                name: key,
                value: form_data[key]
            }).appendTo(form);
        }
        form.appendTo(win.document.body);

        // // Submit the form to download the file
        form.submit();
    }

    onTriggerChange = () =>{
        var check = $("#select_all").is(":checked");

        if(check){
            $(".list_check").prop("checked", true);
        } else{
            $(".list_check").prop("checked", false);
        }
    }
</script>