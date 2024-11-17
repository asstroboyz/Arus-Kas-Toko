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
                  <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h3 class="m-0">Daftar Nama Pelanggan</h3>
        <a href="/Kasir/tambah_pelanggan" class="btn btn-primary"><i class="fa fa-plus"></i> Tambah Pelanggan</a>
    </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0"
                            style="margin-top: 20px;">
                            <thead>
                                <tr>
                                    <th style="text-align:center; width: 5%;">No</th>
                                    <th style="width: 20%;">Nama pelanggan</th>
                                    <th style="width: 20%;">Kontak pelanggan</th>
                                    <th style="width: 35%;">Alamat pelanggan</th>
                                    <th style="text-align:center; width: 8%;">Opsi</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th>Nama pelanggan</th>
                                    <th>Kontak pelanggan</th>
                                    <th>Alamat pelanggan</th>
                                    <th>Opsi</th>
                                </tr>
                            </tfoot>
                            <tbody>
                                <?php if ($pelanggan) { ?>
                                <?php foreach ($pelanggan as $num => $data) { ?>
                                <tr>
                                    <td style="text-align:center;">
                                        <?= $num + 1; ?>
                                    </td>
                                    <!-- Menggunakan white-space: nowrap agar teks tidak pindah ke baris baru -->
                                    <td style="white-space: nowrap;">
                                        <?= $data['nama']; ?>
                                    </td>
                                    <td>
                                        <?= $data['kontak']; ?>
                                    </td>
                                    <td>
                                        <?= $data['alamat']; ?>
                                    </td>
                                    <td style="text-align:center;">
                                        <a href="/Kasir/pelanggan_edit/<?= $data['id_pelanggan'] ?>"
                                            class="btn btn-warning"><i class="fa fa-edit"></i></a>
                                        <a href="#" class="btn btn-danger btn-delete" data-toggle="modal"
                                            data-target="#modalKonfirmasiDelete"
                                            data-delete-url="<?= site_url('/Kasir/pelanggan_delete/' . $data['id_pelanggan']) ?>">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php } ?>
                                <?php } else { ?>
                                <tr>
                                    <td colspan="5">
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

<div class="modal fade" id="modalKonfirmasiDelete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Konfirmasi Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus barang ini?
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
            $($this).remove();
        })

    }, 3000);
    $('.btn-delete').on('click', function(e) {
        e.preventDefault();
        var deleteUrl = $(this).data('delete-url');
        $('#deleteLink').attr('href', deleteUrl);
        $('#modalKonfirmasiDelete').modal('show');
    });
</script>
<?= $this->endSection(); ?>