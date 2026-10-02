@php($restore = (string) old('task_id', '') === (string) ($task->id ?? ''))
<div class="row g-4">
    <div class="col-md-9">
        <label for="{{ $formKey }}-title" class="form-label required">Tugas</label>
        <input id="{{ $formKey }}-title" name="title" class="form-control" maxlength="255" value="{{ $restore ? old('title', $task->title ?? '') : ($task->title ?? '') }}" required>
    </div>
    <div class="col-md-3">
        <label for="{{ $formKey }}-progress" class="form-label required">Progres (%)</label>
        <input id="{{ $formKey }}-progress" name="progress" type="number" min="0" max="100" step="1" class="form-control" value="{{ $restore ? old('progress', $task->progress ?? 0) : ($task->progress ?? 0) }}" required>
        <div class="form-text">Progres 100% otomatis ditandai Selesai.</div>
    </div>
    <div class="col-12">
        <label for="{{ $formKey }}-description" class="form-label">Keterangan (opsional)</label>
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
