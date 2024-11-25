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
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h1 class="h3 mb-0 text-gray-900">Form Tambah Pelanggan</h1>
                    <a href="/Admin/pelanggan" class="btn btn-secondary btn-sm">&laquo; Kembali ke daftar master
                        Pelanggan</a>
                </div>
                <div class="card-body">
                    <!-- <form
                        action="<?= base_url('/Admin/simpanPelanggan') ?> "
                        method="post" enctype="multipart/form-data">
                        <?= csrf_field(); ?>
                        <div class="row">
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="nama">Nama Pelanggan</label>
                                    <input name="nama" type="text"
                                        class="form-control form-control-user <?= ($validation->hasError('nama')) ? 'is-invalid' : ''; ?>"
                                        id="input-nama" placeholder="Masukkan Nama Pelanggan"
                                        value="<?= old('nama'); ?>" />
                                    <div id="namaFeedback" class="invalid-feedback">
                                        <?= $validation->getError('nama'); ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="kontak">Kontak Pelanggan</label>
                                    <input name="kontak" type="text"
                                        class="form-control form-control-user <?= ($validation->hasError('kontak')) ? 'is-invalid' : ''; ?>"
                                        id="input-kontak" placeholder="Masukkan Kontak Pelanggan"
                                        value="<?= old('kontak'); ?>" />
                                    <div id="kontakFeedback" class="invalid-feedback">
                                        <?= $validation->getError('kontak'); ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="alamat">Alamat Pelanggan</label>
                                    <input name="alamat" type="text"
                                        class="form-control form-control-user <?= ($validation->hasError('alamat')) ? 'is-invalid' : ''; ?>"
                                        id="input-alamat" placeholder="Masukkan Alamat Pelanggan"
                                        value="<?= old('alamat'); ?>" />
                                    <div id="alamatFeedback" class="invalid-feedback">
                                        <?= $validation->getError('alamat'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-block btn-primary">Tambah Data</button>
                    </form> -->
                    <div class="card-body">
                        <form action="<?= base_url('/Admin/simpanPelanggan') ?>" method="post" enctype="multipart/form-data">
                            <?= csrf_field(); ?>
                            <div class="row">
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                    <!-- Nama Pelanggan -->
                                    <div class="form-group">
                                        <label for="nama">Nama Pelanggan</label>
                                        <input name="nama" type="text"
                                            class="form-control form-control-user <?= ($validation->hasError('nama')) ? 'is-invalid' : ''; ?>"
                                            id="input-nama" placeholder="Masukkan Nama Pelanggan" value="<?= old('nama'); ?>" />
                                        <div id="namaFeedback" class="invalid-feedback">
                                            <?= $validation->getError('nama'); ?>
                                        </div>
                                    </div>

                                    <!-- Kontak Pelanggan -->
                                    <div class="form-group">
                                        <label for="kontak">Kontak Pelanggan</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">+62</span>
                                            </div>
                                            <input name="no_hp" type="text"
                                                class="form-control form-control-user <?= ($validation->hasError('no_hp')) ? 'is-invalid' : ''; ?>"
                                                id="input-no_hp"
                                                placeholder="Masukkan nomor HP"
                                                value="<?= old('no_hp'); ?>" />
                                            <div id="no_hpFeedback" class="invalid-feedback">
                                                <?= $validation->getError('no_hp'); ?>
                                            </div>
                                        </div>
                                    </div>


                                    <!-- Alamat Pelanggan -->
                                    <div class="form-group">
                                        <label for="alamat">Alamat Pelanggan</label>
                                        <input name="alamat" type="text"
                                            class="form-control form-control-user <?= ($validation->hasError('alamat')) ? 'is-invalid' : ''; ?>"
                                            id="input-alamat" placeholder="Masukkan Alamat Pelanggan" value="<?= old('alamat'); ?>" />
                                        <div id="alamatFeedback" class="invalid-feedback">
                                            <?= $validation->getError('alamat'); ?>
                                        </div>
                                    </div>

                                    <!-- NIK Pelanggan -->
                                    <div class="form-group">
                                        <label for="nik">NIK Pelanggan</label>
                                        <input name="nik" type="text"
                                            class="form-control form-control-user <?= ($validation->hasError('nik')) ? 'is-invalid' : ''; ?>"
                                            id="input-nik" placeholder="Masukkan NIK Pelanggan" value="<?= old('nik'); ?>" />
                                        <div id="nikFeedback" class="invalid-feedback">
                                            <?= $validation->getError('nik'); ?>
                                        </div>
                                    </div>

                                    <!-- Foto KTP -->
                                    <div class="form-group">
                                        <label for="foto_ktp">Foto KTP</label>
                                        <input name="foto_ktp" type="file"
                                            class="form-control form-control-user <?= ($validation->hasError('foto_ktp')) ? 'is-invalid' : ''; ?>"
                                            id="input-foto_ktp" />
                                        <div id="foto_ktpFeedback" class="invalid-feedback">
                                            <?= $validation->getError('foto_ktp'); ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="tgl_pasang">Tanggal Pasang</label>
                                        <input name="tgl_pasang" type="date"
                                            class="form-control form-control-user <?= ($validation->hasError('tgl_pasang')) ? 'is-invalid' : ''; ?>"
                                            id="input-tgl-pasang" value="<?= old('tgl_pasang'); ?>" />
                                        <div id="tglPasangFeedback" class="invalid-feedback">
                                            <?= $validation->getError('tgl_pasang'); ?>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="status_pelanggan">Status Pelanggan</label>
                                        <select name="status_pelanggan"
                                            class="form-control form-control-user <?= ($validation->hasError('status_pelanggan')) ? 'is-invalid' : ''; ?>"
                                            id="input-status-pelanggan">
                                            <option value="aktif" <?= (old('status_pelanggan') == 'aktif') ? 'selected' : ''; ?>>Aktif</option>
                                            <option value="tidak aktif" <?= (old('status_pelanggan') == 'tidak aktif') ? 'selected' : ''; ?>>Tidak Aktif</option>
                                        </select>
                                        <div id="statusPelangganFeedback" class="invalid-feedback">
                                            <?= $validation->getError('status_pelanggan'); ?>
                                        </div>
                                    </div>
                                    <!-- Kode Paket -->
                                    <div class="form-group">
                                        <label for="kode_paket">Kode Paket</label>
                                        <select name="kode_paket[]" id="kode_paket" class="form-control select2 <?= ($validation->hasError('kode_paket')) ? 'is-invalid' : ''; ?>" required>
                                            <option value="">Pilih Paket</option>
                                            <?php foreach ($barangList as $brg): ?>
                                                <option
                                                    value="<?= $brg['kode_paket']; ?>"
                                                    data-satuan="<?= $brg['nama_paket']; ?>"
                                                    data-hj="<?= $brg['harga']; ?>"
                                                    <?= (old('kode_paket') == $brg['kode_paket']) ? 'selected' : ''; ?>>
                                                    <?= $brg['kode_paket'] . ' - ' . $brg['harga']  . ' - ' . $brg['nama_paket']; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <div id="kode_paketFeedback" class="invalid-feedback">
                                            <?= $validation->getError('kode_paket'); ?>
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