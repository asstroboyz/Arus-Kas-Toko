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
use App\Models\paketModel;
use App\Models\pelangganWifiModel;
use App\Models\tagihanModel;
use Mpdf\Mpdf;
use Myth\Auth\Entities\User;
use Myth\Auth\Models\GroupModel;
use Myth\Auth\Models\UserModel;

class Admin extends BaseController
{
    protected $db;
    protected $paketModel;
    protected $tagihanModel;
    protected $pelangganWifiModel;
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

        $this->pelangganWifiModel = new pelangganWifiModel();
        $this->paketModel = new paketModel();
        $this->tagihanModel = new tagihanModel();
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

    // public function index()
    // {
    //     $latestKas = $this->KasModel->orderBy('id_kas', 'DESC')->first();

    //     // Mendapatkan saldo terakhir
    //     $saldoTerakhir = $latestKas ? $latestKas['saldo_terakhir'] : 0;

    //     // Menghitung jumlah inventaris
    //     $dataInventaris = $this->db->table('penjualan_barang')->get()->getResult();

    //     // Menghitung stok barang yang dibawah 10
    //     $queryBarangStokDibawah10 = $this->db->table('barang')->where('stok <', 10)->get()->getResult();
    //     $stokdibawah10 = count($queryBarangStokDibawah10);

    //     // Menghitung total penjualan barang dalam 24 jam terakhir
    //     $waktu24JamYangLalu = date('Y-m-d H:i:s', strtotime('-24 hours'));
    //     $totalPenjualan24Jam = $this->db->table('penjualan_barang')->where('tanggal_penjualan >=', $waktu24JamYangLalu)->countAllResults();

    //     $data = [
    //         'title' => 'Toko Hera - Home',
    //         'saldo_terakhir' => $saldoTerakhir,
    //         'stokdibawah10' => $stokdibawah10,
    //         'totalPenjualan24Jam' => $totalPenjualan24Jam,
    //     ];

    //     return view('Admin/Home/Index', $data);
    // }

    public function index()
    {
        $latestKas = $this->KasModel->orderBy('id_kas', 'DESC')->first();
        $saldoTerakhir = $latestKas ? $latestKas['saldo_terakhir'] : 0;

        $queryBarangStokDibawah10 = $this->db->table('barang')->where('stok <', 10)->get()->getResult();
        $stokdibawah10 = count($queryBarangStokDibawah10);

        $waktu24JamYangLalu = date('Y-m-d H:i:s', strtotime('-24 hours'));
        $totalPenjualan24Jam = $this->db->table('penjualan_barang')->where('tanggal_penjualan >=', $waktu24JamYangLalu)->countAllResults();

        // $totalKasMasuk = $this->db->table('kas_toko')
        // ->selectSum('jumlah_akhir')
        // ->where('jenis_transaksi', 'penerimaan')
        // ->get()
        // ->getRow()->jumlah_akhir;
        $totalKasMasuk = $this->db->table('kas_toko')
            ->select('SUM(jumlah_akhir - jumlah_awal) AS total_masuk', false)
            ->where('jenis_transaksi', 'penerimaan')
            ->get()
            ->getRow()->total_masuk;
        // $totalKasMasuk = $this->db->query("
        //     SELECT SUM(jumlah_akhir - jumlah_awal) AS total_masuk
        //     FROM kas_toko
        //     WHERE jenis_transaksi = 'penerimaan'
        // ")->getRow()->total_masuk;
        $totalKasKeluar = $this->db->table('kas_toko')
            ->select('SUM(jumlah_awal - jumlah_akhir) AS total_keluar', false)
            ->where('jenis_transaksi', 'pengeluaran')
            ->get()
            ->getRow()->total_keluar;

        //         $totalKasKeluar = $this->db->query("
        //     SELECT SUM(jumlah_awal - jumlah_akhir) AS total_keluar
        //     FROM kas_toko
        //     WHERE jenis_transaksi = 'pengeluaran'
        // ")->getRow()->total_keluar;
        $dataPenjualan = $this->PenjualanBarangModel->getAllSales(); // Mengambil semua data penjualan
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

        return view('Admin/Home/Index', $data);
    }

    public function user_list()
    {
        $data['title'] = 'User List';
        // $users = new \Myth\Auth\Models\UserModel();
        // $data['users']  = $users->findAll();

        //join tabel memanggil fungsi
        $this->builder->select('users.id as userid, username, email, name');
        $this->builder->join('auth_groups_users', 'auth_groups_users.user_id = users.id');
        $this->builder->join('auth_groups', 'auth_groups.id = auth_groups_users.group_id');
        $query = $this->builder->get();

        $data['users'] = $query->getResult();
        return view('Admin/User_list', $data);
    }

    public function detail($id = 0)
    {
        $data['title'] = 'Toko Hera - Detail Pengguna';

        $this->builder->select('users.id as userid, username, email, foto, name,created_at');
        $this->builder->join('auth_groups_users', 'auth_groups_users.user_id = users.id');
        $this->builder->join('auth_groups', 'auth_groups.id = auth_groups_users.group_id');
        $this->builder->where('users.id', $id);
        $query = $this->builder->get();

        $data['user'] = $query->getRow();

        if (empty($data['user'])) {
            return redirect()->to('/Admin');
        }

        return view('Admin/Detail', $data);
    }

