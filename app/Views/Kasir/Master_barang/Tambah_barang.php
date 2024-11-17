<?= $this->extend('Kasir/Templates/Index') ?>

<?= $this->section('page-content'); ?>
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-900">Form Tambah Barang</h1>

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
                    <a href="/Kasir/master_barang">&laquo; Kembali ke daftar barang</a>
                </div>
                <div class="card-body">
                    <form
                        action="<?= base_url('/Kasir/saveBarang') ?> "
                        method="post" enctype="multipart/form-data">
                        <?= csrf_field(); ?>
                        <div class="row">
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">

                                <div class="form-group ">
                                    <label for="nama_barang">Nama Barang</label>
                                    <input name="nama_barang" type="text"
                                        class="form-control form-control-user <?= ($validation->hasError('nama_barang')) ? 'is-invalid' : ''; ?>"
                                        id="input-nama_barang" placeholder="Masukkan Nama Barang"
                                        value="<?= old('nama_barang'); ?>" />
                                    <div id="nama_barangFeedback" class="invalid-feedback">
                                        <?= $validation->getError('nama_barang'); ?>
                                    </div>
                                </div>

                                <div class="form-group ">
                                    <label for="merk">Merk</label>
                                    <input name="merk" type="text"
                                        class="form-control form-control-user <?= ($validation->hasError('merk')) ? 'is-invalid' : ''; ?>"
                                        id="input-merk" placeholder="Masukkan Merk Barang" />
                                    <div id="merkFeedback" class="invalid-feedback">
                                        <?= $validation->getError('merk'); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">

                                <div class="form-group">
                                    <label for="nama_barang">Jenis Barang</label>
                                    <select name="jenis_barang"
                                        class="form-control form-control-user <?= ($validation->hasError('jenis_barang')) ? 'is-invalid' : ''; ?>"
                                        id="input-jenis_barang">
                                        <option value="">Pilih Jenis Barang</option>
                                        <option value="obat" <?= old('jenis_barang') == 'obat' ? 'selected' : ''; ?>>Obat
                                        </option>
                                        <option value="bahan_pokok" <?= old('jenis_barang') == 'bahan_pokok' ? 'selected' : ''; ?>>Bahan
                                            Pokok</option>
                                        <option value="atk" <?= old('jenis_barang') == 'atk' ? 'selected' : ''; ?>>ATK
                                        </option>
                                        <option value="sabun" <?= old('jenis_barang') == 'sabun' ? 'selected' : ''; ?>>Sabun
                                        </option>
                                        <option value="shampo" <?= old('jenis_barang') == 'shampo' ? 'selected' : ''; ?>>shampo
                                        </option>

                                        <option value="minuman" <?= old('jenis_barang') == 'minuman' ? 'selected' : ''; ?>>Minuman
                                        </option>
                                        <option value="snack" <?= old('jenis_barang') == 'snack' ? 'selected' : ''; ?>>Snack
                                        </option>
                                        <option value="perlengkapan" <?= old('jenis_barang') == 'perlengkapan' ? 'selected' : ''; ?>>Perlengkapan
                                        </option>
                                        <option value="gas" <?= old('jenis_barang') == 'gas' ? 'selected' : ''; ?>>Gas
                                        </option>
                                        <option value="galon" <?= old('jenis_barang') == 'galon' ? 'selected' : ''; ?>>Galon
                                        </option>
                                    </select>
                                    <div id="jenis_barangFeedback" class="invalid-feedback">
                                        <?= $validation->getError('jenis_barang'); ?>
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