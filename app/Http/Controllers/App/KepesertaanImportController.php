<?php
namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Kepesertaan;
use App\Services\KepesertaanImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, Gate};
use Illuminate\Support\Str;

class KepesertaanImportController extends Controller
{
    public function preview(Request $request, KepesertaanImport $import)
    {
        Gate::authorize('manage-kepesertaan');
        $request->session()->forget('kepesertaan_import');
        $request->validate(['file' => 'required|file|mimes:xlsx,csv,txt|max:20480', 'iuran_year'=>'nullable|integer|between:1900,2099']);
        $extension = strtolower($request->file('file')->getClientOriginalExtension());
        abort_unless(in_array($extension, ['xlsx', 'csv'], true), 422, 'Gunakan file XLSX atau CSV.');
        set_time_limit(300);
        $plan = $import->preview($request->file('file')->getPathname(), $extension, (int)$request->input('iuran_year', now('Asia/Jakarta')->year));
        $token = (string) Str::uuid();
        $request->session()->put('kepesertaan_import', ['token' => $token, 'expires' => time() + 1800, 'plan' => $plan]);
        return $this->previewResponse($plan, $token, 1);
    }

    private function previewResponse(array $plan, string $token, int $page, string $search = '')
    {
        $search = trim($search);
        $filtered = array_filter($plan['rows'], fn ($row) => $search === '' || mb_stripos(implode(' ', array_map(fn ($field) => $row['data'][$field] ?? '', ['kepesertaan_nama', 'kepesertaan_nip', 'kepesertaan_nik'])), $search) !== false);
        $pages = max(1, (int) ceil(count($filtered) / 25));
        $page = min($page, $pages);
        $visible = array_slice($filtered, ($page - 1) * 25, 25, true);
        $selectable = [];
        foreach ($plan['rows'] as $index => $row) if (!$row['errors']) $selectable[] = ['index' => $index, 'status' => $row['status']];
        return response()->json(['html' => view('layout.Kepesertaan.import-preview', ['rows' => $visible, 'counts' => $plan['counts'], 'page' => $page, 'pages' => $pages, 'totalRows' => count($filtered), 'search' => $search])->render(),
            'selectable' => $selectable,
            'token' => $token, 'can_commit' => count($selectable) > 0,
            'changed' => $plan['counts']['changed'], 'new' => $plan['counts']['new']]);
    }

    public function page(Request $request)
    {
        Gate::authorize('manage-kepesertaan');
        $request->validate(['token' => 'required|string', 'page' => 'required|integer|min:1', 'search' => 'nullable|string|max:100']);
        $pending = $request->session()->get('kepesertaan_import');
        abort_unless($pending && hash_equals($pending['token'], $request->input('token')) && $pending['expires'] >= time(), 409, 'Preview kedaluwarsa. Pilih file kembali.');
        return $this->previewResponse($pending['plan'], $pending['token'], (int) $request->input('page'), (string) $request->input('search', ''));
    }

