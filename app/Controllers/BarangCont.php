<?php

namespace App\Controllers;

use App\Models\BarangModel;
use App\Models\detailPenjualanBarangModel;
use App\Models\modalTokoModel;
use App\Models\PelangganModel;
use App\Models\PenjualanBarangModel;
use App\Models\Profil;
use App\Models\KasModel;
use App\Models\PengeluaranModel;
use App\Models\satuanModel;
use App\Models\tipeBarangModel;
use App\Models\TransaksiBarangModel;
use App\Models\detailRestokModel;
use App\Models\supplierModel;
use Mpdf\Mpdf;
use Myth\Auth\Entities\User;
use Myth\Auth\Models\GroupModel;
use Myth\Auth\Models\UserModel;

class BarangCont extends BaseController
{
    protected $db;
    protected $builder;
    protected $BarangModel;
    protected $validation;
    protected $session;
    protected $tipeBarangModel;
    protected $satuanModel;
    protected $TransaksiBarangModel;
    protected $KeuntunganModel;
    protected $PenjualanBarangModel;
    protected $PemasukanModel;
    protected $detailPenjualanBarangModel;
    protected $PelangganModel;
    protected $modalTokoModel;
    protected $supplierModel;
    protected $KasModel;
    protected $PengeluaranModel;
    
    public function __construct()
    {

     
        $this->KasModel = new KasModel();
        $this->PengeluaranModel = new PengeluaranModel();
        $this->modalTokoModel = new modalTokoModel();
        $this->BarangModel = new BarangModel();
        $this->satuanModel = new satuanModel();
        $this->supplierModel = new supplierModel();
        $this->TransaksiBarangModel = new TransaksiBarangModel();
        $this->db = \Config\Database::connect();
        $this->validation = \Config\Services::validation();
        $this->session = \Config\Services::session();
        $this->PelangganModel = new PelangganModel();
        $this->PenjualanBarangModel = new PenjualanBarangModel();
        $this->detailPenjualanBarangModel = new detailPenjualanBarangModel();

    }

    public function index()
    {
        $data = [
            'title' => 'Produk - Hera',
            'barangs' => $this->BarangModel
                ->join('satuan', 'satuan.satuan_id = barang.id_satuan')
                ->orderBy('barang.tanggal_barang_masuk', 'DESC')
                ->where('deleted_at', null)->findAll(),
        ];


        return view('Admin/Barang/Index', $data);
    }


    
    public function atk_trash()
    {
        $barangs = $this->BarangModel->onlyDeleted()->getBarang();

        // Menyaring data yang belum di-restore
        $barangsNotRestored = array_filter($barangs, function ($barang) {
            return $barang['deleted_at'] !== null; // Filter barang yang sudah di-restore
        });

        $data = [
            'title' => 'Toko Hera - Barang',
            'barangs' => $barangsNotRestored,
        ];

        return view('Admin/Barang/Soft_deleted', $data);
    }

