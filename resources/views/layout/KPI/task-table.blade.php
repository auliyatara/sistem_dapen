<p class="text-muted">Tugas dalam periode {{ $selectedPeriod->label }} ditampilkan sesuai periode asalnya.</p>
<div class="table-responsive">
    <table class="table table-row-dashed align-middle kpi-task-table">
        <thead><tr><th>Periode asal</th><th>Tugas</th><th>Progres</th><th>Status</th><th>Evaluasi</th><th>Bukti / Link</th><th>Detail</th></tr></thead>
        <tbody>
            @forelse($tasks as $task)
                <tr>
                    <td class="text-nowrap">{{ $task->period->short_label }}</td>
                    <td style="min-width:200px">@if($task->program_name)<div class="text-muted mb-2">Program: {{ $task->program_name }}</div>@endif<strong>{{ $task->title }}</strong>@if($task->description)<div class="text-muted text-break" style="white-space:pre-wrap">{{ $task->description }}</div>@endif</td>
                    <td style="min-width:130px">{{ $task->progress }}%<div class="kpi-task-progress mt-2" role="progressbar" aria-label="Progres {{ $task->title }}" aria-valuenow="{{ $task->progress }}" aria-valuemin="0" aria-valuemax="100"><div class="kpi-task-progress-fill {{ $task->progress === 100 ? 'is-complete' : '' }}" style="width:{{ $task->progress }}%"></div></div></td>
                    <td>{{ $task->progress === 100 ? 'Selesai' : ($task->progress === 0 ? 'Belum dimulai' : 'Sedang dikerjakan') }}</td>
                    <td class="text-break" style="min-width:180px;white-space:pre-wrap">{{ $task->evaluation ?: 'Belum ada evaluasi.' }}</td>
                    <td style="min-width:180px">
                        @foreach($task->all_attachments as $key => $attachment)
                            <div class="mb-2">
                                @include('layout.KPI.evidence-button', ['evidenceTask' => $task])
                            </div>
                        @endforeach
                        @foreach($task->links ?? [] as $link)<div class="text-break"><a href="{{ $link }}" target="_blank" rel="noopener noreferrer">{{ $link }}</a></div>@endforeach
                    </td>
                    <td>
                        <a class="btn btn-sm btn-light mb-2" href="{{ route('kpi.history', ['role_id' => $selectedRole->role_id, 'task_id' => $task->id, 'year' => $selectedYear, 'period_type' => $selectedType, 'period_number' => $selectedNumber]) }}">Riwayat</a>
                        @if($canManage)
<button type="button" class="btn btn-sm btn-light-primary" data-bs-toggle="modal" data-bs-target="#kpi-edit-{{ $task->id }}"><span aria-hidden="true">&#9998; </span>Edit Data</button>
                            <form method="POST" action="{{ route('kpi.tasks.destroy', $task) }}" onsubmit="return confirm('Hapus tugas ini beserta buktinya?')">@csrf @method('DELETE')<button class="btn btn-sm btn-light-danger mt-3">Hapus Tugas</button></form>
                        @else
                            <a href="{{ route('kpi.index', ['role_id' => $selectedRole->role_id, 'period_id' => $task->kpi_period_id]) }}">Buka periode</a>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center text-muted py-6">{{ $search !== '' || $status !== '' ? 'Tidak ada tugas yang sesuai pencarian atau filter status.' : 'Belum ada tugas pada periode ini.' }}</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mt-4">
    <p class="text-muted mb-0">Menampilkan {{ $tasks->firstItem() ?? 0 }}–{{ $tasks->lastItem() ?? 0 }} dari {{ $tasks->total() }} tugas. Ringkasan mencakup semua tugas pada periode ini.</p>
    {{ $tasks->links('pagination::bootstrap-4') }}
</div>
