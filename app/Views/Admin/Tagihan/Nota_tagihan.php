<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= $title; ?></title>
    <style>
        body { 
            font-family: 'Arial', sans-serif; 
            font-size: 9px; 
            margin: 0; 
            padding: 0; 
        }

        .container {
            width: 100%;
            margin: 0 auto;
            padding: 2px;
        }

        h2 {
            text-align: center;
            font-size: 10px;
            margin: 2px 0;
        }

        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin: 5px 0; 
        }

        th, td { 
            padding: 1px 2px; 
            vertical-align: top;
        }

        th {
            font-weight: normal;
            text-align: left;
        }

        td {
            text-align: left;
        }

        .total {
            font-size: 10px;
            font-weight: bold;
            color: #333;
            text-align: right;
        }

        .note {
            font-size: 8px;
            color: #555;
            margin: 5px 0;
            text-align: center;
        }

        .footer {
            margin-top: 3px;
            text-align: center;
            font-size: 8px;
            color: #777;
        }

        .footer hr {
            margin: 3px 0;
            border: none;
            border-top: 1px solid #ddd;
        }

        .footer span {
            margin-top: 2px;
        }

        .separator {
            border-bottom: 1px solid #ddd;
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>NOTA TAGIHAN - VIP NET</h2>
        <div class="separator"></div>
        <table>
            <tr>
                <th>Nama Pelanggan</th>
                <td><?= $tagihan['nama']; ?></td>
            </tr>
            <tr>
                <th>Alamat</th>
                <td><?= $tagihan['alamat']; ?></td>
            </tr>
            <tr>
                <th>No HP</th>
                <td><?= $tagihan['no_hp']; ?></td>
            </tr>
            <tr>
                <th>Nama Paket</th>
                <td><?= $tagihan['nama_paket']; ?></td>
            </tr>
            <tr>
                <th>Harga Paket</th>
                <td>Rp. <?= number_format($tagihan['harga'], 0, ',', '.'); ?></td>
            </tr>
            <tr>
                <th>Status Tagihan</th>
                <td><?= $tagihan['status_tagihan']; ?></td>
            </tr>
            <tr>
                <th>Tanggal Tagihan</th>
                <td><?= date('d-m-Y', strtotime($tagihan['tanggal_tagihan'])); ?></td>
            </tr>
            <tr>
                <th>Jumlah Tagihan</th>
                <td><span class="total">Rp. <?= number_format($tagihan['jumlah_tagihan'], 0, ',', '.'); ?></span></td>
            </tr>
        </table>

        <p class="note">
            Terima kasih telah menggunakan layanan VIP NET.
        </p>

        <div class="footer">
            <hr>
            <span>&copy; 2024 VIP NET</span>
        </div>
    </div>
</body>
</html>
