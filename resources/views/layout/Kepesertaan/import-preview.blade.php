<style>
.participant-compare {min-width:950px;table-layout:fixed}.participant-compare>thead th{background:#102a43!important;color:white!important;font-weight:700!important;padding:14px!important;position:sticky;top:0;z-index:1;vertical-align:middle}.participant-compare td{padding:14px;vertical-align:top}.participant-compare-values{width:100%;table-layout:fixed}.participant-compare-values th{background:#eaf2ff;font-weight:700;padding:8px}.participant-compare-values td{padding:8px;overflow-wrap:anywhere}.participant-compare-values td:last-child{background:#edf9f1}
</style>
<div class="mb-4"><label for="participant-import-search" class="form-label fw-bold">Cari peserta di seluruh file impor</label><div class="d-flex gap-2"><input id="participant-import-search" type="search" class="form-control" placeholder="Nama, NIP, atau NIK — contoh: Abdul Haris" value="{{ $search }}" maxlength="100"><button type="button" class="btn btn-primary" data-import-page="1">Cari</button></div><small class="text-muted">Kosongkan pencarian lalu klik Cari untuk menampilkan semua. Pilihan centang tetap tersimpan; centang semua memilih data baru/berubah di seluruh file; data tanpa perubahan dilewati.</small></div>
<div class="d-flex flex-wrap gap-3 mb-4">
@foreach(['new' => 'Data baru', 'changed' => 'Data berubah', 'unchanged' => 'Data tetap', 'error' => 'Perlu diperiksa'] as $key => $label)<span class="badge badge-light-primary p-3">{{ $label }}: {{ $counts[$key] }}</span>@endforeach
</div>
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3"><label class="fw-semibold d-flex align-items-center gap-2"><input type="checkbox" id="participant-select-all" class="form-check-input"> Centang semua data baru / berubah</label><span id="participant-selected-count" class="text-muted">0 data dipilih</span></div>
@if($counts['error'])<p class="text-muted">Baris bertanda “Perlu diperiksa” dilewati. Anda tetap bisa mengimpor baris lain yang valid.</p>@endif
<div class="table-responsive border rounded" style="max-height:52vh"><table class="table table-row-dashed mb-0 participant-compare"><thead><tr><th style="width:65px" class="text-center">Pilih</th><th style="width:220px">Peserta / Sumber</th><th style="width:170px">Status</th><th>Perbandingan data</th></tr></thead><tbody>
@foreach($rows as $index => $row)
<?php $selectable = !$row['errors']; ?>
<tr><td class="text-center"><input type="checkbox" class="form-check-input participant-import-row" value="{{ $index }}" data-status="{{ $row['status'] }}" aria-label="Pilih {{ $row['data']['kepesertaan_nama'] ?? 'baris '.$row['row'] }}" @disabled(!$selectable)></td>
<td><strong>{{ $row['data']['kepesertaan_nama'] ?? '—' }}</strong><div class="text-muted mt-1">NIP: {{ $row['data']['kepesertaan_nip'] ?? '—' }}</div><div class="text-muted fs-7 mt-1">{{ $row['sheet'] }} · Baris {{ $row['row'] }}</div></td>
<td><span class="badge {{ $row['errors'] ? 'badge-light-warning' : 'badge-light-primary' }}">{{ $row['errors'] ? 'Perlu diperiksa' : ['new' => 'Baru', 'changed' => 'Berubah', 'unchanged' => 'Tetap'][$row['status']] }}</span><div class="mt-2">{{ $row['data']['kepesertaan_status_kepesertaan'] ?? 'Status lama dipertahankan' }}</div><div class="text-muted">{{ $row['data']['kepesertaan_jenis_pensiun'] ?? '' }}</div></td>
<td>
@if(count($row['candidates'] ?? []) > 1)
<div class="border rounded p-3 mb-3"><label class="form-label fw-bold" for="import-target-{{ $index }}">Pilih data lama yang akan diperbarui</label><select id="import-target-{{ $index }}" class="form-select" data-import-target="{{ $index }}"><option value="">Pilih peserta tujuan</option>@foreach($row['candidates'] as $candidate)<option value="{{ $candidate['id'] }}" @selected(($row['target_id'] ?? '') === $candidate['id'])>{{ $candidate['name'] }} · NIP {{ $candidate['nip'] ?: 'kosong' }} · {{ $candidate['status'] }}</option>@endforeach</select><small class="text-muted">Pilihan ini memperbarui preview; data belum disimpan.</small></div>
@endif
@if(!empty($row['missing_fields']) && !$row['errors'])
<details class="mb-3 border rounded p-3"><summary class="text-primary fw-bold">Lengkapi data kosong di sini (opsional)</summary><p class="text-muted mt-2">Boleh diisi sekarang atau dilewati. Isian ikut disimpan saat peserta dicentang dan impor dikonfirmasi.</p><div class="row g-3">
@foreach($row['missing_fields'] as $field)
<?php $label = collect(\App\Services\KepesertaanImport::FIELDS)->first(fn ($item) => $item[0] === $field)[1] ?? $field; $type = str_contains($field, 'tanggal') ? 'date' : ($field === 'kepesertaan_gaji_pokok' ? 'number' : 'text'); ?>
<div class="col-12 col-lg-6"><label for="import-field-{{ $index }}-{{ $field }}" class="form-label">{{ $label }}</label><input id="import-field-{{ $index }}-{{ $field }}" type="{{ $type }}" class="form-control form-control-sm" data-import-row="{{ $index }}" data-import-field="{{ $field }}" @if($type === 'number') min="0" step="1" @endif @if($field === 'kepesertaan_nik') maxlength="16" @endif></div>
@endforeach
</div></details>
@endif
@if(!empty($row['warnings']))<div class="alert alert-warning mb-3"><strong>Peringatan — tetap bisa dipilih</strong><ul class="mb-0 mt-2">@foreach(array_unique($row['warnings']) as $warning)<li>{{ $warning }}</li>@endforeach</ul></div>@endif
@if($row['errors'])<details class="mb-3"><summary class="text-warning fw-semibold">Lihat alasan baris ini dilewati</summary><ul class="mt-2">@foreach(array_unique($row['errors']) as $error)<li>{{ $error }}</li>@endforeach</ul></details>@endif
@if($row['changes'])<table class="table table-sm participant-compare-values mb-0"><thead><tr><th style="width:32%">Kolom</th><th>Data lama</th><th>Data dari file</th></tr></thead><tbody>
@foreach($row['changes'] as $field => $change)
<?php
    $isIuran = str_starts_with($field, 'iuran:');
    $isMoney = $field === 'kepesertaan_gaji_pokok' || $isIuran;
    $fieldLabel = collect(\App\Services\KepesertaanImport::FIELDS)->first(fn ($item) => $item[0] === $field)[1] ?? 'Status kepesertaan';
    if ($isIuran) {
        $parts = explode(':', $field); [$year, $month] = explode('-', $parts[1]);
        $fieldLabel = ['phdp'=>'PHDP', 'peserta'=>'Iuran peserta', 'pk'=>'Iuran pemberi kerja'][$parts[2]].' · '.\App\Models\KpiPeriod::MONTHS[(int)$month-1].' '.$year;
    }
    $money = fn ($value) => 'Rp '.rtrim(rtrim(number_format((float)$value, 4, ',', '.'), '0'), ',');
?>
<tr><td class="fw-semibold">{{ $fieldLabel }}</td><td>{{ $change['old'] === null || $change['old'] === '' ? '—' : ($isMoney ? $money($change['old']) : $change['old']) }}</td><td class="fw-semibold">{{ $isMoney ? $money($change['new']) : $change['new'] }}</td></tr>
@endforeach
</tbody></table>@else<span class="text-muted">Tidak ada perubahan.</span>@endif
</td></tr>@endforeach
</tbody></table></div>
@if(!$totalRows)<p class="text-muted mt-3">Tidak ada peserta yang cocok. Coba sebagian nama atau cari berdasarkan NIP/NIK.</p>@endif
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mt-3" data-preview-page="{{ $page }}">
    <span class="text-muted">Halaman {{ $page }} dari {{ $pages }} · {{ $totalRows }} peserta · Maksimal 25 per halaman</span>
    <div class="d-flex gap-2"><button type="button" class="btn btn-sm btn-light-primary" data-import-page="{{ $page - 1 }}" @disabled($page <= 1)>Sebelumnya</button><button type="button" class="btn btn-sm btn-light-primary" data-import-page="{{ $page + 1 }}" @disabled($page >= $pages)>Berikutnya</button></div>
</div>