    public function tambahForm()
    {
        // Tampilkan form tambah stok
        $data = [
             'validation' => \Config\Services::validation(), // Correct service retrieval
        'title' => 'Tambah Barang',
        'satuan' => $this->satuanModel->findAll(),
         'supplierList' => $this->supplierModel->findAll(),
        ];

        return view('Admin/Barang/Tambah_barang', $data);
    }

   
    public function saveBarang()
    {
        $KasModel = new KasModel();
        $PengeluaranModel = new PengeluaranModel();

        $namaBarang = $this->request->getPost('nama_barang');
        $jenisBarang = $this->request->getPost('jenis_brg');
        $idSatuan = $this->request->getPost('satuan_barang');
        $id_supplier = $this->request->getPost('id_supplier');
        $hargaBeli = $this->request->getPost('harga_beli'); // Get the purchase price
 $latestKas = $KasModel->getSaldoTerakhir();
        $saldoTerakhir = $latestKas ? $latestKas['saldo_terakhir'] : 0;
        // Check if the item already exists
        $barangExists = $this->BarangModel->where('kode_barang', $namaBarang)
            ->where('id_satuan', $idSatuan)
            ->first();

        if ($barangExists) {
            session()->setFlashdata('error-msg', 'Barang sudah ada dalam database.');
            return redirect()->to('/Admin/tambahForm')->withInput();
        }

        // Validate the form input
        $this->validation->setRules([
            'nama_barang' => 'required',
            'jenis_brg' => 'required',
            'merk' => 'required',
            'satuan_barang' => 'required',
            'stok' => [
                'rules' => 'required|numeric|greater_than[0]',
                'errors' => [
                    'required' => 'Stok wajib diisi.',
                    'numeric' => 'Stok harus berupa angka.',
                    'greater_than' => 'Stok harus lebih besar dari 0.',
                ],
            ],
            'harga_beli' => 'required|numeric',
            'harga_jual' => 'required|numeric',
            'id_supplier' => 'required'
        ]);

        if (!$this->validation->withRequest($this->request)->run()) {
            $errors = $this->validation->getErrors();
            foreach ($errors as $error) {
                echo $error . '<br>';
            }
            return redirect()->to('/Admin/tambahForm')->withInput();
        }

        // Generate the kode_barang
        $kodeBarang = strtoupper(substr($jenisBarang, 0, 3)) . sprintf('%04d', mt_rand(0, 9999));

        // Prepare the data array for insertion
        $data = [
            'kode_barang' => $kodeBarang,
            'nama_brg' => $namaBarang,
            'jenis_brg' => $jenisBarang,
            'merk' => $this->request->getPost('merk'),
            'id_satuan' => $idSatuan,
            'stok' => $this->request->getPost('stok'),
            'harga_beli' => $hargaBeli,
            'harga_jual' => $this->request->getPost('harga_jual'),
            'tanggal_barang_masuk' => date('Y-m-d H:i:s'),
            'id_supplier' => $id_supplier
        ];

        // Insert data into BarangModel
        $this->BarangModel->insert($data);
        $insertID = $this->BarangModel->insertID();

        // Insert data into TransaksiBarangModel
        $this->TransaksiBarangModel->insert([
            'kode_barang' => $kodeBarang,
            'stok' => $data['stok'],
            'tanggal_barang_masuk' => $data['tanggal_barang_masuk'],
            'jumlah_perubahan' => $data['stok'],
            'jenis_transaksi' => 'masuk',
            'informasi_tambahan' => 'Penambahan stok.',
            'tanggal_perubahan' => $data['tanggal_barang_masuk'],
        ]);

        // Retrieve the current cash balance
        $kas = $KasModel->orderBy('tanggal', 'DESC')->first();

        // Handle the case where there are no records in kas_toko
        if ($kas === null) {
            $currentSaldo = 0; // Set a default balance if no record exists
        } else {
            $currentSaldo = $kas['saldo_terakhir'];
        }
        $totalHargaPembelian = $hargaBeli * $data['stok'];
        // Calculate the new cash balance
        $newSaldo = $saldoTerakhir - $totalHargaPembelian;

        // Update the cash balance in kas_toko
        $KasModel->insert([
            'tanggal' => date('Y-m-d H:i:s'),
            'jenis_transaksi' => 'pengeluaran',
            'keterangan' => 'Pembelian barang: ' . $namaBarang,
            'jumlah_awal' => $saldoTerakhir,
            'jumlah_akhir' => $newSaldo,
            'saldo_terakhir' => $newSaldo,
        ]);

        // Insert data into PengeluaranModel
        if (!$PengeluaranModel->insert([
            'tanggal' => date('Y-m-d H:i:s'),
            'keterangan' => 'Pembelian barang: ' . $namaBarang,
            'jumlah' => $totalHargaPembelian,
            'id_kas' => $KasModel->insertID()
        ])) {
            $errors = $PengeluaranModel->errors();
            echo 'Error inserting into pengeluaran: ' . implode(', ', $errors) . '<br>';
            return redirect()->to('/Admin/tambahForm')->withInput();
        }

        // Display success message
        session()->setFlashdata('msg', 'Data barang berhasil ditambahkan dan kas toko telah diperbarui.');
        return redirect()->to('/BarangCont');
    }


    public function softDelete($kode_barang)
    {
        $barangModel = new BarangModel();

        // Cek apakah barang dengan kode_barang tertentu ada
        $barang = $barangModel->find($kode_barang);

        if ($barang) {
            // Lakukan soft delete dengan menghapus record di tabel Barang dan TransaksiBarang
            $barangModel->softDeleteWithRelations($kode_barang);

            return redirect()->to('BarangCont')->with('success', 'Data berhasil dihapus secara soft delete.');
        } else {
            return redirect()->to('BarangCont')->with('error', 'Data tidak ditemukan.');
        }
    }
    
    public function restore($kode_barang)
    {
        $restored = $this->BarangModel->restoreBarang($kode_barang);

        if ($restored) {
            return redirect()->to(base_url('Admin/barang'))->with('msg', 'Barang berhasil dipulihkan.');
        } else {
            return redirect()->to(base_url('Admin/barang'))->with('error-msg', 'Gagal memulihkan barang.');
        }
    }

    public function barangMasuk()
    {
        $barangModel = new BarangModel();

        // Ambil barang-barang yang baru masuk
        $barangMasuk = $barangModel->getBarangMasuk();

        // Kirim data ke view
        $data['title'] = 'Riawayat Stok ';
        $data = [
            'barangMasuk' => $barangMasuk,
            'title' => 'Barang',
        ];

        return view('Admin/Barang/Barang_masuk', $data);
    }

    public function barangKeluar()
    {
        $barangModel = new BarangModel();

        // Ambil barang-barang yang baru keluar
        $barangKeluar = $barangModel->getBarangKeluar();

        // Kirim data ke view
        $data = [
            'barangKeluar' => $barangKeluar,
        ];

        return view('admin/riwayat_stok/barang_keluar', $data);
    }
    public function formTambahStok($kodeBarang)
    {
        $barangModel = new BarangModel();
        $barang = $barangModel->where('kode_barang', $kodeBarang)->first();

        if (!$barang) {
            return redirect()->to('/admin/barang')->with('error-msg', 'Barang tidak ditemukan.');
        }

        $data = [
            'barang' => $barang,
            'kode_barang' => $kodeBarang,
            'stok' => $barang['stok'],
            'harga_beli' => $barang['harga_beli'],
            'validation' => $this->validation,
            'title' => 'Tambah Stok',
        ];

        return view('Admin/Barang/Tambah_stok', $data);
    }
}
