<?php 

include ('../layout/header.php');

if (!isset($_SESSION['login'])) {
    // Jika belum login, redirect
    header('Location: ../../auth/login.php?pesan=belum_login');
    exit();
} elseif ($_SESSION['role'] !== 'mahasiswa' && $_SESSION['role'] !== 'siswa') {
    // Jika role bukan mahasiswa atau siswa, redirect
    header('Location: ../../auth/login.php?pesan=Akun_belum_aktif');
    exit();
}
include_once('../../config.php');

$lokasi_presensi= $_SESSION['lokasi_presensi'];

$result = mysqli_query($koneksi, "SELECT * FROM lokasi_presensi WHERE nama_lokasi='$lokasi_presensi'");

while ($lokasi = mysqli_fetch_array($result)) {
    $nama_lokasi = $lokasi['nama_lokasi'];
    $zona_waktu = $lokasi['zona_waktu'];
    $jam_masuk = $lokasi['jam_masuk'];
    $jam_keluar = $lokasi['jam_keluar'];

}
if ($zona_waktu == 'WIB') {
    date_default_timezone_set('Asia/Jakarta');
} elseif ($zona_waktu == 'WITA') {
    date_default_timezone_set('Asia/Makassar');
} elseif ($zona_waktu == 'WIT') {
    date_default_timezone_set('Asia/Jayapura');
} else {
    date_default_timezone_set('Asia/Jakarta');
}


 

?>
<style>
  .parent_date{
    display: grid;
    grid-template-columns: auto auto auto auto auto;
    font-size: 20px;
    text-align: center;
    justify-content: center;


  }
  .parent_clock{
    display: grid;
    grid-template-columns: auto auto auto auto auto;
    font-size: 30px;
    text-align: center;
    font-weight: bold;
    justify-content: center;
  }
</style>
<!-- Page header -->
<div class="page-header d-print-none">
    <div class="container-xl">
      <div class="row g-2 align-items-center">
        <div class="col">
          
          <h2 class="page-title"><br>
          Home
          </h2>
        </div>
      </div>
    </div>
  </div>
