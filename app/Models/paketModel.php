<?php


namespace App\Models;

use CodeIgniter\Model;

class paketModel extends Model
{
    protected $table = 'paket';
    protected $primaryKey = 'kode_paket';
    // protected $useTimestamps = true;
    protected $allowedFields = ['kode_paket','nama_paket', 'harga', ];

    public function getPaket($id = false)
    {
        if ($id == false) {
            return $this->findAll();
        }
        return $this->where(['kode_paket' => $id])->first();
    }
}