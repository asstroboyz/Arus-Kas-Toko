<?php

namespace App\Models;

use CodeIgniter\Model;

class PenjualanBarangModel extends Model
{
    protected $table = 'penjualan_barang';
    protected $primaryKey = 'penjualan_barang_id';
    protected $allowedFields = ['penjualan_barang_id', 'tanggal_penjualan', 'total_penjualan', 'id_pelanggan', 'jumlah_uang', 'status_piutang'];

    //baru
     public function getTotalPenjualan()
    {
        $builder = $this->db->table('penjualan_barang pb')
                            ->join('detail_penjualan_barang dp', 'pb.penjualan_barang_id = dp.id_penjualan_barang')
                            ->select('SUM(dp.sub_total) as total_penjualan');
        
        $result = $builder->get()->getRow();
        return $result ? $result->total_penjualan : 0;
    }
    
    public function getTotalPenjualanByDateRange($startDate, $endDate)
    {
        return $this->db->table($this->table)
            ->select('COALESCE(SUM(total_penjualan), 0) as total_penjualan')
            ->where('tanggal_penjualan >=', $startDate)
            ->where('tanggal_penjualan <=', $endDate)
            ->get()->getRow()->total_penjualan;
    }

    public function findPenjualanById($id)
    {
        return $this->find($id);
    }

    // Fungsi untuk menghapus penjualan barang berdasarkan ID
    public function deletePenjualan($id)
    {
        return $this->delete($id);
    }

    //baru
    public function getAllPenjualan()
    {
        return $this->findAll(); // Mengambil semua data penjualan
    }
    public function getAllSales()
    {
        return $this->orderBy('tanggal_penjualan', 'ASC')->findAll();
    }
    public function getPenjualan($id = false)
    {
        if ($id == false) {
            return $this->findAll();
        }

        return $this->where(['penjualan_barang_id' => $id])->first();
    }

    public function pelanggan()
    {
        return $this->belongsTo('App\Models\PelangganModel', 'id_pelanggan', 'id');
    }

    public function find($id = null)
    {
        if ($id === null) {
            return $this->findAll();
        }

        return $this->asArray()
            ->where(['penjualan_barang_id' => $id])
            ->first();
    }

    public function getAll()
    {
        $query = $this->table('penjualan_barang')->query('select * from penjualan_barang');
        return $query->getResult();
    }
    public function getpenjualanWithBarang()
    {
        return $this->db->table('penjualan_barang')
            ->join('barang', 'barang.kode_barang = penjualan_barang.kode_barang')
            ->get()
            ->getResultArray();
    }
}
