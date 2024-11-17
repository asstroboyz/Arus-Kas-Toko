<?php


namespace App\Models;

use CodeIgniter\Model;

class perkiraanModel extends Model
{
    protected $table = 'perkiraan_penjualan';
    protected $primaryKey = 'id';
    protected $useTimestamps = false;
    protected $allowedFields = ['kode_barang', 'id_satuan', 'metode_perkiraan', 'periode_perkiraan','hasil_perkiraan','created_at'];

    // public function getRamalan($id = false)
    // {
    //     if ($id == false) {
    //         return $this->findAll();
    //     }
    //     return $this->where(['id' => $id])->first();
    // }
   public function getPerkiraan($id = false)
{
 $query = $this->select('perkiraan_penjualan.kode_barang, perkiraan_penjualan.id_satuan, perkiraan_penjualan.metode_perkiraan AS metode, perkiraan_penjualan.periode_perkiraan, perkiraan_penjualan.hasil_perkiraan, perkiraan_penjualan.created_at, master_barang.nama_brg, satuan.nama_satuan')
      ->join('barang', 'barang.kode_barang = perkiraan_penjualan.kode_barang')
      ->join('satuan', 'satuan.satuan_id = barang.id_satuan')
      ->join('detail_master', 'detail_master.detail_master_id = barang.id_master_barang')
      ->join('master_barang', 'master_barang.kode_brg = detail_master.master_barang');

if ($id == false) {
    return $query->findAll();
} else {
    return $query->where(['id' => $id])->first();
}

}


    public function savePerkiraan($data)
    {
        return $this->insert($data);
    }
}
