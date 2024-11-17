<?php

namespace App\Models;

use CodeIgniter\Model;

class piutangModel extends Model
{
    protected $table = 'piutang';
    protected $primaryKey = 'id_piutang';
    protected $allowedFields = ['id_penjualan_barang', 'id_pelanggan', 'tanggal_piutang','jatuh_tempo', 'jumlah_piutang','created_at','jumlah_terbayar','status_piutang'];

    public function getPiutang($id_piutang = false)
    {
        if ($id_piutang == false) {
            return $this->findAll();
        }
        return $this->where(['id_piutang' => $id_piutang])->first();
    }

      public function getTotalPiutang()
    {
        $builder = $this->db->table($this->table)
                            ->select('COALESCE(SUM(jumlah_piutang - jumlah_terbayar), 0) as total_piutang');
        
        $result = $builder->get()->getRow();
        return $result ? $result->total_piutang : 0;
    }
    public function getTotalPiutangByDateRange($startDate, $endDate)
    {
        return $this->db->table($this->table)
            ->select('COALESCE(SUM(jumlah_piutang), 0) as total_piutang')
            ->where('tanggal_piutang >=', $startDate)
            ->where('tanggal_piutang <=', $endDate)
            ->get()->getRow()->total_piutang;
    }
}
