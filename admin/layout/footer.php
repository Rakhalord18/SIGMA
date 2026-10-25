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

<!-- Modal Tambah Data Pegawai -->
<div class="modal fade" id="modalTambahDatapegawai" tabindex="-1" aria-labelledby="modalTambahDataPegawaiLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="tambah.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahDataPegawaiLabel">Tambah Data Pegawai</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Form Input -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama</label>
                                <input type="text" class="form-control" id="nama" name="nama" required>
                            </div>
                            <div class="mb-3">
                                <label for="nip" class="form-label">NIP</label>
                                <input type="text" class="form-control" id="nip" name="nip" required>
                            </div>
                            <div class="mb-3">
                                <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                                <select class="form-select" id="jenis_kelamin" name="jenis_kelamin" required>
                                    <option value="Laki-laki">Laki-laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="jabatan" class="form-label">Jabatan</label>
                                <input type="text" class="form-control" id="jabatan" name="jabatan" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="notelpon" class="form-label">No Telepon</label>
                                <input type="text" class="form-control" id="notelpon" name="notelpon" required>
                            </div>
                            <!-- Role dan Status otomatis -->
                            <input type="hidden" name="role" value="admin">
                            <input type="hidden" name="status" value="Aktif">
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

<!-- Modal Tambah Data Mahasiswa/Siswa-->
<div class="modal fade" id="modalTambahData" tabindex="-1" aria-labelledby="modalTambahDataLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="tambah_data.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahDataLabel">Tambah Data Mahasiswa/Siswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Form Input -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama</label>
                                <input type="text" class="form-control" id="nama" name="nama" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="nis_nim" class="form-label">NIS/NIM</label>
                                <input type="text" class="form-control" id="nis_nim" name="nis_nim" required>
                            </div>
                            <div class="mb-3">
                                <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                                <select class="form-select" id="jenis_kelamin" name="jenis_kelamin" required>
                                    <option value="Laki-laki">Laki-laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="jurusan" class="form-label">Jurusan</label>
                                <input type="text" class="form-control" id="jurusan" name="jurusan" required>
                            </div>
                            <div class="mb-3">
                                <label for="asal_instansi" class="form-label">Asal Instansi</label>
                                <input type="text" class="form-control" id="asal_instansi" name="asal_instansi" required>
                            </div>
                            
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="alamat" class="form-label">Alamat</label>
                                <textarea class="form-control" id="alamat" name="alamat" rows="3" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="notelpon" class="form-label">No Telepon</label>
                                <input type="text" class="form-control" id="notelpon" name="notelpon" required>
                            </div>
                            <div class="mb-3">
                                <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                                <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai" required>
                            </div>
                            <div class="mb-3">
                                <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
                                <input type="date" class="form-control" id="tanggal_selesai" name="tanggal_selesai" required>
                            </div>
                            <div class="mb-3">
                                <label for="lokasi_presensi" class="form-label">Lokasi Presensi</label>
                                <select class="form-select" id="lokasi_presensi" name="lokasi_presensi" required>
                                    <option value="">Pilih Lokasi Presensi</option>
                                    <?php
                                    $query_lokasi = "SELECT id, nama_lokasi FROM lokasi_presensi";
                                    $result_lokasi = mysqli_query($koneksi, $query_lokasi);
                                    while ($lokasi = mysqli_fetch_assoc($result_lokasi)) {
                                        $selected = ($data['lokasi_presensi'] === $lokasi['nama_lokasi']) ? 'selected' : '';
                                        echo "<option value='" . htmlspecialchars($lokasi['nama_lokasi']) . "' $selected>" . htmlspecialchars($lokasi['nama_lokasi']) . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="role" class="form-label">Role</label>
                                <select class="form-select" id="role" name="role" required>
                                    <option value="mahasiswa">Mahasiswa</option>
                                    <option value="siswa">Siswa</option>
                                </select>
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

<!-- Modal Tambah Data Lokasi-->
<div class="modal fade" id="modalTambahDatalokasi" tabindex="-1" aria-labelledby="modalTambahDataLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="tambah.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahDataLabel">Tambah Data Lokasi Presensi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Form Input -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nama_lokasi" class="form-label">Nama Lokasi</label>
                                <input type="text" class="form-control" id="nama_lokasi" name="nama_lokasi" required>
                            </div>
                            <div class="mb-3">
                                <label for="alamat_lokasi" class="form-label">Alamat Lokasi</label>
                                <textarea class="form-control" id="alamat_lokasi" name="alamat_lokasi" rows="3" required></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="zona_waktu" class="form-label">Zona Waktu</label>
                                <select class="form-select" id="zona_waktu" name="zona_waktu" required>
                                    <option value="WIB">WIB</option>
                                    <option value="WITA">WITA</option>
                                    <option value="WIT">WIT</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="jam_masuk" class="form-label">Jam Masuk</label>
                                <input type="time" class="form-control" id="jam_masuk" name="jam_masuk" required>
                            </div>
                            <div class="mb-3">
                                <label for="jam_keluar" class="form-label">Jam Keluar</label>
                                <input type="time" class="form-control" id="jam_keluar" name="jam_keluar" required>
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

<!-- Modal Tambah Data Ketidakhadiran -->
<div class="modal fade" id="modalTambahDataketidakhadiran" tabindex="-1" aria-labelledby="modalTambahDataKetidakhadiranLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="tambah_ketidakhadiran.php" method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahDataKetidakhadiranLabel">Tambah Data Ketidakhadiran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- PILIH USER -->
                    <div class="mb-3">
                        <label for="id_users" class="form-label">Pilih Mahasiswa/Siswa</label>
                        <select class="form-select" id="id_users" name="id_users" required>
                            <option value="">--- Pilih User ---</option>
                            <?php
                            // Ambil semua user mahasiswa/siswa yang aktif
                            $users = mysqli_query($koneksi, "SELECT u.id, md.nama FROM users u JOIN mahasiswa_detail md ON u.id = md.id_users WHERE u.status = 'Aktif' ORDER BY md.nama ASC");
                            while ($user = mysqli_fetch_assoc($users)): ?>
                                <option value="<?= $user['id'] ?>"><?= htmlspecialchars($user['nama']) ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
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

<script>
    function closeModal() {
        // Tutup modal setelah form dikirim
        const modal = document.getElementById('exampleModalbulan');
        const modalInstance = bootstrap.Modal.getInstance(modal);
        modalInstance.hide();

        // Redirect ke halaman rekap_bulanan.php
        window.location.href = 'rekap_bulanan.php';
    }
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
 <?php  ob_end_flush(); ?>

</html>