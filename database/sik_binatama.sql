-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 05, 2026 at 03:00 PM
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
-- Database: `sik_binatama`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `ip_address` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `ip_address`, `created_at`, `updated_at`) VALUES
(1, 1, 'login', 'Login berhasil dari IP 127.0.0.1', '127.0.0.1', '2026-05-02 19:29:09', '2026-05-02 19:29:09'),
(2, 1, 'create_user', 'Membuat akun baru: fuad (guru)', '127.0.0.1', '2026-05-02 19:37:44', '2026-05-02 19:37:44'),
(3, 1, 'update_product', 'Mengupdate produk: AYAM UNGKEP HERBAL', '127.0.0.1', '2026-05-02 19:49:37', '2026-05-02 19:49:37'),
(4, 1, 'logout', 'Logout dari sistem', '127.0.0.1', '2026-05-02 21:36:19', '2026-05-02 21:36:19'),
(5, 1, 'login', 'Login berhasil dari IP 127.0.0.1', '127.0.0.1', '2026-05-02 21:44:15', '2026-05-02 21:44:15'),
(6, 1, 'logout', 'Logout dari sistem', '127.0.0.1', '2026-05-02 21:44:35', '2026-05-02 21:44:35'),
(7, 1, 'login', 'Login berhasil dari IP 127.0.0.1', '127.0.0.1', '2026-05-02 21:44:57', '2026-05-02 21:44:57'),
(8, 1, 'update_user', 'Mengupdate akun: fuad', '127.0.0.1', '2026-05-02 21:45:22', '2026-05-02 21:45:22'),
(9, 2, 'login', 'Login berhasil dari IP 127.0.0.1', '127.0.0.1', '2026-05-02 21:45:37', '2026-05-02 21:45:37'),
(10, 2, 'logout', 'Logout dari sistem', '127.0.0.1', '2026-05-02 21:46:04', '2026-05-02 21:46:04'),
(11, 1, 'login', 'Login berhasil dari IP 127.0.0.1', '127.0.0.1', '2026-05-02 23:51:14', '2026-05-02 23:51:14'),
(12, 1, 'update_user', 'Mengupdate akun: fuad', '127.0.0.1', '2026-05-02 23:51:30', '2026-05-02 23:51:30'),
(13, 2, 'login', 'Login berhasil dari IP 127.0.0.1', '127.0.0.1', '2026-05-02 23:51:52', '2026-05-02 23:51:52'),
(14, 1, 'logout', 'Logout dari sistem', '127.0.0.1', '2026-05-03 00:14:17', '2026-05-03 00:14:17'),
(15, 2, 'login', 'Login berhasil dari IP 127.0.0.1', '127.0.0.1', '2026-05-03 00:14:26', '2026-05-03 00:14:26'),
(16, 2, 'create_patient', 'Input pasien baru: fuad sakit (RM-202605-0001)', '127.0.0.1', '2026-05-03 00:24:24', '2026-05-03 00:24:24'),
(17, 2, 'create_medical_record', 'Input rekam medis pasien: fuad sakit', '127.0.0.1', '2026-05-03 00:26:15', '2026-05-03 00:26:15'),
(18, 1, 'login', 'Login berhasil dari IP 127.0.0.1', '127.0.0.1', '2026-05-03 04:12:49', '2026-05-03 04:12:49'),
(19, 1, 'logout', 'Logout dari sistem', '127.0.0.1', '2026-05-03 04:13:43', '2026-05-03 04:13:43'),
(20, 1, 'login', 'Login berhasil dari IP 127.0.0.1', '127.0.0.1', '2026-05-03 04:13:50', '2026-05-03 04:13:50'),
(21, 2, 'login', 'Login berhasil dari IP 127.0.0.1', '127.0.0.1', '2026-05-03 04:28:42', '2026-05-03 04:28:42'),
(22, 2, 'create_transaction', 'Transaksi TRX-20260503-0001 untuk pasien fuad sakit', '127.0.0.1', '2026-05-03 04:30:38', '2026-05-03 04:30:38'),
(23, 1, 'login', 'Login berhasil dari IP 127.0.0.1', '127.0.0.1', '2026-05-03 08:10:29', '2026-05-03 08:10:29'),
(24, 1, 'logout', 'Logout dari sistem', '127.0.0.1', '2026-05-03 08:11:48', '2026-05-03 08:11:48'),
(25, 2, 'login', 'Login berhasil dari IP 127.0.0.1', '127.0.0.1', '2026-05-03 08:11:59', '2026-05-03 08:11:59'),
(26, 2, 'logout', 'Logout dari sistem', '127.0.0.1', '2026-05-03 08:14:14', '2026-05-03 08:14:14'),
(27, 1, 'login', 'Login berhasil dari IP 127.0.0.1', '127.0.0.1', '2026-05-03 08:14:17', '2026-05-03 08:14:17'),
(28, 1, 'create_user', 'Membuat akun baru: fuad1 (guru)', '127.0.0.1', '2026-05-03 08:15:31', '2026-05-03 08:15:31'),
(29, 1, 'logout', 'Logout dari sistem', '127.0.0.1', '2026-05-03 08:15:37', '2026-05-03 08:15:37'),
(30, 3, 'login', 'Login berhasil dari IP 127.0.0.1', '127.0.0.1', '2026-05-03 08:15:43', '2026-05-03 08:15:43'),
(31, 3, 'create_medical_record', 'Input rekam medis pasien: fuad sakit', '127.0.0.1', '2026-05-03 08:16:30', '2026-05-03 08:16:30'),
(32, 3, 'create_medical_record', 'Input rekam medis pasien: fuad sakit', '127.0.0.1', '2026-05-03 08:16:51', '2026-05-03 08:16:51'),
(33, 3, 'create_medical_record', 'Input rekam medis pasien: fuad sakit', '127.0.0.1', '2026-05-03 08:17:13', '2026-05-03 08:17:13'),
(34, 3, 'create_transaction', 'Transaksi TRX-20260503-0002 untuk pasien fuad sakit', '127.0.0.1', '2026-05-03 08:17:32', '2026-05-03 08:17:32'),
(35, 3, 'login', 'Login berhasil dari IP 192.168.18.35', '192.168.18.35', '2026-05-03 08:24:30', '2026-05-03 08:24:30'),
(36, 3, 'logout', 'Logout dari sistem', '192.168.18.35', '2026-05-03 08:24:36', '2026-05-03 08:24:36'),
(37, 1, 'login', 'Login berhasil dari IP 127.0.0.1', '127.0.0.1', '2026-05-03 16:45:59', '2026-05-03 16:45:59'),
(38, 1, 'logout', 'Logout dari sistem', '127.0.0.1', '2026-05-03 17:08:38', '2026-05-03 17:08:38'),
(39, 3, 'login', 'Login berhasil dari IP 127.0.0.1', '127.0.0.1', '2026-05-03 17:08:43', '2026-05-03 17:08:43'),
(40, 3, 'logout', 'Logout dari sistem', '127.0.0.1', '2026-05-03 17:09:07', '2026-05-03 17:09:07'),
(41, 2, 'login', 'Login berhasil dari IP 127.0.0.1', '127.0.0.1', '2026-05-03 17:09:12', '2026-05-03 17:09:12'),
(42, 2, 'logout', 'Logout dari sistem', '127.0.0.1', '2026-05-03 17:09:30', '2026-05-03 17:09:30'),
(43, 3, 'login', 'Login berhasil dari IP 127.0.0.1', '127.0.0.1', '2026-05-03 17:09:46', '2026-05-03 17:09:46'),
(44, 3, 'logout', 'Logout dari sistem', '127.0.0.1', '2026-05-03 17:11:33', '2026-05-03 17:11:33'),
(45, 1, 'login', 'Login berhasil dari IP 127.0.0.1', '127.0.0.1', '2026-05-03 17:11:37', '2026-05-03 17:11:37'),
(46, 1, 'logout', 'Logout dari sistem', '127.0.0.1', '2026-05-03 17:24:58', '2026-05-03 17:24:58'),
(47, 1, 'login', 'Login berhasil dari IP 127.0.0.1', '127.0.0.1', '2026-05-03 17:25:02', '2026-05-03 17:25:02'),
(48, 1, 'logout', 'Logout dari sistem', '127.0.0.1', '2026-05-03 17:30:20', '2026-05-03 17:30:20'),
(49, 2, 'login', 'Login berhasil dari IP 127.0.0.1', '127.0.0.1', '2026-05-03 17:30:30', '2026-05-03 17:30:30'),
(50, 2, 'create_medical_record', 'Input rekam medis pasien: fuad sakit', '127.0.0.1', '2026-05-03 17:31:27', '2026-05-03 17:31:27'),
(51, 2, 'logout', 'Logout dari sistem', '127.0.0.1', '2026-05-03 17:31:57', '2026-05-03 17:31:57'),
(52, 1, 'login', 'Login berhasil dari IP 127.0.0.1', '127.0.0.1', '2026-05-03 17:32:00', '2026-05-03 17:32:00'),
(53, 1, 'logout', 'Logout dari sistem', '127.0.0.1', '2026-05-03 17:32:39', '2026-05-03 17:32:39'),
(54, 2, 'login', 'Login berhasil dari IP 127.0.0.1', '127.0.0.1', '2026-05-03 17:32:46', '2026-05-03 17:32:46'),
(55, 2, 'logout', 'Logout dari sistem', '127.0.0.1', '2026-05-03 17:34:21', '2026-05-03 17:34:21'),
(56, 1, 'login', 'Login berhasil dari IP 127.0.0.1', '127.0.0.1', '2026-05-03 17:34:24', '2026-05-03 17:34:24'),
(57, 1, 'logout', 'Logout dari sistem', '127.0.0.1', '2026-05-03 17:34:37', '2026-05-03 17:34:37'),
(58, 2, 'login', 'Login berhasil dari IP 127.0.0.1', '127.0.0.1', '2026-05-03 17:34:44', '2026-05-03 17:34:44'),
(59, 2, 'create_transaction', 'Transaksi TRX-20260504-0001 untuk pasien fuad sakit', '127.0.0.1', '2026-05-03 17:35:17', '2026-05-03 17:35:17'),
(60, 2, 'logout', 'Logout dari sistem', '127.0.0.1', '2026-05-03 17:35:31', '2026-05-03 17:35:31'),
(61, 1, 'login', 'Login berhasil dari IP 127.0.0.1', '127.0.0.1', '2026-05-03 17:35:34', '2026-05-03 17:35:34'),
(62, 1, 'logout', 'Logout dari sistem', '127.0.0.1', '2026-05-03 18:16:43', '2026-05-03 18:16:43'),
(63, 1, 'login', 'Login berhasil dari IP 127.0.0.1', '127.0.0.1', '2026-05-03 18:17:24', '2026-05-03 18:17:24'),
(64, 1, 'logout', 'Logout dari sistem', '127.0.0.1', '2026-05-03 18:18:04', '2026-05-03 18:18:04'),
(65, 3, 'login', 'Login berhasil dari IP 127.0.0.1', '127.0.0.1', '2026-05-03 18:22:07', '2026-05-03 18:22:07'),
(66, 1, 'login', 'Login berhasil dari IP 192.168.18.82', '192.168.18.82', '2026-05-03 19:51:25', '2026-05-03 19:51:25'),
(67, 1, 'login', 'Login berhasil dari IP 192.168.18.24', '192.168.18.24', '2026-05-03 20:05:09', '2026-05-03 20:05:09'),
(68, 1, 'logout', 'Logout dari sistem', '192.168.18.24', '2026-05-03 20:05:28', '2026-05-03 20:05:28'),
(69, 1, 'login', 'Login berhasil dari IP 192.168.18.24', '192.168.18.24', '2026-05-03 20:05:49', '2026-05-03 20:05:49'),
(70, 1, 'logout', 'Logout dari sistem', '192.168.18.24', '2026-05-03 20:06:04', '2026-05-03 20:06:04'),
(71, 1, 'login', 'Login berhasil dari IP 192.168.18.24', '192.168.18.24', '2026-05-03 20:06:13', '2026-05-03 20:06:13'),
(72, 1, 'logout', 'Logout dari sistem', '192.168.18.24', '2026-05-03 20:06:27', '2026-05-03 20:06:27'),
(73, 2, 'login', 'Login berhasil dari IP 192.168.18.24', '192.168.18.24', '2026-05-03 20:06:36', '2026-05-03 20:06:36'),
(74, 2, 'logout', 'Logout dari sistem', '192.168.18.24', '2026-05-03 20:06:53', '2026-05-03 20:06:53'),
(75, 2, 'login', 'Login berhasil dari IP 192.168.18.24', '192.168.18.24', '2026-05-03 20:07:06', '2026-05-03 20:07:06'),
(76, 2, 'logout', 'Logout dari sistem', '192.168.18.24', '2026-05-03 20:07:14', '2026-05-03 20:07:14'),
(77, 3, 'login', 'Login berhasil dari IP 192.168.18.24', '192.168.18.24', '2026-05-03 20:07:19', '2026-05-03 20:07:19'),
(78, 3, 'logout', 'Logout dari sistem', '192.168.18.24', '2026-05-03 20:09:23', '2026-05-03 20:09:23'),
(79, 1, 'login', 'Login berhasil dari IP 192.168.18.24', '192.168.18.24', '2026-05-03 20:09:27', '2026-05-03 20:09:27'),
(80, 1, 'logout', 'Logout dari sistem', '192.168.18.24', '2026-05-03 20:09:34', '2026-05-03 20:09:34'),
(81, 2, 'login', 'Login berhasil dari IP 192.168.18.24', '192.168.18.24', '2026-05-03 20:09:37', '2026-05-03 20:09:37'),
(82, 2, 'logout', 'Logout dari sistem', '192.168.18.24', '2026-05-03 20:09:40', '2026-05-03 20:09:40'),
(83, 1, 'logout', 'Logout dari sistem', '192.168.18.82', '2026-05-03 20:53:38', '2026-05-03 20:53:38'),
(84, 1, 'login', 'Login berhasil dari IP 192.168.18.82', '192.168.18.82', '2026-05-03 20:53:52', '2026-05-03 20:53:52'),
(85, 1, 'logout', 'Logout dari sistem', '192.168.18.82', '2026-05-03 20:54:22', '2026-05-03 20:54:22'),
(86, 2, 'login', 'Login berhasil dari IP 192.168.18.82', '192.168.18.82', '2026-05-03 20:54:42', '2026-05-03 20:54:42'),
(87, 2, 'logout', 'Logout dari sistem', '192.168.18.82', '2026-05-03 20:54:47', '2026-05-03 20:54:47'),
(88, 1, 'login', 'Login berhasil dari IP 192.168.32.21', '192.168.32.21', '2026-05-03 23:47:59', '2026-05-03 23:47:59'),
(89, 1, 'logout', 'Logout dari sistem', '192.168.32.21', '2026-05-03 23:48:30', '2026-05-03 23:48:30'),
(90, 2, 'login', 'Login berhasil dari IP 192.168.32.21', '192.168.32.21', '2026-05-03 23:48:49', '2026-05-03 23:48:49'),
(91, 2, 'login', 'Login berhasil dari IP 192.168.32.236', '192.168.32.236', '2026-05-03 23:51:51', '2026-05-03 23:51:51'),
(92, 2, 'logout', 'Logout dari sistem', '192.168.32.236', '2026-05-03 23:53:13', '2026-05-03 23:53:13'),
(93, 2, 'login', 'Login berhasil dari IP 192.168.32.236', '192.168.32.236', '2026-05-03 23:56:24', '2026-05-03 23:56:24'),
(94, 2, 'logout', 'Logout dari sistem', '192.168.32.236', '2026-05-03 23:58:33', '2026-05-03 23:58:33'),
(95, 1, 'login', 'Login berhasil dari IP 192.168.32.236', '192.168.32.236', '2026-05-04 00:53:27', '2026-05-04 00:53:27');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `medical_records`
--

