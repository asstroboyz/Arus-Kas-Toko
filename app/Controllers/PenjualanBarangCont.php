<?php

namespace App\Controllers;

use App\Models\BarangModel;
use App\Models\detailPenjualanBarangModel;
use App\Models\masterBarangModel;
use App\Models\KasModel;
use App\Models\modalTokoModel;
use App\Models\PelangganModel;
use App\Models\PemasukanModel;
use App\Models\PenjualanBarangModel;
use App\Models\Profil;
use App\Models\satuanModel;
use App\Models\tipeBarangModel;
use App\Models\piutangModel;
use App\Models\TransaksiBarangModel;
use App\Models\detailRestokModel;
use App\Models\pembayaranPiutangModel;
use Mpdf\Mpdf;
use Myth\Auth\Entities\User;
use Myth\Auth\Models\GroupModel;
use Myth\Auth\Models\UserModel;

class PenjualanBarangCont extends BaseController
{
    protected $db;
    protected $builder;
    protected $BarangModel;
    protected $validation;
    protected $session;
    protected $tipeBarangModel;
    protected $masterBarangModel;
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
    protected $piutangModel;
    public function __construct()
    {

     
        $this->modalTokoModel = new modalTokoModel();
        $this->BarangModel = new BarangModel();
        $this->satuanModel = new satuanModel();
        $this->KasModel = new KasModel();
        $this->piutangModel = new piutangModel();

        $this->PemasukanModel = new PemasukanModel();
        $this->TransaksiBarangModel = new TransaksiBarangModel();
        $this->db = \Config\Database::connect();
        $this->validation = \Config\Services::validation();
        $this->session = \Config\Services::session();
        $this->masterBarangModel = new masterBarangModel();
        $this->PelangganModel = new PelangganModel();
        $this->PenjualanBarangModel = new PenjualanBarangModel();
        $this->detailPenjualanBarangModel = new detailPenjualanBarangModel();
    }

    public function index()
    {
        $penjualanModel = new PenjualanBarangModel();
        $detailPenjualanModel = new DetailPenjualanBarangModel();

        // Mendapatkan data penjualan beserta detailnya dengan left join
        $penjualan = $penjualanModel->select('penjualan_barang.*, pelanggan.nama AS nama_pelanggan')
                                    ->join('pelanggan', 'pelanggan.id_pelanggan = penjualan_barang.id_pelanggan')
                                    ->orderBy('penjualan_barang.tanggal_penjualan', 'DESC')
                                    ->findAll();

        // Data detail penjualan
        foreach ($penjualan as &$jual) {
            $details = $detailPenjualanModel->where('id_penjualan_barang', $jual['penjualan_barang_id'])->findAll();
            $jual['details'] = $details;
        }

        // Data untuk dikirimkan ke view
        $data = [
            'title' => 'Daftar Penjualan Barang', // Judul halaman
            'penjualan' => $penjualan
            // Anda dapat menambahkan data lain ke dalam array jika diperlukan
        ];
        return view('Admin/Penjualan_barang/Index', $data);
    }


    // Menu Penjualan

    public function hapus_penjualan($id)
    {
        $this->PenjualanModel->delete($id);
        session()->setFlashdata('msg', 'Penjualan berhasil dihapus.');
        return redirect()->to('/PenjualanBarangCont/penjualan');
    }

    public function penjualanbarang()
    {
        $data['detail'] = $this->PenjualanBarangModel->getAllPenjualan();
        $data['penjualan'] = $this->detailPenjualanBarangModel
                  ->select('detail_penjualan_barang.*, satuan.nama_satuan,penjualan_barang.tanggal_penjualan')
                  ->join('barang', 'barang.kode_barang = detail_penjualan_barang.kode_barang')
                  ->join('pelanggan', 'pelanggan.id = penjualan_barang.id_pelanggan')
                  ->join('satuan', 'satuan.satuan_id = barang.id_satuan')
                  ->join('detail_master', 'detail_master.detail_master_id = barang.id_master_barang')
                  ->join('master_barang', 'master_barang.kode_brg = detail_master.master_barang')
                  ->join('penjualan_barang', 'penjualan_barang.penjualan_barang_id = detail_penjualan_barang.id_penjualan_barang')
                  ->where('id_penjualan_barang', $id)->findAll();
        // dd(  $data['penjualan']);
        $data['title'] = 'Penjualan Barang';

        return view('Admin/Penjualan_barang/Index', $data);
    }

    public function list_penjualan($penjualan_barang_id)
    {
        
        // $penjualan = $this->PenjualanBarangModel->getPenjualan($penjualan_barang_id);

        // // Mengambil detail penjualan berdasarkan ID penjualan
        // $detailPenjualan = $this->detailPenjualanBarangModel->getDetailByPenjualanId($penjualan_barang_id);

        // // Mengambil informasi pelanggan berdasarkan ID pelanggan
        // $pelanggan = $this->PelangganModel->find($penjualan['id_pelanggan']);

        // // Data untuk dikirimkan ke view
        // $data = [
        //     'title' => 'Detail Penjualan', // Judul halaman
        //     'penjualan_barang_id' => $penjualan_barang_id, // ID penjualan
        //     'penjualan' => $penjualan,
        //     'detail_penjualan' => $detailPenjualan,
        //     'pelanggan' => $pelanggan
        // ];
        // Mengambil data penjualan berdasarkan ID
        $penjualan = $this->PenjualanBarangModel->getPenjualan($penjualan_barang_id);

        // Mengambil detail penjualan berdasarkan ID penjualan
        $detailPenjualan = $this->detailPenjualanBarangModel->getDetailByPenjualanId($penjualan_barang_id);

        // Mengambil informasi pelanggan berdasarkan ID pelanggan
        $pelanggan = $this->PelangganModel->find($penjualan['id_pelanggan']);

        // Data untuk dikirimkan ke view
        $data = [
            'title' => 'Detail Penjualan', // Judul halaman
            'penjualan_barang_id' => $penjualan_barang_id, // ID penjualan
            'penjualan' => $penjualan,
            'detail_penjualan' => $detailPenjualan,
            'pelanggan' => $pelanggan
        ];
        return view('Admin/Penjualan_barang/list_penjualan', $data);
    }

    
    

