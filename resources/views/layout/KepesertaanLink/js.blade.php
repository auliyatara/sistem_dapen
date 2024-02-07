<script>
    var urlpath ={
        update: "{{ route('kepesertaan_link_api.update', ['kepesertaan_link_api' => ':id']) }}",
        select: "{{ route('kepesertaan_link_api.index') }}",
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
                        let html =`<input type="hidden" name="kepesertaan_link_id[]" value="${v.kepesertaan_link_id}"> <input type="text" class="form-control" name="kepesertaan_link_url[]" style="width:100%" value="${v.kepesertaan_link_url}">`
                        let push_array = [k+1,v.kepesertaan_link_nama,html]
                        array.push(push_array);
                    });

                    table.rows.add(array).draw();    
                } 
            }
        })
        table.draw();
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
                    url: urlpath.update.replace(':id', 'dummyid'),
                    data: form,
                    contentType: false,
                    processData: false,
                    type: 'PATCH',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded' // Set the custom Content-Type header
                    } ,
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
        window.location.reload()
    }
</script>