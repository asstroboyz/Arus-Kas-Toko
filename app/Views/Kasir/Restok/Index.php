<?= $this->extend('Kasir/Templates/Index'); ?>

<?= $this->section('page-content'); ?>
<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <?php if (session()->has('pesanBerhasil')) : ?>
    <div class="alert alert-success" role="alert">
        <?= session('pesanBerhasil') ?>
    </div>
    <?php endif; ?>
    <?php if (session()->has('message')) : ?>
    <div class="alert alert-danger" role="alert">
        <?= session('message') ?>
    </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h3>Daftar Restok Barang</h3>
                    <a href="/Kasir/tambahRestok" class="btn btn-primary"><i class="fa fa-plus"></i> Tambah Restok</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-lg" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Restok Id</th>
                                    <th>Tanggal</th>
                                    <th>Nama Supplier</th>
                                    <th>Jumlah Bayar</th>
                                    <th width="10%">Aksi</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th>Restok Id</th>
                                    <th>Tanggal</th>
                                    <th>Nama Supplier</th>
                                    <th>Jumlah Bayar</th>
                                    <th width="10%">Aksi</th>
                                </tr>
                            </tfoot>
                            <tbody>
                                <?php if ($dataRestok) : ?>
                                <?php foreach ($dataRestok as $index => $restok) : ?>
                                <tr>
                                    <td><?= $index + 1; ?></td>
                                    <td><?= $restok['restok_id']; ?>
                                    </td>
                                    <td> <?= date(' H:i:s - d - M - Y', strtotime($restok['tanggal'])); ?>
                                    </td>
                                    <td><?= $restok['nama']; ?>
                                     
                                    </td>
                                    <td>
                                        <?= "Rp " . number_format($restok['jumlah_pembayaran'], 0, ',', '.'); ?>
                                    </td>
                                    <td>
                                        <!-- Tombol-tombol Aksi (Edit dan Hapus) -->
                                        <a href="/Kasir/detailRestok/<?= $restok['restok_id']; ?>"
                                            class="btn btn-sm btn-success">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a type="button" class="btn btn-sm btn-danger" data-toggle="modal"
                                            data-target="#deleteModal<?= $restok['restok_id']; ?>">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                <div class="modal fade"
                                    id="deleteModal<?= $restok['restok_id']; ?>"
                                    tabindex="-1" role="dialog"
                                    aria-labelledby="deleteModalLabel<?= $restok['restok_id']; ?>"
                                    aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title"
                                                    id="deleteModalLabel<?= $restok['restok_id']; ?>">
                                                    Konfirmasi Hapus
                                                </h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                Apakah Anda yakin ingin menghapus restok ini?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Batal</button>
                                                <form
                                                    action="/Kasir/deleteRestok/<?= $restok['restok_id']; ?>"
                                                    method="post">
                                                    <?= csrf_field() ?>
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <button type="submit" class="btn btn-danger">Hapus</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                                <?php else : ?>
                                <tr>
                                    <td colspan="12">
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
<?php
date_default_timezone_set("Asia/Jakarta");
function format_tanggal($tanggal)
{
    $bulan = array(
        1 => 'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember'
    );
    $pecahkan = explode('-', $tanggal);

    return $pecahkan[2] . ' ' . $bulan[(int)$pecahkan[1]] . ' ' . $pecahkan[0];
}
?>
<?= $this->endSection(); ?>