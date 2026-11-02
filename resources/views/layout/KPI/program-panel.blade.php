<?php $hideProgramActions = in_array(strtolower(preg_replace('/[^a-z0-9]/i', '', auth()->user()->role->role_name ?? '')), ['admin', 'superadmin'], true); ?>
<section aria-labelledby="kpi-program-heading">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-4 mb-5">
        <div><h3 id="kpi-program-heading" class="mb-1">Program Kerja</h3><span class="text-muted">Catat pelaksanaan, bukti, dan evaluasi per tahun.</span></div>
        <form method="GET" action="{{ route('kpi.index') }}" class="d-flex align-items-center flex-wrap gap-3">
            <input type="hidden" name="role_id" value="{{ $selectedRole->role_id }}">
            <input type="hidden" name="tab" value="programs">
            <label for="program-year" class="visually-hidden">Tahun</label>
            <input id="program-year" type="number" name="year" min="1900" max="2099" required value="{{ $selectedYear }}" class="form-control form-control-solid" style="width:110px" onchange="if(this.form.reportValidity()) this.form.submit()">
            <noscript><button class="btn btn-primary">Tampilkan</button></noscript>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table table-row-dashed align-middle">
            <thead style="font-size:calc(1rem + 2px);font-weight:700;background:#102a43;color:#fff"><tr><th class="text-center fw-bold py-4" style="width:50px;color:#fff">No.</th><th class="text-center fw-bold py-4" style="color:#fff">Program kerja</th><th class="text-center fw-bold py-4" style="width:160px;color:#fff">Pelaksanaan</th>@unless($hideProgramActions)<th class="text-center fw-bold py-4" style="width:180px;color:#fff">Aksi</th>@endunless</tr></thead>
            <tbody>
            @forelse($programs as $program)
                @php($executions = $programTasks->where('program_id', $program->program_id))
                <tr>
                    <td class="align-middle text-center py-5">{{ $loop->iteration }}</td>
                    <td class="align-middle py-5">
                        <div class="fw-semibold text-break">{{ $program->program_nama }}</div>
                        @if(!empty($program->archived))<div class="text-muted fs-7 mt-1">Program asal sudah dihapus</div>@endif
                    </td>
                    <td class="align-top pt-5">
                        <div class="d-flex flex-wrap gap-2">
                        @forelse($executions as $execution)
                            <button type="button" class="btn btn-sm btn-light-primary text-nowrap" data-bs-toggle="modal" data-bs-target="#kpi-execution-{{ $execution->id }}" aria-label="Lihat pelaksanaan {{ $loop->iteration }}: {{ $execution->title }}">Pelaksanaan {{ $loop->iteration }}</button>
                        @empty
                            <span class="badge badge-light-secondary">Belum ada pelaksanaan</span>
                        @endforelse
                        </div>
                    </td>
                    @unless($hideProgramActions)<td class="text-end align-top pt-5">@if($canManage && empty($program->archived))<button type="button" class="btn btn-sm btn-light-primary text-nowrap" data-kpi-program="{{ $program->program_id }}"  data-bs-toggle="modal" data-bs-target="#kpi-add-modal">+ Pelaksanaan</button>@else<span class="text-muted">—</span>@endif</td>@endunless
                </tr>
            @empty
                <tr><td colspan="{{ $hideProgramActions ? 3 : 4 }}" class="text-center text-muted py-8">{{ $programType ? 'Belum ada program kerja untuk bidang ini.' : 'Belum ada kelompok program yang terhubung dengan bidang ini.' }}</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</section>

@foreach($programTasks as $execution)
<div class="modal fade" id="kpi-execution-{{ $execution->id }}" tabindex="-1" aria-labelledby="kpi-execution-title-{{ $execution->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable"><div class="modal-content">
        <div class="modal-header"><h3 class="modal-title text-break" id="kpi-execution-title-{{ $execution->id }}">Detail Pelaksanaan</h3><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button></div>
        <div class="modal-body">
            <p class="text-muted mb-2">{{ $execution->program_name }}</p>
            <p class="text-muted">{{ $execution->period->label }}</p>
                            <article class="border rounded p-4 mt-3">
                                <div class="d-flex justify-content-between align-items-start gap-3"><strong>{{ $execution->title }}</strong><span class="badge badge-light-primary">{{ $execution->progress }}%</span></div>
                                @if($execution->description)<div class="mt-2 text-break" style="white-space:pre-wrap">{{ $execution->description }}</div>@endif
                                <div class="mt-3"><span class="text-muted">Evaluasi</span><div class="text-break" style="white-space:pre-wrap">{{ $execution->evaluation ?: 'Belum ada evaluasi.' }}</div></div>
                                <div class="mt-3"><span class="text-muted">Bukti</span>
                                @forelse($execution->all_attachments as $key => $attachment)<div class="mt-2">@include('layout.KPI.evidence-button', ['evidenceTask' => $execution])</div>@empty @if(!$execution->links)<div>Belum ada bukti.</div>@endif @endforelse
                                @foreach($execution->links ?? [] as $link)<div class="text-break"><a href="{{ $link }}" target="_blank" rel="noopener noreferrer">{{ $link }}</a></div>@endforeach
                                </div>
                                @if($canManage)<button type="button" class="btn btn-sm btn-light mt-3" data-bs-toggle="modal" data-bs-target="#kpi-edit-{{ $execution->id }}">Edit catatan</button>@endif
                            </article>
        </div>
        <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button></div>
    </div></div>
</div>
@endforeach
