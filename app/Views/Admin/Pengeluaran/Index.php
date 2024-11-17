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
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h3>Daftar Pengeluaran</h3>
                    <a href="/Admin/tambah_pengeluaran" class="btn btn-primary"><i class="fa fa-plus"></i> Tambah
                        Pengeluaran</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-lg" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Keterangan</th>
                                    <th>Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($pengeluaran) : ?>
                                <?php foreach ($pengeluaran as $index => $data) : ?>
                                <tr>
                                    <td><?= $index + 1; ?></td>
                                    <td><?= date('d M Y', strtotime($data['tanggal'])); ?></td>
                                    <td><?= $data['keterangan']; ?></td>
                                    <td><?= "Rp " . number_format($data['jumlah'], 0, ',', '.'); ?></td>
                                </tr>
                                <?php endforeach; ?>
                                <?php else : ?>
                                <tr>
                                    <td colspan="4">
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
    </div>
</div>
<?= $this->endSection(); ?>
