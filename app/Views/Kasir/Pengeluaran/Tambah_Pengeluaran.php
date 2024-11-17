<?= $this->extend('Kasir/Templates/Index'); ?>

<?= $this->section('page-content'); ?>
<!-- Begin Page Content -->
<?php

use App\Models\KasModel;

$KasModel = new KasModel();
$lastBalance = $KasModel->getLastBalance(); // Misalnya Anda punya fungsi untuk mendapatkan saldo terakhir
?>
<div class="container-fluid">



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
        action="<?= base_url('/Kasir/simpan_pengeluaran') ?>"
        method="post" id="pengeluaranForm">
        <?= csrf_field(); ?>
        <div class="row">
            <div class="col-12">

                <div class="card shadow">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-6">
                                <h3 class="h3 mb-4 text-gray-900">Form Tambah Pengeluaran</h3>
                                <a href="/Kasir/pengeluaran">&laquo; Kembali ke daftar pengeluaran</a>
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
                                <div class="form-group">
                                    <label for="keterangan">Keterangan</label>
                                    <select name="keterangan" id="keterangan" class="form-control" required>
                                        <option value="">Pilih Keterangan</option>
                                        <!-- Tambahkan opsi default untuk mendorong pengguna memilih -->
                                        <option value="gaji">Gaji</option>
                                        <option value="listrik">Listrik</option>
                                        <option value="air">Air</option>
                                        <option value="lainnya">Lainnya</option>
                                    </select>


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
    // Fungsi untuk menampilkan modal peringatan
    function showWarningModal(message) {
        $('#modalContent').html(message);
        $('#alertModal').modal('show');
    }

    // Validasi jumlah
    $(document).ready(function() {
        var lastBalance = <?= $lastBalance ?> ;

        $('#pengeluaranForm').submit(function(e) {
            e.preventDefault();
            var jumlah = parseFloat($('#jumlah').val());
            if (jumlah > lastBalance) {
                showWarningModal('Jumlah yang dimasukkan melebihi saldo terakhir.');
            } else {
                this.submit();
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