    public function commit(Request $request, KepesertaanImport $import)
    {
        Gate::authorize('manage-kepesertaan');
        $request->validate(['token' => 'required|string', 'confirmed' => 'required|accepted', 'selected' => 'required|array|min:1', 'selected.*' => 'required|integer|min:0|distinct', 'additions' => 'sometimes|array', 'additions.*' => 'array', 'additions.*.*' => 'nullable|string|max:1000']);
        $pending = $request->session()->get('kepesertaan_import');
        abort_unless($pending && hash_equals($pending['token'], $request->input('token')) && $pending['expires'] >= time(), 409, 'Preview kedaluwarsa. Unggah dan periksa ulang file.');
        $selected = array_map('intval', $request->input('selected'));
        foreach ($selected as $index) {
            $row = $pending['plan']['rows'][$index] ?? null;
            abort_unless($row && !$row['errors'], 422, 'Pilihan mengandung baris yang tidak dapat diimpor.');
        }
        $additions = $request->input('additions', []);
        foreach ($additions as $index => $fields) {
            abort_unless(in_array((int)$index, $selected, true) && isset($pending['plan']['rows'][$index]), 422, 'Pilih peserta yang akan dilengkapi terlebih dahulu.');
            $allowed = $pending['plan']['rows'][$index]['missing_fields'] ?? [];
            abort_if(array_diff(array_keys($fields), $allowed), 422, 'Hanya kolom kosong pada preview yang dapat dilengkapi.');
        }
        $result = DB::transaction(function () use ($pending, $import, $selected, $additions) {
            $source = array_map(fn ($row) => array_intersect_key($row, array_flip(['sheet', 'row', 'data', 'errors', 'source_warnings', 'source_iuran_errors', 'iuran', 'target_id'])), $pending['plan']['rows']);
            $current = $import->plan($source, true);
            foreach ($selected as $index) abort_unless(json_encode($current['rows'][$index]) === json_encode($pending['plan']['rows'][$index]), 409, 'Data pilihan berubah sejak preview. Periksa ulang file sebelum impor.');
            if ($additions) {
                foreach ($additions as $index => $fields) foreach ($fields as $field => $value) {
                    if ($value !== null && trim($value) !== '') $source[$index]['data'][$field] = trim($value);
                }
                $current = $import->plan($source, true);
                foreach ($selected as $index) abort_if($current['rows'][$index]['errors'], 422, implode(' ', $current['rows'][$index]['errors']));
            }
            $saved = 0; $details = [];
            foreach ($current['rows'] as $index => $row) {
                if (!in_array($index, $selected, true)) continue;
                $details[] = ['name' => $row['data']['kepesertaan_nama'] ?? '', 'status' => $row['status'] === 'unchanged' ? 'Tidak berubah' : ($row['id'] ? 'Diperbarui' : 'Ditambahkan'), 'iuran' => $row['iuran_errors'] ? implode(' ', $row['iuran_errors']) : 'Tidak ada masalah validasi iuran'];
                if ($row['status'] === 'unchanged') continue;
                if ($row['id']) {
                    $participant = Kepesertaan::findOrFail($row['id']);
                    $participant->fill(array_intersect_key($row['data'], $row['changes']))->save();
                } else {
                    $participant = new Kepesertaan();
                    $participant->fill(array_merge(['kepesertaan_telphone_1' => '', 'kepesertaan_telphone_2' => '',
                        'kepesertaan_nik' => null, 'kepesertaan_nip' => '', 'kepesertaan_jenis_kelamin' => '', 'kepesertaan_tempat_lahir' => '',
                        'kepesertaan_status_jabatan' => '', 'kepesertaan_status_kepesertaan' => '',
                        'kepesertaan_tanggal_lahir' => null, 'kepesertaan_terhitung_mulai_tanggal' => null,
                        'kepesertaan_terhitung_mulai_tanggal_pensiun' => null, 'kepesertaan_gaji_pokok' => null,
                        'kepesertaan_email_1' => '', 'kepesertaan_email_2' => '', 'kepesertaan_alamat' => '',
                        'kepesertaan_jenis_pensiun_sekaligus' => '', 'kepesertaan_npwp' => ''], $row['data']));
                    $participant->kepesertaan_id = substr(str_replace('-', '', (string) Str::uuid()), 0, 16);
                    $participant->save();
                }
                foreach ($row['iuran'] ?? [] as $month) {
                    if ($row['iuran_errors']) break;
                    $key=$month['year'].'-'.$month['month'];
                    $dirty=false; foreach(array_keys($month['values']) as $field) if(isset($row['changes']['iuran:'.$key.':'.$field])) $dirty=true;
                    if(!$dirty) continue;
                    $values=[];
                    if (array_key_exists('phdp', $month['values'])) $values['kepesertaan_iuran_jumlah']=$month['values']['phdp'];
                    if(array_key_exists('peserta',$month['values'])) $values['imported_iuran_peserta']=$month['values']['peserta'];
                    if(array_key_exists('pk',$month['values'])) $values['imported_iuran_pk']=$month['values']['pk'];
                    \App\Models\KepesertaanIuran::updateOrCreate([
                        'kepesertaan_iuran_kepesertaan_id'=>$participant->kepesertaan_id, 'kepesertaan_iuran_type'=>'iuran_total',
                        'kepesertaan_iuran_tahun'=>$month['year'], 'kepesertaan_iuran_bulan'=>$month['month'],
                    ], $values);
                }
                $saved++;
            }
            return ['saved' => $saved, 'details' => $details];
        });
        $request->session()->forget('kepesertaan_import');
        $unchanged = count($selected) - $result['saved'];
        $skipped = [];
        foreach ($pending['plan']['rows'] as $index => $row) if (!in_array($index, $selected, true)) $skipped[] = ['name' => $row['data']['kepesertaan_nama'] ?? '', 'status' => 'Tidak diimpor', 'iuran' => $row['errors'] ? implode(' ', $row['errors']) : 'Tidak dipilih'];
        return response()->json(['message' => $result['saved'].' data peserta berhasil disimpan.'.($unchanged ? ' '.$unchanged.' data pilihan sudah sama dan tetap dipertahankan.' : ''), 'details' => array_merge($result['details'], $skipped)]);
    }

    public function resolve(Request $request, KepesertaanImport $import)
    {
        Gate::authorize('manage-kepesertaan');
        $request->validate(['token' => 'required|string', 'index' => 'required|integer|min:0', 'target' => 'required|string']);
        $pending = $request->session()->get('kepesertaan_import');
        abort_unless($pending && hash_equals($pending['token'], $request->input('token')) && $pending['expires'] >= time(), 409, 'Preview kedaluwarsa. Bandingkan ulang.');
        $index = (int)$request->input('index');
        $row = $pending['plan']['rows'][$index] ?? [];
        abort_unless(in_array($request->input('target'), array_column($row['candidates'] ?? [], 'id'), true), 422, 'Pilih tujuan dari daftar yang cocok.');
        $source = array_map(fn ($item) => array_intersect_key($item, array_flip(['sheet', 'row', 'data', 'errors', 'source_warnings', 'source_iuran_errors', 'iuran', 'target_id'])), $pending['plan']['rows']);
        foreach ($source as &$item) $item['errors'] = array_values(array_filter($item['errors'], fn ($error) => !str_starts_with($error, 'NIK/NIP cocok') && $error !== 'Identitas peserta berulang dalam file impor.'));
        unset($item);
        $source[$index]['target_id'] = $request->input('target');
        $pending['plan'] = $import->plan($source);
        $request->session()->put('kepesertaan_import', $pending);
        return $this->previewResponse($pending['plan'], $pending['token'], max(1, (int)$request->input('page', 1)), (string)$request->input('search', ''));
    }
}
