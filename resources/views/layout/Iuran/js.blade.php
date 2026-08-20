<script>
    const urlpath = {
        select: "{{ route('iuran_api.index') }}",
    };

    $(function () {
        onInitTable();
        $('#year').on('change', onInitTable);
    });

    onInitTable = () => {
        table.clear();

        $.ajax({
            url: urlpath.select,
            type: 'GET',
            data: { year: $('#year').val() },
            success: function (response) {
                if (!response.status) return;

                $('#total_peserta_aktif').text(`Daftar Peserta ${response.data.total_peserta}`);
                const rows = response.data.peserta.map((peserta, index) => [
                    `${index + 1}.`,
                    peserta.nama,
                    formatToRupiah(peserta.phdp),
                    formatToRupiah(peserta.iuran),
                    formatToRupiah(peserta.iuran_pengembangan),
                ]);

                table.rows.add(rows).draw();
            },
        });
    };

    formatToRupiah = (value) => new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 2,
    }).format(value || 0);
</script>
