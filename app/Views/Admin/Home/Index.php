<!-- app/Views/Admin/Home/Index.php -->
<?= $this->extend('Admin/Templates/Index') ?>

<?= $this->section('page-content'); ?>
<div class="container-fluid">
    <div class="row">

        <!-- Kartu Hari Ini -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-black shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-black text-uppercase mb-1">
                                Hari Ini
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-black">
                                <?= format_tanggal(date('Y-m-d')); ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-day fa-2x text-black"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kartu Saldo Saat Ini -->
       <!-- Kartu Saldo Saat Ini -->
<div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-black shadow h-100 py-2">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-black text-uppercase mb-1">
                        Saldo Saat Ini
                    </div>
                    <div class="h5 mb-0 font-weight-bold text-black">
                        Rp. <?= number_format($saldo_terakhir ?? 0, 0, ',', '.'); ?>
                    </div>
                </div>
                <div class="col-auto">
                    <i class="fas fa-wallet fa-2x text-black"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Kartu Saldo Kas Masuk -->
<div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-success shadow h-100 py-2">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                        Saldo Masuk
                    </div>
                    <div class="h5 mb-0 font-weight-bold text-success">
                        Rp. <?= number_format($totalKasMasuk ?? 0, 0, ',', '.'); ?>
                    </div>
                </div>
                <div class="col-auto">
                    <i class="fas fa-arrow-down fa-2x text-success"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Kartu Saldo Kas Keluar -->
<div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-danger shadow h-100 py-2">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                        Saldo Keluar
                    </div>
                    <div class="h5 mb-0 font-weight-bold text-danger">
                        Rp. <?= number_format($totalKasKeluar ?? 0, 0, ',', '.'); ?>
                    </div>
                </div>
                <div class="col-auto">
                    <i class="fas fa-arrow-up fa-2x text-danger"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Kartu Pelanggan Aktif -->
<div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-primary shadow h-100 py-2">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                        Pelanggan Aktif
                    </div>
                    <div class="h5 mb-0 font-weight-bold text-primary">
                        <?= $totalPelangganAktif ?? 0; ?>
                    </div>
                </div>
                <div class="col-auto">
                    <i class="fas fa-users fa-2x text-primary"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Kartu Pelanggan Tidak Aktif -->
<div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-warning shadow h-100 py-2">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                        Pelanggan Tidak Aktif
                    </div>
                    <div class="h5 mb-0 font-weight-bold text-warning">
                        <?= $totalPelangganTidakAktif ?? 0; ?>
                    </div>
                </div>
                <div class="col-auto">
                    <i class="fas fa-user-slash fa-2x text-warning"></i>
                </div>
            </div>
        </div>
    </div>
</div>


    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


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
        'Desember',
    );
    $pecahkan = explode('-', $tanggal);

    return $pecahkan[2] . ' ' . $bulan[(int) $pecahkan[1]] . ' ' . $pecahkan[0];
}
?>

<?= $this->endSection(); ?>