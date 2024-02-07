<script>
    const authEdit = "<?php echo $edit;?>"
    var urlpath ={
        insert: "{{ route('keuangan_api.store') }}",
        insertBulan: "{{ route('keuangan_bulan_api.store') }}",
        update: "{{ route('keuangan_api.update', ['keuangan_api' => ':id']) }}",
        delete: "{{ route('keuangan_api.destroy', ['keuangan_api' => ':id']) }}",
        select: "{{ route('keuangan_api.index') }}",
        show: "{{ route('keuangan_api.show', ['keuangan_api' => ':id']) }}",
        showBulan: "{{ route('keuangan_bulan_api.show', ['keuangan_bulan_api' => ':id']) }}",
    }

    $(function(){
        $('#keuangan_tahun_table').val(new Date().getFullYear())
        onInitTable();
        // onRole();
    })

    onInitTable = () =>{
        $.ajax({
            url: urlpath.select,
            type: 'GET',
            data: {
                year: $('#keuangan_tahun_table').val()
            },
            success: function(response){
                if(response.status == true){
                    $('#table_body').html('');
                    if(response.data.length){
                        $.each(response.data, function( key, value ){
                            let html_action = `
                                <a href="javascript:onEdit('${value.keuangan_id}')" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
                                    <!--begin::Svg Icon | path: icons/duotune/art/art005.svg-->
                                    <span class="svg-icon svg-icon-3">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path opacity="0.3" d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z" fill="currentColor"></path>
                                            <path d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z" fill="currentColor"></path>
                                        </svg>
                                    </span>
                                    <!--end::Svg Icon-->
                                </a>
                                <a href="javascript:onDelete('${value.keuangan_id}')" class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm">
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
                            `

                            html_action = authEdit?html_action:'';
                            $('#table_body').append(`<tr><td class="p-1" style="width:200px;border-right:none"><b>${value.keuangan_nama}</b></td><td class="text-center" style="width:100px;border-left:none">${html_action}</td><td colspan="12"></td></tr>`)

                            $.each(value.keuangan_detail, function( keyDetail, valueDetail ){
                                var bulan = ``;
                                if(value.keuangan_detail[keyDetail].keuangan_bulan.length){
                                    $.each(value.keuangan_detail[keyDetail].keuangan_bulan, function( keyBulan, valueBulan ){
                                        bulan += ` <td class="text-center">${formatToRupiah(valueBulan['keuangan_bulan_jumlah'])}</td>`
                                    })
                                } else{
                                    for(var i =1;i<=12;i++){
                                        bulan += ` <td class="text-center">0</td>`
                                    }
                                }

                                let buttonSet = `<a href="javascript:onAddModal('${valueDetail.keuangan_detail_id}')" class="btn btn-icon btn-bg-light btn-active-color-success btn-sm me-1">
                                        <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo8/dist/../src/media/svg/icons/Navigation/Right-2.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="10px" height="10px" viewBox="0 0 24 24" version="1.1">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <polygon points="0 0 24 0 24 24 0 24"/>
                                                <rect fill="#000000" opacity="0.3" transform="translate(8.500000, 12.000000) rotate(-90.000000) translate(-8.500000, -12.000000) " x="7.5" y="7.5" width="2" height="9" rx="1"/>
                                                <path d="M9.70710318,15.7071045 C9.31657888,16.0976288 8.68341391,16.0976288 8.29288961,15.7071045 C7.90236532,15.3165802 7.90236532,14.6834152 8.29288961,14.2928909 L14.2928896,8.29289093 C14.6714686,7.914312 15.281055,7.90106637 15.675721,8.26284357 L21.675721,13.7628436 C22.08284,14.136036 22.1103429,14.7686034 21.7371505,15.1757223 C21.3639581,15.5828413 20.7313908,15.6103443 20.3242718,15.2371519 L15.0300721,10.3841355 L9.70710318,15.7071045 Z" fill="#000000" opacity="0.3" fill-rule="nonzero" transform="translate(14.999999, 11.999997) scale(1, -1) rotate(90.000000) translate(-14.999999, -11.999997) "/>
                                            </g>
                                        </svg><!--end::Svg Icon--></span></a>`
                                buttonSet = authEdit?buttonSet:'';     
                                $('#table_body').append(`
                                <tr>
                                    <td class="" style="border-right:none;padding-left:20px">${valueDetail.keuangan_detail_nama}</td>
                                    <td class="text-center" style="border-left:none;width:100px">${buttonSet}</td>
                                    ${bulan}
                                </tr>`)
                            })
                        })
                    } else{
                        $('#table_body').html(`
                            <tr>
                                <td class="text-center" colspan="14">No Data Available</td>
                            </tr>
                        `);               
                    }
                    
                } 
            }
        })
    }

    function formatToRupiah(number) {
        let formattedAmount = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(number);
        return formattedAmount.replace(/,00$/, '');
    }

    onAddModal = (id) =>{
        $('#modalform').modal('show')
        $('#keuangan_detail_id').val(id)
        $('#keuangan_tahun').val($('#keuangan_tahun_table').val())
        onRegenerateMonth()
    }

    onRegenerateMonth = () =>{
        let id = $('#keuangan_detail_id').val()
        $.ajax({
            url: urlpath.showBulan.replace(':id', id),
            type: 'GET',
            data: {
                year: $('#keuangan_tahun').val()
            },
            success: function(response){
                if(response.status == true){
                    var data = response.data
                    $('.keuangan_bulan_list').val('')
                    // alert()
                    if(data.length){
                        $('input[name="keuangan_bulan_list[]"]').each(function(index) {
                            $(this).val(data[index]['keuangan_bulan_jumlah']);
                        });
                    }
                } 
            }
        })
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
                    $.each(response.data, function( key, value ){
                        if(key != 'keuangan_detail'){
                            $('[name='+key+']').val(value)
                        }
                    })

                    $.each(response.data.keuangan_detail, function( key, value ){
                        onAddList(value.keuangan_detail_nama,value.keuangan_detail_id)
                    })
                } 
            }
        })
    };

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
                if($('[name=keuangan_id]').val() == ''){
                    var form = new FormData(formElement);
                    urlsave = urlpath.insert
                    urltype = 'POST'
                } else{
                    var form = $('#formData').serialize();
                    urlsave = urlpath.update.replace(':id', $('[name=keuangan_id]').val())
                    urltype = 'PATCH'
                    var content_type = {
                        'Content-Type': 'application/x-www-form-urlencoded'
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

    onSaveBulan = () =>{
        swal({
            title: "Peringatan",
            text: "Apakah Anda Yakin Untuk Menyimpan Data?",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((response) => {
            if (response) {
                const formElement = $('#formBulan')[0];
                var form = new FormData(formElement);

                $.ajax({
                    url: urlpath.insertBulan,
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

    onAddList =(value="",key="")=>{
        $('#list_subdata').append(`
            <div class="row">
                <div class="col-11">
                    <input class="form-control  form-control-solid w-100 mb-1" type="hidden" name='keuangan_detail_id[]' value="${key}">
                    <input class="form-control  form-control-solid w-100 mb-1" type="text" name='keuangan_detail_nama[]' placeholder="Isikan Sub-Data" value="${value}" required>
                </div>
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
            </div> 
        `)
    }

    onDeleteList = (element) =>{
        event.preventDefault()
        $(element).closest(".row").remove();
    }
    
    onRefresh = ()=>{
        onBack();
        onInitTable();
    }

    onClear = ()=>{
        $('#formData')[0].reset();
        $('#modalform').modal('hide')
        $('#formBulan')[0].reset();
        $('[name=keuangan_id]').val("")
        $('#list_subdata').html('');
    }
</script>