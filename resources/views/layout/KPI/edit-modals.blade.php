@if($canManage)
    @foreach($editableTasks as $task)
        <div class="modal fade" id="kpi-edit-{{ $task->id }}" tabindex="-1" aria-labelledby="kpi-edit-title-{{ $task->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable"><div class="modal-content">
                <div class="modal-header"><h3 id="kpi-edit-title-{{ $task->id }}">Edit Data KPI</h3><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button></div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('kpi.tasks.update', $task) }}" enctype="multipart/form-data">
                        @csrf @method('PUT')
                        <input type="hidden" name="task_id" value="{{ $task->id }}">
                        @include('layout.KPI.task-form', ['task' => $task, 'formKey' => 'task-'.$task->id])
                        <div class="d-flex justify-content-end gap-3 mt-5"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button><button class="btn btn-primary">Simpan Perubahan</button></div>
                    </form>
                </div>
            </div></div>
        </div>
    @endforeach
@endif