    public function tambah_penjualanBarang()
    {
        $data = [
            'validation' => $this->validation,
            'title' => 'Tambah Penjualan',
            'barangList' => $this->BarangModel
                ->select('barang.*, satuan.nama_satuan')
                ->join('satuan', 'satuan.satuan_id = barang.id_satuan')

                ->findAll(),
            'selectedBarang' => null,
            'pelangganList' => $this->PelangganModel->findAll(),
        ];

        $kode_barang = $this->request->getPost('kode_barang');
        if ($kode_barang) {
            $selectedBarang = $this->BarangModel->find($kode_barang);
            if ($selectedBarang) {
                $data['selectedBarang'] = $selectedBarang;
            }
        }

        return view('Admin/Penjualan_barang/Tambah_penjualan', $data);
    }
    
    // public function simpanPenjualanBrg()
    // {
    //     $PelangganModel = new PelangganModel();
    //     $barangModel = new BarangModel();
    //     $TransaksiBarangModel = new TransaksiBarangModel();
    //     $KasModel = new KasModel();
    //     $PemasukanModel = new PemasukanModel();

    //     // Mendapatkan saldo terakhir menggunakan fungsi getSaldoTerakhir()
    //     $latestKas = $KasModel->getSaldoTerakhir();
    //     $saldoTerakhir = $latestKas ? $latestKas['saldo_terakhir'] : 0;

    //     // Debug saldo terakhir
    //     // dd($saldoTerakhir);

    //     // Mendapatkan data barang dari input post
    //     $barangList = $this->request->getPost('kode_barang');
    //     $idPelangganList = $this->request->getPost('id_pelanggan'); // Ubah dari $barangList menjadi $idPelangganList

    //     $jumlahList = $this->request->getPost('jumlah');

    //     $total_penjualan = 0;

    //     // Membuat kode permintaan untuk penjualan
    //     $kode_penjualan = 'NHR-' . mt_rand(1000, 9999);

    //     // Persiapkan data untuk penyimpanan penjualan barang
    //     $penjualanData = [
    //         'penjualan_barang_id' => $kode_penjualan,
    //         'tanggal_penjualan' => date('Y-m-d'),
    //         'id_pelanggan' => $this->request->getPost('id_pelanggan')[0],
    //         // 'metode_pembayaran' => $this->request->getPost('metode_pembayaran')[0],
    //         'total_penjualan' => 0, // Total penjualan diisi sementara dengan 0
    //     ];

    //     // Debug data penjualan sebelum disimpan
    //     // dd($penjualanData);

    //     // Simpan data penjualan barang terlebih dahulu
    //     $this->PenjualanBarangModel->insert($penjualanData);

    //     foreach ($barangList as $index => $kode_barang) {
    //         // Mendapatkan data barang dari database berdasarkan kode barang
    //         $barang = $barangModel->where('kode_barang', $kode_barang)->first();

    //         // Memastikan barang tersedia dan stok mencukupi
    //         if ($barang && $barang['stok'] >= $jumlahList[$index]) {
    //             $harga_jual = $barang['harga_jual'];
    //             $harga_beli = $barang['harga_beli'];

    //             $jumlah = $jumlahList[$index];
    //             $sub_total = $jumlah * $harga_jual;
    //             $keuntungan = $jumlah * ($harga_jual - $harga_beli);

    //             $total_penjualan += $sub_total;

    //             // Mengurangi stok barang
    //             $stokBaru = $barang['stok'] - $jumlah;
    //             $barangModel->update($kode_barang, ['stok' => $stokBaru]);

    //             // Menyimpan detail penjualan barang
    //             $dataDetailPenjualan = [
    //                 'kode_barang' => $kode_barang,
    //                 'jumlah' => $jumlah,
    //                 'sub_total' => $sub_total,
    //                 'keuntungan' => $keuntungan,
    //                 'id_penjualan_barang' => $kode_penjualan,
    //             ];

    //             // Debug data detail penjualan sebelum disimpan
    //             // dd($dataDetailPenjualan);

    //             // Simpan detail penjualan barang
    //             $id_detail_penjualan_barang = $this->detailPenjualanBarangModel->insert($dataDetailPenjualan);

    //             // Menyimpan data pemasukan ke kas
    //             $jumlah_awal = $saldoTerakhir;
    //             $jumlah_akhir = $saldoTerakhir + $sub_total;
    //             $saldoTerakhir = $jumlah_akhir; // Update saldo terakhir

    //             $dataKas = [
    //                 'cek_sub' => $sub_total,
    //                 'tanggal' => date('Y-m-d'),
    //                 'jenis_transaksi' => 'penerimaan',
    //                 'keterangan' => 'Penjualan barang tes- ' . $barang['nama_brg'],
    //                 'jumlah_awal' => $jumlah_awal,
    //                 'jumlah_akhir' => $jumlah_akhir,
    //                 'saldo_terakhir' => $saldoTerakhir,
    //             ];

