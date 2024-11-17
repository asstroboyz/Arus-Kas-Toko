<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\asetModel;
use App\Models\BarangModel;
use App\Models\detailPenjualanBarangModel;
use App\Models\detailRestokModel;
use App\Models\hutangModel;
use App\Models\KasModel;
use App\Models\masterBarangModel;
use App\Models\modalTokoModel;
use App\Models\PelangganModel;
use App\Models\PemasukanModel;
use App\Models\pembayaranPiutangModel;
use App\Models\pengecekanModel;
use App\Models\PengeluaranModel;
use App\Models\PenjualanBarangModel;
use App\Models\perkiraanModel;
use App\Models\piutangModel;
use App\Models\Profil;
use App\Models\restokModel;
use App\Models\riwayatSaldo;
use App\Models\SaldoModel;
use App\Models\satuanModel;
use App\Models\supplierModel;
use App\Models\tipeBarangModel;
use App\Models\TransaksiBarangModel;
use Mpdf\Mpdf;
use Myth\Auth\Entities\User;
use Myth\Auth\Models\GroupModel;
use Myth\Auth\Models\UserModel;

class Pemilik extends BaseController
{
    protected $db;
    protected $builder;
    protected $BarangModel;
    protected $validation;
    protected $session;
    protected $tipeBarangModel;
    protected $masterBarangModel;
    protected $Profil;
    protected $perkiraanModel;
    protected $pengecekanModel;
    protected $satuanModel;
    protected $TransaksiBarangModel;
    protected $KeuntunganModel;
    protected $PenjualanBarangModel;
    protected $SaldoModel;
    protected $PemasukanModel;
    protected $PengeluaranModel;
    protected $detailPenjualanBarangModel;
    protected $riwayatSaldo;
    protected $PelangganModel;
    protected $asetModel;
    protected $hutangModel;
    protected $modalTokoModel;
    protected $supplierModel;
    protected $restokModel;
    protected $detailRestokModel;
    protected $KasModel;
    protected $piutangModel;
    protected $pembayaranPiutangModel;
    public function __construct()
    {

        $this->riwayatSaldo = new riwayatSaldo();
        $this->pembayaranPiutangModel = new pembayaranPiutangModel();
        $this->piutangModel = new piutangModel();
        $this->Profil = new Profil();
        $this->asetModel = new asetModel();
        $this->hutangModel = new hutangModel();
        $this->modalTokoModel = new modalTokoModel();
        $this->restokModel = new restokModel();
        $this->supplierModel = new supplierModel();
        $this->tipeBarangModel = new tipeBarangModel();
        $this->pengecekanModel = new pengecekanModel();
        $this->BarangModel = new BarangModel();
        $this->satuanModel = new satuanModel();
        $this->KasModel = new KasModel();
        $this->TransaksiBarangModel = new TransaksiBarangModel();
        $this->db = \Config\Database::connect();
        $this->builder = $this->db->table('users');
        $this->validation = \Config\Services::validation();
        $this->session = \Config\Services::session();
        $this->masterBarangModel = new masterBarangModel();
        $this->perkiraanModel = new perkiraanModel();
        $this->PelangganModel = new PelangganModel();
        $this->SaldoModel = new SaldoModel();
        $this->PemasukanModel = new PemasukanModel();
        $this->PengeluaranModel = new PengeluaranModel();
        $this->PenjualanBarangModel = new PenjualanBarangModel();
        $this->detailPenjualanBarangModel = new detailPenjualanBarangModel();
        $this->detailRestokModel = new detailRestokModel();
    }

