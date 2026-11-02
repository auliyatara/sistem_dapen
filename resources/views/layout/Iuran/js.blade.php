<script>
    const urlpath = {
        select: "{{ route('iuran_api.index') }}",
    };
    let iuranRequest, detailRequest;
    const namaBulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    const displayAmount = value => value === null || value === undefined ? 'Belum ada data' : formatToRupiah(value);

    $(function () {
        onInitTable();
        $('#year').on('change', onInitTable);
        $(document).on('click', '.iuran-detail-button', function () {
            if (detailRequest) detailRequest.abort();
            $('#iuran-detail-status').text('Memuat rincian...');
            $('#iuran-detail-content').prop('hidden', true);
            $('#iuran-detail-rows').empty();
            $('#iuran-detail-title').text(`Rincian iuran ${$(this).text()} — ${this.dataset.year}`);
            bootstrap.Modal.getOrCreateInstance(document.getElementById('iuran-detail-modal')).show();
            detailRequest = $.ajax({
                url: urlpath.select, data: { year: this.dataset.year, participant_id: this.dataset.id },
                success: function (response) {
                    if (!response.status) { $('#iuran-detail-status').text('Rincian gagal dimuat. Silakan coba kembali.'); return; }
                    response.data.bulan.forEach(month => {
                        const tr = $('<tr>');
                        [namaBulan[month.bulan - 1], displayAmount(month.phdp), displayAmount(month.iuran), displayAmount(month.iuran_pengembangan)].forEach((value, index) => {
                            $('<td>').text(value).addClass(index ? 'text-end text-nowrap' : '').appendTo(tr);
                        });
                        $('#iuran-detail-rows').append(tr);
                    });
                    $('#iuran-detail-status').text(response.data.pengembangan_estimasi ? 'Kolom pengembangan memakai estimasi karena data pengembangan tahun ini belum tersedia.' : 'Kolom pengembangan memakai data pengembangan yang tercatat. Bulan tanpa data ditandai Belum ada data.');
                    $('#iuran-detail-content').prop('hidden', false);
                },
                error: function (_, status) { if (status !== 'abort') $('#iuran-detail-status').text('Rincian gagal dimuat. Silakan coba kembali.'); },
            });
        });
        $('#iuran-detail-modal').on('hidden.bs.modal', () => { if (detailRequest) detailRequest.abort(); });
    });

    onInitTable = () => {
        if (!document.getElementById('year').reportValidity()) return;
        if (iuranRequest) iuranRequest.abort();
        table.clear().draw();
        $('#iuran-load-status').text('Memuat data...');

        iuranRequest = $.ajax({
            url: urlpath.select,
            type: 'GET',
            data: { year: $('#year').val() },
            success: function (response) {
                if (!response.status) { $('#iuran-load-status').text('Data gagal dimuat. Silakan coba kembali.'); return; }

                $('#total_peserta_aktif').text(`Daftar Peserta ${response.data.total_peserta}`);
                const rows = response.data.peserta.map((peserta, index) => [
                    `${index + 1}.`,
                    $('<button>').attr({ type: 'button', 'data-id': peserta.kepesertaan_id, 'data-year': response.data.tahun }).addClass('btn btn-sm btn-light-primary iuran-detail-button').text(peserta.nama).prop('outerHTML'),
                    displayAmount(peserta.phdp),
                    displayAmount(peserta.iuran),
                    displayAmount(peserta.iuran_pengembangan),
                ]);

                table.rows.add(rows).draw();
                $('#iuran-load-status').text('');
            },
            error: function (_, status) { if (status !== 'abort') $('#iuran-load-status').text('Data gagal dimuat. Silakan coba kembali.'); },
        });
    };

    formatToRupiah = (value) => new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 2,
    }).format(value || 0);
</script>
