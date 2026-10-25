<?php
include('../../config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_users = $_POST['id_users'];
    $password_baru = $_POST['password_baru'];
    $konfirmasi_password = $_POST['konfirmasi_password'];

    // Validasi apakah password dan konfirmasi password cocok
    if ($password_baru !== $konfirmasi_password) {
        header('Location: edit_password.php?id=' . $id_users . '&pesan=password_tidak_sama');
        exit;
    }

    // Hash password baru
    $password_hashed = password_hash($password_baru, PASSWORD_DEFAULT);

    // Update password di database
    $query = "UPDATE users SET password = '$password_hashed' WHERE id = '$id_users'";
    mysqli_query($koneksi, $query);

    header('Location: ../home/home.php?id=' . $id_users . '&pesan=password_berhasil_diubah');
    exit;
}

$id = $_GET['id'];
$result = mysqli_query($koneksi, "SELECT id FROM users WHERE id = '$id'");
$user = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <title>Edit Password</title>
    <!-- CSS files -->
    <link href="<?= base_url('assets/css/tabler.min.css?1692870487') ?>" rel="stylesheet"/>
    <link href="<?= base_url('assets/css/tabler-vendors.min.css?1692870487') ?>" rel="stylesheet"/>
    <link href="<?= base_url('assets/css/demo.min.css?1692870487') ?>" rel="stylesheet"/>
    <style>
        @import url('https://rsms.me/inter/inter.css');
        :root {
            --tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
        }
        body {
            font-feature-settings: "cv03", "cv04", "cv11";
        }
    </style>
</head>
<body class="d-flex flex-column">
    <script src="./dist/js/demo-theme.min.js?1692870487"></script>
    <div class="page page-center">
        <div class="container container-normal py-4">
            <div class="row align-items-center g-4">
                <div class="col-lg">
                    <div class="container-tight">
                        <div class="text-center mb-4">
                            <a href="." class="navbar-brand navbar-brand-autodark">
                                <img src="<?= base_url('assets/img/logomagang.png') ?>" height="50" alt="">
                                Presensi dan Kegiatan Magang
                            </a>
                        </div>
                        <div class="card card-md">
                            <div class="card-body">
                                <h2 class="h2 text-center mb-4">Ganti Password</h2>
                                <?php if (isset($_GET['pesan']) && $_GET['pesan'] === 'password_tidak_sama'): ?>
                                    <div class="alert alert-danger">Password dan Konfirmasi Password tidak sama!</div>
                                <?php endif; ?>
                                <form action="edit_password.php" method="POST">
                                    <input type="hidden" name="id_users" value="<?= $user['id'] ?>">
                                    <div class="mb-3">
                                        <label for="password_baru" class="form-label">Password Baru</label>
                                        <input type="password" class="form-control" id="password_baru" name="password_baru" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="konfirmasi_password" class="form-label">Konfirmasi Password</label>
                                        <input type="password" class="form-control" id="konfirmasi_password" name="konfirmasi_password" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100">Simpan</button>
                                    <a href="javascript:history.back()" class="btn btn-secondary w-100 mt-2">Batal</a>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg d-none d-lg-block">
                    <img src="<?= base_url('assets/img/undraw_secure_login_pdn4.svg') ?>" height="300" class="d-block mx-auto" alt="">
                </div>
            </div>
        </div>
    </div>
    <!-- Libs JS -->
    <script src="<?= base_url('assets/libs/apexcharts/dist/apexcharts.min.js?1692870487')?>" defer></script>
    <script src="<?= base_url('assets/libs/jsvectormap/dist/js/jsvectormap.min.js?1692870487')?>" defer></script>
    <script src="<?= base_url('assets/libs/jsvectormap/dist/maps/world.js?1692870487')?>" defer></script>
    <script src="<?= base_url('assets/libs/jsvectormap/dist/maps/world-merc.js?1692870487')?>" defer></script>
    <!-- Tabler Core -->
    <script src="<?= base_url('assets/js/tabler.min.js?1692870487')?>" defer></script>
    <script src="<?= base_url('assets/js/demo.min.js?1692870487')?>" defer></script>
    <?php if (isset($_GET['pesan']) && $_GET['pesan'] === 'password_tidak_sama'): ?>
    <div class="alert alert-danger">Password dan Konfirmasi Password tidak sama!</div>
    <?php endif; ?>
</body>
</html>