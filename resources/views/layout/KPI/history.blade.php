@extends('master.app')
@section('content')
<div class="app-container container-fluid"><div class="card"><div class="card-header align-items-center"><h2 class="card-title">Riwayat KPI {{ $selectedRole->role_name }}</h2><a class="btn btn-light" href="{{ route('kpi.index', request()->except(['task_id', 'page'])) }}">Kembali ke KPI</a></div><div class="card-body">
<p class="text-muted">Riwayat mencakup seluruh periode bidang ini{{ request('task_id') ? ' untuk tugas yang dipilih' : '' }}. Pencatatan dimulai sejak fitur riwayat diaktifkan.</p>
@forelse($logs as $log)
<div class="border rounded p-4 mb-4"><strong>{{ $log->description }}: {{ $log->properties['title'] }}</strong><div class="text-muted mb-3">{{ $log->created_at->timezone('Asia/Jakarta')->locale('id')->translatedFormat('j F Y H:i:s') }} WIB · {{ $log->properties['actor'] }}</div>
@php($before = $log->properties['before'] ?? [])
@php($after = $log->properties['after'] ?? [])
<div class="table-responsive"><table class="table table-row-dashed"><thead><tr><th>Data</th><th>Sebelum</th><th>Sesudah</th></tr></thead><tbody>
@foreach(array_unique(array_merge(array_keys($before), array_keys($after))) as $field)
@if(($before[$field] ?? null) !== ($after[$field] ?? null))
<tr><td>{{ $field }}</td>@foreach([$before, $after] as $snapshot)<td class="text-break" style="white-space:pre-wrap">{{ is_array($snapshot[$field] ?? null) ? implode("\n", $snapshot[$field]) : ($snapshot[$field] ?? '—') }}</td>@endforeach</tr>
@endif
@endforeach
</tbody></table></div></div>
@empty<p class="text-muted">Belum ada riwayat perubahan.</p>@endforelse
{{ $logs->links('pagination::bootstrap-4') }}
</div></div></div>
@endsection
