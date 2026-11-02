<?php
namespace Tests\Feature;

use App\Models\{User, Role};
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\{Spreadsheet, Writer\Xlsx};
use Tests\TestCase;

class KepesertaanImportTest extends TestCase
{
    use DatabaseTransactions;

    private function fixture(): void
    {
        $this->actingAs((new User(['user_id' => 'import-test']))->setRelation('role', new Role(['role_name' => 'Admin', 'role_status' => 1])));
        $data = [];
        foreach (DB::select('SHOW COLUMNS FROM kepesertaans') as $column) {
            if ($column->Null === 'NO' && $column->Default === null && !str_contains($column->Extra, 'auto_increment')) {
                $data[$column->Field] = preg_match('/int|decimal|float|double/', $column->Type) ? 0 : (preg_match('/date|time/', $column->Type) ? '2020-01-01' : '');
            }
        }
        DB::table('kepesertaans')->insert(array_merge($data, ['kepesertaan_id' => 'import-fixture', 'kepesertaan_nik' => '0123456789012345',
            'kepesertaan_nip' => '001234', 'kepesertaan_nama' => 'Nama lama', 'kepesertaan_jenis_kelamin' => 'Laki-Laki',
            'kepesertaan_tempat_lahir' => 'Malang', 'kepesertaan_status_jabatan' => 'Dosen', 'kepesertaan_status_kepesertaan' => 'Peserta',
            'kepesertaan_email_1' => 'lama@example.test', 'kepesertaan_foto' => 'foto-lama.png']));
    }

    public function test_preview_paginates_and_can_import_selected_rows_from_different_pages()
    {
        $this->fixture();
        $template = (array) DB::table('kepesertaans')->where('kepesertaan_id', 'import-fixture')->first();
        $csv = "Nama Peserta,NIP UMM,NIK\n";
        for ($i = 0; $i < 31; $i++) {
            $row = array_merge($template, ['kepesertaan_id' => 'page-'.$i, 'kepesertaan_nip' => (string) (98760000 + $i), 'kepesertaan_nik' => (string) (1234567890000000 + $i)]);
            DB::table('kepesertaans')->insert($row);
            $csv .= "Baru $i,{$row['kepesertaan_nip']},{$row['kepesertaan_nik']}\n";
        }
        $preview = $this->postJson(route('kepesertaan.import.preview'), ['file' => UploadedFile::fake()->createWithContent('pages.csv', $csv)])->assertOk();
        $this->assertCount(31, $preview->json('selectable'));
        $this->assertSame(25, substr_count($preview->json('html'), 'form-check-input participant-import-row'));
        $page = $this->getJson(route('kepesertaan.import.page', ['token' => $preview->json('token'), 'page' => 2]))->assertOk();
        $this->assertSame(6, substr_count($page->json('html'), 'form-check-input participant-import-row'));
        $search = $this->getJson(route('kepesertaan.import.page', ['token' => $preview->json('token'), 'page' => 1, 'search' => 'bArU 30']))->assertOk();
        $this->assertSame(1, substr_count($search->json('html'), 'form-check-input participant-import-row'));
        $this->assertStringContainsString('value="30" data-status="changed"', $search->json('html'));
        $byNip = $this->getJson(route('kepesertaan.import.page', ['token' => $preview->json('token'), 'page' => 1, 'search' => '98760030']))->assertOk();
        $this->assertStringContainsString('value="30" data-status="changed"', $byNip->json('html'));
        $empty = $this->getJson(route('kepesertaan.import.page', ['token' => $preview->json('token'), 'page' => 1, 'search' => 'Tidak ditemukan']))->assertOk();
        $this->assertStringContainsString('Tidak ada peserta yang cocok', $empty->json('html'));
        $this->getJson(route('kepesertaan.import.page', ['token' => 'invalid', 'page' => 2]))->assertStatus(409);
        $this->postJson(route('kepesertaan.import.commit'), ['token' => $preview->json('token'), 'confirmed' => 1, 'selected' => [0, 30]])->assertOk();
        $this->assertDatabaseHas('kepesertaans', ['kepesertaan_id' => 'page-0', 'kepesertaan_nama' => 'Baru 0']);
        $this->assertDatabaseHas('kepesertaans', ['kepesertaan_id' => 'page-30', 'kepesertaan_nama' => 'Baru 30']);
        $this->assertDatabaseHas('kepesertaans', ['kepesertaan_id' => 'page-1', 'kepesertaan_nama' => 'Nama lama']);
    }

