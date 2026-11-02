<?php
namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class KepesertaanImport
{
    public const FIELDS = [
        'Nama Peserta' => ['kepesertaan_nama', 'Nama'], 'NIP UMM' => ['kepesertaan_nip', 'NIP UMM'],
        'NIK' => ['kepesertaan_nik', 'NIK'], 'Jenis Kelamin' => ['kepesertaan_jenis_kelamin', 'Jenis kelamin'],
        'Tempat Lahir' => ['kepesertaan_tempat_lahir', 'Tempat lahir'], 'Tanggal Lahir' => ['kepesertaan_tanggal_lahir', 'Tanggal lahir'],
        'TMT Masuk' => ['kepesertaan_terhitung_mulai_tanggal', 'TMT masuk'], 'Status Pegawai' => ['kepesertaan_status_jabatan', 'Status jabatan'],
        'Gaji Terakhir' => ['kepesertaan_gaji_pokok', 'PHDP / gaji terakhir'], 'No Telepon' => ['kepesertaan_telphone_1', 'Telepon utama'],
        'Email 1' => ['kepesertaan_email_1', 'Email utama'], 'Email 2' => ['kepesertaan_email_2', 'Email kedua'],
        'Alamat' => ['kepesertaan_alamat', 'Alamat'], 'Tanggal Pensiun' => ['kepesertaan_terhitung_mulai_tanggal_pensiun', 'Tanggal pensiun'],
        'Jenis Pensiun' => ['kepesertaan_jenis_pensiun', 'Jenis pensiun'], 'NPWP' => ['kepesertaan_npwp', 'NPWP'],
        'Status Kepesertaan' => ['kepesertaan_status_kepesertaan', 'Status kepesertaan'],
        'Status Peserta' => ['kepesertaan_status_kepesertaan', 'Status kepesertaan'],
    ];

    public function preview(string $path, string $extension, ?int $iuranYear = null): array
    {
        if ($extension !== 'csv' && !class_exists(\ZipArchive::class)) throw \Illuminate\Validation\ValidationException::withMessages(['file' => 'Impor XLSX membutuhkan ekstensi ZIP PHP. Aktifkan extension=zip pada php.ini dan restart Apache, atau gunakan CSV.']);
        $reader = IOFactory::createReader($extension === 'csv' ? 'Csv' : 'Xlsx');
        $reader->setReadDataOnly(true);
        $reader->setReadFilter(new class implements \PhpOffice\PhpSpreadsheet\Reader\IReadFilter {
            public function readCell($columnAddress, $row, $worksheetName = '') {
                return !in_array(strtolower(trim($worksheetName)), ['admin', 'logs'], true) && $row <= 10001 && \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($columnAddress) <= 60;
            }
        });
        if ($extension !== 'csv') $reader->setLoadSheetsOnly(array_values(array_filter($reader->listWorksheetNames($path), fn ($name) => !in_array(strtolower(trim($name)), ['admin', 'logs'], true))));
        $binder = \PhpOffice\PhpSpreadsheet\Cell\Cell::getValueBinder();
        try {
            if ($extension === 'csv') \PhpOffice\PhpSpreadsheet\Cell\Cell::setValueBinder(new \PhpOffice\PhpSpreadsheet\Cell\StringValueBinder());
            $book = $reader->load($path);
        } finally { \PhpOffice\PhpSpreadsheet\Cell\Cell::setValueBinder($binder); }
        $source = [];
        try {
            foreach ($book->getWorksheetIterator() as $sheet) {
                $name = $extension === 'csv' ? 'CSV' : $sheet->getTitle();
                if (in_array(strtolower(trim($name)), ['admin', 'logs'], true)) continue;
                $headers = [];
                for ($column = 1; $column <= 60; $column++) {
                    $header = trim((string) $sheet->getCellByColumnAndRow($column, 1)->getValue(), " \t\n\r\0\x0B\xEF\xBB\xBF");
                    if ($header !== '' && !isset($headers[$header])) $headers[$header] = $column;
                }
                if (!isset($headers['Nama Peserta'], $headers['NIP UMM'], $headers['NIK'])) {
                    if ($extension !== 'csv') continue;
                    throw \Illuminate\Validation\ValidationException::withMessages(['file' => 'Header Nama Peserta, NIP UMM, dan NIK wajib ada.']);
                }
                if ($sheet->getHighestRow() > 10000) throw \Illuminate\Validation\ValidationException::withMessages(['file' => 'Maksimal 10.000 baris per sheet.']);
                for ($row = 2; $row <= $sheet->getHighestRow(); $row++) {
                    $data = []; $errors = []; $sourceWarnings = [];
                    foreach (self::FIELDS as $header => [$field, $label]) {
                        if (!isset($headers[$header])) continue;
                        if ($header === 'Status Peserta' && isset($data['kepesertaan_status_kepesertaan'])) continue;
                        $cell = $sheet->getCellByColumnAndRow($headers[$header], $row);
                        $value = $cell->isFormula() ? $cell->getOldCalculatedValue() : $cell->getValue();
                        if ($value === null || trim((string) $value) === '') continue;
                        try {
                            if (str_contains($field, 'tanggal')) {
                                if (is_numeric($value)) $value = ExcelDate::excelToDateTimeObject((float) $value)->format('Y-m-d');
                                else {
                                    $date = \DateTimeImmutable::createFromFormat('!Y-m-d', (string) $value);
                                    if (!$date || $date->format('Y-m-d') !== (string) $value) throw new \RuntimeException('Gunakan tanggal Excel atau YYYY-MM-DD.');
                                }
                            } elseif ($field === 'kepesertaan_gaji_pokok') {
                                if (!is_numeric($value) || $value < 0 || floor((float) $value) != $value) throw new \RuntimeException('Harus berupa nominal angka bulat tanpa pemisah ribuan.');
                                $value = (string) (int) $value;
                            } elseif (in_array($field, ['kepesertaan_nik', 'kepesertaan_nip'], true) && is_numeric($value) && !is_string($value)) {
                                if ($extension !== 'csv' && $field === 'kepesertaan_nik' && (float)$value !== 0.0) {
                                    $digits = sprintf('%.0f', $value);
                                    if (strlen($digits) > 16) {
                                        $sourceWarnings[] = 'NIK tersimpan sebagai angka Excel; ketepatan digit perlu diperiksa pada dokumen asli.';
                                        $value = $digits;
                                    } else {
                                        $sourceWarnings[] = 'NIK berupa angka Excel; digitnya belum dapat dipastikan. NIK asli di database dipertahankan, atau diisi 0000000000000000 jika belum tersedia. Peserta tetap bisa dipilih berdasarkan NIP.';
                                        $value = '0000000000000000';
                                    }
                                } else {
                                    if ($extension !== 'csv' && $field === 'kepesertaan_nip' && strlen(sprintf('%.0f', $value)) > 15) {
                                        $sourceWarnings[] = 'NIP berupa angka Excel panjang sehingga digitnya belum dapat dipastikan. NIP dari sel ini dilewati; NIP lama dipertahankan, atau dikosongkan jika peserta baru. Data lainnya tetap bisa diimpor.';
                                        continue;
                                    }
                                    $value = sprintf('%.0f', $value);
                                }
                            }
                            if ($field === 'kepesertaan_jenis_kelamin') {
                                $value = ['l' => 'Laki-Laki', 'laki-laki' => 'Laki-Laki', 'p' => 'Perempuan', 'perempuan' => 'Perempuan'][strtolower(trim((string) $value))] ?? $value;
                            }
                            if ($field === 'kepesertaan_status_jabatan') {
                                $value = ['tendik' => 'Karyawan', 'tendik satpam' => 'Karyawan', 'gubes' => 'Guru Besar'][strtolower(trim((string) $value))] ?? $value;
                            }
                            $data[$field] = trim((string) $value);
                        } catch (\Throwable $e) { $errors[] = $label.': '.$e->getMessage(); }
                    }
                    if (!$data) continue;
                    [$data, $categoryErrors] = $this->classify($data);
                    $errors = array_merge($errors, $categoryErrors);
                    $months = [];
                    if (isset($headers['Tahun'], $headers['Bulan'], $headers['PHDP'])) {
                        $csvYear = KepesertaanMonthlyImport::value($sheet, $headers['Tahun'], $row);
                        $csvMonth = KepesertaanMonthlyImport::value($sheet, $headers['Bulan'], $row);
                        if ($csvYear !== null && $csvYear !== '') {
                            if (!ctype_digit((string) $csvYear) || !ctype_digit((string) $csvMonth) || (int) $csvMonth < 1 || (int) $csvMonth > 12) $errors[] = 'Tahun/bulan iuran tidak valid.';
                            elseif ((int) $csvYear !== ($iuranYear ?? (int) now('Asia/Jakarta')->year)) continue;
                            else {
                                $values=[];
                                foreach (['PHDP'=>'phdp','Iuran Peserta'=>'peserta','Iuran Pemberi Kerja'=>'pk'] as $header=>$field) {
                                    if (!isset($headers[$header])) continue;
                                    $value=KepesertaanMonthlyImport::value($sheet,$headers[$header],$row);
                                    if ($value===null || $value==='') continue;
                                    if (!is_numeric($value) || $value<0 || $value>999999999999999) { $errors[]='Nominal '.$header.' tidak valid.'; continue; }
                                    $values[$field]=$field==='phdp'?(int)$value:round((float)$value,4);
                                    if ($field==='phdp' && floor((float)$value)!=$value) $errors[]='PHDP bulanan harus angka bulat.';
                                }
                                $months[]=['year'=>(int)$csvYear,'month'=>(int)$csvMonth,'values'=>$values];
                            }
                        }
                    }
                    $source[] = ['sheet' => $name, 'row' => $row, 'data' => $data, 'errors' => $errors, 'source_warnings' => $sourceWarnings, 'iuran'=>$months];
                }
            }
        } finally { $book->disconnectWorksheets(); }
        if ($extension !== 'csv') {
            foreach (app(KepesertaanMonthlyImport::class)->read($path, $iuranYear ?? (int) now('Asia/Jakarta')->year) as $nip => $monthly) {
                $indices=[]; foreach ($source as $i=>$item) if (($item['data']['kepesertaan_nip']??'')===(string)$nip) $indices[]=$i;
                if (count($indices)===1) { $i=$indices[0]; $source[$i]['iuran']=$monthly['months']; $source[$i]['errors']=array_merge($source[$i]['errors'],$monthly['errors']); }
                elseif (!$indices) $source[]=['sheet'=>$monthly['sheet'],'row'=>$monthly['row'],'data'=>['kepesertaan_nip'=>(string)$nip,'kepesertaan_nama'=>$monthly['name']],'errors'=>$monthly['errors'],'iuran'=>$monthly['months']];
                else foreach ($indices as $i) $source[$i]['errors'][]='Iuran tidak dapat dipasangkan karena NIP berulang dalam data peserta.';
            }
        }
        {
            $merged=[];
            foreach ($source as $item) {
                $key=json_encode($item['data']);
                if ($item['iuran'] && isset($merged[$key]) && $merged[$key]['iuran']) {
                    $merged[$key]['iuran']=array_merge($merged[$key]['iuran'],$item['iuran']);
                    $merged[$key]['errors']=array_merge($merged[$key]['errors'],$item['errors']);
                } else $merged[$key.($item['iuran']?'':':'.count($merged))]=$item;
            }
            $source=array_values($merged);
        }
        if (!$source) throw \Illuminate\Validation\ValidationException::withMessages(['file' => 'Tidak ditemukan tabel peserta dengan header Nama Peserta, NIP UMM, dan NIK.']);
        return $this->plan($source);
    }

    public function classify(array $data): array
    {
        $key = fn ($value) => strtolower(preg_replace('/[^a-z0-9]/i', '', $value));
        $statuses = [
            'peserta' => 'Peserta', 'aktif' => 'Peserta', 'pesertaaktif' => 'Peserta',
            'pensiunan' => 'Pensiunan', 'pensiun' => 'Pensiunan',
            'ditunda' => 'Pensiun Ditunda', 'pensiunditunda' => 'Pensiun Ditunda', 'pensiunanditunda' => 'Pensiun Ditunda',
            'sekaligus' => 'Pensiun Sekaligus', 'pensiunsekaligus' => 'Pensiun Sekaligus', 'pensiunansekaligus' => 'Pensiun Sekaligus',
            'pensiunanyangsudahtidakberhakmenerimamp' => 'Pensiunan Yang Sudah Tidak Berhak Menerima MP',
            'tidakberhakmenerimamp' => 'Pensiunan Yang Sudah Tidak Berhak Menerima MP',
        ];
        $types = [
            'normal' => 'Pensiun Normal', 'pensiunnormal' => 'Pensiun Normal',
            'anak' => 'Pensiun Anak', 'pensiunanak' => 'Pensiun Anak',
            'janda' => 'Pensiun Janda/Duda', 'duda' => 'Pensiun Janda/Duda', 'jandaduda' => 'Pensiun Janda/Duda',
            'pensiunjanda' => 'Pensiun Janda/Duda', 'pensiunduda' => 'Pensiun Janda/Duda', 'pensiunjandaduda' => 'Pensiun Janda/Duda',
            'dipercepat' => 'Pensiun Dipercepat', 'pensiundipercepat' => 'Pensiun Dipercepat',
        ];
        $errors = [];
        $statusRaw = $data['kepesertaan_status_kepesertaan'] ?? '';
        $typeRaw = $data['kepesertaan_jenis_pensiun'] ?? '';
        $status = $statusRaw !== '' ? ($statuses[$key($statusRaw)] ?? null) : null;
        $type = $typeRaw !== '' ? ($types[$key($typeRaw)] ?? null) : null;
        if ($statusRaw !== '' && !$status) {
            $fromStatus = $types[$key($statusRaw)] ?? null;
            if ($fromStatus) {
                $status = 'Pensiunan';
                if ($type && $type !== $fromStatus) $errors[] = 'Status dan jenis pensiun saling bertentangan.';
                $type = $fromStatus;
            } else $errors[] = 'Status kepesertaan tidak dikenali: '.$statusRaw;
        }
        if ($typeRaw !== '' && !$type) {
            $special = $statuses[$key($typeRaw)] ?? null;
            if ($special && $special !== 'Peserta' && $special !== 'Pensiunan') {
                if ($status && $status !== 'Pensiunan' && $status !== $special) $errors[] = 'Status dan jenis pensiun saling bertentangan.';
                $status = $special;
                unset($data['kepesertaan_jenis_pensiun']);
            } else $errors[] = 'Jenis pensiun tidak dikenali: '.$typeRaw;
        }
        if ($type) {
            $data['kepesertaan_jenis_pensiun'] = $type;
            if (!$status) $status = 'Pensiunan';
            if ($status !== 'Peserta' && $status !== 'Pensiunan') $errors[] = 'Jenis pensiun tidak sesuai status kepesertaan.';
        }
        if ($status) $data['kepesertaan_status_kepesertaan'] = $status;
        return [$data, $errors];
    }

    public function plan(array $source, bool $lock = false): array
    {
        $query = DB::table('kepesertaans');
        if ($lock) $query->lockForUpdate();
        $existing = $query->get();
        $years=[]; foreach($source as $item) foreach($item['iuran']??[] as $month) $years[]=$month['year'];
        $iuranQuery=DB::table('kepesertaan_iurans')->where('kepesertaan_iuran_type','iuran_total')->whereIn('kepesertaan_iuran_tahun',array_unique($years));
        if ($lock) $iuranQuery->lockForUpdate();
        $existingIuran=$iuranQuery->get()->groupBy(fn($entry)=>$entry->kepesertaan_iuran_kepesertaan_id.':'.$entry->kepesertaan_iuran_tahun.':'.$entry->kepesertaan_iuran_bulan);
        $rows = []; $identities = [];
        foreach ($source as $item) {
            $data = $item['data']; $errors = $item['errors'];
            $iuranErrors = array_values(array_filter($errors, fn ($error) => preg_match('/iuran|PHDP bulanan|Nominal PHDP|Nominal Iuran/i', $error)));
            $errors = array_values(array_diff($errors, $iuranErrors));
            $iuranErrors = array_values(array_unique(array_merge($item['source_iuran_errors'] ?? [], $iuranErrors)));
            $item['source_iuran_errors'] = $iuranErrors;
            $nik = (string) ($data['kepesertaan_nik'] ?? '');
            if (ctype_digit($nik) && strlen($nik) > 16) {
                $removed = substr($nik, 16);
                $data['kepesertaan_nik'] = substr($nik, 0, 16);
                $item['source_warnings'][] = 'NIK kepanjangan: '.strlen($removed).' digit terakhir ('.$removed.') dihapus. NIK yang digunakan: '.$data['kepesertaan_nik'].'.';
            }
            $realNik = !empty($data['kepesertaan_nik']) && !preg_match('/^0+$/', $data['kepesertaan_nik']);
            $matches = $existing->filter(fn ($old) => ($realNik && (string) $old->kepesertaan_nik === $data['kepesertaan_nik']) || (!empty($data['kepesertaan_nip']) && (string) $old->kepesertaan_nip === $data['kepesertaan_nip']));
            $identityWarnings = [];
            if ($matches->isEmpty() && !empty($data['kepesertaan_nama']) && !empty($data['kepesertaan_tanggal_lahir'])) {
                $nameKey = fn ($name) => mb_strtolower(preg_replace('/[^\pL\pN]/u', '', (string)$name));
                $samePerson = $existing->filter(fn ($old) => $nameKey($old->kepesertaan_nama) === $nameKey($data['kepesertaan_nama']) && (string)$old->kepesertaan_tanggal_lahir === $data['kepesertaan_tanggal_lahir']);
                if ($samePerson->count() === 1) {
                    $matches = $samePerson;
                    $identityWarnings[] = 'NIK/NIP berbeda dari data lama. Nama dan tanggal lahir cocok dengan satu peserta; data lama diperbarui tanpa membuat duplikat.';
                } elseif ($samePerson->count() > 1) $matches = $samePerson;
            }
            $nipMatches = $existing->filter(fn ($old) => !empty($data['kepesertaan_nip']) && (string)$old->kepesertaan_nip === $data['kepesertaan_nip']);
            if ($matches->count() > 1 && $nipMatches->count() === 1) {
                $identityWarnings[] = 'NIK juga dipakai peserta lain. Data ini dicocokkan berdasarkan NIP UMM yang unik; data peserta lain tidak diubah.';
                $matches = $nipMatches;
            }
            if (empty($data['kepesertaan_nip'])) {
                $identityWarnings[] = 'NIP belum tersedia; tetap dapat diimpor dan dilengkapi nanti.';
                if (!$realNik) {
                    $normalizeName = fn ($name) => mb_strtolower(preg_replace('/[^\pL\pN]/u', '', (string)$name));
                    $nameMatches = $existing->filter(fn ($old) => $normalizeName($old->kepesertaan_nama) === $normalizeName($data['kepesertaan_nama'] ?? ''));
                    if (!empty($data['kepesertaan_tanggal_lahir'])) $nameMatches = $nameMatches->filter(fn ($old) => (string)$old->kepesertaan_tanggal_lahir === $data['kepesertaan_tanggal_lahir']);
                    if ($nameMatches->count() === 1) {
                        $matches = $nameMatches;
                        $identityWarnings[] = 'NIK dan NIP belum tersedia. Dicocokkan dengan satu data lama yang namanya sama'.(!empty($data['kepesertaan_tanggal_lahir']) ? ' dan tanggal lahirnya sama.' : '.');
                    } elseif ($nameMatches->count() > 1) {
                        $matches = $nameMatches;
                        $identityWarnings[] = 'Nama dan tanggal lahir cocok dengan beberapa data lama. Duplikat lama perlu digabung; impor tidak membuat peserta tambahan.';
                    } else {
                        $identityWarnings[] = 'NIK dan NIP belum tersedia; akan dibuat sebagai peserta baru tanpa menimpa data lama.';
                    }
                }
            }
            $candidates = $matches->map(fn ($person) => ['id' => $person->kepesertaan_id, 'name' => $person->kepesertaan_nama, 'nip' => $person->kepesertaan_nip, 'status' => $person->kepesertaan_status_kepesertaan])->values()->all();
            if (!empty($item['target_id'])) {
                $chosen = $existing->firstWhere('kepesertaan_id', $item['target_id']);
                if ($chosen && in_array($chosen->kepesertaan_id, array_column($candidates, 'id'), true)) $matches = collect([$chosen]);
                else $errors[] = 'Data tujuan tidak lagi cocok. Bandingkan ulang file.';
            }
            if ($matches->count() > 1) $errors[] = 'NIK/NIP cocok dengan lebih dari satu peserta. Pilih data tujuan.';
            $old = $matches->count() === 1 ? $matches->first() : null;
            if (!$realNik) {
                if ($old && !empty($old->kepesertaan_nik) && !preg_match('/^0+$/', $old->kepesertaan_nik)) unset($data['kepesertaan_nik']);
                else $data['kepesertaan_nik'] = '0000000000000000';
            }
            $rules = ['kepesertaan_nik' => 'required|regex:/^[0-9]{16}$/', 'kepesertaan_nip' => 'required|regex:/^[0-9]+$/|max:50',
                'kepesertaan_status_kepesertaan' => 'required|in:Peserta,Pensiunan,Pensiun Ditunda,Pensiun Sekaligus,Pensiunan Yang Sudah Tidak Berhak Menerima MP',
                'kepesertaan_nama' => 'required|string|max:100', 'kepesertaan_jenis_kelamin' => 'required|in:Laki-Laki,Perempuan',
                'kepesertaan_tanggal_lahir' => 'required|date_format:Y-m-d', 'kepesertaan_terhitung_mulai_tanggal' => 'required|date_format:Y-m-d',
                'kepesertaan_terhitung_mulai_tanggal_pensiun' => 'required|date_format:Y-m-d',
                'kepesertaan_tempat_lahir' => 'required|string|max:50', 'kepesertaan_status_jabatan' => 'required|string|max:150',
                'kepesertaan_gaji_pokok' => 'required|integer|min:0', 'kepesertaan_telphone_1' => 'nullable|string|max:50',
                'kepesertaan_email_1' => 'nullable|email|max:100', 'kepesertaan_email_2' => 'nullable|email|max:100',
                'kepesertaan_alamat' => 'nullable|string', 'kepesertaan_jenis_pensiun' => 'nullable|required_if:kepesertaan_status_kepesertaan,Pensiunan|in:Pensiun Normal,Pensiun Anak,Pensiun Janda/Duda,Pensiun Dipercepat', 'kepesertaan_npwp' => 'nullable|string|max:50'];
            $attributes = [];
            // Missing profile fields are warnings during import; keep absent dates/amounts null.
            $profile = array_merge($old ? (array) $old : [], $data);
            $missing = [];
            foreach ($rules as $field => &$rule) {
                if ($field === 'kepesertaan_nama') continue;
                if (str_starts_with($rule, 'required|') && (!array_key_exists($field, $profile) || $profile[$field] === null || $profile[$field] === '')) $missing[] = $field;
                if (str_starts_with($rule, 'required|')) $rule = 'nullable|'.substr($rule, strlen('required|'));
            }
            unset($rule);
            foreach (self::FIELDS as [$field, $label]) $attributes[$field] = $label;
            $validator = Validator::make(array_merge($old ? (array) $old : [], $data), $rules, [
                'required' => ':attribute wajib diisi.', 'regex' => 'Format :attribute tidak valid.',
                'in' => ':attribute tidak sesuai pilihan aplikasi.', 'max' => ':attribute maksimal :max karakter.',
                'email' => 'Format :attribute tidak valid.', 'date_format' => 'Format :attribute harus YYYY-MM-DD.',
            ], $attributes);
            $warnings = array_merge($item['source_warnings'] ?? [], $identityWarnings);
            if (($data['kepesertaan_nik'] ?? '') === '0000000000000000') {
                $warnings[] = 'NIK belum tersedia; disimpan sebagai 0000000000000000 dan tidak dipakai untuk mencocokkan identitas.';
                if (!in_array('kepesertaan_nik', $missing, true)) $missing[] = 'kepesertaan_nik';
            }
            foreach ($missing as $field) $warnings[] = ($attributes[$field] ?? $field).' belum diisi; dapat dilengkapi setelah impor.';
            $validator->passes();
            foreach ($validator->failed() as $field => $failedRules) {
                // Format/pilihan yang berbeda cukup diperingatkan; batas penyimpanan tetap diperiksa.
                $messages = $validator->errors()->get($field);
                if (array_diff(array_keys($failedRules), ['Regex', 'Email', 'In', 'RequiredIf'])) $errors = array_merge($errors, $messages);
                else $warnings = array_merge($warnings, $messages);
            }
            if (mb_strlen($data['kepesertaan_alamat'] ?? '') > 100) $warnings[] = 'Alamat lebih dari 100 karakter. Alamat lengkap tetap disimpan.';
            if (isset($data['kepesertaan_nik']) && mb_strlen($data['kepesertaan_nik']) > 16) $errors[] = 'NIK melebihi kapasitas 16 karakter.';
            $changes = []; $before = [];
            foreach ($data as $field => $value) {
                $before[$field] = $old ? $old->$field : null;
                if (!$old || (string) $old->$field !== (string) $value) $changes[$field] = ['old' => $before[$field], 'new' => $value];
            }
            $iuranBefore=[]; $seenMonths=[];
            foreach($item['iuran']??[] as $month) {
                $key=$month['year'].'-'.$month['month'];
                if(isset($seenMonths[$key])) $iuranErrors[]='Bulan iuran berulang: '.$key;
                $seenMonths[$key]=true;
                $entries=$old?$existingIuran->get($old->kepesertaan_id.':'.$month['year'].':'.$month['month'],collect()):collect();
                if($entries->count()>1) $iuranErrors[]='Database memiliki lebih dari satu iuran untuk '.$key.'.';
                $entry=$entries->first();
                $iuranBefore[$key]=$entry?(array)$entry:null;
                $oldValues=$entry?['phdp'=>$entry->kepesertaan_iuran_jumlah,'peserta'=>$entry->imported_iuran_peserta??($entry->kepesertaan_iuran_jumlah===null?null:$entry->kepesertaan_iuran_jumlah*0.075),'pk'=>$entry->imported_iuran_pk??($entry->kepesertaan_iuran_jumlah===null?null:$entry->kepesertaan_iuran_jumlah*0.057)]:[];
                foreach($month['values'] as $field=>$value) {
                    $previous=$oldValues[$field]??null;
                    if($previous===null || abs((float)$previous-(float)$value)>0.00005) $changes['iuran:'.$key.':'.$field]=['old'=>$previous,'new'=>$value];
                }
            }
            $rowIndex = count($rows);
            foreach (['kepesertaan_nik', 'kepesertaan_nip'] as $field) if (!empty($data[$field]) && !($field === 'kepesertaan_nik' && preg_match('/^0+$/', $data[$field]))) $identities[$field.':'.$data[$field]][] = $rowIndex;
            if ($old) $identities['id:'.$old->kepesertaan_id][] = $rowIndex;
            $item['data'] = $data;
            $item['candidates'] = $candidates;
            $item['iuran_errors'] = $iuranErrors;
            if ($iuranErrors) {
                $warnings[] = 'Profil tetap bisa diimpor. Iuran baris ini dilewati: '.implode(' ', array_unique($iuranErrors));
                foreach (array_keys($changes) as $field) if (str_starts_with($field, 'iuran:')) unset($changes[$field]);
            }
            $rows[] = $item + ['id' => $old->kepesertaan_id ?? null, 'before' => $before, 'iuran_before'=>$iuranBefore, 'warnings' => $warnings, 'missing_fields' => $missing, 'changes' => $changes, 'status' => !$old ? 'new' : ($changes ? 'changed' : 'unchanged')];
            $rows[$rowIndex]['errors'] = $errors;
        }
        foreach ($identities as $identity => $indices) if (count($indices) > 1) foreach ($indices as $i) {
            if (str_starts_with($identity, 'kepesertaan_nik:')) $rows[$i]['warnings'][] = 'NIK berulang dalam file impor. Pencocokan tetap menggunakan NIP UMM masing-masing.';
            else $rows[$i]['errors'][] = 'Identitas peserta berulang dalam file impor.';
        }
        $counts = ['new' => 0, 'changed' => 0, 'unchanged' => 0, 'error' => 0];
        foreach ($rows as $row) $counts[$row['errors'] ? 'error' : $row['status']]++;
        return ['rows' => $rows, 'counts' => $counts];
    }
}
