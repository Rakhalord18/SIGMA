<?php 
  ob_start();
  session_start();
  require_once('../../config.php');

  if (!isset($_SESSION['login'])) {
    header('Location: ../../auth/login.php?pesan=belum_login');
    exit();
  } elseif ($_SESSION['role'] !== 'mahasiswa' && $_SESSION['role'] !== 'siswa') {
    header('Location: ../../auth/login.php?pesan=Akun_belum_aktif');
    exit();
  }

  // Ambil data mahasiswa (foto, nama, role)
  $id = $_SESSION['id'];
  $query = "SELECT d.nama, d.foto, u.role FROM mahasiswa_detail d JOIN users u ON d.id_users = u.id WHERE d.id_users = '$id'";
  $result = mysqli_query($koneksi, $query);
  $data = mysqli_fetch_assoc($result);

  $nama = $data['nama'] ?? $_SESSION['nama'];
  $role = $data['role'] ?? $_SESSION['role'];
  $foto = !empty($data['foto']) ? base_url('uploads/foto_mahasiswa/' . $data['foto']) : base_url('assets/img/default.png');
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
  <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
  <title>Sistem Informasi Generasi Magang Aktif</title>
  <link href="<?= base_url('assets/css/tabler.min.css?1692870487')?>" rel="stylesheet"/>
  <link href="<?= base_url('assets/css/tabler-vendors.min.css?1692870487')?>" rel="stylesheet"/>
  <link href="<?= base_url('assets/css/demo.min.css?1692870487')?>" rel="stylesheet"/>
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
      background-color: red;
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
      background-color: red;
      text-decoration: none;
      color: #333;
      border-radius: 8px;   
      transition: background-color 0.3s, color 0.3s;
      color: #fff;
   }

   .navbar-nav .nav-link {
    padding: 8px 12px;
    font-weight: 500;
  }
  .navbar-nav .dropdown-menu {
    margin-top: 0.5rem;
  }
  .navbar-nav.ms-md-auto {
    margin-left: auto;
    margin-right: 2rem;
  }
  </style>
</head>
<body>
  <script src="./dist/js/demo-theme.min.js?1692870487"></script>
  <div class="page">
    <!-- Navbar -->
   <!-- Navbar Gabungan -->
<header class="navbar navbar-expand-md d-print-none">
  <div class="container-xl d-flex justify-content-between align-items-center w-100">

    <!-- Toggler Button for Mobile -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu" aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Logo -->
    <h1 class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3 m-0">
      <a href=".">
        <img src="<?= base_url('assets/img/logo_sigma.png') ?>" width="120" height="30" alt="Presensi" class="navbar-brand-image">
      </a>
    </h1>

    <!-- Navigation Menu -->
    <div class="collapse navbar-collapse" id="navbar-menu">
      <ul class="navbar-nav ms-auto me-4 gap-2">
        <li class="nav-item">
          <a class="nav-link" href="../home/home.php">
            <span class="nav-link-icon d-md-none d-lg-inline-block">
              <!-- Home Icon -->
              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M5 12l-2 0l9 -9l9 9l-2 0" />
                <path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" />
                <path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" />
              </svg>
            </span>
            <span class="nav-link-title">Home</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="../presensi/rekap_presensi.php">
            <span class="nav-link-icon d-md-none d-lg-inline-block">
              <!-- Presensi Icon -->
              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" />
                <path d="M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" />
                <path d="M9 14l2 2l4 -4" />
              </svg>
            </span>
            <span class="nav-link-title">Rekap Presensi</span>
          </a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" role="button" aria-expanded="false">
            <span class="nav-link-icon d-md-none d-lg-inline-block">
              <!-- Kegiatan Icon -->
              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
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
        <li class="nav-item">
          <a class="nav-link" href="../ketidakhadiran/ketidakhadiran.php">
            <span class="nav-link-icon d-md-none d-lg-inline-block">
              <!-- Ketidakhadiran Icon -->
              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" />
                <path d="M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" />
                <path d="M10 12l4 4m0 -4l-4 4" />
              </svg>
            </span>
            <span class="nav-link-title">Ketidakhadiran</span>
          </a>
        </li>
      </ul>
    </div>

    <!-- Profile -->
    <div class="navbar-nav flex-row order-md-last">
      <div class="nav-item dropdown">
        <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown" aria-label="Open user menu">
          <span class="avatar avatar-sm" style="background-image: url('<?= $foto ?>'); border-radius: 50%; width: 40px; height: 40px;"></span>
          <div class="d-none d-xl-block ps-2">
            <div><?= htmlspecialchars($nama) ?></div>
            <div class="mt-1 small text-secondary"><?= htmlspecialchars($role) ?></div>
          </div>
        </a>
        <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
          <a href="<?= base_url('mahasiswa/fitur/profile.php') ?>" class="dropdown-item">Profile</a>
          <a href="<?= base_url('mahasiswa/fitur/edit_password.php?id=' . $_SESSION['id']) ?>" class="dropdown-item">Ubah Password</a>
          <a href="<?= base_url('auth/logout.php') ?>" class="dropdown-item">Logout</a>
        </div>
      </div>
    </div>
  </div>
</header>

<!-- Tambahkan ini sebelum penutup </body> -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-whatever" crossorigin="anonymous"></script>

