<script>
    const urlpath = {
        select: "{{ route('total_api.index') }}",
    };

    const namaBulan = [
        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];
    let totalRequest, detailRequest;

    $(function () {
        table.page.len(12).draw();
        onInitTable();
        $('#year').on('change', onInitTable);
        $(document).on('click', '.total-month-detail', function () {
            const month = Number(this.dataset.month);
            const year = Number(this.dataset.year);
            if (detailRequest) detailRequest.abort();
            $('#total-detail-title').text(`Rincian iuran ${namaBulan[month - 1]} ${year}`);
            $('#total-detail-status').text('Memuat rincian...').prop('hidden', false);
            $('#total-detail-content').prop('hidden', true);
            $('#total-detail-rows').empty();
            bootstrap.Modal.getOrCreateInstance(document.getElementById('total-detail-modal')).show();
            detailRequest = $.ajax({
                url: urlpath.select, data: { year, month },
                success: function (response) {
                    if (!response.status) { $('#total-detail-status').text('Rincian gagal dimuat. Silakan coba kembali.'); return; }
                    const rows = response.data.rincian;
                    if (!rows.length) { $('#total-detail-status').text('Belum ada data iuran pada bulan ini.'); return; }
                    rows.forEach(row => {
                        const tr = $('<tr>');
                        [row.nama, row.nip, formatToRupiah(row.phdp), formatToRupiah(row.iuran_peserta), formatToRupiah(row.iuran_pemberi_kerja)].forEach((value, index) => {
                            $('<td>').text(value).addClass(index >= 2 ? 'text-end text-nowrap' : 'text-break').appendTo(tr);
                        });
                        $('#total-detail-rows').append(tr);
                    });
                    const summary = response.data.bulan[month - 1];
                    $('#total-detail-peserta').text(formatToRupiah(summary.iuran_peserta));
                    $('#total-detail-pemberi').text(formatToRupiah(summary.iuran_pemberi_kerja));
                    $('#total-detail-status').text(`${rows.length} peserta dengan data iuran pada bulan ini.`);
                    $('#total-detail-content').prop('hidden', false);
                },
                error: function (_, status) { if (status !== 'abort') $('#total-detail-status').text('Rincian gagal dimuat. Silakan coba kembali.'); },
            });
        });
        $('#total-detail-modal').on('hidden.bs.modal', () => { if (detailRequest) detailRequest.abort(); });
    });

    onInitTable = () => {
        if (!document.getElementById('year').reportValidity()) return;
        if (totalRequest) totalRequest.abort();
        table.clear().draw();
        $('#total-data-status').text('Memuat data...');
        $('#total-reconciliation').attr('class', 'alert alert-secondary mb-4').text('Memuat pencocokan dengan menu Iuran...');
        $('#total_iuran_peserta, #total_iuran_pemberi_kerja').text('—');

        totalRequest = $.ajax({
            url: urlpath.select,
            type: 'GET',
            data: { year: $('#year').val() },
            success: function (response) {
                if (!response.status) { $('#total-data-status').text('Data gagal dimuat. Silakan coba kembali.'); $('#total-reconciliation').text('Pencocokan belum tersedia karena data gagal dimuat.'); return; }

                // API mengirim bulan 1 sampai 12; urutan ini dipertahankan Januari–Desember.
                const rows = response.data.bulan.map((data) => [
                    `<button type="button" class="btn btn-sm btn-light-primary total-month-detail" data-month="${data.bulan}" data-year="${response.data.tahun}">${namaBulan[data.bulan - 1]}</button>`,
                    data.has_data ? formatToRupiah(data.iuran_peserta) : '<span class="text-muted">Belum ada data</span>',
                    data.has_data ? formatToRupiah(data.iuran_pemberi_kerja) : '<span class="text-muted">Belum ada data</span>',
                ]);

                table.rows.add(rows);
                table.order([]).draw();
                const filled = response.data.jumlah_bulan_terisi;
                $('#total-data-status').text(`${filled} dari 12 bulan memiliki data. Total hanya menjumlahkan data yang tersedia. Klik bulan untuk melihat rincian peserta.`);
                $('#total_iuran_peserta').text(filled ? formatToRupiah(response.data.total_iuran_peserta) : 'Belum ada data');
                $('#total_iuran_pemberi_kerja').text(filled ? formatToRupiah(response.data.total_iuran_pemberi_kerja) : 'Belum ada data');
                const check = response.data.pencocokan;
                const box = $('#total-reconciliation').empty();
                const labels = { matched: 'Sesuai', different: 'Ada selisih', no_data: 'Belum ada data' };
                box.attr('class', `alert alert-${check.status === 'matched' ? 'success' : (check.status === 'different' ? 'warning' : 'secondary')} mb-4`);
                $('<strong>').text(`Pencocokan Total dan Iuran tahun ${response.data.tahun}: ${labels[check.status]}`).appendTo(box);
                if (check.status !== 'no_data') $('<div>').addClass('mt-1').text(`Menu Total: ${formatToRupiah(check.total_menu_total)} | Menu Iuran: ${formatToRupiah(check.total_menu_iuran)} | Selisih: ${formatToRupiah(check.selisih)}`).appendTo(box);
                $('<div>').addClass('mt-1').text('Membandingkan iuran peserta sebelum pengembangan untuk Dosen, Guru Besar, dan Karyawan pada tahun yang sama.').appendTo(box);
                $('<a>').addClass('d-inline-block mt-2').attr('href', @json(route('iuran.index')) + '?year=' + response.data.tahun).text('Buka menu Iuran').appendTo(box);
            },
            error: function (_, status) { if (status !== 'abort') { $('#total-data-status').text('Data gagal dimuat. Silakan coba kembali.'); $('#total-reconciliation').text('Pencocokan belum tersedia karena data gagal dimuat.'); } },
        });
    };

    formatToRupiah = (value) => new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 2,
    }).format(value || 0);
    (() => {
        const form = document.getElementById('total-export-form');
        const modal = document.getElementById('total-export-modal');
        const frame = document.getElementById('total-export-frame');
        const status = document.getElementById('total-export-status');
        const download = document.getElementById('total-export-download');
        let controller, objectUrl;
        const cleanup = () => {
            controller?.abort();
            frame.removeAttribute('src');
            if (objectUrl) URL.revokeObjectURL(objectUrl);
            objectUrl = null;
        };
        form.addEventListener('submit', async event => {
            event.preventDefault();
            const year = document.getElementById('year');
            if (!year.reportValidity()) return;
            cleanup();
            controller = new AbortController();
            const signal = controller.signal;
            const format = event.submitter?.value || 'pdf';
            const url = new URL(form.action);
            url.search = new URLSearchParams({ year: year.value, format, preview: '1' });
            const downloadUrl = new URL(url);
            downloadUrl.searchParams.delete('preview');
            download.href = downloadUrl;
            download.textContent = format === 'pdf' ? 'Download PDF' : 'Download Excel';
            download.hidden = true;
            frame.hidden = true;
            status.hidden = false;
            status.textContent = 'Memuat preview...';
            document.getElementById('total-export-title').textContent = `Preview ${format === 'pdf' ? 'PDF' : 'Excel'} Total tahun ${year.value}`;
            bootstrap.Modal.getOrCreateInstance(modal).show();
            try {
                const response = await fetch(url, { signal, headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                if (!response.ok || response.redirected || (format === 'pdf' && !response.headers.get('Content-Type')?.includes('application/pdf'))) throw new Error('Preview gagal');
                const blob = await response.blob();
                if (signal.aborted) return;
                objectUrl = URL.createObjectURL(blob);
                frame.src = objectUrl;
                frame.hidden = false;
                status.hidden = true;
                download.hidden = false;
            } catch (error) {
                if (!signal.aborted) status.textContent = 'Preview gagal dimuat. Tutup popup dan coba kembali.';
            }
        });
        modal.addEventListener('hidden.bs.modal', () => { cleanup(); download.hidden = true; });
    })();
</script>
