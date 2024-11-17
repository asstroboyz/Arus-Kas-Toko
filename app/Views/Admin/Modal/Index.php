<?= $this->extend('Admin/Templates/Index') ?>

<?= $this->section('page-content'); ?>
<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-900"></h1>

    <?php if (session()->getFlashdata('error-msg')): ?>
    <div class="row">
        <div class="col-12">
            <div class="alert alert-danger" role="alert">
                <?=session()->getFlashdata('error-msg');?>
            </div>
        </div>
    </div>
    <?php endif;?>

    <?php if (session()->getFlashdata('msg')): ?>
    <div class="row">
        <div class="col-12">
            <div class="alert alert-success alert-dismissible show fade" role="alert">

                <div class="alert-body">
                    <button class="close" data-dismiss>x</button>
                    <b><i class="fa fa-check"></i></b>
                    <?=session()->getFlashdata('msg');?>
                </div>
            </div>
        </div>
    </div>
    <?php endif;?>

    <!-- Menampilkan total modal -->
    <div class="row">


        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-black shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-black text-uppercase mb-1">
                                Total Modal
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-black">
                             Rp.   <?= number_format($totalModal, 0, ',', '.'); ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-black"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <!-- <h3>Modal Toko</h3> -->
                    <a href="/Admin/tambahModal" class="btn btn-primary"><i class="fa fa-plus"></i> Tambah Modal
                        Toko</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th style="width: 5%;">No</th>
                                    <th>Sumber</th>
                                    <th>Jumlah</th>
                                    <th style="width: 10%;">Action</th> <!-- Kolom untuk action edit dan hapus -->
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th style="width: 5%;">No</th>
                                    <th>Sumber</th>
                                    <th>Jumlah</th>
                                    <th style="width: 10%;">Action</th> <!-- Kolom untuk action edit dan hapus -->
                                </tr>
                            </tfoot>
                            <tbody>
                                <?php if ($modal): ?>
                                <?php foreach ($modal as $num => $modal): ?>
                                <tr>
                                    <td><?= $num + 1; ?></td>
                                    <td><?= $modal['sumber']; ?>
                                    </td>
                                    <td><?= number_format($modal['jumlah'], 0, ',', '.'); ?>
                                    </td>
                                    <td style="text-align: center;">
                                        <a href="/Admin/editModal/<?= $modal['id_modal']; ?>"
                                            class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a class="btn btn-sm btn-danger" data-toggle="modal"
                                            data-target="#deleteModal<?= $modal['id_modal']; ?>">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>

                                </tr>
                                <!-- Modal hapus untuk setiap data modal -->
                                <div class="modal fade"
                                    id="deleteModal<?= $modal['id_modal']; ?>"
                                    tabindex="-1"
                                    aria-labelledby="deleteModalLabel<?= $modal['id_modal']; ?>"
                                    aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title"
                                                    id="deleteModalLabel<?= $modal['id_modal']; ?>">
                                                    Konfirmasi Hapus</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                Apakah Anda yakin ingin menghapus data modal ini?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Batal</button>
                                                <a href="/Admin/deleteModal/<?= $modal['id_modal']; ?>"
                                                    class="btn btn-danger">Hapus</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                                <?php else: ?>
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
<?= $this->section('additional-js'); ?>
<script>
    window.setTimeout(function() {
        $(".alert").fadeTo(500, 0).slideUp(500, function() {
            $(this).remove();
        });
    }, 3000);
</script>
<?= $this->endSection(); ?>