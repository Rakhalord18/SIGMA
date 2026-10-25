<?php 
session_start();
require_once('../config.php');

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = trim($_POST['password']);
    
    // Cek email di database dengan JOIN ke tabel mahasiswa_detail
    $cekdatabase = mysqli_query($koneksi, "
        SELECT 
            users.*, 
            mahasiswa_detail.lokasi_presensi,
            mahasiswa_detail.nama AS nama_mahasiswa,
            admin_detail.nama AS nama_admin
        FROM users 
        LEFT JOIN mahasiswa_detail ON users.id = mahasiswa_detail.id_users 
        LEFT JOIN admin_detail ON users.id = admin_detail.id_users
        WHERE users.email = '$email'
    ");
    
    if (mysqli_num_rows($cekdatabase) > 0) {
        $row = mysqli_fetch_assoc($cekdatabase);
    
        // Verifikasi password
        if (password_verify($password, $row['password'])) {
            // Cek status akun
            if ($row['status'] == 'Aktif') {
                // Set sesi login
                $_SESSION['login'] = true;
                $_SESSION['id'] = $row['id'];
                if ($row['role'] === 'admin') {
                    $_SESSION['nama'] = $row['nama_admin'];
                } else {
                    $_SESSION['nama'] = $row['nama_mahasiswa'];
                }
                $_SESSION['role'] = $row['role'];
                $_SESSION['lokasi_presensi'] = $row['lokasi_presensi']; // Ambil lokasi_presensi dari tabel mahasiswa_detail
    
                // Hapus session gagal jika ada
                unset($_SESSION['gagal']);
                
                // Redirect berdasarkan role
                if ($row['role'] === 'admin' || $row['role'] === 'pembimbing') {
                    header("Location: ../admin/home/home.php");
                    exit();
                } elseif ($row['role'] === 'mahasiswa' || $row['role'] === 'siswa') {
                    header("Location: ../mahasiswa/home/home.php");
                    exit();
                } 
            } else {
                $_SESSION['gagal'] = "Akun anda belum aktif";
            }
        } else {
            $_SESSION['gagal'] = "Password salah, silakan coba lagi";
        }
    } else {
        $_SESSION['gagal'] = "Email salah, silakan coba lagi";
    }
}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <title>Login Sistem Informasi Generasi Magang Aktif</title>
    <!-- CSS files -->
    <link href="<?= base_url('assets/css/tabler.min.css?1692870487')?>" rel="stylesheet"/>
    <link href="<?= base_url('assets/css/tabler-vendors.min.css?1692870487')?>" rel="stylesheet"/>
    <link href="<?= base_url('assets/css/demo.min.css?1692870487')?>" rel="stylesheet"/>
    <style>
      @import url('https://rsms.me/inter/inter.css');
      :root {
        --tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
      }
      body {
        font-feature-settings: "cv03", "cv04", "cv11";
        background-color: #d8e1f4;
      }
    </style>
  </head>
  <body  class=" d-flex flex-column">
    <script src="./dist/js/demo-theme.min.js?1692870487"></script>
    <div class="page page-center">
      <div class="container container-normal py-4">
        <div class="row align-items-center g-4">
          <div class="col-lg">
            <div class="container-tight">
              <div class="text-center mb-4">
                <a href="" class="navbar-brand navbar-brand-autodark">
                  <img src="<?= base_url('assets/img/logo_sigma.png')?>" height="50" alt="">
                  Sistem Informasi Generasi Magang Aktif
                </a>
              </div>

              <div class="card card-md">
                <div class="card-body">
                  <h2 class="h2 text-center mb-4">Login to your account</h2>
                  <form action="" method="POST" autocomplete="off" novalidate>
                    <div class="mb-3">
                      <label class="form-label">Email</label>
                      <input type="email" class="form-control" placeholder=" Masukan Email anda " name="email" autocomplete="off" required>
                    </div>
                    <div class="mb-2">
                      <label class="form-label">Password</label>
                      <div class="input-group input-group-flat">
                        <input type="password" id="passwordInput" class="form-control" placeholder="Password" name="password" autocomplete="off" required>
                        <span class="input-group-text">
                          <a href="#" id="togglePassword" class="link-secondary" title="Show password" data-bs-toggle="tooltip" onclick="return false;">
                            <svg xmlns="http://www.w3.org/2000/svg" id="iconEye" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                              <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                              <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                              <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                            </svg>
                          </a>
                        </span>
                      </div>
                    </div>
                    <div class="form-footer">
                      <button type="submit" name="login" class="btn btn-primary w-100">Sign in</button>
                    </div>
                  </form>
                </div>
              </div>              
            </div>
          </div>
          <div class="col-lg d-none d-lg-block">
            <!-- Video animation replacing image -->
            <video width="100%" height="250" autoplay muted loop playsinline id="loginVideo" style="margin-top: 75px">
              <source src="<?= base_url('assets/video/AnimasiSigapICT.mp4') ?>" type="video/mp4" />
              Your browser does not support the video tag.
            </video>
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
    <!-- Sweetalert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <?php if(!empty($_SESSION['gagal'])){ ?>
    <script>
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "<?= $_SESSION['gagal']; ?>"
      });
    </script>
    <?php unset($_SESSION['gagal']); } ?>

    <!-- Toggle Password Script -->
    <script>
      document.getElementById('togglePassword').addEventListener('click', function () {
        const passwordInput = document.getElementById('passwordInput');
        const iconEye = document.getElementById('iconEye');
        if (passwordInput.type === 'password') {
          passwordInput.type = 'text';
          iconEye.innerHTML = `
            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
            <path d="M3 3l18 18" />
            <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
            <path d="M17.94 17.94a9 9 0 0 1 -11.88 0" />
          `;
        } else {
          passwordInput.type = 'password';
          iconEye.innerHTML = `
            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
            <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
            <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
          `;
        }
      });
    </script>
  </body>
</html>
