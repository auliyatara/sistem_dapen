-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 18, 2023 at 02:16 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dana_pensiun`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_log`
--

CREATE TABLE `activity_log` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `log_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `subject_type` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `event` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `subject_id` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `causer_type` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `causer_id` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `properties` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `batch_uuid` char(36) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `activity_log`
--

INSERT INTO `activity_log` (`id`, `log_name`, `description`, `subject_type`, `event`, `subject_id`, `causer_type`, `causer_id`, `properties`, `batch_uuid`, `created_at`, `updated_at`) VALUES
(1, 'default', 'created aktifitas', 'App\\Models\\Aktifitas', 'created', 'cdce820c6c614271', 'App\\Models\\User', '264fb2a1bff64980', '{\"attributes\":{\"aktifitas_id\":\"cdce820c6c614271\",\"aktifitas_nama\":\"aktifitas\",\"aktifitas_file\":\"File Aktifitas1693191940.jpg\",\"aktifitas_komentar\":null,\"aktifitas_status\":0,\"aktifitas_user_id\":\"264fb2a1bff64980\",\"created_at\":\"2023-08-28T03:05:43.000000Z\",\"updated_at\":\"2023-08-28T03:05:43.000000Z\"}}', NULL, '2023-08-27 20:05:44', '2023-08-27 20:05:44');

-- --------------------------------------------------------

--
-- Table structure for table `aktifitas`
--

CREATE TABLE `aktifitas` (
  `aktifitas_id` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `aktifitas_nama` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `aktifitas_file` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `aktifitas_komentar` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `aktifitas_status` int(11) NOT NULL DEFAULT 0,
  `aktifitas_user_id` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `aktifitas`
--

INSERT INTO `aktifitas` (`aktifitas_id`, `aktifitas_nama`, `aktifitas_file`, `aktifitas_komentar`, `aktifitas_status`, `aktifitas_user_id`, `created_at`, `updated_at`) VALUES
('cdce820c6c614271', 'aktifitas', 'File Aktifitas1693191940.jpg', NULL, 0, '264fb2a1bff64980', '2023-08-27 20:05:43', '2023-08-27 20:05:43');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `connection` text COLLATE utf8_unicode_ci NOT NULL,
  `queue` text COLLATE utf8_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `informasi_tambahans`
--

CREATE TABLE `informasi_tambahans` (
  `informasi_id` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `informasi_tanggal` date NOT NULL,
  `informasi_judul` text COLLATE utf8_unicode_ci NOT NULL,
  `informasi_deskripsi` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `informasi_tambahans`
--

INSERT INTO `informasi_tambahans` (`informasi_id`, `informasi_tanggal`, `informasi_judul`, `informasi_deskripsi`, `created_at`, `updated_at`) VALUES
('1572986ff8d9456f', '2023-08-28', 'judul baru', 'ini deskripsi', '2023-08-27 20:12:03', '2023-08-27 20:12:03'),
('e58e5fbc79fe446c', '2023-08-28', 'Informasi tambahan', 'asdfasdfasdf afasdfasdfsadf', '2023-08-27 20:10:30', '2023-08-27 20:10:30');

-- --------------------------------------------------------

--
-- Table structure for table `kepesertaans`
--

CREATE TABLE `kepesertaans` (
  `kepesertaan_id` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `kepesertaan_nama` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `kepesertaan_k_t` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `kepesertaan_jumlah_per_bulan` int(11) NOT NULL,
  `kepesertaan_gol` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `kepesertaan_nip` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `kepesertaan_pendidikan` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `kepesertaan_phdp` bigint(20) NOT NULL,
  `kepesertaan_tanggal_lahir` date NOT NULL,
  `kepesertaan_terhitung_mulai_tanggal` date NOT NULL,
  `kepesertaan_tempat_lahir` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `kepesertaan_jabatan` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `kepesertaan_email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `kepesertaans`
--

INSERT INTO `kepesertaans` (`kepesertaan_id`, `kepesertaan_nama`, `kepesertaan_k_t`, `kepesertaan_jumlah_per_bulan`, `kepesertaan_gol`, `kepesertaan_nip`, `kepesertaan_pendidikan`, `kepesertaan_phdp`, `kepesertaan_tanggal_lahir`, `kepesertaan_terhitung_mulai_tanggal`, `kepesertaan_tempat_lahir`, `kepesertaan_jabatan`, `kepesertaan_email`, `created_at`, `updated_at`) VALUES
('9808d5411f394492', 'Peserta Baru', 'I', 2, 'as', '132411234234', 'S!', 2000000, '1990-01-16', '2023-08-28', 'Malang', 'Penasihat', 'asdfsad@asdfasd', '2023-08-27 18:49:28', '2023-08-27 18:49:28'),
('cc1fe35bc45f44cb', 'Alimin', 'K1', 1, 'I/d', '10191040169', 'SMP', 3198375, '1970-05-20', '1990-04-01', 'Malang', 'ob', 'email@mail', '2023-09-17 08:10:36', '2023-09-17 08:10:36');

