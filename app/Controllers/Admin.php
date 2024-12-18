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
use App\Models\paketModel;
use App\Models\PelangganModel;
use App\Models\pelangganWifiModel;
use App\Models\PemasukanModel;
use App\Models\pembayaranPiutangModel;
use App\Models\pengecekanModel;
use App\Models\PengeluaranModel;
use App\Models\PenjualanBarangModel;
use App\Models\perkiraanModel;
use App\Models\piutangModel;
use App\Models\profil;
use App\Models\restokModel;
use App\Models\riwayatSaldo;
use App\Models\SaldoModel;
use App\Models\satuanModel;
use App\Models\supplierModel;
use App\Models\tagihanModel;
use App\Models\tipeBarangModel;
use App\Models\TransaksiBarangModel;
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
    protected $profil;
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
        $this->profil = new profil();
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
    //         'title' => 'VIP NET - Home',
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

        $waktu24JamYangLalu = date('Y-m-d H:i:s', strtotime('-24 hours'));

        // $totalKasMasuk = $this->db->table('kas_toko')
        // ->selectSum('jumlah_akhir')
        // ->where('jenis_transaksi', 'penerimaan')
        // ->get()
        // ->getRow()->jumlah_akhir;
        // Kas Masuk
        $totalKasMasuk = $this->db->table('kas_toko')
            ->select('SUM(jumlah_akhir) - SUM(jumlah_awal) AS total_masuk', false)
            ->where('jenis_transaksi', 'penerimaan')
            ->get()
            ->getRow()->total_masuk;

        // Kas Keluar (Menggunakan ABS untuk memastikan hasilnya selalu positif)
        $totalKasKeluar = $this->db->table('kas_toko')
            ->select('SUM(ABS(jumlah_awal - jumlah_akhir)) AS total_keluar', false)
            ->where('jenis_transaksi', 'pengeluaran')
            ->get()
            ->getRow()->total_keluar;

        //         $totalKasKeluar = $this->db->query("
        //     SELECT SUM(jumlah_awal - jumlah_akhir) AS total_keluar
        //     FROM kas_toko
        //     WHERE jenis_transaksi = 'pengeluaran'
        // ")->getRow()->total_keluar;

        $pelangganWifiModel = new pelangganWifiModel();

        // Hitung pelanggan aktif
        $totalPelangganAktif = $pelangganWifiModel->countPelangganAktif();

        // Hitung pelanggan tidak aktif
        $totalPelangganTidakAktif = $pelangganWifiModel->countPelangganTidakAktif();

        // Hitung pelanggan berdasarkan status
        $totalByStatus = $pelangganWifiModel->countPelangganByStatus();

        $data = [
            'title' => 'VIP NET - Home',
            'saldo_terakhir' => $saldoTerakhir,
            'totalKasMasuk' => $totalKasMasuk,
            'totalKasKeluar' => $totalKasKeluar,

            'totalPelangganAktif' => $totalPelangganAktif,
            'totalPelangganTidakAktif' => $totalPelangganTidakAktif,
            'totalByStatus' => $totalByStatus,
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
        $data['title'] = 'VIP NET - Detail Pengguna';

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
            'title' => 'Profil - VIP NET',
            'role' => $role_echo,

        ];

        return view('Admin/Home/Profil', $data);
    }

    public function simpanProfile($id)
    {
        $userlogin = user()->username;
        $builder = $this->db->table('users');
        $builder->select('*');
        $query = $builder->where('username', $userlogin)->get()->getRowArray();

        $foto = $this->request->getFile('foto');
        if ($foto->getError() == 4) {
            // Update only email and username if no new file is uploaded
            $this->profil->update($id, [
                'email' => $this->request->getPost('email'),
                'username' => $this->request->getPost('username'),
                'fullname' => $this->request->getPost('fullname'),
            ]);
        } else {
            // Define the new photo name
            $nama_foto = 'UserFoto_' . $this->request->getPost('username') . '.' . $foto->guessExtension();

            // Check if the current photo is not the default profile picture before deleting
            if (!empty($query['foto']) && $query['foto'] != 'profil.svg') {
                unlink('uploads/profile/' . $query['foto']);
            }

            // Move the new photo to the uploads directory
            $foto->move('uploads/profile', $nama_foto);

            // Update the profile with the new data, including the new photo
            $this->profil->update($id, [
                'email' => $this->request->getPost('email'),
                'username' => $this->request->getPost('username'),
                'fullname' => $this->request->getPost('fullname'),
                'foto' => $nama_foto,
            ]);
        }

        session()->setFlashdata('msg', 'Profil Pengguna berhasil Diubah');
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

    // pelanggan
    public function pelanggan()
    {
        $data = [
            'title' => 'Daftar Nama Pelanggan',

            'pelanggan' => $this->pelangganWifiModel

                ->select('pelanggan_wifi.*, paket.nama_paket, paket.harga') // Selecting fields you want
                ->join('paket', 'paket.kode_paket = pelanggan_wifi.kode_paket', 'left') // Perform LEFT JOIN
                ->findAll(),
        ];
        // dd($data);
        return view('Admin/Pelanggan/Index', $data);
    }

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

   
    public function deletePelanggan($id)
    {
        // 1. Ambil data pelanggan berdasarkan ID
        $pelangganModel = new pelangganWifiModel();
        $pelanggan = $pelangganModel->find($id);
    
        if ($pelanggan) {
            // 2. Hapus foto KTP jika ada
            if ($pelanggan['foto_ktp'] && file_exists('uploads/foto_ktp/' . $pelanggan['foto_ktp'])) {
                unlink('uploads/foto_ktp/' . $pelanggan['foto_ktp']);
            }
    
            // 3. Hapus data pelanggan
            $pelangganModel->delete($id);
    
            // 4. Redirect atau tampilkan pesan sukses
            return redirect()->to('/admin/pelanggan')->with('success', 'Pelanggan berhasil dihapus');
        } else {
            // Jika pelanggan tidak ditemukan
            return redirect()->to('/admin/pelanggan')->with('error', 'Pelanggan tidak ditemukan');
        }
    }
    
    // last pelanggan


    public function pemasukana()
    {
        $model = new KasModel();
        $data['kas'] = $model->findAll(); // Mengambil semua data pengeluaran dari tabel pengeluaran

        $saldoModel = new SaldoModel();
        $data['kas'] = $saldoModel->orderBy('id', 'DESC')->first();

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
        $latest_kas = $kasModel->orderBy('id_kas', 'DESC')->first();
        $saldo_terakhir = $latest_kas ? $latest_kas['saldo_terakhir'] : 0;
       
        // Memeriksa apakah jumlah pengeluaran melebihi saldo yang tersedia
        $jumlah_pengeluaran = $this->request->getPost('jumlah');
        if ($lastBalance < $jumlah_pengeluaran) {
            return redirect()->back()->withInput()->with('errors', ['Jumlah pengeluaran melebihi saldo yang tersedia']);
        }

        $pengeluaranData = [
            'tanggal' => date('Y-m-d H:i:s'),
            'keterangan' => $this->request->getPost('keterangan'),
            'jumlah' => $jumlah_pengeluaran,
        ];
        // dd();
        $pengeluaranModel->insert($pengeluaranData);

        // Mengupdate saldo terakhir
        $kasData = [
            'tanggal' => date('Y-m-d H:i:s'),
            'jenis_transaksi' => 'pengeluaran',
            'keterangan' => $this->request->getPost('keterangan'),
            'jumlah_awal' => $saldo_terakhir,
            'jumlah_akhir' =>  $saldo_terakhir -$jumlah_pengeluaran ,
            'saldo_terakhir' =>  $saldo_terakhir -$jumlah_pengeluaran ,
        ];
        // dd($latest_kas,$pengeluaranData,$kasData);
        $kasModel->insert($kasData);

        return redirect()->to('/Admin/pengeluaran')->with('pesanBerhasil', 'Pengeluaran berhasil ditambahkan');
    }

 

    public function lap_permintaan_barang()
    {
        $data = [
            // 'user'=> $query->getResult(),
            'title' => 'VIP NET - Laporan',

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

  
    //Laporan

    public function lap_permintaan()
    {
        $data = [
            // 'user'=> $query->getResult(),
            'title' => 'VIP NET - Laporan',

        ];

        return view('Admin/Laporan/Index', $data);
    }

    public function lap_masuk()
    {
        $data = [
            // 'user'=> $query->getResult(),
            'title' => 'VIP NET - Laporan',

        ];

        return view('Admin/Laporan/Home_transaksimasuk', $data);
    }
    public function lap_keluar()
    {
        $data = [
            // 'user'=> $query->getResult(),
            'title' => 'VIP NET - Laporan',

        ];

        return view('Admin/Laporan/Home_transaksikeluar', $data);
    }

    //laporan inventaris

    //Laporan Barang
    public function lap_barang()
    {
        $data = [
            'title' => 'VIP NET - Laporan Barang',
        ];

        return view('Admin/Laporan/Home_barang', $data);
    }
    public function lap_arus_kas()
    {
        $data = [
            'title' => 'VIP NET - Laporan Arus Kas',
        ];

        return view('Admin/Laporan/Home_arus', $data);
    }
    public function lap_analisa_arus_kas()
    {
        $data = [
            'title' => 'VIP NET - Laporan Analisa arus kas',
        ];

        return view('Admin/Laporan/Home_analisa', $data);
    }
    public function lap_laba_rugi()
    {
        $data = [
            'title' => 'VIP NET - Laporan Laba rugi',
        ];

        return view('Admin/Laporan/Home_laba', $data);
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

        // Query untuk mendapatkan total harga beli barang yang terjual dari model detailPenjualanBarangModel dan BarangModel

        $db = \Config\Database::connect();
        $kasModel = new KasModel();
        $pengeluaranModel = new PengeluaranModel();

        // Total pemasukan
        // $totalPemasukan = $kasModel
        //     ->selectSum('jumlah_awal') // Sesuaikan kolom pemasukan
        //     ->where('jenis_transaksi', 'penerimaan')
        //     ->where('tanggal >=', $tanggalMulai)
        //     ->where('tanggal <=', $tanggalAkhir)
        //     ->first()['jumlah_awal'] ?? 0;

        // $totalPembayaranWifi = $kasModel
        //     ->select('SUM(jumlah_akhir) - SUM(jumlah_awal) AS selisih_pembayaran_wifi') // Menghitung selisih
        //     ->where('jenis_transaksi', 'penerimaan')
        //     ->where('tanggal >=', $tanggalMulai)
        //     ->where('tanggal <=', $tanggalAkhir)
        //     ->like('keterangan', 'Pembayaran Wifi') // Menggunakan LIKE untuk mencocokkan kata-kata dalam keterangan
        //     ->first()['selisih_pembayaran_wifi'] ?? 0;
        $totalPemasukan = $kasModel
            ->select('SUM(jumlah_akhir - jumlah_awal) AS total_pemasukan') // Correct way to sum the difference
            ->where('jenis_transaksi', 'penerimaan') // Filter by 'penerimaan' transaction type
            ->where('tanggal >=', $tanggalMulai) // Filter by start date
            ->where('tanggal <=', $tanggalAkhir) // Filter by end date
            ->groupStart() // Start OR group
            ->like('keterangan', 'Pembayaran Wifi') // Match keterangan with 'Pembayaran Wifi'
            ->orWhere('keterangan', 'Biaya Pasang') // Match keterangan with 'Biaya Pasang'
            ->orWhere('keterangan', 'pengembalian')
            ->orWhere('keterangan', '') // Pencocokan dengan keterangan kosong
            ->orWhere('keterangan IS NULL') // Pencocokan dengan keterangan NULL
            ->orWhere('keterangan !=', '') // Pencocokan dengan keterangan yang tidak kosong
            ->orWhere('keterangan IS NOT NULL') // Match keterangan with 'Biaya Pasang'
            ->groupEnd()
            ->first()['total_pemasukan'] ?? 0; // Fetch the result or default to 0 if no result
        // Ambil hasil pertama, atau 0 jika tidak ada hasil

        // Menghitung Total Pengeluaran
        $bayarTeknisi = $pengeluaranModel
            ->selectSum('jumlah', 'total_bayar_teknisi') // Menjumlahkan kolom jumlah untuk 'bayar teknisi'
            ->where('keterangan', 'bayar teknisi')
            ->where('tanggal >=', $tanggalMulai)
            ->where('tanggal <=', $tanggalAkhir)
            ->first()['total_bayar_teknisi'] ?? 0;

        $listrik = $pengeluaranModel
            ->selectSum('jumlah', 'total_listrik') // Menjumlahkan kolom jumlah untuk 'listrik'
            ->where('keterangan', 'listrik')
            ->where('tanggal >=', $tanggalMulai)
            ->where('tanggal <=', $tanggalAkhir)
            ->first()['total_listrik'] ?? 0;

        $air = $pengeluaranModel
            ->selectSum('jumlah', 'total_air') // Menjumlahkan kolom jumlah untuk 'air'
            ->where('keterangan', 'air')
            ->where('tanggal >=', $tanggalMulai)
            ->where('tanggal <=', $tanggalAkhir)
            ->first()['total_air'] ?? 0;

        $lainnya = $pengeluaranModel
            ->selectSum('jumlah', 'total_lainnya') // Menjumlahkan kolom jumlah untuk 'lainnya'
            ->where('keterangan', 'lainnya')
            ->where('tanggal >=', $tanggalMulai)
            ->where('tanggal <=', $tanggalAkhir)
            ->first()['total_lainnya'] ?? 0;

        // Total Pengeluaran
        $totalPengeluaran = $bayarTeknisi + $listrik + $air + $lainnya;

        // Menghitung Laba Kotor dan Laba Bersih
        $labaKotor = $totalPemasukan - $totalPengeluaran;
        $labaBersih = $labaKotor;

        // Data untuk view

        $builder = $db->table('users');
        $builder->select('users.fullname');
        $builder->join('auth_groups_users', 'auth_groups_users.user_id = users.id');
        $builder->where('auth_groups_users.group_id', 3);
        $query = $builder->get();
        $pemilikData = $query->getRow();
        $pemilikName = $pemilikData ? $pemilikData->fullname : 'Nama Pemilik Tidak Ditemukan';

        // Menyiapkan data untuk dikirim ke view
        $data = [
            'tanggalMulai' => $tanggalMulai,
            'tanggalAkhir' => $tanggalAkhir,
            'totalPemasukan' => $totalPemasukan, //
            'bayarTeknisi' => $bayarTeknisi, //
            'listrik' => $listrik, //
            'air' => $air, //
            'pemilikName' => $pemilikName, //
            'lainnya' => $lainnya, //
            'totalPengeluaran' => $totalPengeluaran, //
            'labaKotor' => $labaKotor, //
            'labaBersih' => $labaBersih, //
            // 'totalPembayaranWifi' => $totalPembayaranWifi,
        ];
        // dd($data);
        // Load view dan generate PDF
        $mpdf = new \Mpdf\Mpdf();
        $mpdf->showImageErrors = true;
        $mpdf->AliasNbPages();

        // Mengatur header dan footer
        $mpdf->SetFooter('Halaman {PAGENO} dari {nbpg}');

        // Load view dan pass data
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

        // Output PDF
        $this->response->setHeader('Content-Type', 'application/pdf');
        $mpdf->Output('Laporan Laba Rugi.pdf', 'I');
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
            'title' => 'VIP NET - Tambah Users',
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
            'tanggal' => date('Y-m-d H:i:s'),
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
            'tanggal' => date('Y-m-d H:i:s'),
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
    public function saldo()
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
            'title' => 'Data Pemasukan',
        ];
        // dd($data);
        return view('Admin/Kas/Index', $data);
    }
    public function pemasukan()
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
            'kas' => $this->KasModel
                ->where('jenis_transaksi', 'penerimaan')
                ->orderBy('id_kas', 'ASC')
                ->findAll(),

            'title' => 'Data Pemasukan',
        ];
        // dd($data);
        return view('Admin/Kas/Index', $data);
    }

    public function pengeluaran_saldo()
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
            'kas' => $this->KasModel
                ->where('jenis_transaksi', 'pengeluaran')
                ->orderBy('id_kas', 'ASC')
                ->findAll(),

            'title' => 'Data Pemasukan',
        ];
        // dd($data);
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
        $tanggal = $this->request->getPost('tanggal');
        if ($tanggal) {
            // Misalnya, $tanggal berasal dari input dalam format 'YYYY-MM-DD'
            // Kita bisa menggunakan DateTime untuk memastikan format yang benar
            $tanggalObj = new \DateTime($tanggal); // Membuat objek DateTime dari input
            $formattedTanggal = $tanggalObj->format('Y-m-d H:i:s'); // Format ke 'YYYY-MM-DD HH:MM:SS'
        } else {
            $formattedTanggal = null; // Jika tidak ada tanggal, set null
        }
        // Ambil data dari request
        $data = [
            'tanggal' => $formattedTanggal,
            'jenis_transaksi' => $this->request->getPost('jenis_transaksi'),
            'keterangan' => $this->request->getPost('keterangan'),
        ];

        // Tambahkan jumlah masuk atau jumlah keluar berdasarkan jenis transaksi
        // if ($data['jenis_transaksi'] === 'penerimaan') {
        //     $data['jumlah_awal'] = $saldo_terakhir;
        //     $data['jumlah_akhir'] = '+' . $this->request->getPost('jumlah_masuk');
        //     $data['jumlah_keluar'] = 0; // Atur jumlah keluar menjadi 0 untuk penerimaan
        // } elseif ($data['jenis_transaksi'] === 'pengeluaran') {
        //     $data['jumlah_awal'] = -$this->request->getPost('jumlah_keluar'); // Gunakan 'jumlah_keluar' untuk pengeluaran
        //     $data['jumlah_akhir'] = '-' . $this->request->getPost('jumlah_keluar'); // Gunakan 'jumlah_keluar' untuk pengeluaran
        //     $data['jumlah_masuk'] = 0; // Atur jumlah masuk menjadi 0 untuk pengeluaran
        // }

        // // Hitung saldo terakhir berdasarkan jumlah awal dan jumlah akhir
        // $data['saldo_terakhir'] = $this->hitungSaldoTerakhir($data['jumlah_akhir']);
        if ($data['jenis_transaksi'] === 'penerimaan') {
            $data['jumlah_awal'] = $saldo_terakhir;
            $jumlah_masuk = $this->request->getPost('jumlah_masuk');
            $data['jumlah_akhir'] = $saldo_terakhir + $jumlah_masuk; // Tambahkan jumlah masuk ke saldo terakhir
            $data['jumlah_keluar'] = 0; // Atur jumlah keluar menjadi 0 untuk penerimaan
        } elseif ($data['jenis_transaksi'] === 'pengeluaran') {
            $data['jumlah_awal'] = $saldo_terakhir;
            $jumlah_keluar = $this->request->getPost('jumlah_keluar');
            $data['jumlah_akhir'] = $saldo_terakhir - $jumlah_keluar; // Kurangi jumlah keluar dari saldo terakhir
            $data['jumlah_masuk'] = 0; // Atur jumlah masuk menjadi 0 untuk pengeluaran
        }

        // Hitung saldo terakhir berdasarkan jumlah akhir yang baru
        $data['saldo_terakhir'] = $data['jumlah_akhir']; // Saldo terakhir diupdate dengan jumlah_akhir

        // Insert data ke database
        // dd($data);
        $this->KasModel->insert($data);

        // Redirect ke halaman daftar kas dengan pesan sukses
        return redirect()->to('/Admin/saldo')->with('pesanBerhasil', 'Data kas berhasil ditambahkan.');
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
            'tanggal_pembayaran' => date('Y-m-d H:i:s'), // Tanggal pembayaran saat ini
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
            'tanggal_pembayaran' => date('Y-m-d H:i:s'), // Tanggal pembayaran saat ini
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
            'tanggal' => date('Y-m-d H:i:s'),
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
            'tanggal_pembayaran' => date('Y-m-d H:i:s'), // Tanggal pembayaran saat ini
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
            'tanggal' => date('Y-m-d H:i:s'),
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
        } while ($exists); // Ulangi jika kode sudah ada

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
    public function editPaket($kode_paket)
    {
        $data = [
            'title' => 'Ubah Paket',
            'validation' => $this->validation,
            'paket' => $this->paketModel->find($kode_paket),
        ];

        return view('Admin/Paket/Edit_paket', $data);
    }
    public function updatePaket()
    {
        $kode_paket = $this->request->getPost('kode_paket');

        // Validasi input
        if (!$this->validate([
            'nama_paket' => 'required',
            'harga' => 'required|numeric',
        ])) {
            return redirect()->to("/admin/paket/edit/$kode_paket")->withInput()->with('validation', $this->validator);
        }

        // Ambil data paket lama
        $paket = $this->paketModel->find($kode_paket);

        if (!$paket) {
            session()->setFlashdata('pesan', 'Data paket tidak ditemukan.');
            return redirect()->to('/admin/paket');
        }

        // Update data paket sesuai allowedFields
        $data = [
            'nama_paket' => $this->request->getPost('nama_paket'),
            'harga' => $this->request->getPost('harga'),
        ];

        if ($this->paketModel->update($kode_paket, $data)) {
            session()->setFlashdata('pesan', 'Data paket berhasil diubah.');
        } else {
            session()->setFlashdata('pesan', 'Gagal mengubah data paket.');
        }

        return redirect()->to('/admin/paket');
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
        $namaPelanggan = strtolower(str_replace(' ', '_', $this->request->getPost('nama'))); // Membuat nama file berdasarkan nama pelanggan
        $namaFile = $namaPelanggan . '_ktp.' . $fotoKTP->getExtension();

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
            'title' => 'Laporan Tagihan',
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
        $tagihanModel = new tagihanModel();

        $query = $tagihanModel->builder()
            ->select('tagihan.*, pelanggan_wifi.nama, pelanggan_wifi.alamat, pelanggan_wifi.no_hp, pelanggan_wifi.nik, paket.nama_paket, paket.harga')
            ->join('pelanggan_wifi', 'tagihan.pelanggan_id = pelanggan_wifi.id', 'left')
            ->join('paket', 'tagihan.kode_paket = paket.kode_paket', 'left')
            ->where('tagihan.id', $id)
            ->get();

        $tagihanData = $query->getRowArray();

        if (!$tagihanData) {
            return redirect()->to('/admin/tagihan')->with('error', 'Tagihan tidak ditemukan.');
        }

        $data = [
            'title' => 'Nota Tagihan',
            'tagihan' => $tagihanData,
        ];

        $html = view('Admin/Tagihan/Nota_tagihan', $data);

        // $mpdf = new \Mpdf\Mpdf([
        //     'format' => [105, 105],  // Ukuran kertas 10.5 cm x 10.5 cm
        //     'orientation' => 'P',
        //     'margin_top' => 2,  // Margin atas minimal
        //     'margin_bottom' => 2, // Margin bawah minimal
        //     'margin_left' => 2, // Margin kiri minimal
        //     'margin_right' => 2, // Margin kanan minimal
        // ]);
        $mpdf = new \Mpdf\Mpdf([
            'format' => [58, 50], // Ukuran kertas 58 mm x 50 mm
            'orientation' => 'P',
            'margin_top' => 1, // Margin atas minimal
            'margin_bottom' => 1, // Margin bawah minimal
            'margin_left' => 1, // Margin kiri minimal
            'margin_right' => 1, // Margin kanan minimal
        ]);
        $mpdf->showImageErrors = true;

        // Menulis HTML ke file PDF
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

                if (!$existingTagihan) { // Jika tagihan belum ada
                    // Pastikan harga sudah ada dalam hasil query
                    $dataTagihanBaru = [
                        'pelanggan_id' => $p['pelanggan_id'],
                        'kode_paket' => $p['kode_paket'],
                        'tanggal_tagihan' => date('Y-m-d', strtotime('+30 days', strtotime($p['tanggal_tagihan']))),
                        'jumlah_tagihan' => $p['harga'], // Pastikan harga dari paket digunakan
                        'status_tagihan' => 'Belum Dibayar',
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
            'title' => 'Paket Wifi',
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
            'title' => 'Paket Wifi',
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
            'title' => 'Paket Wifi',
            'tagihan' => $tagihanData,
        ];

        // Tampilkan halaman dengan data tagihan
        return view('Admin/Tagihan/Index', $data);
    }

   
    public function bayarTagihan($id)
    {
        // Load the models
        $tagihanModel = new TagihanModel();
        $kasTokoModel = new KasModel();
        $pelangganWifiModel = new pelangganWifiModel(); // Load pelangganWifiModel

        // Start a transaction to ensure that both updates happen together
        $this->db->transStart();

        // Fetch the tagihan data first
        $tagihan = $tagihanModel->find($id);

        if (!$tagihan) {
            // If no tagihan is found, show an error message
            return redirect()->to('/Admin/tagihan')->with('error', 'Tagihan tidak ditemukan');
        }

        // Fetch the pelanggan data based on pelangganid from the tagihan
        $pelanggan = $pelangganWifiModel->find($tagihan['pelanggan_id']); // Assuming pelangganid is available in tagihan table

        if (!$pelanggan) {
            // If no pelanggan is found, show an error message
            return redirect()->to('/Admin/tagihan')->with('error', 'Pelanggan tidak ditemukan');
        }

        // Now you can use $pelanggan['nama'] to get the customer's name
        $namaPelanggan = $pelanggan['nama'];

        // Fetch the latest 'kas_toko' record to get the saldo_terakhir
        $latest_kas = $kasTokoModel->orderBy('id_kas', 'DESC')->first();
        $saldo_terakhir = $latest_kas ? $latest_kas['saldo_terakhir'] : 0;

        // Prepare data for the 'kas_toko' insert
        $dataKas = [
            'tanggal' => date('Y-m-d'), // Current date
            'jenis_transaksi' => 'Penerimaan', // Transaction type
            'keterangan' => 'Pembayaran Wifi dengan ID Tagihan ' . $id . ' oleh ' . $namaPelanggan, // Add customer name to the description
            'jumlah_awal' => $saldo_terakhir, // Set the previous saldo_terakhir as jumlah_awal
            'jumlah_akhir' => $saldo_terakhir + $tagihan['jumlah_tagihan'], // Add the tagihan amount to saldo_terakhir
            'saldo_terakhir' => $saldo_terakhir + $tagihan['jumlah_tagihan'], // Update saldo_terakhir after payment
        ];

        // Update the status of the tagihan to "Di Bayar"
        $tagihanModel->update($id, ['status_tagihan' => 'DiBayar']);

        // Insert the payment record into kas_toko
        $kasTokoModel->save($dataKas);

        // Commit the transaction
        $this->db->transComplete();

        // Check if the transaction was successful
        if ($this->db->transStatus() === false) {
            return redirect()->to('/Admin/tagihan')->with('error', 'Gagal membayar tagihan');
        }

        // If everything is successful, redirect to the tagihan page with a success message
        return redirect()->to('/Admin/tagihan')->with('success', 'Tagihan berhasil dibayar');
    }
}
