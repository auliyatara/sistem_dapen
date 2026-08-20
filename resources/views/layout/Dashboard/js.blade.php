<script>
    var urlpath ={
        show: "{{ route('dashboard_api.index') }}",
        show_informasi: "{{ route('informasi_tambahan_api.index') }}",
        peserta_statistik: "{{ route('dashboard_api.peserta_statistik') }}",
    }  

    $(function(){
        onDetail();
        onInformasiUmum();
        onStatistikPeserta();
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
                                $("#profile_image").attr("src", `{{ asset('storage/app/public/user_foto/${value}') }}`);
                                $("#header_image").attr("src", `{{ asset('storage/app/public/user_foto/${data.user_foto}') }}`);
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
                        $('#dashboard_informasi_list').append(`
                            <div class="rounded-3 bg-white p-4 mb-3 shadow-sm">
                                <h4 class="mb-2" style="color: #181c32;">${v.informasi_judul}</h4>
                                <p class="mb-2" style="color: #3f4254;">${v.informasi_deskripsi}</p>
                                <div class="fw-bold fs-7" style="color: #009ef7;">${formatDate(v.informasi_tanggal)}</div>
                                </div>
                        `)
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

        chart.seriesContainer.children.push(am5.Label.new(root, {
            text: `${data.reduce((total, item) => total + Number(item.total), 0)}\nPeserta Aktif`,
            textAlign: 'center',
            centerX: am5.p50,
            centerY: am5.p50,
            x: am5.p50,
            y: am5.p50,
            fontSize: 20,
            fontWeight: '600',
        }));
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
        chart.seriesContainer.children.push(am5.Label.new(root, {
            text: `${totalPensiunan}\nPensiunan`, textAlign: 'center', centerX: am5.p50,
            centerY: am5.p50, x: am5.p50, y: am5.p50, fontSize: 22, fontWeight: '700',
        }));
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
        chart.seriesContainer.children.push(am5.Label.new(root, {
            text: `${total}\nTotal`, textAlign: 'center', centerX: am5.p50,
            centerY: am5.p50, x: am5.p50, y: am5.p50, fontSize: 22, fontWeight: '700',
        }));
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
