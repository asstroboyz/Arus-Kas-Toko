<?php

namespace App\Models;

use CodeIgniter\Model;

class PengeluaranModel extends Model
{
    protected $table = 'pengeluaran';
    // protected $useTimestamps = true;
    protected $primarykey = 'id';
    protected $allowedFields = ['id', 'tanggal', 'keterangan','jumlah','id_kas'];

    public function getSaldo($id = false)
    {
        if ($id == false) {
            return $this->findAll();
        }
        return $this->where(['id' => $id])->first();
    }

    public function getTotalPengeluaran()
    {
        $builder = $this->db->table('pengeluaran')
                            ->select('COALESCE(SUM(jumlah), 0) as total_pengeluaran');
        
        $result = $builder->get()->getRow();
        return $result ? $result->total_pengeluaran : 0;
    }

    
    public function getTotalPengeluaranByDateRange($startDate, $endDate)
    {
        return $this->db->table($this->table)
            ->select('COALESCE(SUM(jumlah), 0) as total_pengeluaran')
            ->where('tanggal >=', $startDate)
            ->where('tanggal <=', $endDate)
            ->get()->getRow()->total_pengeluaran;
    }

}
