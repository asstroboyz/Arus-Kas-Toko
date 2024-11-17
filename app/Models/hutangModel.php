<?php

namespace App\Models;

use CodeIgniter\Model;

class hutangModel extends Model
{
    protected $table = 'hutang';
    protected $primaryKey = 'id_hutang';
    protected $allowedFields = ['keterangan', 'jumlah', 'tanggal','jumlah_sisa', 'status','created_at'];

    public function getHutang($id_hutang = false)
    {
        if ($id_hutang == false) {
            return $this->findAll();
        }
        return $this->where(['id_hutang' => $id_hutang])->first();
    }

    public function getTotalHutang()
    {
        $builder = $this->db->table($this->table)
                            ->select('COALESCE(SUM(jumlah), 0) as total_hutang');
        
        $result = $builder->get()->getRow();
        return $result ? $result->total_hutang : 0;
    }

    public function getTotalPenerimaanPinjaman()
    {
        $builder = $this->db->table($this->table)
                            ->select('COALESCE(SUM(jumlah), 0) as total_penerimaan_pinjaman');
        
        $result = $builder->get()->getRow();
        return $result ? $result->total_penerimaan_pinjaman : 0;
    }

    public function getTotalPenerimaanPinjamanByDateRange($startDate, $endDate)
    {
        return $this->db->table($this->table)
            ->select('COALESCE(SUM(jumlah), 0) as total_penerimaan_pinjaman')
            ->where('tanggal >=', $startDate)
            ->where('tanggal <=', $endDate)
            ->get()->getRow()->total_penerimaan_pinjaman;
    }

    public function getTotalHutangByDateRange($startDate, $endDate)
    {
        return $this->db->table($this->table)
            ->select('COALESCE(SUM(jumlah), 0) as total_hutang')
            ->where('tanggal >=', $startDate)
            ->where('tanggal <=', $endDate)
            ->get()->getRow()->total_hutang;
    }
}