<!-- Page body -->
      <div class="page-body">
          <div class="container-xl">
              <div class="row justify-content-center align-items-center" style="min-block-size: 50vh;">
                  <!-- Presensi Masuk -->
                  <div class="col-md-4">
                      <div class="card text-center h-100">
                          <div class="card-header bg-primary text-white">Presensi Masuk</div>
                          <div class="card-body">
                            <?php 
                             // Periksa apakah pengguna sudah melakukan presensi masuk hari ini
                              $id_users = $_SESSION['id'];
                              $tanggal_hari_ini = date('Y-m-d');
                              $cek_presensi_masuk = mysqli_query($koneksi, "SELECT * FROM presensi_detail WHERE id_users = '$id_users' AND tanggal_masuk = '$tanggal_hari_ini'");
                              ?>

                            <?php if (mysqli_num_rows($cek_presensi_masuk) === 0 ) {?>
                            <div class="parent_date">
                              <div id="tanggal_masuk"></div>
                              <div class="ms-2"></div>
                              <div id="bulan_masuk"></div>
                              <div class="ms-2"></div>
                              <div id="tahun_masuk"></div>
                            </div>
                            <div class="parent_clock">
                              <div id="jam_masuk"></div>
                              <div>:</div>
                              <div id="menit_masuk"></div>
                              <div >:</div>
                              <div id="detik_masuk"></div>
                            </div>
                             
                                
                            
                            <form method="POST" action="<?= base_url('mahasiswa/presensi/presensi_masuk.php')?>">
                                <input type="hidden" name="nama_lokasi" id="nama_lokasi" value="<?= $nama_lokasi?>">
                              <input type="hidden" name="latitude_mahasiswa" id="latitude_mahasiswa">
                              <input type="hidden" name="longitude_mahasiswa" id="longitude_mahasiswa">
                                <input type="hidden" name="latitude" id="latitude" value="<?= $latitude_kantor?>">
                                <input type="hidden" name="longitude" id="longitude" value="<?= $longitude_kantor?>">
                                <input type="hidden" name="radius" id="radius" value="<?= $radius?>">
                                <input type="hidden" name="zona_waktu" id="zona_waktu" value="<?= $zona_waktu?>">
                                <input type="hidden" name="tanggal_masuk" id="tanggal_masuk" value="<?= date('Y-m-d')?>">
                                <input type="hidden" name="jam_masuk" id="jam_masuk" value="<?= date('H:i:s')?>">
                            <button type="submit" name="tombol_masuk" class="btn btn-success mt-3">Masuk</button>
                            </form> 

                           <?php }else{?>

                            <i class="fa-regular fa-circle-check fa-4x text-success "></i>
                            <h4 class="mt-3 text-success">
                                 Anda sudah melakukan<br> presensi masuk
                            </h4>
                            <?php } ?>
                          </div>
                      </div>
                  </div>
                  <!-- Presensi Keluar -->
                  <div class="col-md-4">
                      <div class="card text-center h-100">
                          <div class="card-header bg-primary text-white">Presensi Keluar</div>
                          <div class="card-body">
                              <?php
                              // Ambil data presensi berdasarkan tanggal hari ini
                              $ambil_data_presensi = mysqli_query($koneksi, "SELECT * FROM presensi_detail 
                              WHERE id_users = '$id_users' AND tanggal_masuk = '$tanggal_hari_ini'"); ?>
                              <?php
                              $waktusekatang = date('H:i:s');

                              if (strtotime($waktusekatang) <= strtotime($jam_keluar)) {?>
                                  '<i class="fa-regular fa-circle-xmark fa-4x text-danger"></i>
                                        <h4 class="mt-3">Silakan presensi masuk terlebih dahulu</h4>

                                      
                              <?php } else if (strtotime($waktusekatang) >= strtotime($jam_keluar)&& 
                              mysqli_num_rows($ambil_data_presensi) == 0) { ?>
                                 <i class="fa-regular fa-circle-xmark fa-4x text-danger"></i>
                                        <h4 class="mt-3">Silakan presensi keluar terlebih dahulu</h4>
                               <?php } else {?>


                                <?php while ($cek_presensi_keluar = mysqli_fetch_array($ambil_data_presensi)) { ?>

                                <?php if ($cek_presensi_keluar['tanggal_masuk'] == $tanggal_hari_ini &&
                                    ($cek_presensi_keluar['tanggal_keluar'] == '0000-00-00' || 
                                    is_null($cek_presensi_keluar['tanggal_keluar']))) {?>
                                      
                                      <div class="parent_date">
                                          <div id="tanggal_keluar"></div>
                                          <div class="ms-2"></div>
                                          <div id="bulan_keluar"></div>
                                          <div class="ms-2"></div>
                                          <div id="tahun_keluar"></div>
                                      </div>
                                      <div class="parent_clock">
                                          <div id="jam_keluar"></div>
                                          <div>:</div>
                                          <div id="menit_keluar"></div>
                                          <div>:</div>
                                          <div id="detik_keluar"></div>
                                      </div>
                                      <form method="POST" action="<?= base_url('mahasiswa/presensi/presensi_keluar.php') ?>" id="form_keluar">
                                          <input type="hidden" name="id_users" value="<?= $data_presensi['id_users'] ?>">
                                          <input type="hidden" name="latitude_mahasiswa" id="latitude_mahasiswa">
                                          <input type="hidden" name="longitude_mahasiswa" id="longitude_mahasiswa">
                                          <input type="hidden" name="latitude" id="latitude" value="<?= $latitude_kantor?>">
                                          <input type="hidden" name="longitude" id="longitude" value="<?= $longitude_kantor?>">
                                          <input type="hidden" name="radius" id="radius" value="<?= $radius?>">
                                          <input type="hidden" name="zona_waktu" id="zona_waktu" value="<?= $zona_waktu?>">
                                          <input type="hidden" name="tanggal_keluar" value="<?= date('Y-m-d') ?>">
                                          <input type="hidden" name="jam_keluar" value="<?= date('H:i:s') ?>">
                                          <button type="submit" name="tombol_keluar" class="btn btn-danger mt-3" id="btn_keluar">Keluar</button>
                                      </form>
                                      <?php } else {?>
                                          <i class="fa-regular fa-circle-check fa-4x text-success "></i>
                                          <h4 class="mt-3 text-success">
                                              Anda sudah melakukan<br> presensi 
                                          </h4>
                                     <?php } ?>
                             <?php } ?>
                              <?php } ?>
                          </div>
                      </div>
                  </div>
              </div>
              <div class="col-md-2"></div>
          </div>
      </div>
  </div>
<?php include ('../layout/footer.php'); ?>

<script>
  // Fungsi untuk memperbarui waktu setiap detik
  window.setTimeout("waktu_masuk()", 1000);
  namabulan=["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus",
   "September", "Oktober", "November", "Desember"];
  function waktu_masuk() {
    const waktu = new Date();

    document.getElementById("tanggal_masuk").innerHTML = waktu.getDate();
    document.getElementById("bulan_masuk").innerHTML =  namabulan[waktu.getMonth()]; // Bulan dimulai dari 0
    document.getElementById("tahun_masuk").innerHTML =  waktu.getFullYear();
    document.getElementById("jam_masuk").innerHTML = waktu.getHours();
    document.getElementById("menit_masuk").innerHTML = waktu.getMinutes();
    document.getElementById("detik_masuk").innerHTML = waktu.getSeconds();
  }

  window.setTimeout("waktu_keluar()", 1000);
  namabulan=["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus",
   "September", "Oktober", "November", "Desember"];

  function waktu_keluar() {
    const waktu = new Date();
    setTimeout("waktu_keluar()", 1000);
    document.getElementById("tanggal_keluar").innerHTML = waktu.getDate();
    document.getElementById("bulan_keluar").innerHTML =  namabulan[waktu.getMonth()]; // Bulan dimulai dari 0
    document.getElementById("tahun_keluar").innerHTML =  waktu.getFullYear();
    document.getElementById("jam_keluar").innerHTML = waktu.getHours();
    document.getElementById("menit_keluar").innerHTML = waktu.getMinutes();
    document.getElementById("detik_keluar").innerHTML = waktu.getSeconds();
  }

  // Panggil fungsi waktu_masuk setiap detik
  setInterval(waktu_masuk, 1000);
  setInterval(waktu_keluar, 1000);
</script>
<?php if (isset($_GET['pesan']) && $_GET['pesan'] === 'password_berhasil_diubah'): ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: "success",
                title: "Berhasil!",
                text: "Password berhasil diubah.",
                showConfirmButton: false,
                timer: 1800
            });
        });
    </script>
    <?php endif; ?>
</body>
</html>