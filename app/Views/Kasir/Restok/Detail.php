<?= $this->extend('Kasir/Templates/Index') ?>

<?= $this->section('page-content'); ?>

<div class="container-fluid">

    <!-- Page Heading -->


    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow p-3">
                <div class="row col-lg-12 mx-2">
                    <div class="col-lg-4">
                        <label for="id_restok">Restok ID</label>
                        <input name="id_restok" type="text" class="form-control form-control-user" id="input-id_restok"
                            value="<?= $detail['restok_id']; ?>"
                            readonly />
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="tanggal">Tanggal</label>
                            <input name="tanggal" type="text" class="form-control form-control-user" id="input-tanggal"
                                value="<?= format_tanggal($detail['tanggal']); ?>"
                                readonly />
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="nama_supplier">Nama Supplier</label>
                            <input name="nama_supplier" type="text" class="form-control form-control-user"
                                id="input-nama_supplier"
                                value="<?= $detail['nama']; ?>"
                                readonly />
                        </div>
                    </div>
                </div>
                <div class="row col-lg-12 mx-2 table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Barang</th>
                                <th>Harga Satuan</th>
                                <th>Jumlah</th>
                                <th>Sub Total</th>
                                <th>Status Bayar</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            $no = 1;
foreach ($dataDetailRestok as $row) :
                            
    ?>

                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= $row['nama_brg']; ?>
                                </td>
                                <td>
                                    <?= "Rp " . number_format($row['harga_beli'], 0, ',', '.'); ?>
                                </td>
                                <td><?= $row['jumlah_restok']; ?>
                                </td>
                                <td>
                                    <?= "Rp " . number_format($row['sub_total'], 0, ',', '.'); ?>
                                </td>
                                <td><?= $row['status_bayar']; ?>
                                </td>
                            </tr>

                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" class="text-start">Total</td>
                                <td colspan="2" class="text-start">
                                    <?= "Rp " . number_format($detail['jumlah_pembayaran'], 0, ',', '.'); ?>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <br>
                <a href="/Kasir/Restok" class="btn btn-secondary">&laquo; Kembali ke daftar restok
                </a>
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
<?php
date_default_timezone_set("Asia/Jakarta");
function format_tanggal($tanggal)
{
    $bulan = array(
        1 => 'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember'
    );
    $pecahkan = explode('-', $tanggal);

    return $pecahkan[2] . ' ' . $bulan[(int)$pecahkan[1]] . ' ' . $pecahkan[0];
}
?>
<?= $this->endSection(); ?>