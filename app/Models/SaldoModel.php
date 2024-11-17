<?php

namespace App\Models;

use CodeIgniter\Model;

class SaldoModel extends Model
{
    protected $table = 'saldo';
    // protected $useTimestamps = true;
    protected $primarykey = 'id';
    protected $allowedFields = ['id', 'tanggal', 'saldo'];

    public function getSaldo($id = false)
    {
        if ($id == false) {
            return $this->findAll();
        }
        return $this->where(['id' => $id])->first();
    }


    public function getLastBalance()
    {
        // Mendapatkan saldo terakhir dari tabel saldo
        $query = $this->select('saldo')
                      ->orderBy('id', 'DESC')
                      ->limit(1)
                      ->get();

        // Memeriksa apakah ada hasil dari query
        if ($query->getNumRows() > 0) {
            // Mengembalikan saldo terakhir
            return $query->getRow()->saldo;
        } else {
            // Jika tidak ada saldo sebelumnya, mengembalikan saldo awal 0
            return 0;
        }
    }

    public function updateLastBalance($newBalance)
    {
        $this->set('saldo', $newBalance)
             ->orderBy('id', 'DESC') // Pastikan diurutkan berdasarkan ID atau kolom lain yang relevan
             ->limit(1)
             ->update();
    }

}
