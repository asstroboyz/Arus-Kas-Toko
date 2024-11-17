<?= $this->extend('Kasir/Templates/Index') ?>
<?= $this->section('page-content'); ?>
<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-900"></h1>

    <?php if (session()->has('PesanBerhasil')) : ?>
    <div class="alert alert-success" role="alert">
        <?= session('PesanBerhasil') ?>
    </div>
    <?php elseif (session()->has('PesanGagal')) : ?>
    <div class="alert alert-danger" role="alert">
        <?= session('PesanGagal') ?>
    </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h3>Daftar nama satuan </h3>
                    <button type="button" class="btn btn-primary" data-toggle="modal"
                        data-target="#modalTambahSatuan"><i class="fa fa-plus"></i> Tambah Satuan</button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th style="text-align:center; width: 30px;">No</th>
                                    <th>Nama Satuan</th>
                                    <th>Opsi</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Satuan</th>
                                    <th>Opsi</th>
                                </tr>
                            </tfoot>
                            <tbody>
                                <?php if ($satuan) { ?>
                                <?php foreach ($satuan as $num => $data) { ?>
                                <tr>
                                    <td><?= $num + 1; ?></td>
                                    <td><?= $data['nama_satuan']; ?>
                                    </td>
                                    <td style="text-align:center; width: 150px;">
                                        <!-- <a href="#" class="btn btn-warning btn-edit" data-toggle="modal" data-target="#modalEditSatuan" data-satuanid="<?= $data['satuan_id'] ?>"><i
                                            class="fa fa-edit"></i> </a> -->

                                        <a href="#" class="btn btn-warning btn-edit" data-toggle="modal"
                                            data-target="#modalEditSatuan"
                                            data-satuanid="<?= $data['satuan_id'] ?>">
                                            <!-- Tambahkan data-satuanid -->
                                            <i class="fa fa-edit"></i>
                                        </a>

                                        <a href="#" class="btn btn-danger btn-delete" data-toggle="modal"
                                            data-target="#modalKonfirmasiDelete"
                                            data-delete-url="<?= site_url('/Kasir/satuan_delete/' . $data['satuan_id']) ?>"><i
                                                class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                                <?php } ?>
                                <?php } else { ?>
                                <tr>
                                    <td colspan="3">
                                        <h3 class="text-gray-900 text-center">Data belum ada.</h3>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Satuan -->
<div class="modal fade" id="modalTambahSatuan" tabindex="-1" role="dialog" aria-labelledby="modalTambahSatuanLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTambahSatuanLabel">Tambah Satuan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Form untuk menambahkan satuan -->
                <form action="/Kasir/simpanSatuan" method="post">
                    <?= csrf_field() ?>
                    <div class="form-group">
                        <label for="nama_satuan">Nama Satuan</label>
                        <input type="text" class="form-control" id="nama_satuan" name="nama_satuan"
                            value="<?= old('nama_satuan') ?>"
                            required>
                    </div>
            </div>
            <div class="modal-footer">
                <!-- Tombol Simpan -->
                <button type="submit" class="btn btn-primary">Simpan</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                </form> <!-- Penutup tag form -->
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEditSatuan" tabindex="-1" role="dialog" aria-labelledby="modalEditSatuanLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditSatuanLabel">Edit Satuan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form
                action="<?= site_url('/Kasir/updateSatuan') ?>"
                method="post"> <!-- Perbaiki action -->
                <?= csrf_field() ?>
                <!-- Input hidden untuk menyimpan ID -->
                <input type="hidden" name="id" id="edit_satuan_id">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit_nama_satuan">Nama Satuan</label>
                     <input type="text" class="form-control" id="edit_nama_satuan" name="nama_satuan" required value="<?= esc($data['nama_satuan']) ?>">

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Delete -->
<div class="modal fade" id="modalKonfirmasiDelete" tabindex="-1" role="dialog"
    aria-labelledby="modalKonfirmasiDeleteLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalKonfirmasiDeleteLabel">Konfirmasi Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus satuan ini?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <a id="deleteLink" href="#" class="btn btn-danger">Hapus</a>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('additional-js'); ?>
<script>
    window.setTimeout(function() {
        $(".alert").fadeTo(500, 0).slideUp(500, function() {
            $(this).remove();
        });
    }, 3000);

    $('.btn-delete').on('click', function(e) {
        e.preventDefault();
        var deleteUrl = $(this).data('delete-url');
        $('#deleteLink').attr('href', deleteUrl);
        $('#modalKonfirmasiDelete').modal('show');
    });
    $('.btn-edit').on('click', function() {
        var satuan_id = $(this).data('satuanid');
        // Mengisi nilai input dengan ID yang sesuai
        $('#edit_satuan_id').val(satuan_id);
    });
</script>
<?= $this->endSection(); ?>