    //             // Debug data pemasukan sebelum disimpan
    //             // dd($dataKas);

    //             // Simpan data pemasukan ke kas
    //             $KasModel->insert($dataKas);

    //             $dataTransaksi = [
    //                 'cek_sub' => $sub_total,
    //                 'tanggal_barang_keluar' => date('Y-m-d'),
    //                  'kode_barang' => $kode_barang,
    //                  'stok' => $jumlah, // bagaimana mendapatkan stok?
    //                 'jenis_transaksi' => 'pengeluaran',
    //                 'informasi_tambahan' => 'Penjualan barang tes- ' . $barang['nama_brg'],
    //                 'jumlah_perubahan' => $jumlah,
                   
    //             ];
    //             $TransaksiBarangModel->insert($dataTransaksi);

    //             $dataPemasukan = [
                  
    //                 'tanggal' => date('Y-m-d'),
    //                 'keterangan' => 'Penjualan barang tes- ' . $barang['nama_brg'],
    //                 'id_kas' => null,
    //                  'id_penjualan_barang' => $kode_penjualan,
                   
    //             ];
    //             $PemasukanModel->insert($dataPemasukan);

    //         } else {
    //             // Jika stok tidak mencukupi, tampilkan pesan kesalahan
    //             session()->setFlashdata('msg', 'Stok barang tidak mencukupi.');
    //             return redirect()->back()->withInput();
    //         }
    //     }
    //     // dd($saldoTerakhir, $dataPemasukan, $dataDetailPenjualan, $penjualanData, $dataTransaksi, $dataKas);
    //     // Update total penjualan setelah selesai menyimpan detail penjualan
    //     $this->PenjualanBarangModel->update($kode_penjualan, ['total_penjualan' => $total_penjualan]);

    //     session()->setFlashdata('msg', 'Penjualan berhasil dilakukan.');
    //     return redirect()->to('PenjualanBarangCont/');
    // }


    //penjualan done sebelum piutang
    // public function simpanPenjualanBrg()
    // {
    //     $PelangganModel = new PelangganModel();
    //     $barangModel = new BarangModel();
    //     $TransaksiBarangModel = new TransaksiBarangModel();
    //     $KasModel = new KasModel();
    //     $PemasukanModel = new PemasukanModel();

    //     // Mendapatkan saldo terakhir menggunakan fungsi getSaldoTerakhir()
    //     $latestKas = $KasModel->getSaldoTerakhir();
    //     $saldoTerakhir = $latestKas ? $latestKas['saldo_terakhir'] : 0;

    //     // Mendapatkan data barang dari input post
    //     $barangList = $this->request->getPost('kode_barang');
    //     $idPelangganList = $this->request->getPost('id_pelanggan'); // Ubah dari $barangList menjadi $idPelangganList
    //     $jumlahList = $this->request->getPost('jumlah');

    //     $total_penjualan = 0;

    //     // Membuat kode permintaan untuk penjualan
    //     $kode_penjualan = 'NHR-' . mt_rand(1000, 9999);

    //     // Persiapkan data untuk penyimpanan penjualan barang
    //     $penjualanData = [
    //         'penjualan_barang_id' => $kode_penjualan,
    //         'tanggal_penjualan' => date('Y-m-d'),
    //         'id_pelanggan' => $this->request->getPost('id_pelanggan')[0],
    //         'total_penjualan' => 0, // Total penjualan diisi sementara dengan 0
    //     ];

    //     // Simpan data penjualan barang terlebih dahulu
    //     $this->PenjualanBarangModel->insert($penjualanData);

    //     foreach ($barangList as $index => $kode_barang) {
    //         // Mendapatkan data barang dari database berdasarkan kode barang
    //         $barang = $barangModel->where('kode_barang', $kode_barang)->first();

    //         // Memastikan barang tersedia dan stok mencukupi
    //         if ($barang && $barang['stok'] >= $jumlahList[$index]) {
    //             $harga_jual = $barang['harga_jual'];
    //             $harga_beli = $barang['harga_beli'];

    //             $jumlah = $jumlahList[$index];
    //             $sub_total = $jumlah * $harga_jual;
    //             $keuntungan = $jumlah * ($harga_jual - $harga_beli);

    //             $total_penjualan += $sub_total;

    //             // Mengurangi stok barang
    //             $stokBaru = $barang['stok'] - $jumlah;
    //             $barangModel->update($kode_barang, ['stok' => $stokBaru]);

    //             // Menyimpan data pemasukan ke kas
    //             $jumlah_awal = $saldoTerakhir;
    //             $jumlah_akhir = $saldoTerakhir + $sub_total;
    //             $saldoTerakhir = $jumlah_akhir; // Update saldo terakhir

    //             $dataKas = [
    //                 'cek_sub' => $sub_total,
    //                 'tanggal' => date('Y-m-d'),
    //                 'jenis_transaksi' => 'penerimaan',
    //                 'keterangan' => 'Penjualan barang tes- ' . $barang['nama_brg'],
    //                 'jumlah_awal' => $jumlah_awal,
    //                 'jumlah_akhir' => $jumlah_akhir,
    //                 'saldo_terakhir' => $saldoTerakhir,
    //             ];

    //             // Simpan data pemasukan ke kas dan dapatkan id_kas
    //             $id_kas = $KasModel->insert($dataKas);

