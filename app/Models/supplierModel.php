<?php

namespace App\Models;

use CodeIgniter\Model;

class supplierModel extends Model
{
    protected $table = 'supplier';
    protected $primaryKey = 'id_supplier'; // Perbaikan di sini
    protected $allowedFields = ['nama', 'alamat', 'kontak'];

    public function getSupplier($id_supplier = false)
    {
        if ($id_supplier == false) {
            return $this->findAll();
        }
        return $this->where(['id_supplier' => $id_supplier])->first();
    }
}
