<?php

namespace App\Models;

use CodeIgniter\Model;

class KasModel extends Model
{
    protected $table = 'kas_toko'; // Mengubah nama tabel menjadi 'kas'
    protected $primaryKey = 'id_kas'; // Menggunakan 'id_kas' sebagai primary key
    protected $allowedFields = ['id_kas', 'tanggal', 'jenis_transaksi', 'keterangan', 'jumlah_awal', 'jumlah_akhir', 'saldo_terakhir'];

    public function getTotalPemasukan()
    {
        $builder = $this->db->table('kas_toko')
                            ->select('COALESCE(SUM(jumlah_akhir - jumlah_awal), 0) as total_pemasukan')
                            ->where('jenis_transaksi LIKE', '%Penjualan%')
                            ->where('keterangan LIKE', '%Penjualan%');
        
        $result = $builder->get()->getRow();
        return $result ? $result->total_pemasukan : 0;
    }
    
    public function getTotalPemasukanByDateRange($startDate, $endDate)
    {
        return $this->db->table($this->table)
            ->select('COALESCE(SUM(jumlah_akhir - jumlah_awal), 0) as total_pemasukan')
            ->where('tanggal >=', $startDate)
            ->where('tanggal <=', $endDate)
            ->like('keterangan', 'Penjualan')
            ->get()->getRow()->total_pemasukan;
    }

 public function rollbackSaldo($jumlah)
    {
        // Ambil saldo terakhir
        $latestKas = $this->getSaldoTerakhir();
        $saldoTerakhir = $latestKas ? $latestKas['saldo_terakhir'] : 0;

        // Kembalikan saldo dengan menambahkan jumlah yang dihapus
        $saldoBaru = $saldoTerakhir - $jumlah;

        // Simpan saldo baru
        $this->save([
            'tanggal' => date('Y-m-d'),
            'jenis_transaksi' => 'pengeluaran',
            'keterangan' => 'Penghapusan penjualan',
            'jumlah' => $jumlah,
            'saldo_terakhir' => $saldoBaru,
        ]);
    }
     public function deleteRiwayatByDetailPenjualanId($detailPenjualanId)
    {
        return $this->where('id_detail_penjualan_barang', $detailPenjualanId)->delete();
    }

    // Fungsi untuk mendapatkan saldo terakhir
    public function getLastBalance()
    {
        return $this->orderBy('tanggal', 'DESC')->first()['saldo_terakhir'];
    }

    // Fungsi untuk memperbarui saldo terakhir
    public function updateLastBalance($newBalance)
    {
        // Asumsikan kita menyimpan catatan baru untuk memperbarui saldo terakhir
        $data = [
            'tanggal' => date('Y-m-d H:i:s'),
            'jenis_transaksi' => 'Update Balance',
            'keterangan' => 'Update last balance after deletion',
            'jumlah_awal' => $this->getLastBalance(),
            'jumlah_akhir' => $newBalance,
            'saldo_terakhir' => $newBalance
        ];
        return $this->insert($data);
    }
    public function getSaldo($id = false)
    {
        if ($id == false) {
            return $this->findAll();
        }
        return $this->where(['id_kas' => $id])->first();
    }

    public function getKas()
    {
        return $this->findAll();
    }

     public function getSaldoTerakhir()
    {
        return $this->select('saldo_terakhir')
                    ->orderBy('id_kas', 'DESC')
                    ->first();
    }

    // public function getLastBalance()
    // {
    //     // Mendapatkan saldo terakhir dari tabel kas
    //     $query = $this->select('saldo_terakhir')
    //                   ->orderBy('id_kas', 'DESC')
    //                   ->limit(1)
    //                   ->get();

    //     // Memeriksa apakah ada hasil dari query
    //     if ($query->getNumRows() > 0) {
    //         // Mengembalikan saldo terakhir
    //         return $query->getRow()->saldo_terakhir;
    //     } else {
    //         // Jika tidak ada saldo sebelumnya, mengembalikan saldo awal 0
    //         return 0;
    //     }
    // }

    // public function updateLastBalance($newBalance)
    // {
    //     $this->set('saldo_terakhir', $newBalance)
    //          ->orderBy('id_kas', 'DESC') // Pastikan diurutkan berdasarkan id_kas atau kolom lain yang relevan
    //          ->limit(1)
    //          ->update();
    // }

   public function getPenerimaan($tanggalMulai, $tanggalAkhir)
{
    return $this->where('jenis_transaksi', 'penerimaan')
                ->where('tanggal >=', $tanggalMulai)
                ->where('tanggal <=', $tanggalAkhir)
                ->findAll();
}

public function getPengeluaran($tanggalMulai, $tanggalAkhir)
{
    return $this->where('jenis_transaksi', 'pengeluaran')
                ->where('tanggal >=', $tanggalMulai)
                ->where('tanggal <=', $tanggalAkhir)
                ->findAll();
}


}
