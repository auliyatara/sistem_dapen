<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

class ProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data['program_direktur'] = [
            'Memimpin, mengkoordinasi, mengawasi pelaksanaan tugas Pengurus.',
            'Bersama-sama dengan Pengurus yang lain membuat rencana kerja / program serta anggaran belanja dan pendapatan tiap tahun (Rencana Bisnis).',
            'Menginvestasikan kekayaan Dana Pensiun sesuai arahan investasi yang ditetapkan Pendiri.',
            'Memimpin Rapat Pengurus.',
            'Menandatangani bukti-bukti Penerimaan Uang dan Pengeluaran Uang.',
            'Menandatangani surat-surat yang berhubungan dengan kegiatan Dana Pensiun.',
            'Menandatangani surat keputusan yang berhubungan dengan pemberian manfaat pensiun.',
            'Mewakili Dana Pensiun dalam hal perjanjian dengan pihak ketiga.',
            'Memfasilitasi budaya kolegialitas dan profesionalitas pada setiap aspek kehidupan organisasi.',
            'Mengangkat dan memberhentikan pegawai Dana Pensiun.',
            'Memberikan persetujuan program pengembangan kapasitas SDM dan penerimaan pegawai baru.',
            'Menganalisis kebutuhan pengembangan kapasitas SDM.',
            'Menjalankan fungi kepatuhan dan pengendalian internal.',
            'Menjalankan fungsi audit internal dan kepesertaan.',
            'Menentukan kebijakan dan memeriksa perhitungan manfaat pensiun peserta yang keluar, berhenti bekerja/ pensiun.',
            'Dan tugas lain dalam lingkup tata Kelola DPP UMM.',
        ];

        $data['program_wakil_investasi'] = [
            'Bersama Pengurus yang lain membuat perencanaan anggaran belanja dan pendapatan tiap tahun (Rencana Bisnis) dan menjaga cash flow Dana pensiun. ',
            'Bersama Pengurus menjaga keseimbangan rasio likuiditas, profitabilitas, solvabilitas, nilai kini aktuaria dan kualitas pendaan Dana Pensiun. ',
            'Bersama pengurus menjaga performance investasi untuk mencapai realisasi renbis serta portofolio investasi yang dapat mempengaruhi rasio profitabilitas, rasio operasional dan pemenuhan kewajiban Dana Pensiun dalam jangka pendek, jangka menengah serta jangka Panjang. ',
            'Bersama Pengurus melakukan penyajian Laporan Keuangan bulanan dan tahunan',
            'Bersama Pengurus melakukan penyajian Laporan Pajak',
            'Melaksanakan system akuntansi DPP UMM sesuai dengan peraturan, pedoman, dan standart akuntansi yang berlaku.',
            'Melaksanakan fungsi dan tugas lain untuk kepentingan tatakelola Dana Pensiun.',  
        ];

        $data['program_wakil_keuangan'] = [
            'Bersama Pengurus yang lain membuat perencanaan anggaran belanja dan pendapatan tiap tahun (Rencana Bisnis).',
            'Mengelola Administrasi Umum',
            'Memeriksa surat-surat Keputusan',
            'Membuat laporan hasil pengembangan investasi Dana Pensiun.',
            'Membuat laporan portofolio investasi setiap bulan.',
            'Memantau perkembangan investasi yang dimiliki Dana Pensiun.',
            'Mencaripeluang investasi dalam rangka mengembangkan DPP UMM.',
            'Melaksanakan fungsi manajemen risiko dan internalisasi manajeman resiko.',
            'Dan tugas lain dalam lingkup keuangan dan investasi DPP UMM.',
        ];

        $data['program_bidang_keuangan'] = [
            'Melakukan entry transaksi yang sudah diverifikasi oleh Pengurus.',
            'Melakukan koordinasi dengan Direktur Keuangan dan Investasi terkait jumlah saldo bank setiap bulan.',
            'Melakukan rekonsiliasi dengan Direktur Keuangan dan Investasi berkenaan dengan jumlah uang masuk dan keluar.',
            'Membuat laporan keuangan bulanan, triwulan, semester dan tahunan.',
            'Membuat jurnal keuangan.',
            'Membuat laporan perpajakan setiap tahun.',
            'Membukukan investasi yang akan, sedang dan sudah dilakukan oleh DPP UMM',
            'Membuat laporan hasil pengembangan investasi DPP UMM',
            'Membuat laporan portofolio investasi setiap bulan.',
            'Memantau perkembangan investasi yang dimiliki DPP UMM.',
            'Melaporkan laporan investasi dan kegiatan operasional secara akurat.',
            'Melaksanakan tugas umum dan perkantoran.',
            'Melaksanakan tugas tatalaksana dan kerumahtanggaan kantor.',
            'Dan tugas lain dalam lingkup manajemen risiko dan investasi DPP UMM.'
        ];

        $data['program_bidang_tata_kelola'] = [
            'Menyusun draft tata kelola Dana Pensiun.',
            'Mengkoordinasikan fungsi dan tugas masing-masing satuan kerja pada Dana Pensiun secara jelas sehingga masing-masing pihak dapat melaksanakan fungsi dan tugasnya dengan baik.',
            'Mengkoordinasikan perkembangan mengenai peraturan perundang-undangan yang menjadi arahan dan pedoman managemen kinerja Dana Pensiun secara  tepat waktu dan lengkap.',
            'Mengkoordinasikan pelaksanaan Kode Etik Dana Pensiun sebagai pedoman perilaku etis bagi Pengurus, dan seluruh pegawai.',
            'Memantau efektivitas penerapan Tata Kelola Dana Pensiun Pegawai UMM bagi Pengurus dan pegawai.',
            'Memantau dan mengkoordinasikan penerapan fungsi kepatuhan dalam tata kelola Dana Pensiun..',
            'Memantau dan mengkoordinasikan penerapan fungsi pengendalian internal dalam tata kelola Dana Pensiun..',
            'Memantau dan mengkoordinasikan pelaksanaan internalisasi budaya manajemen risiko.',
            'Menyampaikan kepada Pengurus hasil dan evaluasi penerapan dan pelaksanaan tata kelola, pengendalian internal dan fungsi kepatuhan.',
            'Mengkoordinasi pelaksanaan internalisasi budaya manajemen risiko.',
            'Mengidentifikasi risiko potensial.',
            'Mencari data dan informasi yang dapat digunakan sebagai pertimbangan kebijakan alternatif metode menanggulangi risiko.',
            'Mencari data dan informasi yang dapat digunakan sebagai pertimbangan dalam menyusun strategi penanggulangan (semua/sebagian bertahap).',
            'Menyampaikan semua data dan informasi tentang risiko kepada pengurus.',
            'Dan tugas lain dalam lingkup kepatuhan, tata kelola, pengendalian internal dan Manajemen Risiko Dana Pensiun.',
        ];

        $data['program_bidang_kepesertaan'] = [
            'Menginput iuran peserta dan iuran pendiri ke dalam komputer data setiap bulannya.',
            'Membuat surat tagihan atas iuran pensiun dan piutang iuran peserta maupun iuran pemberi kerja ditujukan kepada Pendiri.',
            'Membuat surat-surat keluar dan surat-surat Keputusan',
            'Mengelola administrasi kepesertaan.',
            'Membuat dan merapikan data data peserta, baik peserta aktif, peserta yang akan pensiun lengkap dengan identitas diri dan masa pensiunnya.',
            'Menghitung manfaat pensiun apabila ada peserta yang akan pensiun dan meminta persetujuan kepada Pengurus atas perhitungan yang sudah dibuat sesuai dengan peraturan perundangan yang berlaku.',
            'Mengumpulkan, mengolah, menyajikan dan menyimpan data serta informasi tentang penyelenggaraan Dana Pensiun.',
            'Memperbarui informasi yang ada di web Dana Pensiun UMM',
            'Membuat Salinan (backup) data Kepesertaan ke penyimpanan hard disk eksternal.',
            'Membuat sistem dokumentasi secara digital',
            'Mengendalikan dan memantau sistem Informasi Dana Pensiun.',
            'Mengembangkan dan mengelola Sistem Informasi Dana Pensiun.',
            'Melakukan evaluasi keamanan data terhadap sistem informasi yang ada.',
            'Dan tugas lain dalam lingkup kepesertaan dan sistem informasi DPP UMM.', 
        ];

        foreach($data as $key=>$v){
            foreach($v as $value){
                DB::table('programs')->insert([
                    'program_id' => Str::substr((Uuid::uuid4())->getHex(), 0, 16),
                    'program_nama' => $value,
                    'program_type' => $key,
                ]);
            }
        }

    }
}
