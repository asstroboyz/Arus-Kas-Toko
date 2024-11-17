<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Analisis Arus Kas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .container {
            width: 80%;
            margin: auto;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .hr-custom {
            height: 2px;
            background-color: #000;
            width: 100%;
            margin: 10px 0;
        }
        .table-container {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .positive {
            color: green;
        }

        .negative {
            color: red;
        }

        .footer-table {
            width: 100%;
            margin-top: 20px;
        }

        .footer-table td {
            padding: 0;
        }

        .footer-left {
            text-align: left;
        }

        .footer-right {
            text-align: right;
        }
    </style>
</head>

<body>
    <div class="container">
        <table style="width: 100%;">
            <tr>
                <td class="logo-container">
                    <img src="assets/img/hera.png" width="10%" height="10%" alt="Logo BPS Kota A">
                </td>
                <td style="text-align: center;">
                    <div class="header-content">
                        <h3 class="kop" style="font-size: 25px; font-weight: bold; margin: 0; width: fit-content;">
                            TOKO HERA NOLOKERTO
                        </h3>
                        <h4 class="kop" style="font-size: 25px; font-weight: bold; margin: 0; width: fit-content;">
                            LAPORAN ANALISA ARUS KAS
                        </h4>
                        <br>
                        <p class="kop" style="font-size: 16px; margin: 0;">
                            27J4+CJ7 Bukit Jabal, Penjor, Nolokerto, Kec. Kaliwungu, Kabupaten Kendal, Jawa Tengah 50244
                        </p>
                    </div>
                </td>
            </tr>
        </table>
        <hr class="hr-custom">
                <p style="margin-bottom: 20px;">
                            <span style="width: 200px; display: inline-block;">Periode :</span>
                            <?php echo strftime('%d-%m-%Y', strtotime($tanggalMulai)); ?>
                            Sampai Dengan
                            <?php echo strftime('%d-%m-%Y', strtotime($tanggalAkhir)); ?>
                        </p>
        <div class="table-container">
            <table>
                <tr>
                    <th>Aktivitas Operasional</th>
                    <th>Tahun
                        <?= date('Y', strtotime('-1 year')); ?>
                    </th>
                    <th>Tahun <?= date('Y'); ?>
                    </th>
                    <th>Tren</th>
                </tr>
                <tr>
                    <td>Penerimaan Penjualan</td>
                    <td>Rp.
                        <?= number_format($totalPenjualanTahunSebelumnya, 0, ',', '.'); ?>
                    </td>
                    <td>Rp.
                        <?= number_format($totalPenjualan, 0, ',', '.'); ?>
                    </td>
                    <td><?= ($totalPenjualan - $totalPenjualanTahunSebelumnya) >= 0 ? 'Positif' : 'Negatif'; ?>
                    </td>
                </tr>
                <tr>
                    <td>Pembayaran Pembelian Barang</td>
                    <td>Rp.
                        <?= number_format($totalHargaBeliTahunSebelumnya, 0, ',', '.'); ?>
                    </td>
                    <td>Rp.
                        <?= number_format($totalHargaBeli, 0, ',', '.'); ?>
                    </td>
                    <td><?= ($totalHargaBeli - $totalHargaBeliTahunSebelumnya) >= 0 ? 'Positif' : 'Negatif'; ?>
                    </td>
                </tr>
                <tr>
                    <td>Pembayaran Biaya Operasional</td>
                    <td>Rp.
                        <?= number_format($totalBiayaOperasionalTahunSebelumnya, 0, ',', '.'); ?>
                    </td>
                    <td>Rp.
                        <?= number_format($totalBiayaOperasional, 0, ',', '.'); ?>
                    </td>
                    <td><?= ($totalBiayaOperasional - $totalBiayaOperasionalTahunSebelumnya) >= 0 ? 'Positif' : 'Negatif'; ?>
                    </td>
                </tr>
                <tr class="subtotal">
                    <td>Total Aktivitas Operasional</td>
                    <td>Rp.
                        <?= number_format($totalAktivitasOperasionalTahunSebelumnya, 0, ',', '.'); ?>
                    </td>
                    <td>Rp.
                        <?= number_format($totalAktivitasOperasional, 0, ',', '.'); ?>
                    </td>
                    <td><?= ($totalAktivitasOperasional - $totalAktivitasOperasionalTahunSebelumnya) >= 0 ? 'Positif' : 'Negatif'; ?>
                    </td>
                </tr>
                <tr>
                    <th>Aktivitas Investasi</th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
                <tr>
                    <td>Penerimaan Penjualan Aset Tetap</td>
                    <td>Rp.
                        <?= number_format($totalPenerimaanAsetTetapTahunSebelumnya, 0, ',', '.'); ?>
                    </td>
                    <td>Rp.
                        <?= number_format($totalPenerimaanAsetTetap, 0, ',', '.'); ?>
                    </td>
                    <td><?= ($totalPenerimaanAsetTetap - $totalPenerimaanAsetTetapTahunSebelumnya) >= 0 ? 'Positif' : 'Negatif'; ?>
                    </td>
                </tr>
                <tr>
                    <td>Pembayaran Pembelian Aset Tetap</td>
                    <td>Rp.
                        <?= number_format($totalPembayaranAsetTetapTahunSebelumnya, 0, ',', '.'); ?>
                    </td>
                    <td>Rp.
                        <?= number_format($totalPembayaranAsetTetap, 0, ',', '.'); ?>
                    </td>
                    <td><?= ($totalPembayaranAsetTetap - $totalPembayaranAsetTetapTahunSebelumnya) >= 0 ? 'Positif' : 'Negatif'; ?>
                    </td>
                </tr>
                <tr class="subtotal">
                    <td>Total Aktivitas Investasi</td>
                    <td>Rp.
                        <?= number_format($totalAktivitasInvestasiTahunSebelumnya, 0, ',', '.'); ?>
                    </td>
                    <td>Rp.
                        <?= number_format($totalAktivitasInvestasi, 0, ',', '.'); ?>
                    </td>
                    <td><?= ($totalAktivitasInvestasi - $totalAktivitasInvestasiTahunSebelumnya) >= 0 ? 'Positif' : 'Negatif'; ?>
                    </td>
                </tr>
                <tr class="total">
                    <td>Arus Kas Bersih dari Operasi dan Investasi</td>
                    <td>Rp.
                        <?= number_format($totalArusKasTahunSebelumnya, 0, ',', '.'); ?>
                    </td>
                    <td>Rp.
                        <?= number_format($totalArusKas, 0, ',', '.'); ?>
                    </td>
                    <td><?= ($totalArusKas - $totalArusKasTahunSebelumnya) >= 0 ? 'Positif' : 'Negatif'; ?>
                    </td>
                </tr>
                <tr>
                    <td>Kas Awal</td>
                    <td>Rp.
                        <?= number_format($kasAwalTahunSebelumnya, 0, ',', '.'); ?>
                    </td>
                    <td>Rp.
                        <?= number_format($kasAwal, 0, ',', '.'); ?>
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td>Kas Akhir</td>
                    <td>Rp.
                        <?= number_format($kasAkhirTahunSebelumnya, 0, ',', '.'); ?>
                    </td>
                    <td>Rp.
                        <?= number_format($kasAkhir, 0, ',', '.'); ?>
                    </td>
                    <td></td>
                </tr>
            </table>

        </div>
        <table class="footer-table">
            <tr>
                <td class="footer-left">
                    Dicetak Oleh: <?= user()->fullname; ?> (Pemilik)
                    <br>
                    <p>Kaliwungu,
                        <?= date('d/m/Y H:i:s'); ?>
                    </p>
                </td>
                <td class="footer-right">
                             Pemilik : <?= esc($pemilikName); ?>

                    <br>
                    <p>Kaliwungu,
                        <?= date('d/m/Y H:i:s'); ?>
                    </p>
                </td>
            </tr>
        </table>
    </div>
</body>

</html>