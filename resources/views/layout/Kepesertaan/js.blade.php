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
            let buttonEdit = (authEdit?` <a href="javascript:onEdit('${v.kepesertaan_id}')" class="btn btn-sm btn-success">
                                    Edit
                                </a>
                    <a href="javascript:onDelete('${v.kepesertaan_id}')" class="btn btn-sm btn-danger">
                        <span class="svg-icon svg-icon-3">
                        </span>Delete
                    </a>`:``)
            let html_action = `
                <td class="text-center">
                    <a href="javascript:onDetail('${v.kepesertaan_id}')" class="btn btn-sm btn-secondary">
                        <span class="svg-icon svg-icon-3">
                        </span>Detail
                    </a>
                    ${buttonEdit}
                </td>
            `
            let ttl = v.kepesertaan_tempat_lahir+", "+ v.kepesertaan_tanggal_lahir
            let push_array = [k+1,v.kepesertaan_nama,v.kepesertaan_nip,ttl,v.kepesertaan_jenis_kelamin,html_action]
            array.push(push_array);
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
                                    $("#kepesertaan_foto").attr("src", `{{ asset('public/storage/kepesertaan_foto/${value}') }}`);
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
                    
                    if(response.data.kepesertaan_status_kepesertaan == 'Pensiunan'){
                        showJenisPensiunan()
                    } else if(response.data.kepesertaan_status_kepesertaan == 'Pensiun Ditunda'){
                        showJenisPensiunan()
                    }

                    let data = response.data
                    onMasaPensiun(data.kepesertaan_status_jabatan)
                    onMasaKerjaSekarang(data.kepesertaan_terhitung_mulai_tanggal)
                    onMasaKerjaPensiun(data.kepesertaan_tanggal_lahir, data.kepesertaan_status_jabatan, data.kepesertaan_gaji_pokok, data.kepesertaan_terhitung_mulai_tanggal)
                    onPerhitunganIuran(data.kepesertaan_gaji_pokok)
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
                                    $("#detail_kepesertaan_foto").attr("src", `{{ asset('public/storage/kepesertaan_foto/${value}') }}`);
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

    onMasaPensiun = (jabatan='') => {
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

        $('#auto_masa_pensiun').val(masa_pensiun)
    } 

    onMasaKerjaSekarang = (tmt='') =>{
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

        $('#auto_masa_kerja_sekarang').val(result)
    }

    onMasaKerjaPensiun = (tgl_lahir='',status_jabatan='',phdp='',tmt='') =>{
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
            $('#auto_masa_kerja_pensiun').val(result)
        } else{
            $('#auto_masa_kerja_pensiun').val('')
        }
        
        onEstimasiSekaligus(parse_year, phdp)
        onPerhitunganBulanan(parse_year, phdp)
    }

    onEstimasiSekaligus = (masa_kerja_pensiun,phdp) =>{
        let total = masa_kerja_pensiun * phdp * 2.5
        let result = formatToRupiah(total)
        $('#auto_sekaligus').val(result)
        on20persen(total)
    }

    on20persen = (estimasi_sekaligus) =>{
        let total = estimasi_sekaligus * 0.2
        let result = formatToRupiah(total)
        $('#auto_20persen').val(result)
        onPerhitunganPajak(total)
    }

    onPerhitunganPajak = (persentase) =>{
        let pajak = 0
        if(persentase > 50000000){
            pajak = 0.05 * (persentase-50000000)
        }
        let result = formatToRupiah(pajak)
        $('#auto_pajak').val(result)
    }

    onPerhitunganBulanan = (masa_kerja_pensiun,phdp) =>{
        let total = (masa_kerja_pensiun * phdp * 0.025) * 0.8
        let result = formatToRupiah(total)
        $('#auto_bulanan').val(result)
    }

    onPerhitunganIuran = (phdp='') =>{
        if(phdp == ''){
            phdp = $('#kepesertaan_gaji_pokok').val()
        }

        let iuran_bulanan = 0.075*phdp
        let iuran_pengembangan_per_tahun = ((0.045/12)*iuran_bulanan ) + iuran_bulanan

        $('#auto_iuran').val(formatToRupiah(iuran_bulanan))
        $('#auto_iuran_pengembangan').val(formatToRupiah(iuran_pengembangan_per_tahun))
    }

    formatToRupiah = (number)=> {
        let formattedAmount = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(number);
        return formattedAmount.replace(/,00$/, '');
    }
</script>