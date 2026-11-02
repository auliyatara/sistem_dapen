<script>
    const formatTanggalIndonesia = value => {
        const match = String(value || '').match(/^(\d{4})-(\d{2})-(\d{2})/);
        if (!match) return '-';
        const date = new Date(Date.UTC(Number(match[1]), Number(match[2]) - 1, Number(match[3])));
        if (date.getUTCFullYear() !== Number(match[1]) || date.getUTCMonth() !== Number(match[2]) - 1 || date.getUTCDate() !== Number(match[3])) return '-';
        return new Intl.DateTimeFormat('id-ID', { day: 'numeric', month: 'long', year: 'numeric', timeZone: 'UTC' }).format(date);
    };
    var urlpath ={
        insert: "{{ route('kepegawaian_api.store') }}",
        update: "{{ route('kepegawaian_api.update', ['kepegawaian_api' => ':id']) }}",
        delete: "{{ route('kepegawaian_api.destroy', ['kepegawaian_api' => ':id']) }}",
        select: "{{ route('kepegawaian_api.index') }}",
        show: "{{ route('kepegawaian_api.show', ['kepegawaian_api' => ':id']) }}",
        role: "{{ route('role_api.index') }}"
    }

    $(function(){
        const address = $('#formData [name=alamat_saat_ini]');
        $('#pegawai-alamat-sama').on('change', function () {
            if (this.checked) address.val($('#formData [name=alamat_ktp]').val());
            address.prop('readonly', this.checked);
        });
        $('#formData [name=alamat_ktp]').on('input', function () {
            if ($('#pegawai-alamat-sama').prop('checked')) address.val(this.value);
        });
        $('#formData').on('reset', function () { address.prop('readonly', false); });
        onInitTable();
        onRole();
        $(document).on('change', '[name=tanggal_lahir]', updateUsia);
        $(document).on('change', '[name=perkiraan_pensiun]', updateSisaMasaKerja);
        $('#add-pegawai-link').on('click', function () {
            addPegawaiLink().find('input').trigger('focus');
        });
        $('#pegawai-links').on('click', '.remove-pegawai-link', function () {
            $(this).closest('.pegawai-link-row').remove();
        });
    })

    const parsePegawaiLinks = (value) => {
        try {
            const links = typeof value === 'string' ? JSON.parse(value) : value;
            return Array.isArray(links) ? links.filter(link => typeof link === 'string') : [];
        } catch (error) {
            return [];
        }
    };

    const addPegawaiLink = (value = '') => {
        const row = $('<div class="d-flex gap-2 pegawai-link-row">');
        $('<input type="url" name="links[]" class="form-control form-control-solid" placeholder="https://..." maxlength="2048" aria-label="Link tambahan">').val(value).appendTo(row);
        $('<button type="button" class="btn btn-light-danger btn-icon remove-pegawai-link" aria-label="Hapus link" title="Hapus link">').text('−').appendTo(row);
        return row.appendTo('#pegawai-links');
    };

    const fillPegawaiLinks = (value) => {
        const links = parsePegawaiLinks(value);
        $('#pegawai-links .pegawai-link-row').slice(1).remove();
        $('#pegawai-link-first').val(links[0] || '');
        links.slice(1).forEach(link => addPegawaiLink(link));
    };

    onInitTable = () =>{
        table.clear();
        $.ajax({
            url: urlpath.select,
            type: 'GET',
            success: function(response){
                if(response.status == true){
                    var array = []
                    $.each( response.data, function( k, v ){
                        let html_action = `
                            <td class="text-center">
                                <a href="javascript:onShow('${v.id}')" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
                                    <!--begin::Svg Icon | path: icons/duotune/art/art005.svg-->
                                    <span class="svg-icon svg-icon-3">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path opacity="0.3" d="M12 5C7 5 3.73 8.11 2 12C3.73 15.89 7 19 12 19C17 19 20.27 15.89 22 12C20.27 8.11 17 5 12 5Z" fill="currentColor"/>
                                            <path d="M12 9C10.3431 9 9 10.3431 9 12C9 13.6569 10.3431 15 12 15C13.6569 15 15 13.6569 15 12C15 10.3431 13.6569 9 12 9Z" fill="currentColor"/>
                                        </svg>
                                    </span>
                                    <!--end::Svg Icon-->
                                </a>
                                @if($edit)
                                <a href="javascript:onEdit('${v.id}')" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
                                    <!--begin::Svg Icon | path: icons/duotune/art/art005.svg-->
                                    <span class="svg-icon svg-icon-3">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path opacity="0.3" d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z" fill="currentColor"></path>
                                            <path d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z" fill="currentColor"></path>
                                        </svg>
                                    </span>
                                    <!--end::Svg Icon-->
                                </a>
                                <a href="javascript:onDelete('${v.id}')" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm">
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
                                @endif
                            </td>
                        `
                        let push_array = [v.nama,v.email_1,v.nomor_hp_1,formatTanggalIndonesia(v.tanggal_pengangkatan),v.jabatan,html_action]
                        array.push(push_array);
                    });

                    table.rows.add(array).draw();    
                } 
            }
        })
        table.draw();
    }

    onAdd = () => {
        fillPegawaiLinks([]);
        $('.table_data').hide()
        $('.form_data').show()
        $("#menu-title").html("Tambah Pegawai");
        $("#lampiran").show().prop('disabled', false).prop('required', true);
        $("#lampiran_view").hide().empty();
    };

    onBack = () => {
        $('.table_data').show()
        $('.form_data').hide()
        onClear();
    };

    onShow = (id) => {
        $.ajax({
            url: urlpath.show.replace(':id', id),
            type: 'GET',
            success: function(response){

                if(response.status == true){
                    renderDetailPegawai(response.data[0]);
                    $('#pegawai_detail_modal').modal('show');
                } 
            }
        })
    }

    onEdit = (id) => {
        onAdd()
        $("#menu-title").html("Edit Pegawai");
        $("#lampiran").prop('required', false);
        $.ajax({
            url: urlpath.show.replace(':id', id),
            type: 'GET',
            success: function(response){

                if(response.status == true){
                    renderLampiran(response.data[0].lampiran)
                    fillPegawaiLinks(response.data[0].links);
                    $.each(response.data[0], function( key, value ){

                            $('[name='+key+']').val(value)
                            $('[name='+key+']').attr("disabled", false)
                        
                    })
                    updateUsia();
                    updateSisaMasaKerja();
                    // $('[name=password]').val('').attr("placeholder", "Isi Jika Ingin Mengganti Password");
                } 
            }
        })
    };

    onRole = () => {
        $.ajax({
            url: urlpath.role,
            type: 'GET',
            success: function(response){
                if(response.status == true){
                    $('#user_role').html('<option value="" selected disabled>Pilih Role</option>')

                    $.each(response.data, function( key, value ){
                        $('#user_role').append('<option value="'+value['role_id']+'">'+value['role_name']+'</option>')
                    })
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
                const formElement = $('#formData')[0];
                var content_type = {}
                if($('[name=id]').val() == ''){
                    var form = new FormData(formElement);
                    urlsave = urlpath.insert
                    urltype = 'POST'
                } else{
                    var form = new FormData(formElement);
                    form.append('_method', 'PATCH');
                    urlsave = urlpath.update.replace(':id', $('[name=id]').val())
                    urltype = 'POST'
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
                    },
                    error: function(xhr) {
                        const errors = xhr.responseJSON?.errors;
                        swal('Periksa data', errors ? Object.values(errors).flat().join('\n') : 'Data gagal disimpan. Silakan coba kembali.', 'warning');
                    }
                })
            }
        }); 
    }

    renderLampiran = (lampiran, target = '#lampiran_view') => {
        let files;
        try {
            files = JSON.parse(lampiran);
        } catch (error) {
            files = lampiran ? [lampiran] : [];
        }

        const urlLampiran = '{{ route('kepegawaian_api.lampiran', ['filename' => '__filename__']) }}';
        const links = files.map((file, index) => {
            const path = typeof file === 'string' ? file : file.path;
            const filename = path.split('/').pop();
            const displayName = typeof file === 'string' || !file.name ? `Lampiran ${index + 1}` : file.name;
            const href = urlLampiran.replace('__filename__', encodeURIComponent(filename));

            return `<button type="button" class="btn btn-sm btn-light-primary me-2 mb-2 btn-preview-lampiran" data-url="${href}" data-filename="${filename}" data-name="${escapeHtml(displayName)}">${escapeHtml(displayName)}</button>`;
        }
        ).join('');

        $(target).html(links).toggle(files.length > 0);
    }

    escapeHtml = (value) => $('<div>').text(value).html();

    renderDetailPegawai = (pegawai) => {
        const fields = {
            nama: 'Nama', nip: 'NIP', nik: 'NIK', tempat_lahir: 'Tempat Lahir',
            tanggal_lahir: 'Tanggal Lahir', usia: 'Usia', jenis_kelamin: 'Jenis Kelamin',
            agama: 'Agama', alamat_ktp: 'Alamat (KTP)', alamat_saat_ini: 'Alamat Saat Ini',
            nomor_hp_1: 'Nomor HP 1', nomor_hp_2: 'Nomor HP 2', email_1: 'Email 1',
            email_2: 'Email 2', tanggal_pengangkatan: 'Tanggal Pengangkatan',
            perkiraan_pensiun: 'Perkiraan Pensiun', masa_kerja_durasi: 'Masa Kerja',
            sisa_masa_kerja: 'Sisa Masa Kerja', jabatan: 'Jabatan'
        };

        const detail = Object.entries(fields).map(([key, label]) => {
            const value = key === 'sisa_masa_kerja'
                ? formatSisaMasaKerja(pegawai.perkiraan_pensiun)
                : (['tanggal_lahir', 'tanggal_pengangkatan', 'perkiraan_pensiun'].includes(key) ? formatTanggalIndonesia(pegawai[key]) : (pegawai[key] || '-'));

            return `
            <div class="col-md-6 mb-4">
                <div class="text-muted fs-7">${label}</div>
                <div class="fw-semibold">${escapeHtml(value)}</div>
            </div>
            `;
        }).join('');

        $('#pegawai_detail_title').text(`Detail Pegawai: ${pegawai.nama}`);
        $('#pegawai_detail_content').html(detail);
        const linkSection = $('<div class="col-12 mb-4">');
        $('<div class="text-muted fs-7 mb-2">').text('Link').appendTo(linkSection);
        const links = parsePegawaiLinks(pegawai.links).filter(link => /^https?:\/\//i.test(link));
        if (!links.length) $('<div>').text('-').appendTo(linkSection);
        links.forEach(link => {
            $('<a class="d-block mb-2" target="_blank" rel="noopener noreferrer">')
                .attr('href', link).text(link).css('overflow-wrap', 'anywhere').appendTo(linkSection);
        });
        $('#pegawai_detail_content').append(linkSection);
        renderLampiran(pegawai.lampiran, '#pegawai_detail_lampiran');
    }

    formatSisaMasaKerja = (tanggalPensiun) => {
        if (!tanggalPensiun) return '-';

        const today = new Date();
        today.setHours(0, 0, 0, 0);
        const pensionDate = new Date(`${tanggalPensiun}T00:00:00`);

        if (Number.isNaN(pensionDate.getTime()) || pensionDate <= today) {
            return '0 Tahun 0 Bulan 0 Hari';
        }

        let years = pensionDate.getFullYear() - today.getFullYear();
        let anniversary = new Date(today);
        anniversary.setFullYear(today.getFullYear() + years);

        if (anniversary > pensionDate) {
            years--;
            anniversary = new Date(today);
            anniversary.setFullYear(today.getFullYear() + years);
        }

        let months = (pensionDate.getFullYear() - anniversary.getFullYear()) * 12
            + pensionDate.getMonth() - anniversary.getMonth();
        let monthiversary = new Date(anniversary);
        monthiversary.setMonth(anniversary.getMonth() + months);

        if (monthiversary > pensionDate) {
            months--;
            monthiversary = new Date(anniversary);
            monthiversary.setMonth(anniversary.getMonth() + months);
        }

        const days = Math.round((pensionDate - monthiversary) / (1000 * 60 * 60 * 24));

        return `${years} Tahun ${months} Bulan ${days} Hari`;
    }

    updateUsia = () => {
        const tanggalLahir = $('[name=tanggal_lahir]').val();
        if (!tanggalLahir) return $('[name=usia]').val('');

        const birthDate = new Date(`${tanggalLahir}T00:00:00`);
        const today = new Date();
        let age = today.getFullYear() - birthDate.getFullYear();
        const monthDifference = today.getMonth() - birthDate.getMonth();

        if (monthDifference < 0 || (monthDifference === 0 && today.getDate() < birthDate.getDate())) {
            age--;
        }

        $('[name=usia]').val(age >= 0 ? age : '');
    }

    updateSisaMasaKerja = () => {
        $('[name=sisa_masa_kerja]').val(formatSisaMasaKerja($('[name=perkiraan_pensiun]').val()));
    }

    onPreviewLampiran = (url, filename, displayName) => {
        const isImage = /\.(jpg|jpeg|png|gif|webp)$/i.test(filename);
        const preview = isImage
            ? `<img src="${url}" alt="${filename}" class="img-fluid d-block mx-auto" style="max-height: 70vh;">`
            : `<iframe src="${url}" title="${filename}" style="width:100%; height:70vh; border:0;"></iframe>`;

        $('#lampiran_preview_title').text(`Preview Lampiran: ${displayName}`);
        $('#lampiran_preview_content').html(preview);
        $('#lampiran_preview_modal').modal('show');
    }

    $('#lampiran_preview_modal').on('hidden.bs.modal', function () {
        $('#lampiran_preview_content').empty();
    });

    $(document).on('click', '.btn-preview-lampiran', function () {
        onPreviewLampiran($(this).data('url'), $(this).data('filename'), $(this).data('name'));
    });


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

    onRefresh = ()=>{
        onBack();
        onInitTable();
    }

    onClear = ()=>{
        $('#formData')[0].reset();
        window.location.reload()
    }
</script>
