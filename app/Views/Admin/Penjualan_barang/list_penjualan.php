<?= $this->extend('Admin/Templates/Index'); ?>

<?= $this->section('page-content'); ?>
<div class="container-fluid">

    <!-- Page Heading -->


    <div class="row">
        <div class="col-lg">
            <div class="card-header">
                <h6 class="font-weight-bold text-primary">Detail Penjualan Barang</h6>
            </div>
            <div class="card shadow p-3">
                <div class="row col-lg-12 mx-2">
                    <div class="col-lg-2">
                        <div class="form-group">
                            <label for="tanggal_penjualan">Tanggal penjualan</label>
                            <input name="tanggal_penjualan" type="text" class="form-control form-control-user"
                                id="input-tanggal_penjualan"
                                value="<?= date('d-m-Y', strtotime($penjualan['tanggal_penjualan'])); ?>"
                                readonly />
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-group">
                            <label for="status_piutang">Metode</label>
                            <input name="status_piutang" type="text" class="form-control form-control-user"
                                id="input-status_piutang"
                                value="<?php echo ($penjualan['status_piutang'] == 'lunas') ? 'Lunas' : 'Belum Lunas'; ?>"
                                readonly />
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-group">
                            <label for="nama_pelanggan">Nama Pelanggan</label>
                            <!-- Ganti dengan nama pelanggan -->
                            <input name="nama_pelanggan" type="text" class="form-control form-control-user"
                                id="input-nama_pelanggan"
                                value="<?= $pelanggan['nama']; ?>"
                                readonly />
                        </div>
                    </div>

                    <div class="col-lg-3">
                        <div class="form-group">
                            <label for="total_penjualan">Total Penjualan</label>
                            <input name="total_penjualan" type="text" class="form-control form-control-user"
                                id="input-total_penjualan"
                                value="Rp<?= number_format($penjualan['total_penjualan'], 0, ',', '.'); ?>"
                                readonly />
                        </div>
                    </div>
                </div>

                <!-- Tampilkan detail penjualan -->
                <div class="row col-lg-12 mx-2 table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Barang</th>
                                <th>Jumlah</th>
                                <th>Satuan</th>
                                <th>Sub Total</th>
                                <!-- <th>Opsi</th> -->
                            </tr>
                        </thead>

                        <tbody>
                            <?php if ($detail_penjualan) { ?>
                            <?php foreach ($detail_penjualan as $num => $detail) { ?>
                            <tr>
                                <td><?= $num + 1; ?></td>
                                <!-- Ganti nama barang -->
                                <td><?= $detail['nama_brg']; ?>
                                </td>
                                <td><?= $detail['jumlah']; ?>
                                </td>
                                <td><?= $detail['nama_satuan']; ?>
                                </td>
                                <td>Rp<?= number_format($detail['sub_total'], 0, ',', '.'); ?>
                                </td>
                                <!-- <td style="text-align: center; width: 150px;">
                                    <a href="/PenjualanBarangCont/detailpermin/<?= $detail['id_penjualan_barang'] ?>"
                                        class="btn btn-danger"><i class="fas fa-trash"></i></a>
                                    <a href="/PenjualanBarangCont/ubah/<?= $detail['id_penjualan_barang'] ?>"
                                        class="btn btn-warning"><i class="fas fa-edit"></i></a>

                                </td> -->

                            </tr>
                            <?php } ?>
                            <?php } else { ?>
                            <tr>
                                <td colspan="6">
                                    <h3 class="text-gray-900 text-center">Data belum ada.</h3>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                <br>
                <a href="<?= base_url('PenjualanBarangCont'); ?>"
                    class="btn btn-secondary">&laquo; Kembali ke
                    daftar penjualan
                    barang
                </a>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection('page-content'); ?>