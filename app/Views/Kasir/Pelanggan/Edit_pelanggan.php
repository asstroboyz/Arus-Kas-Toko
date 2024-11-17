<?= $this->extend('Kasir/Templates/Index') ?>

<?= $this->section('page-content'); ?>
<div class="container-fluid">
    <!-- Page Heading -->
   
    <?php if (session()->getFlashdata('msg')) : ?>
    <div class="row">
        <div class="col-12">
            <div class="alert alert-success" role="alert">
                <?= session()->getFlashdata('msg'); ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header">
                     <h1 class="h3 mb-4 text-gray-900">Form Edit Data Pelanggan</h1>

                    <a href="/Kasir/pelanggan">&laquo; Kembali ke daftar Pelanggan</a>
                </div>
                <div class="card-body">
                    <form action="/Kasir/updatePelanggan" method="post" enctype="multipart/form-data">
                        <?= csrf_field(); ?>
                        <input type="hidden" name="id_pelanggan"
                            value="<?= $pelanggan['id_pelanggan']; ?>">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nama">Nama Pelanggan</label>
                                    <input name="nama" type="text"
                                        class="form-control <?= ($validation->hasError('nama')) ? 'is-invalid' : ''; ?>"
                                        id="input-nama"
                                        value="<?= $pelanggan['nama']; ?>" />
                                    <div class="invalid-feedback">
                                        <?= $validation->getError('nama'); ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="kontak">Kontak Pelanggan</label>
                                    <input name="kontak" type="number"
                                        class="form-control <?= ($validation->hasError('kontak')) ? 'is-invalid' : ''; ?>"
                                        id="input-kontak"
                                        value="<?= $pelanggan['kontak']; ?>" />
                                    <div class="invalid-feedback">
                                        <?= $validation->getError('kontak'); ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="alamat">Alamat Pelanggan</label>
                                    <textarea name="alamat"
                                        class="form-control <?= ($validation->hasError('alamat')) ? 'is-invalid' : ''; ?>"
                                        id="input-alamat"
                                        rows="3"><?= $pelanggan['alamat']; ?></textarea>
                                    <div class="invalid-feedback">
                                        <?= $validation->getError('alamat'); ?>
                                    </div>
                                </div>
                                <button class="btn btn-warning btn-block">Update Data</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection('page-content'); ?>
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