<?= $this->extend('Kasir/Templates/Index') ?>

<?= $this->section('page-content'); ?>
<!-- Begin Page Content -->
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
                    <h3 class="h3 mb-4 text-gray-900">Form Tambah Aset</h3>
                    <a href="/Kasir/aset">&laquo; Kembali ke daftar aset</a>
                </div>
                <div class="card-body">
                    <form
                        action="<?= base_url('/Kasir/saveAset') ?> "
                        method="post" enctype="multipart/form-data">
                        <?= csrf_field(); ?>
                        <div class="row">
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">

                                <div class="form-group ">
                                    <label for="nama_aset">Nama Aset</label>
                                    <input name="nama_aset" type="text"
                                        class="form-control form-control-user <?= ($validation->hasError('nama_aset')) ? 'is-invalid' : ''; ?>"
                                        id="input-nama_aset" placeholder="Masukkan nama ase"
                                        value="<?= old('nama_aset'); ?>" />
                                    <div id="nama_asetFeedback" class="invalid-feedback">
                                        <?= $validation->getError('nama_aset'); ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="nilai">nilai aset</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Rp</span>
                                        </div>
                                        <input name="nilai" type="number" onkeyup="formatRupiah(this)"
                                            class="form-control form-control-user <?= ($validation->hasError('nilai')) ? 'is-invalid' : ''; ?>"
                                            id="input-nilai" placeholder="Masukkan nilai aset"
                                            value="<?= old('nilai'); ?>" />
                                        <div id="nilaiFeedback" class="invalid-feedback">
                                            <?= $validation->getError('nilai'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button class="btn btn-block btn-primary">Tambah Data</button>
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