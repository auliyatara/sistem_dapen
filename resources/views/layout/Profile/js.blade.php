<script>
    var urlpath ={
        show: "{{ route('profile_api.index') }}",
        update: "{{ route('users_api.update', ['users_api' => ':id']) }}",
        updatealternate: "{{ route('users_api.updatealternate') }}",
    }  

    $(function(){
        onEdit();
    })

    onEdit = () =>{
        $.ajax({
            url: urlpath.show,
            type: 'GET',
            success: function(response){
                if(response.status == true){
                    data = response.data[0]
                    $.each(data, function( key, value ){
                        if(key != 'password' && key !="user_foto"){
                            $('[name='+key+']').val(value)
                        } else if(key == "user_foto"){
                            if(value != null){
                            $("#profile_image").attr("src", `{{ asset('public/storage/user_foto/${value}') }}`);
                            }
                        }
                    })

                    $('[name=role_name]').val(data.role.role_name)
                } 
            }
        })
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
                var formData = new FormData($('#formData')[0]);// Create a FormData object from the form element

                $.ajax({
                    url: urlpath.updatealternate,
                    data: formData,
                    contentType: false, // Set to false to let the browser set the content type
                    processData: false, // Set to false to prevent jQuery from processing the data
                    type: 'POST',
                    success: function(response){
                        if(response.status == true){
                            swal("Success !", response.message, "success");
                            window.location.reload()
                        } else {
                            swal("Warning", response.message, "warning");
                        }
                    }
                });
            }
        }); 
    }
</script>