<?= $this->extend('Kasir/Templates/Index'); ?>

<?= $this->section('page-content'); ?>
<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <?php if (session()->has('success')) : ?>
        <div class="alert alert-success" role="alert">
            <?= session('success') ?>
        </div>
    <?php endif; ?>
    <?php if (session()->has('error')) : ?>
        <div class="alert alert-danger" role="alert">
            <?= session('error') ?>
        </div>
    <?php endif; ?>

    <div class="row">
        <!-- Total Piutang Card -->
        <div class="col-md-3 mb-4">
            <div class="card shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Piutang</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= "Rp " . number_format($total_piutang, 0, ',', '.'); ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Total Piutang Card -->

        <!-- Saldo Kas Card -->
        <div class="col-md-3 mb-4">
            <div class="card shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Saldo Kas Saat Ini
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= "Rp " . number_format($saldo_kas, 0, ',', '.'); ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Saldo Kas Card -->


        <!-- Daftar Piutang Table -->
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h3>Daftar Piutang</h3>
                    <!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalTambahPiutang">
                        <i class="fa fa-plus"></i> Tambah Piutang
                    </button> -->

                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-lg" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>ID Penjualan Barang</th>
                                    <th>ID Pelanggan</th>
                                    <th>Tanggal Piutang</th>
                                    <th>Jatuh Tempo</th>
                                    <th>Jumlah Piutang</th>
                                    <th>Jumlah Terbayar</th>
                                    <th>Status Piutang</th>
                                    <th>Pembayaran</th>
                                    <!-- Tambah Kolom Pembayaran -->
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($data['piutangs']) : ?>
                                    <?php foreach ($data['piutangs'] as $index => $piutang) : ?>
                                        <tr>
                                            <td><?= $index + 1; ?></td>
                                            <td><?= $piutang['id_penjualan_barang']; ?>
                                            </td>
                                            <td><?= $piutang['nama_pelanggan']; ?>
                                            </td>
                                            <td><?= date('d/m/Y', strtotime($piutang['tanggal_piutang'])); ?>
                                            </td>
                                            <td>
    <?php
    $jatuhTempo = strtotime($piutang['jatuh_tempo']);
    $today = strtotime(date('Y-m-d'));
    $selisihHari = ceil(($today - $jatuhTempo) / (60 * 60 * 24)); // Hitung selisih dalam hari

    if ($today > $jatuhTempo) {
        // Tanggal sudah melebihi jatuh tempo, tampilkan button dengan informasi selisih hari
        echo '<button class="btn btn-danger">Sudah Jatuh Tempo (' . $selisihHari . ' hari)</button>';
    } else {
        // Tanggal belum melebihi jatuh tempo, tampilkan tanggal
        echo date('d/m/Y', $jatuhTempo);
    }
    ?>
</td>


                                            <td><?= "Rp " . number_format($piutang['jumlah_piutang'], 0, ',', '.'); ?>
                                            </td>
                                            <td><?= "Rp " . number_format($piutang['jumlah_terbayar'], 0, ',', '.'); ?>
                                            </td>
                                            <td>
                                                <?php echo ($piutang['status_piutang'] === 'lunas') ? 'Lunas' : 'Belum Lunas'; ?>
                                            </td>

                                            <td>
                                                <?php if ($piutang['status_piutang'] === 'lunas') : ?>
                                                    <!-- Jika piutang sudah lunas -->
                                                    <span class="btn btn-block badge badge-success">Lunas</span>
                                                <?php elseif ($piutang['status_piutang'] === 'belum_lunas' && $piutang['jumlah_piutang'] > 0) : ?>
                                                    <!-- Jika piutang belum lunas dan masih ada piutang yang harus dibayar -->
                                                    <button type="button" class="btn btn-warning btn-block btn-bayar" data-toggle="modal" data-target="#modalBayar<?= $piutang['id_piutang']; ?>">
                                                        <i class="fas fa-money-bill"></i> Bayar
                                                    </button>
                                                <?php endif; ?>
                                            </td>




                                            <!-- Modal Pembayaran -->
                                            <div class="modal fade" id="modalBayar<?= $piutang['id_piutang']; ?>" tabindex="-1" role="dialog" aria-labelledby="modalBayarLabel<?= $piutang['id_piutang']; ?>" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="modalBayarLabel<?= $piutang['id_piutang']; ?>">
                                                                Konfirmasi Pembayaran</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>Anda yakin ingin melakukan pembayaran untuk piutang ini?</p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                            <a href="/Kasir/bayarPiutang/<?= $piutang['id_piutang']; ?>" class="btn btn-success">Ya, Bayar</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="9">
                                            <!-- Ubah colspan menjadi 9 karena kita menambahkan satu kolom baru -->
                                            <h3 class="text-gray-900 text-center">Data belum ada.</h3>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
        <!-- End Daftar Piutang Table -->
    </div>
</div>

<!-- modal tambah piutang -->
<div class="modal fade" id="modalTambahPiutang" tabindex="-1" role="dialog" aria-labelledby="modalTambahPiutangLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" style="color: black;" id="modalTambahPiutangLabel">Tambah Piutang</h5>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Form untuk menambahkan piutang -->
                <form action="/Kasir/savePiutang" method="post">
                    <?= csrf_field() ?>
                    <div class="form-group">
                        <label for="keterangan">Keterangan</label>
                        <input type="text" class="form-control" id="keterangan" name="keterangan" value="<?= old('keterangan') ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="jumlah">Jumlah</label>
                        <input type="number" class="form-control" id="jumlah" name="jumlah" value="<?= old('jumlah') ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="tanggal">Tanggal</label>
                        <input type="date" class="form-control" id="tanggal" name="tanggal" value="<?= old('tanggal') ?>" required>
                    </div>
            </div>
            <div class="modal-footer">
                <!-- Tombol Simpan -->
                <button type="submit" class="btn btn-primary">Simpan</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                </form> <!-- Penutup tag form -->
            </div>
        </div>
    </div>
</div>


<?= $this->endSection(); ?>

<?= $this->section('additional-js'); ?>
<script>
    $(document).ready(function() {
        window.setTimeout(function() {
            $(".alert").fadeTo(500, 0).slideUp(500, function() {
                $(this).remove();
            });
        }, 3000);
    });
</script>
<?= $this->endSection(); ?>