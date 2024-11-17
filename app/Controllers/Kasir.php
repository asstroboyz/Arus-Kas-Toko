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
use Myth\Auth\Entities\User;
use Myth\Auth\Models\UserModel;

class Kasir extends BaseController
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
        $saldoTerakhir = $latestKas ? $latestKas['saldo_terakhir'] : 0;

        $queryBarangStokDibawah10 = $this->db->table('barang')->where('stok <', 10)->get()->getResult();
        $stokdibawah10 = count($queryBarangStokDibawah10);

        $waktu24JamYangLalu = date('Y-m-d H:i:s', strtotime('-24 hours'));
        $totalPenjualan24Jam = $this->db->table('penjualan_barang')->where('tanggal_penjualan >=', $waktu24JamYangLalu)->countAllResults();

        $dataPenjualan = $this->PenjualanBarangModel->getAllSales(); // Mengambil semua data penjualan
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
        // dd($dataPenjualan);
        $data = [
            'title' => 'Toko Hera - Home',
            'saldo_terakhir' => $saldoTerakhir,
            'stokdibawah10' => $stokdibawah10,
             'totalKasMasuk' => $totalKasMasuk,
        'totalKasKeluar' => $totalKasKeluar,
            'totalPenjualan24Jam' => $totalPenjualan24Jam,
            'dataPenjualan' => $dataPenjualan,
        ];
        return view('Kasir/Home/Index', $data);
    }

    public function profil()
    {
        $data['title'] = 'User Profile ';
        $userlogin = user()->username;
        $userid = user()->id;
        $role = $this->db->table('auth_groups_users')->where('user_id', $userid)->get()->getRow();
        $role == '1' ? $role_echo = 'kasir' : $role_echo = 'Pemilik'; // $data['title'] = 'User Profile ';
        $userlogin = user()->username;
        $userid = user()->id;

        // Mengambil data role dari tabel auth_groups_users
        $roleData = $this->db->table('auth_groups_users')->where('user_id', $userid)->get()->getRow();

        // Memeriksa apakah data role ditemukan
        if ($roleData) {

            $kasirRoleId = 1;
            $petugasPengadaan = 2;

            // Menentukan status role berdasarkan ID role
            if ($roleData->group_id == $kasirRoleId) {
                $role_echo = 'kasir';
            } elseif ($roleData->group_id == $petugasPengadaan) {
                $role_echo = 'Kasir';
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

        return view('Kasir/Home/Profil', $data);
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

            $nama_foto = 'KasirFOTO' . $this->request->getPost('username') . '.' . $foto->guessExtension();
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
        session()->setFlashdata('msg', 'Profil Kasir  berhasil Diubah');
        return redirect()->to(base_url('Kasir/profil/' . $id));
    }

    public function updatePassword($id)
    {
        $passwordLama = $this->request->getPost('passwordLama');
        $passwordbaru = $this->request->getPost('passwordBaru');
        $konfirm = $this->request->getPost('konfirm');

        if ($passwordbaru != $konfirm) {
            session()->setFlashdata('error-msg', 'Password Baru tidak sesuai');
            return redirect()->to(base_url('Kasir/profil/' . $id));
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
            return redirect()->to('/kasir/profil/' . $id);
        } else {
            session()->setFlashdata('error-msg', 'Password Lama tidak sesuai');
            return redirect()->to(base_url('kasir/profil/' . $id));
        }
    }

    // MENU ASET
    public function aset()
    {
        $data = [
            'title' => 'Aset Toko',
            'aset' => $this->asetModel->findAll(),
        ];
        return view('Kasir/Aset/Index', $data);

    }

    public function tambahAset()
    {
        $data = [
            'title' => 'Tambah Aset',
            'validation' => $this->validation,
        ];
        return view('Kasir/Aset/TambahAset', $data);
    }

    public function saveAset()
    {
        if (!$this->validate([

            'nama_aset' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'nama_aset satuan harus diisi',

                ],
            ],
        ])) {
            return redirect()->to('/Kasir/tambahAset')->withInput();
        }
        $data = [
            'nama_aset' => $this->request->getPost('nama_aset'),
            'nilai' => $this->request->getPost('nilai'),

        ];
        // dd($data);
        $this->asetModel->insert($data);

        session()->setFlashdata('pesan', 'Data berhasil ditambahkan');
        return redirect()->to('/Kasir/aset');
    }

    public function editAset($id)
    {
        $aset = $this->asetModel->find($id);

        if (!$aset) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'title' => 'Edit Aset',
            'validation' => $this->validation,
            'aset' => $aset,
        ];

        return view('Kasir/Aset/EditAset', $data);
    }

    public function updateAset($id)
    {
        $data = [
            'nama_aset' => $this->request->getPost('nama_aset'),
            'nilai' => $this->request->getPost('nilai'),
        ];

        $this->asetModel->update($id, $data);

        session()->setFlashdata('pesanBerhasil', 'Data berhasil diupdate');
        return redirect()->to('/Kasir/aset');
    }

    public function deleteAset($id)
    {

        $this->asetModel->delete($id);

        session()->setFlashdata('pesanBerhasil', 'Data berhasil dihapus');
        return redirect()->to('/Kasir/aset');
    }
    // AKHIR MENU ASET

    // MENU HUTANG
    public function hutang()
    {
        // Ambil saldo terakhir dari KasModel
        $latest_kas = $this->KasModel->orderBy('id_kas', 'DESC')->first();

        // Default saldo kas
        $saldo_kas = 0; // Default jika tidak ada saldo kas ditemukan
        if ($latest_kas) {
            $saldo_kas = $latest_kas['saldo_terakhir'];
        }

      
        $hutangs = $this->hutangModel->orderBy('created_at', 'DESC')->findAll();



        // Calculate total hutang
        $total_hutang = 0;
        foreach ($hutangs as $hutang) {
            $total_hutang += $hutang['jumlah'];
        }

        // Calculate total hutang sisa
        $jumlah_sisa = $total_hutang - $saldo_kas;

        // Prepare data to be passed to the view
        $data = [
            'title' => 'Data Hutang',
            'hutangs' => $hutangs,
            'saldo_kas' => $saldo_kas, // Menyertakan saldo kas saat ini
            'jumlah_sisa' => $jumlah_sisa, // Menyertakan total hutang sisa
        ];
        // dd($data);
        return view('kasir/Hutang/Index', $data);
    }

    public function tambahHutang()
    {
        $data = [
            'title' => 'Tambah Hutang',
            'validation' => $this->validation,
        ];
        return view('kasir/Hutang/TambahHutang', $data);
    }

    public function saveHutang()
    {
        if (!$this->validate([
            'keterangan' => 'required',
            'jumlah' => 'required',
            'tanggal' => 'required',
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'keterangan' => $this->request->getPost('keterangan'),
            'jumlah' => $this->request->getPost('jumlah'),
            'tanggal' => $this->request->getPost('tanggal'),
        ];

        $this->hutangModel->insert($data);

        session()->setFlashdata('message', 'Data hutang berhasil ditambahkan');
        return redirect()->to('/kasir/hutang');
    }

    public function bayarHutang($id_hutang)
    {
        // Pastikan validasi disini
        $PengeluaranModel = new PengeluaranModel();
        // Ambil saldo terakhir dari KasModel
        $latest_kas = $this->KasModel->orderBy('id_kas', 'DESC')->first();
        $saldo_terakhir = $latest_kas['saldo_terakhir'];

        // Ambil hutang yang akan dibayar
        $hutang = $this->hutangModel->find($id_hutang);

        // Ambil total hutang
        $total_hutang = $hutang['jumlah'];

        // Validasi jumlah hutang
        if ($total_hutang <= 0) {
            // Handle error jika tidak ada hutang
            return redirect()->back()->with('error', 'Hutang sudah lunas.');
        }

        // Validasi saldo kas
        if ($total_hutang > $saldo_terakhir) {
            // Handle error jika total hutang melebihi saldo kas
            return redirect()->back()->with('error', 'Saldo kas tidak mencukupi.');
        }

        // Lakukan proses pembayaran hutang penuh
        $data_pembayaran = [
            'tanggal' => date('Y-m-d'),
            'jenis_transaksi' => 'pengeluaran',
            'keterangan' => 'Pembayaran hutang: ' . $hutang['keterangan'],
            'jumlah_awal' => $saldo_terakhir,
            'jumlah_akhir' => $total_hutang,
            'saldo_terakhir' => $saldo_terakhir - $total_hutang,
        ];

        // Simpan data pembayaran ke dalam tabel kas
        $this->KasModel->insert($data_pembayaran);

        // Update jumlah hutang dan status menjadi lunas
        $data_hutang = [
            'jumlah' => 0, // Hutang dilunasi
            'status' => 'lunas',
        ];
        $this->hutangModel->update($id_hutang, $data_hutang);

        $data_pengeluaran = [
            'tanggal' => date('Y-m-d'),
            'keterangan' => 'Pembayaran Hutang', // Keterangan transaksi
            'jumlah' => $total_hutang, // Jumlah pembayaran hutang
        ];

        // Simpan data ke dalam tabel pengeluaran
        $PengeluaranModel->insert($data_pengeluaran);

        // Redirect dengan pesan sukses
        return redirect()->to('/kasir/hutang')->with('success', 'Pembayaran hutang berhasil dilunasi.');
    }

    public function editHutang($id)
    {
        $hutang = $this->hutangModel->find($id);

        if (!$hutang) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'title' => 'Edit Hutang',
            'validation' => $this->validation,
            'hutang' => $hutang,
        ];

        return view('kasir/Hutang/EditHutang', $data);
    }

    public function updateHutang($id)
    {
        if (!$this->validate([
            'keterangan' => 'required',
            'jumlah' => 'required',
            'tanggal' => 'required',
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'keterangan' => $this->request->getPost('keterangan'),
            'jumlah' => $this->request->getPost('jumlah'),
            'tanggal' => $this->request->getPost('tanggal'),
        ];

        $this->hutangModel->update($id, $data);

        session()->setFlashdata('message', 'Data hutang berhasil diupdate');
        return redirect()->to('/kasir/hutang');
    }

    public function deleteHutang($id)
    {
        $this->hutangModel->delete($id);

        session()->setFlashdata('message', 'Data hutang berhasil dihapus');
        return redirect()->to('/kasir/hutang');
    }
    // AKHIR MENU HUTANG

    // MENU PIUTANG
    public function piutang()
    {
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

        return view('Kasir/Piutang/Index', $data);
    }

    public function bayarPiutang($id_piutang)
    {
        $penjualanBarangModel = new PenjualanBarangModel();
        $piutangModel = new PiutangModel();
        $pembayaranPiutangModel = new PembayaranPiutangModel(); // Model for pembayaran_piutang table
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
            'jumlah_pembayaran' => $sisaPiutang, // Jumlah pembayaran yang dilakukan
        ];

        // Insert data into pembayaran_piutang table
        $pembayaranPiutangModel->insert($insertData);

        // upodate piutang menjadi lunas
        $updatePiutang = [
            'jumlah_piutang' => 0, // Jumlah piutang menjadi 0
            'jumlah_terbayar' => $totalPenjualan, // Jumlah terbayar menjadi total penjualan
            'status_piutang' => 'lunas', // Update status to lunas
        ];

        $piutangModel->update($id_piutang, $updatePiutang);

        // Update status piutang dan jumlah uang
        $updatePenjualan = [
            'status_piutang' => 'lunas',
            'jumlah_uang' => $totalPenjualan, // Jumlah uang menjadi total penjualan
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
            'saldo_terakhir' => $saldo_terakhir + $sisaPiutang, // Saldo terakhir baru
        ];

        // Simpan data pembayaran ke dalam tabel kas
        $KasModel->insert($data_pembayaran);

        // Simpan data pembayaran ke dalam tabel kas
        // dd($data, $insertData, $updatePiutang, $updatePenjualan, $sisaPiutang, $data_pembayaran, $saldo_terakhir);

        $KasModel->insert($data_pembayaran);

        // Redirect to view or another page after payment
        return redirect()->to('/Kasir/piutang');
    }
    // AKHIR MENU PIUTANG

    // MENU MODAL
    public function modal()
    {
        $modalData = $this->modalTokoModel->findAll();

        // Menginisialisasi variabel total modal
        $totalModal = 0;

        // Menghitung total modal
        foreach ($modalData as $modal) {
            $totalModal += $modal['jumlah'];
        }

        // Menyertakan judul halaman dan total modal ke dalam array data
        $data = [
            'title' => 'Modal Toko',
            'totalModal' => $totalModal,
            'modal' => $modalData, // Jika Anda ingin menyertakan data modal untuk ditampilkan di view
        ];

        // Menampilkan view 'kasir/Modal/Index' dengan data yang sudah disiapkan
        return view('kasir/Modal/Index', $data);
    }

    public function tambahModal()
    {
        $data = [
            'title' => 'Tambah Modal',
            'validation' => $this->validation,
        ];
        return view('kasir/Modal/TambahModal', $data);
    }

    public function saveModal()
    {
        if (!$this->validate([
            'sumber' => [
                'rules' => 'required|is_unique[modal_toko.sumber]',
                'errors' => [
                    'required' => 'Sumber harus diisi',
                    'is_unique' => 'Sumber sudah ada',
                ],
            ],
            'jumlah' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Jumlah harus diisi',
                ],
            ],
        ])) {
            return redirect()->to('/kasir/tambah_modal')->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'sumber' => $this->request->getPost('sumber'),
            'jumlah' => $this->request->getPost('jumlah'),
        ];

        $this->modalTokoModel->insert($data);

        return redirect()->to('/kasir/modal')->with('msg', 'Data modal berhasil ditambahkan.');
    }

    public function editModal($id)
    {
        $data = [
            'title' => 'Ubah Modal',
            'validation' => $this->validation,
            'modal' => $this->modalTokoModel->getModal($id), // Menggunakan fungsi getModal untuk mendapatkan data modal berdasarkan ID
        ];
        return view('kasir/Modal/EditModal', $data);
    }

    public function updateModal()
    {
        $id = $this->request->getPost('id_modal');
        
        // Validasi data yang diterima
        if (!$this->validate([
            'sumber' => 'required',
            'jumlah' => 'required|numeric',
        ])) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }
        
        $data = [
            'sumber' => $this->request->getPost('sumber'),
            'jumlah' => $this->request->getPost('jumlah'),
        ];

        // Pastikan ID dan data tidak kosong
        if ($id && $data) {
            $this->modalTokoModel->update($id, $data);
            session()->setFlashdata('msg', 'Data berhasil diubah');
        } else {
            session()->setFlashdata('msg', 'Data gagal diubah. ID atau data tidak valid.');
        }
        
        return redirect()->to('/kasir/modal');
    }


    public function deleteModal($id)
    {
        $this->modalTokoModel->delete($id);
        session()->setFlashdata('msg', 'Data berhasil dihapus');
        return redirect()->to('/kasir/modal');
    }
    // AKHIR MENU MODAL

    // MENU KAS TOKO
    public function kas()
    {
        $latest_kas = $this->KasModel->orderBy('id_kas', 'DESC')->first();

        // Mendapatkan saldo terakhir
        $saldo_terakhir = $latest_kas['saldo_terakhir'];

        // Mengirimkan data ke view
        $data = [
            'saldo_terakhir' => $saldo_terakhir,
            'kas' => $this->KasModel->orderBy('id_kas', 'ASC')->findAll(),
            'title' => 'Data Kas',
        ];

        return view('kasir/Kas/Index', $data);
    }

    public function tambahKas()
    {
        $data = [
            'title' => 'Tambah Kas',
            'validation' => $this->validation,
        ];
        return view('kasir/Kas/TambahKas', $data);
    }

    public function saveKas()
    {
        // Validasi input
        $rules = [
            'tanggal' => 'required',
            'jenis_transaksi' => 'required',
            'keterangan' => 'required',
        ];

        // Tambahkan aturan validasi berdasarkan jenis transaksi
        if ($this->request->getPost('jenis_transaksi') === 'penerimaan') {
            $rules['jumlah_masuk'] = 'required|numeric';
        } elseif ($this->request->getPost('jenis_transaksi') === 'pengeluaran') {
            $rules['jumlah_keluar'] = 'required|numeric'; // Ubah menjadi 'jumlah_keluar'
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        // Ambil data dari request
        $data = [
            'tanggal' => $this->request->getPost('tanggal'),
            'jenis_transaksi' => $this->request->getPost('jenis_transaksi'),
            'keterangan' => $this->request->getPost('keterangan'),
        ];

        // Tambahkan jumlah masuk atau jumlah keluar berdasarkan jenis transaksi
        if ($data['jenis_transaksi'] === 'penerimaan') {
            $data['jumlah_awal'] = $this->request->getPost('jumlah_masuk');
            $data['jumlah_akhir'] = '+' . $this->request->getPost('jumlah_masuk');
            $data['jumlah_keluar'] = 0; // Atur jumlah keluar menjadi 0 untuk penerimaan
        } elseif ($data['jenis_transaksi'] === 'pengeluaran') {
            $data['jumlah_awal'] = -$this->request->getPost('jumlah_keluar'); // Gunakan 'jumlah_keluar' untuk pengeluaran
            $data['jumlah_akhir'] = '-' . $this->request->getPost('jumlah_keluar'); // Gunakan 'jumlah_keluar' untuk pengeluaran
            $data['jumlah_masuk'] = 0; // Atur jumlah masuk menjadi 0 untuk pengeluaran
        }

        // Hitung saldo terakhir berdasarkan jumlah awal dan jumlah akhir
        $data['saldo_terakhir'] = $this->hitungSaldoTerakhir($data['jumlah_akhir']);

        // Insert data ke database
        // dd($data);
        $this->KasModel->insert($data);

        // Redirect ke halaman daftar kas dengan pesan sukses
        return redirect()->to('/kasir/kas')->with('pesanBerhasil', 'Data kas berhasil ditambahkan.');
    }

    // Fungsi untuk menghitung saldo terakhir
    private function hitungSaldoTerakhir($jumlah_akhir)
    {
        // Ambil saldo terakhir dari database
        $saldo_terakhir = $this->KasModel->select('saldo_terakhir')->orderBy('id_kas', 'desc')->first();

        // Pastikan saldo terakhir adalah nilai numerik sebelum melakukan operasi matematika
        if ($saldo_terakhir && is_numeric($saldo_terakhir['saldo_terakhir']) && is_numeric($jumlah_akhir)) {
            // Jika ada saldo terakhir, tambahkan jumlah akhir ke saldo terakhir sebelumnya
            return $saldo_terakhir['saldo_terakhir'] + $jumlah_akhir;
        } else {
            // Jika tidak ada saldo terakhir atau salah satu nilai non-numeric, kembalikan nilai $jumlah_akhir
            return $jumlah_akhir;
        }
    }

    public function editKas($id)
    {
        $kas = $this->KasModel->find($id);

        // Menambahkan nilai default jika tidak ada
        if (!isset($kas['jumlah_masuk'])) {
            $kas['jumlah_masuk'] = '';
        }
        if (!isset($kas['jumlah_keluar'])) {
            $kas['jumlah_keluar'] = '';
        }

        $data = [
            'title' => 'Edit Kas',
            'validation' => \Config\Services::validation(),
            'kas' => $kas,
        ];

        return view('kasir/Kas/EditKas', $data);
    }

    public function updateKas()
    {
        $id = $this->request->getPost('id_kas');

        // Validate input data
        $validation = \Config\Services::validation();
        if (!$this->validate([
            'tanggal' => 'required',
            'jenis_transaksi' => 'required',
            'jumlah_masuk' => 'numeric',
            'jumlah_keluar' => 'numeric',
            'keterangan' => 'required',
        ])) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        // Calculate saldo terakhir
        $jumlahMasuk = $this->request->getPost('jumlah_masuk');
        $jumlahKeluar = $this->request->getPost('jumlah_keluar');
        $saldoTerakhir = $jumlahMasuk - $jumlahKeluar;

        // Prepare data for update
        $data = [
            'tanggal' => $this->request->getPost('tanggal'),
            'jenis_transaksi' => $this->request->getPost('jenis_transaksi'),
            'jumlah_awal' => $this->getPreviousSaldo($id), // Method to get the previous saldo
            'jumlah_akhir' => $saldoTerakhir,
            'saldo_terakhir' => $this->calculateNewSaldo($id, $saldoTerakhir), // Method to calculate the new saldo
            'keterangan' => $this->request->getPost('keterangan'),
        ];

        // Perform the update
        $this->KasModel->update($id, $data);

        // Recalculate saldo for subsequent transactions
        $this->recalculateSaldo($id);

        // Redirect with success message
        return redirect()->to('/kasir/kas')->with('msg', 'Data kas berhasil diupdate.');
    }

  
    private function getPreviousSaldo($id)
    {
        $previousTransaction = $this->KasModel->where('id_kas <', $id)->orderBy('id_kas', 'DESC')->first();
        return $previousTransaction ? $previousTransaction['saldo_terakhir'] : 0;
    }

  
    private function calculateNewSaldo($id, $saldoTerakhir)
    {
        $previousSaldo = $this->getPreviousSaldo($id);
        return $previousSaldo + $saldoTerakhir;
    }

   
    private function recalculateSaldo($id)
    {
        $transactions = $this->KasModel->where('id_kas >=', $id)->orderBy('id_kas', 'ASC')->findAll();
        $saldo = $this->getPreviousSaldo($id - 1);

        foreach ($transactions as $transaction) {
            $saldo += $transaction['jumlah_akhir'];
            $this->KasModel->update($transaction['id_kas'], ['saldo_terakhir' => $saldo]);
        }
    }

    public function deleteKas($id)
    {
        $this->KasModel->delete($id);
        return redirect()->to('/kasir/kas')->with('msg', 'Data kas berhasil dihapus.');
    }
    //AKHIR MENU KAS TOKO

    // MENU DAFTAR BARANG
    public function Barang()
    {
        $data = [
             'title' => 'Produk - Hera',
             'barangs' => $this->BarangModel
                 ->join('satuan', 'satuan.satuan_id = barang.id_satuan')
                 ->orderBy('barang.tanggal_barang_masuk', 'DESC')
                 ->where('deleted_at', null)->findAll(),
         ];


        return view('Kasir/Barang/Index', $data);
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

        return view('Kasir/Barang/Soft_deleted', $data);
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

        return view('Kasir/Barang/Tambah_barang', $data);
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

        // Check if the item already exists
        $barangExists = $this->BarangModel->where('kode_barang', $namaBarang)
            ->where('id_satuan', $idSatuan)
            ->first();

        if ($barangExists) {
            session()->setFlashdata('error-msg', 'Barang sudah ada dalam database.');
            return redirect()->to('/Kasir/tambahForm')->withInput();
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
            return redirect()->to('/Kasir/tambahForm')->withInput();
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

        // Calculate the new cash balance
        $newSaldo = $currentSaldo - $hargaBeli;

        // Update the cash balance in kas_toko
        if (!$KasModel->insert([
            'tanggal' => date('Y-m-d H:i:s'),
            'jenis_transaksi' => 'pengeluaran',
            'keterangan' => 'Pembelian barang: ' . $namaBarang,
            'jumlah_awal' => $currentSaldo,
            'jumlah_akhir' => $newSaldo,
            'saldo_terakhir' => $newSaldo
        ])) {
            $errors = $KasModel->errors();
            echo 'Error inserting into kas_toko: ' . implode(', ', $errors) . '<br>';
            return redirect()->to('/Kasir/tambahForm')->withInput();
        }

        // Insert data into PengeluaranModel
        if (!$PengeluaranModel->insert([
            'tanggal' => date('Y-m-d H:i:s'),
            'keterangan' => 'Pembelian barang: ' . $namaBarang,
            'jumlah' => $hargaBeli,
            'id_kas' => $KasModel->insertID()
        ])) {
            $errors = $PengeluaranModel->errors();
            echo 'Error inserting into pengeluaran: ' . implode(', ', $errors) . '<br>';
            return redirect()->to('/Kasir/tambahForm')->withInput();
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
            return redirect()->to(base_url('Kasir/barang'))->with('msg', 'Barang berhasil dipulihkan.');
        } else {
            return redirect()->to(base_url('Kasir/barang'))->with('error-msg', 'Gagal memulihkan barang.');
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

        return view('Kasir/Barang/Barang_masuk', $data);
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

        return view('Kasir/riwayat_stok/barang_keluar', $data);
    }

    public function formTambahStok($kodeBarang)
    {
        $barangModel = new BarangModel();
        $barang = $barangModel->where('kode_barang', $kodeBarang)->first();

        if (!$barang) {
            return redirect()->to('/Kasir/barang')->with('error-msg', 'Barang tidak ditemukan.');
        }

        $data = [
            'barang' => $barang,
            'kode_barang' => $kodeBarang,
            'stok' => $barang['stok'],
            'harga_beli' => $barang['harga_beli'],
            'validation' => $this->validation,
            'title' => 'Tambah Stok',
        ];

        return view('Kasir/Barang/Tambah_stok', $data);
    }

    // MASTER SATUAN
    public function satuan()
    {
        $data = [
            'title' => 'Satuan Barang',
            'satuan' => $this->satuanModel->findAll(),
        ];
        return view('Kasir/Satuan/Index', $data);
    }

    public function tambah_satuan()
    {
        $data = [
            'title' => 'Tambah Satuan',
            'validation' => $this->validation,
        ];
        return view('Kasir/Satuan/Tambah_satuan', $data);
    }

    public function simpanSatuan()
    {
        if (!$this->validate([

            'nama_satuan' => [
                'rules' => 'required|is_unique[satuan.nama_satuan]',
                'errors' => [
                    'required' => 'nama satuan harus diisi',
                    'is_unique' => 'nama satuan sudah ada',
                ],
            ],
        ])) {
            return redirect()->to('/Kasir/tambah_satuan')->withInput();
        }
        $data = [
            'nama_satuan' => $this->request->getPost('nama_satuan'),
        ];
        // dd($data);
        $this->satuanModel->insert($data);

        session()->setFlashdata('pesan', 'Data berhasil ditambahkan');
        return redirect()->to('/Kasir/satuan');
    }

    public function updateSatuan()
    {
        $id = $this->request->getPost('id'); // Ambil ID dari form

        // Ambil data nama_satuan dari form
        $nama_satuan = $this->request->getPost('nama_satuan');

        // Update data satuan berdasarkan ID
        $this->satuanModel->update($id, ['nama_satuan' => $nama_satuan]);

        // Set flash message
        session()->setFlashdata('PesanBerhasil', 'Data berhasil diubah');

        return redirect()->to('/Kasir/satuan');
    }

    public function satuan_delete($id)
    {
        $this->satuanModel->delete($id);
        session()->setFlashdata('pesan', 'Data berhasil dihapus');
        return redirect()->to('/Kasir/satuan');
    }
    //AKHIR MENU SATUAN

    // MENU PENJUALAN
    public function penjualanBarang()
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
        ];
        return view('Kasir/Penjualan_barang/Index', $data);
    }
    
    public function list_penjualan($penjualan_barang_id)
    {
        
       
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
        return view('Kasir/Penjualan_barang/list_penjualan', $data);
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

        return view('Kasir/Penjualan_barang/Tambah_penjualan', $data);
    }
    
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
        return redirect()->to('Kasir/penjualanBarang');
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
        return redirect()->to('Kasir/');
    }
    // AKHIR MENU PENJUALAN


    // MENU PENGELUARAN
    public function pengeluaran()
    {
        // 1. Deklarasi Model
        $pengeluaranModel = new PengeluaranModel();

        // 2. Pengambilan Data
        $data['pengeluaran'] = $pengeluaranModel->findAll(); // Mengambil semua data pengeluaran dari tabel pengeluaran

        // 3. Mendapatkan saldo terakhir
        $latestKas = $this->KasModel->orderBy('id_kas', 'DESC')->first();
        $saldoTerakhir = $latestKas['saldo_terakhir'];

        // 4. Mengatur judul
        $data['title'] = 'Pengeluaran';

        // 5. Return View
        return view('Kasir/Pengeluaran/Index', $data);
    }

    public function tambah_pengeluaran()
    {
        $data['title'] = 'Pengeluaran';

        // Instansiasi objek model
        $kasModel = new KasModel();

        // Mengambil saldo terakhir dari tabel kas
        $data['lastBalance'] = $kasModel->getLastBalance();

        return view('Kasir/Pengeluaran/Tambah_Pengeluaran', $data);
    }

    public function simpan_pengeluaran()
    {
        // Instansiasi objek model
        $kasModel = new KasModel();
        $pengeluaranModel = new PengeluaranModel();

        // Validasi input
        $validation = \Config\Services::validation();
        $validation->setRules([
            'tanggal' => 'required',
            'keterangan' => 'required',
            'jumlah' => 'required',
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        // Mengambil saldo terkini
        $lastBalance = $kasModel->getLastBalance();

        // Memeriksa apakah jumlah pengeluaran melebihi saldo yang tersedia
        $jumlah_pengeluaran = $this->request->getPost('jumlah');
        if ($lastBalance < $jumlah_pengeluaran) {
            return redirect()->back()->withInput()->with('errors', ['Jumlah pengeluaran melebihi saldo yang tersedia']);
        }

        // Menghitung saldo akhir
        $newBalance = $lastBalance - $jumlah_pengeluaran;

        // Menyimpan riwayat transaksi pengeluaran
        $pengeluaranData = [
            'tanggal' => date('Y-m-d'),
            'keterangan' => $this->request->getPost('keterangan'),
            'jumlah' => $jumlah_pengeluaran,
        ];
        $pengeluaranModel->insert($pengeluaranData);

        // Mengupdate saldo terakhir
        $kasData = [
            'tanggal' => date('Y-m-d'),
            'jenis_transaksi' => 'pengeluaran',
            'keterangan' => $this->request->getPost('keterangan'),
            'jumlah_awal' => $lastBalance,
            'jumlah_akhir' => $jumlah_pengeluaran,
            'saldo_terakhir' => $newBalance,
        ];
        $kasModel->insert($kasData);

        return redirect()->to('/Kasir/pengeluaran')->with('pesanBerhasil', 'Pengeluaran berhasil ditambahkan');
    }
    // AKHIR MENU PENGELUARAN


    // MENU RESTOK
    public function restok()
    {
        $detailRestokModel = new DetailRestokModel();
        $restokModel = new RestokModel();

        // Mengisi data lainnya
        $data = [
            'title' => 'Restok - Hera',
            'dataRestok' => $restokModel->getRestok(),
        ];
        // dd($data);
        // Mengirimkan data ke view
        return view('Kasir/Restok/Index', $data);
    }

    public function detailRestok($id)
    {
        $detailRestokModel = new DetailRestokModel();
        $restokModel = new RestokModel();

        // dd($id);
        $data = [
            'title' => 'Detail Restok - Hera',
            'dataDetailRestok' => $detailRestokModel->getDetailRestok($id),
            'detail' => $restokModel->getRestok($id),
        ];

        // dd($data);

        return view('Kasir/Restok/Detail', $data);
    }

    public function tambahRestok()
    {
        $latest_kas = $this->KasModel->orderBy('id_kas', 'DESC')->first();

        // Mendapatkan saldo terakhir
        $saldo_terakhir = $latest_kas['saldo_terakhir'];
        $data = [
            'saldo_terakhir' => $saldo_terakhir,
            'validation' => $this->validation,
            'title' => 'Tambah Stok',
            'barangList' => $this->BarangModel
                ->select('barang.*, barang.nama_brg, satuan.nama_satuan, barang.merk,  barang.jenis_brg')

                ->join('satuan', 'satuan.satuan_id = barang.id_satuan')
                ->findAll(),
            'selectedBarang' => null,
            'pelangganList' => $this->PelangganModel->findAll(),
            'supplierList' => $this->supplierModel->findAll(),
        ];

        $kode_barang = $this->request->getPost('kode_barang');
        if ($kode_barang) {
            $selectedBarang = $this->BarangModel->find($kode_barang);
            if ($selectedBarang) {
                $data['selectedBarang'] = $selectedBarang;
            }
        }

        $query = $this->db->table('restok')
            ->select('restok.*, detail_restok.*, supplier.*')
            ->join('detail_restok', 'detail_restok.id_restok = restok.restok_id')
            ->join('supplier', 'supplier.id_supplier = restok.id_supplier')
            ->get();

        $data['restokData'] = $query->getResult();

        return view('Kasir/Restok/TambahRestok', $data);
    }

    public function simpanRestok()
    {
        // Inisialisasi model
        $barangModel = new BarangModel();
        $RestokModel = new restokModel();
        $hutangModel = new hutangModel();
        $modalTokoModel = new modalTokoModel();
        $detailRestokModel = new detailRestokModel();
        $supplierModel = new supplierModel();
        $kasModel = new KasModel(); // Tambahkan inisialisasi untuk KasModel
        $PengeluaranModel = new PengeluaranModel();

        // Mendapatkan saldo terakhir
        $latest_kas = $kasModel->orderBy('id_kas', 'DESC')->first();
        $saldo_terakhir = $latest_kas['saldo_terakhir'];

        // Mendapatkan data dari request
        $kode_restok = 'RST-' . date('Ymdhis') . rand(100, 999);
        $id_supplier = $this->request->getPost('id_supplier');
        $hutang = 0;

        // Menghitung jumlah uang yang dibayarkan
        $jumlah_uang = filter_var($this->request->getPost('jumlah_uang'), FILTER_SANITIZE_NUMBER_INT);
        $jumlah_pembayaran = filter_var($this->request->getPost('jumlah_pembayaran'), FILTER_SANITIZE_NUMBER_INT);

        // Mengurangi jumlah uang yang dibayarkan dari saldo terakhir
        $newBalance = $saldo_terakhir - $jumlah_uang;

        // Menghitung kembalian
        $kembalian = $jumlah_uang - $jumlah_pembayaran;
        // dd($kembalian,$jumlah_uang,$jumlah_pembayaran);
        // Menyimpan data restok
        $data_restok = [
            'restok_id' => $kode_restok,
            'id_supplier' => $id_supplier,
            'tanggal' => date('Y-m-d H:i:s'),
            'jumlah_pembayaran' => $jumlah_pembayaran,
            'jumlah_uang' => $jumlah_uang, // Simpan jumlah uang yang dibayarkan
            'kembalian' => $kembalian, // Simpan kembalian
        ];
        $RestokModel->insert($data_restok);

        // Mengambil data barang dari request dan memprosesnya
        $data = $this->request->getPost();
        foreach ($data['kode_barang'] as $key => $value) {
            // Menyiapkan data detail restok
            $data_detail_restok = [
                'id_restok' => $kode_restok,
                'kode_barang' => $value,
                'harga_beli' => filter_var($data['harga'][$key], FILTER_SANITIZE_NUMBER_INT),
                'jumlah_restok' => $data['jumlah'][$key],
                'sub_total' => filter_var($data['sub_total'][$key], FILTER_SANITIZE_NUMBER_INT),
            ];

     
            $detailRestokModel->insert($data_detail_restok);

            // Mendapatkan data barang dari database
            $barang = $barangModel->getBarang($value);

            // Mengupdate stok barang
            $data_barang = [
                'stok' => $barang['stok'] + $data['jumlah'][$key],
            ];
            // $barangModel->update($value, $data_barang);
        }

        // Mengelola kembalian
        if ($kembalian < 0) {
            // Simpan data hutang
            $data_hutang = [
                'keterangan' => 'Hutang dari restok dengan ID ' . $kode_restok,
                'jumlah' =>  abs($kembalian),
                'tanggal' => date('Y-m-d'),
            ];
            // dd($data_hutang);
            $hutangModel->insert($data_hutang);
        }

        // Menyimpan data ke dalam tabel kas
        $data_pengeluaran = [
            'tanggal' => date('Y-m-d'),
            'keterangan' => 'Restok barang', // Keterangan transaksi
            'jumlah' => $jumlah_pembayaran, // Saldo sebelum restok
        ];

        // Simpan data ke dalam tabel kas
        $PengeluaranModel->insert($data_pengeluaran);

        // Simpan data transaksi pengeluaran
        $data_pengeluaran = [
            'tanggal' => date('Y-m-d'),
            'jenis_transaksi' => 'pengeluaran', // Misalnya, restok barang
            'keterangan' => 'Restok barang', // Keterangan transaksi
            'jumlah_awal' => $saldo_terakhir, // Saldo sebelum restok
            'jumlah_akhir' => $jumlah_uang, // Pengurangan saldo karena pembayaran
            'saldo_terakhir' => $newBalance, // Saldo terbaru setelah restok
        ];
        $kasModel->insert($data_pengeluaran);

        // Mengurangi saldo terakhir di kas
        $kasModel->updateLastBalance($newBalance);

        // Redirect dan set pesan berhasil
        session()->setFlashdata('pesanBerhasil', 'Data restok berhasil ditambahkan');
        return redirect()->to('/Kasir/restok');
    }


    public function deleteRestok($id)
    {
        $restokModel = new RestokModel();
        $detailRestokModel = new DetailRestokModel();
        $barangModel = new BarangModel();
        $hutangModel = new HutangModel();

        $restok = $restokModel->where('restok_id', $id)->first();
        // dd($restok);
        if (!$restok) {
            session()->setFlashdata('pesanBerhasil', 'Data restok tidak ditemukan');
            return redirect()->to('/kasir/restok');
        }

        $detailRestok = $detailRestokModel->where('id_restok', $id)->findAll();
        // dd($detailRestok);

        if ($detailRestok) {
            foreach ($detailRestok as $detail) {
                $barang = $barangModel->getBarang($detail['kode_barang']);
                // dd($barang);
                $data_barang = [
                    'stok' => $barang['stok'] - $detail['jumlah_restok'],
                ];
                $barangModel->update($detail['kode_barang'], $data_barang);

                if ($detail['status_bayar'] == 'hutang') {
                    $hutangModel->where('keterangan', 'Hutang Restok ' . $id . '(' . $barang['nama_brg'] . ')')->delete();
                }
            }
        }

        $restokModel->where('restok_id', $id)->delete();
        $detailRestokModel->where('id_restok', $id)->delete();

        session()->setFlashdata('message', 'Data restok berhasil dihapus');
        return redirect()->to('/kasir/restok');
    }
    // AKHIR MENU


    // MENU PERKIRAAN PENJUALAN
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

        return view('Kasir/Perkiraan/Index', $data);
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

        return view('Kasir/Perkiraan/Tambah_perkiraan', $data);
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
            return redirect()->to(base_url('Kasir/perkiraan'));
        } catch (\Exception $e) {
            session()->setFlashdata('msg', 'Gagal menyimpan perkiraan penjualan. Silakan coba lagi.');
            return redirect()->back()->withInput();
        }
    }
    // AKHIR MENU


    // MENU PELANGGAN
    public function pelanggan()
    {
        $data = [
            'title' => 'Daftar Nama Pelanggan',

            'pelanggan' => $this->PelangganModel->findAll(),
        ];
        return view('Kasir/Pelanggan/Index', $data);
    }

    public function tambah_pelanggan()
    {
        $data = [
            'title' => 'Tambah Pelanggan',
            'validation' => $this->validation,
        ];
        return view('Kasir/Pelanggan/Tambah_pelanggan', $data);
    }
    public function simpanPelanggan()
    {
        if (!$this->validate([

            'nama' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'nama satuan harus diisi',

                ],
            ],
        ])) {
            return redirect()->to('/Kasir/tambah_pelanggan')->withInput();
        }
        $data = [
            'nama' => $this->request->getPost('nama'),
            'alamat' => $this->request->getPost('alamat'),
            'kontak' => $this->request->getPost('kontak'),
        ];
        // dd($data);
        $this->PelangganModel->insert($data);

        session()->setFlashdata('pesan', 'Data berhasil ditambahkan');
        return redirect()->to('/Kasir/pelanggan');
    }
    public function pelanggan_edit($id)
    {
        $data = [
            'title' => 'Ubah Pelanggan',
            'validation' => $this->validation,
            'pelanggan' => $this->PelangganModel->find($id),
        ];
        return view('Kasir/Pelanggan/Edit_pelanggan', $data);
    }
    public function updatePelanggan()
    {
        $id = $this->request->getPost('id_pelanggan');
        $data = [
            'nama' => $this->request->getPost('nama'),
            'alamat' => $this->request->getPost('alamat'),
            'kontak' => $this->request->getPost('kontak'),
        ];
        $this->PelangganModel->update($id, $data);
        session()->setFlashdata('pesan', 'Data berhasil diubah');
        return redirect()->to('/Kasir/pelanggan');
    }
    public function pelanggan_delete($id)
    {
        $this->PelangganModel->delete($id);
        session()->setFlashdata('pesan', 'Data berhasil dihapus');
        return redirect()->to('/Kasir/pelanggan');
    }
    // AKHIR MENU


    // MENU SUPPLIER
    public function supplier()
    {
        $data = [
            'title' => 'Data Supplier',
            'suppliers' => $this->supplierModel->findAll(),
        ];

        return view('Kasir/Supplier/Index', $data);
    }

    public function tambahSupplier()
    {
        $data = [
            'title' => 'Tambah Supplier',
            'validation' => $this->validation,
        ];
        return view('Kasir/Supplier/TambahSupplier', $data);
    }

    public function saveSupplier()
    {
        if (!$this->validate([
            'nama' => 'required',
            'alamat' => 'required',
            'kontak' => 'required',
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'nama' => $this->request->getPost('nama'),
            'alamat' => $this->request->getPost('alamat'),
            'kontak' => $this->request->getPost('kontak'),
        ];

        $this->supplierModel->insert($data);

        session()->setFlashdata('message', 'Supplier berhasil ditambahkan');
        return redirect()->to('/Kasir/supplier');
    }

    public function editSupplier($id)
    {
        $supplier = $this->supplierModel->find($id);

        if (!$supplier) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'title' => 'Edit Supplier',
            'validation' => $this->validation,
            'supplier' => $supplier,
        ];

        return view('Kasir/Supplier/EditSupplier', $data);
    }

    public function updateSupplier($id)
    {
        if (!$this->validate([
            'nama' => 'required',
            'alamat' => 'required',
            'kontak' => 'required',
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'nama' => $this->request->getPost('nama'),
            'alamat' => $this->request->getPost('alamat'),
            'kontak' => $this->request->getPost('kontak'),
        ];

        $this->supplierModel->update($id, $data);

        session()->setFlashdata('message', 'Data supplier berhasil diupdate');
        return redirect()->to('/Kasir/supplier');
    }

    public function deleteSupplier($id)
    {
        $this->supplierModel->delete($id);

        session()->setFlashdata('message', 'Data supplier berhasil dihapus');
        return redirect()->to('/Kasir/supplier');
    }
    // AKHIR MENU


    // MENU LAPORAN
    public function lap_barang()
    {
        $data = [
            'title' => 'Toko Hera - Laporan Barang',
        ];

        return view('Kasir/Laporan/Home_barang', $data);
    }
    public function lap_arus_kas()
    {
        $data = [
            'title' => 'Toko Hera - Laporan Arus Kas',
        ];

        return view('Kasir/Laporan/Home_arus', $data);
    }
    public function lap_analisa_arus_kas()
    {
        $data = [
            'title' => 'Toko Hera - Laporan Analisa arus kas',
        ];

        return view('Kasir/Laporan/Home_analisa', $data);
    }
    public function lap_laba_rugi()
    {
        $data = [
            'title' => 'Toko Hera - Laporan Laba rugi',
        ];

        return view('Kasir/Laporan/Home_laba', $data);
    }

    // cetak analisis laporan
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
        $html = view('Kasir/Laporan/Lap_analisisArusKas', $data);

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

    // cetak arus kas
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

        // dd($data);

        // Load view untuk cetak laporan

        $mpdf = new \Mpdf\Mpdf();
        $mpdf->showImageErrors = true;
        $html = view('Kasir/Laporan/Lap_aruskas', $data);

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

    //cetak databarang
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
        $data['tanggalMulai'] = $tanggalMulai;
        $data['tanggalAkhir'] = $tanggalAkhir;
        $data['pemilikName'] = $pemilikName;
        // Load view untuk cetak laporan

        $mpdf = new \Mpdf\Mpdf();
        $mpdf->showImageErrors = true;
        $html = view('Kasir/Laporan/Lap_barang', $data);

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

    //cetak laba rugi
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
        $html = view('Kasir/Laporan/Lap_labaRugi', $data);

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
    // AKHIR MENU
}
