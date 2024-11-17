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
                       <h1 class="h3 mb-4 text-gray-900">Edit Aset</h1>
                    <a href="/Kasir/aset">&laquo; Kembali ke daftar aset</a>
                </div>
                <div class="card-body">
                    <form
                        action="<?= base_url('/Kasir/updateAset/' . $aset['id_aset']) ?>"
                        method="post" enctype="multipart/form-data">
                        <?= csrf_field(); ?>
                        <div class="row">
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="nama_aset">Nama Aset</label>
                                    <input name="nama_aset" type="text"
                                        class="form-control form-control-user <?= ($validation->hasError('nama_aset')) ? 'is-invalid' : ''; ?>"
                                        id="input-nama_aset" placeholder="Masukkan nama aset"
                                        value="<?= old('nama_aset', $aset['nama_aset']); ?>" />
                                    <div id="nama_asetFeedback" class="invalid-feedback">
                                        <?= $validation->getError('nama_aset'); ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="nilai">Nilai Aset</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Rp</span>
                                        </div>
                                        <input name="nilai" type="number" onkeyup="formatRupiah(this)"
                                            class="form-control form-control-user <?= ($validation->hasError('nilai')) ? 'is-invalid' : ''; ?>"
                                            id="input-nilai" placeholder="Masukkan nilai aset"
                                            value="<?= old('nilai', $aset['nilai']); ?>" />
                                        <div id="nilaiFeedback" class="invalid-feedback">
                                            <?= $validation->getError('nilai'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-block btn-primary">Simpan Perubahan</button>
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