CREATE TABLE `medical_records` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `patient_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `gula_darah` decimal(6,2) DEFAULT NULL,
  `kolesterol` decimal(6,2) DEFAULT NULL,
  `asam_urat` decimal(5,2) DEFAULT NULL,
  `tensi_sistolik` int(11) DEFAULT NULL,
  `tensi_diastolik` int(11) DEFAULT NULL,
  `suhu` decimal(4,1) DEFAULT NULL,
  `nadi` int(11) DEFAULT NULL,
  `respirasi` int(11) DEFAULT NULL,
  `catatan` text DEFAULT NULL,
  `tanggal_periksa` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `medical_records`
--

INSERT INTO `medical_records` (`id`, `patient_id`, `user_id`, `gula_darah`, `kolesterol`, `asam_urat`, `tensi_sistolik`, `tensi_diastolik`, `suhu`, `nadi`, `respirasi`, `catatan`, `tanggal_periksa`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 120.00, 180.00, 5.50, 120, 80, NULL, NULL, NULL, NULL, '2026-05-03', '2026-05-03 00:26:15', '2026-05-03 00:26:15'),
(2, 1, 3, 200.00, 300.00, 10.00, 180, 120, 40.0, 80, 30, NULL, '2026-05-03', '2026-05-03 08:16:30', '2026-05-03 08:16:30'),
(3, 1, 3, 200.00, 300.00, 10.00, 180, 120, 40.0, 80, 30, NULL, '2026-05-03', '2026-05-03 08:16:51', '2026-05-03 08:16:51'),
(4, 1, 3, 200.00, 300.00, 10.00, 180, 120, 40.0, 80, 30, NULL, '2026-05-03', '2026-05-03 08:17:13', '2026-05-03 08:17:13'),
(5, 1, 2, 200.00, 170.00, 6.00, 160, 100, 40.0, 90, 20, NULL, '2026-05-04', '2026-05-03 17:31:27', '2026-05-03 17:31:27');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_05_02_173921_create_patients_table', 2),
(5, '2026_05_02_173929_create_medical_records_table', 2),
(6, '2026_05_02_173935_create_products_table', 2),
(7, '2026_05_02_173940_create_services_table', 2),
(8, '2026_05_02_173946_create_packages_table', 2),
(9, '2026_05_02_173950_create_package_services_table', 2),
(10, '2026_05_02_173955_create_transactions_table', 2),
(11, '2026_05_02_174001_create_transaction_items_table', 2),
(12, '2026_05_02_174005_create_activity_logs_table', 2),
(13, '2026_05_02_174013_add_role_fields_to_users_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `packages`
--

CREATE TABLE `packages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `harga` decimal(10,2) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `packages`
--

