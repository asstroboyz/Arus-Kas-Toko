<?= $this->extend('Admin/Templates/Index') ?>

<?= $this->section('page-content'); ?>
<!-- Begin Page Content -->
<div class="container-fluid">

   

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

                <div class="card-header d-flex justify-content-between align-items-center">
                    <h1 class="h3 mb-0 text-gray-900">Form Tambah Supplier</h1>
                    <a href="/Admin/supplier" class="btn btn-secondary btn-sm">&laquo; Kembali ke daftar supplier
                    </a>
                </div>
                <div class="card-body">
                    <form
                        action="<?= base_url('/Admin/saveSupplier') ?>"
                        method="post" enctype="multipart/form-data">
                        <?= csrf_field(); ?>
                        <div class="row">
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                <div class="form-group ">
                                    <label for="nama">Nama Supplier</label>
                                    <input name="nama" type="text"
                                        class="form-control form-control-user <?= ($validation->hasError('nama')) ? 'is-invalid' : ''; ?>"
                                        id="input-nama" placeholder="Masukkan nama supplier"
                                        value="<?= old('nama'); ?>" />
                                    <div id="namaFeedback" class="invalid-feedback">
                                        <?= $validation->getError('nama'); ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="alamat">Alamat</label>
                                    <textarea name="alamat"
                                        class="form-control form-control-user <?= ($validation->hasError('alamat')) ? 'is-invalid' : ''; ?>"
                                        id="input-alamat"
                                        placeholder="Masukkan alamat"><?= old('alamat'); ?></textarea>
                                    <div id="alamatFeedback" class="invalid-feedback">
                                        <?= $validation->getError('alamat'); ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="kontak">Kontak</label>
                                    <input name="kontak" type="text"
                                        class="form-control form-control-user <?= ($validation->hasError('kontak')) ? 'is-invalid' : ''; ?>"
                                        id="input-kontak" placeholder="Masukkan kontak"
                                        value="<?= old('kontak'); ?>" />
                                    <div id="kontakFeedback" class="invalid-feedback">
                                        <?= $validation->getError('kontak'); ?>
                                    </div>
                                </div>
                            </div>
                            <button class="btn btn-block btn-primary">Tambah Supplier</button>
                    </form>
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