-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 01, 2025 at 03:28 PM
-- Server version: 8.0.30
-- PHP Version: 8.3.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dailymoo`
--

-- --------------------------------------------------------

--
-- Table structure for table `articles`
--

CREATE TABLE `articles` (
  `id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cow_weights`
--

CREATE TABLE `cow_weights` (
  `id` bigint UNSIGNED NOT NULL,
  `cow_id` int NOT NULL COMMENT 'Nomor sapi (1-10)',
  `weight` decimal(8,2) NOT NULL COMMENT 'Bobot dalam kg',
  `measured_at` date NOT NULL COMMENT 'Tanggal pengukuran',
  `notes` text COLLATE utf8mb4_unicode_ci COMMENT 'Catatan tambahan',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cow_weights`
--

INSERT INTO `cow_weights` (`id`, `cow_id`, `weight`, `measured_at`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 1000.00, '2025-11-30', 'Saran pakan: 12.6 kg | Persetujuan: Setuju', '2025-11-30 16:10:44', '2025-11-30 16:10:44'),
(2, 2, 2500.00, '2025-11-30', 'Saran pakan: 8.9 kg | Persetujuan: Setuju', '2025-11-30 16:10:44', '2025-11-30 16:10:44'),
(3, 3, 3000.00, '2025-11-30', 'Saran pakan: 7.6 kg | Persetujuan: Setuju', '2025-11-30 16:10:44', '2025-11-30 16:10:44'),
(4, 4, 2500.00, '2025-11-30', 'Saran pakan: 8.9 kg | Persetujuan: Setuju', '2025-11-30 16:10:44', '2025-11-30 16:10:44'),
(5, 5, 1000.00, '2025-11-30', 'Saran pakan: 12.6 kg | Persetujuan: Setuju', '2025-11-30 16:10:44', '2025-11-30 16:10:44'),
(6, 6, 2000.00, '2025-11-30', 'Saran pakan: 10.1 kg | Persetujuan: Setuju', '2025-11-30 16:10:44', '2025-11-30 16:10:44'),
(7, 7, 250.00, '2025-11-30', 'Saran pakan: 14.4 kg | Persetujuan: Setuju', '2025-11-30 16:10:44', '2025-11-30 16:10:44'),
(8, 8, 2000.00, '2025-11-30', 'Saran pakan: 10.1 kg | Persetujuan: Setuju', '2025-11-30 16:10:44', '2025-11-30 16:10:44'),
(9, 9, 2500.00, '2025-11-30', 'Saran pakan: 8.9 kg | Persetujuan: Setuju', '2025-11-30 16:10:44', '2025-11-30 16:10:44'),
(10, 10, 2400.00, '2025-11-30', 'Saran pakan: 9.1 kg | Persetujuan: Setuju', '2025-11-30 16:10:44', '2025-11-30 16:10:44'),
(11, 1, 1500.00, '2025-09-30', 'Saran pakan: 11.3 kg | Persetujuan: Setuju', '2025-11-30 16:12:55', '2025-11-30 16:12:55'),
(12, 2, 250.00, '2025-09-30', 'Saran pakan: 14.5 kg | Persetujuan: Setuju', '2025-11-30 16:12:55', '2025-11-30 16:12:55'),
(13, 3, 3600.00, '2025-09-30', 'Saran pakan: 6.2 kg | Persetujuan: Setuju', '2025-11-30 16:12:55', '2025-11-30 16:12:55'),
(14, 4, 300.00, '2025-09-30', 'Saran pakan: 14.3 kg | Persetujuan: Setuju', '2025-11-30 16:12:55', '2025-11-30 16:12:55'),
(15, 5, 2000.00, '2025-09-30', 'Saran pakan: 10.1 kg | Persetujuan: Setuju', '2025-11-30 16:12:55', '2025-11-30 16:12:55'),
(16, 6, 1000.00, '2025-09-30', 'Saran pakan: 12.6 kg | Persetujuan: Setuju', '2025-11-30 16:12:55', '2025-11-30 16:12:55'),
(17, 7, 1000.00, '2025-09-30', 'Saran pakan: 12.6 kg | Persetujuan: Setuju', '2025-11-30 16:12:55', '2025-11-30 16:12:55'),
(18, 8, 250.00, '2025-09-30', 'Saran pakan: 14.4 kg | Persetujuan: Setuju', '2025-11-30 16:12:55', '2025-11-30 16:12:55'),
(19, 9, 1500.00, '2025-09-30', 'Saran pakan: 11.4 kg | Persetujuan: Setuju', '2025-11-30 16:12:55', '2025-11-30 16:12:55'),
(20, 10, 1000.00, '2025-09-30', 'Saran pakan: 12.6 kg | Persetujuan: Setuju', '2025-11-30 16:12:55', '2025-11-30 16:12:55');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `feeding_history`
--

