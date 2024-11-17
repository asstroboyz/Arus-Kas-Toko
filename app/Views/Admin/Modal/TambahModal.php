<?= $this->extend('Admin/Templates/Index') ?>

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
                      <h3 class="h3 mb-4 text-gray-900">Form Tambah Modal</h3>
                    <a href="/Admin/modal">&laquo; Kembali ke daftar modal</a>
                </div>
                <div class="card-body">
                    <form
                        action="<?= base_url('/Admin/saveModal') ?> "
                        method="post" enctype="multipart/form-data">
                        <?= csrf_field(); ?>
                        <div class="row">
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="sumber">Sumber</label>
                                    <input name="sumber" type="text"
                                        class="form-control form-control-user <?= ($validation->hasError('sumber')) ? 'is-invalid' : ''; ?>"
                                        id="input-sumber" placeholder="Masukkan Sumber"
                                        value="<?= old('sumber'); ?>" />
                                    <div id="sumberFeedback" class="invalid-feedback">
                                        <?= $validation->getError('sumber'); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="jumlah">Jumlah</label>
                                    <input name="jumlah" type="text"
                                        class="form-control form-control-user <?= ($validation->hasError('jumlah')) ? 'is-invalid' : ''; ?>"
                                        id="input-jumlah" placeholder="Masukkan Jumlah"
                                        value="<?= old('jumlah'); ?>" />
                                    <div id="jumlahFeedback" class="invalid-feedback">
                                        <?= $validation->getError('jumlah'); ?>
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