    //             $dataTransaksi = [
    //                 'cek_sub' => $sub_total,
    //                 'tanggal_barang_keluar' => date('Y-m-d'),
    //                 'kode_barang' => $kode_barang,
    //                 'stok' => $jumlah,
    //                 'jenis_transaksi' => 'pengeluaran',
    //                 'informasi_tambahan' => 'Penjualan barang tes- ' . $barang['nama_brg'],
    //                 'jumlah_perubahan' => $jumlah,
    //             ];

    //             // Simpan data transaksi barang dan dapatkan id_transaksi
    //             $id_transaksi = $TransaksiBarangModel->insert($dataTransaksi);

    //             $dataPemasukan = [
    //                 'tanggal' => date('Y-m-d'),
    //                 'keterangan' => 'Penjualan barang tes- ' . $barang['nama_brg'],
    //                 'id_kas' => $id_kas,
    //                 'id_penjualan_barang' => $kode_penjualan,
    //             ];

    //             $PemasukanModel->insert($dataPemasukan);

    //             // Menyimpan detail penjualan barang dengan id_kas dan id_transaksi
    //             $dataDetailPenjualan = [
    //                 'kode_barang' => $kode_barang,
    //                 'jumlah' => $jumlah,
    //                 'sub_total' => $sub_total,
    //                 'keuntungan' => $keuntungan,
    //                 'id_penjualan_barang' => $kode_penjualan,
    //                 'id_transaksi' => $id_transaksi,
    //                 'id_kas' => $id_kas,
    //             ];

    //             // Simpan detail penjualan barang
    //             $id_detail_penjualan_barang = $this->detailPenjualanBarangModel->insert($dataDetailPenjualan);

    //         } else {
    //             // Jika stok tidak mencukupi, tampilkan pesan kesalahan
    //             session()->setFlashdata('msg', 'Stok barang tidak mencukupi.');
    //             return redirect()->back()->withInput();
    //         }
    //     }
    //     //  dd($saldoTerakhir, $dataPemasukan, $dataDetailPenjualan, $penjualanData, $dataTransaksi, $dataKas);

    //     // Update total penjualan setelah selesai menyimpan detail penjualan
    //     $this->PenjualanBarangModel->update($kode_penjualan, ['total_penjualan' => $total_penjualan]);

    //     session()->setFlashdata('msg', 'Penjualan berhasil dilakukan.');
    //     return redirect()->to('PenjualanBarangCont/');
    // }

    // public function simpanPenjualanBrg()
    // {
    //     $PelangganModel = new PelangganModel();
    //     $barangModel = new BarangModel();
    //     $TransaksiBarangModel = new TransaksiBarangModel();
    //     $KasModel = new KasModel();
    //     $PemasukanModel = new PemasukanModel();
    //     $PiutangModel = new PiutangModel(); // Tambahkan model PiutangModel

    //     // Mendapatkan saldo terakhir menggunakan fungsi getSaldoTerakhir()
    //     $latestKas = $KasModel->getSaldoTerakhir();
    //     $saldoTerakhir = $latestKas ? $latestKas['saldo_terakhir'] : 0;

    //     // Mendapatkan data barang dari input post
    //     $barangList = $this->request->getPost('kode_barang');
    //     $idPelanggan = $this->request->getPost('id_pelanggan')[0]; // Ambil id_pelanggan pertama
    //     $jumlahList = $this->request->getPost('jumlah');
    //     $jumlah_uang = $this->request->getPost('jumlah_uang'); // Tambahkan jumlah uang yang diterima

    //     $total_penjualan = 0;

    //     // Membuat kode permintaan untuk penjualan
    //     $kode_penjualan = 'NHR-' . mt_rand(1000, 9999);

    //     foreach ($barangList as $index => $kode_barang) {
    //         // Mendapatkan data barang dari database berdasarkan kode barang
    //         $barang = $barangModel->where('kode_barang', $kode_barang)->first();

    //         // Memastikan barang tersedia dan stok mencukupi
    //         if ($barang && $barang['stok'] >= $jumlahList[$index]) {
    //             $harga_jual = $barang['harga_jual'];
    //             $harga_beli = $barang['harga_beli'];

    //             $jumlah = $jumlahList[$index];
    //             $sub_total = $jumlah * $harga_jual;
    //             $keuntungan = $jumlah * ($harga_jual - $harga_beli);

    //             $total_penjualan += $sub_total;

    //             // Mengurangi stok barang
    //             $stokBaru = $barang['stok'] - $jumlah;
    //             $barangModel->update($kode_barang, ['stok' => $stokBaru]);

    //             // Menyimpan data pemasukan ke kas
    //             $jumlah_awal = $saldoTerakhir;
    //             $jumlah_akhir = $saldoTerakhir + $sub_total;
    //             $saldoTerakhir = $jumlah_akhir; // Update saldo terakhir

    //             $dataKas = [
    //                 'cek_sub' => $sub_total,
    //                 'tanggal' => date('Y-m-d'),
    //                 'jenis_transaksi' => 'penerimaan',
    //                 'keterangan' => 'Penjualan barang tes penjualann- ' . $barang['nama_brg'],
    //                 'jumlah_awal' => $jumlah_awal,
    //                 'jumlah_akhir' => $jumlah_akhir,
    //                 'saldo_terakhir' => $saldoTerakhir,
    //             ];

    //             // Simpan data pemasukan ke kas dan dapatkan id_kas
    //             $id_kas = $KasModel->insert($dataKas);

