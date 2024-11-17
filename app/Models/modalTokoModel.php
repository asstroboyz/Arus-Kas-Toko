<?php

namespace App\Models;

use CodeIgniter\Model;

class modalTokoModel extends Model
{
    protected $table = 'modal_toko';
    protected $primaryKey = 'id_modal'; // Perubahan dari $primarykey menjadi $primaryKey
    protected $allowedFields = ['sumber', 'jumlah'];

    public function getModal($id = false)
    {
        if ($id === false) {
            return $this->findAll();
        } else {
            return $this->where(['id_modal' => $id])->first();
        }
    }

    public function getTotalModalByDateRange()
    {
        return $this->db->table($this->table)
            ->select('COALESCE(SUM(jumlah), 0) as total_modal')
            ->get()->getRow()->total_modal;
    }
}
