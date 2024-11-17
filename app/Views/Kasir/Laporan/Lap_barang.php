<!DOCTYPE html>
<html lang="en">

<head>
    <title>Laporan Persediaan</title>
    <style>
        body {
            text-align: center;
            font-family: 'Times New Roman', Times, serif;
        }

        .custom-table {
            width: 100%;
            border-collapse: collapse;
            text-align: center;
        }

        .custom-table th,
        .custom-table td {
            border: 1px solid black;
            padding: 10px;
            text-align: left;
        }

        .custom-table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .container {
            width: 80%;
            margin: auto;
        }

        .hr-custom {
            height: 2px;
            background-color: #000;
            width: 100%;
            margin: 10px 0;
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
        <!-- Header -->
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
                            LAPORANA PERSEDIAAN BARANG
                        </h4>
                        <br>
                        <p class="kop" style="font-size: 16px; margin: 0;">
                            27J4+CJ7 Bukit Jabal, Penjor, Nolokerto, Kec. Kaliwungu, Kabupaten Kendal, Jawa Tengah 50244
                        </p>

                    </div>
                </td>
            </tr>
        </table>
        <!-- Horizontal line with adjusted width -->

        <p style="margin-bottom: 20px;">
            <span style="width: 200px; display: inline-block;">Periode :</span>
            <?php echo strftime('%d-%m-%Y', strtotime($tanggalMulai)); ?>
            Sampai Dengan
            <?php echo strftime('%d-%m-%Y', strtotime($tanggalAkhir)); ?>
        </p>
        <hr class="hr-custom">

        <!-- Content Section -->
        <br>
        <div style="text-align: left;">
            <!-- <p>
                <span style="width: 200px; display: inline-block;">JENIS LAPORAN :</span>
                Laporan Persedian Barang
            </p> -->

        </div>

        <!-- Table with dynamic content -->
        <table class="custom-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Barang</th>
                    <th>Nama Barang</th>
                    <th>Merk Barang</th>
                    <th>Satuan Barang</th>
                    <th>Stok</th>
                    <th>Harga Beli</th>
                    <th>Harga Jual</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($barang as $num => $row) : ?>
                <tr>
                    <td><?= $num + 1; ?></td>
                    <td><?= $row['kode_barang']; ?>
                    </td>
                    <td><?= $row['nama_brg']; ?>
                    </td>
                    <td><?= $row['merk']; ?></td>
                    <td><?= $row['nama_satuan']; ?>
                    </td>
                    <td><?= $row['stok']; ?></td>
                    <td>Rp.<?= number_format($row['harga_beli'], 0, ',', '.'); ?>
                    </td>
                    <td>Rp.<?= number_format($row['harga_jual'], 0, ',', '.'); ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <!-- End of Table -->

        <!-- Footer -->
        <table class="footer-table">
            <tr>
                <td class="footer-left">
                    Dicetak Oleh: <?= user()->fullname; ?> (Kasir)
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