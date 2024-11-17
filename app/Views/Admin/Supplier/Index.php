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

                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h3 class="m-0">Daftar Supplier</h3>
                    <a href="/Admin/tambahSupplier" class="btn btn-primary"><i class="fa fa-plus"></i> Tambah
                        Supplier</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-lg" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Supplier</th>
                                    <th>Alamat</th>
                                    <th>Kontak</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($suppliers) : ?>
                                <?php foreach ($suppliers as $index => $supplier) : ?>
                                <tr>
                                    <td><?= $index + 1; ?></td>
                                    <td><?= $supplier['nama']; ?>
                                    </td>
                                    <td><?= $supplier['alamat']; ?>
                                    </td>
                                    <td><?= $supplier['kontak']; ?>
                                    </td>
                                    <td>
                                        <a href="/Admin/editSupplier/<?= $supplier['id_supplier']; ?>"
                                            class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <button type="button" class="btn btn-sm btn-danger" data-toggle="modal"
                                            data-target="#deleteModal<?= $supplier['id_supplier']; ?>">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </td>
                                </tr>
                                <!-- Modal -->
                                <div class="modal fade"
                                    id="deleteModal<?= $supplier['id_supplier']; ?>"
                                    tabindex="-1" role="dialog"
                                    aria-labelledby="deleteModalLabel<?= $supplier['id_supplier']; ?>"
                                    aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title"
                                                    id="deleteModalLabel<?= $supplier['id_supplier']; ?>">
                                                    Konfirmasi Hapus</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                Apakah Anda yakin ingin menghapus supplier ini?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Batal</button>
                                                <form
                                                    action="/Admin/deleteSupplier/<?= $supplier['id_supplier']; ?>"
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
                                    <td colspan="5">
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
<?= $this->endSection(); ?>