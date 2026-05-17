-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 17, 2026 at 08:01 AM
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
(1, 'Administrator', '', 1),
(2, 'Guest', '', 2),
(3, 'Doctor', '', 4),
(4, 'X-Ray Agent', '', 8),
(5, 'Laboratory Agent', '', 16),
(6, 'Pharmacy Agent', '', 32),
(7, 'Receptionist', '', 64),
(8, 'Patient', '', 128),
(9, 'Laboratorist', 'Staff Laboratorium', 8),
(10, 'Pharmacist', 'Apoteker', 16),
(11, 'Radiologist', 'Staff Radiologi (X-Ray)', 32);

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
(34, 0, 9, '2026-05-17 05:53:51', 1);

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

CREATE TABLE `patients` (
  `patient_id` int(11) NOT NULL,
  `first_name` varchar(40) NOT NULL,
  `last_name` varchar(40) DEFAULT NULL,
  `fname` varchar(40) DEFAULT NULL,
  `gender` tinyint(1) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `social_id` varchar(12) DEFAULT NULL,
  `id_type` enum('','Tazkara','Passport','Driver License','Bank ID Card') DEFAULT NULL,
  `birth_date` int(11) DEFAULT NULL,
  `create_date` int(11) NOT NULL,
  `picture` text DEFAULT NULL,
  `memo` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `patients`
--

INSERT INTO `patients` (`patient_id`, `first_name`, `last_name`, `fname`, `gender`, `email`, `phone`, `address`, `social_id`, `id_type`, `birth_date`, `create_date`, `picture`, `memo`) VALUES
(1, 'Sari', 'Dewi', NULL, 0, NULL, '08111222333', NULL, NULL, NULL, 946656000, 1777954430, NULL, NULL);

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
-- Table structure for table `userdata`
--

CREATE TABLE `userdata` (
  `userdata_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `first_name` varchar(40) DEFAULT NULL,
  `last_name` varchar(40) DEFAULT NULL,
  `fname` varchar(40) DEFAULT NULL,
  `gender` tinyint(1) DEFAULT NULL,
  `email` varchar(254) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `position` varchar(40) NOT NULL,
  `social_id` varchar(12) NOT NULL,
  `id_type` enum('','Tazkara','Passport','Driver License','Bank ID Card') DEFAULT 'Tazkara',
  `birth_date` int(11) DEFAULT NULL,
  `create_date` int(11) NOT NULL,
  `picture` text DEFAULT NULL,
  `memo` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `userdata`
--

INSERT INTO `userdata` (`userdata_id`, `user_id`, `first_name`, `last_name`, `fname`, `gender`, `email`, `phone`, `address`, `position`, `social_id`, `id_type`, `birth_date`, `create_date`, `picture`, `memo`) VALUES
(1, 1, 'Andi', 'Wijaya', NULL, NULL, 'dr.andi@klinik.com', '08111111111', NULL, 'Dokter Umum', '', 'Tazkara', NULL, 1777952863, NULL, NULL),
(2, 2, 'Siti', 'Rahayu', NULL, NULL, 'dr.siti@klinik.com', '08222222222', NULL, 'Dokter Anak', '', 'Tazkara', NULL, 1777952863, NULL, NULL),
(3, 3, 'Reza', 'Pratama', NULL, NULL, 'dr.reza@klinik.com', '08333333333', NULL, 'Dokter Gigi', '', 'Tazkara', NULL, 1777952863, NULL, NULL),
(4, 4, 'Maya', 'Kusuma', NULL, NULL, 'dr.maya@klinik.com', '08444444444', NULL, 'Dokter Kandungan', '', 'Tazkara', NULL, 1777952863, NULL, NULL),
(5, 5, 'Super', 'Admin', NULL, 0, 'dummy@klinik.com', '08123456789', NULL, 'Administrator', '-', 'Tazkara', 1777958930, 1777958930, NULL, NULL),
(6, 6, 'Dr. Budi', 'Santoso', NULL, 0, 'dummy@klinik.com', '08123456789', NULL, 'Doctor', '-', 'Tazkara', 1777958930, 1777958930, NULL, NULL),
(7, 7, 'Siti', 'Rahayu', NULL, 0, 'dummy@klinik.com', '08123456789', NULL, 'Receptionist', '-', 'Tazkara', 1777958930, 1777958930, NULL, NULL),
(8, 8, 'Budi', 'Laboratorium', NULL, 0, 'dummy@klinik.com', '08123456789', NULL, 'Laboratorist', '-', 'Tazkara', 1778123138, 1778123138, NULL, NULL),
(9, 9, 'Ani', 'Apoteker', NULL, 0, 'dummy@klinik.com', '08123456789', NULL, 'Pharmacist', '-', 'Tazkara', 1778123138, 1778123138, NULL, NULL),
(10, 10, 'Joko', 'Radiologi', NULL, 0, 'dummy@klinik.com', '08123456789', NULL, 'Radiologist', '-', 'Tazkara', 1778123138, 1778123138, NULL, NULL),
(11, 11, 'Pasien', 'Satu', NULL, 0, 'dummy@klinik.com', '08123456789', NULL, 'Patient', '-', 'Tazkara', 1778123138, 1778123138, NULL, NULL);

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
(1, 'dr.andi', '$2y$10$placeholder', '2026-05-05 11:47:43', 0, '', NULL, 1, NULL, NULL, 1, NULL, NULL),
(2, 'dr.siti', '$2y$10$placeholder', '2026-05-05 11:47:43', 0, '', NULL, 1, NULL, NULL, 1, NULL, NULL),
(3, 'dr.reza', '$2y$10$placeholder', '2026-05-05 11:47:43', 0, '', NULL, 1, NULL, NULL, 1, NULL, NULL),
(4, 'dr.maya', '$2y$10$placeholder', '2026-05-05 11:47:43', 0, '', NULL, 1, NULL, NULL, 1, NULL, NULL),
(5, 'admin', '$2a$08$6ewmXDKaJ/VcY.qr8MJByuf1oLWYwkD6zo.eecuoQpdpxEuSDJ0Ke', '2026-05-05 05:28:50', 0, '8e6fb262c52eef77a651e5cda4c6692a34e2a096', NULL, 1, NULL, NULL, 1, '2026-05-07 11:58:46', 0),
(6, 'dokter1', '$2a$08$QGVATFyp2R0KbHeT2p9r/umF418G01WH/p9Spv3AqD0nOCYdldege', '2026-05-05 05:28:50', 0, 'c0fb8f45925616a4515b1ca7f62937acb80b6137', NULL, 1, NULL, NULL, 1, '2026-05-05 14:02:58', 0),
(7, 'resep1', '$2a$08$LZg/gXF2PoWdFW2w6aFdbeh0f2PlEb/vRlSE8f1/13zA7oC3FK8P2', '2026-05-05 05:28:50', 0, '6c40c20495ff83424d85eefb5d65bdc15fb794cd', NULL, 1, NULL, NULL, 1, '2026-05-17 13:51:47', 0),
(8, 'lab1', '$2a$08$BdeCYv8RMhEyserCBa3KnO67tQq6Ii3JNo7kGGHRWMyi1guuQDI3O', '2026-05-07 11:05:38', 0, '', NULL, 1, NULL, NULL, 1, '2026-05-17 13:51:56', 0),
(9, 'apotek1', '$2a$08$abfy4EQgWDTb4SAMCQHGQ.4VY/vd9CV7pkcatyikmYm4RbFKiNxNq', '2026-05-07 11:05:38', 0, 'b7cc2b3e08cc7bd3c9a0701be819bfda7c715695', NULL, 1, NULL, NULL, 1, '2026-05-17 13:53:51', 0),
(10, 'xray1', '$2a$08$zIyTsT0IrIU2A.VjbKGqaeXQjjhtiylmRofRddejz9taQ3Wqmvfby', '2026-05-07 11:05:38', 0, '700c76410e918ea6e46cecf9186567b04e87c78c', NULL, 1, NULL, NULL, 1, '2026-05-17 13:53:26', 0),
(11, 'pasien1', '$2a$08$GJPVs6pXv2L/BU7pkZyri.IIbDcG9kf74mwiUcxrWNQt7MAN39x8G', '2026-05-07 11:05:38', 0, 'e8b7c24b0870224a71158c949853c7ec0e4541dd', NULL, 1, NULL, NULL, 1, '2026-05-17 13:53:01', 0);

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
(1, 1, 3),
(2, 2, 3),
(3, 3, 3),
(4, 4, 3),
(5, 5, 1),
(6, 6, 3),
(7, 7, 7),
(8, 8, 9),
(9, 9, 10),
(10, 10, 11),
(11, 11, 8);

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
-- Indexes for table `logins`
--
ALTER TABLE `logins`
  ADD PRIMARY KEY (`login_id`),
  ADD KEY `user_id` (`user_id`);

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
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `billing`
--
ALTER TABLE `billing`
  MODIFY `bill_id` int(11) NOT NULL AUTO_INCREMENT;

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
  MODIFY `group_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

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
-- AUTO_INCREMENT for table `logins`
--
ALTER TABLE `logins`
  MODIFY `login_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

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
-- AUTO_INCREMENT for table `userdata`
--
ALTER TABLE `userdata`
  MODIFY `userdata_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `user_group`
--
ALTER TABLE `user_group`
  MODIFY `assoc_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
