<?= $this->extend('Admin/Templates/Index'); ?>

<?= $this->section('page-content'); ?>
<?php

use App\Models\TagihanModel;

$tagihanModel = new TagihanModel();
?>
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-900"></h1>

    <?php if (session()->getFlashdata('error-msg')): ?>
        <div class="row">
            <div class="col-12">
                <div class="alert alert-danger" role="alert">
                    <?= session()->getFlashdata('error-msg'); ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('msg')): ?>
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
                    <h3>Daftar Pembayaran Wifi</h3>
                    <!-- <div>
                        <a href="<?php echo base_url('TagihanCont/tambahForm/'); ?>"
                            class="btn btn-primary">
                            <i class="fa fa-plus"></i> Tambah Tagihan
                        </a>
                        <a href="<?php echo base_url('TagihanCont/arsipTagihan/'); ?>"
                            class="btn btn-success">
                            <i class="fa fa-archive"></i> Arsip Tagihan
                        </a>
                    </div> -->
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th style="width: 5%;">No</th>
                                    <th style="width: 15%;">Nama</th>
                                    <th style="width: 15%;">Nama Paket</th>
                                    <th style="width: 10%;">Tanggal Tagihan</th>
                                    <th style="width: 10%;">Jumlah Tagihan</th>
                                    <th style="width: 10%;">Status</th>
                                    <th style="width: 20%;">Alamat</th>
                                    <th style="width: 15%;">Opsi</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th style="width: 5%;">No</th>
                                    <th style="width: 15%;">Nama</th>
                                    <th style="width: 15%;">Nama Paket</th>
                                    <th style="width: 10%;">Tanggal Tagihan</th>
                                    <th style="width: 10%;">Jumlah Tagihan</th>
                                    <th style="width: 10%;">Status</th>
                                    <th style="width: 20%;">Alamat</th>
                                    <th style="width: 15%;">Opsi</th>
                                </tr>
                            </tfoot>
                            <tbody>
                                <?php if ($tagihan) { ?>
                                    <?php foreach ($tagihan as $num => $data): ?>
                                        <tr>
                                            <td><?= $num + 1; ?></td>
                                            <td><?= $data['nama']; ?></td> <!-- Nama Pelanggan -->
                                            <td><?= $data['nama_paket']; ?></td>
                                            <td><?= date('d-m-Y', strtotime($data['tanggal_tagihan'])); ?></td>
                                            <td>Rp <?= number_format(floatval($data['jumlah_tagihan']), 0, ',', '.'); ?></td>
                                            <td>
                                                <?php
                                                $icon = '';
                                                $tooltip = '';

                                                if ($data['status_tagihan'] === 'Dibayar') {
                                                    $icon = '<i class="fas fa-check-circle"></i>'; // Check icon
                                                    $tooltip = 'Status: Lunas';
                                                    echo '<span style="display: flex; align-items: center; background-color: #28a745; color: white; padding: 8px 12px; border-radius: 5px; cursor: pointer; text-align: center;" data-toggle="tooltip" title="' . $tooltip . '">' . $icon . '<span style="margin-left: 8px;">Lunas</span></span>';
                                                } elseif ($data['status_tagihan'] === 'Belum Dibayar') {
                                                    $icon = '<i class="fas fa-clock"></i>'; // Clock icon
                                                    $tooltip = 'Status: Belum Lunas';
                                                    echo '<span style="display: flex; align-items: center; background-color: #ffc107; color: #212529; padding: 8px 12px; border-radius: 5px; cursor: pointer; text-align: center;" data-toggle="tooltip" title="' . $tooltip . '">' . $icon . '<span style="margin-left: 8px;">Belum Lunas</span></span>';
                                                }
                                                ?>
                                            </td>
                                            <td><?= $data['alamat']; ?></td>
                                            <td style="text-align:center;">
                                                <?php

                                                $message = "Halo, saya ingin menanyakan pembayaran bulanan wifi di VIP NET dengan rincian berikut:\n";
                                                $message .= "Paket: " . $data['nama_paket'] . "\n";
                                                $message .= "Nama Pelanggan: " . $data['nama'] . "\n";
                                                $message .= "Jumlah Tagihan: Rp " . number_format(floatval($data['jumlah_tagihan']), 0, ',', '.') . "\n";

                                                $encodedMessage = urlencode($message);
                                                ?>
                                                <a href="https://wa.me/<?= $data['no_hp']; ?>?text=<?= $encodedMessage; ?>"
                                                    target="_blank"" class=" btn btn-success"> <i class="fab fa-whatsapp"></i></i> </a>
                                                <a href="#" class="btn btn-primary " data-toggle="modal" data-target="#modalKonfirmasiBayar" data-id="<?= $data['id'] ?>" title="Pembayaran Tagihan">
                                                    <i class="fa fa-credit-card"></i>
                                                </a>
                                                <a href="<?= base_url('admin/cetakTagihanById/' . $data['id']); ?>" class="btn btn-danger btn-delete">
                                                    <i class="fa fa-print"></i>
                                                </a>
                                            </td>
                                            <!-- <!-- <td> -->
                                            <?php

                                            // $message = "Halo, saya ingin menanyakan pembayaran bulanan wifi di VIP NET dengan rincian berikut:\n";
                                            // $message .= "Paket: " . $data['nama_paket'] . "\n";
                                            // $message .= "Nama Pelanggan: " . $data['nama'] . "\n";
                                            // $message .= "Jumlah Tagihan: Rp " . number_format(floatval($data['jumlah_tagihan']), 0, ',', '.') . "\n";

                                            // $encodedMessage = urlencode($message);
                                            ?>
                                            <!-- <a href="https://wa.me/<?= $data['no_hp']; ?>?text=<?= $encodedMessage; ?>"
                                                    target="_blank" class="btn btn-success btn-sm " title="Hubungi via WhatsApp">
                                                    <i class="fab fa-whatsapp"></i></i>
                                                </a>


                                                <a href="#" class="btn btn-primary btn-sm " data-toggle="modal" data-target="#modalKonfirmasiDelete" data-id="<?= $data['id'] ?>" title="Pembayaran Tagihan">
                                                    <i class="fa fa-credit-card"></i>
                                                </a>
                                                <a href="<?= base_url('admin/cetakTagihanById/' . $data['id']); ?>" class="btn btn-warning btn-sm " title="Cetak Struk">
                                                    <i class="fa fa-print"></i>
                                                </a>

                                            </td> -->


                                        </tr>
                                    <?php endforeach; ?>
                                <?php } else { ?>
                                    <tr>
                                        <td colspan="8">
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

<div class="modal fade" id="modalKonfirmasiBayar" tabindex="-1" role="dialog" aria-labelledby="modalKonfirmasiBayarLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalKonfirmasiBayarLabel">Konfirmasi Pembayaran</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin membayar tagihan ini?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <a id="bayarLink" class="btn btn-primary" href="#">Bayar</a>
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
    $('#modalKonfirmasiBayar').on('show.bs.modal', function(e) {
        var id = $(e.relatedTarget).data('id');
        $('#bayarLink').attr('href', '<?= base_url("/Admin/bayartagihan/") ?>' + '/' + id);
    });
</script>

<?= $this->endSection(); ?>