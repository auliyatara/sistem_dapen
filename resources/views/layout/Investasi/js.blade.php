<script>
    const authEdit = "<?php echo $edit;?>"
    var urlpath ={
        select: "{{ route('investasi_api.index') }}",
        update: "{{ route('investasi_api.update', ['investasi_api' => ':id']) }}",
        insert: "{{ route('investasi_api.store') }}",
        show: "{{ route('investasi_api.show', ['investasi_api' => ':id']) }}",
        delete: "{{ route('investasi_api.destroy', ['investasi_api' => ':id']) }}",
        showSubdata: "{{ route('investasi_subdata_api.show', ['investasi_subdata_api' => ':id']) }}",
        updateSubdata: "{{ route('investasi_subdata_api.update', ['investasi_subdata_api' => ':id']) }}",
    }

    $(function(){
        $('#investasi_tahun_table').val(new Date().getFullYear())
        onInitTable();
    })

    onInitTable = () =>{
        $.ajax({
            url: urlpath.select,
            type: 'GET',
            data: {
                year: $('#investasi_tahun_table').val()
            },
            success: function(response){
                if(response.status == true){
                    var array = []
                    $('#table_body').html('')
                    if(response.data.length){
                        $.each( response.data, function( k, v ){
                            let buttonEdit = (authEdit? `<a href="javascript:onEdit('${v.investasi_id}')" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
                                                        <!--begin::Svg Icon | path: icons/duotune/art/art005.svg-->
                                                        <span class="svg-icon svg-icon-3">
                                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path opacity="0.3" d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z" fill="currentColor"></path>
                                                                <path d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z" fill="currentColor"></path>
                                                            </svg>
                                                        </span>
                                                        <!--end::Svg Icon-->
                                                    </a>
                                                    <a href="javascript:onDelete('${v.investasi_id}')" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm">
                                                        <!--begin::Svg Icon | path: icons/duotune/general/gen027.svg-->
                                                        <span class="svg-icon svg-icon-3">
                                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M5 9C5 8.44772 5.44772 8 6 8H18C18.5523 8 19 8.44772 19 9V18C19 19.6569 17.6569 21 16 21H8C6.34315 21 5 19.6569 5 18V9Z" fill="currentColor"></path>
                                                                <path opacity="0.5" d="M5 5C5 4.44772 5.44772 4 6 4H18C18.5523 4 19 4.44772 19 5V5C19 5.55228 18.5523 6 18 6H6C5.44772 6 5 5.55228 5 5V5Z" fill="currentColor"></path>
                                                                <path opacity="0.5" d="M9 4C9 3.44772 9.44772 3 10 3H14C14.5523 3 15 3.44772 15 4V4H9V4Z" fill="currentColor"></path>
                                                            </svg>
                                                        </span>
                                                        <!--end::Svg Icon-->
                                                    </a>`:``)

                            let html_action = `
                                <td class="text-center">
                                    
                                                    <a href="javascript:onDetail('${v.investasi_id}')" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
                                                        <!--begin::Svg Icon | path: icons/duotune/art/art005.svg-->
                                                        <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" 
                                                        width="24" height="24" viewBox="0 0 480.606 480.606" xml:space="preserve">
                                                            <g>
                                                                <rect x="85.285" y="192.5" width="200" height="30"/>
                                                                <path opacity="0.3" d="M439.108,480.606l21.213-21.213l-71.349-71.349c12.528-16.886,19.949-37.777,19.949-60.371
                                                                    c0-40.664-24.032-75.814-58.637-92.012V108.787L241.499,0H20.285v445h330v-25.313c6.188-2.897,12.04-6.396,17.475-10.429
                                                                    L439.108,480.606z M250.285,51.213L299.072,100h-48.787V51.213z M50.285,30h170v100h100v96.957
                                                                    c-4.224-0.538-8.529-0.815-12.896-0.815c-31.197,0-59.148,14.147-77.788,36.358H85.285v30h126.856
                                                                    c-4.062,10.965-6.285,22.814-6.285,35.174c0,1.618,0.042,3.226,0.117,4.826H85.285v30H212.01
                                                                    c8.095,22.101,23.669,40.624,43.636,52.5H50.285V30z M307.389,399.208c-39.443,0-71.533-32.09-71.533-71.533
                                                                    s32.089-71.533,71.533-71.533s71.533,32.089,71.533,71.533S346.832,399.208,307.389,399.208z"/>
                                                            </g>
                                                        </svg>
                                                        <!--end::Svg Icon-->
                                                    </a>
                                                    ${buttonEdit}
                                                </td>
                            `
                            var bulan = ``;
                            if(v.investasi_nilai_wajar.length){
                                $.each(v.investasi_nilai_wajar, function( keyBulan, valueBulan ){
                                    bulan += ` <td class="text-center">${formatToRupiah(valueBulan['investasi_bulan_nilai_wajar'])}</td>`
                                })
                            } else{
                                for(var i =1;i<=12;i++){
                                    bulan += ` <td class="text-center">0</td>`
                                }
                            }

                            $('#table_body').append(`<tr><td class="p-1" style="width:200px;border-right:none"><b>${v.investasi_jenis}</b></td>${html_action}${bulan}</tr>`)

                            $.each( v.investasi_subdata, function( ksubdata, vsubdata ){
                                var bulan_subdata = ``;
                                if(vsubdata.investasi_subdata_nilai_wajar.length){
                                    $.each(vsubdata.investasi_subdata_nilai_wajar, function( keyBulan, valueBulan ){
                                        bulan_subdata += ` <td class="text-center">${formatToRupiah(valueBulan['investasi_subdata_bulan_nilai_wajar'])}</td>`
                                    })
                                } else{
                                    for(var i =1;i<=12;i++){
                                        bulan_subdata += ` <td class="text-center">0</td>`
                                    }
                                }

                                let buttonEdit = (authEdit? `<a href="javascript:onEditSubdata('${vsubdata.investasi_subdata_id}')" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
                                        <!--begin::Svg Icon | path: icons/duotune/art/art005.svg-->
                                        <span class="svg-icon svg-icon-3">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path opacity="0.3" d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z" fill="currentColor"></path>
                                                <path d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z" fill="currentColor"></path>
                                            </svg>
                                        </span>
                                        <!--end::Svg Icon-->
                                    </a>`:``)
                                                    
                                let html_action = `
                                <td class="text-center">
                                    
                                                    <a href="javascript:onDetailSubdata('${vsubdata.investasi_subdata_id}')" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
                                                        <!--begin::Svg Icon | path: icons/duotune/art/art005.svg-->
                                                        <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" 
                                                        width="24" height="24" viewBox="0 0 480.606 480.606" xml:space="preserve">
                                                            <g>
                                                                <rect x="85.285" y="192.5" width="200" height="30"/>
                                                                <path opacity="0.3" d="M439.108,480.606l21.213-21.213l-71.349-71.349c12.528-16.886,19.949-37.777,19.949-60.371
                                                                    c0-40.664-24.032-75.814-58.637-92.012V108.787L241.499,0H20.285v445h330v-25.313c6.188-2.897,12.04-6.396,17.475-10.429
                                                                    L439.108,480.606z M250.285,51.213L299.072,100h-48.787V51.213z M50.285,30h170v100h100v96.957
                                                                    c-4.224-0.538-8.529-0.815-12.896-0.815c-31.197,0-59.148,14.147-77.788,36.358H85.285v30h126.856
                                                                    c-4.062,10.965-6.285,22.814-6.285,35.174c0,1.618,0.042,3.226,0.117,4.826H85.285v30H212.01
                                                                    c8.095,22.101,23.669,40.624,43.636,52.5H50.285V30z M307.389,399.208c-39.443,0-71.533-32.09-71.533-71.533
                                                                    s32.089-71.533,71.533-71.533s71.533,32.089,71.533,71.533S346.832,399.208,307.389,399.208z"/>
                                                            </g>
                                                        </svg>
                                                        <!--end::Svg Icon-->
                                                    </a>
                                                    ${buttonEdit}
                                                </td>
                            `

                                $('#table_body').append(`<tr><td class="" style="width:200px;border-right:none;padding-left:20px;"> ${vsubdata.investasi_subdata_nama}</td>${html_action}${bulan_subdata}</tr>`)
                            })
                        });
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
    
    onAdd = () => {
        $('.form_data').show()
        $('.table_data').hide()
        $('[name=investasi_id]').val('')
        onClear();
    };

    onBack = () => {
        $('.table_data').show()
        $('.form_data').hide()
        onClear();
    };

    onRefresh = ()=>{
        onInitTable();
        onBack()
    }

    onClear = ()=>{
        $('#formData')[0].reset();
        $('#list_investasi').html('');
        $('#list_subdata').html('');
	// window.location.reload()
    }

    onAddListSubdata =(value="",key="")=>{
        $('#list_subdata').append(`
            <div class="row">
                <div class="col-11">
                    <input class="form-control  form-control-solid w-100 mb-1" type="hidden" name='investasi_subdata_id[]' value="${key}">
                    <input class="form-control  form-control-solid w-100 mb-1" type="text" name='investasi_subdata_nama[]' placeholder="Isikan Subdata Investasi" value="${value}" required>
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


    onAddList =(id="",nama="",file="")=>{
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

        $('#list_investasi').append(`
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

    onPreviewList = (file) =>{
        $('.preview_file').html(`<embed src="{{ asset('public/storage/investasi_dokumen/${file}') }}" style="width:100%" height="500px">`)
        $('#detail_list_preview').modal('show')
    }

    onDetail =(id)=>{
        $.ajax({
            url: urlpath.show.replace(':id', id),
            type: 'GET',
            success: function(response){
                if(response.status == true){
                    $.each(response.data, function( key, value ){
                        if(key != 'investasi_dokumen' && key != 'investasi_nilai_perolehan'){
                            $('#detail_'+key).html(value)
                        } else if (key == 'investasi_nilai_perolehan'){
                            $('#detail_'+key).html(formatToRupiah(value))
                        }
                    })

                    var firstloop = true
                    $.each(response.data.investasi_dokumen, function( key, value ){
                        if(firstloop){
                            $('#detail_dokumen_list').html('')
                            firstloop = false
                        }

                        if(value.investasi_dokumen_file != ''){
                            htmlpreview = `<a href="#" onclick="onPreviewList('${value.investasi_dokumen_file}')"class="btn btn-icon btn-info btn-active-color-light btn-sm">
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

                        onAddList(value.investasi_dokumen_id, value.investasi_dokumen_judul, value.investasi_dokumen_file)
                        $('#detail_dokumen_list').append(`
                            <tr>
                                <td class="p-2">${value.investasi_dokumen_judul}</td>
                                <td class="text-center">${htmlpreview}</td>
                            </tr>
                        `)
                    })
                    $('#detail_modal').modal('show')

                } 
            }
        })
        
    }
    
    onEdit = (id) => {
        onAdd()
        $.ajax({
            url: urlpath.show.replace(':id', id),
            type: 'GET',
            data: {
                year: $('#investasi_tahun_table').val()
            },
            success: function(response){
                if(response.status == true){
                    $.each(response.data, function( key, value ){
                        if(key != 'investasi_dokumen'){
                            $('[name='+key+']').val(value)
                        }
                    })

                    $.each(response.data.investasi_dokumen, function( key, value ){
                        onAddList(value.investasi_dokumen_id, value.investasi_dokumen_judul, value.investasi_dokumen_file)
                    })
                    
                    $('#investasi_tahun').val($('#investasi_tahun_table').val())

                    var listbulan = response.data.investasi_nilai_wajar
                    $('.investasi_bulan_list').val('')
                    if(listbulan.length){
                        $('input[name="investasi_bulan_list[]"]').each(function(index) {
                            $(this).val(listbulan[index]['investasi_bulan_nilai_wajar']);
                        });
                    }

                    $.each(response.data.investasi_subdata, function( key, value ){
                        onAddListSubdata(value.investasi_subdata_nama,value.investasi_subdata_id)
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
                var form = new FormData(formElement);
                var urlsave = ($('[name=investasi_id]').val() == '')? urlpath.insert:urlpath.update.replace(':id', $('[name=investasi_id]').val()) ;

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

    onRegenerateMonth = () =>{
        let id = $('[name=investasi_id]').val()
        $.ajax({
            url: urlpath.show.replace(':id', id),
            type: 'GET',
            data: {
                year: $('#investasi_tahun').val()
            },
            success: function(response){
                if(response.status == true){
                    var listbulan = response.data.investasi_nilai_wajar
                    $('.investasi_bulan_list').val('')

                    if(listbulan.length){
                        $('input[name="investasi_bulan_list[]"]').each(function(index) {
                            $(this).val(listbulan[index]['investasi_bulan_nilai_wajar']);
                        });
                    }
                } 
            }
        })
    }

    onEditSubdata = (id) =>{
        let year = $('#investasi_tahun_table').val()
        $('#investasi_subdata_tahun').val(year)
        $.ajax({
            url: urlpath.showSubdata.replace(':id', id),
            type: 'GET',
            data: {
                year: year
            },
            success: function(response){
                if(response.status == true){
                    $.each(response.data, function( key, value ){
                        if(key != 'investasi_subdata_nilai_wajar'){
                            $('[name='+key+']').val(value)
                        }
                    })

                    var listbulan = response.data.investasi_subdata_nilai_wajar
                    $('.investasi_subdata_bulan_list').val('')

                    if(listbulan.length){
                        $('input[name="investasi_subdata_bulan_list[]"]').each(function(index) {
                            $(this).val(listbulan[index]['investasi_subdata_bulan_nilai_wajar']);
                        });
                    }
                } 
            }
        })
        $('#subdata_update_modal').modal('show')
    }

    onSaveSubdata = () =>{
        swal({
            title: "Peringatan",
            text: "Apakah Anda Yakin Untuk Menyimpan Data?",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((response) => {
            if (response) {
                const formElement = $('#formDataSubdata')[0];
                var form = new FormData(formElement);

                $.ajax({
                    url: urlpath.updateSubdata.replace(':id', $('[name=investasi_subdata_id]').val()),
                    type: 'POST',
                    data: form,
                    contentType: false,
                    processData: false,
                    success: function(response){
                        if(response.status == true){
                            swal("Success !", response.message, "success");
                            $('#subdata_update_modal').modal('hide')
                            onRefresh()
                        } else{
                            swal("Warning", response.message, "warning");
                        }
                    }
                })
            }
        }); 
    }

    onDetailSubdata =(id)=>{
        $.ajax({
            url: urlpath.showSubdata.replace(':id', id),
            type: 'GET',
            success: function(response){
                if(response.status == true){
                    $.each(response.data, function( key, value ){
                        if(key != 'investasi_subdata_nilai_wajar'){
                            $('#detail_'+key).html(value)
                        }
                    })
                    $('#detail_modal_subdata').modal('show')
                } 
            }
        })
        
    }

    onRegenerateMonthSub = () =>{
        let id = $('[name=investasi_subdata_id]').val()
        $.ajax({
            url: urlpath.showSubdata.replace(':id', id),
            type: 'GET',
            data: {
                year: $('#investasi_subdata_tahun').val()
            },
            success: function(response){
                if(response.status == true){
                    var listbulan = response.data.investasi_subdata_nilai_wajar
                    $('.investasi_subdata_bulan_list').val('')

                    if(listbulan.length){
                        $('input[name="investasi_subdata_bulan_list[]"]').each(function(index) {
                            $(this).val(listbulan[index]['investasi_subdata_bulan_nilai_wajar']);
                        });
                    }
                } 
            }
        })
    }
</script>