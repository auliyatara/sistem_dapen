<script>
    var urlpath ={
        show: "{{ route('dashboard_api.index') }}",
        show_informasi: "{{ route('informasi_tambahan_api.index') }}",
    }  

    $(function(){
        onDetail();
        onInformasiUmum();
    })

    onDetail = () =>{
        $.ajax({
            url: urlpath.show,
            type: 'GET',
            success: function(response){
                if(response.status == true){
                    data = response.data[0]
                    
                    $('#user_nama').html(data.user_nama)
                    $('#user_email').html(data.user_email)
                    $('#role_name').html(data.role.role_name)

                    $.each(data, function( key, value ){
                        if(key == "user_foto"){
                            if(value != null){
                                $("#profile_image").attr("src", `{{ asset('public/storage/user_foto/${value}') }}`);
                                $("#header_image").attr("src", `{{ asset('public/storage/user_foto/${data.user_foto}') }}`);
                            }
                        } else{
                            $('[name='+key+']').val(value)
                        }
                })
                } 

                
            }
        })
    }

    onInformasiUmum = () =>{
        $('.list_informasi').html('Informasi Belum Tersedia')
        $.ajax({
            url: urlpath.show_informasi,
            type: 'GET',
            success: function(response){
                if(response.status == true && response.data.length >0){
                    $('.list_informasi').html('')
                    $.each( response.data, function( k, v ){
                        $('.list_informasi').append(`
                            <div class="card mb-5 mb-xxl-8">
                                <div class="card-body pt-1 pb-1">
                                    <h4>${v.informasi_judul}</h4>
                                    <p style="margin:0">${v.informasi_deskripsi}</p>
                                    <p style="font-style:italic;margin:0;font-size:10px;font-weight:bold">${formatDate(v.informasi_tanggal)}</p>
                                </div>
                            </div>
                        `)
                    })
                } 
            }
        })
    }

    formatDate = (inputDate)=> {
        const parts = inputDate.split('-');
        if (parts.length === 3) {
            const year = parts[0];
            const month = parts[1];
            const day = parts[2];
            return `${day}/${month}/${year}`;
        }
        return "Invalid Date Format";
    }

</script>