INSERT INTO `packages` (`id`, `nama`, `harga`, `deskripsi`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Paket Sehat 1', 85000.00, 'Pijat full body, totok wajah, cek vital sign, herbal drink', 1, '2026-05-02 19:12:32', '2026-05-02 19:12:32'),
(2, 'Paket Sehat 2', 40000.00, 'Terapi infra red, pijat kaki, cek vital sign', 1, '2026-05-02 19:12:32', '2026-05-02 19:12:32'),
(3, 'Paket Sehat 3', 35000.00, 'Pijat kasur panas, pijat kaki, cek vital sign', 1, '2026-05-02 19:12:32', '2026-05-02 19:12:32'),
(4, 'Paket Sehat 4', 70000.00, 'Konsultasi gizi, cek darah lengkap, cek BMI, cek vital sign', 1, '2026-05-02 19:12:32', '2026-05-02 19:12:32'),
(5, 'Paket Sehat 5', 25000.00, 'Senam lansia, cek BMI, cek vital sign', 1, '2026-05-02 19:12:32', '2026-05-02 19:12:32');

-- --------------------------------------------------------

--
-- Table structure for table `package_services`
--

CREATE TABLE `package_services` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `package_id` bigint(20) UNSIGNED NOT NULL,
  `service_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `package_services`
--

INSERT INTO `package_services` (`id`, `package_id`, `service_id`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 13),
(4, 1, 14),
(5, 1, 15),
(6, 1, 16),
(7, 1, 17),
(8, 1, 18),
(9, 2, 3),
(10, 2, 5),
(11, 2, 13),
(12, 2, 14),
(13, 2, 15),
(14, 2, 16),
(15, 2, 18),
(16, 3, 3),
(17, 3, 4),
(18, 3, 13),
(19, 3, 14),
(20, 3, 15),
(21, 3, 16),
(22, 3, 18),
(23, 4, 7),
(24, 4, 8),
(25, 4, 9),
(26, 4, 10),
(27, 4, 11),
(28, 4, 12),
(29, 4, 13),
(30, 4, 14),
(31, 4, 15),
(32, 4, 16),
(33, 4, 18),
(34, 5, 6),
(35, 5, 11),
(36, 5, 13),
(37, 5, 14),
(38, 5, 15),
(39, 5, 16),
(40, 5, 18);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

CREATE TABLE `patients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `no_rm` varchar(255) NOT NULL,
  `kode_unik` varchar(255) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `jenis_kelamin` enum('L','P') DEFAULT NULL,
  `telepon` varchar(255) DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `patients`
--

INSERT INTO `patients` (`id`, `no_rm`, `kode_unik`, `nama`, `alamat`, `tanggal_lahir`, `jenis_kelamin`, `telepon`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'RM-202605-0001', '6A671634', 'fuad sakit', 'jogja', '2000-03-14', 'L', '08123456789', 2, '2026-05-03 00:24:24', '2026-05-03 00:24:24');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode_produk` varchar(255) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `kategori` enum('makanan_minuman','pembersih','lainnya') NOT NULL,
  `harga` decimal(10,2) NOT NULL DEFAULT 0.00,
  `harga_by_order` tinyint(1) NOT NULL DEFAULT 0,
  `stok` int(11) NOT NULL DEFAULT 0,
  `satuan` varchar(255) NOT NULL DEFAULT 'pcs',
  `keterangan` varchar(255) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `kode_produk`, `nama`, `kategori`, `harga`, `harga_by_order`, `stok`, `satuan`, `keterangan`, `foto`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'PRD-001', 'KORI CHIPS', 'makanan_minuman', 6000.00, 0, 10, 'pcs', 'Keripik pegagan 40g', NULL, 1, '2026-05-02 19:12:32', '2026-05-02 19:12:32'),
(2, 'PRD-002', 'CHOCO PSBB', 'makanan_minuman', 6000.00, 0, 10, 'pcs', 'Cokelat herbal', NULL, 1, '2026-05-02 19:12:32', '2026-05-02 19:12:32'),
(3, 'PRD-003', 'JAMPI PSBB (pouch)', 'makanan_minuman', 10000.00, 0, 10, 'pcs', 'Pouch 20g', NULL, 1, '2026-05-02 19:12:32', '2026-05-02 19:12:32'),
(4, 'PRD-004', 'JAMPI PSBB (botol)', 'makanan_minuman', 25000.00, 0, 10, 'pcs', 'Botol 150g', NULL, 1, '2026-05-02 19:12:32', '2026-05-02 19:12:32'),
(5, 'PRD-005', 'JENS', 'makanan_minuman', 4000.00, 0, 10, 'pcs', 'Sari jeruk nipis serbuk sachet', NULL, 1, '2026-05-02 19:12:32', '2026-05-02 19:12:32'),
(6, 'PRD-006', 'IMUN JELLY', 'makanan_minuman', 0.00, 1, 10, 'pcs', 'Jeli peningkat imun', NULL, 1, '2026-05-02 19:12:32', '2026-05-02 19:12:32'),
(7, 'PRD-007', 'NILA MARINASI', 'makanan_minuman', 30000.00, 0, 10, 'pcs', '±300g, harga menyesuaikan pasar', NULL, 1, '2026-05-02 19:12:32', '2026-05-02 19:12:32'),
(8, 'PRD-008', 'AYAM UNGKEP HERBAL', 'makanan_minuman', 25000.00, 0, 10, 'pcs', '±300g, harga menyesuaikan pasar', NULL, 1, '2026-05-02 19:12:32', '2026-05-02 19:12:32'),
(9, 'PRD-009', 'PHARMALIGHT 450ML', 'pembersih', 7000.00, 0, 10, 'pcs', 'Pencuci piring kemasan botol', NULL, 1, '2026-05-02 19:12:32', '2026-05-02 19:12:32'),
(10, 'PRD-010', 'DESIN SPRAY', 'pembersih', 20000.00, 0, 10, 'pcs', 'Desinfektan cair 200ml', NULL, 1, '2026-05-02 19:12:32', '2026-05-02 19:12:32'),
(11, 'PRD-011', 'F HAND SOAP', 'pembersih', 15000.00, 0, 10, 'pcs', 'Pencuci tangan pump 450ml', NULL, 1, '2026-05-02 19:12:32', '2026-05-02 19:12:32'),
(12, 'PRD-012', 'HAND SANITIZER', 'pembersih', 120000.00, 0, 10, 'pcs', 'Jerigen 5 liter', NULL, 1, '2026-05-02 19:12:32', '2026-05-02 19:12:32'),
(13, 'PRD-013', 'ALL SABUN', 'pembersih', 65000.00, 0, 10, 'pcs', 'Jerigen 5 liter', NULL, 1, '2026-05-02 19:12:32', '2026-05-02 19:12:32'),
(14, 'PRD-014', 'PHARMACARE ROLL ON', 'lainnya', 20000.00, 0, 10, 'pcs', 'Botol kaca 10ml', NULL, 1, '2026-05-02 19:12:32', '2026-05-02 19:12:32'),
(15, 'PRD-015', 'NATURA SOAP SEREH', 'lainnya', 12000.00, 0, 10, 'pcs', 'Sabun mandi 75g', NULL, 1, '2026-05-02 19:12:32', '2026-05-02 19:12:32'),
(16, 'PRD-016', 'HERBS OIL', 'lainnya', 30000.00, 0, 10, 'pcs', 'Minyak urut 100ml', NULL, 1, '2026-05-02 19:12:32', '2026-05-02 19:12:32'),
(17, 'PRD-017', 'ROCY FACE MIST', 'lainnya', 10000.00, 0, 10, 'pcs', 'Botol stick 8ml', NULL, 1, '2026-05-02 19:12:32', '2026-05-02 19:12:32'),
(18, 'PRD-018', 'PUPUK ORGANIK CAIR', 'lainnya', 20000.00, 0, 10, 'pcs', 'Botol 180ml', NULL, 1, '2026-05-02 19:12:32', '2026-05-02 19:12:32');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode` varchar(255) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `harga` decimal(10,2) NOT NULL DEFAULT 0.00,
  `durasi_menit` int(11) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `kode`, `nama`, `harga`, `durasi_menit`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'SVC-001', 'Pijat Full Body 45 Menit', 65000.00, 45, 1, '2026-05-02 19:12:32', '2026-05-02 19:12:32'),
