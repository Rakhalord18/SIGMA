<?php 

session_start();
ob_start(); // mulai penampungan output
    require_once('../../config.php');

    // Ambil data foto admin
$id_admin = $_SESSION['id'];
$query = "SELECT d.foto FROM admin_detail d WHERE d.id_users = '$id_admin'";
$result = mysqli_query($koneksi, $query);
$admin = mysqli_fetch_assoc($result);
$foto = !empty($admin['foto']) ? base_url('uploads/foto_admin/' . $admin['foto']) : base_url('assets/img/default.png');
    
?>
<!doctype html>
<!--
* Tabler - Premium and Open Source dashboard template with responsive and high quality UI.
* @version 1.0.0-beta20
* @link https://tabler.io
* Copyright 2018-2023 The Tabler Authors
* Copyright 2018-2023 codecalm.net Paweł Kuna
* Licensed under MIT (https://github.com/tabler/tabler/blob/master/LICENSE)
-->
<html lang="en">
  <head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <title>Dashboard Admin Sistem Informasi Generasi Magang Aktif</title>
    <!-- CSS files -->
    <link href="<?= base_url('assets/css/tabler.min.css?1692870487')?>" rel="stylesheet"/>
    <link href="<?= base_url('assets/css/tabler-vendors.min.css?1692870487')?>" rel="stylesheet"/>
    <link href="<?= base_url('assets/css/demo.min.css?1692870487')?>" rel="stylesheet"/>
    <!-- fontawesome-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
      @import url('https://rsms.me/inter/inter.css');
      :root {
      	--tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
      }
      body {
      	font-feature-settings: "cv03", "cv04", "cv11";
      }

      .nav-item {
      list-style: none;
    }

    .nav-link {
      display: flex;
      align-items: center;
      padding: 10px 15px;
      text-decoration: none;
      color: #333;
      border-radius: 8px;
      transition: background-color 0.3s, color 0.3s;
    }

    .nav-link-icon {
      margin-right: 8px;
    }

    .nav-link:hover {
      background-color: green;
      color: #fff;
    }

    .nav-link:hover .icon {
      stroke: #fff;
    }

    .nav-link-title {
      font-size: 16px;
      font-weight: 500;
    }

    .icon {
      transition: stroke 0.3s;
    }

    .avatar {
      object-fit: cover;
    }
    .dropdown-item:hover {
      background-color: green;
      text-decoration: none;
      color: #333;
      border-radius: 8px;   
      transition: background-color 0.3s, color 0.3s;
      color: #fff;
   }
    </style>
  </head>
  <body >
    <script src="./dist/js/demo-theme.min.js?1692870487"></script>
    <div class="page">
      <!-- Navbar -->
<header class="navbar navbar-expand-md navbar-light d-print-none">
  <div class="container-xl">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu" aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <a href="#" class="navbar-brand d-none d-md-flex ms-7">
      <img src="<?= base_url('assets/img/logo_sigma.png') ?>" width="130" height="37" alt="Presensi" class="navbar-brand-image me-2">
    </a>
  </div>

  <!-- Menu Collapse -->
  <div class="collapse navbar-collapse" id="navbar-menu">
      <div class="container-xl">
        <ul class="navbar-nav">
          <!-- Home -->
          <li class="nav-item">
            <a class="nav-link" href="../home/home.php">
              <span class="nav-link-icon d-md-none d-lg-inline-block">
                <!-- Home Icon -->
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                  <path d="M5 12l-2 0l9 -9l9 9l-2 0" />
                  <path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" />
                  <path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" />
                </svg>
              </span>
              <span class="nav-link-title">Home</span>
            </a>
          </li>

          <!-- Dropdown: Master Data -->
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" role="button" aria-expanded="false">
              <span class="nav-link-icon d-md-none d-lg-inline-block">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-database-edit" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                  <path d="M4 6c0 1.657 3.582 3 8 3s8 -1.343 8 -3s-3.582 -3 -8 -3s-8 1.343 -8 3" />
                  <path d="M4 6v6c0 1.657 3.582 3 8 3c.478 0 .947 -.016 1.402 -.046" />
                  <path d="M20 12v-6" />
                  <path d="M4 12v6c0 1.526 3.04 2.786 6.972 2.975" />
                  <path d="M18.42 15.61a2.1 2.1 0 0 1 2.97 2.97l-3.39 3.42h-3v-3l3.42 -3.39z" />
                </svg>
              </span>
              <span class="nav-link-title">Master Data</span>
            </a>
            <div class="dropdown-menu">
              <a class="dropdown-item" href="../data_pegawai/pegawai.php">Data Pegawai</a>
              <a class="dropdown-item" href="../data_mahasiswa/mahasiswa_siswa.php">Data Mahasiswa/Siswa</a>
              <a class="dropdown-item" href="../data_lokasi_presensi/lokasi_presensi.php">Data Presensi</a>
            </div>
          </li>

          <!-- Dropdown: Master Presensi -->
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" role="button" aria-expanded="false">
              <span class="nav-link-icon d-md-none d-lg-inline-block">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-clipboard-check" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                  <path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" />
                  <path d="M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" />
                  <path d="M9 14l2 2l4 -4" />
                </svg>
              </span>
              <span class="nav-link-title">Master Presensi</span>
            </a>
            <div class="dropdown-menu">
              <a class="dropdown-item" href="../presensi/rekap_harian.php">Rekap Presensi Harian</a>
              <a class="dropdown-item" href="../presensi/rekap_Bulanan.php">Rekap Presensi Bulanan</a>
            </div>
          </li>

          <!-- Dropdown: Master Kegiatan -->
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" role="button" aria-expanded="false">
              <span class="nav-link-icon d-md-none d-lg-inline-block">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-checklist" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                  <path d="M9.615 20h-2.615a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h8a2 2 0 0 1 2 2v8" />
                  <path d="M14 19l2 2l4 -4" />
                  <path d="M9 8h4" />
                  <path d="M9 12h2" />
                </svg>
              </span>
              <span class="nav-link-title">Master Kegiatan</span>
            </a>
            <div class="dropdown-menu">
              <a class="dropdown-item" href="../kegiatan_harian/kegiatan_harian.php">Data Kegiatan Harian</a>
              <a class="dropdown-item" href="../kegiatan_harian/laporan_akhir.php">Data Laporan Akhir</a>
            </div>
          </li>

          <!-- Ketidakhadiran -->
          <li class="nav-item">
            <a class="nav-link" href="../ketidakhadiran/ketidakhadiran.php">
              <span class="nav-link-icon d-md-none d-lg-inline-block">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-clipboard-x" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                  <path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" />
                  <path d="M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" />
                  <path d="M10 12l4 4m0 -4l-4 4" />
                </svg>
              </span>
              <span class="nav-link-title">Ketidakhadiran</span>
            </a>
          </li>
          <div class="navbar-nav flex-row order-md-last ms-md-4 me-8  ">
      <div class="nav-item dropdown">
        <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown" aria-label="Open user menu">
        <span class="avatar avatar-sm" style="background-image: url('<?= $foto ?>'); border-radius: 50%; width: 40px; height: 40px;"></span>
          <div class="d-none d-xl-block ps-2">
            <div><?= $_SESSION['nama'] ?></div>
            <div class="mt-1 small text-secondary"><?= $_SESSION['role'] ?></div>
          </div>
        </a>
        <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
          <a href="<?= base_url('admin/fitur/profile.php') ?>" class="dropdown-item">Profile</a>
          <a href="<?= base_url('admin/data_pegawai/edit_password.php?id=' . $_SESSION['id']) ?>" class="dropdown-item">Ubah Password</a>
          <a href="<?= base_url('auth/logout.php') ?>" class="dropdown-item">Logout</a>
        </div>
      </div>
    </div>
        </ul>     
      </div>
  </div>
</header>

      