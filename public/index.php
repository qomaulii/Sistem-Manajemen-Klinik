<?php

// 1. Cek versi PHP (CI4 v4.5+ WAJIB PHP 8.1 ke atas)
$minPhpVersion = '8.1';
if (PHP_VERSION_ID < 80100) {
    exit("Versi PHP kamu harus {$minPhpVersion} atau lebih tinggi. Versi saat ini: " . PHP_VERSION);
}

// 2. Tentukan path root aplikasi
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);

// 3. Pastikan kita bekerja di direktori yang benar
if (PHP_SAPI !== 'cli') {
    chdir(FCPATH);
}

// 4. Muat file konfigurasi jalur (Paths)
require realpath(FCPATH . '../app/Config/Paths.php') ?: FCPATH . '../app/Config/Paths.php';

$paths = new Config\Paths();

// 5. LOAD BOOT.PHP (Bukan bootstrap.php lagi!)
require rtrim($paths->systemDirectory, '\\/ ') . DIRECTORY_SEPARATOR . 'boot.php';

// 6. Jalankan aplikasi menggunakan class Boot baru
exit(CodeIgniter\Boot::bootWeb($paths));