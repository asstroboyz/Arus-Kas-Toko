<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Laba Rugi - TOKO HERA</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            border: 1px solid #ccc;
            padding: 20px;
            background-color: #f9f9f9;
            position: relative;
            min-height: 90vh;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h2,
        .header p {
            margin: 5px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table,
        th,
        td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .total {
            font-weight: bold;
        }

        .text-right {
            text-align: right;
        }

        .footer-table {
            width: 100%;
            margin-top: 20px;
            border: none;
        }

        .footer-table td {
            padding: 0;
            border: none;
        }

        .footer-left {
            text-align: left;
        }

        .footer-right {
            text-align: right;
        }

        .header-content {
            border: none;
        }

        .logo-container {
            border: none;
        }

        .hr-custom {
            border: none;
            border-top: 2px solid #000;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <table style="width: 100%; border: none;">
            <tr style="border: none;">
                <td class="logo-container">
                    <img src="assets/img/hera.png" width="10%" height="10%" alt="Logo BPS Kota A">
                </td>
                <td style="text-align: center; border: none;">
                    <div class="header-content">
                        <h3 class="kop" style="font-size: 25px; font-weight: bold; margin: 0; width: fit-content;">
                            TOKO HERA NOLOKERTO
                        </h3>
                        <h4 class="kop" style="font-size: 25px; font-weight: bold; margin: 0; width: fit-content;">
                            LAPORAN ARUS KAS
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
        <table>
            <tr>
                <th colspan="2">Pemasukan</th>
                <th colspan="2">Pengeluaran</th>
            </tr>
            <tr>
                <td>Pemasukan</td>
                <td class="right-align"> Rp.
                    <?= number_format(floatval($totalKasMasuk), 0, ',', '.'); ?>
                </td>

                <td>Pengeluaran</td>
                <td class="right-align"> Rp.
                    <?= number_format(floatval($totalKasKeluar), 0, ',', '.'); ?>
                </td>

                </td>
            </tr>


            <tr class="total">
                <td><b>Total Pemasukan</b></td>
                <td class="right-align"><b>Rp.
                        <b>Rp.
                            <?= number_format(floatval($totalKasMasuk), 0, ',', '.'); ?></b>
                </td>
                <td><b>Total Pengeluaran </b></td>
                <td class="right-align"><b>Rp.
                        <?= number_format(floatval($totalKasKeluar), 0, ',', '.'); ?></b>
                </td>
            </tr>
            <tr>
                <th colspan="2">Total</th>
                <th colspan="2"></th>
            </tr>

            <tr class="total">
                <td><b></b></td>
                <td class="right-align">
                </td>
                <td> </td>
                <td>Rp. <?= number_format(floatval($totalKasMasuk - $totalKasKeluar), 0, ',', '.'); ?></b>
                </td>
            </tr>
        </table>

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
                    Pemilik :<?= esc($pemilikName); ?>

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