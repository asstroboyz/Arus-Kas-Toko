<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= $title; ?></title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 8px; text-align: left; border: 1px solid #ddd; }
        th { background-color: #f4f4f4; }
    </style>
</head>
<body>
    <h2><?= $title; ?></h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Pelanggan</th>
                <th>Alamat</th>
                <th>No HP</th>
                <th>NIK</th>
                <th>Nama Paket</th>
                <th>Harga Paket</th>
                <th>Status Tagihan</th>
                <th>Tanggal Tagihan</th>
                <th>Jumlah Tagihan</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; ?>
            <?php foreach ($tagihan as $t): ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= $t['nama']; ?></td>
                    <td><?= $t['alamat']; ?></td>
                    <td><?= $t['no_hp']; ?></td>
                    <td><?= $t['nik']; ?></td>
                    <td><?= $t['nama_paket']; ?></td>
                    <td>Rp. <?= number_format($t['harga'], 0, ',', '.'); ?></td>
                    <td><?= $t['status_tagihan']; ?></td>
                    <td><?= date('d-m-Y', strtotime($t['tanggal_tagihan'])); ?></td>
                    <td>Rp. <?= number_format($t['jumlah_tagihan'], 0, ',', '.'); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
