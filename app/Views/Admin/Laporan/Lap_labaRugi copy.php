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


        footer {
            text-align: right;
            margin-top: 10px;
        }

        footer p {
            text-align: right;
            margin-bottom: 5px;
        }

        footer div {
            text-align: center;
            border-top: 1px solid #000;
            padding-top: 5px;
            margin-top: 5px;
        }

        .footer {
            text-align: right;
            position: fixed;
            bottom: 20px;
            right: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h2>LAPORAN LABA RUGI TOKO HERA</h2>
            <p>Jalan Raya Kendal Kaliwungu</p>
            <p>Periode: <?= $tanggalMulai; ?> -
                <?= $tanggalAkhir; ?>
            </p>
        </div>

        <table>
            <tr>
                <th colspan="2">Pendapatan</th>
            </tr>
            <tr>
                <td>Penjualan Bersih</td>
                <td class="text-right">Rp.
                    <?= number_format($totalPenjualan, 0, ',', '.'); ?>
                </td>
            </tr>
            <tr class="total">
                <td>Total Pendapatan</td>
                <td class="text-right">Rp.
                    <?= number_format($totalPenjualan, 0, ',', '.'); ?>
                </td>
            </tr>
        </table>

        <table>
            <tr>
                <th colspan="2">Beban</th>
            </tr>
            <tr>
                <td>Beban Penjualan</td>
                <td class="text-right">Rp.
                    <?= number_format($totalHPP, 0, ',', '.'); ?>
                </td>
            </tr>
            <tr>
                <td>Beban Gaji</td>
                <td class="text-right">Rp.
                    <?= number_format($gaji, 0, ',', '.'); ?>
                </td>
            </tr>
            <tr>
                <td>Beban Air</td>
                <td class="text-right">Rp.
                    <?= number_format($air, 0, ',', '.'); ?>
                </td>
            </tr>
            <tr>
                <td>Beban Listrik</td>
                <td class="text-right">Rp.
                    <?= number_format($listrik, 0, ',', '.'); ?>
                </td>
            </tr>
            <tr>
                <td>Beban Lain Lain</td>
                <td class="text-right">Rp.
                    <?= number_format($beliDanRestok, 0, ',', '.'); ?>
                </td>
            </tr>
            <tr class="total">
                <td>Total Beban</td>
                <td class="text-right">Rp.
                    <?= number_format($totalHPP + $gaji + $air + $listrik + $beliDanRestok, 0, ',', '.'); ?>
                </td>
            </tr>
        </table>

        <table>
            <tr>
                <th colspan="2">Laba</th>
            </tr>
            <tr class="total">
                <td>Laba Kotor</td>
                <td class="text-right">Rp.
                    <?= number_format($labaKotor, 0, ',', '.'); ?>
                </td>
            </tr>
            <tr class="total">
                <td>Laba Bersih</td>
                <td class="text-right">Rp.
                    <?= number_format($labaBersih, 0, ',', '.'); ?>
                </td>
            </tr>
        </table>

        <!-- <div class="footer">
            <br>
            <p>
                Kaliwungu, <?php echo date('d/m/Y H:i:s'); ?>
        </p>
        <br><br>
        <p>
            <?= user()->fullname; ?> &nbsp; &nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp; &nbsp;
        </p>
        <p></p>
    </div> -->
    <div class="footer">
        <p>Dibuat Oleh: <?= user()->fullname; ?> (Admin)&nbsp; </p>

        <p>Kaliwungu, <?= date('d/m/Y H:i:s'); ?>
        </p>
    </div>
    </div>
</body>

</html>