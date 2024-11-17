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
        <!-- Total Hutang Card -->
        <div class="col-md-3 mb-4">
            <div class="card shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Hutang</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php
                        $total_hutang = 0;
foreach ($hutangs as $hutang) {
    $total_hutang += $hutang['jumlah'];
}
echo "Rp " . number_format($total_hutang, 0, ',', '.');
?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Total Hutang Card -->

        <!-- Saldo Kas Card -->
        <div class="col-md-3 mb-4">
            <div class="card shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Saldo Kas Saat Ini
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo "Rp " . number_format($saldo_kas, 0, ',', '.'); ?>
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


        <!-- Daftar Hutang Table -->
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h3>Daftar Hutang</h3>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalTambahHutang">
                        <i class="fa fa-plus"></i> Tambah Hutang
                    </button>

                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-lg" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Keterangan</th>
                                    <th>Jumlah</th>
                                    <!-- <th>Sisa Hutang</th> -->
                                    <th>Status</th>
                                    <th>Tanggal Hutang</th>
                                    <th>Pembayaran</th>
                                    <!-- Tambah Kolom Pembayaran -->
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($hutangs) : ?>
                                <?php foreach ($hutangs as $index => $hutang) : ?>
                                <tr>
                                    <td><?= $index + 1; ?></td>
                                    <td><?= $hutang['keterangan']; ?>
                                    </td>
                                    <td><?= "Rp " . number_format($hutang['jumlah'], 0, ',', '.'); ?>
                                    </td>
                                    <!-- <td><?= "Rp " . number_format($hutang['jumlah_sisa'], 0, ',', '.'); ?>
                                    </td> -->
                                    <td><?= $hutang['status']; ?>
                                    </td>
                                    <td><?= date('d/m/Y', strtotime($hutang['tanggal'])); ?>
                                    </td>

                                    <td>
                                        <?php if ($hutang['jumlah'] > 0) : ?>
                                        <!-- Jika masih ada hutang yang belum lunas -->
                                        <button type="button" class="btn btn-warning btn-block btn-bayar"
                                            data-toggle="modal"
                                            data-target="#modalBayar<?= $hutang['id_hutang']; ?>">
                                            <i class="fas fa-money-bill"></i> Bayar
                                        </button>

                                        <?php else : ?>
                                        <!-- Jika hutang sudah lunas -->
                                        <span class="btn btn-block badge badge-success">Lunas</span>
                                        <?php endif; ?>
                                    </td>

                                    <!-- Modal Pembayaran -->
                                    <?php foreach ($hutangs as $hutang) : ?>
                                    <div class="modal fade"
                                        id="modalBayar<?= $hutang['id_hutang']; ?>"
                                        tabindex="-1" role="dialog"
                                        aria-labelledby="modalBayarLabel<?= $hutang['id_hutang']; ?>"
                                        aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title"
                                                        id="modalBayarLabel<?= $hutang['id_hutang']; ?>">
                                                        Konfirmasi Pembayaran</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Anda yakin ingin melakukan pembayaran untuk hutang ini?</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Batal</button>
                                                    <a href="/Kasir/bayarHutang/<?= $hutang['id_hutang']; ?>"
                                                        class="btn btn-success">Ya, Bayar</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>

                                    <!-- Modal delete -->
                                    <div class="modal fade"
                                        id="deleteModal<?= $hutang['id_hutang']; ?>"
                                        tabindex="-1" role="dialog"
                                        aria-labelledby="deleteModalLabel<?= $hutang['id_hutang']; ?>"
                                        aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title"
                                                        id="deleteModalLabel<?= $hutang['id_hutang']; ?>">
                                                        Konfirmasi Hapus</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    Apakah Anda yakin ingin menghapus hutang ini?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Batal</button>
                                                    <form
                                                        action="/Kasir/deleteHutang/<?= $hutang['id_hutang']; ?>"
                                                        method="post">
                                                        <?= csrf_field() ?>
                                                        <input type="hidden" name="_method" value="DELETE">
                                                        <button type="submit" class="btn btn-danger">Hapus</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </tr>
                                <?php endforeach; ?>


                                <?php else : ?>
                                <tr>
                                    <td colspan="8">
                                        <!-- Ubah colspan menjadi 8 karena kita menambahkan satu kolom baru -->
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
        <!-- End Daftar Hutang Table -->
    </div>
</div>

<!-- modal tambah hutang -->
<div class="modal fade" id="modalTambahHutang" tabindex="-1" role="dialog" aria-labelledby="modalTambahHutangLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" style="color: black;" id="modalTambahHutangLabel">Tambah Hutang</h5>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Form untuk menambahkan hutang -->
                <form action="/Kasir/saveHutang" method="post">
                    <?= csrf_field() ?>
                    <div class="form-group">
                        <label for="keterangan">Keterangan</label>
                        <input type="text" class="form-control" id="keterangan" name="keterangan"
                            value="<?= old('keterangan') ?>"
                            required>
                    </div>
                    <div class="form-group">
                        <label for="jumlah">Jumlah</label>
                        <input type="number" class="form-control" id="jumlah" name="jumlah"
                            value="<?= old('jumlah') ?>"
                            required>
                    </div>
                    <div class="form-group">
                        <label for="tanggal">Tanggal</label>
                        <input type="date" class="form-control" id="tanggal" name="tanggal"
                            value="<?= old('tanggal') ?>"
                            required>
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