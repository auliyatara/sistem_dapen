@php($restore = (string) old('task_id', '') === (string) ($task->id ?? ''))
<div class="row g-4">
    <div class="col-12">
        <label for="{{ $formKey }}-program" class="form-label">Program kerja (opsional)</label>
        @if($task && $task->program_id)
            <input type="hidden" name="program_id" value="{{ $task->program_id }}">
            <div class="form-control bg-light">{{ $task->program_name }}</div>
        @else
            <select id="{{ $formKey }}-program" name="program_id" class="form-select kpi-program-select"><option value="">Tugas di luar program kerja</option>
            @foreach($programs as $program) @if(empty($program->archived))<option value="{{ $program->program_id }}" @selected($restore && (string) old('program_id') === (string) $program->program_id)>{{ $program->program_nama }}</option>@endif @endforeach
            </select>
        @endif
        <div class="form-text">Pelaksanaan program kerja dicatat per tahun. Satu program dapat memiliki beberapa catatan pelaksanaan.</div>
    </div>
    @if($task)
        @php($editType = $restore ? old('type', $task->period->type) : $task->period->type)
        <div class="col-12 kpi-edit-period" data-number="{{ $restore ? old('number', $task->period->number) : $task->period->number }}">
            <label for="{{ $formKey }}-type" class="form-label">Jenis periode</label>
            <select id="{{ $formKey }}-type" name="type" class="form-select mb-3" required>
                @foreach(($task->program_id ? ['tahunan' => 'Tahunan'] : ['harian' => 'Harian', 'bulanan' => 'Bulanan', 'triwulanan' => 'Triwulanan', 'semesteran' => 'Semesteran', 'tahunan' => 'Tahunan']) as $value => $label)
                    <option value="{{ $value }}" @selected($editType === $value)>{{ $label }}</option>
                @endforeach
            </select>
            <label for="{{ $formKey }}-year" class="form-label">Tahun</label>
            <input id="{{ $formKey }}-year" name="year" type="number" min="1900" max="2099" required class="form-control mb-3" value="{{ $restore ? old('year', $task->period->year) : $task->period->year }}">
            <label for="{{ $formKey }}-number" class="form-label">Periode / tanggal</label>
            <select id="{{ $formKey }}-number" name="number" class="form-select"></select>
            <input name="date" type="date" aria-label="Tanggal tugas" class="form-control" value="{{ $restore ? old('date', $task->period->starts_at->toDateString()) : $task->period->starts_at->toDateString() }}">
        </div>
    @endif
    <div class="col-md-9">
        <label for="{{ $formKey }}-title" class="form-label required">Tugas / Pelaksanaan</label>
        <input id="{{ $formKey }}-title" name="title" class="form-control" maxlength="255" value="{{ $restore ? old('title', $task->title ?? '') : ($task->title ?? '') }}" required>
    </div>
    <div class="col-md-3">
        <label for="{{ $formKey }}-progress" class="form-label required">Progres (%)</label>
        <input id="{{ $formKey }}-progress" name="progress" type="number" min="0" max="100" step="0.1" class="form-control" value="{{ $restore ? old('progress', $task->progress ?? 0) : ($task->progress ?? 0) }}" required>
        <div class="form-text">Progres 100% otomatis ditandai Selesai.</div>
    </div>
    <div class="col-12">
        <label for="{{ $formKey }}-description" class="form-label">Uraian pelaksanaan / Keterangan (opsional)</label>
        <textarea id="{{ $formKey }}-description" name="description" class="form-control" rows="2" maxlength="5000">{{ $restore ? old('description', $task->description ?? '') : ($task->description ?? '') }}</textarea>
    </div>
    <div class="col-12">
        <label for="{{ $formKey }}-evaluation" class="form-label">Evaluasi tugas (opsional)</label>
        <textarea id="{{ $formKey }}-evaluation" name="evaluation" class="form-control" rows="3" maxlength="5000" placeholder="Catat hasil, kendala, dan tindak lanjut tugas.">{{ $restore ? old('evaluation', $task->evaluation ?? '') : ($task->evaluation ?? '') }}</textarea>
    </div>
    <div class="col-12">
        <label for="{{ $formKey }}-evidence" class="form-label">Bukti (opsional)</label>
        <input id="{{ $formKey }}-evidence" type="file" name="evidences[]" multiple class="form-control" accept=".pdf,.jpg,.jpeg,.png">
        <div class="form-text">Pilih beberapa file sekaligus, maksimal 10 file per unggahan. File baru ditambahkan ke bukti yang sudah ada.</div>
        <div class="form-text">PDF, JPG, atau PNG, maksimal 2 MB per file setelah kompresi. File di atas 2 MB dikompres otomatis (unggahan maksimal 40 MB per file).</div>
        @foreach($task ? $task->all_attachments : [] as $key => $attachment)
            <label class="d-block mt-2"><input type="checkbox" name="remove_attachments[]" value="{{ $key }}"> Hapus {{ $attachment['name'] }}</label>
        @endforeach
    </div>
    <div class="col-12 kpi-links">
        <label class="form-label">Link bukti (opsional)</label>
        <div class="kpi-link-rows">
            @foreach(($restore ? old('links', $task->links ?? ['']) : ($task->links ?? [''])) ?: [''] as $link)
                <div class="d-flex gap-2 mb-2"><input type="url" name="links[]" class="form-control" aria-label="Link bukti" placeholder="https://..." maxlength="2048" value="{{ $link }}"><button type="button" class="btn btn-light-danger" data-remove-link aria-label="Hapus link">&times;</button></div>
            @endforeach
        </div>
        <button type="button" class="btn btn-sm btn-light-primary" data-add-link>+ Tambah Link</button>
    </div>
</div>