(2, 'SVC-002', 'Totok Wajah 30 Menit', 25000.00, 30, 1, '2026-05-02 19:12:32', '2026-05-02 19:12:32'),
(3, 'SVC-003', 'Pijat Kaki dengan Alat', 25000.00, 30, 1, '2026-05-02 19:12:32', '2026-05-02 19:12:32'),
(4, 'SVC-004', 'Pijat di Kasur Panas', 15000.00, 30, 1, '2026-05-02 19:12:32', '2026-05-02 19:12:32'),
(5, 'SVC-005', 'Terapi Infra Red', 20000.00, 30, 1, '2026-05-02 19:12:32', '2026-05-02 19:12:32'),
(6, 'SVC-006', 'Senam Lansia', 15000.00, 60, 1, '2026-05-02 19:12:32', '2026-05-02 19:12:32'),
(7, 'SVC-007', 'Cek Asam Urat', 15000.00, NULL, 1, '2026-05-02 19:12:32', '2026-05-02 19:12:32'),
(8, 'SVC-008', 'Cek Kolesterol', 25000.00, NULL, 1, '2026-05-02 19:12:32', '2026-05-02 19:12:32'),
(9, 'SVC-009', 'Cek Gula Darah', 10000.00, NULL, 1, '2026-05-02 19:12:32', '2026-05-02 19:12:32'),
(10, 'SVC-010', 'Konsultasi Gizi', 10000.00, 30, 1, '2026-05-02 19:12:32', '2026-05-02 19:12:32'),
(11, 'SVC-011', 'Cek BMI', 15000.00, NULL, 1, '2026-05-02 19:12:32', '2026-05-02 19:12:32'),
(12, 'SVC-012', 'Cek Antropometri', 5000.00, NULL, 1, '2026-05-02 19:12:32', '2026-05-02 19:12:32'),
(13, 'SVC-013', 'Cek Tekanan Darah', 0.00, NULL, 1, '2026-05-02 19:12:32', '2026-05-02 19:12:32'),
(14, 'SVC-014', 'Cek Suhu', 0.00, NULL, 1, '2026-05-02 19:12:32', '2026-05-02 19:12:32'),
(15, 'SVC-015', 'Cek Nadi', 0.00, NULL, 1, '2026-05-02 19:12:32', '2026-05-02 19:12:32'),
(16, 'SVC-016', 'Cek Respirasi', 0.00, NULL, 1, '2026-05-02 19:12:32', '2026-05-02 19:12:32'),
(17, 'SVC-017', 'Herbal Drink', 0.00, NULL, 1, '2026-05-02 19:12:32', '2026-05-02 19:12:32'),
(18, 'SVC-018', 'Pelayanan Informasi Obat', 0.00, NULL, 1, '2026-05-02 19:12:32', '2026-05-02 19:12:32');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('63RszqYk2hx6xYW9JKUO2RjCj0jAtcUFdcdJ9XJI', 1, '192.168.32.236', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiaHhjcHFDRDZuVm1nbVladW1hVW9jTjRRRGlObDdicjI4clFTcW1EciI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDg6Imh0dHA6Ly8xOTIuMTY4LjMyLjIzNjo4MDAwL2FkbWluL3Byb2R1Y3RzP3BhZ2U9MiI7czo1OiJyb3V0ZSI7czoyMDoiYWRtaW4ucHJvZHVjdHMuaW5kZXgiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1777881555),
('C75JxTwM1kQ2hb3aV6P69lU4EmEimx1CCHBwkRgn', NULL, '192.168.32.236', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiVXE5RkZ0ZkZnWktVclQ5eFJVTVl6bE1WOUNrNEo1aGU5S1hFUldzUCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xOTIuMTY4LjMyLjIzNjo4MDAwL3BvcnRhbCI7czo1OiJyb3V0ZSI7czoxMzoicGF0aWVudC5sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1777879643),
('jRpb5F6x9uZpvdzE6ZffHGI6n1kcynxJS8fnZcEJ', NULL, '192.168.32.21', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiRHM4d1ZSeU1VczJHRjdvQ2hJSlRlMHFxeUlxbFFXbno2NjlZbGFKZCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1777877529),
('Ut2t7MwhXXGZRLrEAdYApj45LtZo9W0KvOrs0GNL', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiR2haVXdEY3RRUGprVlVtaFU2V21STWNJSFhRdmZIRjRva2MxRlZIViI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1777880400),
('xNyE8DlyUbdUhShWabiMrknvMMmXlGDMeoo7kU6V', 2, '192.168.32.21', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoic3ZuZ1EzdzhheXo4RXVRVjVtYkpRUGtmaUZkNXpGVXdaa3JSS3pyRSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly8xOTIuMTY4LjMyLjIzNjo4MDAwL2xvZ2luIjtzOjU6InJvdXRlIjtzOjU6ImxvZ2luIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Mjt9', 1777877329);

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `no_transaksi` varchar(255) NOT NULL,
  `patient_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `medical_record_id` bigint(20) UNSIGNED DEFAULT NULL,
  `subtotal` decimal(12,2) NOT NULL DEFAULT 0.00,
  `diskon` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total` decimal(12,2) NOT NULL DEFAULT 0.00,
  `metode_bayar` varchar(255) NOT NULL DEFAULT 'cash',
  `status` enum('pending','selesai','batal') NOT NULL DEFAULT 'selesai',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `no_transaksi`, `patient_id`, `user_id`, `medical_record_id`, `subtotal`, `diskon`, `total`, `metode_bayar`, `status`, `created_at`, `updated_at`) VALUES