CREATE TABLE `feeding_history` (
  `id` bigint UNSIGNED NOT NULL,
  `cow_id` int NOT NULL,
  `cow_weight` decimal(8,2) NOT NULL,
  `feed_given` decimal(8,2) NOT NULL,
  `feed_eaten` decimal(8,2) NOT NULL,
  `date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `feeding_logs`
--

CREATE TABLE `feeding_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `session` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pagi',
  `feed_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `offered_amount` double NOT NULL DEFAULT '0',
  `consumed_amount` double NOT NULL DEFAULT '0',
  `cow_weight` double DEFAULT NULL,
  `recorded_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `feeding_logs`
--

INSERT INTO `feeding_logs` (`id`, `session`, `feed_type`, `offered_amount`, `consumed_amount`, `cow_weight`, `recorded_at`, `created_at`, `updated_at`) VALUES
(1, 'pagi', 'Konsentrat Premium', 137.6, 136.7, 424.8, '2025-11-20 01:00:00', '2025-11-20 12:40:17', '2025-11-20 12:40:17'),
(2, 'siang', 'Konsentrat Premium', 129.2, 126.8, 396.2, '2025-11-20 13:00:00', '2025-11-20 12:40:17', '2025-11-20 12:40:17'),
(3, 'sore', 'Silase Jagung', 131.1, 125.7, 414.3, '2025-11-20 00:00:00', '2025-11-20 12:40:17', '2025-11-20 12:40:17'),
(4, 'pagi', 'Konsentrat Premium', 119, 115.7, 408.2, '2025-11-19 01:00:00', '2025-11-20 12:40:17', '2025-11-20 12:40:17'),
(5, 'siang', 'Silase Jagung', 117.9, 113.9, 417.4, '2025-11-19 13:00:00', '2025-11-20 12:40:17', '2025-11-20 12:40:17'),
(6, 'sore', 'Silase Jagung', 128.1, 123.3, 424.3, '2025-11-19 05:00:00', '2025-11-20 12:40:17', '2025-11-20 12:40:17'),
(7, 'pagi', 'Konsentrat Premium', 138.3, 132.9, 405.9, '2025-11-18 12:00:00', '2025-11-20 12:40:17', '2025-11-20 12:40:17'),
(8, 'siang', 'Rumput Gajah', 132.5, 124.8, 417.7, '2025-11-18 06:00:00', '2025-11-20 12:40:17', '2025-11-20 12:40:17'),
(9, 'sore', 'Konsentrat Premium', 135.3, 135, 398.9, '2025-11-18 01:00:00', '2025-11-20 12:40:17', '2025-11-20 12:40:17'),
(10, 'pagi', 'Konsentrat Premium', 135.3, 135.2, 405.7, '2025-11-17 03:00:00', '2025-11-20 12:40:17', '2025-11-20 12:40:17'),
(11, 'siang', 'Silase Jagung', 128.1, 123.5, 398.7, '2025-11-16 22:00:00', '2025-11-20 12:40:17', '2025-11-20 12:40:17'),
(12, 'sore', 'Konsentrat Premium', 116.9, 113.5, 400.5, '2025-11-17 09:00:00', '2025-11-20 12:40:17', '2025-11-20 12:40:17'),
(13, 'pagi', 'Silase Jagung', 130.7, 123.5, 426, '2025-11-16 00:00:00', '2025-11-20 12:40:17', '2025-11-20 12:40:17'),
(14, 'siang', 'Konsentrat Premium', 117.8, 117, 406.3, '2025-11-16 09:00:00', '2025-11-20 12:40:17', '2025-11-20 12:40:17'),
(15, 'sore', 'Konsentrat Premium', 120.2, 118, 422.6, '2025-11-16 10:00:00', '2025-11-20 12:40:17', '2025-11-20 12:40:17'),
(16, 'pagi', 'Konsentrat Premium', 138.7, 132.6, 395.4, '2025-11-14 23:00:00', '2025-11-20 12:40:17', '2025-11-20 12:40:17'),
(17, 'siang', 'Rumput Gajah', 121.3, 117.4, 416.1, '2025-11-15 06:00:00', '2025-11-20 12:40:17', '2025-11-20 12:40:17'),
(18, 'sore', 'Silase Jagung', 124.6, 118.2, 422.3, '2025-11-15 07:00:00', '2025-11-20 12:40:17', '2025-11-20 12:40:17'),
(19, 'pagi', 'Konsentrat Premium', 125.7, 121, 415.3, '2025-11-14 12:00:00', '2025-11-20 12:40:17', '2025-11-20 12:40:17'),
(20, 'siang', 'Rumput Gajah', 120.6, 115.9, 418.7, '2025-11-14 03:00:00', '2025-11-20 12:40:17', '2025-11-20 12:40:17'),
(21, 'sore', 'Konsentrat Premium', 138.6, 132.6, 398.4, '2025-11-14 06:00:00', '2025-11-20 12:40:17', '2025-11-20 12:40:17');

-- --------------------------------------------------------

--
-- Table structure for table `humidity_readings`
--

CREATE TABLE `humidity_readings` (
  `id` bigint UNSIGNED NOT NULL,
  `value` double NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'normal',
  `recorded_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `humidity_readings`
--

INSERT INTO `humidity_readings` (`id`, `value`, `status`, `recorded_at`, `created_at`, `updated_at`) VALUES
(1, 71.5, 'normal', '2025-11-19 12:40:16', '2025-11-20 12:40:16', '2025-11-20 12:40:16'),
(2, 65.2, 'kering', '2025-11-19 13:40:16', '2025-11-20 12:40:16', '2025-11-20 12:40:16'),
(3, 62.3, 'normal', '2025-11-19 14:40:16', '2025-11-20 12:40:16', '2025-11-20 12:40:16'),
(4, 60.6, 'kering', '2025-11-19 15:40:16', '2025-11-20 12:40:16', '2025-11-20 12:40:16'),
(5, 64.3, 'lembap', '2025-11-19 16:40:16', '2025-11-20 12:40:16', '2025-11-20 12:40:16'),
(6, 71.9, 'lembap', '2025-11-19 17:40:16', '2025-11-20 12:40:16', '2025-11-20 12:40:16'),
(7, 67.2, 'normal', '2025-11-19 18:40:16', '2025-11-20 12:40:16', '2025-11-20 12:40:16'),
(8, 61.1, 'lembap', '2025-11-19 19:40:16', '2025-11-20 12:40:16', '2025-11-20 12:40:16'),
(9, 71.3, 'normal', '2025-11-19 20:40:16', '2025-11-20 12:40:16', '2025-11-20 12:40:16'),
(10, 62.8, 'kering', '2025-11-19 21:40:16', '2025-11-20 12:40:16', '2025-11-20 12:40:16'),
(11, 62.2, 'normal', '2025-11-19 22:40:16', '2025-11-20 12:40:16', '2025-11-20 12:40:16'),
(12, 73.5, 'kering', '2025-11-19 23:40:16', '2025-11-20 12:40:16', '2025-11-20 12:40:16'),
(13, 60.7, 'lembap', '2025-11-20 00:40:16', '2025-11-20 12:40:16', '2025-11-20 12:40:16'),
(14, 58.4, 'lembap', '2025-11-20 01:40:16', '2025-11-20 12:40:16', '2025-11-20 12:40:16'),
(15, 64.8, 'lembap', '2025-11-20 02:40:16', '2025-11-20 12:40:16', '2025-11-20 12:40:16'),
(16, 76.5, 'normal', '2025-11-20 03:40:16', '2025-11-20 12:40:16', '2025-11-20 12:40:16'),
(17, 59.2, 'normal', '2025-11-20 04:40:16', '2025-11-20 12:40:16', '2025-11-20 12:40:16'),
(18, 58.5, 'kering', '2025-11-20 05:40:16', '2025-11-20 12:40:16', '2025-11-20 12:40:16'),
(19, 76.9, 'lembap', '2025-11-20 06:40:17', '2025-11-20 12:40:17', '2025-11-20 12:40:17'),
(20, 69.1, 'kering', '2025-11-20 07:40:17', '2025-11-20 12:40:17', '2025-11-20 12:40:17'),
(21, 55, 'normal', '2025-11-20 08:40:17', '2025-11-20 12:40:17', '2025-11-20 12:40:17'),
(22, 57.7, 'kering', '2025-11-20 09:40:17', '2025-11-20 12:40:17', '2025-11-20 12:40:17'),
(23, 75.1, 'kering', '2025-11-20 10:40:17', '2025-11-20 12:40:17', '2025-11-20 12:40:17'),
(24, 63.8, 'lembap', '2025-11-20 11:40:17', '2025-11-20 12:40:17', '2025-11-20 12:40:17'),
(25, 59.8, 'lembap', '2025-11-20 12:40:17', '2025-11-20 12:40:17', '2025-11-20 12:40:17');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `knowledge`
--

CREATE TABLE `knowledge` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `excerpt` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `knowledge`
--

INSERT INTO `knowledge` (`id`, `title`, `excerpt`, `content`, `category`, `image`, `date`, `created_at`, `updated_at`) VALUES
(1, 'Cara Meningkatkan Produksi Susu Sapi Perah', 'Pelajari teknik dan strategi untuk meningkatkan produksi susu sapi perah dengan metode modern dan teknologi IoT.', 'Produksi susu sapi perah dapat ditingkatkan melalui beberapa pendekatan terpadu:\n\n1. **Manajemen Pakan yang Optimal**\n   - Berikan pakan berkualitas tinggi dengan kandungan nutrisi seimbang\n   - Pastikan sapi mendapat pakan hijauan segar minimal 3-4 kg per hari\n   - Tambahkan konsentrat sesuai kebutuhan produksi susu\n   - Gunakan teknologi IoT untuk monitoring konsumsi pakan secara real-time\n\n2. **Kontrol Lingkungan Kandang**\n   - Suhu ideal kandang: 18-22°C\n   - Kelembaban optimal: 60-70%\n   - Ventilasi yang baik untuk sirkulasi udara\n   - Pencahayaan yang cukup minimal 16 jam per hari\n\n3. **Program Pemeliharaan Kesehatan**\n   - Vaksinasi rutin sesuai jadwal\n   - Pemeriksaan kesehatan berkala\n   - Manajemen stres yang baik\n   - Monitoring kesehatan dengan sensor IoT\n\n4. **Teknologi Digital Monitoring**\n   - Gunakan sensor suhu dan kelembaban untuk monitoring 24/7\n   - Aplikasi manajemen peternakan untuk tracking produksi\n   - Analisis data untuk optimasi produksi\n\nDengan menerapkan pendekatan ini, produksi susu dapat meningkat hingga 20-30%.', 'Produksi', 'https://images.unsplash.com/photo-1516467508483-a7212febe31a?w=800&h=600&fit=crop', '2025-11-15', '2025-11-20 12:40:16', '2025-11-20 12:40:16'),
(2, 'Pentingnya Monitoring Suhu di Kandang Sapi', 'Suhu yang optimal sangat penting untuk kesehatan dan produktivitas sapi perah.', 'Suhu kandang merupakan faktor kritis dalam manajemen peternakan sapi perah. Berikut penjelasan lengkapnya:\n\n**Suhu Ideal untuk Sapi Perah:**\n- Suhu optimal: 18-22°C\n- Suhu di bawah 5°C dapat menyebabkan stres dingin\n- Suhu di atas 27°C dapat menyebabkan heat stress yang menurunkan produksi susu\n\n**Dampak Suhu Tidak Optimal:**\n1. **Heat Stress (Stres Panas)**\n   - Penurunan produksi susu hingga 30%\n   - Penurunan kualitas susu\n   - Masalah reproduksi\n   - Penurunan nafsu makan\n\n2. **Cold Stress (Stres Dingin)**\n   - Peningkatan konsumsi pakan untuk menghasilkan panas\n   - Penurunan efisiensi pakan\n   - Risiko penyakit pernapasan\n\n**Solusi dengan Teknologi IoT:**\n- Sensor suhu real-time di berbagai titik kandang\n- Sistem alarm otomatis saat suhu tidak normal\n- Data logging untuk analisis pola suhu\n- Integrasi dengan sistem pendingin/pemanas otomatis\n\n**Tips Praktis:**\n- Pasang sensor di ketinggian 1,5 meter dari lantai\n- Monitor suhu setiap jam\n- Sediakan area teduh dan ventilasi yang baik\n- Gunakan kipas atau sistem pendingin saat suhu tinggi\n\nDengan monitoring suhu yang tepat, kesehatan dan produktivitas sapi dapat terjaga optimal.', 'Kesehatan', 'https://images.unsplash.com/photo-1560493676-04071c5f467b?w=800&h=600&fit=crop', '2025-11-12', '2025-11-20 12:40:16', '2025-11-20 12:40:16'),
(3, 'Manajemen Pakan yang Efektif untuk Sapi Perah', 'Pakan yang tepat dan seimbang adalah kunci sukses peternakan sapi perah modern.', 'Manajemen pakan yang baik merupakan fondasi utama dalam peternakan sapi perah. Berikut panduan lengkapnya:\n\n**Komposisi Pakan Ideal:**\n1. **Hijauan (60-70%)**\n   - Rumput gajah, rumput raja, atau rumput odot\n   - Minimal 3-4 kg per 100 kg berat badan\n   - Pastikan hijauan segar dan tidak layu\n\n2. **Konsentrat (30-40%)**\n   - Dedak, jagung, bungkil kedelai\n   - Disesuaikan dengan produksi susu\n   - Formula: 1 kg konsentrat per 2-3 liter susu\n\n3. **Mineral dan Vitamin**\n   - Kalsium, fosfor, magnesium\n   - Vitamin A, D, E\n   - Garam dapur secukupnya\n\n**Jadwal Pemberian Pakan:**\n- Pagi: 05.00-06.00 (40% hijauan + konsentrat)\n- Siang: 12.00-13.00 (30% hijauan)\n- Sore: 17.00-18.00 (30% hijauan + konsentrat)\n\n**Monitoring dengan Teknologi:**\n- Timbangan digital untuk mengukur pakan yang diberikan\n- Sensor untuk tracking konsumsi\n- Aplikasi untuk analisis efisiensi pakan\n- Alert jika konsumsi tidak normal\n\n**Tips Efisiensi:**\n- Simpan pakan di tempat kering dan terlindung\n- Berikan pakan sedikit demi sedikit untuk mengurangi waste\n- Monitor berat badan sapi secara berkala\n- Sesuaikan pakan dengan fase produksi (laktasi, kering, bunting)\n\nDengan manajemen pakan yang tepat, efisiensi produksi dapat meningkat signifikan.', 'Nutrisi', 'https://images.unsplash.com/photo-1500595046743-cd271d694d30?w=800&h=600&fit=crop', '2025-11-08', '2025-11-20 12:40:16', '2025-11-20 12:40:16'),
(4, 'Teknologi IoT dalam Peternakan Modern', 'Revolusi digital dalam peternakan: bagaimana IoT mengubah cara kita mengelola peternakan sapi perah.', 'Internet of Things (IoT) telah membawa transformasi besar dalam industri peternakan. Berikut manfaat dan implementasinya:\n\n**Aplikasi IoT di Peternakan:**\n\n1. **Sensor Suhu dan Kelembaban**\n   - Monitoring 24/7 kondisi lingkungan kandang\n   - Alert otomatis saat kondisi tidak optimal\n   - Data historis untuk analisis pola\n\n2. **Sensor Pakan**\n   - Tracking konsumsi pakan real-time\n   - Deteksi dini masalah kesehatan\n   - Optimasi efisiensi pakan\n\n3. **Sensor Kesehatan**\n   - Monitoring aktivitas sapi\n   - Deteksi gejala penyakit awal\n   - Tracking siklus reproduksi\n\n4. **Sistem Manajemen Terintegrasi**\n   - Dashboard monitoring real-time\n   - Laporan otomatis\n   - Analisis data untuk pengambilan keputusan\n\n**Keuntungan Implementasi IoT:**\n- Peningkatan efisiensi operasional hingga 25%\n- Pengurangan biaya pakan melalui optimasi\n- Deteksi dini masalah kesehatan\n- Peningkatan produksi susu\n- Penghematan waktu dan tenaga kerja\n\n**Langkah Implementasi:**\n1. Mulai dengan sensor dasar (suhu, kelembaban)\n2. Integrasikan dengan sistem manajemen\n3. Latih tim untuk menggunakan teknologi\n4. Analisis data secara berkala\n5. Skalakan sesuai kebutuhan\n\n**Investasi Awal:**\n- Sensor suhu/kelembaban: Rp 500.000 - 2.000.000\n- Sistem monitoring: Rp 1.000.000 - 5.000.000\n- Maintenance tahunan: 10-15% dari investasi awal\n\nROI biasanya tercapai dalam 12-18 bulan melalui peningkatan efisiensi dan produksi.', 'Teknologi', 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=800&h=600&fit=crop', '2025-11-05', '2025-11-20 12:40:16', '2025-11-20 12:40:16'),
(5, 'Pencegahan dan Penanganan Mastitis pada Sapi Perah', 'Mastitis adalah penyakit yang paling merugikan dalam peternakan sapi perah. Pelajari cara mencegah dan mengatasinya.', 'Mastitis atau radang ambing merupakan penyakit yang paling sering terjadi dan merugikan dalam peternakan sapi perah.\n\n**Gejala Mastitis:**\n- Ambing bengkak, merah, dan panas\n- Susu berubah warna (kuning, merah, atau ada gumpalan)\n- Produksi susu menurun drastis\n- Sapi terlihat sakit dan tidak nafsu makan\n- Demam pada kasus akut\n\n**Penyebab Utama:**\n1. Bakteri (Staphylococcus, Streptococcus, E. coli)\n2. Kebersihan kandang yang buruk\n3. Proses pemerahan yang tidak steril\n4. Luka pada ambing\n5. Stres dan penurunan imunitas\n\n**Pencegahan:**\n1. **Kebersihan Kandang**\n   - Bersihkan kandang minimal 2x sehari\n   - Ganti alas kandang secara berkala\n   - Pastikan drainase berfungsi baik\n\n2. **Proses Pemerahan yang Benar**\n   - Cuci tangan sebelum memerah\n   - Bersihkan ambing dengan air hangat dan desinfektan\n   - Keringkan ambing sebelum memerah\n   - Gunakan peralatan yang steril\n   - Celup puting susu setelah pemerahan (teat dipping)\n\n3. **Manajemen Kesehatan**\n   - Vaksinasi rutin\n   - Pemeriksaan kesehatan berkala\n   - Isolasi sapi yang sakit\n   - Monitoring dengan teknologi IoT\n\n**Penanganan:**\n1. Isolasi sapi yang terinfeksi\n2. Konsultasi dengan dokter hewan\n3. Pemberian antibiotik sesuai resep\n4. Kompres hangat pada ambing\n5. Perah susu lebih sering untuk mengurangi tekanan\n6. Berikan pakan bergizi untuk meningkatkan imunitas\n\n**Dampak Ekonomi:**\n- Penurunan produksi susu 20-50%\n- Kualitas susu menurun (tidak bisa dijual)\n- Biaya pengobatan\n- Risiko penularan ke sapi lain\n\nDengan pencegahan yang tepat, insiden mastitis dapat dikurangi hingga 80%.', 'Kesehatan', 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=800&h=600&fit=crop', '2025-11-02', '2025-11-20 12:40:16', '2025-11-20 12:40:16'),
(6, 'Manajemen Reproduksi Sapi Perah', 'Strategi manajemen reproduksi yang efektif untuk memastikan kontinuitas produksi dan profitabilitas peternakan.', 'Manajemen reproduksi yang baik sangat penting untuk menjaga kontinuitas produksi susu dan profitabilitas peternakan.\r\n\r\n**Siklus Reproduksi Sapi:**\r\n- Siklus estrus: 18-24 hari (rata-rata 21 hari)\r\n- Durasi estrus: 12-18 jam\r\n- Masa bunting: 280-290 hari\r\n- Masa kering: 45-60 hari sebelum melahirkan\r\n\r\n**Tanda-tanda Sapi Siap Kawin:**\r\n1. Vulva bengkak dan kemerahan\r\n2. Keluar lendir bening dari vulva\r\n3. Sapi gelisah dan sering mengaum\r\n4. Nafsu makan menurun\r\n5. Sapi menaiki sapi lain atau membiarkan dinaiki\r\n\r\n**Teknik Inseminasi Buatan (IB):**\r\n1. **Persiapan:**\r\n   - Identifikasi sapi yang siap kawin\r\n   - Catat waktu munculnya tanda estrus\r\n   - Siapkan semen beku dan peralatan IB\r\n\r\n2. **Waktu Inseminasi:**\r\n   - Pagi: IB dilakukan sore hari\r\n   - Sore: IB dilakukan pagi hari berikutnya\r\n   - Prinsip: \"AM-PM rule\"\r\n\r\n3. **Prosedur IB:**\r\n   - Bersihkan area vulva\r\n   - Masukkan pipet IB dengan hati-hati\r\n   - Deposit semen di lokasi yang tepat\r\n   - Catat tanggal dan informasi penting\r\n\r\n**Manajemen Sapi Bunting:**\r\n- Pakan berkualitas tinggi\r\n- Hindari stres\r\n- Monitoring kesehatan berkala\r\n- Siapkan kandang untuk melahirkan\r\n- Masa kering minimal 45 hari\r\n\r\n**Manajemen Sapi Melahirkan:**\r\n- Siapkan kandang khusus (calving pen)\r\n- Monitor proses kelahiran\r\n- Bantu jika diperlukan (dengan bantuan dokter hewan)\r\n- Pastikan anak sapi mendapat kolostrum dalam 2 jam pertama\r\n\r\n**Monitoring dengan Teknologi:**\r\n- Sensor aktivitas untuk deteksi estrus\r\n- Aplikasi untuk tracking siklus reproduksi\r\n- Alert untuk waktu inseminasi optimal\r\n- Database untuk analisis performa reproduksi\r\n\r\n**Target Reproduksi:**\r\n- Service per conception: < 2\r\n- Calving interval: 12-13 bulan\r\n- Conception rate: > 50%\r\n- Pregnancy rate: > 20%\r\n\r\nDengan manajemen reproduksi yang tepat, profitabilitas peternakan dapat meningkat signifikan.', 'Reproduksi', 'https://images.unsplash.com/photo-1516467508483-a7212febe31a?w=800&h=600&fit=crop', '2025-10-29', '2025-11-20 12:40:16', '2025-11-22 12:34:42'),
(7, 'Kualitas Air untuk Sapi Perah', 'Air yang berkualitas adalah kebutuhan vital yang sering terabaikan dalam peternakan sapi perah.', 'Air merupakan nutrisi yang paling penting namun sering terabaikan dalam peternakan sapi perah.\n\n**Kebutuhan Air Sapi Perah:**\n- Sapi laktasi: 80-120 liter per hari\n- Sapi kering: 40-60 liter per hari\n- Sapi bunting: 60-80 liter per hari\n- Anak sapi: 5-10 liter per hari\n\n**Kualitas Air yang Baik:**\n1. **Parameter Fisik:**\n   - Jernih, tidak berwarna\n   - Tidak berbau\n   - Tidak berasa\n   - Suhu: 10-20°C (optimal)\n\n2. **Parameter Kimia:**\n   - pH: 6,5-8,5\n   - Total dissolved solids (TDS): < 1000 ppm\n   - Bebas dari logam berat\n   - Bebas dari kontaminan berbahaya\n\n3. **Parameter Biologis:**\n   - Bebas dari bakteri patogen\n   - Total coliform: < 100/100ml\n   - E. coli: 0/100ml\n\n**Sumber Air yang Baik:**\n- Sumur dalam (lebih baik dari sumur dangkal)\n- Air PDAM (jika memenuhi standar)\n- Air dari mata air alami\n- Hindari air dari sungai yang tercemar\n\n**Manajemen Air:**\n1. **Penyimpanan:**\n   - Tandon air yang tertutup\n   - Bersihkan tandon secara berkala\n   - Lindungi dari kontaminasi\n\n2. **Distribusi:**\n   - Pastikan air tersedia 24 jam\n   - Tempat minum yang mudah dijangkau\n   - Bersihkan tempat minum setiap hari\n   - Cek aliran air secara berkala\n\n3. **Monitoring:**\n   - Uji kualitas air minimal 2x setahun\n   - Monitor konsumsi air harian\n   - Alert jika konsumsi tidak normal\n\n**Dampak Air Berkualitas Buruk:**\n- Penurunan produksi susu\n- Masalah kesehatan\n- Penurunan nafsu makan\n- Masalah reproduksi\n\n**Tips Praktis:**\n- Letakkan tempat minum di lokasi strategis\n- Pastikan air selalu segar dan dingin\n- Tambahkan es batu saat cuaca panas\n- Monitor konsumsi dengan teknologi IoT\n- Bersihkan tempat minum dengan desinfektan\n\nInvestasi dalam kualitas air akan memberikan ROI yang sangat baik melalui peningkatan produksi dan kesehatan sapi.', 'Nutrisi', 'https://images.unsplash.com/photo-1559827260-dc66d52bef19?w=800&h=600&fit=crop', '2025-10-26', '2025-11-20 12:40:16', '2025-11-20 12:40:16'),
(8, 'Manajemen Stres Panas (Heat Stress) pada Sapi Perah', 'Heat stress adalah masalah serius yang dapat menurunkan produksi hingga 30%. Pelajari cara mencegah dan mengatasinya.', 'Heat stress atau stres panas merupakan masalah serius yang sering terjadi di iklim tropis seperti Indonesia.\n\n**Penyebab Heat Stress:**\n- Suhu udara tinggi (> 27°C)\n- Kelembaban tinggi (> 70%)\n- Radiasi matahari langsung\n- Ventilasi yang buruk\n- Kepadatan kandang yang tinggi\n\n**Gejala Heat Stress:**\n1. **Fisiologis:**\n   - Napas cepat dan dangkal (> 60x/menit)\n   - Produksi susu menurun drastis\n   - Nafsu makan menurun\n   - Peningkatan suhu tubuh (> 39°C)\n   - Produksi air liur berlebihan\n\n2. **Perilaku:**\n   - Sapi mencari tempat teduh\n   - Berdiri lebih lama\n   - Mengurangi aktivitas\n   - Minum lebih sering\n\n**Dampak Heat Stress:**\n- Penurunan produksi susu 20-30%\n- Penurunan kualitas susu (lemak dan protein)\n- Masalah reproduksi (penurunan fertilitas)\n- Penurunan imunitas (rentan penyakit)\n- Penurunan berat badan\n\n**Pencegahan dan Penanganan:**\n\n1. **Manajemen Lingkungan:**\n   - Pasang atap yang tinggi (minimal 4 meter)\n   - Ventilasi yang baik (kipas angin)\n   - Sistem pendingin (sprinkler, misting)\n   - Area teduh yang cukup\n   - Cat atap dengan warna terang\n\n2. **Manajemen Pakan:**\n   - Berikan pakan saat pagi dan sore\n   - Tambahkan garam untuk mengganti elektrolit\n   - Tingkatkan konsentrat (lebih mudah dicerna)\n   - Pastikan air minum selalu tersedia dan dingin\n\n3. **Manajemen Waktu:**\n   - Aktivitas berat di pagi/sore hari\n   - Hindari aktivitas di siang hari (10.00-15.00)\n   - Pemerahan di pagi dan sore\n\n4. **Teknologi Monitoring:**\n   - Sensor suhu dan kelembaban real-time\n   - Alert otomatis saat kondisi kritis\n   - Tracking produksi untuk deteksi dini\n   - Analisis data untuk optimasi\n\n**Indeks Suhu-Kelembaban (THI):**\n- THI < 72: Normal\n- THI 72-78: Waspada\n- THI 79-83: Berbahaya\n- THI > 83: Sangat berbahaya\n\n**Rumus THI:**\nTHI = (1.8 × T + 32) - [(0.55 - 0.0055 × RH) × (1.8 × T - 26)]\n\nDimana:\n- T = Suhu (°C)\n- RH = Kelembaban relatif (%)\n\nDengan manajemen yang tepat, dampak heat stress dapat diminimalkan hingga 80%.', 'Kesehatan', 'https://images.unsplash.com/photo-1500595046743-cd271d694d30?w=800&h=600&fit=crop', '2025-10-23', '2025-11-20 12:40:16', '2025-11-20 12:40:16'),
(9, 'Strategi', 'Ini bukan artikel beneran', 'Ya namanya juga bukan artikel beneran pasti ga ada isinya', 'Reproduksi', 'https://www.shutterstock.com/image-photo/portrait-asian-man-holding-mobile-260nw-2522315757.jpg', '2020-12-01', '2025-12-01 05:23:04', '2025-12-01 05:23:58'),
(10, 'Bukan artikel lagi', 'ahaha', 'lah', 'Pakan', 'knowledge/TT10TfbREHICKE4DgN6E2a4R8359RR6QKMPMkFpK.png', '2022-01-12', '2025-12-01 05:31:43', '2025-12-01 05:31:43'),
(11, 'tes', 'Tes artikel', 'Tes tes tes tes pokoknya tes yess yess yess', 'Teknologi', 'knowledge/pOIpQcSz4z6HhbsRXVaP8byRTEdi5yhN7M14crSR.png', '2025-01-12', '2025-12-01 07:07:59', '2025-12-01 07:07:59');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000001_create_cache_table', 1),
(2, '0001_01_01_000002_create_jobs_table', 1),
(3, '2024_01_01_000000_create_users_table', 1),
(4, '2024_01_01_000001_create_products_table', 1),
(5, '2024_01_01_000002_create_transactions_table', 1),
(6, '2024_01_01_000003_create_transaction_items_table', 1),
(7, '2024_01_01_000004_create_sensor_data_table', 1),
(8, '2024_01_01_000005_create_feeding_history_table', 1),
(9, '2025_11_12_133734_create_knowledge_table', 1),
(10, '2025_11_18_113348_create_articles_table', 1),
(11, '2025_11_19_000600_create_temperature_readings_table', 1),
(12, '2025_11_19_000601_create_humidity_readings_table', 1),
(13, '2025_11_19_000602_create_feeding_logs_table', 1),
(14, '2025_11_19_000700_create_cow_weights_table', 1),
(15, '2025_11_19_000800_add_customer_and_payment_fields_to_transactions_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `password_reset_tokens`
--

INSERT INTO `password_reset_tokens` (`email`, `token`, `created_at`) VALUES
('arielramaditya@apps.ipb.ac.id', '$2y$12$D49frbGY4tB6UYy6mSgCsOa9AK8Jut4ODYlcOB73ZvusiFlTFquXy', '2025-12-01 06:41:55'),
('della@gmail.com', '$2y$12$brzJ0Y.ijEx76fUNZfydluAeNbhom2lMG5OF8srvLgYvEGSS6kph2', '2025-12-01 06:33:36');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int NOT NULL DEFAULT '0',
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `category`, `price`, `stock`, `image`, `created_at`, `updated_at`) VALUES
(1, 'Pakan Premium 50kg', 'Pakan berkualitas tinggi untuk sapi perah dengan nutrisi lengkap', 'Pakan', 750000.00, 42, 'https://images.unsplash.com/photo-1563636619-e9143da7973b?w=600&h=600&fit=crop', '2025-11-20 12:40:16', '2025-11-30 16:50:33'),
(2, 'Sensor IoT Suhu', 'Sensor suhu digital dengan konektivitas WiFi untuk monitoring real-time', 'Sensor IoT', 1200000.00, 0, 'https://www.electronicwings.com/storage/PlatformSection/TopicContent/135/description/LM35.jpg', '2025-11-20 12:40:16', '2025-12-01 10:05:34'),
(3, 'Sensor IoT Kelembapan', 'Sensor kelembapan presisi tinggi untuk kontrol lingkungan kandang', 'Sensor IoT', 100.00, 20, 'https://static.wixstatic.com/media/4f2646_cd100e99a99548cdb49c23d96fa05c6b~mv2.png/v1/fill/w_510,h_510,al_c,lg_1,q_85/4f2646_cd100e99a99548cdb49c23d96fa05c6b~mv2.png', '2025-11-20 12:40:16', '2025-12-01 08:55:44'),
(4, 'Vitamin Sapi 1L', 'Suplemen vitamin lengkap untuk meningkatkan kesehatan dan produksi susu', 'Kesehatan', 350000.00, 74, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRfNYoUpzFJyaFACw_JzoWbDbvPLBfU1Nm1zQ&s', '2025-11-20 12:40:16', '2025-11-30 17:59:55'),
(5, 'Timbangan Digital Pakan', 'Timbangan digital presisi untuk menimbang pakan dengan akurat', 'Aksesori', 2500000.00, 15, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRJKMT_82r2XvKvbX_ucYv0hoyl8dfCfi0uVw&s', '2025-11-20 12:40:16', '2025-11-30 18:00:51'),
(6, 'Pakan Konsentrat 25kg', 'Pakan konsentrat berprotein tinggi untuk sapi perah', 'Pakan', 450000.00, 59, 'https://pakanmixfeed.com/wp-content/uploads/2023/03/S18-products.png', '2025-11-20 12:40:16', '2025-11-30 18:01:32'),
(7, 'Mineral Mix 5kg', 'Campuran mineral penting untuk kesehatan tulang dan produksi susu', 'Kesehatan', 180000.00, 100, 'https://id-test-11.slatic.net/p/f484d151d78a4a14db6fdc2972be1f4b.jpg', '2025-11-20 12:40:16', '2025-11-30 18:02:03'),
(8, 'Robot Feeder Otomatis', 'Robot pemberi pakan otomatis dengan timer dan sensor', 'Teknologi', 15000000.00, 5, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSuCtnRerzqILsM7GyQQtNnqoeKpA_NUzOXWA&s', '2025-11-20 12:40:16', '2025-11-30 18:03:01'),
(9, 'Susu Dailymoo Original', 'Susu segar murni dengan rasa original yang creamy dan kaya nutrisi. Diproduksi langsung dari peternakan sapi perah berkualitas tinggi.', 'Minuman', 15000.00, 50, 'https://images.unsplash.com/photo-1563636619-e9143da7973b?w=600&h=600&fit=crop', '2025-11-20 12:40:16', '2025-11-20 12:40:16'),
(10, 'Susu Dailymoo Coklat', 'Nikmati rasa coklat lezat dalam setiap tegukan. Perpaduan sempurna antara susu segar dan coklat premium.', 'Minuman', 16000.00, 45, 'products/cEd6BqeBYCOwQ4YLLwc0NiRDP2oTXunJuatnShF7.png', '2025-11-20 12:40:16', '2025-12-01 05:38:27'),
(11, 'Susu Dailymoo Stroberi', 'Perpaduan susu segar dan rasa stroberi alami yang menyegarkan. Cocok untuk semua kalangan.', 'Minuman', 17000.00, 38, 'https://images.unsplash.com/photo-1572490122747-3968b75cc699?w=600&h=600&fit=crop', '2025-11-20 12:40:16', '2025-12-01 07:39:14'),
(12, 'Keju Dailymoo Premium', 'Keju berkualitas tinggi dengan tekstur lembut dan rasa yang kaya. Produk olahan susu terbaik untuk keluarga.', 'Olahan Susu', 45000.00, 30, 'https://images.unsplash.com/photo-1486297678162-eb2a19b0a32d?w=600&h=600&fit=crop', '2025-11-20 12:40:16', '2025-11-20 12:40:16'),
(13, 'Yogurt Dailymoo Natural', 'Yogurt alami tanpa pemanis buatan, kaya probiotik untuk kesehatan pencernaan. Fresh dan sehat setiap hari.', 'Olahan Susu', 12000.00, 35, 'https://images.unsplash.com/photo-1488477181946-6428a0291777?w=600&h=600&fit=crop', '2025-11-20 12:40:16', '2025-11-20 12:40:16'),
(14, 'Butter Dailymoo', 'Butter premium dengan tekstur lembut dan aroma yang menggugah selera. Perfect untuk masakan dan roti.', 'Olahan Susu', 35000.00, 25, 'https://images.unsplash.com/photo-1588168333986-5078d3ae3976?w=600&h=600&fit=crop', '2025-11-20 12:40:16', '2025-11-20 12:40:16'),
(15, 'Pakan Konsentrat Premium', 'Pakan konsentrat berkualitas tinggi untuk sapi perah. Mengandung nutrisi lengkap untuk produksi susu optimal.', 'Pakan', 25000.00, 100, 'https://images.unsplash.com/photo-1500595046743-cd271d694d30?w=600&h=600&fit=crop', '2025-11-20 12:40:16', '2025-11-20 12:40:16'),
(16, 'Vitamin Sapi Perah', 'Suplemen vitamin lengkap untuk menjaga kesehatan dan produktivitas sapi perah. Formula khusus untuk laktasi.', 'Suplemen', 75000.00, 20, 'https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?w=600&h=600&fit=crop', '2025-11-20 12:40:16', '2025-11-20 12:40:16'),
(17, 'Mineral Block', 'Blok mineral untuk melengkapi kebutuhan mineral sapi perah. Meningkatkan kualitas susu dan kesehatan sapi.', 'Suplemen', 55000.00, 15, 'https://images.unsplash.com/photo-1500595046743-cd271d694d30?w=600&h=600&fit=crop', '2025-11-20 12:40:16', '2025-11-20 12:40:16'),
(18, 'Alat Pemerah Susu Manual', 'Alat pemerah susu manual yang praktis dan higienis. Cocok untuk peternakan kecil hingga menengah.', 'Peralatan', 150000.00, 10, 'https://images.unsplash.com/photo-1570042225831-d98fa7577f2e?w=600&h=600&fit=crop', '2025-11-20 12:40:16', '2025-11-20 12:40:16'),
(19, 'Desinfektan Kandang', 'Desinfektan khusus untuk kebersihan kandang sapi. Membunuh bakteri dan virus, menjaga kesehatan sapi.', 'Peralatan', 45000.00, 40, 'https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?w=600&h=600&fit=crop', '2025-11-20 12:40:16', '2025-11-20 12:40:16'),
(20, 'Tempat Pakan Otomatis', 'Tempat pakan otomatis dengan sistem pengisian otomatis. Praktis dan efisien untuk manajemen pakan.', 'Peralatan', 500000.00, 5, 'https://images.unsplash.com/photo-1560493676-04071c5f467b?w=600&h=600&fit=crop', '2025-11-20 12:40:16', '2025-11-20 12:40:16'),
(21, 'a', 'ini bukan produk beneran', 'Pakan', 2000.00, 0, 'products/pi1dxguiioIi7WcLQVcALLeVnnTDtESu4Fp6y3Mo.png', '2025-12-01 09:54:55', '2025-12-01 10:05:34');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('Dy8cM9im7tcxB1aBISdGuYv4Wao7ThJsSDR4jHfh', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiQ2UySXhFSzZ0QkR6YlBQWFlyRGJpN25JVGp3QkZIckZnWFQ5SGd4dSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9tb25pdG9yaW5nIjtzOjU6InJvdXRlIjtzOjEwOiJtb25pdG9yaW5nIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9', 1764589699),
('Pb0fRP6QRUA7uk5ZiRm4RJwfsp83jWOWFb0zBi4a', 13, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoielJTVXZEOXRxbjRsM3Nha0JXU0YzamNmYnFDdUtPcmVsY3hlc0RTdCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTM7fQ==', 1764585519),
('TdRLkkOK0PTkMhhPC88i2K8fmRSctekLHB8p46ry', 12, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiREI4eXhHNjBIMG1ocUtoam1wVzdhNEoycFprZ1c3ODlhbmhyOEc3VSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTI7fQ==', 1764581196);

-- --------------------------------------------------------

--
-- Table structure for table `temperature_readings`
--

CREATE TABLE `temperature_readings` (
  `id` bigint UNSIGNED NOT NULL,
  `value` double NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'stabil',
  `recorded_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `temperature_readings`
--

INSERT INTO `temperature_readings` (`id`, `value`, `status`, `recorded_at`, `created_at`, `updated_at`) VALUES
(1, 29.7, 'stabil', '2025-11-19 12:40:16', '2025-11-20 12:40:16', '2025-11-20 12:40:16'),
(2, 26.5, 'waspada', '2025-11-19 13:40:16', '2025-11-20 12:40:16', '2025-11-20 12:40:16'),
(3, 28, 'optimal', '2025-11-19 14:40:16', '2025-11-20 12:40:16', '2025-11-20 12:40:16'),
(4, 25.1, 'waspada', '2025-11-19 15:40:16', '2025-11-20 12:40:16', '2025-11-20 12:40:16'),
(5, 25.8, 'stabil', '2025-11-19 16:40:16', '2025-11-20 12:40:16', '2025-11-20 12:40:16'),
(6, 25.4, 'optimal', '2025-11-19 17:40:16', '2025-11-20 12:40:16', '2025-11-20 12:40:16'),
(7, 30, 'waspada', '2025-11-19 18:40:16', '2025-11-20 12:40:16', '2025-11-20 12:40:16'),
(8, 25.1, 'optimal', '2025-11-19 19:40:16', '2025-11-20 12:40:16', '2025-11-20 12:40:16'),
(9, 30.7, 'optimal', '2025-11-19 20:40:16', '2025-11-20 12:40:16', '2025-11-20 12:40:16'),
(10, 28.9, 'optimal', '2025-11-19 21:40:16', '2025-11-20 12:40:16', '2025-11-20 12:40:16'),
(11, 30.9, 'stabil', '2025-11-19 22:40:16', '2025-11-20 12:40:16', '2025-11-20 12:40:16'),
(12, 30.1, 'optimal', '2025-11-19 23:40:16', '2025-11-20 12:40:16', '2025-11-20 12:40:16'),
(13, 24.7, 'waspada', '2025-11-20 00:40:16', '2025-11-20 12:40:16', '2025-11-20 12:40:16'),
(14, 29.1, 'optimal', '2025-11-20 01:40:16', '2025-11-20 12:40:16', '2025-11-20 12:40:16'),
(15, 30.6, 'stabil', '2025-11-20 02:40:16', '2025-11-20 12:40:16', '2025-11-20 12:40:16'),
(16, 28.2, 'stabil', '2025-11-20 03:40:16', '2025-11-20 12:40:16', '2025-11-20 12:40:16'),
(17, 28.2, 'waspada', '2025-11-20 04:40:16', '2025-11-20 12:40:16', '2025-11-20 12:40:16'),
(18, 27.3, 'waspada', '2025-11-20 05:40:16', '2025-11-20 12:40:16', '2025-11-20 12:40:16'),
(19, 26.8, 'waspada', '2025-11-20 06:40:17', '2025-11-20 12:40:17', '2025-11-20 12:40:17'),
(20, 29.2, 'waspada', '2025-11-20 07:40:17', '2025-11-20 12:40:17', '2025-11-20 12:40:17'),
(21, 28.9, 'optimal', '2025-11-20 08:40:17', '2025-11-20 12:40:17', '2025-11-20 12:40:17'),
(22, 25.1, 'optimal', '2025-11-20 09:40:17', '2025-11-20 12:40:17', '2025-11-20 12:40:17'),
(23, 28.9, 'waspada', '2025-11-20 10:40:17', '2025-11-20 12:40:17', '2025-11-20 12:40:17'),
(24, 28, 'stabil', '2025-11-20 11:40:17', '2025-11-20 12:40:17', '2025-11-20 12:40:17'),
(25, 28.1, 'optimal', '2025-11-20 12:40:17', '2025-11-20 12:40:17', '2025-11-20 12:40:17');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `customer_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_address` text COLLATE utf8mb4_unicode_ci,
  `customer_phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_amount` decimal(12,2) NOT NULL,
  `status` enum('pending','pending_payment','payment_verification','processing','shipped','completed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `payment_proof` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_account` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `user_id`, `customer_name`, `customer_address`, `customer_phone`, `total_amount`, `status`, `payment_proof`, `bank_account`, `created_at`, `updated_at`) VALUES
(1, 6, 'pembeli1', 'jl jalalnn', '088888888', 1450000.00, 'processing', 'payment-proofs/3vHd7BDnD4T8VbhRwDEA6Figbecv7ObKwdcJ4UMa.png', 'Bank BCA - 1234567890 - PT DailyMoo Indonesia', '2025-11-21 00:26:34', '2025-11-21 00:27:45'),
(3, 6, 'pembeli1', 'nmnmn', 'bnbnb', 750000.00, 'pending_payment', NULL, 'Bank BCA - 1234567890 - PT DailyMoo Indonesia', '2025-11-22 02:05:20', '2025-11-22 02:05:20'),
(4, 6, 'pembeli1', 's', 's', 1200000.00, 'cancelled', 'payment-proofs/4W1UZ69sHJyRQTpfjtRnvq61H4byWy8MbOy26Nid.png', 'Bank BCA - 1234567890 - PT DailyMoo Indonesia', '2025-11-22 02:08:19', '2025-11-22 02:09:31'),
(5, 6, 'pembeli1', 'q', 'w', 1100000.00, 'processing', 'payment-proofs/cWoZh9Qc9fEuzXyLayhpODXlkDdEV8JczG0H9wja.png', 'Bank BCA - 1234567890 - PT DailyMoo Indonesia', '2025-11-22 02:16:18', '2025-11-22 02:17:21'),
(6, 6, 'pembeliiiiii', 'Jl. jalannn', '088888888888', 750000.00, 'processing', 'payment-proofs/LzKHsIGhadCb9b7K4s0f3yBWfSk10fBChmpPy5E2.png', 'Bank BCA - 1234567890 - PT DailyMoo Indonesia', '2025-11-22 09:29:18', '2025-11-22 09:30:19'),
(8, 6, 'pembeli1', 'jalan', '08888', 2250000.00, 'processing', 'payment-proofs/CwzZN2AcWo6gn7h7ORDQLgl37WRarMaCLMRFuF4P.png', 'Bank BCA - 1234567890 - PT DailyMoo Indonesia', '2025-11-27 13:17:48', '2025-11-27 13:19:23'),
(10, 6, 'pembeli1', 'Jalanan', '08888', 2730000.00, 'processing', 'payment-proofs/iOyfOvBzgwWCMHvCjUtITjQamurCYv6xY71HxWF7.png', 'Bank BCA - 1234567890 - PT DailyMoo Indonesia', '2025-11-30 16:50:33', '2025-11-30 17:00:49'),
(11, 6, 'pembeli1', 'aaa', 'aa', 1130000.00, 'processing', 'payment-proofs/OvlfQlereEvTIqHn3LssrbRaJl187ooPKQln0gbA.png', 'Bank BCA - 1234567890 - PT DailyMoo Indonesia', '2025-11-30 17:16:20', '2025-11-30 17:16:41'),
(12, 6, 'pembeli1', 'jalan', '088', 1230000.00, 'processing', 'payment-proofs/RUK259893Bbi2kQzDqSfjq15fb0U2u4ycS8X4dfF.png', 'Bank BCA - 1234567890 - PT DailyMoo Indonesia', '2025-12-01 03:02:52', '2025-12-01 03:03:41'),
(13, 6, 'pembeli1', 'asasa', 'ssaa', 1130000.00, 'pending_payment', NULL, 'Bank BCA - 1234567890 - PT DailyMoo Indonesia', '2025-12-01 04:55:47', '2025-12-01 04:55:47'),
(14, 6, 'pembeli1', 'm,m', 'n', 1130000.00, 'pending_payment', NULL, 'Bank BCA - 1234567890 - PT DailyMoo Indonesia', '2025-12-01 04:56:17', '2025-12-01 04:56:17'),
(15, 6, 'pembeli1', 'jalana', '088', 1230000.00, 'cancelled', 'payment-proofs/4B8PNLrmfNlYjZYcSyOe7QDXHenGIA2NtXAON5Qb.png', 'Bank BCA - 1234567890 - PT DailyMoo Indonesia', '2025-12-01 04:58:54', '2025-12-01 04:59:51'),
(16, 12, 'n1co', 'rumah rumahan', '12345678', 47000.00, 'processing', 'payment-proofs/PN9A9Ejs21tIXM9tcUWiselybXY1nXKLEkSyBB95.png', 'Bank BCA - 1234567890 - PT DailyMoo Indonesia', '2025-12-01 07:39:14', '2025-12-01 08:02:52'),
(17, 13, 'kratos', 'jo2o2o', '55', 20440000.00, 'processing', 'payment-proofs/qcjTnMNVFepAJ8CZUWSyxee8tSnUK58guW0SmAdJ.png', 'Bank BCA - 1234567890 - PT DailyMoo Indonesia', '2025-12-01 10:05:34', '2025-12-01 10:07:02');

-- --------------------------------------------------------

--
-- Table structure for table `transaction_items`
--

CREATE TABLE `transaction_items` (
  `id` bigint UNSIGNED NOT NULL,
  `transaction_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `quantity` int NOT NULL,
  `price` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transaction_items`
--

INSERT INTO `transaction_items` (`id`, `transaction_id`, `product_id`, `quantity`, `price`, `created_at`, `updated_at`) VALUES
(1, 1, 3, 1, 1100000.00, '2025-11-21 00:26:34', '2025-11-21 00:26:34'),
(2, 1, 4, 1, 350000.00, '2025-11-21 00:26:34', '2025-11-21 00:26:34'),
(5, 3, 1, 1, 750000.00, '2025-11-22 02:05:20', '2025-11-22 02:05:20'),
(6, 4, 2, 1, 1200000.00, '2025-11-22 02:08:19', '2025-11-22 02:08:19'),
(7, 5, 3, 1, 1100000.00, '2025-11-22 02:16:18', '2025-11-22 02:16:18'),
(8, 6, 1, 1, 750000.00, '2025-11-22 09:29:18', '2025-11-22 09:29:18'),
(10, 8, 1, 3, 750000.00, '2025-11-27 13:17:48', '2025-11-27 13:17:48'),
(12, 10, 2, 1, 1200000.00, '2025-11-30 16:50:33', '2025-11-30 16:50:33'),
(13, 10, 1, 2, 750000.00, '2025-11-30 16:50:33', '2025-11-30 16:50:33'),
(14, 11, 3, 1, 1100000.00, '2025-11-30 17:16:20', '2025-11-30 17:16:20'),
(15, 12, 2, 1, 1200000.00, '2025-12-01 03:02:52', '2025-12-01 03:02:52'),
(16, 13, 3, 1, 1100000.00, '2025-12-01 04:55:47', '2025-12-01 04:55:47'),
(17, 14, 3, 1, 1100000.00, '2025-12-01 04:56:17', '2025-12-01 04:56:17'),
(18, 15, 2, 1, 1200000.00, '2025-12-01 04:58:54', '2025-12-01 04:58:54'),
(19, 16, 11, 1, 17000.00, '2025-12-01 07:39:14', '2025-12-01 07:39:14'),
(20, 17, 21, 5, 2000.00, '2025-12-01 10:05:34', '2025-12-01 10:05:34'),
(21, 17, 2, 17, 1200000.00, '2025-12-01 10:05:34', '2025-12-01 10:05:34');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('superadmin','pegawai','pembeli') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pembeli',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `email_verified_at`, `password`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'superadmin', 'superadmin@dailymoo.id', NULL, '$2y$12$7LVQ9L4KVOLWoIGFRorUmeOEd56EHzDDAKJ.bPHIzN59OXX./WIdm', 'superadmin', NULL, '2025-11-20 12:40:14', '2025-11-20 12:40:14'),
(6, 'pembeli1', 'pembeli1@gmail.com', NULL, '$2y$12$88RS0/5jNmvK8Ycy1SYq7uvaoo.4eWMsdzspP.1ZL3/XZpi9LdgZC', 'pembeli', NULL, '2025-11-20 21:41:49', '2025-11-20 21:41:49'),
(7, 'pegawai1', 'pegawai1@gmail.com', NULL, '$2y$12$g1/K5ozrzBwHiITh8Yh1Qukq6YT15B4V74u1/QFaxo0j5Nso5JsFi', 'pegawai', NULL, '2025-11-20 21:42:26', '2025-11-20 21:42:26'),
(10, 'della', 'della@gmail.com', NULL, '$2y$12$6FWgiJsNknt0IOWHbHvlMuMzhmfTcwxWLnbLJl.ZKOxnGshr9GRyS', 'pembeli', 'S1l4mdW9B6OW0Es4FWInGvDoO6jbg8Jaor6NrasbIo8b5aBnALZ0VU4dr8yK', '2025-12-01 06:13:32', '2025-12-01 06:13:32'),
(11, 'ariel', 'arielramaditya@apps.ipb.ac.id', NULL, '$2y$12$CQDj3W5TsVFz0WMvyktQTOGhiUSKAMZZ4ZnNeUysqkdFZwMfZ/V16', 'pembeli', NULL, '2025-12-01 06:34:46', '2025-12-01 06:41:20'),
(12, 'n1co', 'nic@gmail.com', NULL, '$2y$12$HgesK41j4Dr.u9G4BpNOduukYqhBy51SGSUblZ3unCgm/KGAEU0Qy', 'pembeli', NULL, '2025-12-01 06:47:56', '2025-12-01 06:47:56'),
(13, 'kratos', 'kratos@gmail.com', NULL, '$2y$12$bUPaaqtuUJrKIB0lIa5EQuUGndXJ0ayG7qpXMeYVlFIbbWiRuNgKO', 'pembeli', NULL, '2025-12-01 10:03:16', '2025-12-01 10:03:16');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cow_weights`
--
ALTER TABLE `cow_weights`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cow_weights_cow_id_measured_at_index` (`cow_id`,`measured_at`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `feeding_history`
--
ALTER TABLE `feeding_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `feeding_logs`
--
ALTER TABLE `feeding_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `humidity_readings`
--
ALTER TABLE `humidity_readings`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `knowledge`
--
ALTER TABLE `knowledge`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `temperature_readings`
--
ALTER TABLE `temperature_readings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transactions_user_id_foreign` (`user_id`);

--
-- Indexes for table `transaction_items`
--
ALTER TABLE `transaction_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transaction_items_transaction_id_foreign` (`transaction_id`),
  ADD KEY `transaction_items_product_id_foreign` (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `articles`
--
ALTER TABLE `articles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cow_weights`
--
ALTER TABLE `cow_weights`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `feeding_history`
--
ALTER TABLE `feeding_history`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `feeding_logs`
--
ALTER TABLE `feeding_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `humidity_readings`
--
ALTER TABLE `humidity_readings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `knowledge`
--
ALTER TABLE `knowledge`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `temperature_readings`
--
ALTER TABLE `temperature_readings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `transaction_items`
--
ALTER TABLE `transaction_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transaction_items`
--
ALTER TABLE `transaction_items`
  ADD CONSTRAINT `transaction_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transaction_items_transaction_id_foreign` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