    //             $dataTransaksi = [
    //                 'cek_sub' => $sub_total,
    //                 'tanggal_barang_keluar' => date('Y-m-d'),
    //                 'kode_barang' => $kode_barang,
    //                 'stok' => $jumlah,
    //                 'jenis_transaksi' => 'pengeluaran',
    //                 'informasi_tambahan' => 'Penjualan barang tes penjualan- ' . $barang['nama_brg'],
    //                 'jumlah_perubahan' => $jumlah,
    //             ];

    //             // Simpan data transaksi barang dan dapatkan id_transaksi
    //             $id_transaksi = $TransaksiBarangModel->insert($dataTransaksi);

    //             $dataPemasukan = [
    //                 'tanggal' => date('Y-m-d'),
    //                 'keterangan' => 'Penjualan barang tes penjualan- ' . $barang['nama_brg'],
    //                 'id_kas' => $id_kas,
    //                 'id_penjualan_barang' => $kode_penjualan,
    //             ];

    //             $PemasukanModel->insert($dataPemasukan);

    //             // Menyimpan detail penjualan barang dengan id_kas dan id_transaksi
    //             $dataDetailPenjualan = [
    //                 'kode_barang' => $kode_barang,
    //                 'jumlah' => $jumlah,
    //                 'sub_total' => $sub_total,
    //                 'keuntungan' => $keuntungan,
    //                 'id_penjualan_barang' => $kode_penjualan,
    //                 'id_transaksi' => $id_transaksi,
    //                 'id_kas' => $id_kas,
    //             ];

    //             // Simpan detail penjualan barang
    //             $id_detail_penjualan_barang = $this->detailPenjualanBarangModel->insert($dataDetailPenjualan);

    //         } else {
    //             // Jika stok tidak mencukupi, tampilkan pesan kesalahan
    //             session()->setFlashdata('msg', 'Stok barang tidak mencukupi.');
    //             return redirect()->back()->withInput();
    //         }
    //     }

    //     // Hitung kembalian
    //     $jumlah_pembayaran = $total_penjualan;
    //     $kembalian = $jumlah_uang - $jumlah_pembayaran;
    //     $jatuh_tempo = date('Y-m-d', strtotime('+30 days'));

    //     // Set status piutang berdasarkan kembalian
    //     $status_piutang = ($kembalian < 0) ? 'belum_lunas' : 'lunas';

    //     // Persiapkan data untuk penyimpanan penjualan barang dengan status piutang
    //     $penjualanData = [
    //         'penjualan_barang_id' => $kode_penjualan,
    //         'tanggal_penjualan' => date('Y-m-d'),
    //         'id_pelanggan' => $idPelanggan,
    //         'total_penjualan' => $total_penjualan,
    //         'jumlah_uang' => $jumlah_uang,
    //         'status_piutang' => $status_piutang,
    //     ];

    //     // Simpan data penjualan barang dengan total penjualan dan status piutang
    //     $this->PenjualanBarangModel->insert($penjualanData);

    //     // Jika kembalian kurang dari 0, masukkan ke dalam piutang
    //     if ($kembalian < 0) {
    //         $dataPiutang = [
    //             'tanggal' => date('Y-m-d'),
    //             'keterangan' => 'Piutang dari penjualan dengan ID ' . $kode_penjualan,
    //             'jatuh_tempo' => $jatuh_tempo,
    //             'jumlah_terbayar' => $jumlah_uang, // Jumlah uang yang dibayarkan
    //             'jumlah_piutang' => abs($kembalian), // Masukkan nilai positif ke piutang
    //             'id_pelanggan' => $idPelanggan,
    //             'id_penjualan_barang' => $kode_penjualan,
    //         ];

    //         // Simpan data piutang
    //         //  dd($saldoTerakhir, $dataPemasukan, $dataDetailPenjualan, $penjualanData, $dataTransaksi, $dataKas);
    //         $PiutangModel->insert($dataPiutang);
    //     }

