<?= $this->extend('Admin/Templates/Index') ?>

<?= $this->section('page-content'); ?>
<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-900">Form Edit Data Paket</h1>

    <?php if (session()->getFlashdata('pesan')) : ?>
        <div class="row">
            <div class="col-12">
                <div class="alert alert-success" role="alert">
                    <?= session()->getFlashdata('pesan'); ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header">
                    <a href="/admin/paket">&laquo; Kembali ke daftar paket</a>
                </div>
                <div class="card-body">
                    <form action="/admin/updatePaket" method="post">
                        <?= csrf_field(); ?>
                        <div class="row">
                            <input type="hidden" name="kode_paket" value="<?= $paket['kode_paket']; ?>">

                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="nama_paket">Nama Paket</label>
                                    <input name="nama_paket" type="text" class="form-control form-control-user <?= ($validation->hasError('nama_paket')) ? 'is-invalid' : ''; ?>" id="input-nama_paket" value="<?= $paket['nama_paket']; ?>" />
                                    <div id="nama_paketFeedback" class="invalid-feedback">
                                        <?= $validation->getError('nama_paket'); ?>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="harga">Harga Paket</label>
                                    <input name="harga" type="number" class="form-control form-control-user <?= ($validation->hasError('harga')) ? 'is-invalid' : ''; ?>" id="input-harga" value="<?= $paket['harga']; ?>" />
                                    <div id="hargaFeedback" class="invalid-feedback">
                                        <?= $validation->getError('harga'); ?>
                                    </div>
                                </div>
                            </div>

                            <button class="btn btn-block btn-warning">Update Data</button>
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
