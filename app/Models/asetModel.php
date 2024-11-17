<?php

namespace App\Models;

use CodeIgniter\Model;

class asetModel extends Model
{
    protected $table = 'aset';
    // protected $useTimestamps = true;
    protected $primaryKey = 'id_aset'; // Perhatikan penulisan disini, harus $primaryKey bukan $primarykey
    protected $allowedFields = ['nama_aset', 'nilai','created_at'];

    public function getAset($id_aset = false)
    {
        if ($id_aset == false) {
            return $this->findAll();
        }
        return $this->where(['id_aset' => $id_aset])->first();
    }


    public function getTotalPembelianAset()
    {
        $builder = $this->db->table('aset')
                            ->select('COALESCE(SUM(nilai), 0) as total_pembelian_aset');
        
        $result = $builder->get()->getRow();
        return $result ? $result->total_pembelian_aset : 0;
    }

    // public function getTotalPembelianAsetByDateRange($startDate, $endDate)
    // {
    //     $builder = $this->db->table($this->table)
    //                         ->select('COALESCE(SUM(nilai), 0) as total_pembelian_aset')
    //                         ->where('tanggal >=', $startDate)
    //                         ->where('tanggal <=', $endDate);
    
    //     $result = $builder->get()->getRow();
    //     return $result ? $result->total_pembelian_aset : 0;
    // }
    public function getTotalPembelianAsetByDateRange()
    {
        return $this->db->table($this->table)
            ->select('COALESCE(SUM(nilai), 0) as total_pembelian_aset')
            ->get()->getRow()->total_pembelian_aset;
    }

}
