<?php


namespace App\Models;

use CodeIgniter\Model;

class tagihanModel extends Model
{
    protected $table = 'tagihan';
    protected $primaryKey = 'id';
    protected $useTimestamps = false;
    protected $allowedFields = ['pelanggan_id', 'kode_paket', 'tanggal_tagihan', 'jumlah_tagihan','status_tagihan'];

    public function getPaket($id = false)
    {
        if ($id == false) {
            return $this->findAll();
        }
        return $this->where(['id' => $id])->first();
    }
}