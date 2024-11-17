<?= $this->extend('Kasir/Templates/Index') ?>

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
                    <h3>Daftar Aset</h3>
                    <a href="/Kasir/tambahAset" class="btn btn-primary"><i class="fa fa-plus"></i> Tambah Aset</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-lg" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th style="width: 5%;">No</th>
                                    <th>Nama Aset</th>
                                    <th>Nilai Aset</th>
                                    <th style="width: 10%;">Aksi</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th style="width: 5%;">No</th>
                                    <th>Nama Aset</th>
                                    <th>Nilai Aset</th>
                                    <th style="width: 10%;">Aksi</th>
                                </tr>
                            </tfoot>
                            <tbody>
                                <?php if ($aset) : ?>
                                <?php foreach ($aset as $index => $data) : ?>
                                <tr>
                                    <td><?= $index + 1; ?></td>
                                    <td><?= $data['nama_aset']; ?>
                                    </td>
                                    <td><?= "Rp " . number_format($data['nilai'], 0, ',', '.'); ?>
                                    </td>
                                    <td>
                                        <a href="/Kasir/editAset/<?= $data['id_aset']; ?>"
                                            class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i> 
                                        </a>
                                        <button type="button" class="btn btn-sm btn-danger" data-toggle="modal"
                                            data-target="#deleteModal<?= $data['id_aset']; ?>">
                                            <i class="fas fa-trash"></i> 
                                        </button>

                                    </td>
                                </tr>
                                <!-- Modal -->
                                <div class="modal fade"
                                    id="deleteModal<?= $data['id_aset']; ?>"
                                    tabindex="-1" role="dialog"
                                    aria-labelledby="deleteModalLabel<?= $data['id_aset']; ?>"
                                    aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title"
                                                    id="deleteModalLabel<?= $data['id_aset']; ?>">
                                                    Konfirmasi Hapus</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                Apakah Anda yakin ingin menghapus aset ini?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Batal</button>
                                                <form
                                                    action="/Kasir/deleteAset/<?= $data['id_aset']; ?>"
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
    $(document).ready(function() {
        window.setTimeout(function() {
            $(".alert").fadeTo(500, 0).slideUp(500, function() {
                $(this).remove();
            });
        }, 3000);
    });
</script>
<?= $this->endSection(); ?>