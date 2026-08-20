<script>
    const urlpath = {
        select: "{{ route('total_api.index') }}",
    };

    const namaBulan = [
        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];

    $(function () {
        table.page.len(12).draw();
        onInitTable();
        $('#year').on('change', onInitTable);
        $(document).on('input', '.persen-pemberi-kerja', hitungIuranPemberiKerja);
    });

    onInitTable = () => {
        table.clear();

        $.ajax({
            url: urlpath.select,
            type: 'GET',
            data: { year: $('#year').val() },
            success: function (response) {
                if (!response.status) return;

                // API mengirim bulan 1 sampai 12; urutan ini dipertahankan Januari–Desember.
                const rows = response.data.bulan.map((data) => [
                    namaBulan[data.bulan - 1],
                    formatToRupiah(data.iuran_peserta),
                    `
                        <div class="d-flex flex-column gap-1">
                            <div class="input-group input-group-sm">
                                <input type="number" min="0" step="0.01" class="form-control persen-pemberi-kerja" data-iuran="${data.iuran_peserta}" placeholder="Persen">
                                <span class="input-group-text">%</span>
                            </div>
                            <span class="nominal-pemberi-kerja text-muted">Rp0</span>
                        </div>
                    `,
                ]);

                table.rows.add(rows);
                table.order([]).draw();
                $('#total_iuran_peserta').val(formatToRupiah(response.data.total_iuran_peserta));
                $('#total_iuran_pemberi_kerja').val(formatToRupiah(0));
            },
        });
    };

    hitungIuranPemberiKerja = () => {
        let total = 0;

        $('.persen-pemberi-kerja').each(function () {
            const iuranPeserta = Number($(this).data('iuran')) || 0;
            const persen = Number($(this).val()) || 0;
            const nominal = iuranPeserta * persen / 100;

            $(this).closest('.d-flex').find('.nominal-pemberi-kerja').text(formatToRupiah(nominal));
            total += nominal;
        });

        $('#total_iuran_pemberi_kerja').val(formatToRupiah(total));
    };

    formatToRupiah = (value) => new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 2,
    }).format(value || 0);
</script>
