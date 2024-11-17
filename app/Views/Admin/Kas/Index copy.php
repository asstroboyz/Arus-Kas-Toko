<?= $this->extend('Admin/Templates/Index'); ?>

<?= $this->section('page-content'); ?>
<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <?php if (session()->has('pesanBerhasil')) : ?>
    <div class="alert alert-success" role="alert">
        <?= session('pesanBerhasil') ?>
    </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-lg-12">
            <div class="card border-left-black shadow mb-4">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-black text-uppercase mb-1">
                                Kas Saat Ini
                            </div>
                            <?php if (isset($saldo_terakhir)) : ?>
                            <div class="h5 mb-0 font-weight-bold text-black">
                                Rp.
                                <?= number_format($saldo_terakhir, 0, ',', '.'); ?>
                            </div>
                            <?php else : ?>
                            <div class="h5 mb-0 font-weight-bold text-danger">
                                Saldo Tidak Tersedia
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-black"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h3 class="m-0 font-weight-bold text-primary">Daftar Kas</h3>
                    <a href="/Admin/tambahKas" class="btn btn-primary"><i class="fa fa-plus"></i> Tambah Kas</a>
                </div>
                <div class="card-body">
                     <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Jenis Transaksi</th>
                                    <th>Keterangan</th>
                                    <th>Saldo Awal</th>
                                    <th>Transaksi</th>
                                    <th>Saldo Terakhir</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($kas) : ?>
                                <?php foreach ($kas as $index => $data) : ?>
                                <tr>
                                    <td><?= $index + 1; ?></td>
                                    <td><?= $data['tanggal']; ?>
                                    </td>
                                    <td><?= $data['jenis_transaksi']; ?>
                                    </td>
                                    <td><?= $data['keterangan']; ?>
                                    </td>
                                    <td>Rp.
                                        <?= number_format(floatval($data['jumlah_awal']), 0, ',', '.'); ?>
                                    </td>
                                    <td>Rp.
                                        <?= number_format(floatval($data['jumlah_akhir']), 0, ',', '.'); ?>
                                    </td>
                                    <td>Rp.
                                        <?= number_format(floatval($data['saldo_terakhir']), 0, ',', '.'); ?>
                                    </td>
                                    <td>
                                        <a href="/Admin/editKas/<?= $data['id_kas']; ?>"
                                            class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <button href="#" class="btn btn-danger btn-sm btn-delete" data-toggle="modal"
                                            data-target="#modalKonfirmasiDelete"
                                            data-kodekas="<?= $data['id_kas']; ?>">
                                            <i class="fa fa-trash"></i> Hapus
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                <?php else : ?>
                                <tr>
                                    <td colspan="8">
                                        <h3 class="text-center text-muted">Data belum ada.</h3>
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Page Content -->

<!-- Modal konfirmasi delete -->
<div class="modal fade" id="modalKonfirmasiDelete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Konfirmasi Hapus Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus data ini?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <!-- Tombol delete yang memanggil fungsi deleteKas() -->
                <a href="/Admin/deleteKas/<?= $data['id_kas']; ?>"
                    class="btn btn-danger">Hapus</a>
            </div>
        </div>
    </div>
</div>
<!-- End Modal konfirmasi delete -->

<?= $this->endSection(); ?>

<?= $this->section('additional-js'); ?>
<script>
    // Function to hide success message after a certain period
    window.onload = function() {
        setTimeout(function() {
            var alert = document.querySelector('.alert');
            if (alert) {
                alert.style.display = 'none';
            }
        }, 3000); // 3000 milliseconds = 3 seconds
    };
</script>
<?= $this->endSection(); ?>