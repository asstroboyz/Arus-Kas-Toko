<?= $this->extend('Pemilik/Templates/Index') ?>

<?=$this->section('page-content');?>
<!-- Begin Page Content -->

<div class="container-fluid">

    <!-- Second Row -->
    <?php if (session()->has('error')): ?>
    <div class="alert alert-danger" role="alert">
        <?=session('error')?>
    </div>
    <?php endif;?>
    <?php if (session()->has('error')): ?>
    <div class="alert alert-danger" role="alert">
        <?=session('error')?>
    </div>
    <?php endif;?>
    <div class="row">
        <!-- Third Column -->
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Cetak Laba Rugi</h6>
                </div>
                <div class="card-body">
                    <form
                        action="<?=base_url('Pemilik/cetakLabaRugi');?>"
                        method="post" target="_blank">
                        <?= csrf_field() ?>
                        <div class="form-group">
                            <label for="tanggal_mulai">Pilih Rentang Waktu:</label>
                            <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai" required>
                        </div>
                        <div class="form-group">
                            <label for="tanggal_akhir">Sampai dengan:</label>
                            <input type="date" class="form-control" id="tanggal_akhir" name="tanggal_akhir" required>
                        </div>

                        <center>
                            <button type="submit" class="btn btn-primary">Cetak Data</button>
                        </center>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?=$this->endSection();?>
<?=$this->section('additional-js');?>
<script>
    // Script untuk mengatur tanggal otomatis
    $(document).ready(function() {
        var today = new Date().toISOString().slice(0, 10);
        $("#tanggal_mulai").val(today);
        $("#tanggal_akhir").val(today);
    });
</script>
<?=$this->endSection();?>