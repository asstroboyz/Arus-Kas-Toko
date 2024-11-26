<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= $title; ?></title>
    <style>
        /* Global body styles */
        body { 
            font-family: 'Arial', sans-serif; 
            font-size: 12px; 
            margin: 0; 
            padding: 0; 
            background-color: #f9f9f9; /* Background light grey */
        }

        /* Contain the main content in the center */
        .container {
            width: 90%;
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff; /* White background for the note */
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Title style */
        h2 {
            text-align: center;
            font-size: 18px;
            color: #333;
            margin-bottom: 20px;
        }

        /* Table styling */
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 20px;
        }

        th, td { 
            padding: 8px; 
            text-align: left; 
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #4CAF50;
            color: #fff;
            font-weight: bold;
        }

        td {
            background-color: #f9f9f9;
        }

        .total {
            font-size: 16px;
            font-weight: bold;
            color: #333;
        }

        .note {
            font-size: 12px;
            color: #555;
            margin-top: 20px;
        }

        /* Footer with a clear line */
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #777;
        }

        .footer hr {
            margin: 10px 0;
            border: none;
            border-top: 1px solid #ddd;
        }

        .footer span {
            display: block;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>NOTA TAGIHAN - VIP NET</h2>
        <table>
            <tr>
                <td><strong>Nama Pelanggan</strong></td>
                <td><?= $tagihan['nama']; ?></td>
            </tr>
            <tr>
                <td><strong>Alamat</strong></td>
                <td><?= $tagihan['alamat']; ?></td>
            </tr>
            <tr>
                <td><strong>No HP</strong></td>
                <td><?= $tagihan['no_hp']; ?></td>
            </tr>
            <tr>
                <td><strong>NIK</strong></td>
                <td><?= $tagihan['nik']; ?></td>
            </tr>
            <tr>
                <td><strong>Nama Paket</strong></td>
                <td><?= $tagihan['nama_paket']; ?></td>
            </tr>
            <tr>
                <td><strong>Harga Paket</strong></td>
                <td>Rp. <?= number_format($tagihan['harga'], 0, ',', '.'); ?></td>
            </tr>
            <tr>
                <td><strong>Status Tagihan</strong></td>
                <td><?= $tagihan['status_tagihan']; ?></td>
            </tr>
            <tr>
                <td><strong>Tanggal Tagihan</strong></td>
                <td><?= date('d-m-Y', strtotime($tagihan['tanggal_tagihan'])); ?></td>
            </tr>
            <tr>
                <td><strong>Jumlah Tagihan</strong></td>
                <td><span class="total">Rp. <?= number_format($tagihan['jumlah_tagihan'], 0, ',', '.'); ?></span></td>
            </tr>
        </table>

        <p class="note">
            Terima kasih telah menggunakan layanan VIP NET. Jika ada pertanyaan, silakan hubungi kami melalui nomor yang tercantum.
        </p>

        <div class="footer">
            <hr>
            <span>&copy; 2024 VIP NET. Semua hak cipta dilindungi.</span>
        </div>
    </div>
</body>
</html>
