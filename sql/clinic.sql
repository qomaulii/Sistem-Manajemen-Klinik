-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 29, 2026 at 05:09 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `clinic`
--

-- --------------------------------------------------------

--
-- Table structure for table `billing`
--

CREATE TABLE `billing` (
  `bill_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL COMMENT 'ID Resepsionis/Kasir yang melayani',
  `service_details` text NOT NULL COMMENT 'Deskripsi layanan atau rincian biaya',
  `total_amount` decimal(10,0) NOT NULL,
  `payment_method` enum('Cash','Transfer','BPJS','Insurance') NOT NULL DEFAULT 'Cash',
  `payment_status` enum('Unpaid','Paid','Pending') NOT NULL DEFAULT 'Unpaid',
  `create_date` int(11) NOT NULL COMMENT 'Waktu tagihan dibuat (Unix Timestamp)',
  `paid_date` int(11) DEFAULT NULL COMMENT 'Waktu tagihan dilunasi'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `billing_details`
--

CREATE TABLE `billing_details` (
  `billing_detail_id` int(11) NOT NULL,
  `billing_id` int(11) NOT NULL,
  `visit_item_id` int(11) NOT NULL,
  `item_type` enum('PEMERIKSAAN','LAB','XRAY','OBAT') NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `price` int(11) NOT NULL DEFAULT 0,
  `qty` int(11) NOT NULL DEFAULT 1,
  `subtotal` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `billing_headers`
--

CREATE TABLE `billing_headers` (
  `billing_id` int(11) NOT NULL,
  `visit_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `total_amount` int(11) NOT NULL DEFAULT 0,
  `payment_status` enum('Belum Bayar','Sudah Bayar') DEFAULT 'Belum Bayar',
  `payment_date` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `comment_id` int(11) NOT NULL,
  `patient_doctor_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `comment_type` smallint(6) NOT NULL DEFAULT 1 COMMENT 'for future use, 1 is default',
  `create_date` int(11) NOT NULL,
  `last_edit_time` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `drugs`
--

CREATE TABLE `drugs` (
  `drug_id` int(11) NOT NULL,
  `drug_name_en` varchar(50) DEFAULT NULL,
  `drug_name_fa` varchar(50) DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  `price` decimal(10,0) NOT NULL,
  `num` int(11) NOT NULL DEFAULT 0,
  `memo` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `drug_patient`
--

CREATE TABLE `drug_patient` (
  `drug_patient_id` int(11) NOT NULL,
  `drug_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `user_id_assign` int(11) NOT NULL,
  `assign_date` int(11) NOT NULL,
  `no_of_item` int(11) NOT NULL DEFAULT 1,
  `total_cost` decimal(10,0) NOT NULL,
  `user_id_discharge` int(11) DEFAULT NULL,
  `discharge_date` int(11) DEFAULT NULL,
  `memo` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE `groups` (
  `group_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(48) NOT NULL,
  `description` text NOT NULL,
  `roles` bigint(20) UNSIGNED NOT NULL DEFAULT 2
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`group_id`, `name`, `description`, `roles`) VALUES
(1, 'Administrator', 'Memiliki kendali penuh atas manajemen pengguna, grup hak akses, dan pengaturan inti sistemm.', 1),
(3, 'Dokter', 'Menganalisis rekam medis, membuat resep obat, serta merujuk tes lab dan radiologi.', 4),
(4, 'Radiografer', 'Mengelola jadwal pasien, mengunggah hasil pindai X-Ray, dan memperbarui rekam radiologi.', 8),
(5, 'Analis Lab', 'Mengelola jadwal tes laboratorium, menginput hasil tes sampel, dan menerbitkan dokumen lab.', 16),
(6, 'Apoteker', 'Mengelola inventaris obat, memperbarui stok, dan mencatat transaksi pengambilan resep.', 32),
(7, 'Resepsionis', 'Mengatur pendaftaran pasien, mengelola antrean harian, dan mencetak tagihan pembayaran.', 64),
(8, 'Pasien', 'Akses mandiri untuk melihat riwayat kunjungan, hasil lab/X-Ray, dan status antrean.', 128);

-- --------------------------------------------------------

--
-- Table structure for table `lab`
--

CREATE TABLE `lab` (
  `test_id` int(11) NOT NULL,
  `test_name_en` varchar(50) DEFAULT NULL,
  `test_name_fa` varchar(50) DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  `price` decimal(10,0) NOT NULL,
  `memo` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lab_files`
--

CREATE TABLE `lab_files` (
  `lab_file_id` int(11) NOT NULL,
  `lab_patient_id` int(11) NOT NULL,
  `upload_date` int(11) NOT NULL,
  `path` text NOT NULL,
  `memo` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lab_patient`
--

CREATE TABLE `lab_patient` (
  `lab_patient_id` int(11) NOT NULL,
  `test_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `user_id_assign` int(11) NOT NULL,
  `assign_date` int(11) NOT NULL,
  `no_of_item` int(11) NOT NULL DEFAULT 1,
  `total_cost` decimal(10,0) NOT NULL,
  `user_id_discharge` int(11) DEFAULT NULL,
  `discharge_date` int(11) DEFAULT NULL,
  `memo` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lab_requests`
--

CREATE TABLE `lab_requests` (
  `request_id` int(11) NOT NULL,
  `visit_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `test_id` int(11) NOT NULL,
  `doctor_notes` text DEFAULT NULL,
  `status` enum('Pending','Selesai') DEFAULT 'Pending',
  `created_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lab_requests`
--

INSERT INTO `lab_requests` (`request_id`, `visit_id`, `patient_id`, `doctor_id`, `test_id`, `doctor_notes`, `status`, `created_at`) VALUES
(1, 0, 12, 6, 0, 'Tes: Tes Darah | Catatan: ', 'Pending', 1779946378),
(2, 0, 12, 6, 0, 'Tes: Tes Darah | Catatan: ', 'Pending', 1779946426);

-- --------------------------------------------------------

--
-- Table structure for table `logins`
--

CREATE TABLE `logins` (
  `login_id` int(10) UNSIGNED NOT NULL,
  `ip_address` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `success` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `logins`
--

INSERT INTO `logins` (`login_id`, `ip_address`, `user_id`, `time`, `success`) VALUES
(1, 0, 5, '2026-05-05 05:39:53', 1),
(2, 0, 5, '2026-05-05 05:41:05', 1),
(3, 0, 6, '2026-05-05 05:41:32', 1),
(4, 0, 5, '2026-05-05 05:50:06', 1),
(5, 0, 5, '2026-05-05 05:51:38', 1),
(6, 0, 5, '2026-05-05 05:55:03', 1),
(7, 0, 0, '2026-05-05 05:55:32', 0),
(8, 0, 0, '2026-05-05 05:56:03', 0),
(9, 0, 6, '2026-05-05 05:56:25', 1),
(10, 0, 6, '2026-05-05 05:56:38', 1),
(11, 0, 6, '2026-05-05 05:57:04', 1),
(12, 0, 6, '2026-05-05 06:02:58', 1),
(13, 0, 7, '2026-05-05 06:09:11', 1),
(14, 0, 7, '2026-05-05 06:12:14', 1),
(15, 0, 5, '2026-05-05 06:16:03', 1),
(16, 0, 0, '2026-05-05 07:40:06', 0),
(17, 0, 5, '2026-05-05 08:11:00', 1),
(18, 0, 0, '2026-05-07 02:57:17', 0),
(19, 0, 0, '2026-05-07 03:02:18', 0),
(20, 0, 0, '2026-05-07 03:02:23', 0),
(21, 0, 0, '2026-05-07 03:02:31', 0),
(22, 0, 11, '2026-05-07 03:12:28', 1),
(23, 0, 0, '2026-05-07 03:22:34', 0),
(24, 0, 9, '2026-05-07 03:23:01', 1),
(25, 0, 7, '2026-05-07 03:32:45', 1),
(26, 0, 5, '2026-05-07 03:58:46', 1),
(27, 0, 10, '2026-05-07 04:02:53', 1),
(28, 0, 7, '2026-05-17 05:51:47', 1),
(29, 0, 8, '2026-05-17 05:51:56', 1),
(30, 0, 9, '2026-05-17 05:52:17', 1),
(31, 0, 10, '2026-05-17 05:52:44', 1),
(32, 0, 11, '2026-05-17 05:53:01', 1),
(33, 0, 10, '2026-05-17 05:53:27', 1),
(34, 0, 9, '2026-05-17 05:53:51', 1),
(35, 0, 5, '2026-05-19 03:39:57', 1),
(36, 0, 5, '2026-05-19 07:15:51', 1),
(37, 0, 5, '2026-05-19 12:24:30', 1),
(38, 0, 5, '2026-05-28 01:12:56', 1),
(39, 0, 5, '2026-05-28 03:58:06', 1),
(40, 0, 6, '2026-05-28 03:58:23', 1),
(41, 0, 5, '2026-05-28 04:35:26', 1),
(42, 0, 5, '2026-05-28 04:36:01', 1),
(43, 0, 6, '2026-05-28 04:40:40', 1),
(44, 0, 5, '2026-05-28 04:49:52', 1),
(45, 0, 6, '2026-05-28 04:51:03', 1),
(46, 0, 7, '2026-05-28 06:02:49', 1),
(47, 0, 11, '2026-05-28 07:20:29', 1),
(48, 0, 11, '2026-05-28 07:41:12', 1),
(49, 0, 11, '2026-05-28 07:48:46', 1),
(50, 0, 11, '2026-05-28 10:25:24', 1),
(51, 0, 7, '2026-05-28 10:45:49', 1),
(52, 0, 11, '2026-05-28 10:53:39', 1),
(53, 0, 11, '2026-05-28 10:53:50', 1),
(54, 0, 0, '2026-05-28 10:54:30', 0),
(55, 0, 11, '2026-05-28 22:52:43', 1),
(56, 0, 7, '2026-05-28 22:52:58', 1),
(57, 0, 7, '2026-05-28 22:53:45', 1),
(58, 0, 11, '2026-05-28 22:53:56', 1),
(59, 0, 5, '2026-05-28 23:03:27', 1),
(60, 0, 7, '2026-05-28 23:06:41', 1),
(61, 0, 5, '2026-05-28 23:09:16', 1),
(62, 0, 11, '2026-05-28 23:57:11', 1),
(63, 0, 5, '2026-05-28 23:57:31', 1),
(64, 0, 5, '2026-05-29 00:01:56', 1),
(65, 0, 5, '2026-05-29 00:08:54', 1),
(66, 0, 11, '2026-05-29 00:24:16', 0),
(67, 0, 11, '2026-05-29 00:24:24', 1),
(68, 0, 7, '2026-05-29 00:26:51', 1),
(69, 0, 11, '2026-05-29 00:27:28', 1),
(70, 0, 7, '2026-05-29 00:27:49', 1),
(71, 0, 11, '2026-05-29 00:51:54', 1),
(72, 0, 7, '2026-05-29 00:54:52', 1),
(73, 0, 11, '2026-05-29 00:57:02', 1),
(74, 0, 7, '2026-05-29 00:57:22', 1),
(75, 0, 6, '2026-05-29 00:57:50', 1),
(76, 0, 17, '2026-05-29 01:12:43', 1),
(77, 0, 7, '2026-05-29 01:13:55', 1),
(78, 0, 17, '2026-05-29 01:24:41', 1),
(79, 0, 11, '2026-05-29 01:25:12', 1),
(80, 0, 7, '2026-05-29 01:25:25', 1),
(81, 0, 6, '2026-05-29 01:59:08', 1),
(82, 0, 6, '2026-05-29 02:32:51', 1),
(83, 0, 0, '2026-05-29 02:37:31', 0),
(84, 0, 9, '2026-05-29 02:37:40', 0),
(85, 0, 9, '2026-05-29 02:37:59', 1),
(86, 0, 9, '2026-05-29 03:03:18', 1);

-- --------------------------------------------------------

--
-- Table structure for table `medical_records`
--

CREATE TABLE `medical_records` (
  `record_id` int(11) NOT NULL,
  `visit_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `keluhan` text DEFAULT NULL,
  `diagnosis` text DEFAULT NULL,
  `hasil_pemeriksaan` text DEFAULT NULL,
  `catatan_tindakan` text DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `medical_record_details`
--

CREATE TABLE `medical_record_details` (
  `detail_id` int(11) NOT NULL,
  `record_id` int(11) NOT NULL,
  `visit_item_id` int(11) DEFAULT NULL,
  `item_type` enum('PEMERIKSAAN','LAB','XRAY','OBAT') NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `result_note` text DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

CREATE TABLE `patients` (
  `patient_id` int(11) NOT NULL,
  `first_name` varchar(40) NOT NULL,
  `last_name` varchar(40) DEFAULT NULL,
  `nip` varchar(40) DEFAULT NULL,
  `gender` tinyint(1) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `nik` varchar(40) DEFAULT NULL,
  `birth_date` int(11) DEFAULT NULL,
  `create_date` int(11) NOT NULL,
  `picture` text DEFAULT NULL,
  `memo` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `patients`
--

INSERT INTO `patients` (`patient_id`, `first_name`, `last_name`, `nip`, `gender`, `email`, `phone`, `address`, `nik`, `birth_date`, `create_date`, `picture`, `memo`) VALUES
(1, 'Sari', 'Dewi', NULL, 0, NULL, '08111222333', NULL, NULL, 946656000, 1777954430, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `patient_doctor`
--

CREATE TABLE `patient_doctor` (
  `patient_doctor_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `visit_date` int(11) NOT NULL,
  `status` smallint(6) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `patient_doctor`
--

INSERT INTO `patient_doctor` (`patient_doctor_id`, `patient_id`, `user_id`, `visit_date`, `status`) VALUES
(1, 1, 1, 1777954430, 0);

-- --------------------------------------------------------

--
-- Table structure for table `patient_visits`
--

CREATE TABLE `patient_visits` (
  `visit_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `doctor_id` int(11) DEFAULT 0,
  `queue_number` varchar(20) NOT NULL,
  `status` enum('Menunggu','Telah Diurus','Selesai','Batal') DEFAULT 'Menunggu',
  `payment_status` enum('Belum Bayar','Sudah Bayar') DEFAULT 'Belum Bayar',
  `register_time` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patient_visits`
--

INSERT INTO `patient_visits` (`visit_id`, `patient_id`, `doctor_id`, `queue_number`, `status`, `payment_status`, `register_time`) VALUES
(1, 17, 6, 'P5-0001', 'Telah Diurus', 'Belum Bayar', 1780017900),
(2, 11, 0, 'P5-0002', 'Menunggu', 'Belum Bayar', 1780017917);

-- --------------------------------------------------------

--
-- Table structure for table `prescriptions`
--

CREATE TABLE `prescriptions` (
  `prescription_id` int(11) NOT NULL,
  `visit_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `drug_id` int(11) NOT NULL,
  `dosage_instructions` varchar(255) NOT NULL,
  `status` enum('Pending','Diserahkan') DEFAULT 'Pending',
  `created_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `prescriptions`
--

INSERT INTO `prescriptions` (`prescription_id`, `visit_id`, `patient_id`, `doctor_id`, `drug_id`, `dosage_instructions`, `status`, `created_at`) VALUES
(1, 0, 12, 6, 0, '', 'Pending', 1779946057),
(2, 0, 14, 6, 0, '', 'Pending', 1779946139),
(3, 0, 12, 6, 0, '', 'Pending', 1779946215);

-- --------------------------------------------------------

--
-- Table structure for table `purchased_drugs`
--

CREATE TABLE `purchased_drugs` (
  `purchased_drug_id` int(11) NOT NULL,
  `drug_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `purchase_date` int(11) NOT NULL,
  `purchase_price` decimal(10,0) NOT NULL,
  `no_of_item` int(11) NOT NULL DEFAULT 1,
  `total_cost` decimal(10,0) NOT NULL,
  `memo` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `report_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `subject` text DEFAULT NULL,
  `url` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `create_date` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `returned_drugs`
--

CREATE TABLE `returned_drugs` (
  `returned_drug_id` int(11) NOT NULL,
  `drug_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `return_date` int(11) NOT NULL,
  `no_of_item` int(11) NOT NULL DEFAULT 1,
  `total_cost` decimal(10,0) NOT NULL,
  `memo` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `service_items`
--

CREATE TABLE `service_items` (
  `item_id` int(11) NOT NULL,
  `item_type` enum('PEMERIKSAAN','LAB','XRAY','OBAT') NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `price` int(11) NOT NULL DEFAULT 0,
  `stock` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `service_items`
--

INSERT INTO `service_items` (`item_id`, `item_type`, `item_name`, `price`, `stock`, `is_active`, `created_at`) VALUES
(1, 'PEMERIKSAAN', 'Konsultasi Dokter Umum', 100000, 0, 1, 1780020203),
(2, 'PEMERIKSAAN', 'Konsultasi Dokter Spesialis Penyakit Dalam', 250000, 0, 1, 1780020203),
(3, 'PEMERIKSAAN', 'Konsultasi Dokter Anak', 220000, 0, 1, 1780020203),
(4, 'PEMERIKSAAN', 'Konsultasi Dokter Saraf', 300000, 0, 1, 1780020203),
(5, 'PEMERIKSAAN', 'Konsultasi Dokter Jantung', 350000, 0, 1, 1780020203),
(6, 'PEMERIKSAAN', 'Konsultasi Dokter Mata', 250000, 0, 1, 1780020203),
(7, 'PEMERIKSAAN', 'Konsultasi Dokter Kulit', 275000, 0, 1, 1780020203),
(8, 'PEMERIKSAAN', 'Konsultasi Dokter THT', 250000, 0, 1, 1780020203),
(9, 'PEMERIKSAAN', 'Konsultasi Dokter Gigi', 150000, 0, 1, 1780020203),
(10, 'PEMERIKSAAN', 'Konsultasi Dokter Kandungan', 300000, 0, 1, 1780020203),
(11, 'PEMERIKSAAN', 'Pemeriksaan Tekanan Darah', 20000, 0, 1, 1780020203),
(12, 'PEMERIKSAAN', 'Pemeriksaan Suhu Tubuh', 15000, 0, 1, 1780020203),
(13, 'PEMERIKSAAN', 'Pemeriksaan Saturasi Oksigen', 25000, 0, 1, 1780020203),
(14, 'PEMERIKSAAN', 'Pemeriksaan Denyut Nadi', 20000, 0, 1, 1780020203),
(15, 'PEMERIKSAAN', 'Pemeriksaan Tinggi dan Berat Badan', 15000, 0, 1, 1780020203),
(16, 'PEMERIKSAAN', 'EKG / Rekam Jantung', 200000, 0, 1, 1780020203),
(17, 'PEMERIKSAAN', 'Spirometri / Tes Paru', 350000, 0, 1, 1780020203),
(18, 'PEMERIKSAAN', 'Nebulizer', 100000, 0, 1, 1780020203),
(19, 'PEMERIKSAAN', 'Infus Cairan', 300000, 0, 1, 1780020203),
(20, 'PEMERIKSAAN', 'Suntik Vitamin', 150000, 0, 1, 1780020203),
(21, 'PEMERIKSAAN', 'Suntik Antibiotik', 175000, 0, 1, 1780020203),
(22, 'PEMERIKSAAN', 'Jahit Luka Ringan', 750000, 0, 1, 1780020203),
(23, 'PEMERIKSAAN', 'Jahit Luka Sedang', 1500000, 0, 1, 1780020203),
(24, 'PEMERIKSAAN', 'Pembersihan Luka', 200000, 0, 1, 1780020203),
(25, 'PEMERIKSAAN', 'Ganti Perban', 75000, 0, 1, 1780020203),
(26, 'PEMERIKSAAN', 'Cabut Gigi', 400000, 0, 1, 1780020203),
(27, 'PEMERIKSAAN', 'Tambal Gigi', 500000, 0, 1, 1780020203),
(28, 'PEMERIKSAAN', 'Scaling / Pembersihan Karang Gigi', 750000, 0, 1, 1780020203),
(29, 'PEMERIKSAAN', 'Pemeriksaan Mata Lengkap', 150000, 0, 1, 1780020203),
(30, 'PEMERIKSAAN', 'Tes Buta Warna', 100000, 0, 1, 1780020203),
(31, 'PEMERIKSAAN', 'Pemeriksaan THT Lengkap', 200000, 0, 1, 1780020203),
(32, 'PEMERIKSAAN', 'Rawat Inap Kelas III / hari', 350000, 0, 1, 1780020203),
(33, 'PEMERIKSAAN', 'Rawat Inap Kelas II / hari', 750000, 0, 1, 1780020203),
(34, 'PEMERIKSAAN', 'Rawat Inap Kelas I / hari', 1500000, 0, 1, 1780020203),
(35, 'PEMERIKSAAN', 'ICU / hari', 5000000, 0, 1, 1780020203),
(36, 'PEMERIKSAAN', 'Ambulans Dalam Kota', 750000, 0, 1, 1780020203),
(37, 'PEMERIKSAAN', 'Pemeriksaan Kehamilan Rutin', 250000, 0, 1, 1780020203),
(38, 'PEMERIKSAAN', 'Fisioterapi per Sesi', 250000, 0, 1, 1780020203),
(39, 'PEMERIKSAAN', 'Hemodialisa / Cuci Darah', 1200000, 0, 1, 1780020203),
(40, 'XRAY', 'X-Ray Thorax / Dada', 300000, 0, 1, 1780020210),
(41, 'XRAY', 'X-Ray Kepala', 350000, 0, 1, 1780020210),
(42, 'XRAY', 'X-Ray Leher', 300000, 0, 1, 1780020210),
(43, 'XRAY', 'X-Ray Tulang Belakang', 450000, 0, 1, 1780020210),
(44, 'XRAY', 'X-Ray Pinggang / Lumbal', 400000, 0, 1, 1780020210),
(45, 'XRAY', 'X-Ray Panggul', 400000, 0, 1, 1780020210),
(46, 'XRAY', 'X-Ray Bahu', 300000, 0, 1, 1780020210),
(47, 'XRAY', 'X-Ray Lengan', 275000, 0, 1, 1780020210),
(48, 'XRAY', 'X-Ray Siku', 250000, 0, 1, 1780020210),
(49, 'XRAY', 'X-Ray Pergelangan Tangan', 250000, 0, 1, 1780020210),
(50, 'XRAY', 'X-Ray Tangan', 250000, 0, 1, 1780020210),
(51, 'XRAY', 'X-Ray Jari Tangan', 200000, 0, 1, 1780020210),
(52, 'XRAY', 'X-Ray Lutut', 300000, 0, 1, 1780020210),
(53, 'XRAY', 'X-Ray Kaki', 275000, 0, 1, 1780020210),
(54, 'XRAY', 'X-Ray Pergelangan Kaki', 250000, 0, 1, 1780020210),
(55, 'XRAY', 'X-Ray Tumit', 250000, 0, 1, 1780020210),
(56, 'XRAY', 'X-Ray Jari Kaki', 200000, 0, 1, 1780020210),
(57, 'XRAY', 'X-Ray Rahang', 350000, 0, 1, 1780020210),
(58, 'XRAY', 'X-Ray Gigi Panoramik', 500000, 0, 1, 1780020210),
(59, 'XRAY', 'X-Ray Sinus', 350000, 0, 1, 1780020210),
(60, 'XRAY', 'Mammografi', 1200000, 0, 1, 1780020210),
(61, 'XRAY', 'Fluoroskopi', 1500000, 0, 1, 1780020210),
(62, 'XRAY', 'CT Scan Kepala', 2500000, 0, 1, 1780020210),
(63, 'XRAY', 'CT Scan Dada', 3000000, 0, 1, 1780020210),
(64, 'XRAY', 'CT Scan Abdomen', 3500000, 0, 1, 1780020210),
(65, 'XRAY', 'CT Scan Tulang Belakang', 3750000, 0, 1, 1780020210),
(66, 'XRAY', 'CT Scan Panggul', 3250000, 0, 1, 1780020210),
(67, 'XRAY', 'MRI Kepala', 5000000, 0, 1, 1780020210),
(68, 'XRAY', 'MRI Lutut', 4500000, 0, 1, 1780020210),
(69, 'XRAY', 'MRI Tulang Belakang', 5500000, 0, 1, 1780020210),
(70, 'XRAY', 'MRI Bahu', 4750000, 0, 1, 1780020210),
(71, 'XRAY', 'USG Abdomen', 400000, 0, 1, 1780020210),
(72, 'XRAY', 'USG Hati', 350000, 0, 1, 1780020210),
(73, 'XRAY', 'USG Ginjal', 350000, 0, 1, 1780020210),
(74, 'XRAY', 'USG Kehamilan 2D', 350000, 0, 1, 1780020210),
(75, 'XRAY', 'USG Kehamilan 4D', 750000, 0, 1, 1780020210),
(76, 'XRAY', 'USG Jantung / Echocardiography', 1200000, 0, 1, 1780020210),
(77, 'XRAY', 'Bone Densitometry', 1000000, 0, 1, 1780020210),
(78, 'XRAY', 'Angiografi', 6500000, 0, 1, 1780020210),
(79, 'XRAY', 'PET Scan', 12000000, 0, 1, 1780020210),
(80, 'LAB', 'Tes Darah Lengkap', 150000, 0, 1, 1780020217),
(81, 'LAB', 'Tes Hemoglobin (HB)', 50000, 0, 1, 1780020217),
(82, 'LAB', 'Tes Hematokrit', 45000, 0, 1, 1780020217),
(83, 'LAB', 'Tes Leukosit', 50000, 0, 1, 1780020217),
(84, 'LAB', 'Tes Trombosit', 55000, 0, 1, 1780020217),
(85, 'LAB', 'Tes Gula Darah Sewaktu', 50000, 0, 1, 1780020217),
(86, 'LAB', 'Tes Gula Darah Puasa', 60000, 0, 1, 1780020217),
(87, 'LAB', 'Tes HbA1c', 275000, 0, 1, 1780020217),
(88, 'LAB', 'Tes Kolesterol Total', 100000, 0, 1, 1780020217),
(89, 'LAB', 'Tes HDL', 125000, 0, 1, 1780020217),
(90, 'LAB', 'Tes LDL', 125000, 0, 1, 1780020217),
(91, 'LAB', 'Tes Trigliserida', 120000, 0, 1, 1780020217),
(92, 'LAB', 'Tes Asam Urat', 75000, 0, 1, 1780020217),
(93, 'LAB', 'Tes Fungsi Hati', 300000, 0, 1, 1780020217),
(94, 'LAB', 'SGOT', 125000, 0, 1, 1780020217),
(95, 'LAB', 'SGPT', 125000, 0, 1, 1780020217),
(96, 'LAB', 'Tes Bilirubin', 150000, 0, 1, 1780020217),
(97, 'LAB', 'Tes Fungsi Ginjal', 250000, 0, 1, 1780020217),
(98, 'LAB', 'Ureum', 100000, 0, 1, 1780020217),
(99, 'LAB', 'Kreatinin', 100000, 0, 1, 1780020217),
(100, 'LAB', 'Tes Elektrolit', 250000, 0, 1, 1780020217),
(101, 'LAB', 'Tes Kalsium', 150000, 0, 1, 1780020217),
(102, 'LAB', 'Tes Natrium', 125000, 0, 1, 1780020217),
(103, 'LAB', 'Tes Kalium', 125000, 0, 1, 1780020217),
(104, 'LAB', 'Tes Urine Lengkap', 100000, 0, 1, 1780020217),
(105, 'LAB', 'Tes Protein Urine', 80000, 0, 1, 1780020217),
(106, 'LAB', 'Tes Kehamilan', 75000, 0, 1, 1780020217),
(107, 'LAB', 'Analisis Sperma', 350000, 0, 1, 1780020217),
(108, 'LAB', 'Tes HIV', 250000, 0, 1, 1780020217),
(109, 'LAB', 'Tes Hepatitis B', 350000, 0, 1, 1780020217),
(110, 'LAB', 'Tes Hepatitis C', 400000, 0, 1, 1780020217),
(111, 'LAB', 'Rapid Test DBD', 200000, 0, 1, 1780020217),
(112, 'LAB', 'Widal', 125000, 0, 1, 1780020217),
(113, 'LAB', 'Swab Antigen', 100000, 0, 1, 1780020217),
(114, 'LAB', 'PCR COVID-19', 700000, 0, 1, 1780020217),
(115, 'LAB', 'Kultur Darah', 450000, 0, 1, 1780020217),
(116, 'LAB', 'Kultur Urine', 400000, 0, 1, 1780020217),
(117, 'LAB', 'Tes Alergi', 500000, 0, 1, 1780020217),
(118, 'LAB', 'Tes Hormon Tiroid', 450000, 0, 1, 1780020217),
(119, 'LAB', 'TSH', 250000, 0, 1, 1780020217),
(120, 'LAB', 'T3', 200000, 0, 1, 1780020217),
(121, 'LAB', 'T4', 200000, 0, 1, 1780020217),
(122, 'LAB', 'Tes Vitamin D', 600000, 0, 1, 1780020217),
(123, 'LAB', 'Tes Vitamin B12', 500000, 0, 1, 1780020217),
(124, 'LAB', 'Tes CRP', 250000, 0, 1, 1780020217),
(125, 'LAB', 'D-Dimer', 450000, 0, 1, 1780020217),
(126, 'LAB', 'Analisis Gas Darah', 500000, 0, 1, 1780020217),
(127, 'LAB', 'Tes Koagulasi', 300000, 0, 1, 1780020217),
(128, 'LAB', 'PT / APTT', 275000, 0, 1, 1780020217),
(129, 'LAB', 'Tes Golongan Darah', 40000, 0, 1, 1780020217),
(130, 'LAB', 'Crossmatch Darah', 350000, 0, 1, 1780020217),
(131, 'LAB', 'Tes TORCH', 1200000, 0, 1, 1780020217),
(132, 'LAB', 'Tes Sifilis', 250000, 0, 1, 1780020217),
(133, 'LAB', 'Pap Smear', 500000, 0, 1, 1780020217),
(134, 'LAB', 'Biopsi Jaringan', 1500000, 0, 1, 1780020217),
(135, 'LAB', 'Patologi Anatomi', 2000000, 0, 1, 1780020217),
(136, 'LAB', 'Pemeriksaan Feses Lengkap', 125000, 0, 1, 1780020217),
(137, 'LAB', 'Tes Cacing', 100000, 0, 1, 1780020217),
(138, 'LAB', 'Tes Dengue IgG/IgM', 350000, 0, 1, 1780020217),
(139, 'LAB', 'Panel Medical Check Up Dasar', 1500000, 0, 1, 1780020217),
(140, 'OBAT', 'Paracetamol Tablet', 15000, 0, 1, 1780020227),
(141, 'OBAT', 'Ibuprofen', 25000, 0, 1, 1780020227),
(142, 'OBAT', 'Asam Mefenamat', 30000, 0, 1, 1780020227),
(143, 'OBAT', 'Aspirin', 20000, 0, 1, 1780020227),
(144, 'OBAT', 'Antibiotik Amoxicillin', 45000, 0, 1, 1780020227),
(145, 'OBAT', 'Antibiotik Cefixime', 85000, 0, 1, 1780020227),
(146, 'OBAT', 'Antibiotik Ciprofloxacin', 75000, 0, 1, 1780020227),
(147, 'OBAT', 'Azithromycin', 95000, 0, 1, 1780020227),
(148, 'OBAT', 'Obat Batuk Sirup', 35000, 0, 1, 1780020227),
(149, 'OBAT', 'Obat Flu dan Pilek', 40000, 0, 1, 1780020227),
(150, 'OBAT', 'Cetirizine', 25000, 0, 1, 1780020227),
(151, 'OBAT', 'Loratadine', 30000, 0, 1, 1780020227),
(152, 'OBAT', 'Omeprazole', 45000, 0, 1, 1780020227),
(153, 'OBAT', 'Antasida DOEN', 20000, 0, 1, 1780020227),
(154, 'OBAT', 'Domperidone', 35000, 0, 1, 1780020227),
(155, 'OBAT', 'Oralit', 15000, 0, 1, 1780020227),
(156, 'OBAT', 'Vitamin C', 25000, 0, 1, 1780020227),
(157, 'OBAT', 'Vitamin D', 75000, 0, 1, 1780020227),
(158, 'OBAT', 'Multivitamin', 60000, 0, 1, 1780020227),
(159, 'OBAT', 'Suplemen Zat Besi', 45000, 0, 1, 1780020227),
(160, 'OBAT', 'Obat Diabetes Metformin', 65000, 0, 1, 1780020227),
(161, 'OBAT', 'Insulin', 350000, 0, 1, 1780020227),
(162, 'OBAT', 'Obat Hipertensi Amlodipine', 55000, 0, 1, 1780020227),
(163, 'OBAT', 'Captopril', 40000, 0, 1, 1780020227),
(164, 'OBAT', 'Simvastatin', 50000, 0, 1, 1780020227),
(165, 'OBAT', 'Salep Kulit Antijamur', 40000, 0, 1, 1780020227),
(166, 'OBAT', 'Salep Antibiotik', 45000, 0, 1, 1780020227),
(167, 'OBAT', 'Salep Luka Bakar', 55000, 0, 1, 1780020227),
(168, 'OBAT', 'Tetes Mata', 45000, 0, 1, 1780020227),
(169, 'OBAT', 'Tetes Telinga', 40000, 0, 1, 1780020227),
(170, 'OBAT', 'Inhaler Asma', 180000, 0, 1, 1780020227),
(171, 'OBAT', 'Nebulizer Solution', 95000, 0, 1, 1780020227),
(172, 'OBAT', 'Obat Maag Sirup', 35000, 0, 1, 1780020227),
(173, 'OBAT', 'Loperamide', 20000, 0, 1, 1780020227),
(174, 'OBAT', 'Antimo', 18000, 0, 1, 1780020227),
(175, 'OBAT', 'Ondansetron', 85000, 0, 1, 1780020227),
(176, 'OBAT', 'Obat Tidur Ringan', 90000, 0, 1, 1780020227),
(177, 'OBAT', 'Obat Anti Cemas', 120000, 0, 1, 1780020227),
(178, 'OBAT', 'Obat Jerawat', 65000, 0, 1, 1780020227),
(179, 'OBAT', 'Krim Kortikosteroid', 70000, 0, 1, 1780020227),
(180, 'OBAT', 'Vaksin Influenza', 350000, 0, 1, 1780020227),
(181, 'OBAT', 'Vaksin Hepatitis B', 600000, 0, 1, 1780020227),
(182, 'OBAT', 'Vaksin HPV', 1500000, 0, 1, 1780020227),
(183, 'OBAT', 'Obat TBC Paket Bulanan', 450000, 0, 1, 1780020227),
(184, 'OBAT', 'Obat HIV ARV Bulanan', 850000, 0, 1, 1780020227),
(185, 'OBAT', 'Obat Kemoterapi Dasar', 2500000, 0, 1, 1780020227),
(186, 'OBAT', 'Infus Vitamin', 250000, 0, 1, 1780020227),
(187, 'OBAT', 'Cairan Infus NaCl', 85000, 0, 1, 1780020227),
(188, 'OBAT', 'Suntik Antibiotik', 175000, 0, 1, 1780020227),
(189, 'OBAT', 'Suntik Pereda Nyeri', 150000, 0, 1, 1780020227);

-- --------------------------------------------------------

--
-- Table structure for table `userdata`
--

CREATE TABLE `userdata` (
  `userdata_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `first_name` varchar(40) DEFAULT NULL,
  `last_name` varchar(40) DEFAULT NULL,
  `nip` varchar(40) DEFAULT NULL,
  `gender` tinyint(1) DEFAULT NULL,
  `email` varchar(254) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `position` varchar(40) NOT NULL,
  `nik` varchar(40) DEFAULT NULL,
  `birth_date` int(11) DEFAULT NULL,
  `create_date` int(11) NOT NULL,
  `picture` text DEFAULT NULL,
  `identity_document` varchar(255) DEFAULT NULL,
  `memo` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `userdata`
--

INSERT INTO `userdata` (`userdata_id`, `user_id`, `first_name`, `last_name`, `nip`, `gender`, `email`, `phone`, `address`, `position`, `nik`, `birth_date`, `create_date`, `picture`, `identity_document`, `memo`) VALUES
(1, 1, 'Andi', 'Wijayaa', '123', 1, 'dr.andi@klinik.com', '08111111111', 'Mataram', 'Dokter Umum', '123', -28800, 1777952863, NULL, NULL, NULL),
(2, 2, 'Siti', 'Rahayu', NULL, NULL, 'dr.siti@klinik.com', '08222222222', NULL, 'Dokter Anak', '', NULL, 1777952863, NULL, NULL, NULL),
(3, 3, 'Reza', 'Pratama', NULL, NULL, 'dr.reza@klinik.com', '08333333333', NULL, 'Dokter Gigi', '', NULL, 1777952863, NULL, NULL, NULL),
(4, 4, 'Maya', 'Kusuma', NULL, NULL, 'dr.maya@klinik.com', '08444444444', NULL, 'Dokter Kandungan', '', NULL, 1777952863, NULL, NULL, NULL),
(5, 5, 'Admin', '', '1233', 1, 'dummy@klinik.com', '08123456789', '', 'Administrator', '123', 1777910400, 1777958930, 'uploads/hospital/staff/5/5_profile_picture.png', NULL, NULL),
(6, 6, 'Budi', 'Santoso', NULL, 0, 'dummy@klinik.com', '08123456789', NULL, 'Doctor', '-', 1777958930, 1777958930, NULL, NULL, NULL),
(7, 7, 'Siti', 'Rahayu', NULL, 0, 'dummy@klinik.com', '08123456789', NULL, 'Receptionist', '-', 1777958930, 1777958930, NULL, NULL, NULL),
(8, 8, 'Budi', 'Tabuti', NULL, 0, 'budy@klinik.com', '08123456789', NULL, 'Laboratorist', '-', 1778123138, 1778123138, NULL, NULL, NULL),
(9, 9, 'Ani', 'Apoteker', NULL, 0, 'dummy@klinik.com', '08123456789', NULL, 'Pharmacist', '-', 1778123138, 1778123138, NULL, NULL, NULL),
(10, 10, 'Joko', 'Radiologi', NULL, 0, 'dummy@klinik.com', '08123456789', NULL, 'Radiologist', '-', 1778123138, 1778123138, NULL, NULL, NULL),
(11, 11, 'Pasien', 'Satu', NULL, 0, 'dummy@klinik.com', '08123456789', NULL, 'Patient', '-', 1778123138, 1778123138, NULL, NULL, NULL),
(17, 17, 'qoma', 'aul', '', 0, 'qom@gmail.com', '081234567890', 'Mataram', 'Pasien', '1234567890123456', 1188489600, 0, NULL, 'uploads/patients/identity/17_identitas.png', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) UNSIGNED NOT NULL,
  `username` varchar(32) NOT NULL,
  `password` varchar(60) NOT NULL,
  `password_last_set` datetime NOT NULL,
  `password_never_expires` tinyint(1) NOT NULL DEFAULT 0,
  `remember_me` varchar(40) NOT NULL,
  `activation_code` varchar(40) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `forgot_code` varchar(40) DEFAULT NULL,
  `forgot_generated` datetime DEFAULT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT 1,
  `last_login` datetime DEFAULT NULL,
  `last_login_ip` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `password_last_set`, `password_never_expires`, `remember_me`, `activation_code`, `active`, `forgot_code`, `forgot_generated`, `enabled`, `last_login`, `last_login_ip`) VALUES
(1, 'dr.andi', '$2y$10$placeholder', '2026-05-05 11:47:43', 1, '', NULL, 1, NULL, NULL, 1, NULL, NULL),
(2, 'dr.siti', '$2y$10$placeholder', '2026-05-05 11:47:43', 0, '', NULL, 1, NULL, NULL, 1, NULL, NULL),
(3, 'dr.reza', '$2y$10$placeholder', '2026-05-05 11:47:43', 0, '', NULL, 1, NULL, NULL, 1, NULL, NULL),
(4, 'dr.maya', '$2y$10$placeholder', '2026-05-05 11:47:43', 0, '', NULL, 1, NULL, NULL, 1, NULL, NULL),
(5, 'admin', '$2a$08$6ewmXDKaJ/VcY.qr8MJByuf1oLWYwkD6zo.eecuoQpdpxEuSDJ0Ke', '2026-05-05 05:28:50', 1, '54773a884738c9ff0dc35929e68c4b2973f0afb2', NULL, 1, NULL, NULL, 1, '2026-05-29 08:08:54', 0),
(6, 'dokter1', '$2a$08$QGVATFyp2R0KbHeT2p9r/umF418G01WH/p9Spv3AqD0nOCYdldege', '2026-05-05 05:28:50', 0, '66f821e82c6bb4d24ca685f207c84b031993886c', NULL, 1, NULL, NULL, 1, '2026-05-29 10:32:51', 0),
(7, 'resep1', '$2a$08$LZg/gXF2PoWdFW2w6aFdbeh0f2PlEb/vRlSE8f1/13zA7oC3FK8P2', '2026-05-05 05:28:50', 0, 'd1242480da9af9e468db110153b8f9b5bc9bd51d', NULL, 1, NULL, NULL, 1, '2026-05-29 09:25:25', 0),
(8, 'lab1', '$2a$08$BdeCYv8RMhEyserCBa3KnO67tQq6Ii3JNo7kGGHRWMyi1guuQDI3O', '2026-05-07 11:05:38', 0, '', NULL, 1, NULL, NULL, 1, '2026-05-17 13:51:56', 0),
(9, 'apotek1', '$2a$08$abfy4EQgWDTb4SAMCQHGQ.4VY/vd9CV7pkcatyikmYm4RbFKiNxNq', '2026-05-07 11:05:38', 0, '2724b6021483a5147f2bbe308323e5b62f4fdbf5', NULL, 1, NULL, NULL, 1, '2026-05-29 11:03:18', 0),
(10, 'xray1', '$2a$08$zIyTsT0IrIU2A.VjbKGqaeXQjjhtiylmRofRddejz9taQ3Wqmvfby', '2026-05-07 11:05:38', 0, '700c76410e918ea6e46cecf9186567b04e87c78c', NULL, 1, NULL, NULL, 1, '2026-05-17 13:53:26', 0),
(11, 'pasien1', '$2a$08$GJPVs6pXv2L/BU7pkZyri.IIbDcG9kf74mwiUcxrWNQt7MAN39x8G', '2026-05-07 11:05:38', 0, '80c027d20769d759ba8b11791340ca884b390724', NULL, 1, NULL, NULL, 1, '2026-05-29 09:25:12', 0),
(17, 'liyapasien21', '$2a$08$XINWuvDlCcgjtuc8LCUlRu5WFVwyNr1Vrbz5.J7qtZUCqVfUugUUm', '2026-05-29 09:12:34', 1, '', NULL, 1, NULL, NULL, 1, '2026-05-29 09:24:41', 0);

-- --------------------------------------------------------

--
-- Table structure for table `user_group`
--

CREATE TABLE `user_group` (
  `assoc_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `group_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `user_group`
--

INSERT INTO `user_group` (`assoc_id`, `user_id`, `group_id`) VALUES
(22, 1, 3),
(26, 2, 3),
(25, 3, 3),
(24, 4, 3),
(21, 5, 1),
(23, 6, 3),
(7, 7, 7),
(11, 11, 8),
(18, 12, 8),
(19, 13, 8),
(20, 14, 8),
(28, 15, 8),
(29, 16, 8),
(30, 17, 8);

-- --------------------------------------------------------

--
-- Table structure for table `visit_items`
--

CREATE TABLE `visit_items` (
  `visit_item_id` int(11) NOT NULL,
  `visit_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `doctor_id` int(11) DEFAULT NULL,
  `item_id` int(11) NOT NULL,
  `item_type` enum('PEMERIKSAAN','LAB','XRAY','OBAT') NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `price` int(11) NOT NULL DEFAULT 0,
  `qty` int(11) NOT NULL DEFAULT 1,
  `subtotal` int(11) NOT NULL DEFAULT 0,
  `note` text DEFAULT NULL,
  `status` enum('Diajukan','Selesai','Batal') DEFAULT 'Diajukan',
  `created_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `xrays`
--

CREATE TABLE `xrays` (
  `xray_id` int(11) NOT NULL,
  `xray_name_en` varchar(50) DEFAULT NULL,
  `xray_name_fa` varchar(50) DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  `price` decimal(10,0) NOT NULL,
  `memo` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `xray_files`
--

CREATE TABLE `xray_files` (
  `xray_file_id` int(11) NOT NULL,
  `xray_patient_id` int(11) NOT NULL,
  `upload_date` int(11) NOT NULL,
  `path` text NOT NULL,
  `memo` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `xray_patient`
--

CREATE TABLE `xray_patient` (
  `xray_patient_id` int(11) NOT NULL,
  `xray_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `user_id_assign` int(11) NOT NULL,
  `assign_date` int(11) NOT NULL,
  `no_of_item` int(11) NOT NULL DEFAULT 1,
  `total_cost` decimal(10,0) NOT NULL,
  `user_id_discharge` int(11) DEFAULT NULL,
  `discharge_date` int(11) DEFAULT NULL,
  `memo` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `xray_requests`
--

CREATE TABLE `xray_requests` (
  `request_id` int(11) NOT NULL,
  `visit_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `xray_id` int(11) NOT NULL,
  `doctor_notes` text DEFAULT NULL,
  `status` enum('Pending','Selesai') DEFAULT 'Pending',
  `created_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `xray_requests`
--

INSERT INTO `xray_requests` (`request_id`, `visit_id`, `patient_id`, `doctor_id`, `xray_id`, `doctor_notes`, `status`, `created_at`) VALUES
(1, 0, 13, 6, 0, 'Bagian: Throax | Catatan: a', 'Pending', 1779946762),
(2, 0, 13, 6, 1, 'Bagian: Throax | Catatan: a', 'Pending', 1779946881),
(3, 0, 12, 6, 1, 'Bagian: Throax | Catatan: ', 'Pending', 1779946888),
(4, 0, 12, 6, 1, 'Bagian: Throax | Catatan: ', 'Pending', 1779946976);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `billing`
--
ALTER TABLE `billing`
  ADD PRIMARY KEY (`bill_id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `billing_details`
--
ALTER TABLE `billing_details`
  ADD PRIMARY KEY (`billing_detail_id`);

--
-- Indexes for table `billing_headers`
--
ALTER TABLE `billing_headers`
  ADD PRIMARY KEY (`billing_id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `patient_doctor_id` (`patient_doctor_id`);

--
-- Indexes for table `drugs`
--
ALTER TABLE `drugs`
  ADD PRIMARY KEY (`drug_id`);

--
-- Indexes for table `drug_patient`
--
ALTER TABLE `drug_patient`
  ADD PRIMARY KEY (`drug_patient_id`),
  ADD KEY `drug_id` (`drug_id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `user_id_assign` (`user_id_assign`),
  ADD KEY `user_id_discharge` (`user_id_discharge`);

--
-- Indexes for table `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`group_id`);

--
-- Indexes for table `lab`
--
ALTER TABLE `lab`
  ADD PRIMARY KEY (`test_id`);

--
-- Indexes for table `lab_files`
--
ALTER TABLE `lab_files`
  ADD PRIMARY KEY (`lab_file_id`),
  ADD KEY `lab_patient_id` (`lab_patient_id`);

--
-- Indexes for table `lab_patient`
--
ALTER TABLE `lab_patient`
  ADD PRIMARY KEY (`lab_patient_id`),
  ADD KEY `test_id` (`test_id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `user_id_assign` (`user_id_assign`),
  ADD KEY `user_id_discharge` (`user_id_discharge`);

--
-- Indexes for table `lab_requests`
--
ALTER TABLE `lab_requests`
  ADD PRIMARY KEY (`request_id`);

--
-- Indexes for table `logins`
--
ALTER TABLE `logins`
  ADD PRIMARY KEY (`login_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `medical_records`
--
ALTER TABLE `medical_records`
  ADD PRIMARY KEY (`record_id`);

--
-- Indexes for table `medical_record_details`
--
ALTER TABLE `medical_record_details`
  ADD PRIMARY KEY (`detail_id`);

--
-- Indexes for table `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`patient_id`);

--
-- Indexes for table `patient_doctor`
--
ALTER TABLE `patient_doctor`
  ADD PRIMARY KEY (`patient_doctor_id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `patient_visits`
--
ALTER TABLE `patient_visits`
  ADD PRIMARY KEY (`visit_id`);

--
-- Indexes for table `prescriptions`
--
ALTER TABLE `prescriptions`
  ADD PRIMARY KEY (`prescription_id`);

--
-- Indexes for table `purchased_drugs`
--
ALTER TABLE `purchased_drugs`
  ADD PRIMARY KEY (`purchased_drug_id`),
  ADD KEY `drug_id` (`drug_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`report_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `returned_drugs`
--
ALTER TABLE `returned_drugs`
  ADD PRIMARY KEY (`returned_drug_id`),
  ADD KEY `drug_id` (`drug_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `service_items`
--
ALTER TABLE `service_items`
  ADD PRIMARY KEY (`item_id`);

--
-- Indexes for table `userdata`
--
ALTER TABLE `userdata`
  ADD PRIMARY KEY (`userdata_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `user_group`
--
ALTER TABLE `user_group`
  ADD PRIMARY KEY (`assoc_id`),
  ADD KEY `user_id` (`user_id`,`group_id`);

--
-- Indexes for table `visit_items`
--
ALTER TABLE `visit_items`
  ADD PRIMARY KEY (`visit_item_id`);

--
-- Indexes for table `xrays`
--
ALTER TABLE `xrays`
  ADD PRIMARY KEY (`xray_id`);

--
-- Indexes for table `xray_files`
--
ALTER TABLE `xray_files`
  ADD PRIMARY KEY (`xray_file_id`),
  ADD KEY `xray_patient_id` (`xray_patient_id`);

--
-- Indexes for table `xray_patient`
--
ALTER TABLE `xray_patient`
  ADD PRIMARY KEY (`xray_patient_id`),
  ADD KEY `xray_id` (`xray_id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `user_id_assign` (`user_id_assign`),
  ADD KEY `user_id_discharge` (`user_id_discharge`);

--
-- Indexes for table `xray_requests`
--
ALTER TABLE `xray_requests`
  ADD PRIMARY KEY (`request_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `billing`
--
ALTER TABLE `billing`
  MODIFY `bill_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `billing_details`
--
ALTER TABLE `billing_details`
  MODIFY `billing_detail_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `billing_headers`
--
ALTER TABLE `billing_headers`
  MODIFY `billing_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `drugs`
--
ALTER TABLE `drugs`
  MODIFY `drug_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `drug_patient`
--
ALTER TABLE `drug_patient`
  MODIFY `drug_patient_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `group_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `lab`
--
ALTER TABLE `lab`
  MODIFY `test_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lab_files`
--
ALTER TABLE `lab_files`
  MODIFY `lab_file_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lab_patient`
--
ALTER TABLE `lab_patient`
  MODIFY `lab_patient_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lab_requests`
--
ALTER TABLE `lab_requests`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `logins`
--
ALTER TABLE `logins`
  MODIFY `login_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;

--
-- AUTO_INCREMENT for table `medical_records`
--
ALTER TABLE `medical_records`
  MODIFY `record_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `medical_record_details`
--
ALTER TABLE `medical_record_details`
  MODIFY `detail_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `patients`
--
ALTER TABLE `patients`
  MODIFY `patient_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `patient_doctor`
--
ALTER TABLE `patient_doctor`
  MODIFY `patient_doctor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `patient_visits`
--
ALTER TABLE `patient_visits`
  MODIFY `visit_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `prescriptions`
--
ALTER TABLE `prescriptions`
  MODIFY `prescription_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `purchased_drugs`
--
ALTER TABLE `purchased_drugs`
  MODIFY `purchased_drug_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `report_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `returned_drugs`
--
ALTER TABLE `returned_drugs`
  MODIFY `returned_drug_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `service_items`
--
ALTER TABLE `service_items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=250;

--
-- AUTO_INCREMENT for table `userdata`
--
ALTER TABLE `userdata`
  MODIFY `userdata_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `user_group`
--
ALTER TABLE `user_group`
  MODIFY `assoc_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `visit_items`
--
ALTER TABLE `visit_items`
  MODIFY `visit_item_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `xrays`
--
ALTER TABLE `xrays`
  MODIFY `xray_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `xray_files`
--
ALTER TABLE `xray_files`
  MODIFY `xray_file_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `xray_patient`
--
ALTER TABLE `xray_patient`
  MODIFY `xray_patient_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `xray_requests`
--
ALTER TABLE `xray_requests`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
