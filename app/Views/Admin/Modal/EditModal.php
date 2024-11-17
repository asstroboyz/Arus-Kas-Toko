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
                      <h1 class="h3 mb-4 text-gray-900">Edit Modal</h1>
                    <a href="/Admin/modal">&laquo; Kembali ke daftar modal</a>
                </div>
                <div class="card-body">
                  <form action="/admin/updateModal" method="post">
    <?= csrf_field(); ?>
    <input type="hidden" name="id_modal" value="<?= $modal['id_modal']; ?>">
    <div class="form-group">
        <label for="sumber">Sumber</label>
        <input type="text" name="sumber" class="form-control" id="sumber" value="<?= $modal['sumber']; ?>">
    </div>
    <div class="form-group">
        <label for="jumlah">Jumlah</label>
        <input type="number" name="jumlah" class="form-control" id="jumlah" value="<?= $modal['jumlah']; ?>">
    </div>
    <button type="submit" class="btn btn-primary">Simpan</button>
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
