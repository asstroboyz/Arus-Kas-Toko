<?= $this->extend('Kasir/Templates/Index'); ?>

<?= $this->section('page-content'); ?>
<!-- Begin Page Content -->
<?php

use App\Models\detailPenjualanBarangModel;

$penjualanModel = new detailPenjualanBarangModel();
?>
<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-900"></h1>

    <?php if (session()->has('msg')) : ?>
    <div class="alert alert-success" role="alert">
        <?= session('msg') ?>
    </div>
    <?php endif; ?>
  

    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h3>Daftar Penjualan Barang </h3>
                    <a href="/Kasir/tambah_penjualanBarang" class="btn btn-primary"><i
                            class="fa fa-plus"></i> Tambah
                        Penjualan</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th style="width: 70px;">No</th>
                                    <th style="width: auto;">ID Penjualan Barang</th>
                                    <th style="width: 90px;">Total Barang</th>
                                    <th style="width: auto;">Tanggal Belanja</th>
                                    <th style="width: auto;">Nama Pembeli</th>
                                       <th style="width: auto;">Total Belanja</th>
                                    <th style="width: auto; text-align: center;">Action</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th style="width: 70px;">No</th>
                                    <th style="width: auto;">ID Penjualan Barang</th>
                                    <th style="width: 90px;">Total Barang</th>
                                    <th style="width: auto;">Tanggal Belanja</th>
                                    <th style="width: auto;">Nama Pembeli</th>
                                       <th style="width: auto;">Total Belanja</th>
                                    <th style="width: auto; text-align: center;">Action</th>
                                </tr>
                            </tfoot>
                            <tbody>
                                <?php if ($penjualan) { ?>
                                <?php foreach ($penjualan as $num => $data) {
                                    $total = $penjualanModel->where('id_penjualan_barang', $data['penjualan_barang_id'])->countAllResults();
                                    ?>
                                <tr>
                                    <td><?= $num + 1; ?></td>
                                    <td
                                        style="width: auto; max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                        <?= $data['penjualan_barang_id']; ?>
                                    </td>
                                    <td style="width: auto; max-width: 80px;">
                                        <?= $total; ?> Item
                                    </td>
                                    <td style="width: auto; max-width: 80px;">
                                        <?= date('d - M - Y - H:i:s', strtotime($data['tanggal_penjualan'])); ?>
                                    </td>
                                    <td style="width: auto; max-width: 80px;">
                                       <?= $data['nama_pelanggan']; ?>
                                    </td>
                                    <td style="width: auto; max-width: 80px;">
                                        <?= 'Rp' . number_format($data['total_penjualan'], 0, ',', '.'); ?>
                                    </td>
                                    <td style="text-align:center; width: auto; max-width: 150px;">
                                        <a href="/PenjualanBarangCont/list_penjualan/<?= $data['penjualan_barang_id'] ?>"
                                            class="btn btn-primary"><i class="fas fa-eye"></i></a>
                                        <a href="/PenjualanBarangCont/delete_penjualanBarang/<?= $data['penjualan_barang_id'] ?>"
                                            class="btn btn-danger delete-btn" data-toggle="modal"
                                            data-target="#deleteConfirmationModal">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php } ?>
                                <!-- end of foreach                -->
                                <?php } else { ?>
                                <tr>
                                    <td colspan="4">
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
<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" role="dialog"
    aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteConfirmationModalLabel">Konfirmasi Hapus</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus penjualan ini?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <a id="deleteConfirmationButton" href="#" class="btn btn-danger">Hapus</a>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>
<?= $this->section('additional-js'); ?>
<script>
    window.setTimeout(function() {
        $(".alert").fadeTo(5000, 0).slideUp(500, function() {
            $($this).remove();
        })

    }, 3000);

    function setDeleteConfirmationUrl(url) {
        $("#deleteConfirmationButton").attr("href", url);
    }

    // Show delete confirmation modal
    $(".delete-btn").on("click", function() {
        var deleteUrl = $(this).attr("href");
        setDeleteConfirmationUrl(deleteUrl);
        $("#deleteConfirmationModal").modal("show");
        return false;
    });

    // Close delete confirmation modal on cancel
    $('#deleteConfirmationModal').on('hide.bs.modal', function(e) {
        setDeleteConfirmationUrl("#"); // Reset the href when the modal is closed
    });

    // Hide alert messages after 3 seconds
</script>
<?= $this->endSection(); ?>