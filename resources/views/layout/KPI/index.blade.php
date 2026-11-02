@extends('master.app')

@section('content')
@php($kpiTab = request('tab', request()->hasAny(['period_type', 'period_id', 'search', 'page']) && !request()->has('program_semester') ? 'tasks' : 'programs'))
<style>
    .kpi-print-button {background:#795548!important;border:1px solid #795548!important;color:#fff!important;font-weight:700;padding:12px 20px;border-radius:8px}
    .kpi-print-button:hover {background:#684638!important;border-color:#684638!important}
    .kpi-print-button:active,.kpi-print-button:focus {background:#d7b49e!important;border-color:#d7b49e!important;color:#40291e!important}
    .kpi-print-button:focus-visible {outline:3px solid #795548;outline-offset:3px}
    .kpi-summary-total {background:#e8f1ff;border-color:#c8dcff!important;color:#244776}
    .kpi-summary-completed {background:#e8f7ee;border-color:#c4e8d1!important;color:#245b3c}
    .kpi-summary-in_progress {background:#fff6dc;border-color:#f4e3ac!important;color:#745714}
    .kpi-summary-not_started {background:#fce7f0;border-color:#f3c8db!important;color:#813751}
    .kpi-summary-label {color:inherit;font-weight:600}
    .kpi-history-button,.kpi-own-badge {background:#d60075;color:#fff;font-weight:700;border:1px solid #d60075}
    .kpi-history-button:hover,.kpi-history-button:active,.kpi-history-button:focus {background:#f9a8d4;color:#831843;border-color:#f9a8d4}
    .kpi-history-button:focus-visible {outline:3px solid #d60075;outline-offset:3px}
    .kpi-tabs {gap:8px;border-bottom:0}
    .kpi-tabs .nav-link {background:#102a43;color:#fff;font-weight:700;border:1px solid #102a43;border-radius:8px;padding:12px 20px}
    .kpi-tabs .nav-link:hover {background:#193e60;color:#fff;border-color:#193e60}
    .kpi-tabs .nav-link.active,.kpi-tabs .nav-link.active:hover {background:#93c5fd;color:#102a43;border-color:#93c5fd;font-weight:700}
    .kpi-tabs .nav-link:focus-visible {outline:3px solid #3b82f6;outline-offset:3px}
    .kpi-task-table thead th {font-weight:700;font-size:1.1rem;text-align:center;vertical-align:middle;color:#087e8b;background:#e0f7fa;padding:14px 12px;border-bottom:2px solid #34b8c5;white-space:nowrap}
    .kpi-task-progress {height:18px;background:#e4eaf2;border-radius:9px;overflow:hidden;margin-bottom:20px}
    .kpi-task-progress-fill {height:100%;background:#3b82f6;border-radius:9px;transition:width .25s ease}
    .kpi-task-progress-fill.is-complete {background:#16a34a}
    .kpi-action-summary {list-style:none;display:flex;align-items:center;gap:8px}
    .kpi-action-summary::-webkit-details-marker {display:none}
    .kpi-action-summary::marker {content:''}
    .kpi-action-icon {width:18px;height:18px;flex-shrink:0}
</style>
<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-fluid">
        <div class="card shadow-sm">
            <div class="card-header border-0 pt-5 align-items-center gap-4">
                <div class="card-title flex-column align-items-start">
                    <h2 class="fw-bold mb-2">KPI</h2>
                    <span class="text-muted fs-6">Indikator Kinerja Utama</span>
                </div>
                @if($roles->isNotEmpty())
                    <form action="{{ route('kpi.index') }}" method="GET" class="d-flex align-items-center flex-wrap gap-3">
                        <input type="hidden" name="year" value="{{ $selectedYear }}">
                        <input type="hidden" name="period_type" value="{{ $selectedType }}">
                        <input type="hidden" name="period_number" value="{{ $selectedNumber }}">
                        <input type="hidden" name="search" value="{{ $search }}">
                        <input type="hidden" name="status" value="{{ $status }}">
                        <input type="hidden" name="tab" value="{{ $kpiTab }}">
<label for="kpi-role" class="fw-semibold">Bidang / Hak Akses</label>
                        <select id="kpi-role" name="role_id" class="form-select form-select-solid w-auto mw-100" onchange="this.form.submit()">
                            @foreach($roles as $role)
                                <option value="{{ $role->role_id }}" @selected($selectedRole->role_id === $role->role_id)>{{ $role->role_name }}</option>
                            @endforeach
                        </select>
                        <noscript><button type="submit" class="btn btn-primary">Tampilkan</button></noscript>
                    </form>
                @endif
            </div>
            <div class="card-body pt-4">
                @if(session('success'))<div class="alert alert-success" role="status">{{ session('success') }}</div>@endif
                @if($errors->any())
                    <div class="alert alert-danger" role="alert"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
                @endif
                @if($selectedRole)
                    <div class="d-flex align-items-center flex-wrap gap-3 border-top pt-6">
                        <h3 class="mb-0">KPI {{ $selectedRole->role_name }}</h3>
                        <a class="btn btn-sm kpi-history-button" href="{{ route('kpi.history', ['role_id' => $selectedRole->role_id, 'year' => $selectedYear, 'period_type' => $selectedType, 'period_number' => $selectedNumber]) }}">Riwayat bidang</a>
                        <span class="badge {{ $canManage ? 'kpi-own-badge' : 'badge-light-secondary' }}">{{ $canManage ? 'Bidang Anda' : 'Hanya lihat' }}</span>
                    </div>
                    
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-4 mt-5 mb-5"><nav class="nav nav-tabs kpi-tabs my-0" aria-label="Jenis KPI">
                        <a class="nav-link {{ $kpiTab === 'programs' ? 'active' : '' }}" href="{{ route('kpi.index', array_merge(request()->except(['page', 'tab']), ['tab' => 'programs'])) }}">Program Kerja</a>
                        <a class="nav-link {{ $kpiTab === 'tasks' ? 'active' : '' }}" href="{{ route('kpi.index', array_merge(request()->except(['page', 'tab']), ['tab' => 'tasks'])) }}">Semua Kinerja</a>
                        <a class="nav-link {{ $kpiTab === 'achievement' ? 'active' : '' }}" href="{{ route('kpi.index', array_merge(request()->except(['page', 'tab']), ['tab' => 'achievement'])) }}">Pencapaian KPI {{ $selectedYear }}</a>
                    </nav>
<div class="ms-auto">
@if($kpiTab === 'tasks')
<form id="kpi-export-form" method="GET" action="{{ route('kpi.export') }}" class="d-flex flex-wrap align-items-center gap-3 mb-0">
                                <input type="hidden" name="preview" value="1">
                                <input type="hidden" name="role_id" value="{{ $selectedRole->role_id }}">
                                <input type="hidden" name="year" value="{{ $selectedYear }}">
                                <input type="hidden" name="period_type" value="{{ $selectedType }}">
                                <input type="hidden" name="period_number" value="{{ $selectedNumber }}">
                                <input type="hidden" name="search" value="{{ $search }}">
                                <input type="hidden" name="status" value="{{ $status }}">
                                <label for="kpi-export-scope" class="fw-semibold">Ekspor laporan</label>
                                <select id="kpi-export-scope" name="scope" class="form-select w-auto"><option value="all">Semua tugas periode ini</option><option value="filtered">Hasil filter</option></select>
                                <button class="btn kpi-print-button" name="format" value="xlsx">Excel</button>
                                <button class="btn kpi-print-button" name="format" value="pdf">PDF</button>
                            </form>
@else
@include('layout.KPI.annual-export', ['reportKind' => $kpiTab === 'achievement' ? 'achievement' : 'programs'])
@endif
</div></div>
                    <div @if($kpiTab !== 'programs') hidden @endif>
                    @include('layout.KPI.program-panel')
                    </div>
                    <div @if($kpiTab !== 'achievement') hidden @endif>
                        @include('layout.KPI.achievement')
                    </div>
                    <div @if($kpiTab !== 'tasks') hidden @endif>
                    <form method="GET" action="{{ route('kpi.index') }}" class="d-flex flex-wrap align-items-center gap-4 mt-6">
                        <input type="hidden" name="tab" value="tasks">
                        <input type="hidden" name="role_id" value="{{ $selectedRole->role_id }}">
                        <input type="hidden" name="search" value="{{ $search }}">
                        <input type="hidden" name="status" value="{{ $status }}">
                        <label for="kpi-year" class="fw-semibold mb-0">Tahun</label>
                        <input id="kpi-year" name="year" type="number" min="1900" max="2099" step="1" required value="{{ $selectedYear }}" class="form-control form-control-solid" style="width:120px" onchange="if(this.form.elements.date) this.form.elements.date.disabled=true;if(this.form.reportValidity()) this.form.submit()">
                            <label for="kpi-period" class="fw-semibold mb-0">Periode</label>
                            <select name="period_type" id="kpi-period" class="form-select form-select-solid w-auto" onchange="this.form.elements.period_number.value=1;if(this.form.reportValidity()) this.form.submit()">
                                @foreach(['harian' => 'Harian', 'bulanan' => 'Bulanan', 'triwulanan' => 'Triwulanan', 'semesteran' => 'Semesteran', 'tahunan' => 'Tahunan'] as $value => $label)
                                    <option value="{{ $value }}" @selected($selectedType === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($selectedType === 'harian')
                                <label for="kpi-date" class="fw-semibold mb-0">Tanggal</label>
                                <input type="date" id="kpi-date" name="date" value="{{ \App\Models\KpiPeriod::dateRange('harian', $selectedYear, $selectedNumber)[0] }}" class="form-control form-control-solid w-auto" required onchange="if(this.form.reportValidity()) this.form.submit()">
                                <input type="hidden" name="period_number" value="1">
                            @elseif($selectedType !== 'tahunan')
                                <label for="kpi-period-number" class="fw-semibold mb-0">{{ $selectedType === 'bulanan' ? 'Bulan' : ($selectedType === 'triwulanan' ? 'Triwulan' : 'Semester') }}</label>
                                <select id="kpi-period-number" name="period_number" class="form-select form-select-solid w-auto" onchange="if(this.form.reportValidity()) this.form.submit()">
                                    @for($number = 1; $number <= 12 / \App\Models\KpiPeriod::DURATIONS[$selectedType]; $number++)
                                        <option value="{{ $number }}" @selected($selectedNumber === $number)>{{ $selectedType === 'bulanan' ? \App\Models\KpiPeriod::MONTHS[$number - 1] : $number }}</option>
                                    @endfor
                                </select>
                            @else
                                <input type="hidden" name="period_number" value="1">
                            @endif
                            <noscript><button class="btn btn-primary">Tampilkan</button></noscript>
@if($canManage)<button type="button" class="btn btn-primary ms-auto" data-bs-toggle="modal" data-bs-target="#kpi-add-modal"><span aria-hidden="true">+ </span>Tambah Data</button>@endif
                        </form>
                    @if($selectedPeriod)
                        <p class="text-muted mt-3">{{ $selectedPeriod->starts_at->locale('id')->translatedFormat('j F Y') }} – {{ $selectedPeriod->ends_at->locale('id')->translatedFormat('j F Y') }}</p>

                        <div class="mt-6">
                            <div class="row g-3 mb-6">
                                @foreach(['total' => 'Total tugas', 'completed' => 'Selesai', 'in_progress' => 'Sedang dikerjakan', 'not_started' => 'Belum dimulai'] as $key => $label)
                                    <div class="col-6 col-lg-3"><div class="border rounded p-4 h-100 kpi-summary-{{ $key }}"><div class="kpi-summary-label mb-2">{{ $label }}</div><div class="fs-2 fw-bold">{{ $summary[$key] }}</div></div></div>
                                @endforeach
                            </div>
                            <form method="GET" action="{{ route('kpi.index') }}" class="d-flex flex-wrap align-items-end gap-3 mb-5">
                                <input type="hidden" name="role_id" value="{{ $selectedRole->role_id }}">
                                <input type="hidden" name="year" value="{{ $selectedYear }}">
                                <input type="hidden" name="period_type" value="{{ $selectedType }}">
                                <input type="hidden" name="period_number" value="{{ $selectedNumber }}">
                                <input type="hidden" name="tab" value="tasks">
<div class="flex-grow-1"><label for="kpi-search" class="form-label">Cari tugas</label><input id="kpi-search" type="search" name="search" class="form-control" maxlength="255" placeholder="Cari tugas atau keterangan..." value="{{ $search }}"></div>
                                <div><label for="kpi-status" class="form-label">Status</label><select id="kpi-status" name="status" class="form-select"><option value="">Semua status</option>@foreach(['not_started' => 'Belum dimulai', 'in_progress' => 'Sedang dikerjakan', 'completed' => 'Selesai'] as $value => $label)<option value="{{ $value }}" @selected($status === $value)>{{ $label }}</option>@endforeach</select></div>
                                <button type="submit" class="btn btn-primary">Tampilkan</button>
                                @if($search !== '' || $status !== '')<a class="btn btn-light" href="{{ route('kpi.index', ['role_id' => $selectedRole->role_id, 'year' => $selectedYear, 'period_type' => $selectedType, 'period_number' => $selectedNumber, 'tab' => 'tasks']) }}">Reset</a>@endif
                            </form>
                            <h4>Tugas · {{ $selectedPeriod->label }}</h4>
                            
                            @include('layout.KPI.task-table')
                        </div>
                    @else
                    <div class="py-10 text-center">
                        <div class="symbol symbol-60px mb-5"><span class="symbol-label bg-light-primary text-primary fs-1" aria-hidden="true">&#9678;</span></div>
                        <h4 class="mb-3">Belum ada periode KPI</h4>
                        <p class="text-muted mb-0">Periode yang dipilih pada tahun {{ $selectedYear }} belum ditambahkan. {{ $canManage ? 'Pilih Tambah Data dan tentukan periodenya.' : '' }}</p>
                    </div>
                    @endif
                    </div>
                @else
                    <p class="text-muted text-center py-10 mb-0">Belum ada hak akses aktif untuk ditampilkan.</p>
                @endif
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="kpi-evidence-modal" tabindex="-1" aria-labelledby="kpi-evidence-title" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered"><div class="modal-content">
        <div class="modal-header"><h3 class="modal-title text-break" id="kpi-evidence-title">Bukti tugas</h3><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button></div>
        <div class="modal-body text-center"><p id="kpi-evidence-status" role="status">Memuat preview...</p><img id="kpi-evidence-image" alt="Bukti tugas" class="img-fluid" style="max-height:70vh" hidden><iframe id="kpi-evidence-pdf" title="Preview PDF bukti tugas" class="w-100 border rounded" style="height:70vh" hidden></iframe></div>
    </div></div>
</div>
@if($canManage && $selectedRole)
<div class="modal fade" id="kpi-add-modal" tabindex="-1" aria-labelledby="kpi-add-title" aria-hidden="true">
<div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable"><div class="modal-content">
<div class="modal-header"><h3 id="kpi-add-title">Tambah Data KPI</h3><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button></div>
<div class="modal-body">                            <form action="{{ route('kpi.tasks.storeForRole', $selectedRole->role_id) }}" method="POST" enctype="multipart/form-data" class="mt-5">
                                @csrf
                                <div class="row g-4">
                                    <div class="col-md-4">
                                        <label for="period-type" class="form-label required">Jenis periode</label>
                                        <select id="period-type" name="type" class="form-select" required>
                                            @foreach(['harian' => 'Harian', 'bulanan' => 'Bulanan', 'triwulanan' => 'Triwulanan', 'semesteran' => 'Semesteran', 'tahunan' => 'Tahunan'] as $value => $label)
                                                <option value="{{ $value }}" @selected(old('type', $selectedType) === $value)>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="period-year" class="form-label required">Tahun</label>
                                        <input id="period-year" name="year" type="number" class="form-control" min="1900" max="2099" value="{{ old('year', $selectedYear) }}" required>
                                    </div>
                                    <div class="col-md-5">
                                        <label for="period-number" class="form-label required">Periode</label>
                                        <select id="period-number" name="number" class="form-select" required></select>
                                        <input type="date" id="period-date" name="date" class="form-control" value="{{ old('date', now('Asia/Jakarta')->toDateString()) }}" hidden disabled>
                                    </div>
                                </div>
                                <p class="text-muted mt-4">Periode mengikuti kalender: bulanan 1 bulan, triwulanan 3 bulan, semesteran 6 bulan, dan tahunan Januari–Desember.</p>
                                @include('layout.KPI.task-form', ['task' => null, 'formKey' => 'new'])
                                <button type="submit" class="btn btn-primary mt-4">Simpan Tugas</button>
                            </form></div></div></div></div>
@endif
@include('layout.KPI.edit-modals')
<div class="modal fade" id="kpi-export-modal" tabindex="-1" aria-labelledby="kpi-export-title" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered"><div class="modal-content">
        <div class="modal-header"><h3 class="modal-title" id="kpi-export-title">Preview laporan KPI</h3><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button></div>
        <div class="modal-body"><p id="kpi-export-status" role="status">Memuat preview...</p><iframe id="kpi-export-preview" title="Preview laporan KPI" class="w-100 border rounded" style="height:65vh" hidden></iframe></div>
        <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button><a id="kpi-export-download" class="btn btn-primary" hidden>Download</a></div>
    </div></div>
</div>
@endsection

@push('javascript')
<script>
    document.querySelectorAll('.kpi-program-select').forEach(select => {
        const type = select.form.querySelector('[name=type]');
        const sync = () => {
            const linked = Boolean(select.value);
            Array.from(type.options).forEach(option => option.disabled = linked && option.value !== 'tahunan');
            if (linked && type.value !== 'tahunan') { type.value = 'tahunan'; type.dispatchEvent(new Event('change')); }
        };
        select.addEventListener('change', sync);
        select.form.addEventListener('submit', sync);
        // Wait until period option builders below have been initialized.
        queueMicrotask(sync);
    });
    document.getElementById('kpi-add-modal')?.addEventListener('show.bs.modal', event => {
        if (!event.relatedTarget) return;
        const select = document.getElementById('new-program');
        select.value = event.relatedTarget.dataset.kpiProgram || '';
        document.getElementById('kpi-add-title').textContent = select.value ? 'Tambah Pelaksanaan Program Kerja' : 'Tambah Tugas KPI';
        select.dispatchEvent(new Event('change'));
        if (select.value) {
            document.getElementById('period-year').value = @json($selectedYear);
            document.getElementById('period-year').dispatchEvent(new Event('change'));
            document.getElementById('period-number').value = '1';
        }
    });
    (() => {
        const forms = document.querySelectorAll('#kpi-export-form, .kpi-annual-export');
        if (!forms.length) return;
        const modal = document.getElementById('kpi-export-modal');
        const frame = document.getElementById('kpi-export-preview');
        const status = document.getElementById('kpi-export-status');
        const download = document.getElementById('kpi-export-download');
        let controller, objectUrl;
        const cleanup = () => {
            controller?.abort();
            frame.removeAttribute('src');
            if (objectUrl) URL.revokeObjectURL(objectUrl);
            objectUrl = null;
        };
        forms.forEach(form => form.addEventListener('submit', async event => {
            event.preventDefault();
            cleanup();
            controller = new AbortController();
            const signal = controller.signal;
            const format = event.submitter?.value || 'pdf';
            const url = new URL(form.action);
            url.search = new URLSearchParams(new FormData(form)).toString();
            url.searchParams.set('format', format);
            const downloadUrl = new URL(url);
            downloadUrl.searchParams.delete('preview');
            download.href = downloadUrl.toString();
            download.textContent = format === 'pdf' ? 'Download PDF' : 'Download Excel';
            download.hidden = true;
            frame.hidden = true;
            status.hidden = false;
            status.textContent = 'Memuat preview...';
            document.getElementById('kpi-export-title').textContent = format === 'pdf' ? 'Preview PDF' : 'Preview Excel';
            bootstrap.Modal.getOrCreateInstance(modal).show();
            try {
                const response = await fetch(url, { signal, headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                if (!response.ok || response.redirected) throw new Error('Preview gagal dimuat');
                if (format === 'pdf' && !response.headers.get('Content-Type')?.includes('application/pdf')) throw new Error('Preview gagal dimuat');
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
        }));
        modal.addEventListener('hidden.bs.modal', () => { cleanup(); download.hidden = true; });
    })();
    @if($errors->any() && $canManage)
    (() => {
        const taskId = @json(old('task_id'));
        const modal = document.getElementById(taskId ? 'kpi-edit-' + taskId : 'kpi-add-modal');
        if (!modal) return;
        const message = document.createElement('div');
        message.className = 'alert alert-danger';
        message.setAttribute('role', 'alert');
        message.textContent = @json(implode(' ', $errors->all()));
        modal.querySelector('.modal-body').prepend(message);
        bootstrap.Modal.getOrCreateInstance(modal).show();
    })();
    @endif
    const syncKpiDailyDate = (date, year) => {
        const selectedYear = Number(year.value);
        if (!Number.isInteger(selectedYear) || selectedYear < 1900 || selectedYear > 2099) return;
        if (date.value && Number(date.value.slice(0, 4)) !== selectedYear) {
            const month = Number(date.value.slice(5, 7));
            const day = Number(date.value.slice(8, 10));
            const lastDay = new Date(selectedYear, month, 0).getDate();
            date.value = String(selectedYear) + '-' + String(month).padStart(2, '0') + '-' + String(Math.min(day, lastDay)).padStart(2, '0');
        }
    };
    document.querySelectorAll('.kpi-edit-period').forEach(group => {
        const type = group.querySelector('[name=type]');
        const year = group.querySelector('[name=year]');
        const number = group.querySelector('[name=number]');
        const date = group.querySelector('[name=date]');
        const labels = {
            bulanan: @json(\App\Models\KpiPeriod::MONTHS),
            triwulanan: ['Triwulan 1', 'Triwulan 2', 'Triwulan 3', 'Triwulan 4'],
            semesteran: ['Semester 1', 'Semester 2'], tahunan: ['Tahunan'],
        };
        const update = selected => {
            const daily = type.value === 'harian';
            number.disabled = number.hidden = daily;
            date.disabled = date.hidden = !daily;
            date.required = daily;
            date.min = year.value + '-01-01'; date.max = year.value + '-12-31';
            if (daily) syncKpiDailyDate(date, year);
            number.replaceChildren();
            (labels[type.value] || []).forEach((label, index) => number.add(new Option(label, index + 1, false, index + 1 === Number(selected))));
        };
        update(group.dataset.number);
        type.addEventListener('change', () => update(1));
        year.addEventListener('change', () => update(number.value));
    });
    document.addEventListener('click', event => {
        if (event.target.closest('[data-add-link]')) {
            const rows = event.target.closest('.kpi-links').querySelector('.kpi-link-rows');
            if (rows.children.length >= 20) return;
            const row = rows.firstElementChild.cloneNode(true);
            row.querySelector('input').value = '';
            rows.appendChild(row);
            row.querySelector('input').focus();
        }
        if (event.target.closest('[data-remove-link]')) {
            const row = event.target.closest('[data-remove-link]').parentElement;
            if (row.parentElement.children.length === 1) row.querySelector('input').value = '';
            else row.remove();
        }
    });
    (() => {
        const modal = document.getElementById('kpi-evidence-modal');
        const img = document.getElementById('kpi-evidence-image');
        const pdf = document.getElementById('kpi-evidence-pdf');
        const status = document.getElementById('kpi-evidence-status');
        let controller, objectUrl;
        const cleanup = () => {
            controller?.abort();
            img.onload = img.onerror = null;
            img.removeAttribute('src');
            pdf.removeAttribute('src');
            img.hidden = pdf.hidden = true;
            if (objectUrl) URL.revokeObjectURL(objectUrl);
            objectUrl = null;
        };
        modal.addEventListener('show.bs.modal', async event => {
            const trigger = event.relatedTarget;
            if (!trigger) return;
            cleanup();
            controller = new AbortController();
            const signal = controller.signal;
            status.hidden = false;
            status.textContent = 'Memuat preview...';
            document.getElementById('kpi-evidence-title').textContent = trigger.dataset.evidenceName || 'Bukti tugas';
            try {
                const response = await fetch(trigger.dataset.evidenceUrl, { signal, headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                const mime = (response.headers.get('Content-Type') || '').split(';')[0];
                if (!response.ok || response.redirected || !['application/pdf', 'image/jpeg', 'image/png'].includes(mime)) throw new Error('File tidak dapat dipreview');
                const blob = await response.blob();
                if (signal.aborted) return;
                objectUrl = URL.createObjectURL(blob);
                if (mime === 'application/pdf') {
                    pdf.src = objectUrl;
                    pdf.hidden = false;
                    status.hidden = true;
                } else {
                    img.onload = () => { if (!signal.aborted) { img.hidden = false; status.hidden = true; } };
                    img.onerror = () => { if (!signal.aborted) status.textContent = 'Gambar gagal dimuat. Tutup popup dan coba kembali.'; };
                    img.src = objectUrl;
                }
            } catch (error) {
                if (!signal.aborted) status.textContent = 'Preview gagal dimuat. Tutup popup dan coba kembali.';
            }
        });
        modal.addEventListener('hidden.bs.modal', cleanup);
    })();
    (() => {
        const type = document.getElementById('period-type');
        if (!type) return;
        const number = document.getElementById('period-number');
        const date = document.getElementById('period-date');
        const year = document.getElementById('period-year');
        const choices = {
            bulanan: @json(\App\Models\KpiPeriod::MONTHS),
            triwulanan: ['Triwulan 1 (Januari–Maret)', 'Triwulan 2 (April–Juni)', 'Triwulan 3 (Juli–September)', 'Triwulan 4 (Oktober–Desember)'],
            semesteran: ['Semester 1 (Januari–Juni)', 'Semester 2 (Juli–Desember)'],
            tahunan: ['Januari–Desember'],
        };
        const update = selected => {
            const daily = type.value === 'harian';
            number.hidden = daily;
            number.disabled = daily;
            date.hidden = !daily;
            date.disabled = !daily;
            date.required = daily;
            date.min = year.value + '-01-01';
            date.max = year.value + '-12-31';
            if (daily) syncKpiDailyDate(date, year);
            number.replaceChildren();
            (choices[type.value] || choices.bulanan).forEach((label, index) => {
                number.add(new Option(label, index + 1, false, index + 1 === Number(selected)));
            });
        };
        update(@json(old('number', $selectedNumber)));
        type.addEventListener('change', () => update(1));
        year.addEventListener('change', () => update(number.value));
    })();
</script>
@endpush