    public function profil()
    {
        $data['title'] = 'User Profile ';
        $userlogin = user()->username;
        $userid = user()->id;
        $role = $this->db->table('auth_groups_users')->where('user_id', $userid)->get()->getRow();
        $role == '1' ? $role_echo = 'Admin' : $role_echo = 'Pegawai'; // $data['title'] = 'User Profile ';
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
                $role_echo = 'Pegawai';
            }
        } else {
            // Jika data role tidak ditemukan, mengatur nilai default sebagai 'Pegawai'
            $role_echo = 'Pegawai';
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

        return view('Admin/Home/Profil', $data);
    }

    public function simpanProfile($id)
    {
        // dd($this->request->getPost());
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

            $nama_foto = 'AdminFOTO' . $this->request->getPost('username') . '.' . $foto->guessExtension();
            if (!(empty($query['foto']))) {
                unlink('uploads/profile/' . $query['foto']);
            }
            $foto->move('uploads/profile', $nama_foto);

            $this->Profil->update($id, [
                'email' => $this->request->getPost('email'),
                'fullname' => $this->request->getPost('fullname'),
                'username' => $this->request->getPost('username'),
                'foto' => $nama_foto,
            ]);
        }
        session()->setFlashdata('msg', 'Profil Admin  berhasil Diubah');
        return redirect()->to(base_url('Admin/profil/' . $id));
    }

    public function updatePassword($id)
    {
        $passwordLama = $this->request->getPost('passwordLama');
        $passwordbaru = $this->request->getPost('passwordBaru');
        $konfirm = $this->request->getPost('konfirm');

        if ($passwordbaru != $konfirm) {
            session()->setFlashdata('error-msg', 'Password Baru tidak sesuai');
            return redirect()->to(base_url('admin/profil/' . $id));
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
            return redirect()->to('/admin/profil/' . $id);
        } else {
            session()->setFlashdata('error-msg', 'Password Lama tidak sesuai');
            return redirect()->to(base_url('admin/profil/' . $id));
        }
    }

    // satuan
    public function satuan()
    {
        $data = [
            'title' => 'Satuan Barang',
            'satuan' => $this->satuanModel->findAll(),
        ];
        return view('Admin/Satuan/Index', $data);
    }

    public function tambah_satuan()
    {
        $data = [
            'title' => 'Tambah Satuan',
            'validation' => $this->validation,
        ];
        return view('Admin/Satuan/Tambah_satuan', $data);
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
            return redirect()->to('/admin/tambah_satuan')->withInput();
        }
        $data = [
            'nama_satuan' => $this->request->getPost('nama_satuan'),
        ];
        // dd($data);
        $this->satuanModel->insert($data);

        session()->setFlashdata('pesan', 'Data berhasil ditambahkan');
        return redirect()->to('/admin/satuan');
    }

    // public function satuan_edit($id)
    // {
    //     $data = [
    //         'title' => 'Ubah Satuan',
    //         'validation' => $this->validation,
    //         'satuan' => $this->satuanModel->find($id),
    //     ];
    //     return view('Admin/Satuan/Edit_satuan', $data);
    // }

    public function updateSatuan()
    {
        $id = $this->request->getPost('id'); // Ambil ID dari form

        // Ambil data nama_satuan dari form
        $nama_satuan = $this->request->getPost('nama_satuan');

        // Update data satuan berdasarkan ID
        $this->satuanModel->update($id, ['nama_satuan' => $nama_satuan]);

        // Set flash message
        session()->setFlashdata('PesanBerhasil', 'Data berhasil diubah');

        return redirect()->to('/admin/satuan');
    }

    public function satuan_delete($id)
    {
        $this->satuanModel->delete($id);
        session()->setFlashdata('pesan', 'Data berhasil dihapus');
        return redirect()->to('/admin/satuan');
    }
    // last satuan

    // pelanggan
    public function pelanggan()
    {
        $data = [
            'title' => 'Daftar Nama Pelanggan',

            'pelanggan' => $this->pelangganWifiModel

                ->select('pelanggan_wifi.*, paket.nama_paket, paket.harga')  // Selecting fields you want
                ->join('paket', 'paket.kode_paket = pelanggan_wifi.kode_paket', 'left')  // Perform LEFT JOIN
                ->findAll(),
        ];
        // dd($data);
        return view('Admin/Pelanggan/Index', $data);
    }

    // public function tambah_pelanggan()
    // {
    //     $data = [
    //         'title' => 'Tambah Pelanggan',
    //         'barangList' => $this->paketModel
    //         ->select('paket.*')
    //         ->findAll(),
    //     'selectedBarang' => null,
    //         'validation' => $this->validation,
    //     ];
    //     $kode_paket = $this->request->getPost('kode_paket');
    //     if ($kode_paket) {
    //         $selectedBarang = $this->paketModel->find($kode_paket);
    //         if ($selectedBarang) {
    //             $data['selectedBarang'] = $selectedBarang;
    //         }
    //     }

    //     return view('Admin/Pelanggan/Tambah_pelanggan', $data);
    // }
    // public function simpanPelanggan()
    // {
    //     if (!$this->validate([

    //         'nama' => [
    //             'rules' => 'required',
    //             'errors' => [
    //                 'required' => 'nama satuan harus diisi',

    //             ],
    //         ],
    //     ])) {
    //         return redirect()->to('/admin/tambah_pelanggan')->withInput();
    //     }
    //     $data = [
    //         'nama' => $this->request->getPost('nama'),
    //         'alamat' => $this->request->getPost('alamat'),
    //         'kontak' => $this->request->getPost('kontak'),
    //     ];
    //     // dd($data);
    //     $this->PelangganModel->insert($data);

    //     session()->setFlashdata('pesan', 'Data berhasil ditambahkan');
    //     return redirect()->to('/admin/pelanggan');
    // }
    public function pelanggan_edit($id)
    {
        $data = [
            'title' => 'Ubah Pelanggan',
            'validation' => $this->validation,
            'pelanggan' => $this->pelangganWifiModel->find($id),
        ];

        // dd($data);
        return view('Admin/Pelanggan/Edit_pelanggan', $data);
    }
    public function updatePelanggan()
    {
        $id = $this->request->getPost('id_pelanggan');

    // Validasi input
    if (!$this->validate([
        'nama' => 'required',
        'alamat' => 'required',
        'no_hp' => 'required',
        'foto_ktp' => [
            'rules' => 'is_image[foto_ktp]|mime_in[foto_ktp,image/jpg,image/jpeg,image/png]|max_size[foto_ktp,2048]',
            'errors' => [
                'is_image' => 'File harus berupa gambar.',
                'mime_in' => 'Format file harus jpg, jpeg, atau png.',
                'max_size' => 'Ukuran file maksimal 2MB.',
            ],
        ],
    ])) {
        return redirect()->to("/admin/pelanggan/edit/$id")->withInput()->with('validation', $this->validator);
    }

    // Ambil data pelanggan lama
    $pelanggan = $this->pelangganWifiModel->find($id);

    if (!$pelanggan) {
        session()->setFlashdata('pesan', 'Data pelanggan tidak ditemukan.');
        return redirect()->to('/admin/pelanggan');
    }

    // Jika ada file baru
    $fotoKTP = $this->request->getFile('foto_ktp');
    if ($fotoKTP && $fotoKTP->isValid()) {
        $namaFile = $fotoKTP->getRandomName();
        $fotoKTP->move('uploads/foto_ktp', $namaFile);

        // Hapus foto KTP lama jika ada
        if ($pelanggan['foto_ktp'] && file_exists('uploads/foto_ktp/' . $pelanggan['foto_ktp'])) {
            unlink('uploads/foto_ktp/' . $pelanggan['foto_ktp']);
        }
    } else {
        $namaFile = $pelanggan['foto_ktp']; // Tetap gunakan file lama
    }

    // Update data sesuai allowedFields
    $data = [
        'nama' => $this->request->getPost('nama'),
        'alamat' => $this->request->getPost('alamat'),
        'no_hp' => $this->request->getPost('no_hp'),
        'nik' => $this->request->getPost('nik'),
        'foto_ktp' => $namaFile,
        'kode_paket' => $this->request->getPost('kode_paket'),
        'tgl_pasang' => $this->request->getPost('tgl_pasang'),
        'status_pelanggan' => $this->request->getPost('status_pelanggan'),
        'updated_at' => date('Y-m-d H:i:s'),
    ];

    if ($this->pelangganWifiModel->update($id, $data)) {
        session()->setFlashdata('pesan', 'Data pelanggan berhasil diubah.');
    } else {
        session()->setFlashdata('pesan', 'Gagal mengubah data pelanggan.');
    }

    return redirect()->to('/admin/pelanggan');
    }

    public function pelanggan_delete($id)
    {
        $this->PelangganModel->delete($id);
        session()->setFlashdata('pesan', 'Data berhasil dihapus');
        return redirect()->to('/admin/pelanggan');
    }
    // last pelanggan

    // master barang
    public function master_barang()
    {
        $data['title'] = 'Master Barang';
        $data['master_brg'] = $this->masterBarangModel
            ->orderBy('jenis_brg', 'ASC')
            ->findAll();
        return view('Admin/Master_barang/Index', $data);
    }

    public function addBarang()
    {
        $data = [
            'title' => 'Tambah Barang',
            'validation' => $this->validation,
        ];
        return view('Admin/Master_barang/Tambah_barang', $data);
    }
    public function saveBarang()
    {
        if (!$this->validate([

            'nama_barang' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Nama Barang harus diisi',
                ],
            ],
            'jenis_barang' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Jenis Barang harus diisi',
                ],
            ],
            'merk' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Merk harus diisi',
                ],
            ],
        ])) {
            return redirect()->to('/admin/addBarang')->withInput();
        }
        $jenis_brg = $this->request->getPost('jenis_barang');

        if ($jenis_brg == 'obat') {
            $jenis_brg = 'obt';
        } elseif ($jenis_brg == 'bahan_pokok') {
            $jenis_brg = 'bpok';
        } elseif ($jenis_brg == 'atk') {
            $jenis_brg = 'atk';
        } elseif ($jenis_brg == 'sabun') {
            $jenis_brg = 'sabun';
        } elseif ($jenis_brg == 'minuman') {
            $jenis_brg = 'minuman';
        } elseif ($jenis_brg == 'snack') { // Makanan ringan diganti dengan snack
            $jenis_brg = 'snack';
        } elseif ($jenis_brg == 'perlengkapan') {
            $jenis_brg = 'perkap';
        } elseif ($jenis_brg == 'galon') {
        } elseif ($jenis_brg == 'gas') {
            $jenis_brg = 'gas';
        } elseif ($jenis_brg == 'galon') {
            $jenis_brg = 'galon';
        } elseif ($jenis_brg == 'shampo') {
            $jenis_brg = 'shampo';
        }

        $kode_brg = $jenis_brg . '-' . date('Ymd') . rand(100, 999);
        $data = [
            'kode_brg' => $kode_brg,
            'merk' => $this->request->getPost('merk'),
            'nama_brg' => $this->request->getPost('nama_barang'),
            'jenis_brg' => $this->request->getPost('jenis_barang'),
        ];
        // dd($data);
        $this->masterBarangModel->insert($data);

        session()->setFlashdata('pesan', 'Data berhasil ditambahkan');
        return redirect()->to('/admin/master_barang');
    }

    public function detail_master_brg($id)
    {
        $data['title'] = 'Detail Master Barang';
        $data['master_brg'] = $this->masterBarangModel->where('kode_brg', $id)->first();
        $data['detail_brg'] = $this->tipeBarangModel->getMaster($id);
        $data['barang_model'] = $this->BarangModel;
        // dd($data['detail_brg']);
        return view('Admin/Master_barang/Detail_brg', $data);
    }
    public function detail_tipe_barang($id)
    {
        $data['title'] = 'Detail Master Barang';
        $barang = $this->tipeBarangModel->getTipeBarang($id);
        if ($barang['jenis_brg'] == 'inv') {
            $data['detail_brg'] = $this->InventarisModel->detailMaster($id);
        } else {
            $data['detail_brg'] = $this->BarangModel->getMaster($id);
        }
        $data['master_brg'] = $barang;
        // dd($data['master_brg']);
        return view('Admin/Tipe_Barang/Detail_brg', $data);
    }

    public function ubah_master($id)
    {
        $data = [
            'title' => 'Ubah Master Barang',
            'validation' => $this->validation,
            'master_brg' => $this->masterBarangModel->getMaster($id),
        ];
        return view('Admin/Master_barang/Edit_barang', $data);
    }
    public function editMaster()
    {
        $id = $this->request->getPost('kode_brg');
        $data = [
            'merk' => $this->request->getPost('merk'),
            'nama_brg' => $this->request->getPost('nama_brg'),
            'jenis_brg' => $this->request->getPost('jenis_brg'),
        ];
        $this->masterBarangModel->update($id, $data);
        session()->setFlashdata('pesan', 'Data berhasil diubah');
        return redirect()->to('/admin/master_barang');
    }
    public function hapus_master($id)
    {
        $this->masterBarangModel->delete($id);
        session()->setFlashdata('pesan', 'Data berhasil dihapus');
        return redirect()->to('/admin/master_barang');
    }

    // tipe barang
    public function master_tipe_barang()
    {
        $data['master_brg'] = $this->masterBarangModel->findAll();

        $data = [
            'title' => 'Master Tipe Barang',
            'tipe_barang' => $this->tipeBarangModel->getTipeBarang(),
        ];
        return view('Admin/Tipe_barang/Index', $data);
    }

    public function tambah_tipe_barang()
    {
        $data = [
            'title' => 'Tambah Tipe Barang',
            'validation' => $this->validation,
            'master_barang' => $this->masterBarangModel->findAll(),
        ];
        return view('Admin/Tipe_barang/Tambah_tipe', $data);
    }

    public function simpanTipe()
    {
        $data = [
            'tipe_barang' => $this->request->getPost('tipe_barang'),
            'master_barang' => $this->request->getPost('kode_brg'),
        ];
        // dd($data);
        $this->tipeBarangModel->save($data);

        session()->setFlashdata('pesan', 'Data berhasil ditambahkan');
        return redirect()->to('/admin/master_tipe_barang');
    }

    public function editTipe($id)
    {
        $data = [
            'title' => 'Ubah Tipe Barang',
            'validation' => $this->validation,
            'tipe_barang' => $this->tipeBarangModel->getTipeBarang($id),
            'master_barang' => $this->masterBarangModel->findAll(),
        ];
        return view('Admin/Tipe_barang/Edit_tipe', $data);
    }

    public function updateTipe()
    {
        $id = $this->request->getPost('id');
        $data = [
            'tipe_barang' => $this->request->getPost('tipe_barang'),
            'master_barang' => $this->request->getPost('kode_brg'),
        ];
        $this->tipeBarangModel->update($id, $data);
        session()->setFlashdata('pesan', 'Data berhasil diubah');
        return redirect()->to('/admin/master_tipe_barang');
    }

    public function deleteTipe($id)
    {
        // Cek apakah data dengan $id tersedia
        $tipe_barang = $this->tipeBarangModel->find($id);
        if (!$tipe_barang) {
            session()->setFlashdata('PesanGagal', 'Data tidak ditemukan.');
            return redirect()->to('/admin/master_tipe_barang');
        }

        // Lakukan penghapusan data
        $this->tipeBarangModel->delete($id);

        // Set pesan sukses
        session()->setFlashdata('PesanBerhasil', 'Data berhasil dihapus.');

        // Redirect ke halaman master tipe barang
        return redirect()->to('/admin/master_tipe_barang');
    }

    // Menu Penjualan

    public function hapus_penjualan($id)
    {
        $this->PenjualanModel->delete($id);
        session()->setFlashdata('msg', 'Penjualan berhasil dihapus.');
        return redirect()->to('/Admin/penjualan');
    }

    public function penjualanbarang()
    {
        $model = new PenjualanBarangModel();
        // $data['pengaduan'] = $query;
        $this->builder = $this->db->table('penjualan_barang');
        $this->builder->select('*');
        $this->query = $this->builder->get();
        $data['penjualan'] = $this->query->getResultArray();
        // dd(  $data['permintaan']);
        $data['title'] = 'Penjualan Barang';

        return view('Admin/Penjualan_barang/Index', $data);
    }

    public function list_penjualan($id)
    {
        $data['detail'] = $this->PenjualanBarangModel->getPenjualan($id);
        $data['penjualan'] = $this->detailPenjualanBarangModel
            ->select('detail_penjualan_barang.*, master_barang.nama_brg, satuan.nama_satuan,penjualan_barang.tanggal_penjualan, master_barang.merk,detail_master.tipe_barang')
            ->join('barang', 'barang.kode_barang = detail_penjualan_barang.kode_barang')
            ->join('satuan', 'satuan.satuan_id = barang.id_satuan')
            ->join('detail_master', 'detail_master.detail_master_id = barang.id_master_barang')
            ->join('master_barang', 'master_barang.kode_brg = detail_master.master_barang')
            ->join('penjualan_barang', 'penjualan_barang.penjualan_barang_id = detail_penjualan_barang.id_penjualan_barang')
            ->where('id_penjualan_barang', $id)->findAll();
        // dd(  $data['penjualan']);
        $data['title'] = 'Penjualan Barang';
        return view('Admin/Penjualan_barang/list_penjualan', $data);
    }

    public function tambah_penjualanBarang()
    {
        $data = [
            'validation' => $this->validation,
            'title' => 'Tambah Penjualan',
            'barangList' => $this->BarangModel
                ->select('barang.*,master_barang.nama_brg, satuan.nama_satuan, master_barang.merk,detail_master.detail_master_id,detail_master.tipe_barang')
                ->join('detail_master', 'detail_master.detail_master_id = barang.id_master_barang')
                ->join('master_barang', 'master_barang.kode_brg = detail_master.master_barang')
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

    public function simpanPenjualanBrg()
    {
        $PelangganModel = new PelangganModel();
        $barangModel = new BarangModel();
        $TransaksiBarangModel = new TransaksiBarangModel();
        $KasModel = new KasModel();

        // Mendapatkan saldo terakhir menggunakan fungsi getSaldoTerakhir()
        $latestKas = $KasModel->getSaldoTerakhir();
        $saldoTerakhir = $latestKas ? $latestKas['saldo_terakhir'] : 0;

        // Debug saldo terakhir
        // dd($saldoTerakhir);

        // Mendapatkan data barang dari input post
        $barangList = $this->request->getPost('kode_barang');
        $idPelangganList = $this->request->getPost('id_pelanggan'); // Ubah dari $barangList menjadi $idPelangganList

        $jumlahList = $this->request->getPost('jumlah');

        $total_penjualan = 0;

        // Membuat kode permintaan untuk penjualan
        $kode_penjualan = 'NHR-' . mt_rand(1000, 9999);

        // Persiapkan data untuk penyimpanan penjualan barang
        $penjualanData = [
            'penjualan_barang_id' => $kode_penjualan,
            'tanggal_penjualan' => 'YYYY-MM-DD HH:MM:SS',
            'id_pelanggan' => $this->request->getPost('id_pelanggan')[0],
            'metode_pembayaran' => $this->request->getPost('metode_pembayaran')[0],
            'total_penjualan' => 0, // Total penjualan diisi sementara dengan 0
        ];

        // Simpan data penjualan barang terlebih dahulu

        $this->PenjualanBarangModel->insert($penjualanData);

        // Simpan id transaksi untuk seluruh detail penjualan
        $id_transaksi = $kode_penjualan;

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

                // Menyimpan detail penjualan barang
                $dataDetailPenjualan = [
                    'kode_barang' => $kode_barang,
                    'jumlah' => $jumlah,
                    'sub_total' => $sub_total,
                    'keuntungan' => $keuntungan,
                    'id_penjualan_barang' => $kode_penjualan,
                    'id_transaksi' => $id_transaksi, // Menggunakan id_transaksi yang sama untuk seluruh detail penjualan
                ];

                // Simpan detail penjualan barang
                $id_detail_penjualan_barang = $this->detailPenjualanBarangModel->insert($dataDetailPenjualan);

                // Menyimpan data pemasukan ke kas
                $jumlah_awal = $saldoTerakhir;
                $jumlah_akhir = $saldoTerakhir + $sub_total;
                $saldoTerakhir = $jumlah_akhir; // Update saldo terakhir

                $dataPemasukan = [
                    'cek_sub' => $sub_total,
                    'tanggal' =>  date('Y-m-d H:i:s'),
                    'jenis_transaksi' => 'penerimaan',
                    'keterangan' => 'Penjualan barang - ' . $barang['nama_brg'],
                    'jumlah_awal' => $jumlah_awal,
                    'jumlah_akhir' => $jumlah_akhir,
                    'saldo_terakhir' => $saldoTerakhir,
                ];

                // Simpan data pemasukan ke kas
                $KasModel->insert($dataPemasukan);
            } else {
                // Jika stok tidak mencukupi, tampilkan pesan kesalahan
                session()->setFlashdata('msg', 'Stok barang tidak mencukupi.');
                return redirect()->back()->withInput();
            }
        }

        // Update total penjualan setelah selesai menyimpan detail penjualan
        $this->PenjualanBarangModel->update($kode_penjualan, ['total_penjualan' => $total_penjualan]);

        session()->setFlashdata('msg', 'Penjualan berhasil dilakukan.');
        return redirect()->to('PenjualanBarangCont/');
    }

    public function ubah($id)
    {

        session();
        $barangList = $this->BarangModel->getBarang();

        $data = [
            'title' => "Toko Hera Ubah Data Permintaan",
            'validation' => \Config\Services::validation(),
            'barangList' => $barangList,
            'permintaan' => $this->detailPermintaanModel->getDetailPermintaan($id),
        ];

        return view('Pegawai/Permintaan_barang/Edit_permintaan', $data);
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

        $this->detailPermintaanModel->update($id, $dataPermintaan);
        $id_permintaan = $this->request->getPost('id_permintaan_barang');
        session()->setFlashdata('msg', 'Permintaan berhasil diperbarui.');
        return redirect()->to('/Pegawai/list_permintaan/' . $id_permintaan);
    }

    public function delete_penjualanBarang($id)
    {
        // Cari penjualan barang berdasarkan ID
        $penjualan = $this->PenjualanBarangModel->find($id);

        // Pastikan penjualan barang ditemukan
        if ($penjualan) {
            // Hapus detail penjualan barang berdasarkan ID penjualan
            $this->detailPenjualanBarangModel->where('id_penjualan_barang', $penjualan['penjualan_barang_id'])->delete();

            // Hapus transaksi penjualan berdasarkan ID penjualan
            $this->TransaksiBarangModel->where('id_penjualan_barang', $penjualan['penjualan_barang_id'])->delete();

            // Hapus pemasukan penjualan berdasarkan ID penjualan
            $this->PemasukanModel->where('id_detail_penjualan_barang', $penjualan['penjualan_barang_id'])->delete();

            // Hapus riwayat saldo penjualan berdasarkan ID penjualan
            $this->riwayatSaldo->where('id_detail_penjualan_barang', $penjualan['penjualan_barang_id'])->delete();

            // Update saldo terakhir dengan mengurangkan total penjualan yang dihapus
            $lastBalance = $this->SaldoModel->getLastBalance();
            $newBalance = $lastBalance - $penjualan['total_penjualan'];
            $this->SaldoModel->updateLastBalance($newBalance);

            // Hapus penjualan barang berdasarkan ID
            $this->PenjualanBarangModel->delete($id);

            session()->setFlashdata('msg', 'Penjualan barang berhasil dihapus.');
            return redirect()->to('Admin/penjualanbarang/');
        } else {
            // Jika penjualan barang tidak ditemukan, tampilkan pesan kesalahan
            session()->setFlashdata('msg', 'Penjualan barang tidak ditemukan.');
            return redirect()->to('Admin/penjualanbarang/');
        }
    }
    // Menu Penjualan
    //barang
    public function Barang()
    {
        $data = [
            'title' => 'Produk - Hera',
            'barangs' => $this->BarangModel
                ->join('detail_master', 'detail_master.detail_master_id = barang.id_master_barang')
                ->join('master_barang', 'master_barang.kode_brg = detail_master.master_barang')
                ->join('satuan', 'satuan.satuan_id = barang.id_satuan')
                ->where('deleted_at', null)->findAll(),
        ];

        return view('Admin/Barang/Index', $data);
    }

    public function pemasukan()
    {
        $model = new KasModel();
        $data['kas'] = $model->findAll(); // Mengambil semua data pengeluaran dari tabel pengeluaran

        $saldoModel = new SaldoModel();
        $data['kas'] = $saldoModel->orderBy('id', 'DESC')->first(); // Mengambil saldo terakhir dari tabel saldo

        $data['riwayat_saldo'] = $this->db->table('riwayat_saldo')->get()->getResultArray(); // Mengambil semua data dari tabel riwayat saldo

        // Mengatur judul
        $data['title'] = 'Pengeluaran';

        return view('Admin/Pengeluaran/Index', $data);
    }

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
        return view('Admin/Pengeluaran/Index', $data);
    }

    public function tambah_pengeluaran()
    {
        $data['title'] = 'Pengeluaran';

        // Instansiasi objek model
        $kasModel = new KasModel();

        // Mengambil saldo terakhir dari tabel kas
        $data['lastBalance'] = $kasModel->getLastBalance();

        return view('Admin/Pengeluaran/Tambah_Pengeluaran', $data);
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
            'tanggal' =>  date('Y-m-d H:i:s'),
            'keterangan' => $this->request->getPost('keterangan'),
            'jumlah' => $jumlah_pengeluaran,
        ];
        // dd($pengeluaranData);
        $pengeluaranModel->insert($pengeluaranData);

        // Mengupdate saldo terakhir
        $kasData = [
            'tanggal' =>  date('Y-m-d H:i:s'),
            'jenis_transaksi' => 'pengeluaran',
            'keterangan' => $this->request->getPost('keterangan'),
            'jumlah_awal' => $lastBalance,
            'jumlah_akhir' => $jumlah_pengeluaran,
            'saldo_terakhir' => $newBalance,
        ];
        $kasModel->insert($kasData);

        return redirect()->to('/Admin/pengeluaran')->with('pesanBerhasil', 'Pengeluaran berhasil ditambahkan');
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
            'validation' => $this->validation,
            'title' => 'Tambah Barang ',
            'satuan' => $this->satuanModel->findAll(),
            'master_barang' => $this->tipeBarangModel->getMasterBarang(),
        ];

        return view('Admin/Barang/Tambah_barang', $data);
    }

    public function tambah()
    {
        $namaBarang = $this->request->getPost('nama_barang');
        $idSatuan = $this->request->getPost('satuan_barang');

        // Lakukan pengecekan apakah barang sudah ada berdasarkan id_master_barang dan id_satuan
        $barangExists = $this->BarangModel->where('id_master_barang', $namaBarang)
            ->where('id_satuan', $idSatuan)
            ->first();

        // Jika barang sudah ada, berikan pesan error dan kembalikan ke form tambah
        if ($barangExists) {
            session()->setFlashdata('error-msg', 'Barang sudah ada dalam database.');
            return redirect()->to('/Admin/tambahForm')->withInput();
        }

        // Validasi input form tambah barang
        $this->validation->setRules([
            'nama_barang' => 'required',
            'satuan_barang' => 'required',
            'stok' => [
                'rules' => 'required|numeric|greater_than[0]',
                'errors' => [
                    'required' => 'Stok wajib diisi.',
                    'numeric' => 'Stok harus berupa angka.',
                    'greater_than' => 'Stok harus lebih besar dari 0.',
                ],
            ],
        ]);

        if (!$this->validation->withRequest($this->request)->run()) {
            // Ambil pesan kesalahan
            $errors = $this->validation->getErrors();

            // Tampilkan pesan kesalahan sesuai dengan aturan yang telah ditentukan
            foreach ($errors as $error) {
                echo $error . '<br>';
            }

            // Redirect kembali ke formulir dengan input
            return redirect()->to('/Admin/tambahForm')->withInput();
        }

        // Simpan data barang ke database tanpa menyertakan kode_barang
        $data = [
            'id_master_barang' => $this->request->getPost('nama_barang'),
            'id_satuan' => $this->request->getPost('satuan_barang'),
            'stok' => $this->request->getPost('stok'),
            'harga_beli' => $this->request->getPost('harga_beli'),
            'harga_jual' => $this->request->getPost('harga_jual'),
            'tanggal_barang_masuk' => date('Y-m-d H:i:s'), // Tambahkan waktu saat ini
        ];

        // Debug nilai variabel sebelum menyimpan
        var_dump($data);

        // Simpan data ke BarangModel tanpa kode_barang
        $this->BarangModel->save($data);

        // Dapatkan kode_barang yang baru saja disimpan
        $kodeBarang = $this->TransaksiBarangModel->insertID();

        // Debug nilai kode_barang setelah menyimpan
        var_dump($kodeBarang);

        // Masukkan data ke tabel transaksi_barang dengan kode_brg sebagai kode_barang dari BarangModel
        $this->TransaksiBarangModel->insert([
            'kode_brg' => $kodeBarang, // ini dari tab
            'stok' => $data['stok'],
            'tanggal_barang_masuk' => $data['tanggal_barang_masuk'],
            'jumlah_perubahan' => $data['stok'],
            'jenis_transaksi' => 'masuk',
            'informasi_tambahan' => 'Penambahan stok.',
            'tanggal_perubahan' => $data['tanggal_barang_masuk'],
        ]);

        // Tampilkan pesan sukses atau error
        session()->setFlashdata('msg', 'Data barang berhasil ditambahkan.');
        return redirect()->to('/Admin/barang');
    }

    // public function tambah()
    // {
    //     $namaBarang = $this->request->getPost('nama_barang');
    //     $idSatuan = $this->request->getPost('satuan_barang');

    //     // Lakukan pengecekan apakah barang sudah ada berdasarkan id_master_barang dan id_satuan
    //     $barangExists = $this->BarangModel->where('id_master_barang', $namaBarang)
    //         ->where('id_satuan', $idSatuan)
    //         ->first();

    //     // Jika barang sudah ada, berikan pesan error dan kembalikan ke form tambah
    //     if ($barangExists) {
    //         session()->setFlashdata('error-msg', 'Barang sudah ada dalam database.');
    //         return redirect()->to('/Admin/tambahForm')->withInput();
    //     }

    //     // Validasi input form tambah barang
    //     $this->validation->setRules([
    //         'nama_barang' => 'required',
    //         'satuan_barang' => 'required',
    //         'stok' => [
    //             'rules' => 'required|numeric|greater_than[0]',
    //             'errors' => [
    //                 'required' => 'Stok wajib diisi.',
    //                 'numeric' => 'Stok harus berupa angka.',
    //                 'greater_than' => 'Stok harus lebih besar dari 0.',
    //             ],
    //         ],
    //     ]);

    //     if (!$this->validation->withRequest($this->request)->run()) {
    //         // Node 1: Ambil pesan kesalahan
    //         $errors = $this->validation->getErrors();

    //         // Node 2: Tampilkan pesan kesalahan sesuai dengan aturan yang telah ditentukan
    //         foreach ($errors as $error) {
    //             echo $error . '<br>';
    //         }

    //         // Node 3: Redirect kembali ke formulir dengan input
    //         return redirect()->to('/Admin/tambahForm')->withInput();
    //     }

    //     // Simpan data barang ke database
    //     $data = [
    //         'id_master_barang' => $this->request->getPost('nama_barang'),
    //         'id_satuan' => $this->request->getPost('satuan_barang'),
    //         'stok' => $this->request->getPost('stok'),
    //         'harga_beli' => $this->request->getPost('harga_beli'),
    //         'harga_jual' => $this->request->getPost('harga_jual'),
    //         'tanggal_barang_masuk' => date('Y-m-d H:i:s'), // Tambahkan waktu saat ini
    //     ];
    //     // dd($data);

    //     // Generate dan tambahkan kode_barang ke dalam data
    //     $this->BarangModel->save($data);

    //     // Dapatkan kode_barang yang baru saja disimpan
    //     $kodeBarang = $this->BarangModel->getInsertID();

    //     // Masukkan data ke tabel transaksi_barang
    //     $this->TransaksiBarangModel->insert([
    //         'kode_barang' => $kodeBarang,
    //         'stok' => $data['stok'],
    //         'tanggal_barang_masuk' => $data['tanggal_barang_masuk'],
    //         'jumlah_perubahan' => $data['stok'],
    //         'jenis_transaksi' => 'masuk',
    //         'informasi_tambahan' => 'Penambahan stok.',
    //         'tanggal_perubahan' => $data['tanggal_barang_masuk'],
    //     ]);

    //     // Tampilkan pesan sukses atau error
    //     session()->setFlashdata('msg', 'Data barang berhasil ditambahkan.');
    //     return redirect()->to('/Admin/barang');

    // }

    public function softDelete($kode_barang)
    {
        $barangModel = new BarangModel();

        // Cek apakah barang dengan kode_barang tertentu ada
        $barang = $barangModel->find($kode_barang);

        if ($barang) {
            // Lakukan soft delete dengan menghapus record di tabel Barang dan TransaksiBarang
            $barangModel->softDeleteWithRelations($kode_barang);

            return redirect()->to('/Admin/barang')->with('success', 'Data berhasil dihapus secara soft delete.');
        } else {
            return redirect()->to('/Admin/barang')->with('error', 'Data tidak ditemukan.');
        }
    }
    // app/Controllers/AdminController.php
    public function restore($kode_barang)
    {
        $restored = $this->BarangModel->restoreBarang($kode_barang);

        if ($restored) {
            return redirect()->to(base_url('BarangCont'))->with('msg', 'Barang berhasil dipulihkan.');
        } else {
            return redirect()->to(base_url('BarangCont'))->with('error-msg', 'Gagal memulihkan barang.');
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
    // public function tambahStok($kodeBarang)
    // {
    //     $barangModel = new BarangModel();
    //     $TransaksiBarangModel = new TransaksiBarangModel();
    //     $SaldoModel = new SaldoModel(); // Model untuk saldo
    //     $PengeluaranModel = new PengeluaranModel();

    //     // Mendapatkan data barang
    //     $barang = $barangModel->where('kode_barang', $kodeBarang)->first();

    //     if (!$barang) {
    //         return redirect()->to("/Admin/formTambahStok/{$kodeBarang}")->withInput()->with('error-msg', 'Barang tidak ditemukan.');
    //     }

    //     // Mendapatkan data dari form
    //     $jumlahPenambahanStok = (int) $this->request->getPost('jumlah_penambahan_stok');
    //     $tanggalBarangMasuk = $this->request->getPost('tanggal_barang_masuk');

    //     // Mendapatkan harga_beli dari barang
    //     $hargaBeli = $barang['harga_beli'];

    //     // Menghitung total nilai barang yang ditambahkan
    //     $totalNilai = $jumlahPenambahanStok * $hargaBeli;

    //     // Mendapatkan saldo terakhir
    //     $lastBalance = $SaldoModel->getLastBalance();

    //     // Mengupdate saldo dengan nilai total barang yang ditambahkan
    //     $newBalance = $lastBalance - $totalNilai;

    //     // Update saldo terakhir
    //     $SaldoModel->updateLastBalance($newBalance); // Memasukkan pemanggilan updateLastBalance()

    //     // Update stok pada tabel barang
    //     $stokBaru = $barang['stok'] + $jumlahPenambahanStok;
    //     $barangModel->update($barang['kode_barang'], [
    //         'stok' => $stokBaru,
    //     ]);

    //     // Insert data restok
    //     $dataRestok = [
    //         'jumlah' => $totalNilai, // Simpan total nilai sebagai jumlah pengeluaran
    //         'keterangan' => 'Restok barang',
    //         'tanggal' =>  date('Y-m-d H:i:s'), // Tanggal pemasukan, bisa diubah sesuai kebutuhan
    //     ];
    //     $PengeluaranModel->insert($dataRestok); // Menggunakan model PengeluaranModel

    //     // Masukkan data ke tabel transaksi_barang
    //     $TransaksiBarangModel->insert([
    //         'kode_brg' => $kodeBarang,
    //         'stok' => $stokBaru,
    //         'tanggal_barang_masuk' => $tanggalBarangMasuk,
    //         'jumlah_perubahan' => $jumlahPenambahanStok,
    //         'jenis_transaksi' => 'masuk',
    //         'informasi_tambahan' => 'Penambahan stok melalui form tambah stok.',
    //         'tanggal_perubahan' => $tanggalBarangMasuk,
    //     ]);

    //     // Set pesan sukses dan redirect
    //     session()->setFlashdata('msg', 'Stok barang berhasil ditambahkan.');
    //     return redirect()->to('/Admin/barang')->with('success-msg', 'Stok barang berhasil ditambahkan.');
    // }
    public function tambahStok($kodeBarang)
    {
        $barangModel = new BarangModel();
        $TransaksiBarangModel = new TransaksiBarangModel();
        $KasModel = new KasModel(); // Model untuk kas
        $PengeluaranModel = new PengeluaranModel();

        // Mendapatkan data barang
        $barang = $barangModel->where('kode_barang', $kodeBarang)->first();

        if (!$barang) {
            return redirect()->to("/Admin/formTambahStok/{$kodeBarang}")->withInput()->with('error-msg', 'Barang tidak ditemukan.');
        }

        // Mendapatkan data dari form
        $jumlahPenambahanStok = (int) $this->request->getPost('jumlah_penambahan_stok');
        $tanggalBarangMasuk = $this->request->getPost('tanggal_barang_masuk');

        // Mendapatkan harga_beli dari barang
        $hargaBeli = $barang['harga_beli'];

        // Menghitung total nilai barang yang ditambahkan
        $totalNilai = $jumlahPenambahanStok * $hargaBeli;

        // Mendapatkan kas terakhir
        $lastBalance = $KasModel->getLastBalance();

        // Mengupdate kas dengan nilai total barang yang ditambahkan
        $newBalance = $lastBalance - $totalNilai;

        // Update kas terakhir
        $KasModel->updateLastBalance($newBalance); // Memasukkan pemanggilan updateLastBalance()

        // Update stok pada tabel barang
        $stokBaru = $barang['stok'] + $jumlahPenambahanStok;
        $barangModel->update($barang['kode_barang'], [
            'stok' => $stokBaru,
        ]);

        // Insert data restok
        $dataRestok = [
            'jumlah' => $totalNilai, // Simpan total nilai sebagai jumlah pengeluaran
            'keterangan' => 'Restok barang',
            'tanggal' =>  date('Y-m-d H:i:s'), // Tanggal pemasukan, bisa diubah sesuai kebutuhan
        ];
        $PengeluaranModel->insert($dataRestok); // Menggunakan model PengeluaranModel

        // Masukkan data ke tabel transaksi_barang
        $TransaksiBarangModel->insert([
            'kode_brg' => $kodeBarang,
            'stok' => $stokBaru,
            'tanggal_barang_masuk' => $tanggalBarangMasuk,
            'jumlah_perubahan' => $jumlahPenambahanStok,
            'jenis_transaksi' => 'masuk',
            'informasi_tambahan' => 'Penambahan stok melalui form tambah stok.',
            'tanggal_perubahan' => $tanggalBarangMasuk,
        ]);

        // Set pesan sukses dan redirect
        session()->setFlashdata('msg', 'Stok barang berhasil ditambahkan.');
        return redirect()->to('/Admin/barang')->with('success-msg', 'Stok barang berhasil ditambahkan.');
    }

    public function formKurangStok($kodeBarang)
    {
        $barangModel = new BarangModel();
        $barang = $barangModel->where('kode_barang', $kodeBarang)->first();
        $harga_jual = $barang['harga_jual'];

        // Pastikan barang ditemukan sebelum melanjutkan
        if (!$barang) {
            // Tampilkan pesan kesalahan atau redirect ke halaman lain jika perlu
            return redirect()->to('/Admin/barang')->with('error-msg', 'Barang tidak ditemukan.');
        }

        // Kirim data ke view, termasuk nilai stok
        $data = [
            'barang' => $barang,
            'kodeBarang' => $kodeBarang,
            'stok' => $barang['stok'], // Inisialisasi variabel stok
            'harga_jual' => $harga_jual,
            'validation' => $this->validation,
            'title' => 'Kurang Barang',
        ];

        return view('Admin/Barang/Kurang_stok', $data);
    }

    public function kurangiStok($kodeBarang)
    {
        $barangModel = new BarangModel();
        $TransaksiBarangModel = new TransaksiBarangModel();

        // Mendapatkan data barang
        $barang = $barangModel->where('kode_barang', $kodeBarang)->first();

        if (!$barang) {
            // Tampilkan pesan kesalahan atau redirect ke halaman lain jika perlu
            return redirect()->to('/Admin/barang')->with('error-msg', 'Barang tidak ditemukan.');
        }

        // Mendapatkan data dari form
        $jumlahPenguranganStok = (int) $this->request->getPost('jumlah_pengurangan_stok');
        $tanggalBarangKeluar = $this->request->getPost('tanggal_barang_keluar');
        $stok = $barang['stok']; // Menggunakan jenis_barang dari data barang
        $stokBaru = max(0, $stok - $jumlahPenguranganStok);

        // Update stok pada tabel barang
        $barangModel->update($barang['kode_barang'], [
            'stok' => $stokBaru,
        ]);

        // Menghitung harga jual total
        $hargaJualTotal = $barang['harga_jual'] * $jumlahPenguranganStok;

        // Masukkan data ke tabel transaksi_barang
        $TransaksiBarangModel->insert([
            'kode_brg' => $kodeBarang,
            'stok' => $stok,
            'tanggal_barang_keluar' => $tanggalBarangKeluar,
            'jumlah_perubahan' => $jumlahPenguranganStok,
            'jenis_transaksi' => 'keluar',
            'informasi_tambahan' => 'Pengurangan stok melalui form kurang stok.',
            'tanggal_perubahan' => $tanggalBarangKeluar,
            'harga_jual_total' => $hargaJualTotal, // Menyimpan nilai harga jual total ke dalam tabel
        ]);

        // Set pesan sukses dan redirect
        session()->setFlashdata('msg', 'Stok barang berhasil dikurangi.');
        return redirect()->to('/Admin/barang');
    }

    public function trans_masuk()
    {
        $this->builder = $this->db->table('transaksi_barang');
        $this->builder->select('transaksi_barang.*, satuan.nama_satuan, master_barang.nama_brg, master_barang.merk');
        $this->builder->join('barang', 'transaksi_barang.kode_barang = barang.kode_barang');
        $this->builder->join('satuan', 'barang.id_satuan = satuan.satuan_id');
        $this->builder->join('detail_master', 'detail_master.detail_master_id = barang.id_master_barang');
        $this->builder->join('master_barang', 'master_barang.kode_brg = detail_master.master_barang');
        $this->builder->where('transaksi_barang.jenis_transaksi', 'masuk');

        $this->query = $this->builder->get();

        $data = [
            'transaksi_barang' => $this->query->getResultArray(),
            'title' => 'Daftar Transaksi Barang Masuk',
        ];

        return view('Admin/Barang/Barang_masuk', $data);
    }
    public function trans_keluar()
    {
        $this->builder = $this->db->table('transaksi_barang');
        $this->builder->select('transaksi_barang.*, satuan.nama_satuan, master_barang.nama_brg, master_barang.merk');
        $this->builder->join('barang', 'transaksi_barang.kode_barang = barang.kode_barang');
        $this->builder->join('satuan', 'barang.id_satuan = satuan.satuan_id');
        $this->builder->join('detail_master', 'detail_master.detail_master_id = barang.id_master_barang');
        $this->builder->join('master_barang', 'master_barang.kode_brg = detail_master.master_barang');

        $this->builder->where('transaksi_barang.jenis_transaksi', 'keluar');

        $this->query = $this->builder->get();

        $data = [
            'transaksi_barang' => $this->query->getResultArray(),
            'title' => 'Daftar Transaksi Barang Keluar',
        ];

        return view('Admin/Barang/Barang_keluar', $data);
    }

    public function lap_permintaan_barang()
    {
        $data = [
            // 'user'=> $query->getResult(),
            'title' => 'Toko Hera - Laporan',

        ];

        return view('Admin/Laporan/Home_permintaan', $data);
    }

    public function cetakDataMasuk()
    {

        $tanggalMulai = $this->request->getGet('tanggal_mulai');
        $tanggalAkhir = $this->request->getGet('tanggal_akhir');

        if (empty($tanggalMulai) || empty($tanggalAkhir)) {
            return redirect()->to(base_url('Admin'))->with('error', 'Tanggal mulai dan tanggal akhir harus diisi.');
        }

        $dateMulai = strtotime($tanggalMulai);
        $dateAkhir = strtotime($tanggalAkhir);

        if ($dateMulai === false || $dateAkhir === false || $dateMulai > $dateAkhir) {
            return redirect()->to(base_url('Admin'))->with('error', 'Format tanggal tidak valid atau tanggal mulai melebihi tanggal akhir.');
        }

        $transaksiBarangModel = new TransaksiBarangModel();
        $data['atk'] = $transaksiBarangModel
            ->withDeleted()
            ->select('transaksi_barang.*, satuan.nama_satuan, master_barang.nama_brg, barang.id_master_barang, barang.id_satuan, master_barang.merk,detail_master.tipe_barang')
            ->join('barang', 'transaksi_barang.kode_barang = barang.kode_barang')
            ->join('detail_master', 'detail_master.detail_master_id = barang.id_master_barang')
            ->join('master_barang', 'master_barang.kode_brg = detail_master.master_barang')
            ->join('satuan', 'barang.id_satuan = satuan.satuan_id')
            ->where('transaksi_barang.tanggal_barang_masuk >=', $tanggalMulai . ' 00:00:00')
            ->where('transaksi_barang.tanggal_barang_masuk <=', $tanggalAkhir . ' 23:59:59')
            ->findAll();
        $data['tanggalMulai'] = $tanggalMulai; // Add this line
        $data['tanggalAkhir'] = $tanggalAkhir;

        $mpdf = new \Mpdf\Mpdf();
        $mpdf->showImageErrors = true;
        $html = view('Admin/Laporan/Lap_barangMasuk', $data);

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
        $mpdf->Output('Lap Barang Masuk Inventaris Barang.pdf', 'I');
    }

    public function cetakDataKeluar()
    {

        $tanggalMulai = $this->request->getGet('tanggal_mulai');
        $tanggalAkhir = $this->request->getGet('tanggal_akhir');

        if (empty($tanggalMulai) || empty($tanggalAkhir)) {
            return redirect()->to(base_url('Admin'))->with('error', 'Tanggal mulai dan tanggal akhir harus diisi.');
        }

        $dateMulai = strtotime($tanggalMulai);
        $dateAkhir = strtotime($tanggalAkhir);

        if ($dateMulai === false || $dateAkhir === false || $dateMulai > $dateAkhir) {
            return redirect()->to(base_url('Admin'))->with('error', 'Format tanggal tidak valid atau tanggal mulai melebihi tanggal akhir.');
        }

        $transaksiBarangModel = new TransaksiBarangModel();

        $data['atkKeluar'] = $transaksiBarangModel
            ->withDeleted()
            ->select('transaksi_barang.*, satuan.nama_satuan, master_barang.nama_brg, barang.id_master_barang, barang.id_satuan, master_barang.merk,detail_master.tipe_barang')
            ->join('barang', 'transaksi_barang.kode_barang = barang.kode_barang')
            ->join('detail_master', 'detail_master.detail_master_id = barang.id_master_barang')
            ->join('master_barang', 'master_barang.kode_brg = detail_master.master_barang')

            ->join('satuan', 'barang.id_satuan = satuan.satuan_id')
            ->where('transaksi_barang.tanggal_barang_keluar >=', $tanggalMulai . ' 00:00:00') // Mengatur kondisi where untuk tanggal mulai
            ->where('transaksi_barang.tanggal_barang_keluar <=', $tanggalAkhir . ' 23:59:59') // Mengatur kondisi where untuk tanggal akhir
            ->findAll();

        $data['tanggalMulai'] = $tanggalMulai; // Add this line
        $data['tanggalAkhir'] = $tanggalAkhir;
        $mpdf = new \Mpdf\Mpdf();
        $mpdf->showImageErrors = true;
        $html = view('Admin/Laporan/Lap_barangKeluar', $data);

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
        $mpdf->Output('Lap Barang Keluar Barang.pdf', 'I');
    }

    // peramalan

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

        return view('Admin/Perkiraan/Index', $data);
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

        return view('Admin/Perkiraan/Tambah_perkiraan', $data);
    }

    // perkiraan done
    // public function save_perkiraan()
    // {
    //     // Ambil data dari formulir
    //     $kode_barang = $this->request->getPost('kode_barang');
    //     $tanggal_mulai = $this->request->getPost('tanggal_mulai');
    //     $tanggal_akhir = $this->request->getPost('tanggal_akhir');

    //     // Pastikan data yang diterima adalah data yang valid
    //     if (empty($kode_barang) || empty($tanggal_mulai) || empty($tanggal_akhir)) {
    //         // Set pesan kesalahan ke dalam session
    //         session()->setFlashdata('error', 'Semua field harus diisi.');
    //         // Kembalikan ke halaman sebelumnya
    //         return redirect()->back()->withInput();
    //     }

    //     // Konversi tanggal menjadi jumlah hari
    //     $jumlah_hari = (strtotime($tanggal_akhir) - strtotime($tanggal_mulai)) / (60 * 60 * 24);

    //     if ($jumlah_hari <= 0) {
    //         // Set pesan kesalahan ke dalam session
    //         session()->setFlashdata('error', 'Tanggal akhir harus setelah tanggal mulai.');
    //         // Kembalikan ke halaman sebelumnya
    //         return redirect()->back()->withInput();
    //     }

    //     // Lakukan perhitungan perkiraan penjualan jika ada data historis
    //     $historical_data = $this->detailPenjualanBarangModel->getHistoricalData($kode_barang, $jumlah_hari);
    //     if (count($historical_data) > 0) {
    //         $total_sales = 0;
    //         foreach ($historical_data as $data) {
    //             $total_sales += $data['jumlah'];
    //         }
    //         $average_sales = $total_sales / count($historical_data);
    //     } else {
    //         // Set pesan kesalahan ke dalam session
    //         session()->setFlashdata('error', 'Tidak ada data historis penjualan untuk periode yang ditentukan.');
    //         // Kembalikan ke halaman sebelumnya
    //         return redirect()->back()->withInput();
    //     }

    //     // Simpan hasil perkiraan ke dalam database
    //     $perkiraanModel = new perkiraanModel();
    //     $perkiraanModel->savePerkiraan([
    //         'kode_barang' => $kode_barang,
    //         'periode_perkiraan' => $jumlah_hari,
    //         'hasil_perkiraan' => $average_sales,
    //         'tanggal_perkiraan' => date('Y-m-d')
    //     ]);

    //     // Set pesan sukses ke dalam session
    //     session()->setFlashdata('success', 'Perkiraan penjualan berhasil disimpan.');
    //     // Redirect ke halaman perkiraan
    //     return redirect()->to('Admin/perkiraan');
    // }
    // perkiraan done

    // public function save_perkiraan()
    // {
    //     // Ambil data dari formulir
    //     $kode_barang = $this->request->getPost('kode_barang');
    //     $tanggal_mulai = $this->request->getPost('tanggal_mulai');
    //     $tanggal_akhir = $this->request->getPost('tanggal_akhir');
    //     $metode_perkiraan = $this->request->getPost('metode_perkiraan'); // Metode perkiraan yang dipilih

    //     // Pastikan data yang diterima adalah data yang valid
    //     if (empty($kode_barang) || empty($tanggal_mulai) || empty($tanggal_akhir) || empty($metode_perkiraan)) {
    //         // Set pesan kesalahan ke dalam session
    //         session()->setFlashdata('error', 'Semua field harus diisi.');
    //         // Kembalikan ke halaman sebelumnya
    //         return redirect()->back()->withInput();
    //     }

    //     // Konversi tanggal menjadi jumlah hari
    //     $jumlah_hari = (strtotime($tanggal_akhir) - strtotime($tanggal_mulai)) / (60 * 60 * 24);

    //     if ($jumlah_hari <= 0) {
    //         // Set pesan kesalahan ke dalam session
    //         session()->setFlashdata('error', 'Tanggal akhir harus setelah tanggal mulai.');
    //         // Kembalikan ke halaman sebelumnya
    //         return redirect()->back()->withInput();
    //     }

    //     // Ambil data historis penjualan barang
    //     $historical_data = $this->detailPenjualanBarangModel->getHistoricalData($kode_barang, $jumlah_hari);

    //     // Inisialisasi variabel hasil perkiraan
    //     $forecast = 0;

    //     // Tentukan metode perhitungan berdasarkan opsi model
    //     switch ($metode_perkiraan) {
    //         case 'moving_average':
    //             // Hitung total penjualan selama periode historis
    //             $total_sales = array_sum(array_column($historical_data, 'jumlah'));
    //             // Hitung rata-rata penjualan
    //             $forecast = $total_sales / count($historical_data);
    //             break;
    //         case 'exponential_smoothing':
    //             // Hitung nilai awal (initial value) dengan menggunakan rata-rata penjualan historis
    //             $initial_value = array_sum(array_column($historical_data, 'jumlah')) / count($historical_data);
    //             // Tentukan faktor smoothing (alpha)
    //             $alpha = 0.2; // Anda bisa menyesuaikan nilai alpha sesuai kebutuhan
    //             // Lakukan perhitungan exponential smoothing
    //             $smoothed_value = $initial_value;
    //             foreach ($historical_data as $data) {
    //                 $smoothed_value = $alpha * $data['jumlah'] + (1 - $alpha) * $smoothed_value;
    //             }
    //             $forecast = $smoothed_value;
    //             break;
    //         case 'time_series':
    //             // Lakukan perhitungan menggunakan metode time series yang sesuai
    //             // Implementasikan algoritma yang sesuai untuk analisis seri waktu
    //             // Contoh sederhana: Menggunakan rata-rata penjualan dari 7 hari sebelumnya sebagai perkiraan
    //             $forecast = array_sum(array_column($historical_data, 'jumlah')) / count($historical_data);
    //             break;
    //         default:
    //             // Opsi model tidak valid
    //             session()->setFlashdata('error', 'Metode perkiraan tidak valid.');
    //             return redirect()->back()->withInput();
    //     }

    //     // Pastikan forecast tidak false
    //     if ($forecast !== false) {
    //         // Simpan hasil perkiraan ke dalam database
    //         $perkiraanModel = new perkiraanModel();
    //         $perkiraanModel->savePerkiraan([
    //             'kode_barang' => $kode_barang,
    //             'periode_perkiraan' => $jumlah_hari,
    //             'metode_perkiraan' => $metode_perkiraan,
    //             'hasil_perkiraan' => $forecast,
    //             'tanggal_perkiraan' => date('Y-m-d')
    //         ]);

    //         // Set pesan sukses ke dalam session
    //         session()->setFlashdata('success', 'Perkiraan penjualan berhasil disimpan.');
    //         // Redirect ke halaman perkiraan
    //         return redirect()->to('Admin/perkiraan');
    //     } else {
    //         // Metode perkiraan tidak valid
    //         session()->setFlashdata('error', 'Metode perkiraan tidak valid.');
    //         return redirect()->back()->withInput();
    //     }
    // }
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
            return redirect()->to(base_url('Admin/perkiraan'));
        } catch (\Exception $e) {
            session()->setFlashdata('msg', 'Gagal menyimpan perkiraan penjualan. Silakan coba lagi.');
            return redirect()->back()->withInput();
        }
    }

    //akhir peramalan

    //Laporan

    public function lap_permintaan()
    {
        $data = [
            // 'user'=> $query->getResult(),
            'title' => 'Toko Hera - Laporan',

        ];

        return view('Admin/Laporan/Index', $data);
    }

    public function lap_masuk()
    {
        $data = [
            // 'user'=> $query->getResult(),
            'title' => 'Toko Hera - Laporan',

        ];

        return view('Admin/Laporan/Home_transaksimasuk', $data);
    }
    public function lap_keluar()
    {
        $data = [
            // 'user'=> $query->getResult(),
            'title' => 'Toko Hera - Laporan',

        ];

        return view('Admin/Laporan/Home_transaksikeluar', $data);
    }

    //laporan inventaris

    //Laporan Barang
    public function lap_barang()
    {
        $data = [
            'title' => 'Toko Hera - Laporan Barang',
        ];

        return view('Admin/Laporan/Home_barang', $data);
    }
    public function lap_arus_kas()
    {
        $data = [
            'title' => 'Toko Hera - Laporan Arus Kas',
        ];

        return view('Admin/Laporan/Home_arus', $data);
    }
    public function lap_analisa_arus_kas()
    {
        $data = [
            'title' => 'Toko Hera - Laporan Analisa arus kas',
        ];

        return view('Admin/Laporan/Home_analisa', $data);
    }
    public function lap_laba_rugi()
    {
        $data = [
            'title' => 'Toko Hera - Laporan Laba rugi',
        ];

        return view('Admin/Laporan/Home_laba', $data);
    }
    // public function cetakDataBarang()
    // {
    //     $dateOption = $this->request->getGet('date_option');

    //     if (empty($dateOption)) {
    //         return redirect()->to(base_url('Admin'))->with('error', 'Pilih rentang waktu terlebih dahulu.');
    //     }

    //     $today = date('Y-m-d');
    //     $tanggalMulai = '';
    //     $tanggalAkhir = '';

    //     switch ($dateOption) {
    //         case 'today':
    //             $tanggalMulai = $today;
    //             $tanggalAkhir = $today;
    //             break;
    //         case 'this_week':
    //             $tanggalMulai = date('Y-m-d', strtotime('monday this week'));
    //             $tanggalAkhir = date('Y-m-d', strtotime('sunday this week'));
    //             break;
    //         case 'this_month':
    //             $tanggalMulai = date('Y-m-01');
    //             $tanggalAkhir = date('Y-m-t');
    //             break;
    //         default:

    //             return redirect()->to(base_url('Admin'))->with('error', 'Opsi rentang waktu tidak valid.');
    //     }
    //     $barangModel = new BarangModel();

    //     $data['barang'] = $barangModel
    //         ->select('barang.*,transaksi_barang.*,barang.stok, barang.nama_brg,barang.kode_barang, satuan.nama_satuan, barang.merk, transaksi_barang.tanggal_barang_masuk,transaksi_barang.tanggal_barang_keluar,
    //         COALESCE(SUM(CASE WHEN transaksi_barang.jenis_transaksi = "masuk" THEN transaksi_barang.jumlah_perubahan ELSE 0 END), 0) AS total_masuk,
    //         COALESCE(SUM(CASE WHEN transaksi_barang.jenis_transaksi = "keluar" THEN transaksi_barang.jumlah_perubahan ELSE 0 END), 0) AS total_keluar')

    //         ->join('satuan', 'satuan.satuan_id = barang.id_satuan')
    //         ->join('transaksi_barang', 'transaksi_barang.kode_barang = barang.kode_barang', 'left')
    //         ->groupBy('barang.kode_barang') // Assuming kode_barang is the primary key of barang table
    //         ->findAll();

    //     // Ambil stok barang saat ini jika opsi "hari ini" dipilih
    //     if ($dateOption === 'today') {
    //         $data['stokSaatIni'] = $this->getStokSaatIni();
    //     }

    //     $data['tanggalMulai'] = $tanggalMulai;
    //     $data['tanggalAkhir'] = $tanggalAkhir;

    //     $mpdf = new \Mpdf\Mpdf();
    //     $mpdf->showImageErrors = true;
    //     $html = view('Admin/Laporan/Lap_barang', $data);

    //     $mpdf->setAutoPageBreak(true);

    //     $options = [
    //         'curl' => [
    //             CURLOPT_SSL_VERIFYPEER => false,
    //             CURLOPT_SSL_VERIFYHOST => false,
    //         ],
    //     ];

    //     $mpdf->AddPageByArray(['orientation' => 'L'] + $options);

    //     $mpdf->WriteHtml($html);
    //     $this->response->setHeader('Content-Type', 'application/pdf');
    //     $mpdf->Output('Lap Data Barang.pdf', 'I');
    // }
    // public function cetakDataBarang()
    // {
    //     // Ambil tanggal mulai dan tanggal akhir dari form
    //     $tanggalMulai = $this->request->getPost('tanggal_mulai');
    //     $tanggalAkhir = $this->request->getPost('tanggal_akhir');

    //     // Pastikan kedua tanggal tidak kosong
    //     if (empty($tanggalMulai) || empty($tanggalAkhir)) {
    //         return redirect()->back()->withInput()->with('error', 'Pilih rentang waktu terlebih dahulu.');
    //     }

    //     $barangModel = new BarangModel();

    //     // Ambil data persediaan barang berdasarkan rentang waktu
    //     $data['barang'] = $barangModel
    //         ->select('barang.*, SUM(CASE WHEN transaksi_barang.jenis_transaksi = "masuk" THEN transaksi_barang.jumlah_perubahan ELSE 0 END) AS total_masuk,
    //                   SUM(CASE WHEN transaksi_barang.jenis_transaksi = "keluar" THEN transaksi_barang.jumlah_perubahan ELSE 0 END) AS total_keluar')
    //         ->join('transaksi_barang', 'transaksi_barang.kode_barang = barang.kode_barang', 'left')
    //         ->where('transaksi_barang.tanggal_barang_keluar >=', $tanggalMulai)
    //         ->where('transaksi_barang.tanggal_barang_keluar <=', $tanggalAkhir)
    //         ->groupBy('barang.kode_barang') // Assuming kode_barang is the primary key of barang table
    //         ->findAll();

    //     // Kirim data tanggal mulai dan tanggal akhir ke view
    //     $data['tanggalMulai'] = $tanggalMulai;
    //     $data['tanggalAkhir'] = $tanggalAkhir;

    //     // Load view untuk cetak laporan
    //     $html = view('Admin/Laporan/Lap_barang', $data);

    //     // Buat PDF
    //     $mpdf = new \Mpdf\Mpdf();
    //     $mpdf->showImageErrors = true;

    //     // Set halaman PDF
    //     $mpdf->AddPage('L');

    //     // Tulis HTML ke dalam PDF
    //     $mpdf->WriteHtml($html);

    //     // Output PDF ke browser
    //     $mpdf->Output('Laporan_Persediaan_Barang.pdf', 'I');

    //     // dd($data); // Cek apakah data sudah benar
    // }

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

        $data['tanggalMulai'] = $tanggalMulai;
        $data['tanggalAkhir'] = $tanggalAkhir;

        // Load view untuk cetak laporan

        $mpdf = new \Mpdf\Mpdf();
        $mpdf->showImageErrors = true;
        // Menambahkan alias untuk nomor halaman
        $mpdf->AliasNbPages();

        // Mengatur header dan footer
        $mpdf->SetFooter('Halaman {PAGENO} dari {nbpg}');
        $html = view('Admin/Laporan/Lap_barang', $data);

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
        $mpdf->AliasNbPages();

        // Mengatur header dan footer
        $mpdf->SetFooter('Halaman {PAGENO} dari {nbpg}');

        $html = view('Admin/Laporan/Lap_labaRugi', $data);

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

        // Perhitungan arus kas
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
        $mpdf->AliasNbPages();

        // Mengatur header dan footer
        $mpdf->SetFooter('Halaman {PAGENO} dari {nbpg}');

        $html = view('Admin/Laporan/Lap_aruskas', $data);

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

    // public function cetakArusKas()
    // {
    //     $dateOption = $this->request->getGet('date_option');

    //     // Validasi opsi tanggal
    //     if (empty($dateOption)) {
    //         return redirect()->to(base_url('Admin'))->with('error', 'Pilih rentang waktu terlebih dahulu.');
    //     }

    //     $today = date('Y-m-d');
    //     $tanggalMulai = '';
    //     $tanggalAkhir = '';

    //     // Tentukan rentang tanggal berdasarkan opsi yang dipilih
    //     switch ($dateOption) {
    //         case 'today':
    //             $tanggalMulai = $today;
    //             $tanggalAkhir = $today;
    //             break;
    //         case 'this_week':
    //             $tanggalMulai = date('Y-m-d', strtotime('monday this week'));
    //             $tanggalAkhir = date('Y-m-d', strtotime('sunday this week'));
    //             break;
    //         case 'this_month':
    //             $tanggalMulai = date('Y-m-01');
    //             $tanggalAkhir = date('Y-m-t');
    //             break;
    //         default:
    //             // Jika opsi tidak valid, kembalikan ke halaman admin dengan pesan kesalahan
    //             return redirect()->to(base_url('Admin'))->with('error', 'Opsi rentang waktu tidak valid.');
    //     }

    //     // Query untuk mendapatkan total penerimaan dari penjualan
    //     $penjualanModel = new PenjualanBarangModel();
    //     $totalPenerimaanPenjualan = $penjualanModel
    //         ->selectSum('total_penjualan')
    //         ->where('tanggal_penjualan >=', $tanggalMulai)
    //         ->where('tanggal_penjualan <=', $tanggalAkhir)
    //         ->first()['total_penjualan'];

    //     // Query untuk mendapatkan total penerimaan dari investasi
    //     $pemasukanModel = new PemasukanModel();
    //     $totalPenerimaanInvestasi = $pemasukanModel
    //         ->selectSum('jumlah')
    //         ->where('tanggal >=', $tanggalMulai)
    //         ->where('tanggal <=', $tanggalAkhir)
    //         ->where('keterangan', 'Investasi')
    //         ->first()['jumlah'];

    //     // Query untuk mendapatkan total penerimaan lainnya
    //     $totalPenerimaanLainnya = $pemasukanModel
    //         ->selectSum('jumlah')
    //         ->where('tanggal >=', $tanggalMulai)
    //         ->where('tanggal <=', $tanggalAkhir)
    //         ->whereNotIn('keterangan', ['Penjualan', 'Investasi'])
    //         ->first()['jumlah'];

    //     // Query untuk mendapatkan total pengeluaran biaya operasional
    //     $pengeluaranModel = new PengeluaranModel();
    //     $totalBiayaOperasional = $pengeluaranModel
    //         ->selectSum('jumlah')
    //         ->where('tanggal >=', $tanggalMulai)
    //         ->where('tanggal <=', $tanggalAkhir)
    //         ->first()['jumlah'];

    //     // Query untuk mendapatkan total pengeluaran pembelian inventaris
    //     $totalPengeluaranInventaris = $pengeluaranModel
    //         ->selectSum('jumlah')
    //         ->where('tanggal >=', $tanggalMulai)
    //         ->where('tanggal <=', $tanggalAkhir)
    //         ->where('keterangan', 'Pembelian Inventaris')
    //         ->first()['jumlah'];

    //     // Anda perlu menyesuaikan model dan kondisi berdasarkan data yang tersedia
    //     $totalPembayaranUtang = 0; // Contoh: $totalPembayaranUtang = $model->selectSum('jumlah')->where(...)->first()['jumlah'];

    //     // Query untuk mendapatkan total pengeluaran lainnya
    //     // Anda perlu menyesuaikan model dan kondisi berdasarkan data yang tersedia
    //     $totalPengeluaranLainnya = 0; // Contoh: $totalPengeluaranLainnya = $model->selectSum('jumlah')->where(...)->first()['jumlah'];

    //     // Perhitungan arus kas bersih
    //     $arusKasBersih = ($totalPenerimaanPenjualan + $totalPenerimaanInvestasi + $totalPenerimaanLainnya) - ($totalBiayaOperasional + $totalPengeluaranInventaris + $totalPembayaranUtang + $totalPengeluaranLainnya);

    //     // Menyiapkan data untuk dikirim ke view
    //     $data['tanggalMulai'] = $tanggalMulai;
    //     $data['tanggalAkhir'] = $tanggalAkhir;
    //     $data['totalPenerimaanPenjualan'] = $totalPenerimaanPenjualan;
    //     $data['totalPenerimaanInvestasi'] = $totalPenerimaanInvestasi;
    //     $data['totalPenerimaanLainnya'] = $totalPenerimaanLainnya;
    //     $data['totalBiayaOperasional'] = $totalBiayaOperasional;
    //     $data['totalPengeluaranInventaris'] = $totalPengeluaranInventaris;
    //     $data['totalPembayaranUtang'] = $totalPembayaranUtang;
    //     $data['totalPengeluaranLainnya'] = $totalPengeluaranLainnya;
    //     $data['arusKasBersih'] = $arusKasBersih;

    //     // Load view dan generate PDF
    //     $mpdf = new \Mpdf\Mpdf();
    //     $mpdf->showImageErrors = true;
    //     $html = view('Admin/Laporan/Lap_arusKas', $data);

    //     $mpdf->setAutoPageBreak(true);

    //     $options = [
    //         'curl' => [
    //             CURLOPT_SSL_VERIFYPEER => false,
    //             CURLOPT_SSL_VERIFYHOST => false,
    //         ],
    //     ];

    //     $mpdf->AddPageByArray(['orientation' => 'L'] + $options);

    //     $mpdf->WriteHtml($html);
    //     $this->response->setHeader('Content-Type', 'application/pdf');
    //     $mpdf->Output('Laporan Arus Kas.pdf', 'I');
    // }

    // public function analisisArusKas()
    // {
    //     $dateOption = $this->request->getGet('date_option');

    //     if (empty($dateOption)) {
    //         return redirect()->to(base_url('Admin'))->with('error', 'Pilih rentang waktu terlebih dahulu.');
    //     }

    //     $today = date('Y-m-d');
    //     $tanggalMulai = '';
    //     $tanggalAkhir = '';

    //     switch ($dateOption) {
    //         case 'this_year':
    //             $tanggalMulai = date('Y-01-01');
    //             $tanggalAkhir = date('Y-12-31');
    //             break;
    //         default:

    //             return redirect()->to(base_url('Admin'))->with('error', 'Opsi rentang waktu tidak valid.');
    //     }

    //     $penjualanModel = new PenjualanBarangModel();
    //     $totalPenjualan = $penjualanModel
    //         ->selectSum('total_penjualan')
    //         ->where('tanggal_penjualan >=', $tanggalMulai)
    //         ->where('tanggal_penjualan <=', $tanggalAkhir)
    //         ->first()['total_penjualan'];

    //     // Query untuk mendapatkan total harga beli barang dari model BarangModel
    //     $barangModel = new BarangModel();
    //     $totalHargaBeli = $barangModel
    //         ->selectSum('harga_beli')
    //         ->first()['harga_beli'];

    //     // Query untuk mendapatkan total biaya operasional dari model PengeluaranModel
    //     $pengeluaranModel = new PengeluaranModel();
    //     $totalBiayaOperasional = $pengeluaranModel
    //         ->selectSum('jumlah')
    //         ->where('tanggal >=', $tanggalMulai)
    //         ->where('tanggal <=', $tanggalAkhir)
    //         ->first()['jumlah'];

    //     // Perhitungan total arus kas
    //     $totalArusKas = $totalPenjualan - $totalHargaBeli - $totalBiayaOperasional;

    //     // Query untuk mendapatkan data penjualan tahun sebelumnya
    //     $tanggalMulaiTahunSebelumnya = date('Y-m-d', strtotime($tanggalMulai . ' -1 year'));
    //     $tanggalAkhirTahunSebelumnya = date('Y-m-d', strtotime($tanggalAkhir . ' -1 year'));
    //     $totalPenjualanTahunSebelumnya = $penjualanModel
    //         ->selectSum('total_penjualan')
    //         ->where('tanggal_penjualan >=', $tanggalMulaiTahunSebelumnya)
    //         ->where('tanggal_penjualan <=', $tanggalAkhirTahunSebelumnya)
    //         ->first()['total_penjualan'];

    //     // Query untuk mendapatkan total harga beli barang tahun sebelumnya
    //     $totalHargaBeliTahunSebelumnya = $barangModel
    //         ->selectSum('harga_beli')
    //         ->where('created_at >=', $tanggalMulaiTahunSebelumnya)
    //         ->where('created_at <=', $tanggalAkhirTahunSebelumnya)
    //         ->first()['harga_beli'];

    //     // Query untuk mendapatkan total biaya operasional tahun sebelumnya
    //     $totalBiayaOperasionalTahunSebelumnya = $pengeluaranModel
    //         ->selectSum('jumlah')
    //         ->where('tanggal >=', $tanggalMulaiTahunSebelumnya)
    //         ->where('tanggal <=', $tanggalAkhirTahunSebelumnya)
    //         ->first()['jumlah'];

    //     // Perhitungan total aktivitas operasional
    //     $totalAktivitasOperasional = $totalPenjualan - $totalHargaBeli - $totalBiayaOperasional;
    //     $totalAktivitasOperasionalTahunSebelumnya = $totalPenjualanTahunSebelumnya - $totalHargaBeliTahunSebelumnya - $totalBiayaOperasionalTahunSebelumnya;
    //     // Query untuk mendapatkan total penerimaan penjualan
    //     $pemasukanModel = new PemasukanModel();
    //     $totalPenerimaanPenjualan = $pemasukanModel
    //         ->selectSum('jumlah')
    //         ->where('tanggal >=', $tanggalMulai)
    //         ->where('tanggal <=', $tanggalAkhir)
    //         ->where('keterangan', 'Penjualan')
    //         ->first()['jumlah'];

    //     // Query untuk mendapatkan total penerimaan penjualan aset tetap
    //     $totalPenerimaanAsetTetap = $pemasukanModel
    //         ->selectSum('jumlah')
    //         ->where('tanggal >=', $tanggalMulai)
    //         ->where('tanggal <=', $tanggalAkhir)
    //         ->where('keterangan', 'Penerimaan Penjualan Aset Tetap')
    //         ->first()['jumlah'];

    //     // Query untuk mendapatkan total penerimaan penjualan aset tetap tahun sebelumnya
    //     $totalPenerimaanAsetTetapTahunSebelumnya = $pemasukanModel
    //         ->selectSum('jumlah')
    //         ->where('tanggal >=', $tanggalMulaiTahunSebelumnya)
    //         ->where('tanggal <=', $tanggalAkhirTahunSebelumnya)
    //         ->where('keterangan', 'Penerimaan Penjualan Aset Tetap')
    //         ->first()['jumlah'];

    //     // Query untuk mendapatkan total pembayaran pembelian aset tetap tahun sebelumnya
    //     $totalPembayaranAsetTetapTahunSebelumnya = $pengeluaranModel
    //         ->selectSum('jumlah')
    //         ->where('tanggal >=', $tanggalMulaiTahunSebelumnya)
    //         ->where('tanggal <=', $tanggalAkhirTahunSebelumnya)
    //         ->where('keterangan', 'Pembayaran Pembelian Aset Tetap')
    //         ->first()['jumlah'];

    //     // Query untuk mendapatkan total pembayaran pembelian aset tetap
    //     $totalPembayaranAsetTetap = $pengeluaranModel
    //         ->selectSum('jumlah')
    //         ->where('tanggal >=', $tanggalMulai)
    //         ->where('tanggal <=', $tanggalAkhir)
    //         ->where('keterangan', 'Pembayaran Pembelian Aset Tetap')
    //         ->first()['jumlah'];

    //     $totalArusKasTahunSebelumnya = $totalPenjualanTahunSebelumnya - $totalHargaBeliTahunSebelumnya - $totalBiayaOperasionalTahunSebelumnya;

    //     // Inisialisasi nilai kasAwal dan kasAwalTahunSebelumnya dengan totalArusKasTahunSebelumnya
    //     $kasAwal = $kasAwalTahunSebelumnya = $totalArusKasTahunSebelumnya;

    //     // Perhitungan kasAkhir dan kasAkhirTahunSebelumnya
    //     $kasAkhir = $kasAwal + $totalArusKas;
    //     $kasAkhirTahunSebelumnya = $kasAwalTahunSebelumnya + $totalArusKasTahunSebelumnya;
    //     // Perhitungan total aktivitas investasi
    //     $totalAktivitasInvestasi = $totalPenerimaanAsetTetap - $totalPembayaranAsetTetap;

    //     // Perhitungan total arus kas tahun sebelumnya
    //     $totalArusKasTahunSebelumnya = $totalPenjualanTahunSebelumnya - $totalHargaBeliTahunSebelumnya - $totalBiayaOperasionalTahunSebelumnya;
    //     // Perhitungan total aktivitas investasi tahun sebelumnya
    //     $totalAktivitasInvestasiTahunSebelumnya = $totalPenerimaanAsetTetapTahunSebelumnya - $totalPembayaranAsetTetapTahunSebelumnya;

    //     // Menyiapkan data untuk dikirim ke view
    //     $data['kasAwal'] = $kasAwal;
    //     $data['kasAkhir'] = $kasAkhir;
    //     $data['kasAwalTahunSebelumnya'] = $kasAwalTahunSebelumnya;
    //     $data['kasAkhirTahunSebelumnya'] = $kasAkhirTahunSebelumnya;

    //     $data['tanggalMulai'] = $tanggalMulai;
    //     $data['tanggalAkhir'] = $tanggalAkhir;
    //     $data['totalPenjualan'] = $totalPenjualan;
    //     $data['totalHargaBeli'] = $totalHargaBeli;
    //     $data['totalBiayaOperasional'] = $totalBiayaOperasional;
    //     $data['totalArusKas'] = $totalArusKas;
    //     $data['totalPenerimaanPenjualan'] = $totalPenerimaanPenjualan;
    //     $data['totalPenerimaanAsetTetap'] = $totalPenerimaanAsetTetap;
    //     $data['totalPembayaranAsetTetap'] = $totalPembayaranAsetTetap;
    //     $data['totalPenjualanTahunSebelumnya'] = $totalPenjualanTahunSebelumnya;
    //     $data['totalHargaBeliTahunSebelumnya'] = $totalHargaBeliTahunSebelumnya;
    //     $data['totalBiayaOperasionalTahunSebelumnya'] = $totalBiayaOperasionalTahunSebelumnya;
    //     $data['totalAktivitasOperasional'] = $totalAktivitasOperasional;
    //     $data['totalAktivitasInvestasi'] = $totalAktivitasInvestasi;
    //     $data['totalArusKasTahunSebelumnya'] = $totalArusKasTahunSebelumnya;
    //     $data['totalPembayaranAsetTetapTahunSebelumnya'] = $totalPembayaranAsetTetapTahunSebelumnya;
    //     $data['totalAktivitasOperasionalTahunSebelumnya'] = $totalAktivitasOperasionalTahunSebelumnya;
    //     $data['totalPenerimaanAsetTetapTahunSebelumnya'] = $totalPenerimaanAsetTetapTahunSebelumnya;
    //     $data['totalAktivitasInvestasiTahunSebelumnya'] = $totalAktivitasInvestasiTahunSebelumnya;

    //     // Load view dan generate PDF
    //     $mpdf = new \Mpdf\Mpdf();
    //     $mpdf->showImageErrors = true;
    //     $html = view('Admin/Laporan/Lap_analisisArusKas', $data);

    //     $mpdf->setAutoPageBreak(true);

    //     $options = [
    //         'curl' => [
    //             CURLOPT_SSL_VERIFYPEER => false,
    //             CURLOPT_SSL_VERIFYHOST => false,
    //         ],
    //     ];

    //     $mpdf->AddPageByArray(['orientation' => 'L'] + $options);

    //     $mpdf->WriteHtml($html);
    //     $this->response->setHeader('Content-Type', 'application/pdf');
    //     $mpdf->Output('Laporan Analisis Arus Kas.pdf', 'I');
    // }
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
        $mpdf->AliasNbPages();

        // Mengatur header dan footer
        $mpdf->SetFooter('Halaman {PAGENO} dari {nbpg}');
        $html = view('Admin/Laporan/Lap_analisisArusKas', $data);

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

    // tambah user
    public function kelola_user()
    {
        $userModel = new UserModel();
        $data['users'] = $userModel->findAll();

        $groupModel = new GroupModel();
        $no = 1;

        foreach ($data['users'] as $row) {
            $dataRow['group'] = $groupModel->getGroupsForUser($row->id);
            $dataRow['row'] = $row;
            $dataRow['no'] = $no++;
            $data['row' . $row->id] = view('Admin/User/Row', $dataRow);
        }
        $data['groups'] = $groupModel->findAll();
        $data['title'] = 'Daftar Pengguna';
        return view('Admin/User/Index', $data);
    }

    public function tambah_user()
    {

        $data = [
            'title' => 'Toko Hera - Tambah Users',
        ];
        return view('/Admin/User/Tambah', $data);
    }

    public function changeGroup()
    {
        $userId = $this->request->getVar('id');
        $groupId = $this->request->getVar('group');
        $groupModel = new GroupModel();
        $groupModel->removeUserFromAllGroups(intval($userId));
        $groupModel->addUserToGroup(intval($userId), intval($groupId));
        return redirect()->to(base_url('/Admin/kelola_user'));
    }

    public function changePassword()
    {
        $userId = $this->request->getVar('user_id');

        $password_baru = $this->request->getVar('password_baru');
        $userModel = new \App\Models\User();
        $user = $userModel->getUsers($userId);
        // $dataUser->update($userId, ['password_hash' => password_hash($password_baru, PASSWORD_DEFAULT)]);
        $userEntity = new User($user);
        $userEntity->password = $password_baru;
        $userModel->save($userEntity);
        return redirect()->to(base_url('Admin/kelola_user'));
    }

    public function activateUser($id, $active)
    {
        $userModel = new UserModel();
        $user = $userModel->find($id);

        if ($user) {
            $userModel->update($id, ['active' => $active]);
            return redirect()->back()->with('success', 'Status pengguna berhasil diperbarui.');
        } else {
            return redirect()->back()->with('error', 'Pengguna tidak ditemukan.');
        }
    }

    public function aset()
    {
        $data = [
            'title' => 'Aset Toko',
            'aset' => $this->asetModel->orderBy('created_at', 'ASC')->findAll(),
        ];

        return view('Admin/Aset/Index', $data);
    }
    public function tambahAset()
    {
        $data = [
            'title' => 'Tambah Aset',
            'validation' => $this->validation,
        ];
        return view('Admin/Aset/TambahAset', $data);
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
            return redirect()->to('/admin/tambahAset')->withInput();
        }
        $data = [
            'nama_aset' => $this->request->getPost('nama_aset'),
            'nilai' => $this->request->getPost('nilai'),
            'created_at' => date('Y-m-d H:i:s'),

        ];
        // dd($data);
        $this->asetModel->insert($data);

        session()->setFlashdata('pesan', 'Data berhasil ditambahkan');
        return redirect()->to('/admin/aset');
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

        return view('Admin/Aset/EditAset', $data); // Sesuaikan dengan lokasi view Anda
    }

    public function updateAset($id)
    {
        $data = [
            'nama_aset' => $this->request->getPost('nama_aset'),
            'nilai' => $this->request->getPost('nilai'),
        ];

        // Validasi data di sini jika diperlukan

        // Update data aset
        $this->asetModel->update($id, $data);

        session()->setFlashdata('pesanBerhasil', 'Data berhasil diupdate');
        return redirect()->to('/admin/aset');
    }

    public function deleteAset($id)
    {
        // Hapus data aset berdasarkan ID
        $this->asetModel->delete($id);

        session()->setFlashdata('pesanBerhasil', 'Data berhasil dihapus');
        return redirect()->to('/admin/aset');
    }

    public function supplier()
    {
        $data = [
            'title' => 'Data Supplier',
            'suppliers' => $this->supplierModel->findAll(),
        ];

        return view('Admin/Supplier/Index', $data);
    }

    public function tambahSupplier()
    {
        $data = [
            'title' => 'Tambah Supplier',
            'validation' => $this->validation,
        ];
        return view('Admin/Supplier/TambahSupplier', $data);
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
        return redirect()->to('/admin/supplier');
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

        return view('Admin/Supplier/EditSupplier', $data);
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
        return redirect()->to('/admin/supplier');
    }

    public function deleteSupplier($id)
    {
        $this->supplierModel->delete($id);

        session()->setFlashdata('message', 'Data supplier berhasil dihapus');
        return redirect()->to('/admin/supplier');
    }

    // public function hutang()
    // {
    //     // Ambil saldo terakhir dari KasModel
    //     $latest_kas = $this->KasModel->orderBy('id_kas', 'DESC')->first();
    //     $saldo_kas = $latest_kas['saldo_terakhir'];

    //     // Data hutang
    //     $hutangs = $this->hutangModel->findAll();

    //     // Calculate total hutang
    //     $total_hutang = 0;
    //     foreach ($hutangs as $hutang) {
    //         $total_hutang += $hutang['jumlah'];
    //     }

    //     // Calculate total hutang sisa
    //     $jumlah_sisa = $total_hutang - $saldo_kas;

    //     // Prepare data to be passed to the view
    //     $data = [
    //         'title' => 'Data Hutang',
    //         'hutangs' => $hutangs,
    //         'saldo_kas' => $saldo_kas, // Menyertakan saldo kas saat ini
    //         'jumlah_sisa' => $jumlah_sisa, // Menyertakan total hutang sisa
    //     ];

    //     return view('Admin/Hutang/Index', $data);
    // }
    public function hutang()
    {
        // Ambil saldo terakhir dari KasModel
        $latest_kas = $this->KasModel->orderBy('id_kas', 'DESC')->first();

        // Default saldo kas
        $saldo_kas = 0; // Default jika tidak ada saldo kas ditemukan
        if ($latest_kas) {
            $saldo_kas = $latest_kas['saldo_terakhir'];
        }

        // Data hutang
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
        return view('Admin/Hutang/Index', $data);
    }

    public function tambahHutang()
    {
        $data = [
            'title' => 'Tambah Hutang',
            'validation' => $this->validation,
        ];
        return view('Admin/Hutang/TambahHutang', $data);
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
        return redirect()->to('/admin/hutang');
    }
    public function savePiutang()
    {
        $piutangModel = new piutangModel(); // Sesuaikan dengan nama model yang benar

        // Validasi input
        if (!$this->validate([
            'keterangan' => 'required',

        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Data untuk disimpan
        $data = [
            'id_penjualan_barang' => 'Penambahan manual', // Misalnya 'Penambahan manual', sesuaikan dengan kebutuhan
            'keterangan' => $this->request->getPost('keterangan'),
            'jumlah_piutang' => $this->request->getPost('jumlah_piutang'),
            'tanggal_piutang' => $this->request->getPost('tanggal'),
            'created_at' => date('Y-m-d H:i:s'), // Tanggal dan waktu saat ini
            'jatuh_tempo' => date('Y-m-d', strtotime('+30 days')), // Jatuh tempo 30 hari dari sekarang
            'jumlah_terbayar' => 0, // Awalnya belum ada yang terbayar
            'id_pelanggan' => $this->request->getPost('id_pelanggan'),
        ];

        // Insert data ke dalam tabel piutang
        if (!$piutangModel->insert($data)) {
            $errors = $piutangModel->errors();
            foreach ($errors as $error) {
                echo $error . '<br>';
            }
            return redirect()->back()->withInput()->with('errors', $errors);
        }


        // Jika berhasil, set flash message dan redirect ke halaman yang tepat
        session()->setFlashdata('message', 'Data piutang berhasil ditambahkan');
        return redirect()->to('/PenjualanBarangCont/piutang');
    }

    // Pembayaran hutang
    // public function bayarHutang($id_hutang)
    // {
    //     // Pastikan validasi disini
    //     $PengeluaranModel = new PengeluaranModel();
    //     // Ambil saldo terakhir dari KasModel
    //     $latest_kas = $this->KasModel->orderBy('id_kas', 'DESC')->first();
    //     $saldo_terakhir = $latest_kas['saldo_terakhir'];

    //     // Ambil hutang yang akan dibayar
    //     $hutang = $this->hutangModel->find($id_hutang);

    //     // Ambil jumlah pembayaran dari input
    //     $jumlah_bayar = $this->request->getPost('jumlah_bayar');

    //     // Validasi jumlah pembayaran
    //     if ($jumlah_bayar <= 0) {
    //         // Handle error jika jumlah pembayaran tidak valid
    //         return redirect()->back()->withInput()->with('error', 'Jumlah pembayaran tidak valid.');
    //     }

    //     if ($jumlah_bayar > $hutang['jumlah_sisa']) {
    //         // Handle error jika jumlah pembayaran melebihi jumlah sisa hutang
    //         return redirect()->back()->withInput()->with('error', 'Jumlah pembayaran melebihi jumlah sisa hutang.');
    //     }

    //     // Validasi saldo kas
    //     if ($jumlah_bayar > $saldo_terakhir) {
    //         // Handle error jika jumlah pembayaran melebihi saldo kas
    //         return redirect()->back()->withInput()->with('error', 'Saldo kas tidak mencukupi.');
    //     }

    //     // Lakukan proses pembayaran hutang
    //     $data_pembayaran = [
    //         'tanggal' =>  date('Y-m-d H:i:s'),
    //         'jenis_transaksi' => 'pengeluaran',
    //         'keterangan' => 'Pembayaran hutang: ' . $hutang['keterangan'],
    //         'jumlah_awal' => $saldo_terakhir,
    //         'jumlah_akhir' => $jumlah_bayar,
    //         'saldo_terakhir' => $saldo_terakhir - $jumlah_bayar,
    //     ];

    //     // Simpan data pembayaran ke dalam tabel kas
    //     $this->KasModel->insert($data_pembayaran);

    //     // Update jumlah sisa hutang
    //     $data_hutang = [
    //         'jumlah_sisa' => $hutang['jumlah_sisa'] - $jumlah_bayar, // Update jumlah_sisa
    //     ];
    //     // Cek apakah hutang sudah lunas
    //     if ($data_hutang['jumlah_sisa'] == 0) {
    //         // Jika sudah lunas, ubah status menjadi 'Lunas'
    //         $data_hutang['status'] = 'lunas';
    //     }

    //     $this->hutangModel->update($id_hutang, $data_hutang);

    //     $data_pengeluaran = [
    //         'tanggal' =>  date('Y-m-d H:i:s'),
    //         'keterangan' => 'Pembayaran Hutang', // Keterangan transaksi
    //         'jumlah' =>  $jumlah_bayar, // Jumlah pembayaran hutang
    //     ];

    //     // Simpan data ke dalam tabel pengeluaran
    //     $PengeluaranModel->insert($data_pengeluaran);

    //     // Redirect dengan pesan sukses
    //     return redirect()->to('/Admin/hutang')->with('success', 'Pembayaran hutang berhasil.');
    // }
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
            'tanggal' =>  date('Y-m-d H:i:s'),
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
            'created_at' => date('Y-m-d H:i:s'),
        ];
        $this->hutangModel->update($id_hutang, $data_hutang);

        $data_pengeluaran = [
            'tanggal' =>  date('Y-m-d H:i:s'),
            'keterangan' => 'Pembayaran Hutang', // Keterangan transaksi
            'jumlah' => $total_hutang, // Jumlah pembayaran hutang
        ];

        // Simpan data ke dalam tabel pengeluaran
        $PengeluaranModel->insert($data_pengeluaran);

        // Redirect dengan pesan sukses
        return redirect()->to('/Admin/hutang')->with('success', 'Pembayaran hutang berhasil dilunasi.');
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

        return view('Admin/Hutang/EditHutang', $data);
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
        return redirect()->to('/admin/hutang');
    }

    public function deleteHutang($id)
    {
        $this->hutangModel->delete($id);

        session()->setFlashdata('message', 'Data hutang berhasil dihapus');
        return redirect()->to('/admin/hutang');
    }

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
        return view('Admin/Restok/Index', $data);
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

        return view('Admin/Restok/Detail', $data);
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

        return view('Admin/Restok/TambahRestok', $data);
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

        // Menghitung jumlah uang yang dibayarkan oleh pelanggan
        $jumlah_uang = filter_var($this->request->getPost('jumlah_uang'), FILTER_SANITIZE_NUMBER_INT);

        // Mengurangi jumlah uang yang dibayarkan dari saldo terakhir
        $newBalance = $saldo_terakhir - $jumlah_uang;

        // Menyimpan data restok
        $data_restok = [
            'restok_id' => $kode_restok,
            'id_supplier' => $id_supplier,
            'tanggal' =>  date('Y-m-d H:i:s'),
            'jumlah_pembayaran' => filter_var($this->request->getPost('jumlah_pembayaran'), FILTER_SANITIZE_NUMBER_INT),
            'jumlah_uang' => $jumlah_uang, // Simpan jumlah uang yang dibayarkan
            'kembalian' => filter_var($this->request->getPost('kembalian'), FILTER_SANITIZE_NUMBER_INT),
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
                'status_bayar' => $data['status_bayar'][$key],
            ];

            // Memasukkan data detail restok ke dalam database
            $detailRestokModel->insert($data_detail_restok);

            // Mendapatkan data barang dari database
            $barang = $barangModel->getBarang($value);

            // Mengupdate stok barang
            $data_barang = [
                'stok' => $barang['stok'] + $data['jumlah'][$key],
            ];
            $barangModel->update($value, $data_barang);

            // Memproses pembayaran hutang
            if ($data['status_bayar'][$key] == 'hutang') {
                $hutang += filter_var($data['sub_total'][$key], FILTER_SANITIZE_NUMBER_INT);
                $data_hutang = [
                    'keterangan' => 'Hutang Restok ' . $kode_restok . '(' . $barang['nama_brg'] . ')',
                    'jumlah' => $hutang,
                    'tanggal' =>  date('Y-m-d H:i:s'),
                ];
                $hutangModel->insert($data_hutang);
            }
        }

        // Menyimpan data ke dalam tabel kas
        $data_pengeluaran = [
            'tanggal' =>  date('Y-m-d H:i:s'),

            'keterangan' => 'Restok barang', // Keterangan transaksi
            'jumlah' => filter_var($this->request->getPost('jumlah_pembayaran'), FILTER_SANITIZE_NUMBER_INT), // Saldo sebelum restok

        ];

        // Simpan data ke dalam tabel kas
        $PengeluaranModel->insert($data_pengeluaran);

        $data_pengeluaran = [
            'tanggal' =>  date('Y-m-d H:i:s'),
            'jenis_transaksi' => 'pengeluaran', // Misalnya, restok barang
            'keterangan' => 'Restok barang', // Keterangan transaksi
            'jumlah_awal' => $saldo_terakhir, // Saldo sebelum restok
            'jumlah_akhir' => $jumlah_uang, // Pengurangan saldo karena pembayaran
            'saldo_terakhir' => $newBalance, // Saldo terbaru setelah restok
        ];

        // Simpan data ke dalam tabel kas
        $kasModel->insert($data_pengeluaran);

        // Mengurangi saldo terakhir di kas
        $kasModel->updateLastBalance($newBalance);

        // Redirect dan set pesan berhasil
        session()->setFlashdata('pesanBerhasil', 'Data restok berhasil ditambahkan');
        return redirect()->to('/admin/restok');
    }

    // public function simpanRestok()
    // {
    //     $barangModel = new BarangModel();
    //     $RestokModel = new restokModel();
    //     $hutangModel = new hutangModel();
    //     $modalTokoModel = new modalTokoModel();
    //     $detailRestokModel = new detailRestokModel();
    //     $supplierModel = new supplierModel();
    //     $kasModel = new KasModel();

    //     $kode_restok = 'RST-' . date('Ymdhis') . rand(100, 999);
    //     $id_supplier = $this->request->getPost('id_supplier');
    //     $hutang = 0;

    //     $latest_kas = $this->KasModel->orderBy('id_kas', 'DESC')->first();

    //     // Mendapatkan saldo terakhir
    //     $saldo_terakhir = $latest_kas['saldo_terakhir'];
    //     $data_restok = [
    //         'restok_id' => $kode_restok,
    //         'id_supplier' => $id_supplier,
    //         'tanggal' =>  date('Y-m-d H:i:s'),
    //         'jumlah_pembayaran' => filter_var($this->request->getPost('jumlah_pembayaran'), FILTER_SANITIZE_NUMBER_INT),
    //         'jumlah_uang' => filter_var($this->request->getPost('jumlah_uang'), FILTER_SANITIZE_NUMBER_INT),
    //         'kembalian' => filter_var($this->request->getPost('kembalian'), FILTER_SANITIZE_NUMBER_INT),
    //     ];
    //     // dd($data_restok);
    //     $RestokModel->insert($data_restok);

    //     $data = $this->request->getPost();
    //     // dd($data);
    //     foreach ($data['kode_barang'] as $key => $value) {
    //         $data_detail_restok = [
    //            'id_restok' => $kode_restok,
    //            'kode_barang' => $value,
    //             'harga_beli' => filter_var($data['harga'][$key], FILTER_SANITIZE_NUMBER_INT),
    //            'jumlah_restok' => $data['jumlah'][$key],
    //            'sub_total' => filter_var($data['sub_total'][$key], FILTER_SANITIZE_NUMBER_INT),
    //            'status_bayar' => $data['status_bayar'][$key],
    //         ];
    //         // dd($data_detail_restok);

    //         $detailRestokModel->insert($data_detail_restok);

    //         $barang = $barangModel->getBarang($value);
    //         // dd($barang);
    //         $data_barang = [
    //             'stok' => $barang['stok'] + $data['jumlah'][$key],
    //         ];

    //         $barangModel->update($value, $data_barang);

    //         if ($data['status_bayar'][$key] == 'hutang') {
    //             $hutang += filter_var($data['sub_total'][$key], FILTER_SANITIZE_NUMBER_INT);
    //             $data_hutang =
    //             [
    //                 'keterangan' => 'Hutang Restok ' . $kode_restok. '(' . $barang['nama_brg'] . ')',
    //                 'jumlah' => $hutang,
    //                 'tanggal' =>  date('Y-m-d H:i:s'),
    //             ];

    //             $hutangModel->insert($data_hutang);
    //         }

    //     }
    //     // dd($hutang);
    //     session()->setFlashdata('pesanBerhasil', 'Data restok berhasil ditambahkan');
    //     return redirect()->to('/admin/restok');
    // }

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
            return redirect()->to('/admin/restok');
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
        return redirect()->to('/admin/restok');
    }

    public function modal()
    {
        // Mengambil semua data dari tabel modal_toko
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

        // Menampilkan view 'Admin/Modal/Index' dengan data yang sudah disiapkan
        return view('Admin/Modal/Index', $data);
    }

    public function tambahModal()
    {
        $data = [
            'title' => 'Tambah Modal',
            'validation' => $this->validation,
        ];
        return view('Admin/Modal/TambahModal', $data);
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
            return redirect()->to('/Admin/tambah_modal')->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'sumber' => $this->request->getPost('sumber'),
            'jumlah' => $this->request->getPost('jumlah'),
        ];

        $this->modalTokoModel->insert($data);

        return redirect()->to('/Admin/modal')->with('msg', 'Data modal berhasil ditambahkan.');
    }

    public function editModal($id)
    {
        $data = [
            'title' => 'Ubah Modal',
            'validation' => $this->validation,
            'modal' => $this->modalTokoModel->getModal($id), // Menggunakan fungsi getModal untuk mendapatkan data modal berdasarkan ID
        ];
        return view('Admin/Modal/EditModal', $data);
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

        return redirect()->to('/admin/modal');
    }

    public function deleteModal($id)
    {
        $this->modalTokoModel->delete($id);
        session()->setFlashdata('msg', 'Data berhasil dihapus');
        return redirect()->to('/admin/modal');
    }

    //kas
    public function kas()
    {
        // Mendapatkan data kas terbaru
        $latest_kas = $this->KasModel->orderBy('id_kas', 'DESC')->first();

        // Memeriksa apakah data kas kosong
        if ($latest_kas === null) {
            $saldo_terakhir = 0; // Nilai default jika tidak ada data
        } else {
            // Mendapatkan saldo terakhir dari data kas terbaru
            $saldo_terakhir = $latest_kas['saldo_terakhir'];
        }

        // Mengirimkan data ke view
        $data = [
            'saldo_terakhir' => $saldo_terakhir,
            'kas' => $this->KasModel->orderBy('id_kas', 'ASC')->findAll(),
            'title' => 'Data Kas',
        ];

        return view('Admin/Kas/Index', $data);
    }


    public function tambahKas()
    {
        $data = [
            'title' => 'Tambah Kas',
            'validation' => $this->validation,
        ];
        return view('Admin/Kas/TambahKas', $data);
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

        // Ambil saldo terakhir dari transaksi kas terbaru
        $latest_kas = $this->KasModel->orderBy('id_kas', 'DESC')->first();
        $saldo_terakhir = $latest_kas ? floatval($latest_kas['saldo_terakhir']) : 0;

        // Ambil data dari request
        $data = [
            'tanggal' => $this->request->getPost('tanggal'),
            'jenis_transaksi' => $this->request->getPost('jenis_transaksi'),
            'keterangan' => $this->request->getPost('keterangan'),
        ];

        // Tambahkan jumlah masuk atau jumlah keluar berdasarkan jenis transaksi
        if ($data['jenis_transaksi'] === 'penerimaan') {
            $data['jumlah_awal'] = $saldo_terakhir;
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
        return redirect()->to('/Admin/kas')->with('pesanBerhasil', 'Data kas berhasil ditambahkan.');
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

        return view('Admin/Kas/EditKas', $data);
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
        return redirect()->to('/Admin/kas')->with('msg', 'Data kas berhasil diupdate.');
    }

    // Method to get the previous saldo
    private function getPreviousSaldo($id)
    {
        $previousTransaction = $this->KasModel->where('id_kas <', $id)->orderBy('id_kas', 'DESC')->first();
        return $previousTransaction ? $previousTransaction['saldo_terakhir'] : 0;
    }

    // Method to calculate the new saldo
    private function calculateNewSaldo($id, $saldoTerakhir)
    {
        $previousSaldo = $this->getPreviousSaldo($id);
        return $previousSaldo + $saldoTerakhir;
    }

    // Method to recalculate the saldo for all subsequent transactions
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
        return redirect()->to('/Admin/kas')->with('msg', 'Data kas berhasil dihapus.');
    }

    // public function bayarPiutang($id_piutang)
    // {
    //     // Ambil saldo terakhir dari KasModel
    //     $latest_kas = $this->KasModel->orderBy('id_kas', 'DESC')->first();
    //     $saldo_terakhir = $latest_kas['saldo_terakhir'];

    //     // Ambil data penjualan berdasarkan id_penjualan
    //     $penjualan = $this->PenjualanBarangModel->findPenjualanById($id);

    //     // Ambil total penjualan dan jumlah uang yang sudah dibayarkan
    //     $total_penjualan = $penjualan['total_penjualan'];
    //     $jumlah_uang = $penjualan['jumlah_uang'];

    //     // Validasi jumlah pembayaran
    //     if ($jumlah_bayar <= 0) {
    //         return redirect()->back()->with('error', 'Jumlah pembayaran tidak valid.');
    //     }

    //     // Validasi apakah pembayaran melebihi jumlah piutang yang tersisa
    //     $sisa_piutang = $total_penjualan - $jumlah_uang;
    //     if ($jumlah_bayar > $sisa_piutang) {
    //         return redirect()->back()->with('error', 'Jumlah pembayaran melebihi sisa piutang.');
    //     }

    //     // Hitung saldo terakhir setelah pembayaran
    //     $saldo_terakhir_baru = $saldo_terakhir + $jumlah_bayar;

    //     // Update data penerimaan di kas
    //     $data_penerimaan = [
    //         'tanggal' =>  date('Y-m-d H:i:s'),
    //         'jenis_transaksi' => 'penerimaan',
    //         'keterangan' => 'Pembayaran piutang: ' . $penjualan['penjualan_barang_id'],
    //         'jumlah_awal' => $saldo_terakhir,
    //         'jumlah_akhir' => $jumlah_bayar,
    //         'saldo_terakhir' => $saldo_terakhir_baru,
    //     ];

    //     // Simpan data penerimaan ke dalam tabel kas
    //     $this->KasModel->insert($data_penerimaan);

    //     // Hitung jumlah uang baru dan sisa piutang
    //     $jumlah_uang_baru = $jumlah_uang + $jumlah_bayar;
    //     $sisa_piutang_baru = $total_penjualan - $jumlah_uang_baru;

    //     // Update jumlah uang dan status piutang di tabel penjualan
    //     $data_penjualan = [
    //         'jumlah_uang' => $jumlah_uang_baru,
    //     ];

    //     // Jika piutang sudah lunas, update status
    //     if ($sisa_piutang_baru == 0) {
    //         $data_penjualan['status_piutang'] = 'lunas';
    //     }

    //     // Simpan pembaruan data penjualan
    //     dd($penjualan,$total_penjualan,$jumlah_uang, $jumlah_bayar,$saldo_terakhir_baru, $data_penerimaan,$data_penjualan);
    //     $this->penjualanModel->update($id_penjualan, $data_penjualan);

    //     // Redirect dengan pesan sukses
    //     return redirect()->to('/Admin/piutang')->with('success', 'Pembayaran piutang berhasil diterima.');
    // }

    public function debugbayarPiutang($id_piutang)
    {
        // Load model
        $penjualanBarangModel = new PenjualanBarangModel();
        $piutangModel = new piutangModel();

        $db = \Config\Database::connect();
        $builder = $db->table('piutang');
        $builder->select('piutang.*, penjualan_barang.penjualan_barang_id, penjualan_barang.tanggal_penjualan, penjualan_barang.total_penjualan, penjualan_barang.id_pelanggan, penjualan_barang.jumlah_uang, penjualan_barang.status_piutang');
        $builder->join('penjualan_barang', 'piutang.id_penjualan_barang = penjualan_barang.penjualan_barang_id');
        $builder->where('piutang.id_piutang', $id_piutang);
        $query = $builder->get();

        $data = $query->getRowArray();

        // Dump and die to inspect the data
        dd($data);

        if ($data) {
            // Process your data here
            return view('bayar_piutang', ['data' => $data]);
        } else {
            // Handle no data found case
            return redirect()->to('/error-page');
        }
    }

    public function ddbayarPiutang($id_piutang)
    {
        // Load models
        $penjualanBarangModel = new PenjualanBarangModel();
        $piutangModel = new PiutangModel();
        $pembayaranPiutangModel = new pembayaranPiutangModel(); // Model for pembayaran_piutang table
        $KasModel = new KasModel();

        // Get piutang data using join query
        $db = \Config\Database::connect();
        $builder = $db->table('piutang');
        $builder->select('piutang.*, penjualan_barang.*');
        $builder->join('penjualan_barang', 'piutang.id_penjualan_barang = penjualan_barang.penjualan_barang_id');
        $builder->where('piutang.id_piutang', $id_piutang);
        $query = $builder->get();

        $data = $query->getRowArray();

        // Dump and die to inspect variables

        // Check if data exists
        if (!$data) {
            // Handle no data found case
            return redirect()->to('/error-page');
        }

        // Calculate remaining piutang
        $jumlahUang = $data['jumlah_uang'];
        $jumlahPiutang = $data['jumlah_piutang'];

        $sisaPiutang = $jumlahPiutang - $jumlahUang;

        // Prepare data for pembayaran_piutang table
        $insertData = [
            'id_piutang' => $id_piutang,
            'tanggal_pembayaran' =>  date('Y-m-d H:i:s'), // Tanggal pembayaran saat ini
            'jumlah_pembayaran' => $jumlahUang,
        ];

        // Insert data into pembayaran_piutang table
        $pembayaranPiutangModel->insert($insertData);

        // Update piutang data in piutang table
        $updatePiutang = [
            'jumlah_terbayar' => $jumlahUang,
            'status_piutang' => ($sisaPiutang <= 0) ? 'lunas' : 'belum_lunas', // Update status to lunas if sisaPiutang <= 0
        ];

        $piutangModel->update($id_piutang, $updatePiutang);

        // Update status piutang in penjualan_barang table
        $updatePenjualan = [
            'status_piutang' => $updatePiutang['status_piutang'], // Update status_piutang in penjualan_barang
        ];

        dd($data, $insertData, $updatePiutang, $updatePenjualan);
        $penjualanBarangModel->update($data['penjualan_barang_id'], $updatePenjualan);

        // Redirect to view or another page after payment
        return redirect()->to('/Admin/bayarPiutang/' . $id_piutang);
    }

    public function dengankasbayarPiutang($id_piutang)
    {
        // Load models
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
        $jumlahUang = $data['jumlah_uang'];

        $sisaPiutang = $totalPenjualan - $jumlahUang;

        // Prepare data for pembayaran_piutang table
        $insertData = [
            'id_piutang' => $id_piutang,
            'tanggal_pembayaran' =>  date('Y-m-d H:i:s'), // Tanggal pembayaran saat ini
            'jumlah_pembayaran' => $sisaPiutang,
        ];

        // Insert data into pembayaran_piutang table
        $pembayaranPiutangModel->insert($insertData);

        // Update piutang data in piutang table
        $updatePiutang = [
            'jumlah_terbayar' => $data['total_penjualan'],
            'jumlah_piutang' => 0,
            'status_piutang' => 'lunas', // Update status to lunas if sisaPiutang <= 0
        ];

        $piutangModel->update($id_piutang, $updatePiutang);

        // Update status piutang in penjualan_barang table
        $updatePenjualan = [
            'status_piutang' => $updatePiutang['status_piutang'],
            'jumlah_uang' => $totalPenjualan,
        ];
        // dd($data, $insertData, $updatePiutang, $updatePenjualan, $sisaPiutang);
        $penjualanBarangModel->update($data['penjualan_barang_id'], $updatePenjualan);

        $latest_kas = $KasModel->orderBy('id_kas', 'DESC')->first();
        $saldo_terakhir = $latest_kas ? $latest_kas['saldo_terakhir'] : 0;

        $data_pembayaran = [
            'tanggal' =>  date('Y-m-d H:i:s'),
            'jenis_transaksi' => 'penerimaan', // Tipe transaksi, misalnya penerimaan
            'keterangan' => 'Pembayaran piutang', // Keterangan transaksi
            'jumlah_awal' => $saldo_terakhir,
            'jumlah_akhir' => $saldo_terakhir + $sisaPiutang, // Saldo terakhir setelah ditambah jumlah pembayaran
            'saldo_terakhir' => $saldo_terakhir + $sisaPiutang, // Saldo terakhir baru
        ];

        // Simpan data pembayaran ke dalam tabel kas
        dd($data, $insertData, $updatePiutang, $updatePenjualan, $sisaPiutang, $data_pembayaran);

        $KasModel->insert($data_pembayaran);

        // Redirect to view or another page after payment
        return redirect()->to('/PenjualanBarangCont/piutang');
    }

    public function bayarPiutang($id_piutang)
    {
        // Load models
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
            'tanggal_pembayaran' =>  date('Y-m-d H:i:s'), // Tanggal pembayaran saat ini
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
            'tanggal' =>  date('Y-m-d H:i:s'),
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
        return redirect()->to('/PenjualanBarangCont/piutang');
    }


    // PAKET
    public function paket()
    {
        $data = [
            'title' => 'Paket Wifi',
            'paket' => $this->paketModel->findAll(),
        ];
        return view('Admin/Paket/Index', $data);
    }

    public function savePaket()
    {
        // Validasi input
        if (!$this->validate([
            'nama_paket' => [
                'rules' => 'required|is_unique[paket.nama_paket]',
                'errors' => [
                    'required' => 'Nama paket harus diisi.',
                    'is_unique' => 'Nama paket sudah ada.',
                ],
            ],
            'harga' => [
                'rules' => 'required|numeric',
                'errors' => [
                    'required' => 'Harga harus diisi.',
                    'numeric' => 'Harga harus berupa angka.',
                ],
            ],
        ])) {
            return redirect()->to('/admin/paket')->withInput();
        }

        // Generate kode paket unik
        do {
            $prefix = 'PKG'; // Awalan kode
            $randomNumber = str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT); // 4 digit angka acak
            $kodePaket = $prefix . $randomNumber;
            // Cek apakah kode sudah ada di database
            $exists = $this->paketModel->where('kode_paket', $kodePaket)->first();
        } while ($exists);  // Ulangi jika kode sudah ada

        // Data yang akan disimpan
        $data = [
            'kode_paket' => $kodePaket,
            'nama_paket' => $this->request->getPost('nama_paket') . ' MBPS',
            'harga' => $this->request->getPost('harga'),
        ];

        // Simpan ke database
        if ($this->paketModel->insert($data)) {
            session()->setFlashdata('pesan', 'Data berhasil ditambahkan.');
        } else {
            // Tampilkan error jika gagal insert
            session()->setFlashdata('pesan', 'Gagal menyimpan data. Silakan coba lagi.');
        }

        // Redirect kembali ke halaman paket
        return redirect()->to('/Admin/paket');
    }
    public function updatePaket($kodePaket)
    {
        // Validasi input
        if (!$this->validate([
            'nama_paket' => [
                'rules' => 'required|is_unique[paket.nama_paket, id,{id}]', // Pastikan untuk menghindari validasi ganda pada nama yang sama
                'errors' => [
                    'required' => 'Nama paket harus diisi.',
                    'is_unique' => 'Nama paket sudah ada.',
                ],
            ],
            'harga' => [
                'rules' => 'required|numeric',
                'errors' => [
                    'required' => 'Harga harus diisi.',
                    'numeric' => 'Harga harus berupa angka.',
                ],
            ],
        ])) {
            return redirect()->to('/admin/paket/edit/' . $kodePaket)->withInput();
        }

        // Cek apakah paket dengan kode tertentu ada
        $paket = $this->paketModel->find($kodePaket);

        if ($paket) {
            // Data yang akan diperbarui
            $data = [
                'nama_paket' => $this->request->getPost('nama_paket'),
                'harga' => $this->request->getPost('harga'),
            ];

            // Lakukan update
            if ($this->paketModel->update($kodePaket, $data)) {
                session()->setFlashdata('pesan', 'Data berhasil diperbarui.');
            } else {
                session()->setFlashdata('pesan', 'Gagal memperbarui data. Silakan coba lagi.');
            }
        } else {
            // Jika paket tidak ditemukan
            session()->setFlashdata('pesan', 'Paket tidak ditemukan.');
        }

        // Redirect kembali ke halaman paket
        return redirect()->to('/Admin/paket');
    }

    public function deletePaket($kodePaket)
    {
        // Cek apakah paket dengan kode tertentu ada
        $paket = $this->paketModel->find($kodePaket);

        if ($paket) {
            // Jika paket ditemukan, hapus data
            if ($this->paketModel->delete($kodePaket)) {
                session()->setFlashdata('pesan', 'Data berhasil dihapus.');
            } else {
                session()->setFlashdata('pesan', 'Gagal menghapus data. Silakan coba lagi.');
            }
        } else {
            // Jika paket tidak ditemukan
            session()->setFlashdata('pesan', 'Paket tidak ditemukan.');
        }

        // Redirect kembali ke halaman paket
        return redirect()->to('/Admin/paket');
    }

    //pelanggan 
    public function tambah_pelanggan()
    {
        $data = [
            'title' => 'Tambah Pelanggan',
            'barangList' => $this->paketModel
                ->select('paket.*')
                ->findAll(),
            'selectedBarang' => null,
            'validation' => $this->validation,
        ];
        $kode_paket = $this->request->getPost('kode_paket');
        if ($kode_paket) {
            $selectedBarang = $this->paketModel->find($kode_paket);
            if ($selectedBarang) {
                $data['selectedBarang'] = $selectedBarang;
            }
        }

        return view('Admin/Pelanggan/Tambah_pelanggan', $data);
    }

    public function simpanPelanggan()
    {
        // Validasi input form
        if (!$this->validate([
            'nama' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Nama pelanggan harus diisi',
                ],
            ],
            'no_hp' => [
                'rules' => 'required|numeric',
                'errors' => [
                    'required' => 'Kontak pelanggan harus diisi',
                    'numeric' => 'Kontak pelanggan harus berupa angka',
                ],
            ],
            'alamat' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Alamat pelanggan harus diisi',
                ],
            ],
            'nik' => [
                'rules' => 'required|numeric',
                'errors' => [
                    'required' => 'NIK pelanggan harus diisi',
                    'numeric' => 'NIK pelanggan harus berupa angka',
                ],
            ],
            'foto_ktp' => [
                'rules' => 'uploaded[foto_ktp]|is_image[foto_ktp]|mime_in[foto_ktp,image/jpg,image/jpeg,image/png]',
                'errors' => [
                    'uploaded' => 'Foto KTP harus diupload',
                    'is_image' => 'File yang diupload harus berupa gambar',
                    'mime_in' => 'File yang diupload harus berupa gambar dengan format JPG, JPEG, atau PNG',
                ],
            ],
            'kode_paket' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Kode paket harus diisi',
                ],
            ],
            'tgl_pasang' => [
                'rules' => 'required|valid_date',
                'errors' => [
                    'required' => 'Tanggal pasang harus diisi',
                    'valid_date' => 'Tanggal pasang tidak valid',
                ],
            ],
            'status_pelanggan' => [
                'rules' => 'required|in_list[aktif,tidak aktif]',
                'errors' => [
                    'required' => 'Status pelanggan harus diisi',
                    'in_list' => 'Status pelanggan tidak valid',
                ],
            ],
        ])) {
            // Jika validasi gagal, kembalikan ke form dengan input yang sudah diisi sebelumnya
            return redirect()->to('/admin/tambah_pelanggan')->withInput();
        }

        // Mengambil data dari form
        $fotoKTP = $this->request->getFile('foto_ktp');
        $namaFile = $fotoKTP->getRandomName(); // Membuat nama file acak untuk foto KTP

        // Pindahkan file foto KTP ke folder yang ditentukan
        $fotoKTP->move('uploads/foto_ktp', $namaFile);


        $kodePaket = $this->request->getPost('kode_paket');
        $tglPasang = $this->request->getPost('tgl_pasang');
        $statusPelanggan = $this->request->getPost('status_pelanggan');

        // Ambil harga dari kode paket
        $paket = $this->paketModel->where('kode_paket', $kodePaket)->first();

        if (!$paket) {
            // Jika kode paket tidak ditemukan
            session()->setFlashdata('pesan', 'Kode paket tidak valid.');
            return redirect()->to('/admin/tambah_pelanggan')->withInput();
        }

        $hargaPaket = $paket['harga']; // Ambil harga paket
        $data = [
            'nama' => $this->request->getPost('nama'),
            'no_hp' => $this->request->getPost('no_hp'),
            'alamat' => $this->request->getPost('alamat'),
            'nik' => $this->request->getPost('nik'),
            'foto_ktp' => $namaFile,
            'tgl_pasang' => $tglPasang,
            'status_pelanggan' => $statusPelanggan,
            'kode_paket' => $kodePaket,
        ];

        // Simpan data ke database
        // Simpan data pelanggan ke database
        if ($this->pelangganWifiModel->insert($data)) {
            $pelangganId = $this->pelangganWifiModel->getInsertID(); // Dapatkan ID pelanggan yang baru ditambahkan

            // Jika status pelanggan aktif, tambahkan data ke tabel tagihan
            if ($statusPelanggan === 'aktif') {
                $tanggalTagihan = date('Y-m-d', strtotime($tglPasang . ' +30 days'));
                $dataTagihan = [
                    'pelanggan_id' => $pelangganId,
                    'kode_paket' => $kodePaket,
                    'tanggal_tagihan' => $tanggalTagihan,
                    'jumlah_tagihan' => $hargaPaket,
                    'status_tagihan' => 'Belum Dibayar',
                ];

                // dd($dataTagihan);    
                $this->tagihanModel->insert($dataTagihan);
            }

            session()->setFlashdata('pesan', 'Data pelanggan berhasil ditambahkan.');
        } else {
            session()->setFlashdata('pesan', 'Gagal menambahkan data pelanggan. Silakan coba lagi.');
        }

        // Redirect ke halaman daftar pelanggan
        return redirect()->to('/admin/pelanggan');
    }

    // public function tagihan()
    // {
    //     $tagihanModel = new tagihanModel();
    //     $query = $tagihanModel->builder()
    //         ->select('tagihan.*, pelanggan_wifi.nama, pelanggan_wifi.alamat, pelanggan_wifi.no_hp, pelanggan_wifi.nik, paket.nama_paket, paket.harga')
    //         ->join('pelanggan_wifi', 'tagihan.pelanggan_id = pelanggan_wifi.id', 'left')
    //         ->join('paket', 'tagihan.kode_paket = paket.kode_paket', 'left')
    //         ->get();

    //     // Mendapatkan hasil query dalam bentuk array

    //     $data = [
    //         'title' => 'Paket Wifi',
    //         'tagihan' => $query->getResultArray(),
    //     ];


    //     // dd($data['tagihan']);
    //     return view('Admin/Tagihan/Index', $data);
    // }
    public function cetakTagihan()
    {
        // Membuat instance model tagihan
        $tagihanModel = new tagihanModel();

        // Ambil data tagihan dan join dengan tabel pelanggan dan paket
        $query = $tagihanModel->builder()
            ->select('tagihan.*, pelanggan_wifi.nama, pelanggan_wifi.alamat, pelanggan_wifi.no_hp, pelanggan_wifi.nik, paket.nama_paket, paket.harga')
            ->join('pelanggan_wifi', 'tagihan.pelanggan_id = pelanggan_wifi.id', 'left')
            ->join('paket', 'tagihan.kode_paket = paket.kode_paket', 'left')
            ->get(); // Ambil semua tagihan untuk ditampilkan

        // Ambil hasil query
        $tagihanData = $query->getResultArray();

        // Siapkan data yang akan dikirim ke view untuk laporan
        $data = [
            'title'   => 'Laporan Tagihan',
            'tagihan' => $tagihanData, // Mengirim data tagihan ke view
        ];
        // dd($data);
        // Load view untuk laporan tagihan
        $html = view('Admin/Tagihan/Lap_tagihan', $data);

        // Membuat instance mPDF
        $mpdf = new \Mpdf\Mpdf();
        $mpdf->showImageErrors = true;

        // Menambahkan alias untuk nomor halaman
        $mpdf->AliasNbPages();

        // Mengatur header dan footer
        $mpdf->SetFooter('Halaman {PAGENO} dari {nbpg}');

        // Menambahkan halaman dan menulis HTML ke file PDF
        $mpdf->WriteHtml($html);

        // Mengirimkan file PDF ke browser
        $this->response->setHeader('Content-Type', 'application/pdf');
        $mpdf->Output('Laporan_Tagihan.pdf', 'I');
    }
    public function cetakTagihanById($id)
    {
        // Membuat instance model tagihan
        $tagihanModel = new tagihanModel();
    
        // Ambil data tagihan berdasarkan ID yang dipilih
        $query = $tagihanModel->builder()
            ->select('tagihan.*, pelanggan_wifi.nama, pelanggan_wifi.alamat, pelanggan_wifi.no_hp, pelanggan_wifi.nik, paket.nama_paket, paket.harga')
            ->join('pelanggan_wifi', 'tagihan.pelanggan_id = pelanggan_wifi.id', 'left')
            ->join('paket', 'tagihan.kode_paket = paket.kode_paket', 'left')
            ->where('tagihan.id', $id) // Menambahkan kondisi untuk ID tagihan
            ->get(); // Ambil tagihan berdasarkan ID
    
        // Ambil hasil query
        $tagihanData = $query->getRowArray(); // Mengambil satu data berdasarkan ID
    
        if (!$tagihanData) {
            // Jika data tidak ditemukan, redirect dengan pesan error
            return redirect()->to('/admin/tagihan')->with('error', 'Tagihan tidak ditemukan.');
        }
    
        // Siapkan data untuk laporan
        $data = [
            'title'   => 'Nota Tagihan',
            'tagihan' => $tagihanData, // Mengirimkan data tagihan ke view
        ];
    
        // Load view untuk laporan tagihan
        $html = view('Admin/Tagihan/Nota_tagihan', $data);
    
        // Membuat instance mPDF dengan ukuran kertas khusus 10.5cm x 10.5cm
        $mpdf = new \Mpdf\Mpdf([
            'format' => [105, 105],  // Ukuran kertas 10.5 cm x 10.5 cm (dalam milimeter)
            'orientation' => 'P'      // Orientasi Portrait
        ]);
    
        $mpdf->showImageErrors = true;
    
        // Menambahkan alias untuk nomor halaman
        $mpdf->AliasNbPages();
    
        // Mengatur header dan footer
        $mpdf->SetFooter('Halaman {PAGENO} dari {nbpg}');
    
        // Menambahkan halaman dan menulis HTML ke file PDF
        $mpdf->WriteHtml($html);
    
        // Mengirimkan file PDF ke browser
        $this->response->setHeader('Content-Type', 'application/pdf');
        $mpdf->Output('Nota_Tagihan_' . $id . '.pdf', 'I');
    }
    
    
    public function tagihan()
    {
        // Membuat instance model tagihan
        $tagihanModel = new tagihanModel();

        // Ambil data tagihan dan join dengan tabel pelanggan dan paket
        $query = $tagihanModel->builder()
            ->select('tagihan.*, pelanggan_wifi.nama, pelanggan_wifi.alamat, pelanggan_wifi.no_hp, pelanggan_wifi.nik, paket.nama_paket, paket.harga')
            ->join('pelanggan_wifi', 'tagihan.pelanggan_id = pelanggan_wifi.id', 'left')
            ->join('paket', 'tagihan.kode_paket = paket.kode_paket', 'left')
            ->get(); // Ambil semua tagihan untuk ditampilkan

        // Debugging untuk melihat hasil query
        // dd($query->getResultArray());

        // Cek pelanggan dengan status "Dibayar" dan durasi lebih dari 30 hari
        $queryForNewTagihan = $tagihanModel->builder()
            ->select('tagihan.*, pelanggan_wifi.nama, pelanggan_wifi.alamat, pelanggan_wifi.no_hp, pelanggan_wifi.nik, paket.nama_paket, paket.harga')
            ->join('pelanggan_wifi', 'tagihan.pelanggan_id = pelanggan_wifi.id', 'left')
            ->join('paket', 'tagihan.kode_paket = paket.kode_paket', 'left')
            ->where('tagihan.status_tagihan', 'Dibayar')
            ->where('tagihan.tanggal_tagihan >=', date('Y-m-d', strtotime('-30 days'))) // Tanggal 30 hari kebelakang
            ->where('tagihan.tanggal_tagihan <=', date('Y-m-d')) // Hari ini
            ->get();


        $pelanggan = $queryForNewTagihan->getResultArray();

        // Periksa apakah ada pelanggan yang perlu dibuatkan tagihan baru
        if (!empty($pelanggan)) {
            foreach ($pelanggan as $p) {
                // Cek apakah tagihan baru sudah ada berdasarkan pelanggan_id, kode_paket, dan status_tagihan
                $existingTagihan = $tagihanModel->where('pelanggan_id', $p['pelanggan_id'])
                    ->where('kode_paket', $p['kode_paket'])
                    ->where('status_tagihan', 'Belum Dibayar')
                    ->first(); // Mengambil data pertama yang ditemukan

                if (!$existingTagihan) {  // Jika tagihan belum ada
                    // Pastikan harga sudah ada dalam hasil query
                    $dataTagihanBaru = [
                        'pelanggan_id'    => $p['pelanggan_id'],
                        'kode_paket'      => $p['kode_paket'],
                        'tanggal_tagihan' => date('Y-m-d', strtotime('+30 days', strtotime($p['tanggal_tagihan']))),
                        'jumlah_tagihan'  => $p['harga'],  // Pastikan harga dari paket digunakan
                        'status_tagihan'  => 'Belum Dibayar',
                    ];

                    // Simpan tagihan baru
                    $tagihanModel->insert($dataTagihanBaru);
                }
            }
        }

        // Ambil data tagihan terbaru setelah insert tagihan baru
        $tagihanData = $query->getResultArray();

        // Kirimkan data ke view
        $data = [
            'title'   => 'Paket Wifi',
            'tagihan' => $tagihanData, // Pastikan variabelnya konsisten
        ];
// dd($data);
        // Tampilkan halaman dengan data tagihan
        return view('Admin/Tagihan/Index', $data);
    }

    public function tagihanbelumbayar()
    {
        // Membuat instance model tagihan
        $tagihanModel = new tagihanModel();

        // Ambil data tagihan dengan status "Belum Dibayar" dan join dengan tabel pelanggan dan paket
        $query = $tagihanModel->builder()
            ->select('tagihan.*, pelanggan_wifi.nama, pelanggan_wifi.alamat, pelanggan_wifi.no_hp, pelanggan_wifi.nik, paket.nama_paket, paket.harga')
            ->join('pelanggan_wifi', 'tagihan.pelanggan_id = pelanggan_wifi.id', 'left')
            ->join('paket', 'tagihan.kode_paket = paket.kode_paket', 'left')
            ->where('tagihan.status_tagihan', 'Belum Dibayar') // Tambahkan kondisi status "Belum Dibayar"
            ->get(); // Ambil semua tagihan dengan status "Belum Dibayar"

        // Ambil data tagihan terbaru
        $tagihanData = $query->getResultArray();

        // Kirimkan data ke view
        $data = [
            'title'   => 'Paket Wifi',
            'tagihan' => $tagihanData,
        ];

        // Tampilkan halaman dengan data tagihan
        return view('Admin/Tagihan/Index', $data);
    }
    public function tagihandibayar()
    {
        // Membuat instance model tagihan
        $tagihanModel = new tagihanModel();

        // Ambil data tagihan dengan status "Belum Dibayar" dan join dengan tabel pelanggan dan paket
        $query = $tagihanModel->builder()
            ->select('tagihan.*, pelanggan_wifi.nama, pelanggan_wifi.alamat, pelanggan_wifi.no_hp, pelanggan_wifi.nik, paket.nama_paket, paket.harga')
            ->join('pelanggan_wifi', 'tagihan.pelanggan_id = pelanggan_wifi.id', 'left')
            ->join('paket', 'tagihan.kode_paket = paket.kode_paket', 'left')
            ->where('tagihan.status_tagihan', 'Dibayar') // Tambahkan kondisi status "Belum Dibayar"
            ->get(); // Ambil semua tagihan dengan status "Belum Dibayar"

        // Ambil data tagihan terbaru
        $tagihanData = $query->getResultArray();

        // Kirimkan data ke view
        $data = [
            'title'   => 'Paket Wifi',
            'tagihan' => $tagihanData,
        ];

        // Tampilkan halaman dengan data tagihan
        return view('Admin/Tagihan/Index', $data);
    }
}