    //     session()->setFlashdata('msg', 'Penjualan berhasil dilakukan.');
    //     return redirect()->to('PenjualanBarangCont/');
    // }
    public function simpanPenjualanBrg()
    {
        $PelangganModel = new PelangganModel();
        $barangModel = new BarangModel();
        $TransaksiBarangModel = new TransaksiBarangModel();
        $KasModel = new KasModel();
        $PemasukanModel = new PemasukanModel();
        $PiutangModel = new PiutangModel(); // Tambahkan model PiutangModel

        // Mendapatkan saldo terakhir menggunakan fungsi getSaldoTerakhir()
        $latestKas = $KasModel->getSaldoTerakhir();
        $saldoTerakhir = $latestKas ? $latestKas['saldo_terakhir'] : 0;

        // Mendapatkan data barang dari input post
        $barangList = $this->request->getPost('kode_barang');
        $idPelanggan = $this->request->getPost('id_pelanggan')[0]; // Ambil id_pelanggan pertama
        $jumlahList = $this->request->getPost('jumlah');
        $jumlah_uang = $this->request->getPost('jumlah_uang'); // Tambahkan jumlah uang yang diterima

        $total_penjualan = 0;

        // Membuat kode permintaan untuk penjualan
        $kode_penjualan = 'NHR-' . mt_rand(1000, 9999);

        foreach ($barangList as $index => $kode_barang) {
            // Mendapatkan data barang dari database berdasarkan kode barang
            $barang = $barangModel->where('kode_barang', $kode_barang)->first();

            // Memastikan barang tersedia dan stok mencukupi
            if ($barang && $barang['stok'] >= $jumlahList[$index]) {
                $harga_jual = $barang['harga_jual'];
                $harga_beli = $barang['harga_beli'];

                $jumlah = $jumlahList[$index];
                $sub_total = $jumlah * $harga_jual;
                $keuntungan = $jumlah * ($harga_jual - $harga_beli);

                $total_penjualan += $sub_total;

                // Mengurangi stok barang
                $stokBaru = $barang['stok'] - $jumlah;
                $barangModel->update($kode_barang, ['stok' => $stokBaru]);

                // Menyimpan data pemasukan ke kas
                $jumlah_awal = $saldoTerakhir;
                $jumlah_akhir = $saldoTerakhir + $sub_total;
                $saldoTerakhir = $jumlah_akhir; // Update saldo terakhir

                $dataKas = [
                    'cek_sub' => $sub_total,
                    'tanggal' =>  date('Y-m-d H:i:s'),
                    'jenis_transaksi' => 'penerimaan',
                    'keterangan' => 'Penjualan barang tes- ' . $barang['nama_brg'],
                    'jumlah_awal' => $jumlah_awal,
                    'jumlah_akhir' => $jumlah_akhir,
                    'saldo_terakhir' => $saldoTerakhir,
                ];

                // Simpan data pemasukan ke kas dan dapatkan id_kas
                $id_kas = $KasModel->insert($dataKas);

                $dataTransaksi = [
                    'cek_sub' => $sub_total,
                    'tanggal_barang_keluar' => date('Y-m-d H:i:s'),
                    'kode_barang' => $kode_barang,
                    'stok' => $jumlah,
                    'jenis_transaksi' => 'pengeluaran',
                    'informasi_tambahan' => 'Penjualan barang tes- ' . $barang['nama_brg'],
                    'jumlah_perubahan' => $jumlah,
                ];

                // Simpan data transaksi barang dan dapatkan id_transaksi
                $id_transaksi = $TransaksiBarangModel->insert($dataTransaksi);

                $dataPemasukan = [
                    'tanggal' => date('Y-m-d H:i:s'),
                    'keterangan' => 'Penjualan barang tes- ' . $barang['nama_brg'],
                    'id_kas' => $id_kas,
                    'id_penjualan_barang' => $kode_penjualan,
                ];

                $PemasukanModel->insert($dataPemasukan);

                // Menyimpan detail penjualan barang dengan id_kas dan id_transaksi
                $dataDetailPenjualan = [
                    'kode_barang' => $kode_barang,
                    'jumlah' => $jumlah,
                    'sub_total' => $sub_total,
                    'keuntungan' => $keuntungan,
                    'id_penjualan_barang' => $kode_penjualan,
                    'id_transaksi' => $id_transaksi,
                    'id_kas' => $id_kas,
                ];

                // Simpan detail penjualan barang
                $id_detail_penjualan_barang = $this->detailPenjualanBarangModel->insert($dataDetailPenjualan);

            } else {
                // Jika stok tidak mencukupi, tampilkan pesan kesalahan
                session()->setFlashdata('msg', 'Stok barang tidak mencukupi.');
                return redirect()->back()->withInput();
            }
        }

        // Hitung kembalian
        $jumlah_pembayaran = $total_penjualan;
        $kembalian = $jumlah_uang - $jumlah_pembayaran;
        $jatuh_tempo = date('Y-m-d', strtotime('+30 days'));

        // Set status piutang berdasarkan kembalian
        $status_piutang = ($kembalian < 0) ? 'belum_lunas' : 'lunas';

        // Jumlah yang akan dimasukkan ke dalam kas (jumlah yang diterima dari penjualan)
        $jumlah_masuk_kas = $jumlah_pembayaran;

        // Jika ada piutang (kembalian negatif), tambahkan kembalian ke kas saat piutang dilunasi
        if ($kembalian < 0) {
            $jumlah_masuk_kas += abs($kembalian); // Tambahkan pembayaran piutang ke dalam kas
        }

        // Persiapkan data untuk penyimpanan penjualan barang dengan status piutang
        $penjualanData = [
            'penjualan_barang_id' => $kode_penjualan,
            'tanggal_penjualan' => date('Y-m-d H:i:s'),
            'id_pelanggan' => $idPelanggan,
            'total_penjualan' => $total_penjualan,
            'jumlah_uang' => $jumlah_uang,
            'status_piutang' => $status_piutang,
        ];

        // Simpan data penjualan barang dengan total penjualan dan status piutang
        $this->PenjualanBarangModel->insert($penjualanData);

        // Update saldo kas
        $jumlah_awal_kas = $saldoTerakhir;
        $jumlah_akhir_kas = $saldoTerakhir + $jumlah_masuk_kas;
        $saldoTerakhir = $jumlah_akhir_kas;

        // Simpan data pemasukan ke kas
        $dataKas = [
            'cek_sub' => $jumlah_masuk_kas,
            'tanggal' => date('Y-m-d'),
            'jenis_transaksi' => 'penerimaan',
            'keterangan' => 'Penjualan barang - ' . $kode_penjualan,
            'jumlah_awal' => $jumlah_awal_kas,
            'jumlah_akhir' => $jumlah_akhir_kas,
            'saldo_terakhir' => $saldoTerakhir,
        ];

        // Simpan data pemasukan ke kas dan dapatkan id_kas
        $id_kas = $KasModel->insert($dataKas);

        // Jika ada piutang, simpan data pembayaran piutang
        if ($kembalian < 0) {
            $dataPiutang = [
                'tanggal_piutang' => date('Y-m-d'),
                'keterangan' => 'Piutang dari penjualan dengan ID ' . $kode_penjualan,
                'jatuh_tempo' => $jatuh_tempo,
                'jumlah_terbayar' => $jumlah_uang, // Jumlah uang yang dibayarkan
                'jumlah_piutang' => abs($kembalian), // Masukkan nilai positif ke piutang
                'id_pelanggan' => $idPelanggan,
                'id_penjualan_barang' => $kode_penjualan,
                 'created_at' => date('Y-m-d H:i:s'),
            ];

            // Simpan data piutang
            $PiutangModel->insert($dataPiutang);
        }

        session()->setFlashdata('msg', 'Penjualan berhasil dilakukan.');
        return redirect()->to('PenjualanBarangCont/');
    }





