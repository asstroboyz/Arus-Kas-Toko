<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan VIP NET</title>
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
        <!-- Header Section -->
        <table style="width: 100%; border: none;">
            <tr style="border: none;">
                <td class="logo-container">
                    <!-- Add Logo Here if needed -->
                </td>
                <td style="text-align: center; border: none;">
                    <div class="header-content">
                        <h3 style="font-size: 25px; font-weight: bold; margin: 0; width: fit-content;">
                            VIP NET
                        </h3>
                        <h4 style="font-size: 25px; font-weight: bold; margin: 0; width: fit-content;">
                            LAPORAN LABA RUGI
                        </h4>
                        <br>
                        <p style="font-size: 16px; margin: 0;">
                            Jl. KKO Usman No.9, Pekuncen, Karangasem Utara, Kec. Batang, Kabupaten Batang, Jawa Tengah 51216
                        </p>
                    </div>
                </td>
            </tr>
        </table>
        <hr class="hr-custom">

        <!-- Periode Section -->
        <p style="margin-bottom: 20px;">
            <span style="width: 200px; display: inline-block;">Periode :</span>
            <?= $tanggalMulai ?> s/d <?= $tanggalAkhir ?>
        </p>

        <!-- Pendapatan Section -->
        <table>
            <tr>
                <th colspan="2">Pendapatan</th>
            </tr>
            <tr>
                <td>Pendapatan Bersih</td>
                <td class="text-right">Rp. <?= number_format((int)($totalPemasukan ?? 0), 0, ',', '.'); ?></td>
            </tr>
            <tr class="total">
                <td>Total Pendapatan</td>
                <td class="text-right">Rp. <?= number_format((int)($totalPemasukan ?? 0), 0, ',', '.'); ?></td>
            </tr>
        </table>

        <!-- Beban Section -->
        <?php if ($bayarTeknisi > 0 || $listrik > 0 || $air > 0 || $lainnya > 0): ?>
            <table>
                <tr>
                    <th colspan="2">Beban</th>
                </tr>
                <?php if ($bayarTeknisi > 0): ?>
                    <tr>
                        <td>Beban Bayar Teknisi</td>
                        <td class="text-right">Rp. <?= number_format((int)$bayarTeknisi, 0, ',', '.'); ?></td>
                    </tr>
                <?php endif; ?>
                <?php if ($listrik > 0): ?>
                    <tr>
                        <td>Beban Listrik</td>
                        <td class="text-right">Rp. <?= number_format((int)$listrik, 0, ',', '.'); ?></td>
                    </tr>
                <?php endif; ?>
                <?php if ($air > 0): ?>
                    <tr>
                        <td>Beban Air</td>
                        <td class="text-right">Rp. <?= number_format((int)$air, 0, ',', '.'); ?></td>
                    </tr>
                <?php endif; ?>
                <?php if ($lainnya > 0): ?>
                    <tr>
                        <td>Beban Lainnya</td>
                        <td class="text-right">Rp. <?= number_format((int)$lainnya, 0, ',', '.'); ?></td>
                    </tr>
                <?php endif; ?>
                <tr class="total">
                    <td>Total Beban</td>
                    <td class="text-right">Rp. <?= number_format((int)$totalPengeluaran, 0, ',', '.'); ?></td>
                </tr>
            </table>
        <?php endif; ?>

     

        <!-- Laba Section -->
        <table>
            <tr>
                <th colspan="2">Laba</th>
            </tr>
            <tr class="total">
                <td>Laba Kotor</td>
                <td class="text-right">Rp. <?= number_format((int)$labaKotor, 0, ',', '.'); ?></td>
            </tr>
            <tr class="total">
                <td>Laba Bersih</td>
                <td class="text-right">Rp. <?= number_format((int)$labaBersih, 0, ',', '.'); ?></td>
            </tr>
        </table>

        <!-- Footer Section -->
        <table class="footer-table">
            <tr>
                <td class="footer-left">
                    Dicetak Oleh: <?= user()->fullname; ?>
                    <br>
                    <p>Batang, <?= date('d/m/Y H:i:s'); ?></p>
                </td>
                <td class="footer-right">
                    Pemilik: <?= $pemilikName; ?>
                    <br>
                    <p>Batang, <?= date('d/m/Y H:i:s'); ?></p>
                </td>
            </tr>
        </table>
    </div>
</body>




</html>