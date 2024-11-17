<?= $this->extend('Kasir/Templates/Index') ?>

<?= $this->section('page-content'); ?>
<?php

use App\Models\TransaksiBarangModel;

$transaksiModel = new TransaksiBarangModel();
?>
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-900"></h1>

    <?php if (session()->getFlashdata('error-msg')) : ?>
    <div class="row">
        <div class="col-12">
            <div class="alert alert-danger" role="alert">
                <?= session()->getFlashdata('error-msg'); ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('msg')) : ?>
    <div class="row">
        <div class="col-12">
            <div class="alert alert-success alert-dismissible show fade" role="alert">
                <div class="alert-body">
                    <button class="close" data-dismiss>x</button>
                    <b><i class="fa fa-check"></i></b>
                    <?= session()->getFlashdata('msg'); ?>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h3>Daftar Barang</h3>
                    <div>
                        <a href="<?php echo base_url('BarangCont/tambahForm/'); ?>"
                            class="btn btn-primary">
                            <i class="fa fa-plus"></i> Tambah Barang
                        </a>
                        <a href="<?php echo base_url('BarangCont/atk_trash/'); ?>"
                            class="btn btn-success">
                            <i class="fa fa-archive"></i> Arsip Barang
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th style="width: 2%;">No</th>
                                    <th style="width: 5%">Kode Barang</th>
                                    <th style="width: 20%;">Nama Barang</th>
                                    <th style="width: 10%;">Stok</th>
                                    <th style="width: 10%;">Harga Beli</th>
                                    <th style="width: 10%;">Harga Jual</th>
                                    <th style="width: 20%;">Opsi</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th style="width: 2%;">No</th>
                                    <th style="width: 5%">Kode Barang</th>
                                    <th style="width: 20%;">Nama Barang</th>
                                    <th style="width: 10%;">Stok</th>
                                    <th style="width: 10%;">Harga Beli</th>
                                    <th style="width: 10%;">Harga Jual</th>
                                    <th style="width: 20%;">Opsi</th>
                                </tr>
                            </tfoot>

                            <tbody>
                                <?php if ($barangs) { ?>
                                <?php foreach ($barangs as $num => $data) : ?>
                                <?php $transaksiKeluar = $transaksiModel->where('kode_barang', $data['kode_barang'])->where('jenis_transaksi', 'keluar')->first(); ?>
                                <tr>
                                    <td><?= $num + 1; ?></td>
                                    <td><?= $data['kode_barang']; ?>
                                    </td>
                                    <td><?= $data['nama_brg']; ?><?= $data['merk']; ?>
                                    </td>

                                    <td><?= $data['stok'] < 10 ? '<span class="btn btn-danger text-white d-flex justify-content-center align-items-center">' . $data['stok'] . '</span>' : $data['stok']; ?>
                                        -
                                        <?= $data['nama_satuan']; ?>
                                    </td>
                                    <td>Rp
                                        <?= number_format(floatval($data['harga_beli']), 0, ',', '.'); ?>
                                    </td>
                                    <td>Rp
                                        <?= number_format(floatval($data['harga_jual']), 0, ',', '.'); ?>
                                    </td>
                                    <td>
                                       
                                        <?php if (!$transaksiKeluar) : ?>
                                        <a href="#" class="btn btn-danger btn-sm btn-block" data-toggle="modal"
                                            data-target="#modalKonfirmasiDelete"
                                            data-kodebarang="<?= $data['kode_barang'] ?>">
                                            <i class="fa fa-trash"></i> Hapus Barang
                                        </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                <?php } else { ?>
                                <tr>
                                    <td colspan="7">
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
        });
    }, 3000);
    setTimeout(function() {
        $('.alert.alert-danger').fadeOut('slow');
    }, 7000); // Ubah nilai timeout menjadi 7000 (7 detik)
    $('#modalKonfirmasiDelete').on('show.bs.modal', function(e) {
        var kodeBarang = $(e.relatedTarget).data('kodebarang');
        $('#deleteLink').attr('href',
            '<?= base_url("/BarangCont/softDelete/") ?>' +
            '/' + kodeBarang);
    });
</script>

<?= $this->endSection(); ?>