    public function ubah($id)
    {
        session();
        $barangList = $this->BarangModel->getBarang();

        // Mengambil data penjualan dari model
        $penjualan = $this->detailPenjualanBarangModel->getDetailpenjualan($id);

        // Dump and die to see if penjualan is null
        if (is_null($penjualan)) {
            dd("penjualan is null for ID: $id");
        }

        // Convert stdClass to array using json_decode with true parameter
        $penjualanArray = json_decode(json_encode($penjualan), true);

        $data = [
            'title' => "Toko Hera Ubah Data penjualan",
            'validation' => \Config\Services::validation(),
            'barangList' => $barangList,
            'pelangganList' => $this->PelangganModel->findAll(),
            'penjualan' => $penjualanArray, // Gunakan array yang telah dikonversi
        ];

        return view('Admin/Penjualan_barang/Edit_penjualan', $data);
    }






    public function updatePenjualanBarang($id)
    {
        $dataPermintaan = [
            'kode_barang' => $this->request->getPost('kode_barang'),
            'jumlah' => $this->request->getPost('jumlah'),
            'perihal' => $this->request->getPost('perihal'),
            'detail' => $this->request->getPost('detail'),
        ];
        // dd($dataPermintaan);

        $this->detailPenjualanBarangModel->update($id, $dataPermintaan);
        $id_permintaan = $this->request->getPost('id_permintaan_barang');
        session()->setFlashdata('msg', 'Permintaan berhasil diperbarui.');
        return redirect()->to('/admin/list_permintaan/' . $id_permintaan);
    }

    public function delete_penjualanBarang($id)
    {
        $PenjualanBarangModel = new PenjualanBarangModel();
        $DetailPenjualanBarangModel = new DetailPenjualanBarangModel();
        $TransaksiBarangModel = new TransaksiBarangModel();
        $KasModel = new KasModel();
        $PemasukanModel = new PemasukanModel();
        $piutangModel = new piutangModel();
        $pembayaranPiutangModel = new pembayaranPiutangModel();

        // Mengambil data penjualan berdasarkan ID
        $penjualan = $PenjualanBarangModel->find($id);

        if (!$penjualan) {
            // Jika penjualan tidak ditemukan, tampilkan pesan error atau lakukan sesuai kebutuhan
            session()->setFlashdata('msg', 'Penjualan tidak ditemukan.');
            return redirect()->back();
        }

        // Menghapus detail penjualan barang terkait penjualan
        $DetailPenjualanBarangModel->where('id_penjualan_barang', $penjualan['penjualan_barang_id'])->delete();

        // Menghapus data transaksi barang terkait penjualan
        $TransaksiBarangModel->where('kode_barang', $penjualan['penjualan_barang_id'])->delete();

        // Menghapus pencatatan pemasukan terkait penjualan
        $piutang = $this->piutangModel->where('id_penjualan_barang', $penjualan['penjualan_barang_id'])->findAll();


        foreach ($piutang as $item) {
            // Hapus pembayaran piutang berdasarkan ID piutang
            $pembayaranPiutangModel->where('id_piutang', $item['id_piutang'])->delete();

            // Hapus piutang
            $this->piutangModel->delete($item['id_piutang']);
        }


        // Mengambil data kas terakhir
        $latestKas = $KasModel->getSaldoTerakhir();
        $saldoTerakhir = $latestKas ? $latestKas['saldo_terakhir'] : 0;

        // Mengambil data piutang terkait penjualan
        $piutang = $piutangModel->where('id_penjualan_barang', $penjualan['penjualan_barang_id'])->first();

        // Mengurangi saldo kas
        $jumlah_masuk_kas = $penjualan['total_penjualan']; // Jumlah yang perlu dikurangi dari saldo kas
        $jumlah_awal_kas = $saldoTerakhir;
        $jumlah_akhir_kas = $saldoTerakhir - $jumlah_masuk_kas;
        $saldoTerakhir = $jumlah_akhir_kas;

        // Menghapus data penjualan barang
        $PenjualanBarangModel->delete($id);

        // Mengurangi saldo kas
        $dataKas = [
            'cek_sub' => -$jumlah_masuk_kas, // Nilai negatif untuk pengurangan
            'tanggal' => date('Y-m-d'),
            'jenis_transaksi' => 'pengeluaran',
            'keterangan' => 'Pembatalan penjualan barang - ' . $penjualan['penjualan_barang_id'],
            'jumlah_awal' => $jumlah_awal_kas,
            'jumlah_akhir' => $jumlah_akhir_kas,
            'saldo_terakhir' => $saldoTerakhir,
        ];

        // Simpan data pengurangan ke kas
        $KasModel->insert($dataKas);

        // Debug: dd semua variabel
        // dd($penjualan, $DetailPenjualanBarangModel, $TransaksiBarangModel, $piutang, $dataKas, $KasModel);

        // Jika ada piutang terkait, hapus data piutang
        if ($piutang) {
            $piutangModel->delete($piutang['id_piutang']);
        }

        session()->setFlashdata('msg', 'Penjualan berhasil dibatalkan.');
        return redirect()->to('PenjualanBarangCont/');
    }









