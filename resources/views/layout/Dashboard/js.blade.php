<script>
    var urlpath ={
        show: "{{ route('dashboard_api.index') }}",
        show_informasi: "{{ route('informasi_tambahan_api.index', ['active_only' => 1]) }}",
        peserta_statistik: "{{ route('dashboard_api.peserta_statistik') }}",
    }  

    $(function(){
        const dateFormatter = new Intl.DateTimeFormat('id-ID', {
            timeZone: 'Asia/Jakarta', day: '2-digit', month: 'long', year: 'numeric',
        });
        const clockFormatter = new Intl.DateTimeFormat('en-GB', {
            timeZone: 'Asia/Jakarta', hour: '2-digit', minute: '2-digit', second: '2-digit', hourCycle: 'h23',
        });
        let informasiDate;
        const updateDashboardClock = () => {
            const now = new Date();
            const today = dateFormatter.format(now);
            if (informasiDate && informasiDate !== today) onInformasiUmum();
            informasiDate = today;
            $('#dashboard-live-date').text(today);
            $('#dashboard-live-clock').text(clockFormatter.format(now)).attr('datetime', now.toISOString());
        };
        updateDashboardClock();
        setInterval(updateDashboardClock, 1000);
        onDetail();
        onInformasiUmum();
        onStatistikPeserta();
        loadDashboardIuran();
        $('#dashboard-year').on('change', loadDashboardIuran);
    })

    let dashboardIuranRequest;
    const dashboardRupiah = value => new Intl.NumberFormat('id-ID', {
        style: 'currency', currency: 'IDR', maximumFractionDigits: 2,
    }).format(Number(value) || 0);

    function loadDashboardIuran() {
        const input = $('#dashboard-year')[0];
        if (!input.value || !input.checkValidity()) { input.reportValidity(); return; }
        if (dashboardIuranRequest) dashboardIuranRequest.abort();
        $('#dashboard-total').text('—');
        $('.dashboard-year-label').text(input.value);
        $('#dashboard-monthly-chart, #dashboard-monthly-table, #dashboard-chart-caption').empty();
        $('#dashboard-chart-status').text('Memuat iuran...');
        dashboardIuranRequest = $.ajax({
            url: "{{ route('total_api.index') }}",
            data: { year: input.value },
            success: function (response) {
                if (!response.status) { $('#dashboard-chart-status').text('Iuran belum dapat dimuat. Silakan pilih tahun kembali.'); return; }
                const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
                const rows = response.data.bulan;
                const maximum = Math.max(...rows.map(row => Number(row.iuran_peserta)), 0);
                $('#dashboard-total').text(dashboardRupiah(response.data.total_iuran_peserta));
                $('#dashboard-chart-status').text(maximum ? 'Tertinggi: ' + dashboardRupiah(maximum) : 'Belum ada nominal iuran tercatat pada tahun ini.');
                rows.forEach(row => {
                    const label = months[row.bulan - 1];
                    const description = label + ': ' + dashboardRupiah(row.iuran_peserta);
                    const column = $('<div class="month-column">');
                    $('<button type="button" class="month-bar">').css('height', maximum ? (Number(row.iuran_peserta) / maximum * 185) + 'px' : '3px')
                        .attr({ title: description, 'aria-label': description })
                        .on('mouseenter focus click', () => $('#dashboard-chart-caption').text(description)).appendTo(column);
                    $('<span class="month-name">').text(label).appendTo(column);
                    column.appendTo('#dashboard-monthly-chart');
                    const tableRow = $('<tr>');
                    $('<td>').text(label).appendTo(tableRow);
                    $('<td class="text-end">').text(dashboardRupiah(row.iuran_peserta)).appendTo(tableRow);
                    tableRow.appendTo('#dashboard-monthly-table');
                });
                $('#dashboard-chart-caption').text('Arahkan kursor atau pilih batang untuk melihat nominal.');
            },
            error: function (xhr, status) {
                if (status !== 'abort') $('#dashboard-chart-status').text('Gagal memuat iuran. Silakan pilih tahun kembali.');
            }
        });
    }

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
                                $("#profile_image").attr("src", "{{ route('dashboard_api.profile_photo') }}");
                                
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
        $('#dashboard_informasi_list').html('<div class="text-muted">Informasi belum tersedia.</div>')
        $.ajax({
            url: urlpath.show_informasi,
            type: 'GET',
            success: function(response){
                if(response.status == true && response.data.length >0){
                    $('#dashboard_informasi_list').html('')
                    $.each( response.data, function( k, v ){
                        const item = $('<article class="notice-item">');
                        const date = String(v.informasi_tanggal || '').match(/^(\d{4})-(\d{2})-(\d{2})(?:$|[T ])/);
                        const months = ['JAN', 'FEB', 'MAR', 'APR', 'MEI', 'JUN', 'JUL', 'AGU', 'SEP', 'OKT', 'NOV', 'DES'];
                        if (date && months[Number(date[2]) - 1]) {
                            const calendar = $('<time class="notice-calendar">').attr({
                                datetime: date[0].slice(0, 10),
                                'aria-label': formatDate(date[0].slice(0, 10)),
                            });
                            $('<span class="notice-month" aria-hidden="true">').text(months[Number(date[2]) - 1]).appendTo(calendar);
                            $('<span class="notice-day" aria-hidden="true">').text(Number(date[3])).appendTo(calendar);
                            $('<span class="notice-year" aria-hidden="true">').text(date[1]).appendTo(calendar);
                            calendar.appendTo(item);
                        }
                        const content = $('<div class="notice-content">').appendTo(item);
                        $('<h3 class="notice-title">').text(v.informasi_judul || '').appendTo(content);
                        $('<p class="notice-description">').text(v.informasi_deskripsi || '').appendTo(content);
                        item.appendTo('#dashboard_informasi_list');
                    })
                } 
            }
        })
    }

    onStatistikPeserta = () => {
        $.ajax({
            url: urlpath.peserta_statistik,
            type: 'GET',
            success: function (response) {
                if (!response.status) return;

                const statistik = response.data;
                $('#dashboard-active').text(Number(statistik.peserta.total).toLocaleString('id-ID'));
                $('#dashboard-retired').text(Number(statistik.pensiunan.total_semua).toLocaleString('id-ID'));
                const urutan = ['Guru Besar', 'Dosen', 'Karyawan'];
                const jabatan = [...statistik.jabatan].sort((a, b) => urutan.indexOf(a.jabatan) - urutan.indexOf(b.jabatan));
                renderChartPeserta(jabatan);
                renderLegend('#legend_peserta_dashboard', jabatan, 'jabatan', ['#F1416C', '#F783A5', '#FBC5D3']);
                renderChartPensiunan(statistik.pensiunan.kategori, statistik.pensiunan.total);
                renderLegend('#legend_pensiunan_dashboard', statistik.pensiunan.kategori, 'kategori', ['#7239EA', '#9B6BDB', '#C5A7F3', '#E3D7FA']);
                renderChartPensiunLain(statistik.pensiun_lain.kategori, statistik.pensiun_lain.total);
                renderLegend('#legend_pensiun_lain_dashboard', statistik.pensiun_lain.kategori, 'kategori', ['#D9214E', '#E85D75', '#F5A1AE']);
            }
        });
    }

    renderChartPeserta = (data) => {
        am5.array.each(am5.registry.rootElements, function (root) {
            if (root.dom.id === 'chart_peserta_dashboard') root.dispose();
        });

        const root = am5.Root.new('chart_peserta_dashboard');
        root._logo.dispose();
        root.setThemes([am5themes_Animated.new(root)]);

        const chart = root.container.children.push(am5percent.PieChart.new(root, {
            innerRadius: am5.percent(55),
            layout: root.verticalLayout,
        }));
        const series = chart.series.push(am5percent.PieSeries.new(root, {
            valueField: 'total',
            categoryField: 'jabatan',
            alignLabels: false,
        }));

        series.get('colors').set('colors', [
            am5.color(0xF1416C), am5.color(0xF783A5), am5.color(0xFBC5D3),
        ]);
        series.labels.template.set('visible', false);
        series.ticks.template.set('visible', false);
        series.data.setAll(data);

        series.appear(800, 100);
    }

    renderChartPensiunan = (data, totalPensiunan) => {
        am5.array.each(am5.registry.rootElements, function (root) {
            if (root.dom.id === 'chart_pensiunan_dashboard') root.dispose();
        });

        const root = am5.Root.new('chart_pensiunan_dashboard');
        root._logo.dispose();
        root.setThemes([am5themes_Animated.new(root)]);
        const chart = root.container.children.push(am5percent.PieChart.new(root, {
            innerRadius: am5.percent(55), layout: root.verticalLayout,
        }));
        const series = chart.series.push(am5percent.PieSeries.new(root, {
            valueField: 'total', categoryField: 'kategori', alignLabels: false,
        }));

        series.get('colors').set('colors', [
            am5.color(0x7239EA), am5.color(0x9B6BDB), am5.color(0xC5A7F3), am5.color(0xE3D7FA),
        ]);
        series.labels.template.set('visible', false);
        series.ticks.template.set('visible', false);
        series.data.setAll(data);
        series.appear(800, 100);
    }

    renderLegend = (target, data, field, colors) => {
        $(target).html(data.map((item, index) => `
            <div class="d-flex align-items-center text-nowrap">
                <span class="rounded-circle me-2" style="width:12px;height:12px;background:${colors[index]};"></span>
                <span class="fw-semibold">${item[field]}: ${item.total} (${item.persentase}%)</span>
            </div>
        `).join(''));
    }

    renderChartPensiunLain = (data, total) => {
        am5.array.each(am5.registry.rootElements, function (root) {
            if (root.dom.id === 'chart_pensiun_lain_dashboard') root.dispose();
        });

        const root = am5.Root.new('chart_pensiun_lain_dashboard');
        root._logo.dispose();
        root.setThemes([am5themes_Animated.new(root)]);
        const chart = root.container.children.push(am5percent.PieChart.new(root, {
            innerRadius: am5.percent(55), layout: root.verticalLayout,
        }));
        const series = chart.series.push(am5percent.PieSeries.new(root, {
            valueField: 'total', categoryField: 'kategori', alignLabels: false,
        }));

        series.get('colors').set('colors', [am5.color(0xD9214E), am5.color(0xE85D75), am5.color(0xF5A1AE)]);
        series.labels.template.set('visible', false);
        series.ticks.template.set('visible', false);
        series.data.setAll(data);
        series.appear(800, 100);
    }

    formatDate = (inputDate)=> {
        const parts = inputDate.split('-');
        if (parts.length === 3) {
            const year = parts[0];
            const month = Number(parts[1]);
            const day = Number(parts[2]);
            const bulan = [
                'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
            ];
            return bulan[month - 1] ? `${day} ${bulan[month - 1]} ${year}` : inputDate;
        }
        return inputDate;
    }

</script>
