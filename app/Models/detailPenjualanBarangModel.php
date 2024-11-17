<?php

namespace App\Models;

use CodeIgniter\Model;

class detailPenjualanBarangModel extends Model
{
    protected $table = 'detail_penjualan_barang';
    protected $primaryKey = 'id';
    protected $allowedFields = ['kode_barang', 'id_penjualan_barang',  'jumlah', 'sub_total', 'keuntungan','id_kas','id_transaksi'];


    //baru
    public function deleteDetailByPenjualanId($penjualanId)
    {
        return $this->where('id_penjualan_barang', $penjualanId)->delete();
    }

    //baru
    // Fungsi untuk mengambil detail penjualan berdasarkan id penjualan
    public function getDetailByPenjualanId($id)
    {
        // return $this->where('id_penjualan_barang', $id)->findAll();
        // Lakukan join dengan tabel barang dan satuan
        return $this->select('detail_penjualan_barang.*, barang.nama_brg, satuan.nama_satuan')
                    ->join('barang', 'barang.kode_barang = detail_penjualan_barang.kode_barang')
                    ->join('satuan', 'satuan.satuan_id = barang.id_satuan')
                    ->where('id_penjualan_barang', $id)
                    ->findAll();
    }
    
    public function getHistoricalData($kode_barang, $jumlah_hari)
    {
        // Ambil tanggal awal berdasarkan jumlah hari
        $startDate = date('Y-m-d H:i:s', strtotime("-$jumlah_hari days"));
        $endDate = date('Y-m-d H:i:s'); // Tanggal akhir adalah tanggal saat ini

        // Query untuk mendapatkan data historis
        return $this->select('detail_penjualan_barang.*, penjualan_barang.tanggal_penjualan')
                    ->join('penjualan_barang', 'penjualan_barang.penjualan_barang_id = detail_penjualan_barang.id_penjualan_barang')
                    ->where('detail_penjualan_barang.kode_barang', $kode_barang)
                    ->where('penjualan_barang.tanggal_penjualan >=', $startDate)
                    ->where('penjualan_barang.tanggal_penjualan <=', $endDate)
                    ->findAll();
    }

    public function getDetailpenjualan($id = false)
    {
        if ($id == false) {
            $result = $this
                ->select('detail_penjualan_barang.*, barang.nama_brg, satuan.nama_satuan, penjualan_barang.tanggal_penjualan, barang.merk')
                ->join('barang', 'barang.kode_barang = detail_penjualan_barang.kode_barang')
                ->join('satuan', 'satuan.satuan_id = barang.id_satuan')
                ->join('penjualan_barang', 'penjualan_barang.penjualan_barang_id = detail_penjualan_barang.id_penjualan_barang')
                ->join('pelanggan', 'pelanggan.id_pelanggan = penjualan_barang.id_pelanggan')
                ->findAll();

            // dd($result); // Tampilkan hasil saat id tidak diberikan
        }

        // Log query untuk debugging
        $builder = $this->db->table('detail_penjualan_barang')
            ->select('detail_penjualan_barang.*, barang.nama_brg, satuan.nama_satuan, penjualan_barang.tanggal_penjualan, detail_penjualan_barang.id as id_detail_penjualan, barang.merk')
            ->join('barang', 'barang.kode_barang = detail_penjualan_barang.kode_barang')
            ->join('satuan', 'satuan.satuan_id = barang.id_satuan')
            ->join('penjualan_barang', 'penjualan_barang.penjualan_barang_id = detail_penjualan_barang.id_penjualan_barang')
            ->join('pelanggan', 'pelanggan.id_pelanggan = penjualan_barang.id_pelanggan')
            ->where(['detail_penjualan_barang.id_penjualan_barang' => $id]);

        // Dapatkan query SQL yang dihasilkan
        $sql = $builder->getCompiledSelect();
        // dd($sql); // Tampilkan query SQL yang dihasilkan

        // Jalankan query dan dapatkan hasilnya
        $result = $builder->get()->getFirstRow();
        // dd($result); // Tampilkan hasil saat id diberikan

        return $result;
    }

    public function getpenjualanProses()
    {
        return $this
            ->select('detail_penjualan_barang.*, master_barang.nama_brg, satuan.nama_satuan,penjualan_barang.tanggal_penjualan, master_barang.merk,detail_master.tipe_barang')
            ->join('barang', 'barang.kode_barang = detail_penjualan_barang.kode_barang')
            ->join('satuan', 'satuan.satuan_id = barang.id_satuan')
            ->join('detail_master', 'detail_master.detail_master_id = barang.id_master_barang')
            ->join('master_barang', 'master_barang.kode_brg = detail_master.master_barang')

            ->join('users', 'users.id = detail_penjualan_barang.id_user')
            ->join('penjualan_barang', 'penjualan_barang.penjualan_barang_id = detail_penjualan_barang.id_penjualan_barang')
            ->where(['detail_penjualan_barang.status' => 'diproses'])
            ->findAll();
    }

    public function getpenjualanMasuk()
    {
        return $this
            ->select('detail_penjualan_barang.*, master_barang.nama_brg, satuan.nama_satuan,penjualan_barang.tanggal_penjualan, master_barang.merk,detail_master.tipe_barang')
            ->join('barang', 'barang.kode_barang = detail_penjualan_barang.kode_barang')
            ->join('satuan', 'satuan.satuan_id = barang.id_satuan')
            ->join('detail_master', 'detail_master.detail_master_id = barang.id_master_barang')
            ->join('master_barang', 'master_barang.kode_brg = detail_master.master_barang')
            ->join('users', 'users.id = detail_penjualan_barang.id_user')
            ->join('penjualan_barang', 'penjualan_barang.penjualan_barang_id = detail_penjualan_barang.id_penjualan_barang')
            ->where(['detail_penjualan_barang.status' => 'belum diproses'])
            ->findAll();
    }

    public function getpenjualanSelesai()
    {
        return $this
            ->select('detail_penjualan_barang.*, master_barang.nama_brg, satuan.nama_satuan,penjualan_barang.tanggal_penjualan, master_barang.merk,detail_master.tipe_barang')
            ->join('barang', 'barang.kode_barang = detail_penjualan_barang.kode_barang')
            ->join('satuan', 'satuan.satuan_id = barang.id_satuan')
            ->join('detail_master', 'detail_master.detail_master_id = barang.id_master_barang')
            ->join('master_barang', 'master_barang.kode_brg = detail_master.master_barang')
            ->join('users', 'users.id = detail_penjualan_barang.id_user')
            ->join('penjualan_barang', 'penjualan_barang.penjualan_barang_id = detail_penjualan_barang.id_penjualan_barang')
            ->where(['detail_penjualan_barang.status' => 'selesai'])
            ->findAll();
    }

    public function find($id = null)
    {
        if ($id === null) {
            return $this->findAll();
        }

        return $this->asArray()
            ->where(['kode_barang' => $id])
            ->first();
    }

    public function getAll()
    {
        $query = $this->table('penjualan_barang')->query('select * from penjualan_barang');
        return $query->getResult();
    }

    public function getpenjualanWithBarang()
    {
        return $this->db->table('penjualan_barang')
            ->join('barang', 'barang.kode_barang = penjualan_barang.kode_barang')
            ->get()
            ->getResultArray();
    }
}
