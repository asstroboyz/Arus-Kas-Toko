<?php

namespace App\Models;

use CodeIgniter\Model;

class restokModel extends Model
{
    protected $table = 'restok';
    // protected $useTimestamps = true;
    protected $primarykey = 'restok_id';
    protected $allowedFields = ['restok_id','id_supplier','tanggal','jumlah_pembayaran','jumlah_uang','kembalian'];

    public function getTotalRestok()
    {
        $builder = $this->db->table('restok r')
                            ->join('detail_restok dr', 'r.restok_id = dr.id_restok')
                            ->select('SUM(dr.sub_total) as total_restok');
        
        $result = $builder->get()->getRow();
        return $result ? $result->total_restok : 0;
    
    }
    // public function getTotalRestokByDateRange($startDate, $endDate)
    // {
    //     $builder = $this->db->table('restok')
    //                         ->join('detail_restok', 'restok.restok_id = detail_restok.id_restok')
    //                         ->select('COALESCE(SUM(detail_restok.sub_total), 0) as total_restok')
    //                         ->where('restok.tanggal >=', $startDate)
    //                         ->where('restok.tanggal <=', $endDate);
    
    //     $result = $builder->get()->getRow();
    //     return $result ? $result->total_restok : 0;
    // }

    public function getTotalRestokByDateRange($startDate, $endDate)
    {
        return $this->db->table($this->table)
            ->select('COALESCE(SUM(dr.sub_total), 0) as total_restok')
            ->join('detail_restok dr', 'restok.restok_id = dr.id_restok')
            ->where('tanggal >=', $startDate)
            ->where('tanggal <=', $endDate)
            ->get()->getRow()->total_restok;
    }
   public function getRestok($restok_id = false)
{
    if ($restok_id == false) {
        return $this
            ->select('restok.*, supplier.nama, detail_restok.*')
            ->join('detail_restok', 'restok.restok_id = detail_restok.id_restok')
            ->join('supplier', 'supplier.id_supplier = restok.id_supplier')
            ->orderBy('restok.tanggal', 'DESC')
            ->findAll();
    }
    return $this
        ->select('restok.*, supplier.nama, detail_restok.*')
        ->join('detail_restok', 'restok.restok_id = detail_restok.id_restok')
        ->join('supplier', 'supplier.id_supplier = restok.id_supplier')
        ->orderBy('restok.tanggal', 'DESC')
        ->where(['restok.restok_id' => $restok_id])
        ->first();
}

}
