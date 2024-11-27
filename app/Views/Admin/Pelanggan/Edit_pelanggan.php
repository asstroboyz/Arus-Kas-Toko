<?= $this->extend('Admin/Templates/Index') ?>

<?= $this->section('page-content'); ?>
<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-900">Form Edit Data Pelanggan</h1>

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
                    <a href="/Admin/pelanggan">&laquo; Kembali ke daftar Pelanggan</a>
                </div>
                <div class="card-body">
    <form action="/Admin/updatePelanggan" method="post" enctype="multipart/form-data">
        <?= csrf_field(); ?>
        <input type="hidden" name="id_pelanggan" value="<?= $pelanggan['id']; ?>">
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
                    <label for="no_hp">Kontak Pelanggan</label>
                    <input name="no_hp" type="number"
                        class="form-control <?= ($validation->hasError('no_hp')) ? 'is-invalid' : ''; ?>"
                        id="input-kontak"
                        value="<?= $pelanggan['no_hp']; ?>" />
                    <div class="invalid-feedback">
                        <?= $validation->getError('no_hp'); ?>
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
                <div class="form-group">
                    <label for="foto_ktp">Foto KTP</label>
                    <input name="foto_ktp" type="file"
                        class="form-control <?= ($validation->hasError('foto_ktp')) ? 'is-invalid' : ''; ?>"
                        id="input-foto-ktp">
                    <div class="invalid-feedback">
                        <?= $validation->getError('foto_ktp'); ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="nik">NIK</label>
                    <input name="nik" type="text"
                        class="form-control <?= ($validation->hasError('nik')) ? 'is-invalid' : ''; ?>"
                        id="input-nik"
                        value="<?= $pelanggan['nik']; ?>" />
                    <div class="invalid-feedback">
                        <?= $validation->getError('nik'); ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="kode_paket">Kode Paket</label>
                    <input name="kode_paket" type="text"
                        class="form-control <?= ($validation->hasError('kode_paket')) ? 'is-invalid' : ''; ?>"
                        id="input-kode-paket"
                        value="<?= $pelanggan['kode_paket']; ?>" />
                    <div class="invalid-feedback">
                        <?= $validation->getError('kode_paket'); ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="tgl_pasang">Tanggal Pasang</label>
                    <input name="tgl_pasang" type="date"
                        class="form-control <?= ($validation->hasError('tgl_pasang')) ? 'is-invalid' : ''; ?>"
                        id="input-tgl-pasang"
                        value="<?= $pelanggan['tgl_pasang']; ?>" />
                    <div class="invalid-feedback">
                        <?= $validation->getError('tgl_pasang'); ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="status_pelanggan">Status Pelanggan</label>
                    <select name="status_pelanggan"
                        class="form-control <?= ($validation->hasError('status_pelanggan')) ? 'is-invalid' : ''; ?>"
                        id="input-status-pelanggan">
                        <option value="aktif" <?= $pelanggan['status_pelanggan'] === 'aktif' ? 'selected' : ''; ?>>Aktif</option>
                        <option value="nonaktif" <?= $pelanggan['status_pelanggan'] === 'nonaktif' ? 'selected' : ''; ?>>Nonaktif</option>
                    </select>
                    <div class="invalid-feedback">
                        <?= $validation->getError('status_pelanggan'); ?>
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