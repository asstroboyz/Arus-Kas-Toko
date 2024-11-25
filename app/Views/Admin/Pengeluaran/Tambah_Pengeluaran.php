<?= $this->extend('Admin/Templates/Index'); ?>

<?= $this->section('page-content'); ?>
<!-- Begin Page Content -->
<?php

use App\Models\KasModel;

$KasModel = new KasModel();
$lastBalance = $KasModel->getLastBalance(); // Misalnya Anda punya fungsi untuk mendapatkan saldo terakhir
?>
<div class="container-fluid">

    <!-- Page Heading -->


    <?php if (session()->getFlashdata('msg')) : ?>
        <div class="row">
            <div class="col-12">
                <div class="alert alert-success" role="alert">
                    <?= session()->getFlashdata('msg'); ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <form
        action="<?= base_url('/Admin/simpan_pengeluaran') ?>"
        method="post" id="pengeluaranForm">
        <?= csrf_field(); ?>
        <div class="row">
            <div class="col-12">

                <div class="card shadow">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-6">
                                <h3 class="h3 mb-4 text-gray-900">Form Tambah Pengeluaran</h3>
                                <a href="/Admin/pengeluaran">&laquo; Kembali ke daftar pengeluaran</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="tanggal">Tanggal</label>
                                    <input type="date" name="tanggal" id="tanggal" class="form-control" required>
                                </div>
                            </div>
                          <div class="col-lg-6">
                               <!--    <div class="form-group">
                                    <label for="keterangan">Keterangan</label>
                                    <div id="selectKeterangan">
                                        <select name="keterangan" id="keterangan" class="form-control" required>
                                            <option value="">Pilih Keterangan</option>
                                            <option value="teknisi">Bayar Teknisi</option>
                                            <option value="listrik">Listrik</option>
                                            <option value="air">Air</option>
                                            <option value="beli perlengkapan">Beli perlengkapan</option>
                                            <option value="lainnya">Lainnya</option>
                                        </select>
                                    </div>
                                  
                                    <div id="inputKeterangan" style="display: none;">
                                        <input type="text" name="keterangan" id="keteranganInput" class="form-control" placeholder="Masukkan keterangan lainnya" />
                                    </div>
                                </div> -->
                            <div class="form-group">
                                <label for="keterangan">Keterangan</label>
                                <div id="selectKeterangan">
                                    <select id="keterangan" class="form-control" required>
                                        <option value="">Pilih Keterangan</option>
                                        <option value="gaji">Gaji</option>
                                        <option value="listrik">Listrik</option>
                                        <option value="air">Air</option>
                                        <option value="lainnya">Lainnya</option>
                                    </select>
                                </div>
                                <!-- Input untuk menggantikan select jika "Lainnya" dipilih -->
                                <div id="inputKeterangan" style="display: none;">
                                    <input type="text" id="keteranganInput" class="form-control" placeholder="Masukkan keterangan lainnya" />
                                </div>
                                <!-- Input hidden untuk menyimpan nilai akhir keterangan -->
                                <input type="hidden" name="keterangan" id="keteranganHidden" />
                            </div>

                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="jumlah">Jumlah</label>
                                <input type="number" name="jumlah" id="jumlah" class="form-control" required min="0"
                                    max="<?= $lastBalance ?>">

                                <div id="saldo-message" class="text-danger"></div>
                                <!-- Untuk menampilkan pesan kesalahan -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary btn-block mt-3">Simpan Pengeluaran</button>
            <!-- Menambahkan class btn-block -->
        </div>
</div>
</form>
</div>
<?= $this->endSection(); ?>

<?= $this->section('additional-js'); ?>
<!-- Memuat jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function () {
        // Fungsi untuk toggle input
        $('#keterangan').change(function () {
            if ($(this).val() === "lainnya") {
                // Sembunyikan select dan tampilkan input
                $('#selectKeterangan').hide();
                $('#inputKeterangan').show();
                $('#keteranganInput').val(' '); // Awalan
            } else {
                // Pastikan select tetap terlihat jika bukan "Lainnya"
                $('#selectKeterangan').show();
                $('#inputKeterangan').hide();

                // Salin nilai langsung dari select ke input hidden
                $('#keteranganHidden').val($(this).val());
            }
        });

        // Saat input "Lainnya" berubah, perbarui nilai hidden input
        $('#keteranganInput').on('input', function () {
            $('#keteranganHidden').val($(this).val());
        });

        // Pastikan input hidden memiliki nilai saat submit
        $('#pengeluaranForm').submit(function (e) {
            var selectedValue = $('#keterangan').val();
            var inputValue = $('#keteranganInput').val();

            if (selectedValue !== "lainnya") {
                $('#keteranganHidden').val(selectedValue);
            } else if (selectedValue === "lainnya" && inputValue.trim() === "") {
                alert("Silakan isi keterangan lainnya.");
                e.preventDefault();
            }
        });
    });
</script>


<!-- Modal -->
<div class="modal fade" id="alertModal" tabindex="-1" aria-labelledby="alertModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="alertModalLabel">Peringatan!</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalContent">
                <!-- Pesan kesalahan akan ditampilkan di sini -->
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>