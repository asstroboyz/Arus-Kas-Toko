<?= $this->extend('Admin/Templates/Index') ?>
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
                    <h3>Daftar Nama Paket</h3>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalTambahPaket">
                        <i class="fa fa-plus"></i> Tambah Paket
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th style="text-align:center; width: 30px;">No</th>
                                    <th>Kode Paket</th>
                                    <th>Nama Paket</th>
                                    <th>Harga</th>
                                    <th>Opsi</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Paket</th>
                                    <th>Nama Paket</th>
                                    <th>Harga</th>
                                    <th>Opsi</th>
                                </tr>
                            </tfoot>
                            <tbody>
                                <?php if ($paket) { ?>
                                    <?php foreach ($paket as $num => $data) { ?>
                                        <tr>
                                            <td><?= $num + 1; ?></td>
                                            <td><?= $data['kode_paket']; ?></td>
                                            <td><?= $data['nama_paket']; ?></td>
                                            <td>Rp <?= number_format($data['harga'], 0, ',', '.'); ?></td>
                                            <td style="text-align:center; width: 150px;">
                                                <a href="#" class="btn btn-warning btn-edit" data-toggle="modal"
                                                    data-target="#modalEditPaket"
                                                    data-paketid="<?= $data['kode_paket'] ?>"
                                                    data-nama="<?= esc($data['nama_paket']) ?>"
                                                    data-harga="<?= esc($data['harga']) ?>">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <a href="#" class="btn btn-danger btn-delete" data-toggle="modal"
                                                    data-target="#modalKonfirmasiDelete"
                                                    data-delete-url="<?= site_url('/Admin/deletePaket/' . $data['kode_paket']) ?>">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                <?php } else { ?>
                                    <tr>
                                        <td colspan="4">
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

<!-- Modal Tambah Paket -->
<div class="modal fade" id="modalTambahPaket" tabindex="-1" role="dialog" aria-labelledby="modalTambahPaketLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTambahPaketLabel">Tambah Paket</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="/Admin/savePaket" method="post">
                    <?= csrf_field() ?>
                    <div class="form-group">
                        <label for="nama_paket">Nama Paket</label>
                        <input type="text" class="form-control" id="nama_paket" name="nama_paket"
                            value="<?= old('nama_paket') ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="harga">Harga</label>
                        <input type="number" class="form-control" id="harga" name="harga" value="<?= old('harga') ?>"
                            required>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Paket -->
<div class="modal fade" id="modalEditPaket" tabindex="-1" role="dialog" aria-labelledby="modalEditPaketLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditPaketLabel">Edit Paket</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= site_url('/Admin/updatePaket/' . $data['kode_paket']) ?>"" method="post">
                <?= csrf_field() ?>
                <input type="hidden" name="kode_paket" id="edit_kode_paket">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit_nama_paket">Nama Paket</label>
                        <input type="text" class="form-control" id="edit_nama_paket" name="nama_paket" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_harga">Harga</label>
                        <input type="number" class="form-control" id="edit_harga" name="harga" required>
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
<div class="modal fade" id="modalKonfirmasiDelete" tabindex="-1" role="dialog" aria-labelledby="modalKonfirmasiDeleteLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalKonfirmasiDeleteLabel">Konfirmasi Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus paket ini?
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
        var kode_paket = $(this).data('paketid');
        var nama_paket = $(this).data('nama');
        var harga = $(this).data('harga');

        $('#edit_kode_paket').val(kode_paket);
        $('#edit_nama_paket').val(nama_paket);
        $('#edit_harga').val(harga);
    });
</script>
<?= $this->endSection(); ?>
