<script>
    var urlpath ={
        select: "{{ route('riwayat_api.index') }}",
        delete: "{{ route('riwayat_api.destroy', ['riwayat_api' => ':id']) }}",

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
                        let push_array = [k+1,v.user_nama,v.role_name,v.description,v.created_at]
                        array.push(push_array);
                    });

                    table.rows.add(array).draw();    
                } 
            }
        })
        table.draw();
    }

    onDelete = (id) =>{
        swal({
            title: "Peringatan",
            text: "Apakah Anda Yakin Untuk Mereset Data Riwayat?",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((response) => {
            if (response) {
                $.ajax({
                    url: urlpath.delete.replace(':id', 'all'),
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

    onRefresh =()=>{
        onInitTable()
    }
    
</script>