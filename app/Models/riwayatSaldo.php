<?php

namespace App\Models;

use CodeIgniter\Model;

class riwayatSaldo extends Model
{
    protected $table = 'riwayat_saldo';
    protected $primaryKey = 'id';
    protected $useSoftDeletes = true;
    protected $allowedFields = ['id', 'tanggal', 'jenis_transaksi', 'jumlah', 'saldo_awal', 'saldo_akhir', 'saldo_id', ];

    // Fungsi untuk menambahkan transaksi Saldo masuk
    public function tambahSaldoMasuk($data)
    {
        return $this->insert($data);
    }

    // Fungsi untuk menambahkan transaksi Saldo keluar
    public function tambahSaldoKeluar($data)
    {
        return $this->insert($data);
    }

    // Fungsi untuk mendapatkan riwayat transaksi berdasarkan id
    public function riwayatTransaksi($id)
    {
        return $this->where('id', $id)->findAll();
    }

    // Tambahan fungsi atau logika lainnya sesuai kebutuhan
}
