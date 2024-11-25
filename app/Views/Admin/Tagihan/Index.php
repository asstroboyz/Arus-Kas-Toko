<?= $this->extend('Admin/Templates/Index'); ?>

<?= $this->section('page-content'); ?>
<?php

use App\Models\TagihanModel;

$tagihanModel = new TagihanModel();
?>
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-900"></h1>

    <?php if (session()->getFlashdata('error-msg')) : ?>
        <div class="row">
            <div class="col-12">
                <div class="alert alert-danger" role="alert">
                    <?= session()->getFlashdata('error-msg'); ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('msg')) : ?>
        <div class="row">
            <div class="col-12">
                <div class="alert alert-success alert-dismissible show fade" role="alert">
                    <div class="alert-body">
                        <button class="close" data-dismiss>x</button>
                        <b><i class="fa fa-check"></i></b>
                        <?= session()->getFlashdata('msg'); ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h3>Daftar Tagihan</h3>
                    <div>
                        <a href="<?php echo base_url('TagihanCont/tambahForm/'); ?>"
                            class="btn btn-primary">
                            <i class="fa fa-plus"></i> Tambah Tagihan
                        </a>
                        <a href="<?php echo base_url('TagihanCont/arsipTagihan/'); ?>"
                            class="btn btn-success">
                            <i class="fa fa-archive"></i> Arsip Tagihan
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th style="width: 5%;">No</th>
                                    <th style="width: 15%;">Nama</th>
                                    <th style="width: 15%;">Nama Paket</th>
                                    <th style="width: 10%;">Tanggal Tagihan</th>
                                    <th style="width: 10%;">Jumlah Tagihan</th>
                                    <th style="width: 10%;">Status</th>
                                    <th style="width: 20%;">Alamat</th>
                                    <th style="width: 15%;">Opsi</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th style="width: 5%;">No</th>
                                    <th style="width: 15%;">Nama</th>
                                    <th style="width: 15%;">Nama Paket</th>
                                    <th style="width: 10%;">Tanggal Tagihan</th>
                                    <th style="width: 10%;">Jumlah Tagihan</th>
                                    <th style="width: 10%;">Status</th>
                                    <th style="width: 20%;">Alamat</th>
                                    <th style="width: 15%;">Opsi</th>
                                </tr>
                            </tfoot>
                            <tbody>
                                <?php if ($tagihan) { ?>
                                    <?php foreach ($tagihan as $num => $data) : ?>
                                        <tr>
                                            <td><?= $num + 1; ?></td>
                                            <td><?= $data['nama']; ?></td> <!-- Nama Pelanggan -->
                                            <td><?= $data['nama_paket']; ?></td>
                                            <td><?= date('d-m-Y', strtotime($data['tanggal_tagihan'])); ?></td>
                                            <td>Rp <?= number_format(floatval($data['jumlah_tagihan']), 0, ',', '.'); ?></td>
                                            <td>
                                                <?= $data['status_tagihan'] === 'Lunas'
                                                    ? '<span class="btn btn-success text-white d-flex justify-content-center align-items-center">' . $data['status_tagihan'] . '</span>'
                                                    : '<span class="btn btn-warning text-white d-flex justify-content-center align-items-center">' . $data['status_tagihan'] . '</span>'; ?>
                                            </td>
                                            <td><?= $data['alamat']; ?></td>
                                            <td>
                                                <a href="#" class="btn btn-danger btn-sm btn-block" data-toggle="modal"
                                                    data-target="#modalKonfirmasiDelete"
                                                    data-id="<?= $data['id'] ?>">
                                                    <i class="fa fa-trash"></i> Hapus Tagihan
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php } else { ?>
                                    <tr>
                                        <td colspan="8">
                                            <h3 class="text-gray-900 text-center">Data belum ada.</h3>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>

                        </table>
                    </div>
                </div>


            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalKonfirmasiDelete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Konfirmasi Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus tagihan ini?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <a id="deleteLink" href="#" class="btn btn-danger">Hapus</a>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('additional-js'); ?>
<script>
    window.setTimeout(function() {
        $(".alert").fadeTo(500, 0).slideUp(500, function() {
            $($this).remove();
        });
    }, 3000);
    $('#modalKonfirmasiDelete').on('show.bs.modal', function(e) {
        var id = $(e.relatedTarget).data('id');
        $('#deleteLink').attr('href',
            '<?= base_url("/TagihanCont/softDelete/") ?>' + '/' + id);
    });
</script>

<?= $this->endSection(); ?>