-- --------------------------------------------------------

--
-- Table structure for table `kepesertaan_dokumens`
--

CREATE TABLE `kepesertaan_dokumens` (
  `kepesertaan_dokumen_id` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `kepesertaan_dokumen_kepesertaan_id` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `kepesertaan_dokumen_judul` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `kepesertaan_dokumen_file` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `menus`
--

CREATE TABLE `menus` (
  `menu_id` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `menu_kode` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `menu_nama` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `menu_route` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `menu_level` int(11) NOT NULL,
  `menu_order` int(11) DEFAULT NULL,
  `menu_parent` varchar(16) COLLATE utf8_unicode_ci DEFAULT NULL,
  `menu_has_child` tinyint(1) NOT NULL DEFAULT 0,
  `menu_status` int(11) NOT NULL,
  `menu_icon` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `menus`
--

INSERT INTO `menus` (`menu_id`, `menu_kode`, `menu_nama`, `menu_route`, `menu_level`, `menu_order`, `menu_parent`, `menu_has_child`, `menu_status`, `menu_icon`, `created_at`, `updated_at`) VALUES
('0852349i1029d723', 'informasi_tambahan', 'Informasi Umum', 'informasi_tambahan', 1, 6, '', 0, 1, '', NULL, NULL),
('0fecca7572e2326a', 'access', 'Manajemen User', '', 1, 8, '', 1, 1, '', NULL, NULL),
('236603c446814114', 'program_direktur', 'Program Direktur', 'program_direktur', 2, NULL, '7283h2131h31b512', 0, 1, '', NULL, NULL),
('2391j231hn323412', 'dashboard', 'Dashboard', 'dashboard', 1, 1, '', 0, 1, '', NULL, NULL),
('2413549i1029d723', 'kepesertaan', 'Kepesertaan', 'kepesertaan', 1, 7, '', 0, 1, '', NULL, NULL),
('36596a7446379c26', 'user', 'User', 'users', 2, 9, '0fecca7572e2326a', 0, 1, '', NULL, NULL),
('382cbddfb4d24fee', 'program_bidang_keuangan', 'Program Bidang Keuangan, Investasi dan Umum', 'program_wakil_keuangan', 2, NULL, '7283h2131h31b512', 0, 1, '', NULL, NULL),
('6132e44df0df4cd8', 'program_wakil_keuangan', 'Program Wakil Direktur Keuangan dan Akuntansi', 'program_wakil_keuangan', 2, NULL, '7283h2131h31b512', 0, 1, '', NULL, NULL),
('7283h2131h31b512', 'program', 'Program', 'program', 1, 2, '', 0, 1, '', NULL, NULL),
('8716289i1029d723', 'riwayat', 'Riwayat Aktifitas', 'riwayat', 1, 5, '', 0, 1, '', NULL, NULL),
('87696a7446379b57', 'role_user', 'Hak Akses', 'roleaccess', 2, 10, '0fecca7572e2326a', 0, 1, '', NULL, NULL),
('987dt2131h31w253', 'aktifitas', 'Aktifitas', 'aktifitas', 1, 3, '', 0, 1, '', NULL, NULL),
('98y653131h31w253', 'aktifitas_approval', 'Approval Aktifitas', 'aktifitas_approval', 1, 4, '', 0, 1, '', NULL, NULL),
('c283256427174024', 'program_bidang_kepesertaan', 'Program Bidang Kepesertaan dan Sistem Informasi', 'program_bidang_tata_kelola', 2, NULL, '7283h2131h31b512', 0, 1, '', NULL, NULL),
('c3549da7cf204bfa', 'program_wakil_investasi', 'Program Wakil Direktur Investasi, Manajemen Risiko, dan Umum', 'program_wakil_investasi', 2, NULL, '7283h2131h31b512', 0, 1, '', NULL, NULL),
('ec12c7062f5a4b10', 'program_bidang_tata_kelola', 'Program Bidang Tata Kelola, Pengendalian Internal, Kepatuhan dan Manajemen Risiko', 'program_bidang_tata_kelola', 2, NULL, '7283h2131h31b512', 0, 1, '', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_06_11_113942_create_roles_table', 1),
(2, '2014_10_12_000000_create_users_table', 1),
(3, '2014_10_12_100000_create_password_resets_table', 1),
(4, '2019_08_19_000000_create_failed_jobs_table', 1),
(5, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(6, '2023_05_18_035850_create_menus_table', 1),
(7, '2023_06_11_113959_create_role_accesses_table', 1),
(8, '2023_08_01_093832_create_programs_table', 1),
(9, '2023_08_01_234257_create_program_kegiatans_table', 1),
(10, '2023_08_01_234310_create_program_pelaksanaans_table', 1),
(11, '2023_08_01_234321_create_program_evaluasis_table', 1),
(12, '2023_08_07_131541_create_aktifitas_table', 1),
(13, '2023_08_13_084356_create_activity_log_table', 1),
(14, '2023_08_13_084357_add_event_column_to_activity_log_table', 1),
(15, '2023_08_13_084358_add_batch_uuid_column_to_activity_log_table', 1),
(16, '2023_08_21_001946_create_informasi_tambahans_table', 1),
(17, '2023_08_27_003501_create_kepesertaans_table', 1),
(18, '2023_08_27_003959_create_kepesertaan_dokumens_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `tokenable_id` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `programs`
--

CREATE TABLE `programs` (
  `program_id` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `program_nama` text COLLATE utf8_unicode_ci NOT NULL,
  `program_type` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `programs`
--

INSERT INTO `programs` (`program_id`, `program_nama`, `program_type`, `created_at`, `updated_at`) VALUES
('032bad156f644bad', 'Membuat sistem dokumentasi secara digital', 'program_bidang_kepesertaan', NULL, NULL),
('040b177132dd4689', 'Membuat laporan hasil pengembangan investasi Dana Pensiun.', 'program_wakil_keuangan', NULL, NULL),
('09dd74f168f44de1', 'Membuat surat tagihan atas iuran pensiun dan piutang iuran peserta maupun iuran pemberi kerja ditujukan kepada Pendiri.', 'program_bidang_kepesertaan', NULL, NULL),
('1144fccbe2f140ef', 'Mengangkat dan memberhentikan pegawai Dana Pensiun.', 'program_direktur', NULL, NULL),
('15f07831d92f4216', 'Memfasilitasi budaya kolegialitas dan profesionalitas pada setiap aspek kehidupan organisasi.', 'program_direktur', NULL, NULL),
('17156125cd784119', 'Mengelola Administrasi Umum', 'program_wakil_keuangan', NULL, NULL),
('17b39decd91b4c67', 'Membuat Salinan (backup) data Kepesertaan ke penyimpanan hard disk eksternal.', 'program_bidang_kepesertaan', NULL, NULL),
('1c0bccca946c4e96', 'Mengidentifikasi risiko potensial.', 'program_bidang_tata_kelola', NULL, NULL),
('1f6bc88060a64140', 'Mencari data dan informasi yang dapat digunakan sebagai pertimbangan dalam menyusun strategi penanggulangan (semua/sebagian bertahap).', 'program_bidang_tata_kelola', NULL, NULL),
('2b9ad3dd3aac4404', 'Membuat jurnal keuangan.', 'program_bidang_keuangan', NULL, NULL),
('3188c3bcd33b43a1', 'Melakukan koordinasi dengan Direktur Keuangan dan Investasi terkait jumlah saldo bank setiap bulan.', 'program_bidang_keuangan', NULL, NULL),
('3542d5b753f24c0b', 'testing type', 'program_direktur', '2023-09-17 06:00:37', '2023-09-17 06:00:37'),
('355ef5dca01b41ea', 'Dan tugas lain dalam lingkup kepesertaan dan sistem informasi DPP UMM.', 'program_bidang_kepesertaan', NULL, NULL),
('378baee03c2d45f6', 'Menyusun draft tata kelola Dana Pensiun.', 'program_bidang_tata_kelola', NULL, NULL),
('3a50e3b57bc04dc3', 'Mengelola administrasi kepesertaan.', 'program_bidang_kepesertaan', NULL, NULL),
('3afeb68bdc5942a2', 'Bersama Pengurus yang lain membuat perencanaan anggaran belanja dan pendapatan tiap tahun (Rencana Bisnis).', 'program_wakil_keuangan', NULL, NULL),
('3b0eee5044b14a64', 'Mengkoordinasi pelaksanaan internalisasi budaya manajemen risiko.', 'program_bidang_tata_kelola', NULL, NULL),
('41b936b9dca54f8f', 'Mengkoordinasikan perkembangan mengenai peraturan perundang-undangan yang menjadi arahan dan pedoman managemen kinerja Dana Pensiun secara  tepat waktu dan lengkap.', 'program_bidang_tata_kelola', NULL, NULL),
('4203309289354272', 'Mengendalikan dan memantau sistem Informasi Dana Pensiun.', 'program_bidang_kepesertaan', NULL, NULL),
('425decc203594aac', 'Memantau efektivitas penerapan Tata Kelola Dana Pensiun Pegawai UMM bagi Pengurus dan pegawai.', 'program_bidang_tata_kelola', NULL, NULL),
('4438f3b9164f42dc', 'Melakukan evaluasi keamanan data terhadap sistem informasi yang ada.', 'program_bidang_kepesertaan', NULL, NULL),
('4830be30a0964846', 'Melaksanakan fungsi dan tugas lain untuk kepentingan tatakelola Dana Pensiun.', 'program_wakil_investasi', NULL, NULL),
('48677aabec534a25', 'Memimpin, mengkoordinasi, mengawasi pelaksanaan tugas Pengurus.', 'program_direktur', NULL, NULL),
('50cc3c5b78fc45d8', 'Menandatangani surat-surat yang berhubungan dengan kegiatan Dana Pensiun.', 'program_direktur', NULL, NULL),
('519a278881304d2c', 'Melaksanakan tugas tatalaksana dan kerumahtanggaan kantor.', 'program_bidang_keuangan', NULL, NULL),
('559c7f9e4bda4882', 'Memperbarui informasi yang ada di web Dana Pensiun UMM', 'program_bidang_kepesertaan', NULL, NULL),
('5adeba444585449f', 'Mencaripeluang investasi dalam rangka mengembangkan DPP UMM.', 'program_wakil_keuangan', NULL, NULL),
('5d0789dded7c4529', 'coba lagi', 'program_direktur', '2023-09-17 06:02:15', '2023-09-17 06:02:15'),
('614f0cfff99b4a3c', 'Memantau dan mengkoordinasikan pelaksanaan internalisasi budaya manajemen risiko.', 'program_bidang_tata_kelola', NULL, NULL),
('628f37d972634f5b', 'Mewakili Dana Pensiun dalam hal perjanjian dengan pihak ketiga.', 'program_direktur', NULL, NULL),
('693fb430b21a4de4', 'Bersama Pengurus menjaga keseimbangan rasio likuiditas, profitabilitas, solvabilitas, nilai kini aktuaria dan kualitas pendaan Dana Pensiun. ', 'program_wakil_investasi', NULL, NULL),
('6de70d242ac24649', 'Menjalankan fungi kepatuhan dan pengendalian internal.', 'program_direktur', NULL, NULL),
('6eb0e27376df4d36', 'Menyampaikan semua data dan informasi tentang risiko kepada pengurus.', 'program_bidang_tata_kelola', NULL, NULL),
('6ecf5fd7aa90453b', 'Melaksanakan tugas umum dan perkantoran.', 'program_bidang_keuangan', NULL, NULL),
('70dd1d3c4305495f', 'Menginput iuran peserta dan iuran pendiri ke dalam komputer data setiap bulannya.', 'program_bidang_kepesertaan', NULL, NULL),
('72af0263831b4034', 'Memantau perkembangan investasi yang dimiliki DPP UMM.', 'program_bidang_keuangan', NULL, NULL),
('7601bce42709470d', 'Menentukan kebijakan dan memeriksa perhitungan manfaat pensiun peserta yang keluar, berhenti bekerja/ pensiun.', 'program_direktur', NULL, NULL),
('7737f282c3484d6a', 'Menandatangani bukti-bukti Penerimaan Uang dan Pengeluaran Uang.', 'program_direktur', NULL, NULL),
('7a1ff742a40947f4', 'Memantau dan mengkoordinasikan penerapan fungsi pengendalian internal dalam tata kelola Dana Pensiun..', 'program_bidang_tata_kelola', NULL, NULL),
('7b36ae24caac4478', 'Mengembangkan dan mengelola Sistem Informasi Dana Pensiun.', 'program_bidang_kepesertaan', NULL, NULL),
('8645e4bbb0ca47dc', 'Menjalankan fungsi audit internal dan kepesertaan.', 'program_direktur', NULL, NULL),
('8863d7ad0e87491f', 'Bersama-sama dengan Pengurus yang lain membuat rencana kerja / program serta anggaran belanja dan pendapatan tiap tahun (Rencana Bisnis).', 'program_direktur', NULL, NULL),
('8f4a3b4934f442a8', 'Memimpin Rapat Pengurus.', 'program_direktur', NULL, NULL),
('8f5d700ba41e4869', 'Menganalisis kebutuhan pengembangan kapasitas SDM.', 'program_direktur', NULL, NULL),
('9012e6ffc14d4748', 'Melakukan rekonsiliasi dengan Direktur Keuangan dan Investasi berkenaan dengan jumlah uang masuk dan keluar.', 'program_bidang_keuangan', NULL, NULL),
('9267e23536b3468a', 'Membuat laporan portofolio investasi setiap bulan.', 'program_wakil_keuangan', NULL, NULL),
('9380b52271f84e22', 'Mencari data dan informasi yang dapat digunakan sebagai pertimbangan kebijakan alternatif metode menanggulangi risiko.', 'program_bidang_tata_kelola', NULL, NULL),
('95394e1dd701482c', 'Dan tugas lain dalam lingkup keuangan dan investasi DPP UMM.', 'program_wakil_keuangan', NULL, NULL),
('a1ac7a9bff36417d', 'Memantau dan mengkoordinasikan penerapan fungsi kepatuhan dalam tata kelola Dana Pensiun..', 'program_bidang_tata_kelola', NULL, NULL),
('a52ff2f5e56141c8', 'Menghitung manfaat pensiun apabila ada peserta yang akan pensiun dan meminta persetujuan kepada Pengurus atas perhitungan yang sudah dibuat sesuai dengan peraturan perundangan yang berlaku.', 'program_bidang_kepesertaan', NULL, NULL),
('a74aa63758d547e3', 'Melaksanakan fungsi manajemen risiko dan internalisasi manajeman resiko.', 'program_wakil_keuangan', NULL, NULL),
('ae7a17abea9e46ab', 'Membuat dan merapikan data data peserta, baik peserta aktif, peserta yang akan pensiun lengkap dengan identitas diri dan masa pensiunnya.', 'program_bidang_kepesertaan', NULL, NULL),
('b76f4cf4977e46a5', 'Melaporkan laporan investasi dan kegiatan operasional secara akurat.', 'program_bidang_keuangan', NULL, NULL),
('b8418c084c7048f7', 'Mengumpulkan, mengolah, menyajikan dan menyimpan data serta informasi tentang penyelenggaraan Dana Pensiun.', 'program_bidang_kepesertaan', NULL, NULL),
('ba6e6f42d7ef4dcf', 'Membukukan investasi yang akan, sedang dan sudah dilakukan oleh DPP UMM', 'program_bidang_keuangan', NULL, NULL),
('bc28bc40c0764fca', 'Menyampaikan kepada Pengurus hasil dan evaluasi penerapan dan pelaksanaan tata kelola, pengendalian internal dan fungsi kepatuhan.', 'program_bidang_tata_kelola', NULL, NULL),
('bc4893fd198b46d6', 'Memberikan persetujuan program pengembangan kapasitas SDM dan penerimaan pegawai baru.', 'program_direktur', NULL, NULL),
('bd6f584a2c9d4921', 'Menginvestasikan kekayaan Dana Pensiun sesuai arahan investasi yang ditetapkan Pendiri.', 'program_direktur', NULL, NULL),
('bed3e7115ffc4cb6', 'Membuat laporan portofolio investasi setiap bulan.', 'program_bidang_keuangan', NULL, NULL),
('c1bcc6d9f84f4882', 'Bersama Pengurus melakukan penyajian Laporan Pajak', 'program_wakil_investasi', NULL, NULL),
('c38c7b3c04e74bfd', 'Melaksanakan system akuntansi DPP UMM sesuai dengan peraturan, pedoman, dan standart akuntansi yang berlaku.', 'program_wakil_investasi', NULL, NULL),
('c3b26fb91a8c4092', 'Mengkoordinasikan pelaksanaan Kode Etik Dana Pensiun sebagai pedoman perilaku etis bagi Pengurus, dan seluruh pegawai.', 'program_bidang_tata_kelola', NULL, NULL),
('c4afb00710594ff0', 'Melakukan entry transaksi yang sudah diverifikasi oleh Pengurus.', 'program_bidang_keuangan', NULL, NULL),
('c741437c26d84fbb', 'Dan tugas lain dalam lingkup kepatuhan, tata kelola, pengendalian internal dan Manajemen Risiko Dana Pensiun.', 'program_bidang_tata_kelola', NULL, NULL),
('cf3dfb0cff7f4f04', 'Menandatangani surat keputusan yang berhubungan dengan pemberian manfaat pensiun.', 'program_direktur', NULL, NULL),
('d3feb43aac334419', 'Membuat laporan hasil pengembangan investasi DPP UMM', 'program_bidang_keuangan', NULL, NULL),
('d467df03f7a94d82', 'Memantau perkembangan investasi yang dimiliki Dana Pensiun.', 'program_wakil_keuangan', NULL, NULL),
('d5b51729e0964e29', 'Bersama pengurus menjaga performance investasi untuk mencapai realisasi renbis serta portofolio investasi yang dapat mempengaruhi rasio profitabilitas, rasio operasional dan pemenuhan kewajiban Dana Pensiun dalam jangka pendek, jangka menengah serta jangka Panjang. ', 'program_wakil_investasi', NULL, NULL),
('d64b31efbb9a44a9', 'Mengkoordinasikan fungsi dan tugas masing-masing satuan kerja pada Dana Pensiun secara jelas sehingga masing-masing pihak dapat melaksanakan fungsi dan tugasnya dengan baik.', 'program_bidang_tata_kelola', NULL, NULL),
('d7cf51ade1aa4546', 'Dan tugas lain dalam lingkup manajemen risiko dan investasi DPP UMM.', 'program_bidang_keuangan', NULL, NULL),
('dd2f9d3cd41f48b9', 'Bersama Pengurus yang lain membuat perencanaan anggaran belanja dan pendapatan tiap tahun (Rencana Bisnis) dan menjaga cash flow Dana pensiun. ', 'program_wakil_investasi', NULL, NULL),
('dfd57dbf23814b89', 'Membuat laporan perpajakan setiap tahun.', 'program_bidang_keuangan', NULL, NULL),
('e038cd7215a145ae', 'Bersama Pengurus melakukan penyajian Laporan Keuangan bulanan dan tahunan', 'program_wakil_investasi', NULL, NULL),
('e6353fe85ffe46cf', 'Membuat laporan keuangan bulanan, triwulan, semester dan tahunan.', 'program_bidang_keuangan', NULL, NULL),
('e7493a48c0a74531', 'Membuat surat-surat keluar dan surat-surat Keputusan', 'program_bidang_kepesertaan', NULL, NULL),
('ee3b880b33a34d17', 'Dan tugas lain dalam lingkup tata Kelola DPP UMM.', 'program_direktur', NULL, NULL),
('ff59beb5bee64e8b', 'Memeriksa surat-surat Keputusan', 'program_wakil_keuangan', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `program_evaluasis`
--

CREATE TABLE `program_evaluasis` (
  `program_evaluasi_id` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `program_evaluasi_program_id` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `program_evaluasi_nama` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `program_evaluasis`
--

INSERT INTO `program_evaluasis` (`program_evaluasi_id`, `program_evaluasi_program_id`, `program_evaluasi_nama`, `created_at`, `updated_at`) VALUES
('59ad4a70059b4bec', '1144fccbe2f140ef', 'testing evaluasi', '2023-09-17 07:45:18', '2023-09-17 07:45:18'),
('752b99b3d1f444c3', '4830be30a0964846', 'Evaluasi', '2023-08-27 19:20:20', '2023-08-27 19:20:20'),
('a863e125f0a14a5a', '1144fccbe2f140ef', 'testing baru', '2023-09-17 07:45:18', '2023-09-17 07:45:18');

-- --------------------------------------------------------

--
-- Table structure for table `program_kegiatans`
--

CREATE TABLE `program_kegiatans` (
  `program_kegiatan_id` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `program_kegiatan_program_id` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `program_kegiatan_nama` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `program_pelaksanaans`
--

CREATE TABLE `program_pelaksanaans` (
  `program_pelaksanaan_id` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `program_pelaksanaan_program_id` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `program_pelaksanaan_nama` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `program_pelaksanaans`
--

INSERT INTO `program_pelaksanaans` (`program_pelaksanaan_id`, `program_pelaksanaan_program_id`, `program_pelaksanaan_nama`, `created_at`, `updated_at`) VALUES
('8e6eeb2ea0a345d6', '1144fccbe2f140ef', 'testing pelaksanaan', '2023-09-17 07:45:18', '2023-09-17 07:45:18'),
('b15cdcd463254172', '4830be30a0964846', 'Pelaksanaan 2', '2023-08-27 19:20:20', '2023-08-27 19:20:20'),
('c86f446da3b94fbe', '1144fccbe2f140ef', 'testing 123', '2023-09-17 07:45:18', '2023-09-17 07:45:18'),
('e899d2b798674997', '4830be30a0964846', 'Pelaksanaan 1', '2023-08-27 19:20:20', '2023-08-27 19:20:20');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `role_id` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `role_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `role_status` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`role_id`, `role_name`, `role_status`, `created_at`, `updated_at`) VALUES
('0e74fd22437f4815', 'Bidang Tata Kelola, Pengendalian Internal, Kepatuhan dan Manajemen Risiko', 1, NULL, NULL),
('0ff43e36d53f445b', 'asdfasdf 12345', 0, '2023-09-17 04:02:51', '2023-09-17 04:05:22'),
('16669d9c38ec42dc', 'Wakil Direktur Keuangan dan Akuntansi', 1, NULL, NULL),
('39d8feeacddc4802', 'asdfasdf 123', 0, '2023-09-17 04:01:52', '2023-09-17 04:05:38'),
('42bec21062754b68', 'Bidang Kepesertaan dan Sistem Informasi', 1, NULL, NULL),
('4f40c808df0442dc', 'Bidang Keuangan, Investasi dan Umum', 1, NULL, NULL),
('7529daeef87e41e8', 'Nama Testing', 1, '2023-09-17 03:50:34', '2023-09-17 03:50:34'),
('753847cfb629c0f9', 'Admin', 1, NULL, NULL),
('bba43a7d2c844bfc', 'Wakil Direktur Investasi, Manajemen Risiko, dan Umum', 1, NULL, NULL),
('c434208db2634623', 'asdfasdf', 0, '2023-09-17 03:52:25', '2023-09-17 04:05:47'),
('c98e42fde57b4a02', 'coba h', 0, '2023-09-17 03:52:58', '2023-09-17 04:06:03'),
('f182fb68e2414299', 'Coba Tambah', 0, '2023-09-17 04:07:07', '2023-09-17 04:07:14'),
('f38641021b954cb6', 'testing role', 0, '2023-09-17 03:17:38', '2023-09-17 04:05:55'),
('fea3bfe6940f4d9f', 'Bidang Tata Kelola, Pengendalian Internal, Kepatuhan dan Manajemen Risiko', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `role_accesses`
--

CREATE TABLE `role_accesses` (
  `role_access_id` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `role_access_role_id` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `role_access_menu_id` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `role_accesses`
--

INSERT INTO `role_accesses` (`role_access_id`, `role_access_role_id`, `role_access_menu_id`, `created_at`, `updated_at`) VALUES
('0c78ac2799874fb2', '42bec21062754b68', 'c283256427174024', '2023-08-27 19:54:09', '2023-08-27 19:54:09'),
('279b79ebbf6648ff', '753847cfb629c0f9', '98y653131h31w253', '2023-09-17 03:47:23', '2023-09-17 03:47:23'),
('2c684c9863b74d75', '42bec21062754b68', '8716289i1029d723', '2023-08-27 19:54:09', '2023-08-27 19:54:09'),
('444d07d5e9734a19', '753847cfb629c0f9', '2391j231hn323412', '2023-09-17 03:47:23', '2023-09-17 03:47:23'),
('4c641d4b9f6c4897', '753847cfb629c0f9', '0852349i1029d723', '2023-09-17 03:47:23', '2023-09-17 03:47:23'),
('5dcd772ede574de0', '753847cfb629c0f9', '0fecca7572e2326a', '2023-09-17 03:47:23', '2023-09-17 03:47:23'),
('62c2bd6b931a4fad', '753847cfb629c0f9', '987dt2131h31w253', '2023-09-17 03:47:23', '2023-09-17 03:47:23'),
('73942d393be54518', '753847cfb629c0f9', '2413549i1029d723', '2023-09-17 03:47:23', '2023-09-17 03:47:23'),
('81e961c8942d4d5b', '42bec21062754b68', '2413549i1029d723', '2023-08-27 19:54:09', '2023-08-27 19:54:09'),
('87fa3f9569a54601', '753847cfb629c0f9', '236603c446814114', '2023-09-17 03:47:23', '2023-09-17 03:47:23'),
('8f7959f455a5411a', '753847cfb629c0f9', '36596a7446379c26', '2023-09-17 03:47:23', '2023-09-17 03:47:23'),
('aa3cab285ac04534', '42bec21062754b68', '7283h2131h31b512', '2023-08-27 19:54:09', '2023-08-27 19:54:09'),
('b8bf010d9ecf4928', '753847cfb629c0f9', '7283h2131h31b512', '2023-09-17 03:47:23', '2023-09-17 03:47:23'),
('bbcf4664330c4ac4', '42bec21062754b68', '2391j231hn323412', '2023-08-27 19:54:09', '2023-08-27 19:54:09'),
('cee88a8a712c4f88', '42bec21062754b68', '987dt2131h31w253', '2023-08-27 19:54:09', '2023-08-27 19:54:09'),
('e764488681b64479', '753847cfb629c0f9', '8716289i1029d723', '2023-09-17 03:47:23', '2023-09-17 03:47:23'),
('f397b9f786584cf7', '753847cfb629c0f9', '382cbddfb4d24fee', '2023-09-17 03:47:23', '2023-09-17 03:47:23'),
('f762d87f9e9a43d0', '753847cfb629c0f9', '87696a7446379b57', '2023-09-17 03:47:23', '2023-09-17 03:47:23'),
('fedc7cbefe624098', '42bec21062754b68', '0852349i1029d723', '2023-08-27 19:54:09', '2023-08-27 19:54:09');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `user_username` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `user_nama` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `user_tempat_lahir` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_tanggal_lahir` date DEFAULT NULL,
  `user_kontak` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_alamat` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `user_email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `user_role_id` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_status` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `user_username`, `user_nama`, `user_tempat_lahir`, `user_tanggal_lahir`, `user_kontak`, `user_alamat`, `password`, `user_email`, `user_role_id`, `remember_token`, `user_status`, `created_at`, `updated_at`) VALUES
('264fb2a1bff64980', 'user', 'John Doe', NULL, NULL, NULL, NULL, '$2y$10$uOEZJQesgX7wC6/Sthp76eHWFvEyEZ0LdCCu6Emy0D8aJm4X27ImK', 'mail@mail.com', '753847cfb629c0f9', NULL, 1, NULL, NULL),
('ff676f198eb7486c', 'peserta', 'Kepesertaan', NULL, NULL, NULL, NULL, '$2y$10$UZ.MmQvJzaJY4Jnc8z2EC.yhfJQJ3WObfrpxSDuJrXPltAG3EPvIW', 'peserta@admin1234', '42bec21062754b68', NULL, 1, '2023-08-27 19:52:34', '2023-08-27 19:52:34');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_log`
--
ALTER TABLE `activity_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activity_log_log_name_index` (`log_name`);

--
-- Indexes for table `aktifitas`
--
ALTER TABLE `aktifitas`
  ADD PRIMARY KEY (`aktifitas_id`),
  ADD KEY `aktifitas_aktifitas_user_id_foreign` (`aktifitas_user_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `informasi_tambahans`
--
ALTER TABLE `informasi_tambahans`
  ADD PRIMARY KEY (`informasi_id`) USING BTREE;

--
-- Indexes for table `kepesertaans`
--
ALTER TABLE `kepesertaans`
  ADD PRIMARY KEY (`kepesertaan_id`);

--
-- Indexes for table `kepesertaan_dokumens`
--
ALTER TABLE `kepesertaan_dokumens`
  ADD PRIMARY KEY (`kepesertaan_dokumen_id`),
  ADD KEY `kepesertaan_dokumens_kepesertaan_dokumen_kepesertaan_id_foreign` (`kepesertaan_dokumen_kepesertaan_id`);

--
-- Indexes for table `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`menu_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `programs`
--
ALTER TABLE `programs`
  ADD PRIMARY KEY (`program_id`);

--
-- Indexes for table `program_evaluasis`
--
ALTER TABLE `program_evaluasis`
  ADD PRIMARY KEY (`program_evaluasi_id`),
  ADD KEY `program_evaluasis_program_evaluasi_program_id_foreign` (`program_evaluasi_program_id`);

--
-- Indexes for table `program_kegiatans`
--
ALTER TABLE `program_kegiatans`
  ADD PRIMARY KEY (`program_kegiatan_id`),
  ADD KEY `program_kegiatans_program_kegiatan_program_id_foreign` (`program_kegiatan_program_id`);

--
-- Indexes for table `program_pelaksanaans`
--
ALTER TABLE `program_pelaksanaans`
  ADD PRIMARY KEY (`program_pelaksanaan_id`),
  ADD KEY `program_pelaksanaans_program_pelaksanaan_program_id_foreign` (`program_pelaksanaan_program_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`role_id`);

--
-- Indexes for table `role_accesses`
--
ALTER TABLE `role_accesses`
  ADD PRIMARY KEY (`role_access_id`),
  ADD KEY `role_accesses_role_access_role_id_foreign` (`role_access_role_id`),
  ADD KEY `role_accesses_role_access_menu_id_foreign` (`role_access_menu_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `users_user_role_id_foreign` (`user_role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_log`
--
ALTER TABLE `activity_log`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `aktifitas`
--
ALTER TABLE `aktifitas`
  ADD CONSTRAINT `aktifitas_aktifitas_user_id_foreign` FOREIGN KEY (`aktifitas_user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `kepesertaan_dokumens`
--
ALTER TABLE `kepesertaan_dokumens`
  ADD CONSTRAINT `kepesertaan_dokumens_kepesertaan_dokumen_kepesertaan_id_foreign` FOREIGN KEY (`kepesertaan_dokumen_kepesertaan_id`) REFERENCES `kepesertaans` (`kepesertaan_id`) ON DELETE CASCADE;

--
-- Constraints for table `program_evaluasis`
--
ALTER TABLE `program_evaluasis`
  ADD CONSTRAINT `program_evaluasis_program_evaluasi_program_id_foreign` FOREIGN KEY (`program_evaluasi_program_id`) REFERENCES `programs` (`program_id`) ON DELETE CASCADE;

--
-- Constraints for table `program_kegiatans`
--
ALTER TABLE `program_kegiatans`
  ADD CONSTRAINT `program_kegiatans_program_kegiatan_program_id_foreign` FOREIGN KEY (`program_kegiatan_program_id`) REFERENCES `programs` (`program_id`) ON DELETE CASCADE;

--
-- Constraints for table `program_pelaksanaans`
--
ALTER TABLE `program_pelaksanaans`
  ADD CONSTRAINT `program_pelaksanaans_program_pelaksanaan_program_id_foreign` FOREIGN KEY (`program_pelaksanaan_program_id`) REFERENCES `programs` (`program_id`) ON DELETE CASCADE;

--
-- Constraints for table `role_accesses`
--
ALTER TABLE `role_accesses`
  ADD CONSTRAINT `role_accesses_role_access_menu_id_foreign` FOREIGN KEY (`role_access_menu_id`) REFERENCES `menus` (`menu_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_accesses_role_access_role_id_foreign` FOREIGN KEY (`role_access_role_id`) REFERENCES `roles` (`role_id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_user_role_id_foreign` FOREIGN KEY (`user_role_id`) REFERENCES `roles` (`role_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