    public function test_format_warnings_and_long_addresses_remain_selectable_and_are_saved_in_full()
    {
        $this->fixture();
        $address = str_repeat('Alamat lengkap ', 20);
        $csv = "Nama Peserta,NIP UMM,NIK,Alamat,Email 1\nNama baru,001234,0123456789012345,$address,email-belum-valid\n";
        $preview = $this->postJson(route('kepesertaan.import.preview'), ['file' => UploadedFile::fake()->createWithContent('warning.csv', $csv)])->assertOk()->assertJsonPath('can_commit', true);
        $this->assertCount(1, $preview->json('selectable'));
        $this->assertStringContainsString('tetap bisa dipilih', $preview->json('html'));
        $this->postJson(route('kepesertaan.import.commit'), ['token' => $preview->json('token'), 'confirmed' => 1, 'selected' => [0]])->assertOk();
        $this->assertDatabaseHas('kepesertaans', ['kepesertaan_id' => 'import-fixture', 'kepesertaan_alamat' => trim($address), 'kepesertaan_email_1' => 'email-belum-valid']);
    }

    public function test_new_participant_with_missing_profile_fields_is_selectable_and_saved_without_invented_dates()
    {
        $this->fixture();
        $preview = $this->postJson(route('kepesertaan.import.preview'), ['file' => UploadedFile::fake()->createWithContent('incomplete.csv', "Nama Peserta,NIP UMM,NIK\nPeserta Belum Lengkap,98765432,\n")])->assertOk()->assertJsonPath('new', 1);
        $this->assertCount(1, $preview->json('selectable'));
        $this->assertStringContainsString('belum diisi', $preview->json('html'));
        $this->postJson(route('kepesertaan.import.commit'), ['token' => $preview->json('token'), 'confirmed' => 1, 'selected' => [0]])->assertOk();
        $this->assertDatabaseHas('kepesertaans', ['kepesertaan_nip' => '98765432', 'kepesertaan_nik' => '0000000000000000', 'kepesertaan_tanggal_lahir' => null, 'kepesertaan_gaji_pokok' => null]);
    }

    public function test_missing_fields_can_be_completed_during_import_and_invalid_dates_can_be_corrected()
    {
        $this->fixture();
        $preview = $this->postJson(route('kepesertaan.import.preview'), ['file' => UploadedFile::fake()->createWithContent('complete.csv', "Nama Peserta,NIP UMM,NIK\nPeserta Baru,98765433,\n")])->assertOk();
        $this->assertStringContainsString('Lengkapi data kosong di sini', $preview->json('html'));
        $body = ['token' => $preview->json('token'), 'confirmed' => 1, 'selected' => [0], 'additions' => [0 => ['kepesertaan_tanggal_lahir' => '2026-02-31']]];
        $this->postJson(route('kepesertaan.import.commit'), $body)->assertUnprocessable();
        $this->assertDatabaseMissing('kepesertaans', ['kepesertaan_nip' => '98765433']);
        $body['additions'][0] = ['kepesertaan_tanggal_lahir' => '1980-01-01', 'kepesertaan_nik' => '1234567890123456', 'kepesertaan_gaji_pokok' => '3500000', 'kepesertaan_status_kepesertaan' => 'Peserta'];
        $this->postJson(route('kepesertaan.import.commit'), $body)->assertOk();
        $this->assertDatabaseHas('kepesertaans', ['kepesertaan_nip' => '98765433', 'kepesertaan_tanggal_lahir' => '1980-01-01', 'kepesertaan_nik' => '1234567890123456', 'kepesertaan_gaji_pokok' => 3500000]);
    }

    public function test_placeholder_nik_does_not_merge_people_and_iuran_can_be_imported_without_phdp()
    {
        $this->fixture();
        $csv = "Nama Peserta,NIP UMM,NIK,Tahun,Bulan,PHDP,Iuran Peserta,Iuran Pemberi Kerja\nOrang Satu,98765434,,2026,1,,100000,75000\nOrang Dua,98765435,0000000000000000,2026,1,,200000,150000\n";
        $preview = $this->postJson(route('kepesertaan.import.preview'), ['file' => UploadedFile::fake()->createWithContent('partial.csv', $csv), 'iuran_year' => 2026])->assertOk()->assertJsonPath('new', 2);
        $this->assertCount(2, $preview->json('selectable'));
        $this->postJson(route('kepesertaan.import.commit'), ['token' => $preview->json('token'), 'confirmed' => 1, 'selected' => [0, 1]])->assertOk();
        foreach (['98765434' => 100000, '98765435' => 200000] as $nip => $amount) {
            $participant = DB::table('kepesertaans')->where('kepesertaan_nip', (string)$nip)->first();
            $this->assertSame('0000000000000000', $participant->kepesertaan_nik);
            $this->assertDatabaseHas('kepesertaan_iurans', ['kepesertaan_iuran_kepesertaan_id' => $participant->kepesertaan_id, 'kepesertaan_iuran_jumlah' => null, 'imported_iuran_peserta' => $amount]);
        }
        // Reimport must match each person by NIP, despite both having the same placeholder NIK.
        $this->postJson(route('kepesertaan.import.preview'), ['file' => UploadedFile::fake()->createWithContent('partial.csv', $csv), 'iuran_year' => 2026])->assertOk()->assertJsonPath('new', 0)->assertJsonPath('can_commit', true);
    }

