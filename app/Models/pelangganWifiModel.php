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
}