    // Menu Penjualan
    //barang

    //Piutang

    public function piutang()
    {
        // Mengambil model dan koneksi database
        $piutangModel = new piutangModel();
        $kasModel = new KasModel();
        $pelangganModel = new PelangganModel();
        $pembayaranPiutangModel = new pembayaranPiutangModel();

        // Query untuk mengambil data piutang dengan join
        $data['piutangs'] = $piutangModel
                            ->select('piutang.*, pelanggan.nama AS nama_pelanggan')
                            ->join('pelanggan', 'pelanggan.id_pelanggan = piutang.id_pelanggan')
                              ->orderBy('piutang.created_at', 'DESC')
                            ->findAll();

        // Query untuk mengambil data kas
        $latestKas = $kasModel->orderBy('id_kas', 'DESC')->first();
        $saldo_kas = $latestKas ? $latestKas['saldo_terakhir'] : 0;

        // Query untuk mengambil data pembayaran piutang
        $data['pembayaran_piutang'] = $pembayaranPiutangModel->findAll();

        // Query untuk mengambil data pelanggan
        $data['pelangganList'] = $pelangganModel->findAll();
        $data['pelanggan'] = $pelangganModel->findAll();

        // Hitung total piutang
        $total_piutang = 0;
        foreach ($data['piutangs'] as $piutang) {
            $total_piutang += $piutang['jumlah_piutang'];
        }

        // Data tambahan
        $data = [
            'title' => "Data Piutang",
            'validation' => \Config\Services::validation(),
            'data' => $data,
            'total_piutang' => $total_piutang,
            'pelangganList' => $this->PelangganModel->findAll(),
            'saldo_kas' => $saldo_kas, // Tambahkan saldo kas ke data yang akan dikirimkan ke view
        ];

        return view('Admin/Piutang/Index', $data);
    }
    public function bayarPiutang($id_piutang)
    {
        // Load models
        $penjualanBarangModel = new PenjualanBarangModel();
        $piutangModel = new piutangModel();
        $pembayaranPiutangModel = new pembayaranPiutangModel(); // Model for pembayaran_piutang table
        $KasModel = new KasModel();

        // Get piutang data using join query
        $db = \Config\Database::connect();
        $builder = $db->table('piutang');
        $builder->select('piutang.*, penjualan_barang.total_penjualan, penjualan_barang.jumlah_uang, penjualan_barang.penjualan_barang_id');
        $builder->join('penjualan_barang', 'piutang.id_penjualan_barang = penjualan_barang.penjualan_barang_id');
        $builder->where('piutang.id_piutang', $id_piutang);
        $query = $builder->get();
        
        $data = $query->getRowArray();

        // Check if data exists
        if (!$data) {
            // Handle no data found case
            return redirect()->to('/error-page');
        }

        // Calculate remaining piutang
        $totalPenjualan = $data['total_penjualan'];
        $jumlahTerbayar = $data['jumlah_terbayar'];
        $jumlahPiutang = $data['jumlah_piutang'];

        $sisaPiutang = $totalPenjualan - $jumlahTerbayar; // Sisa piutang yang harus dibayar

        // menambahkan ke pembayaran piutang
        $insertData = [
            'id_piutang' => $id_piutang,
            'tanggal_pembayaran' => date('Y-m-d'), // Tanggal pembayaran saat ini
            'jumlah_pembayaran' => $sisaPiutang // Jumlah pembayaran yang dilakukan
        ];

        // Insert data into pembayaran_piutang table
        $pembayaranPiutangModel->insert($insertData);

        // upodate piutang menjadi lunas
        $updatePiutang = [
            'jumlah_piutang' => 0, // Jumlah piutang menjadi 0
            'jumlah_terbayar' => $totalPenjualan, // Jumlah terbayar menjadi total penjualan
            'status_piutang' => 'lunas' // Update status to lunas
        ];

        $piutangModel->update($id_piutang, $updatePiutang);

        // Update status piutang dan jumlah uang
        $updatePenjualan = [
            'status_piutang' => 'lunas',
            'jumlah_uang' => $totalPenjualan // Jumlah uang menjadi total penjualan
        ];

        $penjualanBarangModel->update($data['penjualan_barang_id'], $updatePenjualan);

        // update kas
        $latest_kas = $KasModel->orderBy('id_kas', 'DESC')->first();
        $saldo_terakhir = $latest_kas ? $latest_kas['saldo_terakhir'] : 0;

        $data_pembayaran = [
            'tanggal' => date('Y-m-d'),
            'jenis_transaksi' => 'penerimaan', // Tipe transaksi, misalnya penerimaan
            'keterangan' => 'Pembayaran piutang', // Keterangan transaksi
            'jumlah_awal' => $saldo_terakhir,
            'jumlah_akhir' => $saldo_terakhir + $sisaPiutang, // Saldo terakhir setelah ditambah sisa piutang
            'saldo_terakhir' => $saldo_terakhir + $sisaPiutang // Saldo terakhir baru
        ];

        // Simpan data pembayaran ke dalam tabel kas
        $KasModel->insert($data_pembayaran);

        // Simpan data pembayaran ke dalam tabel kas
        // dd($data, $insertData, $updatePiutang, $updatePenjualan, $sisaPiutang, $data_pembayaran, $saldo_terakhir);

        $KasModel->insert($data_pembayaran);

        // Redirect to view or another page after payment
        return redirect()->to('/PenjualanBarangCont/piutang');
    }

}
