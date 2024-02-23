<script>
    const authEdit = "<?php echo $edit;?>"

    var urlpath ={
        insert: "{{ route('kepesertaan_api.store') }}",
        update: "{{ route('kepesertaan_api.update', ['kepesertaan_api' => ':id']) }}",
        delete: "{{ route('kepesertaan_api.destroy', ['kepesertaan_api' => ':id']) }}",
        select: "{{ route('kepesertaan_api.index') }}",
        count: "{{ route('kepesertaan_api_count.count') }}",
        show: "{{ route('kepesertaan_api.show', ['kepesertaan_api' => ':id']) }}",
    }

    $(function(){
        onInitTable();
        count();
    })

    onInitTable = () =>{
        table.clear();
        $.ajax({
            url: urlpath.select,
            type: 'GET',
            success: function(response){
                if(response.status == true){
                    var array = onLoadRow(response.data)
                    table.rows.add(array).draw();      
                } 
            }
        })
        table.draw();
    }

    onInitFilter = (tipe="",kategori="",filter="") =>{
        if(tipe == "" && kategori == "" && filter == ""){
            var filter = "tampilan"
            var tipe = $("#tipe_filter").val()
        } 

        if(tipe != ""){
            table.clear();
            $.ajax({
                url: urlpath.show.replace(':id', tipe+"_"+kategori+"_"+filter),
                type: 'GET',
                success: function(response){
                    if(response.status == true){
                        var array = onLoadRow(response.data)
                        table.rows.add(array).draw();    
                    } 
                }
            })
            table.draw();
        }        
    }

    onLoadRow = (data) =>{
        var array = []
        $.each( data, function( k, v ){
            if(k == 'iuran_tahun_distinct' || k == 'year_before_iuran_total'  || k == 'iuran_pengembangan'){
            } else{
                let buttonEdit = (authEdit?` <a href="javascript:onEdit('${v.kepesertaan_id}')" class="btn btn-sm btn-success">
                                    Edit
                                </a>
                    <a href="javascript:onDelete('${v.kepesertaan_id}')" class="btn btn-sm btn-danger">
                        <span class="svg-icon svg-icon-3">
                        </span>Delete
                    </a>`:``)
                let html_action = `
                <td class="text-center">
                        <a href="javascript:onDetail('${v.kepesertaan_id}')" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
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
                        <a href="javascript:onEdit('${v.kepesertaan_id}')" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
                        <!--begin::Svg Icon | path: icons/duotune/art/art005.svg-->
                        <span class="svg-icon svg-icon-3">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path opacity="0.3" d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z" fill="currentColor"></path>
                        <path d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z" fill="currentColor"></path>
                        </svg>
                        </span>
                        <!--end::Svg Icon-->
                        </a>
                        <a href="javascript:onDelete('${v.kepesertaan_id}')" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm">
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
                    </td>
                `
                let ttl = v.kepesertaan_tempat_lahir+", "+ v.kepesertaan_tanggal_lahir
                let push_array = [parseInt(k)+1,v.kepesertaan_nama,v.kepesertaan_nip,ttl,v.kepesertaan_terhitung_mulai_tanggal,v.kepesertaan_status_jabatan,html_action]
                array.push(push_array);
            }
            
        });

        return array
    }

    count = () =>{
        $.ajax({
            url: urlpath.count,
            type: 'GET',
            success: function(response){
                if(response.status == true){
                    $.each( response.data, function( k, v ){
                        $('#count_'+v.initial).html(v.total)
                    })
                }
            }
        })
    }

    onAdd = () => {
        $('#kepesertaan_foto_show').hide()
        $('.table_data').hide()
        $('.form_data').show()
        $('#profile_li').hide()
        $('.kepesertaan_foto_show').hide()

    };

    onBack = () => {
        $('.table_data').show()
        $('.form_data').hide()
        onClear();
        // window.location.reload()
    };

    onRefresh = ()=>{
        onInitTable();
        onBack()
        onClear();
        count();
        window.location.reload()
    }

    onClear = ()=>{
        $('#formData')[0].reset();
        $('#list_kepesertaan_dokumen').html('');
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
                var urlsave = ($('[name=kepesertaan_id]').val() == '')? urlpath.insert:urlpath.update.replace(':id', $('[name=kepesertaan_id]').val()) ;

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

    onEdit = (id) => {
        onAdd()
        $('#profile_li').show()
        $.ajax({
            url: urlpath.show.replace(':id', id),
            type: 'GET',
            success: function(response){
                if(response.status == true){
                    $.each(response.data, function( key, value ){
                        if(key != 'kepesertaan_link_url'){
                            if(key == 'kepesertaan_foto'){
                                if(value != null){
                                    onCheckUrl(`{{ asset('public/storage/kepesertaan_foto/${value}') }}`, function(exist) {
                                        if(exist){
                                            $("#kepesertaan_foto").attr("src", `{{ asset('public/storage/kepesertaan_foto/${value}') }}`);
                                        } else{
                                            $("#kepesertaan_foto").attr("src", `{{asset('public/profile_default.png')}}`);
                                        }
                                    });
                                    
                                    $('.kepesertaan_foto_show').show()
                                }
                            } else{
                                $('[name='+key+']').val(value)

                            }
                        } 
                    })
                    $('#list_kepesertaan_dokumen').html('');
                    $.each(response.data.kepesertaan_dokumen, function( key, value ){
                        onAddList(value.kepesertaan_dokumen_id, value.kepesertaan_dokumen_judul, value.kepesertaan_dokumen_file)
                    })

                    $('input').focus();
                    $('#kepesertaan_dokumen_kepesertaan_id').val(response.data.kepesertaan_id)
                    $('#year_before_iuran_total').val(formatToRupiah(response.data.year_before_iuran_total))
                    $('#year_before_iuran_pengembangan').val(formatToRupiah(response.data.year_before_iuran_pengembangan))
                    
                    if(response.data.kepesertaan_status_kepesertaan == 'Pensiunan'){
                        showJenisPensiunan()
                    } else if(response.data.kepesertaan_status_kepesertaan == 'Pensiun Ditunda'){
                        showJenisPensiunan()
                    }

                    let data = response.data
                    onMasaPensiun(data.kepesertaan_status_jabatan)
                    onMasaKerjaSekarang(data.kepesertaan_terhitung_mulai_tanggal)
                    onMasaKerjaPensiun(data.kepesertaan_tanggal_lahir, data.kepesertaan_status_jabatan, data.kepesertaan_gaji_pokok, data.kepesertaan_terhitung_mulai_tanggal)
                    // onPerhitunganIuran(data.kepesertaan_gaji_pokok)

                    $('#kepesertaan_iuran_tahun').val(new Date().getFullYear())
                    var list_iuran = response.data.kepesertaan_iuran
                    var jumlah_iuran_total = 0;
                    var jumlah_iuran_pengembagan = 0;
                    if(list_iuran.length){
                        $.each(list_iuran, function( key, value ){
                            let index = value['kepesertaan_iuran_bulan'] - 1
                            $('input[name="'+value['kepesertaan_iuran_type']+'_list[]"]:eq(' + index + ')').val(value['kepesertaan_iuran_jumlah']);
                            
                            if(value['kepesertaan_iuran_type'] == 'iuran_total'){
                                if(value['kepesertaan_iuran_jumlah'] > 0){
                                    jumlah_iuran_total += parseFloat(value['kepesertaan_iuran_jumlah'])*0.075;
                                }
                                onChangeIuranTotal('total_'+value['kepesertaan_iuran_bulan'],value['kepesertaan_iuran_jumlah'])
                            } else if (value['kepesertaan_iuran_type'] == 'iuran_pengembangan'){
                                if(value['kepesertaan_iuran_jumlah'] > 0){
                                    var iuran_bulanan = parseFloat(value['kepesertaan_iuran_jumlah'])*0.075;
                                    jumlah_iuran_pengembagan += ((0.045/12)*iuran_bulanan ) + iuran_bulanan
                                }
                                onChangeIuranPengembangan('pengembangan_'+value['kepesertaan_iuran_bulan'],value['kepesertaan_iuran_jumlah'])
                            }
                        })

                        $('#jumlah_iuran_total').val(formatToRupiah(jumlah_iuran_total))
                        $('#jumlah_iuran_pengembangan').val(formatToRupiah(jumlah_iuran_pengembagan))
                        onPerhitunganTotal()
                    }
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
        $('.detail_kepesertaan_foto_show').hide()
        $.ajax({
            url: urlpath.show.replace(':id', id),
            type: 'GET',
            success: function(response){
                if(response.status == true){
                    data = response.data
                    $.each(data, function( key, value ){
                        if(key != 'kepesertaan_link_url'){
                            if(key == 'kepesertaan_foto'){
                                if(value != null){
                                    onCheckUrl(`{{ asset('public/storage/kepesertaan_foto/${value}') }}`, function(exist) {
                                        if(exist){
                                            $("#detail_kepesertaan_foto").attr("src", `{{ asset('public/storage/kepesertaan_foto/${value}') }}`);
                                        } else{
                                            $("#detail_kepesertaan_foto").attr("src", `{{asset('public/profile_default.png')}}`);
                                        }
                                    });
                                    
                                    $('.detail_kepesertaan_foto_show').show()
                                }
                            } else{
                                $('#detail_'+key).html(value)
                            }
                        } else if(value != ''){
                            $('#detail_'+key).html(`<a href="${value}" target="_blank" class="btn btn-info mt-3" style="width:100%">Lihat Detail Data</a>`)
                        }
                    })

                    if(data['kepesertaan_status_kepesertaan'] == 'Pensiunan'){
                        $('#detail_kepesertaan_jenis_pensiun').html(data['kepesertaan_jenis_pensiun'])
                    } else if(data['kepesertaan_status_kepesertaan'] == 'Pensiun Ditunda'){
                        $('#detail_kepesertaan_jenis_pensiun').html(data['kepesertaan_jenis_pensiun_sekaligus'])
                    } 

                    var firstloop = true
                    $.each(response.data.kepesertaan_dokumen, function( key, value ){
                        if(firstloop){
                            $('#detail_dokumen_list').html('')
                            firstloop = false
                        }

                        if(value.kepesertaan_dokumen_file != ''){
                            htmlpreview = `<a href="#" onclick="onPreviewList('${value.kepesertaan_dokumen_file}')"class="btn btn-icon btn-info btn-active-color-light btn-sm">
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

                        onAddList(value.kepesertaan_dokumen_id, value.kepesertaan_dokumen_judul, value.kepesertaan_dokumen_file)
                        $('#detail_dokumen_list').append(`
                            <tr>
                                <td class="p-2">${value.kepesertaan_dokumen_judul}</td>
                                <td class="text-center">${htmlpreview}</td>
                            </tr>
                        `)
                    })

                    onMasaPensiun(data.kepesertaan_status_jabatan,true)
                    onMasaKerjaSekarang(data.kepesertaan_terhitung_mulai_tanggal,true)
                    onMasaKerjaPensiun(data.kepesertaan_tanggal_lahir, data.kepesertaan_status_jabatan, data.kepesertaan_gaji_pokok, data.kepesertaan_terhitung_mulai_tanggal,true)
                    // onPerhitunganIuran(data.kepesertaan_gaji_pokok,true)

                    $('#detail_iuran_tahun').html('<option value="" selected disabled>Pilih Tahun Iuran</option>')
                    $.each(response.data.iuran_tahun_distinct, function( key, value ){
                        $('#detail_iuran_tahun').append(`<option value="${value.kepesertaan_iuran_tahun}">${value.kepesertaan_iuran_tahun}</option>`)
                    })

                    if(response.data.iuran_tahun_distinct.length){
                        $('#detail_kepesertaan_id').val(id)
                        $("#detail_iuran_tahun option:eq(1)").prop("selected", true);
                        onChangeTahunIuranDetail($("#detail_iuran_tahun").val())
                    } else{
                        $('#detail_kepesertaan_id').val('')
                        $('.detail_iuran_total_list').html(formatToRupiah(0))
                        $('.detail_iuran_pengembangan_list').html(formatToRupiah(0))
                        $('.detail_jumlah_iuran_total_list').html(formatToRupiah(0))
                        $('.detail_jumlah_iuran_pengembangan_list').html(formatToRupiah(0))
                    }
                    $('#detail_modal').modal('show')
                } 
            }
        })
    }

    showJenisPensiunan=()=>{
        let type = $('#kepesertaan_status_kepesertaan').val()

        $('#display_jenis_pensiunan').hide()
        $('#display_jenis_pensiun_sekaligus').hide()

        if(type == "Pensiunan"){
            $('#display_jenis_pensiunan').show()
        } else if(type == "Pensiun Ditunda"){
            $('#display_jenis_pensiun_sekaligus').show()
        }
    }

    onFilterShow =()=>{
        if($('#tipe_filter').val() == 1){
            var list = ["Guru Besar","Dosen","Karyawan"]
        } else{
            var list = ["Pensiun Normal","Pensiun Anak","Pensiun Janda/Duda"]
        }
        $("#kategori_filter").html()
        $("#kategori_filter").html('<option value="" selected disabled>Pilih Kategori Filter</option>')

        $.each( list, function( k, v ){
            $('#kategori_filter').append(
                `<option value="${v}" >${v}</option>`
            )
        }); 

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

        $('#list_kepesertaan_dokumen').append(`
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
        $('.preview_file').html(`<embed src="{{ asset('public/storage/kepesertaan_dokumen/${file}') }}" style="width:100%" height="500px">`)
        $('#detail_list_preview').modal('show')
    }

    onMasaPensiun = (jabatan='',detail=false) => {
        if(jabatan == ''){
            jabatan = $('#kepesertaan_status_jabatan').val()
        }

        var masa_pensiun = '0 Tahun'
        if(jabatan == "Guru Besar"){
            masa_pensiun = '70 Tahun'
        } else if(jabatan == "Dosen"){
            masa_pensiun = '65 Tahun'
        } else if(jabatan == "Karyawan"){
            masa_pensiun = '58 Tahun'
        }

        if(detail){
            $('#detail_masa_pensiun').html(masa_pensiun)
        } else{
            $('#auto_masa_pensiun').val(masa_pensiun)
        }
    } 

    onMasaKerjaSekarang = (tmt='',detail=false) =>{
        const currentDate = new Date();
        if(tmt == ''){
            tmt = $('#kepesertaan_terhitung_mulai_tanggal').val()
        }

        tmt = new Date(tmt);
        const timeDifference = currentDate - tmt;
        let result = '0 Tahun 0 Bulan 0 Hari'
        if(timeDifference > 0){
            const years = Math.floor(timeDifference / (365.25 * 24 * 60 * 60 * 1000));
            const months = Math.floor((timeDifference % (365.25 * 24 * 60 * 60 * 1000)) / (30.44 * 24 * 60 * 60 * 1000));
            const days = Math.floor((timeDifference % (30.44 * 24 * 60 * 60 * 1000)) / (24 * 60 * 60 * 1000));

            result = `${years} Tahun ${months} Bulan ${days} Hari`;
        }

        if(detail){
            $('#detail_masa_kerja_sekarang').html(result)
        } else{
            $('#auto_masa_kerja_sekarang').val(result)
        }
    }

    onMasaKerjaPensiun = (tgl_lahir='',status_jabatan='',phdp='',tmt='',detail=false) =>{
        if(tgl_lahir == ''){
            tgl_lahir = $('#kepesertaan_tanggal_lahir').val()
        }

        if(status_jabatan == ''){
            status_jabatan = $('#kepesertaan_status_jabatan').val()
        }

        if(phdp == ''){
            phdp = $('#kepesertaan_gaji_pokok').val()
        }

        if(tmt == ''){
            tmt = $('#kepesertaan_terhitung_mulai_tanggal').val()
        }

        let parse_year = 0
        if(tgl_lahir != '' && status_jabatan != ''){
            tgl_lahir = new Date(tgl_lahir);
            let year = tgl_lahir.getFullYear();
            let month = String(tgl_lahir.getMonth() + 1).padStart(2, '0');
            let day = String(tgl_lahir.getDate()).padStart(2, '0');

            let sum = 0
            if(status_jabatan == "Guru Besar"){
                sum = 70
            } else if(status_jabatan == "Dosen"){
                sum = 65
            } else if(status_jabatan == "Karyawan"){
                sum  = 58
            }
            sumyear = sum + year
            let pension_date = new Date(`${sumyear}-${month}-${day}`);
            tmt = new Date(tmt);

            const timeDifference = pension_date - tmt;
            let result = '0 Tahun 0 Bulan 0 Hari'
            if(timeDifference > 0){
                const years = Math.floor(timeDifference / (365.25 * 24 * 60 * 60 * 1000));
                const months = Math.floor((timeDifference % (365.25 * 24 * 60 * 60 * 1000)) / (30.44 * 24 * 60 * 60 * 1000));
                const days = Math.floor((timeDifference % (30.44 * 24 * 60 * 60 * 1000)) / (24 * 60 * 60 * 1000));

                result = `${years} Tahun ${months} Bulan ${days} Hari`;
                parse_year = years
                if(years >=32){
                    result = `32 Tahun 0 Bulan 0 Hari`;
                    parse_year = 32
                }
            }
            if(detail){
                $('#detail_masa_kerja_pensiun').html(result)
            } else{
                $('#auto_masa_kerja_pensiun').val(result)
            }
            
            } else{
                if(detail){
                    $('#detail_masa_kerja_pensiun').html(0)
                } else{
                    $('#auto_masa_kerja_pensiun').val('')
                }
            }
        
        onEstimasiSekaligus(parse_year, phdp, detail)
        onPerhitunganBulanan(parse_year, phdp, detail)
    }

    onEstimasiSekaligus = (masa_kerja_pensiun,phdp,detail) =>{
        let total = masa_kerja_pensiun * phdp * 2.5
        let result = formatToRupiah(total)
        if(detail){
            $('#detail_sekaligus').html(result)
        } else{
            $('#auto_sekaligus').val(result)
        }
        on20persen(total,detail)
    }

    on20persen = (estimasi_sekaligus,detail) =>{
        let total = estimasi_sekaligus * 0.2
        let result = formatToRupiah(total)
        if(detail){
            $('#detail_20persen').html(result)
        } else{
            $('#auto_20persen').val(result)
        }
        onPerhitunganPajak(total,detail)
    }

    onPerhitunganPajak = (persentase,detail) =>{
        let pajak = 0
        if(persentase > 50000000){
            pajak = 0.05 * (persentase-50000000)
        }
        let result = formatToRupiah(pajak)

        if(detail){
            $('#detail_pajak').html(result)
        } else{
            $('#auto_pajak').val(result)
        }
    }

    onPerhitunganBulanan = (masa_kerja_pensiun,phdp,detail) =>{
        let total = (masa_kerja_pensiun * phdp * 0.025) * 0.8
        let result = formatToRupiah(total)

        if(detail){
            $('#detail_bulanan').html(result)
        } else{
            $('#auto_bulanan').val(result)
        }
    }

    onChangeIuranTotal = (id,phdp="",detail=false) =>{
        if(phdp == ''){
            phdp = $('#phdp_'+id).val()
        }

        let iuran_bulanan = 0.075*phdp
        if(detail){
            
        } else{
            $('#iuran_'+id).val(formatToRupiah(iuran_bulanan))
        }
    }

    onChangeIuranPengembangan = (id,phdp="",detail=false) =>{
        if(phdp == ''){
            phdp = $('#phdp_'+id).val()
        }

        let iuran_bulanan = 0.075*phdp
        let iuran_pengembangan_per_tahun = ((0.045/12)*iuran_bulanan ) + iuran_bulanan

        if(detail){
            
        } else{
            $('#iuran_'+id).val(formatToRupiah(iuran_pengembangan_per_tahun))
        }
    }

    formatToRupiah = (number)=> {
        let formattedAmount = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(number);
        return formattedAmount.replace(/,00$/, '');
    }

    formatToNumber = (rupiah)=> {
        let removerp = rupiah.replace('Rp ','');
        let removedot = removerp.replaceAll('.','')
        return removedot.replace(',','.');
    }

    onRegenerateIuran = () =>{
        let id = $('[name=kepesertaan_id]').val()
        if(id){
            $.ajax({
            url: urlpath.show.replace(':id', id),
            type: 'GET',
            data: {
                year: $('#kepesertaan_iuran_tahun').val()
            },
            success: function(response){
                var list_iuran = response.data.kepesertaan_iuran
                var jumlah_iuran_total = 0;
                var jumlah_iuran_pengembagan = 0;

                $('.iuran_total_list').val('')
                $('.iuran_pengembangan_list').val('')
                $('#jumlah_iuran_total').val(formatToRupiah(0))
                $('#jumlah_iuran_pengembangan').val(formatToRupiah(0))
                $('#year_before_iuran_total').val(formatToRupiah(response.data.year_before_iuran_total))
                 $('#year_before_iuran_pengembangan').val(formatToRupiah(response.data.year_before_iuran_pengembangan))

                if(list_iuran.length){
                    $.each(list_iuran, function( key, value ){
                        let index = value['kepesertaan_iuran_bulan'] - 1
                        $('input[name="'+value['kepesertaan_iuran_type']+'_list[]"]:eq(' + index + ')').val(value['kepesertaan_iuran_jumlah']);

                        if(value['kepesertaan_iuran_type'] == 'iuran_total'){
                            if(value['kepesertaan_iuran_jumlah'] > 0){
                                jumlah_iuran_total += parseFloat(value['kepesertaan_iuran_jumlah'])*0.075;
                            }
                            onChangeIuranTotal('total_'+value['kepesertaan_iuran_bulan'],value['kepesertaan_iuran_jumlah'])
                        } else if (value['kepesertaan_iuran_type'] == 'iuran_pengembangan'){
                            if(value['kepesertaan_iuran_jumlah'] > 0){
                                var iuran_bulanan = parseFloat(value['kepesertaan_iuran_jumlah'])*0.075;
                                jumlah_iuran_pengembagan += ((0.045/12)*iuran_bulanan ) + iuran_bulanan
                            }
                            onChangeIuranPengembangan('pengembangan_'+value['kepesertaan_iuran_bulan'],value['kepesertaan_iuran_jumlah'])
                        }
                    })


                    
                    $('#jumlah_iuran_total').val(formatToRupiah(jumlah_iuran_total))
                    $('#jumlah_iuran_pengembangan').val(formatToRupiah(jumlah_iuran_pengembagan))
                }
            }
        })
        }
        
        $('.year_choose').html($('#kepesertaan_iuran_tahun').val())
    }

    onChangeTahunIuranDetail = (parse_year='') =>{
        let id = $('#detail_kepesertaan_id').val()
        if(parse_year == ''){            parse_year = $("#detail_iuran_tahun").val()
        }
        $.ajax({
            url: urlpath.show.replace(':id', id),
            type: 'GET',
            data: {
                year: parse_year
            },
            success: function(response){
                var list_iuran = response.data.kepesertaan_iuran
                var jumlah_iuran_total = 0;
                var jumlah_iuran_pengembagan = 0;

                $('.detail_iuran_total_list').html(formatToRupiah(0))
                $('.detail_iuran_pengembangan_list').html(formatToRupiah(0))
                $('.detail_jumlah_iuran_total_list').html(formatToRupiah(0))
                $('.detail_jumlah_iuran_pengembangan_list').html(formatToRupiah(0))

                if(list_iuran.length){
                    $.each(list_iuran, function( key, value ){
                        let index = value['kepesertaan_iuran_bulan'] - 1
                        if(value['kepesertaan_iuran_jumlah'] > 0){
                            $('.detail_'+value['kepesertaan_iuran_type']+'_list:eq(' + index + ')').html(formatToRupiah(value['kepesertaan_iuran_jumlah']));
                        } else{
                            $('.detail_'+value['kepesertaan_iuran_type']+'_list:eq(' + index + ')').html(formatToRupiah(0));
                        }

                        if(value['kepesertaan_iuran_type'] == 'iuran_total'){
                            jumlah_iuran_total += value['kepesertaan_iuran_jumlah']
                        } else if (value['kepesertaan_iuran_type'] == 'iuran_pengembangan'){
                            jumlah_iuran_pengembagan += value['kepesertaan_iuran_jumlah']
                        }
                    })

                    $('.detail_jumlah_iuran_total_list').html(formatToRupiah(jumlah_iuran_total))
                    $('.detail_jumlah_iuran_pengembangan_list').html(formatToRupiah(jumlah_iuran_pengembagan))
                }
            }
        })
    }

    $(".iuran_total_list").on("input", function() {
        var sum = 0;
        $("input[name='iuran_total_list[]']").each(function() {
            var value = parseFloat($(this).val())*0.075;
            if (!isNaN(value)) {
                sum += value;
            }
        });
        $('#jumlah_iuran_total').val(formatToRupiah(sum));
        onPerhitunganTotal()
    });

    $(".iuran_pengembangan_list").on("input", function() {
        var sum = 0;
        $("input[name='iuran_pengembangan_list[]']").each(function() {
            var iuran_bulanan = parseFloat($(this).val())*0.075;
            var value =((0.045/12)*iuran_bulanan ) + iuran_bulanan
            if (!isNaN(value)) {
                sum += value;
            }
        });
        $('#jumlah_iuran_pengembangan').val(formatToRupiah(sum));
        onPerhitunganTotal()
    });

    onPerhitunganTotal = () =>{
        let jumlah_iuran_pengembangan = parseFloat(formatToNumber($('#jumlah_iuran_pengembangan').val()))
        let jumlah_iuran_total =  parseFloat(formatToNumber($('#jumlah_iuran_total').val()))
        let before_jumlah_iuran_pengembangan = parseFloat(formatToNumber($('#year_before_iuran_pengembangan').val()))
        let before_jumlah_iuran_total =  parseFloat(formatToNumber($('#year_before_iuran_total').val()))

        let total = jumlah_iuran_pengembangan+jumlah_iuran_total+before_jumlah_iuran_pengembangan+before_jumlah_iuran_total
        $('#jumlah_keseluruhan').val(formatToRupiah(total))
    }
    // onPerhitunganIuran = (phdp='',detail=false) =>{
    //     if(phdp == ''){
    //         phdp = $('#kepesertaan_gaji_pokok').val()
    //     }

    //     let iuran_bulanan = 0.075*phdp
    //     let iuran_pengembangan_per_tahun = ((0.045/12)*iuran_bulanan ) + iuran_bulanan

    //     if(detail){
    //         $('#detail_iuran').html(formatToRupiah(iuran_bulanan))
    //         $('#detail_iuran_pengembangan').html(formatToRupiah(iuran_pengembangan_per_tahun))
    //     } else{
    //         $('#auto_iuran').val(formatToRupiah(iuran_bulanan))
    //         $('#auto_iuran_pengembangan').val(formatToRupiah(iuran_pengembangan_per_tahun))
    //     }
    // }
</script>