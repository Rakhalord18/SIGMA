<footer class="footer footer-transparent d-print-none">
          <div class="container-xl">
            <div class="row text-center align-items-center flex-row-reverse">
              
              <div class="col-12 col-lg-auto mt-3 mt-lg-0">
                <ul class="list-inline list-inline-dots mb-0">
                  <li class="list-inline-item">
                    Sistem Informasi Generasi Magang Aktif
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </footer>
      </div>
    </div>
<!-- Modal Tambah Data Ketidakhadiran -->
<div class="modal fade" id="modalTambahDataketidakhadiran" tabindex="-1" aria-labelledby="modalTambahDataLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="tambah_ketidakhadiran.php" method="POST" enctype="multipart/form-data">
                  <input type="hidden" value="<?= $_SESSION['id']?>" name="id_users">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTambahDataLabel">Tambah Data Pengajuan Ketidakhadiran</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Form Input -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tanggal" class="form-label">Tanggal</label>
                                    <input type="date" class="form-control" id="tanggal" name="tanggal" required>
                                </div>
                                <div class="mb-3">
                                    <label for="keterangan" class="form-label">Keterangan</label>
                                    <select class="form-select" id="keterangan" name="keterangan" required>
                                    <option value="">--- Pilih Keterangan ---</option>
                                    <option value="Izin">Izin</option>
                                    <option value="Sakit">Sakit</option>
                                </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="deskripsi" class="form-label">Deskripsi</label>
                                    <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="file" class="form-label">File Bukti (PDF/JPG/PNG, max 2MB)</label>
                                    <input type="file" class="form-control" id="file" name="file" accept=".pdf,.jpg,.jpeg,.png" optional>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
</div>

   <!-- Modal Tambah Kegiatan Harian -->
<div class="modal fade" id="modalTambahKegiatanHarian" tabindex="-1" aria-labelledby="modalTambahKegiatanLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="tambah_kegiatan.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" value="<?= $_SESSION['id']?>" name="id_users">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahKegiatanLabel">Tambah Kegiatan Harian Magang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Form Input -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tanggal" class="form-label">Tanggal</label>
                                <input type="date" class="form-control" id="modal_tanggal" name="tanggal" required
                                    min="<?= $tanggal_mulai ?>" max="<?= $tanggal_selesai ?>">
                            </div>
                            <div class="mb-3">
                                <label for="hari" class="form-label">Hari</label>
                                <input type="text" class="form-control" id="modal_hari" name="hari" readonly required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="deskripsi" class="form-label">Deskripsi Kegiatan</label>
                                <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="file" class="form-label">Upload File (PDF/JPG/PNG, max 2MB)</label>
                                <input type="file" class="form-control" id="file" name="file" accept=".pdf,.jpg,.jpeg,.png">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Tambah Data Laporan Akhir -->
<div class="modal fade" id="modalTambahLaporanAkhir" tabindex="-1" aria-labelledby="modalTambahLaporanAkhirLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="tambah_laporan.php" method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahLaporanAkhirLabel">Upload Laporan Akhir</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Form Input -->
                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <input type="text" class="form-control" id="keterangan" name="keterangan" placeholder="Contoh: Laporan Akhir Magang" required>
                    </div>
                    <div class="mb-3">
                        <label for="file" class="form-label">File Laporan (ZIP, max 100MB)</label>
                        <input type="file" class="form-control" id="file" name="file" accept=".zip" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>

    <!-- Libs JS -->
    <script src="<?= base_url('assets/libs/apexcharts/dist/apexcharts.min.js?1692870487')?>" defer></script>
    <script src="<?= base_url('assets/libs/jsvectormap/dist/js/jsvectormap.min.js?1692870487')?>" defer></script>
    <script src="<?= base_url('assets/libs/jsvectormap/dist/maps/world.js?1692870487')?>" defer></script>
    <script src="<?= base_url('assets/libs/jsvectormap/dist/maps/world-merc.js?1692870487')?>" defer></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <!-- Tabler Core -->
    <script src="<?= base_url('assets/js/tabler.min.js?1692870487')?>" defer></script>
    <script src="<?= base_url('assets/js/demo.min.js?1692870487')?>" defer></script>
    
    <?php if (isset($_SESSION['gagal'])): ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: '<?= $_SESSION['gagal'] ?>',
        });
    </script>
    <?php unset($_SESSION['gagal']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['sukses'])): ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: '<?= $_SESSION['sukses'] ?>',
        });
    </script>
    <?php unset($_SESSION['sukses']); ?>
    <?php endif; ?>
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const btnKeluar = document.getElementById("btn_keluar");
        const btnKeluarDisabled = document.getElementById("btn_keluar_disabled");
        const formKeluar = document.getElementById("form_keluar");

        // Jika tombol keluar diklik tanpa presensi masuk
        if (btnKeluarDisabled) {
            btnKeluarDisabled.addEventListener("click", function () {
                Swal.fire({
                    icon: "error",
                    title: "Gagal",
                    text: "Anda belum melakukan presensi masuk",
                });
            });
        }

        // Jika tombol keluar diklik setelah presensi masuk
        if (btnKeluar) {
            btnKeluar.addEventListener("click", function () {
                Swal.fire({
                    icon: "warning",
                    title: "Konfirmasi",
                    text: "Apakah Anda yakin ingin melakukan presensi keluar?",
                    showCancelButton: true,
                    confirmButtonText: "Ya, Keluar",
                    cancelButtonText: "Batal",
                }).then((result) => {
                    if (result.isConfirmed) {
                        formKeluar.submit();
                    }
                });
            });
        }
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const btnKeluarDisabled = document.getElementById("btn_keluar_disabled");

        // Jika tombol keluar diklik tanpa presensi masuk
        if (btnKeluarDisabled) {
            btnKeluarDisabled.addEventListener("click", function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Anda belum melakukan Presensi Masuk!!',
                });
            });
        }
    });
</script><?php if (isset($_SESSION['sukses'])): ?>
<script>
Swal.fire({
  icon: 'success',
  title: 'Berhasil!',
  text: '<?= $_SESSION["sukses"]; ?>'
});
</script>
<?php unset($_SESSION['sukses']); endif; ?>

<?php if (isset($_SESSION['gagal'])): ?>
<script>
Swal.fire({
  icon: 'error',
  title: 'Gagal!',
  text: '<?= $_SESSION["gagal"]; ?>'
});
</script>
<?php unset($_SESSION['gagal']); endif; ?>


<script>
document.getElementById('modal_tanggal').addEventListener('change', function() {
    const tanggal = this.value;
    if (tanggal) {
        const hariList = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
        const d = new Date(tanggal);
        document.getElementById('modal_hari').value = hariList[d.getDay()];
    } else {
        document.getElementById('modal_hari').value = '';
    }
});
</script>
  </body>

  <?php ob_end_flush(); ?>
</html>