<?php

namespace App\Models;

use CodeIgniter\Model;

class pembayaranPiutangModel extends Model
{
    protected $table = 'pembayaran_piutang';
    protected $primaryKey = 'id_pembayaran_piutang';
    protected $allowedFields = ['id_piutang', 'tanggal_pembayaran', 'jumlah_pembayaran'];

    public function getPembayaranPiutang($id_pembayaran_piutang = false)
    {
        if ($id_pembayaran_piutang == false) {
            return $this->findAll();
        }
        return $this->where(['id_pembayaran_piutang' => $id_pembayaran_piutang])->first();
    }
//     public function getTotalPembayaranPiutangByDateRange($startDate, $endDate)
// {
//     $builder = $this->db->table($this->table)
//                         ->select('COALESCE(SUM(jumlah_pembayaran), 0) as total_pembayaran_piutang')
//                         ->where('tanggal_pembayaran >=', $startDate)
//                         ->where('tanggal_pembayaran <=', $endDate);
    
//     $result = $builder->get()->getRow();
//     return $result ? $result->total_pembayaran_piutang : 0;
// }
public function getTotalPembayaranPiutangByDateRange($startDate, $endDate)
    {
        return $this->db->table($this->table)
            ->select('COALESCE(SUM(jumlah_pembayaran), 0) as total_pembayaran_piutang')
            ->where('tanggal_pembayaran >=', $startDate)
            ->where('tanggal_pembayaran <=', $endDate)
            ->get()->getRow()->total_pembayaran_piutang;
    }

}