(1, 'TRX-20260503-0001', 1, 2, NULL, 85000.00, 0.00, 85000.00, 'cash', 'selesai', '2026-05-03 04:30:38', '2026-05-03 04:30:38'),
(2, 'TRX-20260503-0002', 1, 3, NULL, 25000.00, 0.00, 25000.00, 'cash', 'selesai', '2026-05-03 08:17:32', '2026-05-03 08:17:32'),
(3, 'TRX-20260504-0001', 1, 2, NULL, 50000.00, 0.00, 50000.00, 'cash', 'selesai', '2026-05-03 17:35:17', '2026-05-03 17:35:17');

-- --------------------------------------------------------

--
-- Table structure for table `transaction_items`
--

CREATE TABLE `transaction_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `transaction_id` bigint(20) UNSIGNED NOT NULL,
  `item_type` enum('service','product','package') NOT NULL,
  `item_id` bigint(20) UNSIGNED NOT NULL,
  `nama_item` varchar(255) NOT NULL,
  `harga_satuan` decimal(10,2) NOT NULL,
  `qty` int(11) NOT NULL DEFAULT 1,
  `subtotal` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transaction_items`
--

INSERT INTO `transaction_items` (`id`, `transaction_id`, `item_type`, `item_id`, `nama_item`, `harga_satuan`, `qty`, `subtotal`, `created_at`, `updated_at`) VALUES
(1, 1, 'package', 1, 'Paket Sehat 1', 85000.00, 1, 85000.00, '2026-05-03 04:30:38', '2026-05-03 04:30:38'),
(2, 2, 'package', 5, 'Paket Sehat 5', 25000.00, 1, 25000.00, '2026-05-03 08:17:32', '2026-05-03 08:17:32'),
(3, 3, 'service', 7, 'Cek Asam Urat', 15000.00, 1, 15000.00, '2026-05-03 17:35:17', '2026-05-03 17:35:17'),
(4, 3, 'service', 8, 'Cek Kolesterol', 25000.00, 1, 25000.00, '2026-05-03 17:35:17', '2026-05-03 17:35:17'),
(5, 3, 'service', 9, 'Cek Gula Darah', 10000.00, 1, 10000.00, '2026-05-03 17:35:17', '2026-05-03 17:35:17');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `role` enum('admin','guru','siswa') NOT NULL DEFAULT 'guru',
  `barcode` varchar(255) DEFAULT NULL,
  `jabatan` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `must_change_password` tinyint(1) NOT NULL DEFAULT 0,
  `last_login` timestamp NULL DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `email`, `role`, `barcode`, `jabatan`, `is_active`, `must_change_password`, `last_login`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Administrator', 'admin', 'admin@sik-binatama.sch.id', 'admin', NULL, 'Administrator Sistem', 1, 0, '2026-05-04 00:53:27', NULL, '$2y$12$MWvOp5QDjV3QddIazbQaPO7g3dE6kfTNeAPVtr8UPOJlMqZttxiU.', NULL, '2026-05-02 19:12:32', '2026-05-04 00:53:27'),
