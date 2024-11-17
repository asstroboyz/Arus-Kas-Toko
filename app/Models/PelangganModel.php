<?php


namespace App\Models;

use CodeIgniter\Model;

class PelangganModel extends Model
{
    protected $table = 'pelanggan';
    protected $primaryKey = 'id_pelanggan';
    protected $useTimestamps = true;
    protected $allowedFields = ['id_pelanggan', 'nama','kontak','alamat', 'created_at', 'updated_at'];

    public function getNama($id = false)
    {
        if ($id == false) {
            return $this->findAll();
        }
        return $this->where(['id_pelanggan' => $id])->first();
    }
}
