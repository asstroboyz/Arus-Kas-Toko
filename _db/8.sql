-- phpMyAdmin SQL Dump
-- version 5.0.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 15 Agu 2024 pada 01.26
-- Versi server: 10.4.14-MariaDB
-- Versi PHP: 7.4.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `toko_hera`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(30) DEFAULT NULL,
  `fullname` varchar(255) DEFAULT NULL,
  `foto` varchar(255) NOT NULL DEFAULT 'profil.svg',
  `password_hash` varchar(255) NOT NULL,
  `reset_hash` varchar(255) DEFAULT NULL,
  `reset_at` datetime DEFAULT NULL,
  `reset_expires` datetime DEFAULT NULL,
  `activate_hash` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `status_message` varchar(255) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `force_pass_reset` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `email`, `username`, `fullname`, `foto`, `password_hash`, `reset_hash`, `reset_at`, `reset_expires`, `activate_hash`, `status`, `status_message`, `active`, `force_pass_reset`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'admin@gmail.com', 'admin', 'Admin', 'AdminFOTOadmin.jpg', '$2y$10$PVlRaK5qKEom7DkgtqdL.ucmoo4EwbD7JPC3zW5s0ghL2D9/V1CQy', NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, '2023-10-02 17:15:56', '2024-04-17 19:56:48', NULL),
(14, 'kasir@gmail.com', 'kasir', NULL, 'KasirFOTOkasir.jpg', '$2y$10$8/oHH9bF5alsUROBEXtrreIBHTgWSB4KrfVjcR8nX3iSsTTJjJ5Lm', NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, '2024-01-09 15:53:39', '2024-06-06 21:12:59', NULL),
(15, 'pemilik@gmail.com', 'pemilik', 'Ganda', 'pegawaiFOTOpegawai1.png', '$2y$10$tBpr.1YSP4J..061PlRORe0IWlXY0WX04x.anXQVg0FHDA5hFEJq2', NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, '2024-01-09 15:56:41', '2024-06-06 21:16:01', NULL),
(16, 'administrator@gmail.com', 'administrator', NULL, 'profil.svg', '$2y$10$fB0zLIk541Tn4UiPE7c84OVeJJrQw43jMR/w00kZlUZxHNyFkO6s2', NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, '2024-02-19 06:36:43', '2024-02-19 06:36:43', NULL);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
