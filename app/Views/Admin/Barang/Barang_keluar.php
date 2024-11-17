<?= $this->extend('Admin/Templates/Index'); ?>

<?= $this->section('page-content'); ?>
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->


    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h1 class="h3 mb-4 text-gray-900"><?= $title; ?>
                    </h1>
                    <a href="<?= base_url('Admin/lap_keluar/'); ?>"
                        class="btn btn-success" target="_blank"><i class="fa fa-print"></i> Cetak </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Barang</th>
                                    <th>Merk Barang</th>

                                    <th>Tanggal Barang Keluar</th>
                                    <th>Jumlah Barang Keluar</th>
                                    <th>Harga Jual Total</th> <!-- Tambahkan kolom total harga jual -->
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1;
$totalHargaJual = 0; // Inisialisasi total harga jual
foreach ($transaksi_barang as $transaksi) : ?>
                                <tr>
                                    <td><?= $i++; ?></td>
                                    <td><?= $transaksi['nama_brg']; ?>
                                    </td>
                                    <td><?= $transaksi['merk']; ?>
                                    </td>

                                    <td><?= date('d-m-Y', strtotime($transaksi['tanggal_barang_keluar'])); ?>
                                    </td>
                                    <td><?= $transaksi['jumlah_perubahan']; ?>
                                        -
                                        <?= $transaksi['nama_satuan']; ?>
                                    </td>
                                    <?php
        // Menghitung total harga jual untuk setiap transaksi
        $hargaJualTotal = $transaksi['harga_jual'] * $transaksi['jumlah_perubahan'];
    $totalHargaJual += $hargaJualTotal; // Menambahkan ke total harga jual
    ?>
                                    <td><?= "Rp " . number_format($transaksi['harga_jual_total'], 0, ',', '.'); ?>
                                    </td>

                                </tr>
                                <?php endforeach; ?>
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- /.container-fluid -->

<?= $this->endSection(); ?>