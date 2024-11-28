<?php


namespace App\Models;

use CodeIgniter\Model;

class pelangganWifiModel extends Model
{
    protected $table = 'pelanggan_wifi';
    protected $primaryKey = 'id';
    // protected $useTimestamps = true;
    protected $allowedFields = ['nama', 'alamat', 'no_hp', 'nik','foto_ktp','kode_paket','tgl_pasang','status_pelanggan','created_at','updated_at'];

    public function getPaket($id = false)
    {
        if ($id == false) {
            return $this->findAll();
        }
        return $this->where(['id' => $id])->first();
    }
    public function countPelangganAktif()
    {
        return $this->where('status_pelanggan', 'aktif')
                    ->countAllResults();
    }

    // Method untuk menghitung pelanggan tidak aktif
    public function countPelangganTidakAktif()
    {
        return $this->where('status_pelanggan', 'tidak aktif')
             
        ->countAllResults();
    }
    public function countPelangganByStatus()
    {
        $this->select('status_pelanggan, COUNT(*) AS total')
             ->groupBy('status_pelanggan');
        return $this->get()->getResult();
    }
}