    public function test_unchanged_participant_with_missing_data_can_be_selected_and_completed()
    {
        $this->fixture();
        DB::table('kepesertaans')->where('kepesertaan_id', 'import-fixture')->update(['kepesertaan_terhitung_mulai_tanggal_pensiun' => null]);
        $preview = $this->postJson(route('kepesertaan.import.preview'), ['file' => $this->csv('Nama lama')])->assertOk()->assertJsonPath('can_commit', true);
        $this->assertSame('unchanged', $preview->json('selectable.0.status'));
        $this->assertStringContainsString('Tanggal pensiun belum diisi', $preview->json('html'));
        $this->assertDoesNotMatchRegularExpression('/class="form-check-input participant-import-row"[^>]*\sdisabled/', $preview->json('html'));
        $this->postJson(route('kepesertaan.import.commit'), ['token' => $preview->json('token'), 'confirmed' => 1, 'selected' => [0], 'additions' => [0 => ['kepesertaan_terhitung_mulai_tanggal_pensiun' => '2040-01-01']]])->assertOk();
        $this->assertDatabaseHas('kepesertaans', ['kepesertaan_id' => 'import-fixture', 'kepesertaan_terhitung_mulai_tanggal_pensiun' => '2040-01-01']);
    }

    public function test_numeric_excel_nik_is_a_warning_and_does_not_disable_selection_or_block_import()
    {
        $this->fixture();
        $book = new Spreadsheet();
        $sheet = $book->getActiveSheet()->setTitle('peserta_aktif');
        $sheet->fromArray([['Nama Peserta', 'NIP UMM', 'NIK', 'Gaji Terakhir'], ['Nama lama', '001234', 1234567890123456, 3980375]]);
        $sheet->setCellValueExplicit('B2', '001234', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        $path = tempnam(sys_get_temp_dir(), 'nik-warning-');
        try {
            (new Xlsx($book))->save($path);
            $preview = $this->postJson(route('kepesertaan.import.preview'), ['file' => new UploadedFile($path, 'warning.xlsx', null, null, true)])->assertOk()->assertJsonPath('can_commit', true);
            $this->assertCount(1, $preview->json('selectable'));
            $this->assertStringContainsString('NIK berupa angka Excel', $preview->json('html'));
            $this->assertDoesNotMatchRegularExpression('/class="form-check-input participant-import-row"[^>]*\sdisabled/', $preview->json('html'));
            $this->postJson(route('kepesertaan.import.commit'), ['token' => $preview->json('token'), 'confirmed' => 1, 'selected' => [0]])->assertOk();
            $this->assertDatabaseHas('kepesertaans', ['kepesertaan_id' => 'import-fixture', 'kepesertaan_nik' => '0123456789012345', 'kepesertaan_gaji_pokok' => 3980375]);
        } finally { $book->disconnectWorksheets(); if (is_file($path)) unlink($path); }
    }

    public function test_overlong_nik_is_trimmed_with_warning_and_remains_selectable()
    {
        $this->fixture();
        $csv = "Nama Peserta,NIP UMM,NIK\nPeserta NIK Panjang,98765439,3507186811000002020\n";
        $preview = $this->postJson(route('kepesertaan.import.preview'), ['file' => UploadedFile::fake()->createWithContent('long.csv', $csv)])->assertOk()->assertJsonPath('can_commit', true);
        $this->assertCount(1, $preview->json('selectable'));
        $this->assertStringContainsString('3 digit terakhir (020) dihapus', $preview->json('html'));
        $this->assertStringContainsString('3507186811000002', $preview->json('html'));
        $this->postJson(route('kepesertaan.import.commit'), ['token' => $preview->json('token'), 'confirmed' => 1, 'selected' => [0]])->assertOk();
        $this->assertDatabaseHas('kepesertaans', ['kepesertaan_nip' => '98765439', 'kepesertaan_nik' => '3507186811000002']);
    }

    public function test_shared_nik_does_not_block_import_when_each_participant_has_a_unique_nip()
    {
        $this->fixture();
        $other = (array) DB::table('kepesertaans')->where('kepesertaan_id', 'import-fixture')->first();
        $other['kepesertaan_id'] = 'shared-nik-other';
        $other['kepesertaan_nip'] = '001235';
        $other['kepesertaan_nama'] = 'Peserta lain';
        DB::table('kepesertaans')->insert($other);
        $csv = "Nama Peserta,NIP UMM,NIK\nNama pertama diperbarui,001234,0123456789012345\nNama kedua diperbarui,001235,0123456789012345\n";
        $preview = $this->postJson(route('kepesertaan.import.preview'), ['file' => UploadedFile::fake()->createWithContent('shared.csv', $csv)])->assertOk();
        $this->assertCount(2, $preview->json('selectable'));
        $this->assertStringContainsString('NIK berulang dalam file impor', $preview->json('html'));
        $this->postJson(route('kepesertaan.import.commit'), ['token' => $preview->json('token'), 'confirmed' => 1, 'selected' => [0]])->assertOk();
        $this->assertDatabaseHas('kepesertaans', ['kepesertaan_id' => 'import-fixture', 'kepesertaan_nama' => 'Nama pertama diperbarui']);
        $this->assertDatabaseHas('kepesertaans', ['kepesertaan_id' => 'shared-nik-other', 'kepesertaan_nama' => 'Peserta lain']);
    }

    public function test_participants_without_nip_or_real_nik_can_be_imported_and_reimport_does_not_duplicate_them()
    {
        $this->fixture();
        $csv = "Nama Peserta,NIP UMM,NIK\nPegawai Tanpa NIP,,\n";
        $preview = $this->postJson(route('kepesertaan.import.preview'), ['file' => UploadedFile::fake()->createWithContent('no-nip.csv', $csv)])->assertOk()->assertJsonPath('new', 1);
        $this->assertCount(1, $preview->json('selectable'));
        $this->assertStringContainsString('NIP belum tersedia; tetap dapat diimpor', $preview->json('html'));
        $this->postJson(route('kepesertaan.import.commit'), ['token' => $preview->json('token'), 'confirmed' => 1, 'selected' => [0]])->assertOk();
        $this->assertDatabaseHas('kepesertaans', ['kepesertaan_nama' => 'Pegawai Tanpa NIP', 'kepesertaan_nip' => '', 'kepesertaan_nik' => '0000000000000000']);
        $repeat = $this->postJson(route('kepesertaan.import.preview'), ['file' => UploadedFile::fake()->createWithContent('no-nip.csv', $csv)])->assertOk()->assertJsonPath('new', 0);
        $this->postJson(route('kepesertaan.import.commit'), ['token' => $repeat->json('token'), 'confirmed' => 1, 'selected' => [0]])->assertOk();
        $this->assertSame(1, DB::table('kepesertaans')->where('kepesertaan_nama', 'Pegawai Tanpa NIP')->count());
    }

    public function test_long_numeric_excel_nip_is_only_a_warning_and_other_fields_are_imported()
    {
        $this->fixture();
        $book = new Spreadsheet();
        $sheet = $book->getActiveSheet();
        $sheet->fromArray([['Nama Peserta', 'NIP UMM', 'NIK', 'Gaji Terakhir'], ['Nama lama', 123456789012345678, '', 4567890], ['Pegawai baru tanpa NIP pasti', 223456789012345678, '', 1234000]]);
        $path = tempnam(sys_get_temp_dir(), 'nip-warning-');
        try {
            (new Xlsx($book))->save($path);
            $preview = $this->postJson(route('kepesertaan.import.preview'), ['file' => new UploadedFile($path, 'nip.xlsx', null, null, true)])->assertOk();
            $this->assertCount(2, $preview->json('selectable'));
            $this->assertStringContainsString('NIP berupa angka Excel panjang', $preview->json('html'));
            $this->postJson(route('kepesertaan.import.commit'), ['token' => $preview->json('token'), 'confirmed' => 1, 'selected' => [0, 1]])->assertOk();
            $this->assertDatabaseHas('kepesertaans', ['kepesertaan_id' => 'import-fixture', 'kepesertaan_nip' => '001234', 'kepesertaan_gaji_pokok' => 4567890]);
            $this->assertDatabaseHas('kepesertaans', ['kepesertaan_nama' => 'Pegawai baru tanpa NIP pasti', 'kepesertaan_nip' => '', 'kepesertaan_gaji_pokok' => 1234000]);
        } finally { $book->disconnectWorksheets(); if (is_file($path)) unlink($path); }
    }

    public function test_reimport_matches_unique_name_and_birthdate_despite_punctuation_and_changed_nip()
    {
        $this->fixture();
        DB::table('kepesertaans')->where('kepesertaan_id', 'import-fixture')->update(['kepesertaan_nama' => "Nama As'ari, S.Kom", 'kepesertaan_tanggal_lahir' => '1980-01-01']);
        $csv = "Nama Peserta,NIP UMM,NIK,Tanggal Lahir\n\"Nama As;ari, S.Kom\",99887766,,1980-01-01\n";
        $preview = $this->postJson(route('kepesertaan.import.preview'), ['file' => UploadedFile::fake()->createWithContent('repeat.csv', $csv)])->assertOk()->assertJsonPath('new', 0);
        $this->postJson(route('kepesertaan.import.commit'), ['token' => $preview->json('token'), 'confirmed' => 1, 'selected' => [0]])->assertOk();
        $this->assertDatabaseHas('kepesertaans', ['kepesertaan_id' => 'import-fixture', 'kepesertaan_nip' => '99887766']);
    }

    public function test_invalid_month_does_not_block_profile_and_is_reported()
    {
        $this->fixture();
        $csv = "Nama Peserta,NIP UMM,NIK,Tahun,Bulan,PHDP\nProfil diperbarui,001234,0123456789012345,2026,13,1000\n";
        $preview = $this->postJson(route('kepesertaan.import.preview'), ['file' => UploadedFile::fake()->createWithContent('bad-month.csv', $csv)])->assertOk();
        $this->assertCount(1, $preview->json('selectable'));
        $result = $this->postJson(route('kepesertaan.import.commit'), ['token' => $preview->json('token'), 'confirmed' => 1, 'selected' => [0]])->assertOk();
        $this->assertStringContainsString('Tahun/bulan iuran tidak valid', $result->json('details.0.iuran'));
        $this->assertDatabaseHas('kepesertaans', ['kepesertaan_id' => 'import-fixture', 'kepesertaan_nama' => 'Profil diperbarui']);
    }

    public function test_ambiguous_identity_can_be_resolved_only_to_an_offered_target()
    {
        $this->fixture();
        $other = (array) DB::table('kepesertaans')->where('kepesertaan_id', 'import-fixture')->first();
        $other['kepesertaan_id'] = 'target-other';
        $other['kepesertaan_nama'] = 'Peserta lain';
        DB::table('kepesertaans')->insert($other);
        $preview = $this->postJson(route('kepesertaan.import.preview'), ['file' => $this->csv()])->assertOk();
        $this->assertStringContainsString('Pilih data lama yang akan diperbarui', $preview->json('html'));
        $this->postJson(route('kepesertaan.import.resolve'), ['token' => $preview->json('token'), 'index' => 0, 'target' => 'not-offered'])->assertUnprocessable();
        $resolved = $this->postJson(route('kepesertaan.import.resolve'), ['token' => $preview->json('token'), 'index' => 0, 'target' => 'import-fixture'])->assertOk();
        $this->assertCount(1, $resolved->json('selectable'));
        $this->postJson(route('kepesertaan.import.commit'), ['token' => $preview->json('token'), 'confirmed' => 1, 'selected' => [0]])->assertOk();
        $this->assertDatabaseHas('kepesertaans', ['kepesertaan_id' => 'target-other', 'kepesertaan_nama' => 'Peserta lain']);
    }

    private function csv(string $name = 'Nama baru'): UploadedFile
    {
        return UploadedFile::fake()->createWithContent('peserta.csv', "Nama Peserta,NIP UMM,NIK,Email 1\n$name,001234,0123456789012345,\n");
    }

    public function test_selected_valid_rows_import_salary_and_monthly_iuran_while_invalid_rows_are_skipped()
    {
        $this->fixture();
        $csv="Nama Peserta,NIP UMM,NIK,Gaji Terakhir,Tahun,Bulan,PHDP,Iuran Peserta,Iuran Pemberi Kerja\nNama lama,001234,0123456789012345,4500000,2026,1,3000000,222222.125,170000.5\nNama lama,001234,0123456789012345,4500000,2026,2,4000000,299999.75,220000\n,009999,INVALID,1000,2026,13,1000,75,57\n";
        $preview=$this->postJson(route('kepesertaan.import.preview'),['file'=>UploadedFile::fake()->createWithContent('iuran.csv',$csv),'iuran_year'=>2026])->assertOk()->assertJsonPath('can_commit',true);
        $this->assertStringContainsString('PHDP / gaji terakhir',$preview->json('html'));
        $this->assertStringContainsString('Iuran peserta',$preview->json('html'));
        $this->assertStringNotContainsString('Impor belum bisa disimpan',$preview->json('html'));
        $this->postJson(route('kepesertaan.import.commit'),['token'=>$preview->json('token'),'confirmed'=>1,'selected'=>[1]])->assertUnprocessable();
        $this->postJson(route('kepesertaan.import.commit'),['token'=>$preview->json('token'),'confirmed'=>1,'selected'=>[0]])->assertOk();
        $this->assertDatabaseHas('kepesertaans',['kepesertaan_id'=>'import-fixture','kepesertaan_gaji_pokok'=>4500000]);
        $this->assertDatabaseMissing('kepesertaans',['kepesertaan_nip'=>'009999']);
        $rows=DB::table('kepesertaan_iurans')->where('kepesertaan_iuran_kepesertaan_id','import-fixture')->where('kepesertaan_iuran_tahun',2026)->get();
        $this->assertCount(2,$rows);
        $this->assertEquals(222222.125,$rows->firstWhere('kepesertaan_iuran_bulan',1)->imported_iuran_peserta);
        $this->assertEquals(170000.5,$rows->firstWhere('kepesertaan_iuran_bulan',1)->imported_iuran_pk);
        $iuran=(new \App\Http\Controllers\Api\IuranController())->index(new \Illuminate\Http\Request(['year'=>2026]))['data']['peserta']->firstWhere('kepesertaan_id','import-fixture');
        $this->assertEqualsWithDelta(522221.875,(float)$iuran->iuran,0.0001);
        $details=(new \App\Http\Controllers\Api\TotalController())->index(new \Illuminate\Http\Request(['year'=>2026,'month'=>1]))['data']['rincian']->firstWhere('kepesertaan_id','import-fixture');
        $this->assertEquals(222222.125,$details->iuran_peserta);
        $this->assertEquals(170000.5,$details->iuran_pemberi_kerja);
    }

    public function test_unchecked_valid_participant_is_not_overwritten_and_does_not_block_selected_row()
    {
        $this->fixture();
        $other=(array)DB::table('kepesertaans')->where('kepesertaan_id','import-fixture')->first();
        $other['kepesertaan_id']='import-other'; $other['kepesertaan_nip']='001235'; $other['kepesertaan_nik']='0123456789012346';
        DB::table('kepesertaans')->insert($other);
        $csv="Nama Peserta,NIP UMM,NIK\nDipilih,001234,0123456789012345\nTidak dipilih,001235,0123456789012346\n";
        $preview=$this->postJson(route('kepesertaan.import.preview'),['file'=>UploadedFile::fake()->createWithContent('pilihan.csv',$csv)])->assertOk()->assertJsonPath('changed',2);
        DB::table('kepesertaans')->where('kepesertaan_id','import-other')->update(['kepesertaan_nama'=>'Edit dari akun lain']);
        $this->postJson(route('kepesertaan.import.commit'),['token'=>$preview->json('token'),'confirmed'=>1,'selected'=>[0]])->assertOk();
        $this->assertDatabaseHas('kepesertaans',['kepesertaan_id'=>'import-fixture','kepesertaan_nama'=>'Dipilih']);
        $this->assertDatabaseHas('kepesertaans',['kepesertaan_id'=>'import-other','kepesertaan_nama'=>'Edit dari akun lain']);
    }

    public function test_xlsx_reads_only_selected_year_from_monthly_sheet()
    {
        $this->fixture();
        $book=new Spreadsheet(); $sheet=$book->getActiveSheet()->setTitle('Iuran Bulanan Bebas');
        $sheet->setCellValue('B2','NIP UMM')->setCellValue('C2','Nama Peserta');
        $sheet->setCellValueExplicit('B4','001234',\PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)->setCellValue('C4','Nama lama');
        foreach([11=>2026,14=>2027] as $col=>$year) {
            $sheet->setCellValueByColumnAndRow($col,2,\PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel(new \DateTime($year.'-01-01')));
            foreach(['Gaji Pokok','Iuran Peserta','Iuran PK'] as $offset=>$header) $sheet->setCellValueByColumnAndRow($col+$offset,3,$header);
            foreach([3000000,225000.125,171000.125] as $offset=>$amount) $sheet->setCellValueByColumnAndRow($col+$offset,4,$amount);
        }
        $path=tempnam(sys_get_temp_dir(),'iuran-year-');
        try {
            (new Xlsx($book))->save($path);
            $preview=$this->postJson(route('kepesertaan.import.preview'),['file'=>new UploadedFile($path,'iuran.xlsx',null,null,true),'iuran_year'=>2026])->assertOk()->assertJsonPath('can_commit',true);
            $this->postJson(route('kepesertaan.import.commit'),['token'=>$preview->json('token'),'confirmed'=>1,'selected'=>[0]])->assertOk();
            $this->assertDatabaseHas('kepesertaan_iurans',['kepesertaan_iuran_kepesertaan_id'=>'import-fixture','kepesertaan_iuran_tahun'=>2026,'imported_iuran_peserta'=>225000.125]);
            $this->assertDatabaseMissing('kepesertaan_iurans',['kepesertaan_iuran_kepesertaan_id'=>'import-fixture','kepesertaan_iuran_tahun'=>2027]);
        } finally {$book->disconnectWorksheets();if(is_file($path))unlink($path);}
    }

    public function test_valid_new_participant_is_created_only_after_confirmation()
    {
        $this->fixture();
        $csv = "Nama Peserta,NIP UMM,NIK,Jenis Kelamin,Tempat Lahir,Tanggal Lahir,TMT Masuk,Status Pegawai,Gaji Terakhir,Tanggal Pensiun,Status Kepesertaan\nPeserta Baru,000567,0123456789012346,L,Malang,1980-01-01,2000-01-01,Tendik,1000000,2040-01-01,Peserta\n";
        $preview = $this->postJson(route('kepesertaan.import.preview'), ['file' => UploadedFile::fake()->createWithContent('baru.csv', $csv), 'csv_sheet' => 'peserta_aktif'])->assertOk()->assertJsonPath('can_commit', true)->assertJsonPath('new', 1);
        $this->assertDatabaseMissing('kepesertaans', ['kepesertaan_nip' => '000567']);
        $this->postJson(route('kepesertaan.import.commit'), ['token' => $preview->json('token'), 'confirmed' => 1, 'selected' => [0]])->assertOk();
        $this->assertDatabaseHas('kepesertaans', ['kepesertaan_nip' => '000567', 'kepesertaan_nik' => '0123456789012346', 'kepesertaan_nama' => 'Peserta Baru', 'kepesertaan_jenis_kelamin' => 'Laki-Laki', 'kepesertaan_status_jabatan' => 'Karyawan']);
    }

    public function test_preview_confirmation_and_stale_preview_do_not_silently_overwrite()
    {
        $this->fixture();
        $url = route('kepesertaan.import.preview');
        $preview = $this->postJson($url, ['file' => $this->csv(), 'csv_sheet' => 'peserta_aktif'])->assertOk()->assertJsonPath('can_commit', true);
        $this->assertDatabaseHas('kepesertaans', ['kepesertaan_id' => 'import-fixture', 'kepesertaan_nama' => 'Nama lama']);
        $token = $preview->json('token');
        $this->postJson(route('kepesertaan.import.commit'), ['token' => $token])->assertUnprocessable();
        DB::table('kepesertaans')->where('kepesertaan_id', 'import-fixture')->update(['kepesertaan_nama' => 'Diubah akun lain']);
        $this->postJson(route('kepesertaan.import.commit'), ['token' => $token, 'confirmed' => 1, 'selected' => [0]])->assertStatus(409);
        $preview = $this->postJson($url, ['file' => $this->csv(), 'csv_sheet' => 'peserta_aktif'])->assertOk();
        $token = $preview->json('token');
        $this->postJson(route('kepesertaan.import.commit'), ['token' => $token, 'confirmed' => 1, 'selected' => [0]])->assertOk();
        $this->assertDatabaseHas('kepesertaans', ['kepesertaan_id' => 'import-fixture', 'kepesertaan_nama' => 'Nama baru', 'kepesertaan_email_1' => 'lama@example.test', 'kepesertaan_foto' => 'foto-lama.png']);
        $this->postJson(route('kepesertaan.import.commit'), ['token' => $token, 'confirmed' => 1, 'selected' => [0]])->assertStatus(409);
    }

    public function test_xlsx_ignores_admin_logs_and_rejects_duplicate_import_identities()
    {
        $this->fixture();
        $book = new Spreadsheet();
        $sheet = $book->getActiveSheet()->setTitle('Gabungan Peserta');
        $sheet->fromArray([['Nama Peserta', 'NIP UMM', 'NIK'], ['Nama baru', '001234', '0123456789012345'], ['Duplikat', '001234', '0123456789012345']]);
        foreach ([2, 3] as $row) $sheet->setCellValueExplicit('C'.$row, '0123456789012345', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        $book->createSheet()->setTitle('admin')->setCellValue('A1', 'Tidak boleh dibaca');
        $book->createSheet()->setTitle('logs')->setCellValue('A1', 'Tidak boleh dibaca');
        $path = tempnam(sys_get_temp_dir(), 'participant-import-');
        try {
            (new Xlsx($book))->save($path);
            $response = $this->postJson(route('kepesertaan.import.preview'), ['file' => new UploadedFile($path, 'peserta.xlsx', null, null, true), 'csv_sheet' => 'peserta_aktif'])->assertOk()->assertJsonPath('can_commit', false);
            $this->assertStringContainsString('Identitas peserta berulang', $response->json('html'));
            $this->assertStringNotContainsString('Tidak boleh dibaca', $response->json('html'));
            $this->postJson(route('kepesertaan.import.commit'), ['token' => $response->json('token'), 'confirmed' => 1, 'selected' => [0]])->assertUnprocessable();
        } finally { $book->disconnectWorksheets(); if (is_file($path)) unlink($path); }
        $user = (new User(['user_id' => 'view-only']))->setRelation('role', new Role(['role_name' => 'Bidang Investasi', 'role_status' => 1]));
        $this->actingAs($user);
        $this->postJson(route('kepesertaan.import.preview'), [])->assertForbidden();
        $this->postJson(route('kepesertaan.import.commit'), [])->assertForbidden();
    }

    public function test_mixed_csv_detects_every_category_without_a_separate_participant_selector()
    {
        $this->fixture();
        $csv = "Nama Peserta,NIP UMM,NIK,Jenis Kelamin,Tempat Lahir,Tanggal Lahir,TMT Masuk,Status Pegawai,Gaji Terakhir,Tanggal Pensiun,Status Kepesertaan,Jenis Pensiun\n";
        $categories = [
            ['Peserta', '', 'Peserta', null],
            ['Pensiunan', 'Anak', 'Pensiunan', 'Pensiun Anak'],
            ['Pensiunan', 'janda.duda', 'Pensiunan', 'Pensiun Janda/Duda'],
            ['Pensiunan', 'Sekaligus', 'Pensiun Sekaligus', null],
            ['Pensiun Ditunda', '', 'Pensiun Ditunda', null],
            ['', 'Normal', 'Pensiunan', 'Pensiun Normal'],
        ];
        foreach ($categories as $i => [$status, $type]) {
            $nip = '00800'.$i; $nik = '012345678900000'.$i;
            $csv .= "Peserta $i,$nip,$nik,L,Malang,1980-01-01,2000-01-01,Dosen,1000000,2040-01-01,$status,$type\n";
        }
        $response = $this->postJson(route('kepesertaan.import.preview'), ['file' => UploadedFile::fake()->createWithContent('gabungan.csv', $csv)])
            ->assertOk()->assertJsonPath('can_commit', true)->assertJsonPath('new', 6);
        $book = new Spreadsheet();
        $values = array_map('str_getcsv', explode("\n", trim($csv)));
        $book->getActiveSheet()->setTitle('Seluruh Data')->fromArray($values);
        foreach (['admin', 'logs'] as $name) $book->createSheet()->setTitle($name)->fromArray($values);
        $path = tempnam(sys_get_temp_dir(), 'mixed-participants-');
        try {
            (new Xlsx($book))->save($path);
            $response = $this->postJson(route('kepesertaan.import.preview'), ['file' => new UploadedFile($path, 'gabungan.xlsx', null, null, true)])
                ->assertOk()->assertJsonPath('can_commit', true)->assertJsonPath('new', 6);
        } finally { $book->disconnectWorksheets(); if (is_file($path)) unlink($path); }
        $this->postJson(route('kepesertaan.import.commit'), ['token' => $response->json('token'), 'confirmed' => 1, 'selected' => range(0, 5)])->assertOk();
        foreach ($categories as $i => [$status, $type, $expectedStatus, $expectedType]) {
            $this->assertDatabaseHas('kepesertaans', ['kepesertaan_nip' => '00800'.$i, 'kepesertaan_status_kepesertaan' => $expectedStatus, 'kepesertaan_jenis_pensiun' => $expectedType]);
        }
    }
}
