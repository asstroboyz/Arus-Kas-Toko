
<?= $this->extend('Kasir/Templates/Index') ?>

<?= $this->section('page-content'); ?>

<div class="container-fluid">
    <div class="row">
        <!-- Kartu informasi seperti yang sudah ada -->
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
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-black shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-black text-uppercase mb-1">
                                Stok Dibawah 10
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-black">
                                <?= $stokdibawah10; ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-box fa-2x text-black"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-black shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-black text-uppercase mb-1">
                                Total Penjualan 24 Jam
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-black">
                                <?= $totalPenjualan24Jam; ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-black"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
  
       <div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-black shadow h-100 py-2">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-black text-uppercase mb-1">
                        Saldo Saat Ini
                    </div>
                    <div class="h5 mb-0 font-weight-bold text-black">
                        Rp.
                        <?=number_format($saldo_terakhir, 0, ',', '.');?>
                    </div>
                </div>
                <div class="col-auto">
                    <i class="fas fa-money-check-alt fa-2x text-black"></i>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-black shadow h-100 py-2">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-black text-uppercase mb-1">
                        Saldo Kas Masuk
                    </div>
                    <div class="h5 mb-0 font-weight-bold text-black">
                        Rp.
                        <?=number_format($totalKasMasuk, 0, ',', '.');?>
                    </div>
                </div>
                <div class="col-auto">
                    <i class="fas fa-arrow-down fa-2x text-success"></i>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-black shadow h-100 py-2">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-black text-uppercase mb-1">
                        Saldo Kas Keluar
                    </div>
                    <div class="h5 mb-0 font-weight-bold text-black">
                        Rp.
                        <?=number_format($totalKasKeluar, 0, ',', '.');?>
                    </div>
                </div>
                <div class="col-auto">
                    <i class="fas fa-arrow-up fa-2x text-danger"></i>
                </div>
            </div>
        </div>
    </div>
</div>

        <!-- Tambahkan canvas untuk grafik penjualan -->

        <!-- <div class="col-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Grafik Penjualan</h6>
                </div>
                <div class="card-body">
                    <canvas id="salesChart" style="max-height: 300px;"></canvas>
                </div>
            </div>
        </div> -->

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', (event) => {
        var ctx = document.getElementById('salesChart').getContext('2d');
        var salesData = <?= json_encode($dataPenjualan); ?> ;

        var labels = salesData.map(function(e) {
            return e.tanggal_penjualan; // Ganti dengan field tanggal penjualan
        });
        var data = salesData.map(function(e) {
            return e.total_penjualan; // Ganti dengan field total penjualan
        });

        var bgColor = getRandomColorArray(labels.length);

        var chart = new Chart(ctx, {
            type: 'pie', // Jenis chart: bar, line, pie, dll.
            data: {
                labels: labels,
                datasets: [{
                    label: 'Total Penjualan Rp.',
                    data: data,
                    backgroundColor: bgColor,
                    borderColor: 'rgba(255, 255, 255, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });

    // Fungsi untuk menghasilkan array warna acak
    function getRandomColorArray(numColors) {
        var colors = [];
        for (var i = 0; i < numColors; i++) {
            var color = getRandomColor();
            colors.push(color);
        }
        return colors;
    }

    // Fungsi untuk mendapatkan warna acak
    function getRandomColor() {
        var letters = '0123456789ABCDEF';
        var color = '#';
        for (var i = 0; i < 6; i++) {
            color += letters[Math.floor(Math.random() * 16)];
        }
        return color;
    }
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