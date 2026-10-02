@extends('master.app')

@section('content')
<style>
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
                        <span class="badge {{ $canManage ? 'badge-light-primary' : 'badge-light-secondary' }}">{{ $canManage ? 'Bidang Anda' : 'Hanya lihat' }}</span>
                    </div>
                    <p class="text-muted mt-3 mb-0">{{ $canManage ? 'Pengelolaan KPI bidang ini menjadi wewenang akun dengan hak akses Anda.' : 'Anda dapat melihat KPI bidang ini. Pengelolaan data hanya tersedia bagi akun dengan hak akses bidang terkait.' }}</p>
                    @if($canManage)
                        <details class="mt-6 border rounded p-5" @if($errors->any()) open @endif>
                            <summary class="kpi-action-summary text-primary fw-bold cursor-pointer"><svg class="kpi-action-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>Tambah Periode</summary>
                            <form action="{{ route('kpi.periods.store', $selectedRole->role_id) }}" method="POST" class="mt-5">
                                @csrf
                                <div class="row g-4">
                                    <div class="col-md-4">
                                        <label for="period-type" class="form-label required">Jenis periode</label>
                                        <select id="period-type" name="type" class="form-select" required>
                                            @foreach(['bulanan' => 'Bulanan', 'triwulanan' => 'Triwulanan', 'semesteran' => 'Semesteran', 'tahunan' => 'Tahunan'] as $value => $label)
                                                <option value="{{ $value }}" @selected(old('type', 'bulanan') === $value)>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="period-year" class="form-label required">Tahun</label>
                                        <input id="period-year" name="year" type="number" class="form-control" min="1900" max="2099" value="{{ old('year', now('Asia/Jakarta')->year) }}" required>
                                    </div>
                                    <div class="col-md-5">
                                        <label for="period-number" class="form-label required">Periode</label>
                                        <select id="period-number" name="number" class="form-select" required></select>
                                    </div>
                                </div>
                                <p class="text-muted mt-4">Periode mengikuti kalender: bulanan 1 bulan, triwulanan 3 bulan, semesteran 6 bulan, dan tahunan Januari–Desember.</p>
                                <button type="submit" class="btn btn-primary">Simpan Periode</button>
                            </form>
                        </details>
                    @endif
                    <form method="GET" action="{{ route('kpi.index') }}" class="d-flex flex-wrap align-items-center gap-4 mt-6">
                        <input type="hidden" name="role_id" value="{{ $selectedRole->role_id }}">
                        <label for="kpi-year" class="fw-semibold mb-0">Tahun</label>
                        <input id="kpi-year" name="year" type="number" min="1900" max="2099" step="1" required value="{{ $selectedYear }}" class="form-control form-control-solid" style="width:120px" onchange="if(this.form.reportValidity()) this.form.submit()">
                            <label for="kpi-period" class="fw-semibold mb-0">Periode</label>
                            <select name="period_type" id="kpi-period" class="form-select form-select-solid w-auto" onchange="this.form.elements.period_number.value=1;if(this.form.reportValidity()) this.form.submit()">
                                @foreach(['bulanan' => 'Bulanan', 'triwulanan' => 'Triwulanan', 'semesteran' => 'Semesteran', 'tahunan' => 'Tahunan'] as $value => $label)
                                    <option value="{{ $value }}" @selected($selectedType === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($selectedType !== 'tahunan')
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
                        </form>
                    @if($selectedPeriod)
                        <p class="text-muted mt-3">{{ $selectedPeriod->starts_at->format('d/m/Y') }} – {{ $selectedPeriod->ends_at->format('d/m/Y') }}</p>
                        @if($canManage)
                            <details class="border rounded p-5 mt-5" @if($errors->hasAny(['title', 'description', 'progress', 'evidence']) && !old('task_id')) open @endif>
                                <summary class="kpi-action-summary text-primary fw-bold cursor-pointer"><svg class="kpi-action-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>Tambah Tugas</summary>
                                <form method="POST" action="{{ route('kpi.tasks.store', $selectedPeriod) }}" enctype="multipart/form-data" class="mt-5">
                                    @csrf
                                    @include('layout.KPI.task-form', ['task' => null, 'formKey' => 'new'])
                                    <button class="btn btn-primary mt-4">Simpan Tugas</button>
                                </form>
                            </details>
                        @endif
                        <div class="mt-6">
                            <h4>Tugas · {{ $selectedPeriod->label }}</h4>
                            @forelse($tasks as $task)
                                <article class="border rounded p-5 mb-4">
                                    <div class="d-flex justify-content-between align-items-start gap-3">
                                        <h5 class="mb-2 text-break">{{ $task->title }}</h5>
                                        @if($task->progress === 100)<span class="badge badge-light-success"><span aria-hidden="true">&#10003; </span>Selesai</span>@endif
                                    </div>
                                    @if($task->description)<p class="text-muted text-break" style="white-space:pre-wrap">{{ $task->description }}</p>@endif
                                    <div class="d-flex justify-content-between align-items-center mt-3 mb-2">
                                        <span class="text-muted">{{ $task->progress == 100 ? 'Selesai' : ($task->progress == 0 ? 'Belum dimulai' : 'Sedang dikerjakan') }}</span>
                                        <span class="fw-bold">{{ $task->progress }}%</span>
                                    </div>
                                    <div class="kpi-task-progress" role="progressbar" aria-label="Progres {{ $task->title }}" aria-valuenow="{{ $task->progress }}" aria-valuemin="0" aria-valuemax="100">
                                        <div class="kpi-task-progress-fill {{ $task->progress == 100 ? 'is-complete' : '' }}" style="width:{{ $task->progress }}%"></div>
                                    </div>
                                    @if($task->evaluation || $task->progress === 100)
                                        <div class="bg-light rounded p-4 mb-4"><h6>Evaluasi</h6><p class="mb-0 text-break" style="white-space:pre-wrap">{{ $task->evaluation ?: 'Belum ada evaluasi.' }}</p></div>
                                    @endif
                                    @foreach($task->all_attachments as $key => $attachment)
                                        <div class="mb-2">
                                        @if(in_array(strtolower(pathinfo($attachment['path'], PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png']))
                                            <button type="button" class="btn btn-sm btn-light-primary" data-bs-toggle="modal" data-bs-target="#kpi-evidence-modal" data-evidence-url="{{ route('kpi.tasks.evidence', ['task' => $task, 'attachment' => $key, 'preview' => 1]) }}" data-evidence-name="{{ $attachment['name'] }}">Lihat gambar: {{ $attachment['name'] }}</button>
                                        @else
                                            <a href="{{ route('kpi.tasks.evidence', ['task' => $task, 'attachment' => $key]) }}">Unduh bukti: {{ $attachment['name'] }}</a>
                                        @endif
                                        </div>
                                    @endforeach
                                    @foreach($task->links ?? [] as $link)
                                        <div class="mb-2 text-break"><a href="{{ $link }}" target="_blank" rel="noopener noreferrer">{{ $link }}</a></div>
                                    @endforeach
                                    @if($canManage)
                                        <details class="mt-4" @if((string) old('task_id') === (string) $task->id) open @endif>
                                            <summary class="kpi-action-summary text-primary cursor-pointer"><svg class="kpi-action-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m16 3 5 5L8 21H3v-5L16 3Z M13 6l5 5"/></svg>Edit tugas / progres</summary>
                                            <form method="POST" action="{{ route('kpi.tasks.update', $task) }}" enctype="multipart/form-data" class="mt-4">
                                                @csrf @method('PUT')
                                                <input type="hidden" name="task_id" value="{{ $task->id }}">
                                                @include('layout.KPI.task-form', ['task' => $task, 'formKey' => 'task-'.$task->id])
                                                <button class="btn btn-primary mt-4">Simpan Perubahan</button>
                                            </form>
                                        </details>
                                        <form method="POST" action="{{ route('kpi.tasks.destroy', $task) }}" class="mt-4" onsubmit="return confirm('Hapus tugas ini beserta buktinya?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-light-danger">Hapus Tugas</button>
                                        </form>
                                    @endif
                                </article>
                            @empty
                                <p class="text-muted py-5">Belum ada tugas untuk periode ini.</p>
                            @endforelse
                        </div>
                    @else
                    <div class="py-10 text-center">
                        <div class="symbol symbol-60px mb-5"><span class="symbol-label bg-light-primary text-primary fs-1" aria-hidden="true">&#9678;</span></div>
                        <h4 class="mb-3">Belum ada periode KPI</h4>
                        <p class="text-muted mb-0">Periode yang dipilih pada tahun {{ $selectedYear }} belum ditambahkan. {{ $canManage ? 'Gunakan Tambah Periode untuk membuatnya terlebih dahulu.' : '' }}</p>
                    </div>
                    @endif
                @else
                    <p class="text-muted text-center py-10 mb-0">Belum ada hak akses aktif untuk ditampilkan.</p>
                @endif
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="kpi-evidence-modal" tabindex="-1" aria-labelledby="kpi-evidence-title" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered"><div class="modal-content">
        <div class="modal-header"><h3 class="modal-title text-break" id="kpi-evidence-title">Bukti tugas</h3><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button></div>
        <div class="modal-body text-center"><p id="kpi-evidence-status" role="status">Memuat gambar...</p><img id="kpi-evidence-image" alt="Bukti tugas" class="img-fluid" style="max-height:70vh" hidden></div>
    </div></div>
</div>
@endsection

@push('javascript')
<script>
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
        const status = document.getElementById('kpi-evidence-status');
        modal.addEventListener('show.bs.modal', event => {
            const trigger = event.relatedTarget;
            if (!trigger) return;
            img.hidden = true;
            status.hidden = false;
            status.textContent = 'Memuat gambar...';
            document.getElementById('kpi-evidence-title').textContent = trigger.dataset.evidenceName || 'Bukti tugas';
            img.onload = () => { img.hidden = false; status.hidden = true; };
            img.onerror = () => { img.hidden = true; status.hidden = false; status.textContent = 'Gambar gagal dimuat. Silakan coba kembali.'; };
            img.src = trigger.dataset.evidenceUrl;
        });
        modal.addEventListener('hidden.bs.modal', () => { img.onload = null; img.onerror = null; img.removeAttribute('src'); img.hidden = true; });
    })();
    (() => {
        const type = document.getElementById('period-type');
        if (!type) return;
        const number = document.getElementById('period-number');
        const choices = {
            bulanan: @json(\App\Models\KpiPeriod::MONTHS),
            triwulanan: ['Triwulan 1 (Januari–Maret)', 'Triwulan 2 (April–Juni)', 'Triwulan 3 (Juli–September)', 'Triwulan 4 (Oktober–Desember)'],
            semesteran: ['Semester 1 (Januari–Juni)', 'Semester 2 (Juli–Desember)'],
            tahunan: ['Januari–Desember'],
        };
        const update = selected => {
            number.replaceChildren();
            (choices[type.value] || choices.bulanan).forEach((label, index) => {
                number.add(new Option(label, index + 1, false, index + 1 === Number(selected)));
            });
        };
        update(@json(old('number', 1)));
        type.addEventListener('change', () => update(1));
    })();
</script>
@endpush