    public function index()
    {
        $latestKas = $this->KasModel->orderBy('id_kas', 'DESC')->first();

        // Mendapatkan saldo terakhir
        $saldoTerakhir = $latestKas ? $latestKas['saldo_terakhir'] : 0;

        // Menghitung jumlah inventaris
        $dataInventaris = $this->db->table('penjualan_barang')->get()->getResult();

        // Menghitung stok barang yang dibawah 10
        $queryBarangStokDibawah10 = $this->db->table('barang')->where('stok <', 10)->get()->getResult();
        $stokdibawah10 = count($queryBarangStokDibawah10);

        // Menghitung total penjualan barang dalam 24 jam terakhir
        $waktu24JamYangLalu = date('Y-m-d H:i:s', strtotime('-24 hours'));
        $totalPenjualan24Jam = $this->db->table('penjualan_barang')->where('tanggal_penjualan >=', $waktu24JamYangLalu)->countAllResults();

        $data = [
            'title' => 'Toko Hera - Home',
            'saldo_terakhir' => $saldoTerakhir,
            'stokdibawah10' => $stokdibawah10,
            'totalPenjualan24Jam' => $totalPenjualan24Jam,
        ];

        return view('Pemilik/Home/Index', $data);
    }

    
    public function profil()
    {
        $data['title'] = 'User Profile ';
        $userlogin = user()->username;
        $userid = user()->id;
        $role = $this->db->table('auth_groups_users')->where('user_id', $userid)->get()->getRow();
        $role == '1' ? $role_echo = 'Admin' : $role_echo = 'Pemilik'; // $data['title'] = 'User Profile ';
        $userlogin = user()->username;
        $userid = user()->id;

        // Mengambil data role dari tabel auth_groups_users
        $roleData = $this->db->table('auth_groups_users')->where('user_id', $userid)->get()->getRow();

        // Memeriksa apakah data role ditemukan
        if ($roleData) {

            $adminRoleId = 1;
            $petugasPengadaan = 2;

            // Menentukan status role berdasarkan ID role
            if ($roleData->group_id == $adminRoleId) {
                $role_echo = 'Admin';
            } elseif ($roleData->group_id == $petugasPengadaan) {
                $role_echo = 'Petugas Pengadaan';
            } else {
                $role_echo = 'Pemilik';
            }
        } else {
            // Jika data role tidak ditemukan, mengatur nilai default sebagai 'Pemilik'
            $role_echo = 'Pemilik';
        }

        // $data = $this->db->table('permintaan_barang');
        // $query1 = $data->where('id_user', $userid)->get()->getResult();
        $builder = $this->db->table('users');
        $builder->select('id,username,fullname,email,created_at,foto');
        $builder->where('username', $userlogin);
        $query = $builder->get();
        // $semua = count($query1);
        $data = [
            // 'semua' => $semua,
            'user' => $query->getRow(),
            'title' => 'Profil - Toko Hera',
            'role' => $role_echo,

        ];

        return view('Pemilik/Home/Profil', $data);
    }

    public function simpanProfile($id)
    {
        $userlogin = user()->username;
        $builder = $this->db->table('users');
        $builder->select('*');
        $query = $builder->where('username', $userlogin)->get()->getRowArray();

        $foto = $this->request->getFile('foto');
        if ($foto->getError() == 4) {
            $this->Profil->update($id, [
                'email' => $this->request->getPost('email'),
                'username' => $this->request->getPost('username'),
                'fullname' => $this->request->getPost('fullname'),
            ]);
        } else {

            $nama_foto = 'PemilikFOTO' . $this->request->getPost('username') . '.' . $foto->guessExtension();
            if (!(empty($query['foto']))) {
                unlink('uploads/profile/' . $query['foto']);
            }
            $foto->move('uploads/profile', $nama_foto);

            $this->Profil->update($id, [
                'email' => $this->request->getPost('email'),
                'username' => $this->request->getPost('username'),
                'fullname' => $this->request->getPost('fullname'),
                'foto' => $nama_foto,
            ]);
        }
        session()->setFlashdata('msg', 'Profil Pemilik  berhasil Diubah');
        return redirect()->to(base_url('Pemilik/profil/' . $id));
    }
    public function updatePassword($id)
    {
        $passwordLama = $this->request->getPost('passwordLama');
        $passwordbaru = $this->request->getPost('passwordBaru');
        $konfirm = $this->request->getPost('konfirm');

        if ($passwordbaru != $konfirm) {
            session()->setFlashdata('error-msg', 'Password Baru tidak sesuai');
            return redirect()->to(base_url('pemilik/profil/' . $id));
        }

        $builder = $this->db->table('users');
        $builder->where('id', user()->id);
        $query = $builder->get()->getRow();
        $verify_pass = password_verify(base64_encode(hash('sha384', $passwordLama, true)), $query->password_hash);

        if ($verify_pass) {
            $users = new UserModel();
            $entity = new \Myth\Auth\Entities\User();

            $entity->setPassword($passwordbaru);
            $hash = $entity->password_hash;
            $users->update($id, ['password_hash' => $hash]);
            session()->setFlashdata('msg', 'Password berhasil Diubah');
            return redirect()->to('/pemilik/profil/' . $id);
        } else {
            session()->setFlashdata('error-msg', 'Password Lama tidak sesuai');
            return redirect()->to(base_url('pemilik/profil/' . $id));
        }
    }

    
    public function perkiraan()
    {
        $perkiraanModel = new perkiraanModel();
        $dataperkiraan = $perkiraanModel
        ->select('perkiraan_penjualan.kode_barang, perkiraan_penjualan.id_satuan, perkiraan_penjualan.metode_perkiraan, perkiraan_penjualan.periode_perkiraan, perkiraan_penjualan.hasil_perkiraan, perkiraan_penjualan.created_at, barang.nama_brg, satuan.nama_satuan, barang.merk, barang.jenis_brg')
        ->join('barang', 'barang.kode_barang = perkiraan_penjualan.kode_barang')
        ->join('satuan', 'satuan.satuan_id = barang.id_satuan')
        ->orderBy('perkiraan_penjualan.created_at', 'DESC')
        ->findAll();

        // Data yang akan dilewatkan ke view
        $data = [
            'title' => 'Perkiraan Penjualan Barang',
            'perkiraan' => $dataperkiraan,
        ];

        return view('Pemilik/Perkiraan/Index', $data);
    }

