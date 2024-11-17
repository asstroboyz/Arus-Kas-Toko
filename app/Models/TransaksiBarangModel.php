<?php

namespace App\Models;

use CodeIgniter\Model;

class TransaksiBarangModel extends Model
{
    protected $table = 'transaksi_barang';
    protected $primaryKey = 'id';
    protected $useSoftDeletes = true;
    protected $allowedFields = ['kode_barang', 'nama_barang', 'tanggal_barang_masuk', 'stok', 'jenis_transaksi', 'informasi_tambahan', 'jumlah_perubahan', 'tanggal_barang_keluar','harga_jual_total','keuntungan'];


    // Fungsi untuk menghapus transaksi berdasarkan ID penjualan barang
     public function deleteByPenjualanId($penjualanId)
    {
        return $this->where('id_penjualan_barang', $penjualanId)->delete();
    }
    
    // Fungsi untuk menambahkan transaksi barang masuk
    public function tambahBarangMasuk($data)
    {
        return $this->insert($data);
    }

    // Fungsi untuk menambahkan transaksi barang keluar
    public function tambahBarangKeluar($data)
    {
        return $this->insert($data);
    }

    // Fungsi untuk mendapatkan riwayat transaksi berdasarkan kode_brg
    public function riwayatTransaksi($kode_brg)
    {
        return $this->where('kode_brg', $kode_brg)->findAll();
    }

    // Tambahan fungsi atau logika lainnya sesuai kebutuhan
}
