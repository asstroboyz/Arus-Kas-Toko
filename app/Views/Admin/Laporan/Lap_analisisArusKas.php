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
                        <?= $totalPenjualanTahunSebelumnya; ?>
                    </td>
                    <td>Rp.
                        <?= $totalPenjualan; ?>
                    </td>
                    <td><?= $totalPenjualan - $totalPenjualanTahunSebelumnya >= 0 ? 'Positif' : 'Negatif'; ?>
                    </td>
                </tr>
                <tr>
                    <td>Pembayaran Pembelian Barang</td>
                    <td>Rp.
                        <?= $totalHargaBeliTahunSebelumnya; ?>
                    </td>
                    <td>Rp.
                        <?= $totalHargaBeli; ?>
                    </td>
                    <td><?= $totalHargaBeli - $totalHargaBeliTahunSebelumnya >= 0 ? 'Positif' : 'Negatif'; ?>
                    </td>
                </tr>
                <tr>
                    <td>Pembayaran Biaya Operasional</td>
                    <td>Rp.
                        <?= $totalBiayaOperasionalTahunSebelumnya; ?>
                    </td>
                    <td>Rp.
                        <?= $totalBiayaOperasional; ?>
                    </td>
                    <td><?= $totalBiayaOperasional - $totalBiayaOperasionalTahunSebelumnya >= 0 ? 'Positif' : 'Negatif'; ?>
                    </td>
                </tr>
                <tr class="subtotal">
                    <td>Total Aktivitas Operasional</td>
                    <td>Rp.
                        <?= $totalAktivitasOperasionalTahunSebelumnya; ?>
                    </td>
                    <td>Rp.
                        <?= $totalAktivitasOperasional; ?>
                    </td>
                    <td><?= $totalAktivitasOperasional - $totalAktivitasOperasionalTahunSebelumnya >= 0 ? 'Positif' : 'Negatif'; ?>
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
                        <?= $totalPenerimaanAsetTetapTahunSebelumnya; ?>
                    </td>
                    <td>Rp.
                        <?= $totalPenerimaanAsetTetap; ?>
                    </td>
                    <td><?= $totalPenerimaanAsetTetap - $totalPenerimaanAsetTetapTahunSebelumnya >= 0 ? 'Positif' : 'Negatif'; ?>
                    </td>
                </tr>
                <tr>
                    <td>Pembayaran Pembelian Aset Tetap</td>
                    <td>Rp.
                        <?= $totalPembayaranAsetTetapTahunSebelumnya; ?>
                    </td>
                    <td>Rp.
                        <?= $totalPembayaranAsetTetap; ?>
                    </td>
                    <td><?= $totalPembayaranAsetTetap - $totalPembayaranAsetTetapTahunSebelumnya >= 0 ? 'Positif' : 'Negatif'; ?>
                    </td>
                </tr>
                <tr class="subtotal">
                    <td>Total Aktivitas Investasi</td>
                    <td>Rp.
                        <?= $totalAktivitasInvestasiTahunSebelumnya; ?>
                    </td>
                    <td>Rp.
                        <?= $totalAktivitasInvestasi; ?>
                    </td>
                    <td><?= $totalAktivitasInvestasi - $totalAktivitasInvestasiTahunSebelumnya >= 0 ? 'Positif' : 'Negatif'; ?>
                    </td>
                </tr>
                <tr class="total">
                    <td>Arus Kas Bersih dari Operasi dan Investasi</td>
                    <td>Rp.
                        <?= $totalArusKasTahunSebelumnya; ?>
                    </td>
                    <td>Rp.
                        <?= $totalArusKas; ?>
                    </td>
                    <td><?= $totalArusKas - $totalArusKasTahunSebelumnya >= 0 ? 'Positif' : 'Negatif'; ?>
                    </td>
                </tr>
                <tr>
                    <td>Kas Awal</td>
                    <td>Rp.
                        <?= $kasAwalTahunSebelumnya; ?>
                    </td>
                    <td>Rp.
                        <?= $kasAwal; ?>
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td>Kas Akhir</td>
                    <td>Rp.
                        <?= $kasAkhirTahunSebelumnya; ?>
                    </td>
                    <td>Rp.
                        <?= $kasAkhir; ?>
                    </td>
                    <td></td>
                </tr>
            </table>

        </div>
        <table class="footer-table">
            <tr>
                <td class="footer-left">
                    Dicetak Oleh: <?= user()->fullname; ?> (Admin)
                    <br>
                    <p>Kaliwungu,
                        <?= date('d/m/Y H:i:s'); ?>
                    </p>
                </td>
                <td class="footer-right">
                             Pemilik : <?= $pemilikName; ?>

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