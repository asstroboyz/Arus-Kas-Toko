<?php

namespace App\Models;

use CodeIgniter\Model;

class detailRestokModel extends Model
{
    protected $table = 'detail_restok';
    // protected $useTimestamps = true;
    protected $primarykey = 'id';
    protected $allowedFields = ['id_restok', 'kode_barang','jumlah_restok','sub_total','status_bayar','harga_beli'];

    public function getRestok($id = false)
    {
        if ($id == false) {
            return $this
            ->findAll();
        }
        return $this->where(['id' => $id])->first();
    }

    public function getDetailRestok($idRestok = false)
    {
       if ($idRestok == false) {
           return $this
            ->select('detail_restok.*, barang.nama_brg')
            ->join('barang', 'barang.kode_barang = detail_restok.kode_barang')
           
            ->findAll();
       }else{
            return $this
            ->select('detail_restok.*, barang.nama_brg')
            ->join('barang', 'barang.kode_barang = detail_restok.kode_barang')
           
            ->where(['id_restok' => $idRestok])
            ->findAll();
       }
    }
}