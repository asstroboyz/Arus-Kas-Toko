<?php

namespace App\Models;

use CodeIgniter\Model;

class PemasukanModel extends Model
{
    protected $table = 'pemasukan';
    // protected $useTimestamps = true;
    protected $primarykey = 'id';
    protected $allowedFields = ['id', 'tanggal', 'keterangan','id_saldo','id_detail_penjualan_barang','jumlah','id_kas'];

    public function getPemasukan($id = false)
    {
        if ($id == false) {
            return $this->findAll();
        }
        return $this->where(['id' => $id])->first();
    }
    public function insertPemasukan($data)
    {
        return $this->insert($data);
    }
    public function deleteByTransaksiId($transaksiId)
    {
        return $this->where('id_transaksi', $transaksiId)->delete();
    }
}