(2, 'fuad', 'fuad', 'fuad@gmail.com', 'guru', NULL, 'guru', 1, 0, '2026-05-03 23:56:24', NULL, '$2y$12$lZJHqi0ODXbn.eRPHRKNTezPPaQDmAxA3SjHUxPj5CY4bKsZ9FKEW', NULL, '2026-05-02 19:37:44', '2026-05-03 23:56:24'),
(3, 'fuad1', 'fuad1', 'fuad1@gmail.com', 'guru', NULL, NULL, 1, 0, '2026-05-03 20:07:19', NULL, '$2y$12$915omX3fWMXX8ARMVTTl5O/CRirzQK9O9.AUui3WaHbBcSclSMIeW', NULL, '2026-05-03 08:15:31', '2026-05-03 20:07:19');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activity_logs_user_id_foreign` (`user_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `medical_records`
--
ALTER TABLE `medical_records`
  ADD PRIMARY KEY (`id`),
  ADD KEY `medical_records_patient_id_foreign` (`patient_id`),
  ADD KEY `medical_records_user_id_foreign` (`user_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `packages`
--
ALTER TABLE `packages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `package_services`
--
ALTER TABLE `package_services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `package_services_package_id_foreign` (`package_id`),
  ADD KEY `package_services_service_id_foreign` (`service_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `patients_no_rm_unique` (`no_rm`),
  ADD UNIQUE KEY `patients_kode_unik_unique` (`kode_unik`),
  ADD KEY `patients_created_by_foreign` (`created_by`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `products_kode_produk_unique` (`kode_produk`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `services_kode_unique` (`kode`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `transactions_no_transaksi_unique` (`no_transaksi`),
  ADD KEY `transactions_patient_id_foreign` (`patient_id`),
  ADD KEY `transactions_user_id_foreign` (`user_id`),
  ADD KEY `transactions_medical_record_id_foreign` (`medical_record_id`);

--
-- Indexes for table `transaction_items`
--
ALTER TABLE `transaction_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transaction_items_transaction_id_foreign` (`transaction_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_username_unique` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=96;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `medical_records`
--
ALTER TABLE `medical_records`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `packages`
--
ALTER TABLE `packages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `package_services`
--
ALTER TABLE `package_services`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `patients`
--
ALTER TABLE `patients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `transaction_items`
--
ALTER TABLE `transaction_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `activity_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `medical_records`
--
ALTER TABLE `medical_records`
  ADD CONSTRAINT `medical_records_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `medical_records_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `package_services`
--
ALTER TABLE `package_services`
  ADD CONSTRAINT `package_services_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `package_services_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `patients`
--
ALTER TABLE `patients`
  ADD CONSTRAINT `patients_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_medical_record_id_foreign` FOREIGN KEY (`medical_record_id`) REFERENCES `medical_records` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `transactions_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `transaction_items`
--
ALTER TABLE `transaction_items`
  ADD CONSTRAINT `transaction_items_transaction_id_foreign` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