    public function tambah_perkiraan()
    {
        $data = [
            'validation' => $this->validation,
            'title' => 'Tambah perkiraan penjualan',
            'barangList' => $this->BarangModel
                ->select('barang.*,barang.nama_brg, satuan.nama_satuan, barang.merk,')
                ->join('satuan', 'satuan.satuan_id = barang.id_satuan')

                ->findAll(),
            'selectedBarang' => null,
        ];

        $kode_barang = $this->request->getPost('kode_barang');
        if ($kode_barang) {
            $selectedBarang = $this->BarangModel->find($kode_barang);
            if ($selectedBarang) {
                $data['selectedBarang'] = $selectedBarang;
            }
        }

        return view('Pemilik/Perkiraan/Tambah_perkiraan', $data);
    }
    public function save_perkiraan()
    {
        $kode_barang = $this->request->getPost('kode_barang');
        $tanggal_mulai = $this->request->getPost('tanggal_mulai');
        $tanggal_akhir = $this->request->getPost('tanggal_akhir');
        $metode_perkiraan = $this->request->getPost('metode_perkiraan');
        $id_satuan = $this->request->getPost('id_satuan');

        // Validasi input
        $validationRules = [
            'kode_barang' => 'required',
            'tanggal_mulai' => 'required',
            'tanggal_akhir' => 'required',
            'metode_perkiraan' => 'required',
            'id_satuan' => 'required',
        ];

        if (!$this->validate($validationRules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        // Hitung jumlah hari
        $jumlah_hari = (strtotime($tanggal_akhir) - strtotime($tanggal_mulai)) / (60 * 60 * 24);

        // Validasi tanggal akhir harus setelah tanggal mulai
        if ($jumlah_hari <= 0) {
            session()->setFlashdata('msg', 'Tanggal akhir harus setelah tanggal mulai.');
            return redirect()->back()->withInput();
        }

        // Ambil data historis penjualan berdasarkan kode barang yang dipilih
        $historical_data = $this->detailPenjualanBarangModel->getHistoricalData($kode_barang, $jumlah_hari);

        // Debug: Cek kode barang yang dipilih
        // dd($historical_data);

        if (empty($historical_data)) {
            session()->setFlashdata('msg', 'Tidak ada data historis penjualan untuk barang ini.');
            return redirect()->back()->withInput();
        }

        // Lakukan proses perkiraan berdasarkan metode
        $forecast = 0;

        switch ($metode_perkiraan) {
            case 'moving_average':
                // Proses moving average
                $total_sales = array_sum(array_column($historical_data, 'jumlah'));
                $forecast = $total_sales / count($historical_data);
                break;
            case 'exponential_smoothing':
                // Proses exponential smoothing
                $initial_value = array_sum(array_column($historical_data, 'jumlah')) / count($historical_data);
                $alpha = 0.2;
                $smoothed_value = $initial_value;
                foreach ($historical_data as $data) {
                    $smoothed_value = $alpha * $data['jumlah'] + (1 - $alpha) * $smoothed_value;
                }
                $forecast = $smoothed_value;
                break;
            case 'time_series':
                // Proses time series
                $forecast = array_sum(array_column($historical_data, 'jumlah')) / count($historical_data);
                break;
            default:
                session()->setFlashdata('msg', 'Metode perkiraan tidak valid.');
                return redirect()->back()->withInput();
        }

        // Simpan data perkiraan
        $perkiraanModel = new PerkiraanModel();
        try {
            $perkiraanModel->savePerkiraan([
                'kode_barang' => $kode_barang,
                'periode_perkiraan' => $jumlah_hari,
                'metode_perkiraan' => $metode_perkiraan,
                'hasil_perkiraan' => $forecast,
                'created_at' => date('Y-m-d H:i:s'),
                'id_satuan' => $id_satuan,
            ]);
            session()->setFlashdata('msg', 'Perkiraan penjualan berhasil disimpan.');
            return redirect()->to(base_url('Pemilik/perkiraan'));
        } catch (\Exception $e) {
            session()->setFlashdata('msg', 'Gagal menyimpan perkiraan penjualan. Silakan coba lagi.');
            return redirect()->back()->withInput();
        }
    }


    public function lap_barang()
    {
        $data = [
            'title' => 'Toko Hera - Laporan Barang',
        ];

        return view('Pemilik/Laporan/Home_barang', $data);
    }
    public function lap_arus_kas()
    {
        $data = [
            'title' => 'Toko Hera - Laporan Arus Kas',
        ];

        return view('Pemilik/Laporan/Home_arus', $data);
    }
    public function lap_analisa_arus_kas()
    {
        $data = [
            'title' => 'Toko Hera - Laporan Analisa arus kas',
        ];

        return view('Pemilik/Laporan/Home_analisa', $data);
    }
    public function lap_laba_rugi()
    {
        $data = [
            'title' => 'Toko Hera - Laporan Laba rugi',
        ];

        return view('Pemilik/Laporan/Home_laba', $data);
    }

    public function cetakDataBarang()
    {
        // Ambil tanggal mulai dan tanggal akhir dari form
        $tanggalMulai = $this->request->getPost('tanggal_mulai');
        $tanggalAkhir = $this->request->getPost('tanggal_akhir');

        // Pastikan kedua tanggal tidak kosong
        if (empty($tanggalMulai) || empty($tanggalAkhir)) {
            return redirect()->back()->withInput()->with('error', 'Pilih rentang waktu terlebih dahulu.');
        }

        $barangModel = new BarangModel();
        $satuanModel = new satuanModel(); // Tambahkan ini

        // Ambil data persediaan barang berdasarkan rentang waktu
        $data['barang'] = $barangModel
            ->select('barang.*,
                   SUM(CASE WHEN transaksi_barang.jenis_transaksi = "masuk" THEN transaksi_barang.jumlah_perubahan ELSE 0 END) AS total_masuk,
                   SUM(CASE WHEN transaksi_barang.jenis_transaksi = "keluar" THEN transaksi_barang.jumlah_perubahan ELSE 0 END) AS total_keluar,
                   satuan.nama_satuan') // Tambahkan kolom nama_satuan
            ->join('transaksi_barang', 'transaksi_barang.kode_barang = barang.kode_barang', 'left')
            ->join('satuan', 'satuan.satuan_id = barang.id_satuan', 'left') // Join dengan tabel satuan
            ->where('transaksi_barang.tanggal_barang_keluar >=', $tanggalMulai)
            ->where('transaksi_barang.tanggal_barang_keluar <=', $tanggalAkhir)
            ->groupBy('barang.kode_barang') // Assuming kode_barang is the primary key of barang table
            ->findAll();
        $db = \Config\Database::connect();

        $builder = $db->table('users');
        $builder->select('users.fullname');
        $builder->join('auth_groups_users', 'auth_groups_users.user_id = users.id');
        $builder->where('auth_groups_users.group_id', 3);
        $query = $builder->get();
        $pemilikData = $query->getRow();
        $pemilikName = $pemilikData ? $pemilikData->fullname : 'Nama Pemilik Tidak Ditemukan';

        // Kirim data tanggal mulai dan tanggal akhir ke view
        $data['pemilikName'] = $pemilikName;
        // Kirim data tanggal mulai dan tanggal akhir ke view
        $data['tanggalMulai'] = $tanggalMulai;
        $data['tanggalAkhir'] = $tanggalAkhir;

        // Load view untuk cetak laporan

        $mpdf = new \Mpdf\Mpdf();
        $mpdf->showImageErrors = true;
        $html = view('Pemilik/Laporan/Lap_barang', $data);

        $mpdf->setAutoPageBreak(true);

        $options = [
            'curl' => [
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
            ],
        ];

        $mpdf->AddPageByArray(['orientation' => 'L'] + $options);

        $mpdf->WriteHtml($html);
        $this->response->setHeader('Content-Type', 'application/pdf');
        $mpdf->Output('Laporan Laba Rugi.pdf', 'I');

    }

    public function cetakLabaRugi()
    {
        $tanggalMulai = $this->request->getPost('tanggal_mulai');
        $tanggalAkhir = $this->request->getPost('tanggal_akhir');

        // Validasi input
        if (!$tanggalMulai || !$tanggalAkhir) {
            return redirect()->back()->with('error', 'Tanggal mulai dan akhir harus diisi.');
        }

        // Query untuk mendapatkan data penjualan bersih dari model PenjualanBarangModel
        $penjualanModel = new PenjualanBarangModel();
        $totalPenjualan = $penjualanModel
            ->selectSum('total_penjualan')
            ->where('tanggal_penjualan >=', $tanggalMulai)
            ->where('tanggal_penjualan <=', $tanggalAkhir)
            ->first()['total_penjualan'];

        // Query untuk mendapatkan total harga beli barang yang terjual dari model detailPenjualanBarangModel dan BarangModel

        $db = \Config\Database::connect();
        $builder = $db->table('detail_penjualan_barang');
        $totalHPP = $builder
            ->select('SUM(barang.harga_beli * detail_penjualan_barang.jumlah) AS total_hpp')
            ->join('barang', 'barang.kode_barang = detail_penjualan_barang.kode_barang')
            ->join('penjualan_barang', 'penjualan_barang.penjualan_barang_id = detail_penjualan_barang.id_penjualan_barang')
            ->where('penjualan_barang.tanggal_penjualan >=', $tanggalMulai)
            ->where('penjualan_barang.tanggal_penjualan <=', $tanggalAkhir)
            ->get()
            ->getRow()
            ->total_hpp;
        // Query untuk mendapatkan total biaya operasional dari model PengeluaranModel
        $pengeluaranModel = new PengeluaranModel();
        $totalBiayaOperasional = $pengeluaranModel
            ->selectSum('jumlah')
            ->where('tanggal >=', $tanggalMulai)
            ->where('tanggal <=', $tanggalAkhir)
            ->first()['jumlah'];

      
        $gaji = $pengeluaranModel
               ->selectSum('jumlah')
               ->where('tanggal >=', $tanggalMulai)
               ->where('tanggal <=', $tanggalAkhir)
               ->where('keterangan', 'gaji')
               ->first()['jumlah'];

      
        $listrik = $pengeluaranModel
               ->selectSum('jumlah')
               ->where('tanggal >=', $tanggalMulai)
               ->where('tanggal <=', $tanggalAkhir)
               ->where('keterangan', 'listrik')
               ->first()['jumlah'];

      
        $air = $pengeluaranModel
               ->selectSum('jumlah')
               ->where('tanggal >=', $tanggalMulai)
               ->where('tanggal <=', $tanggalAkhir)
               ->where('keterangan', 'air')
               ->first()['jumlah'];

     
        // $pembelian_restok = $pengeluaranModel
        //     ->selectSum('jumlah')
        //     ->where('tanggal >=', $tanggalMulai)
        //     ->where('tanggal <=', $tanggalAkhir)
        //     ->where(function($builder) {
        //         $builder->like('keterangan', 'Pembelian', 'both'); // Mengandung kata 'Pembelian'
        //         $builder->orLike('keterangan', 'Restok', 'both'); // Atau mengandung kata 'Restok'
        //     })
        //     ->first()['jumlah'];
        $beliDanRestok = $pengeluaranModel
        ->selectSum('jumlah')
        ->where('tanggal >=', $tanggalMulai)
        ->where('tanggal <=', $tanggalAkhir)
        ->groupStart()
            ->like('keterangan', 'Pembelian')
            ->orLike('keterangan', 'Restok')
            ->orLike('keterangan', 'lainnya')
        ->groupEnd()
        ->first()['jumlah'];

        $labaKotor = $totalPenjualan - $totalHPP;
        $labaBersih = $labaKotor - $totalBiayaOperasional;
 $builder = $db->table('users');
        $builder->select('users.fullname');
        $builder->join('auth_groups_users', 'auth_groups_users.user_id = users.id');
        $builder->where('auth_groups_users.group_id', 3);
        $query = $builder->get();
        $pemilikData = $query->getRow();
        $pemilikName = $pemilikData ? $pemilikData->fullname : 'Nama Pemilik Tidak Ditemukan';
        // Menyiapkan data untuk dikirim ke view
        $data['pemilikName'] = $pemilikName;
        $data['listrik'] = $listrik;
        $data['air'] = $air;
        $data['beliDanRestok'] = $beliDanRestok;
        $data['gaji'] = $gaji;
        $data['tanggalMulai'] = $tanggalMulai;
        $data['tanggalAkhir'] = $tanggalAkhir;
        $data['totalPenjualan'] = $totalPenjualan;
        $data['totalHPP'] = $totalHPP;
        $data['totalBiayaOperasional'] = $totalBiayaOperasional;
        $data['labaKotor'] = $labaKotor;
        $data['labaBersih'] = $labaBersih;
        // dd($data);
        // Load view dan generate PDF
        $mpdf = new \Mpdf\Mpdf();
        $mpdf->showImageErrors = true;
        $html = view('Pemilik/Laporan/Lap_labaRugi', $data);

        $mpdf->setAutoPageBreak(true);

        $options = [
            'curl' => [
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
            ],
        ];

        $mpdf->AddPageByArray(['orientation' => 'P'] + $options);

        $mpdf->WriteHtml($html);
        $this->response->setHeader('Content-Type', 'application/pdf');
        $mpdf->Output('Laporan Laba Rugi.pdf', 'I');

    }

    // Fungsi untuk mendapatkan stok barang saat ini
    private function getStokSaatIni()
    {
        // Query untuk mendapatkan total stok barang saat ini
        $barangModel = new BarangModel();
        $totalStok = $barangModel
            ->selectSum('stok')
            ->where('stok >', 0)
            ->first();

        return $totalStok['stok'] ?? 0;
    }

    public function cetakArusKas()
    {
        $tanggalMulai = $this->request->getPost('tanggal_mulai');
        $tanggalAkhir = $this->request->getPost('tanggal_akhir');

        // Validasi input
        if (!$tanggalMulai || !$tanggalAkhir) {
            return redirect()->back()->with('error', 'Tanggal mulai dan akhir harus diisi.');
        }

        // Load models
        $db = \Config\Database::connect();

        $penjualanModel = new PenjualanBarangModel();
        $restokModel = new restokModel();
        $kasModel = new KasModel();
        $pengeluaranModel = new PengeluaranModel();
        $asetModel = new asetModel();
        $hutangModel = new hutangModel();
        $piutangModel = new piutangModel();
        $pembayaranPiutangModel = new pembayaranPiutangModel();
        $modalTokoModel = new modalTokoModel();

        $latest_kas = $kasModel->orderBy('id_kas', 'DESC')->first();
        $saldo_terakhir = $latest_kas ? $latest_kas['saldo_terakhir'] : 0;

        $totalPiutang = $piutangModel->getTotalPiutangByDateRange($tanggalMulai, $tanggalAkhir);
        $totalPenjualan = $penjualanModel->getTotalPenjualanByDateRange($tanggalMulai, $tanggalAkhir);
        $totalPemasukan = $kasModel->getTotalPemasukanByDateRange($tanggalMulai, $tanggalAkhir);
        $totalPengeluaran = $pengeluaranModel->getTotalPengeluaranByDateRange($tanggalMulai, $tanggalAkhir);
        $totalPembelianAset = $asetModel->getTotalPembelianAsetByDateRange();
        $totalModal = $modalTokoModel->getTotalModalByDateRange();
        $totalPenerimaanPinjaman = $hutangModel->getTotalPenerimaanPinjamanByDateRange($tanggalMulai, $tanggalAkhir);
        $totalPembayaranPiutang = $pembayaranPiutangModel->getTotalPembayaranPiutangByDateRange($tanggalMulai, $tanggalAkhir);
$totalKasMasuk = $this->db->table('kas_toko')
            ->select('SUM(jumlah_akhir - jumlah_awal) AS total_masuk', false)
            ->where('jenis_transaksi', 'penerimaan')
            ->get()
            ->getRow()->total_masuk;
        $totalKasKeluar = $this->db->table('kas_toko')
            ->select('SUM(jumlah_awal - jumlah_akhir) AS total_keluar', false)
            ->where('jenis_transaksi', 'pengeluaran')
            ->get()
            ->getRow()->total_keluar;
        // Perhitungan arus kas
        $arusKasOperasional = $totalPenjualan + $totalPemasukan - $totalPengeluaran;
        $arusKasInvestasi = 0 - $totalPembelianAset;
        $arusKasPendanaan = $totalPenerimaanPinjaman - $totalPembayaranPiutang;
        $arusKasBersih = $arusKasOperasional + $arusKasInvestasi + $arusKasPendanaan;
        $total_keseluruhan = $totalPiutang + $totalPembelianAset + $saldo_terakhir;
        $latest_kas = $kasModel->orderBy('id_kas', 'DESC')->first();
        $saldo_terakhir = $latest_kas ? $latest_kas['saldo_terakhir'] : 0;
        $builder = $db->table('users');
        $builder->select('users.fullname');
        $builder->join('auth_groups_users', 'auth_groups_users.user_id = users.id');
        $builder->where('auth_groups_users.group_id', 3);
        $query = $builder->get();
        $pemilikData = $query->getRow();
        $pemilikName = $pemilikData ? $pemilikData->fullname : 'Nama Pemilik Tidak Ditemukan';

        // Menyiapkan data untuk ditampilkan dalam view
        $data = [
              'pemilikName' => $pemilikName,
              'totalKasMasuk' => $totalKasMasuk,
            'totalKasKeluar' => $totalKasKeluar,
            'totalPenjualan' => $totalPenjualan,
            'totalPemasukan' => $totalPemasukan,
            'totalPengeluaran' => $totalPengeluaran,
            'totalAset' => $totalPembelianAset,
            'hutang' => $totalPenerimaanPinjaman,
            'totalPembayaranPiutang' => $totalPembayaranPiutang,
            'arusKasOperasional' => $arusKasOperasional,
            'arusKasInvestasi' => $arusKasInvestasi,
            'arusKasPendanaan' => $arusKasPendanaan,
            'arusKasBersih' => $arusKasBersih,
            'kas' => $saldo_terakhir, // Pastikan variabel ini ada di sini
            'tanggalMulai' => $tanggalMulai,
            'tanggalAkhir' => $tanggalAkhir,
            'totalPiutang' => $totalPiutang,
            'totalModalToko' => $totalModal,
            'total_keseluruhan' => $totalPiutang + $totalPembelianAset + $saldo_terakhir,
        ];
        // dd($data);

        // Menyiapkan data untuk ditampilkan
        // $data = [
        //     'totalPenjualan' => $pembayaranPiutangModel,
        //     'totalPemasukan' => $totalPemasukan,
        //     'totalRestok' => $totalRestok,
        //     'totalPengeluaran' => $totalPengeluaran,
        //     'totalPembelianAset' => $totalPembelianAset,
        //     'totalPenerimaanPinjaman' => $totalPenerimaanPinjaman,
        //     'totalPiutang' => $totalPiutang,
        //     'totalHutang' => $totalHutang,
        //     'totalPembayaranPiutang' => $totalPembayaranPiutang,
        //     'arusKasOperasional' => $arusKasOperasional,
        //     // 'arusKasInvestasi' => $arusKasInvestasi,
        //     'arusKasPendanaan' => $arusKasPendanaan,
        //     'arusKasBersih' => $arusKasBersih,
        // ];

        // Menampilkan hasil sementara untuk debug
        // dd($data);

        // Load view untuk cetak laporan

        $mpdf = new \Mpdf\Mpdf();
        $mpdf->showImageErrors = true;
        $html = view('Pemilik/Laporan/Lap_aruskas', $data);

        $mpdf->setAutoPageBreak(true);

        $options = [
            'curl' => [
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
            ],
        ];

        $mpdf->AddPageByArray(['orientation' => 'L'] + $options);

        $mpdf->WriteHtml($html);
        $this->response->setHeader('Content-Type', 'application/pdf');
        $mpdf->Output('Laporan Laba Rugi.pdf', 'I');

    }

    public function analisisArusKas()
    {
        $tanggalMulai = $this->request->getPost('tanggal_mulai');
        $tanggalAkhir = $this->request->getPost('tanggal_akhir');
        //   dd($tanggalMulai,$tanggalAkhir);
        // Validasi input
        if (!$tanggalMulai || !$tanggalAkhir) {
            return redirect()->back()->with('error', 'Tanggal mulai dan akhir harus diisi.');
        }

        $penjualanModel = new PenjualanBarangModel();
        $totalPenjualan = $penjualanModel
            ->selectSum('total_penjualan')
            ->where('tanggal_penjualan >=', $tanggalMulai)
            ->where('tanggal_penjualan <=', $tanggalAkhir)
            ->first()['total_penjualan'];

        // Query untuk mendapatkan total harga beli barang dari model BarangModel
        $barangModel = new BarangModel();
        $totalHargaBeli = $barangModel
            ->selectSum('harga_beli')
            ->first()['harga_beli'];

        // Query untuk mendapatkan total biaya operasional dari model PengeluaranModel
        $pengeluaranModel = new PengeluaranModel();
        $totalBiayaOperasional = $pengeluaranModel
            ->selectSum('jumlah')
            ->where('tanggal >=', $tanggalMulai)
            ->where('tanggal <=', $tanggalAkhir)
            ->first()['jumlah'];

        // Perhitungan total arus kas
        $totalArusKas = $totalPenjualan - $totalHargaBeli - $totalBiayaOperasional;

        // Query untuk mendapatkan data penjualan tahun sebelumnya
        $tanggalMulaiTahunSebelumnya = date('Y-m-d', strtotime($tanggalMulai . ' -1 year'));
        $tanggalAkhirTahunSebelumnya = date('Y-m-d', strtotime($tanggalAkhir . ' -1 year'));
        $totalPenjualanTahunSebelumnya = $penjualanModel
            ->selectSum('total_penjualan')
            ->where('tanggal_penjualan >=', $tanggalMulaiTahunSebelumnya)
            ->where('tanggal_penjualan <=', $tanggalAkhirTahunSebelumnya)
            ->first()['total_penjualan'];

        // Query untuk mendapatkan total harga beli barang tahun sebelumnya
        $totalHargaBeliTahunSebelumnya = $barangModel
            ->selectSum('harga_beli')
            ->where('created_at >=', $tanggalMulaiTahunSebelumnya)
            ->where('created_at <=', $tanggalAkhirTahunSebelumnya)
            ->first()['harga_beli'];

        // Query untuk mendapatkan total biaya operasional tahun sebelumnya
        $totalBiayaOperasionalTahunSebelumnya = $pengeluaranModel
            ->selectSum('jumlah')
            ->where('tanggal >=', $tanggalMulaiTahunSebelumnya)
            ->where('tanggal <=', $tanggalAkhirTahunSebelumnya)
            ->first()['jumlah'];

        // Perhitungan total aktivitas operasional
        $totalAktivitasOperasional = $totalPenjualan - $totalHargaBeli - $totalBiayaOperasional;
        $totalAktivitasOperasionalTahunSebelumnya = $totalPenjualanTahunSebelumnya - $totalHargaBeliTahunSebelumnya - $totalBiayaOperasionalTahunSebelumnya;

        // Query untuk mendapatkan total penerimaan penjualan
        $pemasukanModel = new PemasukanModel();
        $totalPenerimaanPenjualan = $pemasukanModel
            ->selectSum('jumlah')
            ->where('tanggal >=', $tanggalMulai)
            ->where('tanggal <=', $tanggalAkhir)
            ->where('keterangan', 'Penjualan')
            ->first()['jumlah'];

        // Query untuk mendapatkan total penerimaan penjualan aset tetap
        $totalPenerimaanAsetTetap = $pemasukanModel
            ->selectSum('jumlah')
            ->where('tanggal >=', $tanggalMulai)
            ->where('tanggal <=', $tanggalAkhir)
            ->where('keterangan', 'Penerimaan Penjualan Aset Tetap')
            ->first()['jumlah'];

        // Query untuk mendapatkan total penerimaan penjualan aset tetap tahun sebelumnya
        $totalPenerimaanAsetTetapTahunSebelumnya = $pemasukanModel
            ->selectSum('jumlah')
            ->where('tanggal >=', $tanggalMulaiTahunSebelumnya)
            ->where('tanggal <=', $tanggalAkhirTahunSebelumnya)
            ->where('keterangan', 'Penerimaan Penjualan Aset Tetap')
            ->first()['jumlah'];

        // Query untuk mendapatkan total pembayaran pembelian aset tetap tahun sebelumnya
        $totalPembayaranAsetTetapTahunSebelumnya = $pengeluaranModel
            ->selectSum('jumlah')
            ->where('tanggal >=', $tanggalMulaiTahunSebelumnya)
            ->where('tanggal <=', $tanggalAkhirTahunSebelumnya)
            ->where('keterangan', 'Pembayaran Pembelian Aset Tetap')
            ->first()['jumlah'];

        // Query untuk mendapatkan total pembayaran pembelian aset tetap
        $totalPembayaranAsetTetap = $pengeluaranModel
            ->selectSum('jumlah')
            ->where('tanggal >=', $tanggalMulai)
            ->where('tanggal <=', $tanggalAkhir)
            ->where('keterangan', 'Pembayaran Pembelian Aset Tetap')
            ->first()['jumlah'];

        $totalArusKasTahunSebelumnya = $totalPenjualanTahunSebelumnya - $totalHargaBeliTahunSebelumnya - $totalBiayaOperasionalTahunSebelumnya;

        // Inisialisasi nilai kasAwal dan kasAwalTahunSebelumnya dengan totalArusKasTahunSebelumnya
        $kasAwal = $kasAwalTahunSebelumnya = $totalArusKasTahunSebelumnya;

        // Perhitungan kasAkhir dan kasAkhirTahunSebelumnya
        $kasAkhir = $kasAwal + $totalArusKas;
        $kasAkhirTahunSebelumnya = $kasAwalTahunSebelumnya + $totalArusKasTahunSebelumnya;

        // Perhitungan total aktivitas investasi
        $totalAktivitasInvestasi = $totalPenerimaanAsetTetap - $totalPembayaranAsetTetap;

        // Perhitungan total aktivitas investasi tahun sebelumnya
        $totalAktivitasInvestasiTahunSebelumnya = $totalPenerimaanAsetTetapTahunSebelumnya - $totalPembayaranAsetTetapTahunSebelumnya;
 $db = \Config\Database::connect();
        $builder = $db->table('users');
        $builder->select('users.fullname');
        $builder->join('auth_groups_users', 'auth_groups_users.user_id = users.id');
        $builder->where('auth_groups_users.group_id', 3);
        $query = $builder->get();
        $pemilikData = $query->getRow();
        $pemilikName = $pemilikData ? $pemilikData->fullname : 'Nama Pemilik Tidak Ditemukan';

        // Menyiapkan data untuk dikirim ke view
        $data['pemilikName'] = $pemilikName;
        // Menyiapkan data untuk dikirim ke view
        $data['kasAwal'] = $kasAwal;
        $data['kasAkhir'] = $kasAkhir;
        $data['kasAwalTahunSebelumnya'] = $kasAwalTahunSebelumnya;
        $data['kasAkhirTahunSebelumnya'] = $kasAkhirTahunSebelumnya;

        $data['tanggalMulai'] = $tanggalMulai;
        $data['tanggalAkhir'] = $tanggalAkhir;
        $data['totalPenjualan'] = $totalPenjualan;
        $data['totalHargaBeli'] = $totalHargaBeli;
        $data['totalBiayaOperasional'] = $totalBiayaOperasional;
        $data['totalArusKas'] = $totalArusKas;
        $data['totalPenerimaanPenjualan'] = $totalPenerimaanPenjualan;
        $data['totalPenerimaanAsetTetap'] = $totalPenerimaanAsetTetap;
        $data['totalPembayaranAsetTetap'] = $totalPembayaranAsetTetap;
        $data['totalPenjualanTahunSebelumnya'] = $totalPenjualanTahunSebelumnya;
        $data['totalHargaBeliTahunSebelumnya'] = $totalHargaBeliTahunSebelumnya;
        $data['totalBiayaOperasionalTahunSebelumnya'] = $totalBiayaOperasionalTahunSebelumnya;
        $data['totalAktivitasOperasional'] = $totalAktivitasOperasional;
        $data['totalAktivitasInvestasi'] = $totalAktivitasInvestasi;
        $data['totalArusKasTahunSebelumnya'] = $totalArusKasTahunSebelumnya;
        $data['totalPembayaranAsetTetapTahunSebelumnya'] = $totalPembayaranAsetTetapTahunSebelumnya;
        $data['totalAktivitasOperasionalTahunSebelumnya'] = $totalAktivitasOperasionalTahunSebelumnya;
        $data['totalPenerimaanAsetTetapTahunSebelumnya'] = $totalPenerimaanAsetTetapTahunSebelumnya;
        $data['totalAktivitasInvestasiTahunSebelumnya'] = $totalAktivitasInvestasiTahunSebelumnya;

        // Load view dan generate PDF
        $mpdf = new \Mpdf\Mpdf();
        $mpdf->showImageErrors = true;
        $html = view('Pemilik/Laporan/Lap_analisisArusKas', $data);

        $mpdf->setAutoPageBreak(true);

        $options = [
            'curl' => [
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
            ],
        ];

        $mpdf->AddPageByArray(['orientation' => 'L'] + $options);

        $mpdf->WriteHtml($html);
        $this->response->setHeader('Content-Type', 'application/pdf');
        $mpdf->Output('Laporan Analisis Arus Kas.pdf', 'I');
    }
}
