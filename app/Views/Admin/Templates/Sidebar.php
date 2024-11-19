
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar Brand -->
 
    <!-- Sidebar Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center"
        href="<?= base_url(); ?>">
        <div class="sidebar-brand-icon">
            <i class="fas fa-user-cog"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Admin Panel</div>
    </a>


    <!-- Dashboard -->
    <li class="nav-item">
        <a class="nav-link" href="<?= base_url(); ?>">
            <i class="fas fa-home"></i>
            <span style="font-size: 16px;">Dashboard</span>
        </a>
    </li>

     <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#master" aria-expanded="true"
            aria-controls="master">
            <i class="fas fa-cash-register"></i>
            <span>Master</span>
        </a>
        <!-- <div id="master" class="collapse" aria-labelledby="headingTransaksi" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" style="font-size: 16px;"
                    href="<?= base_url('Admin/kelola_user'); ?>">Manjemen user</a>
                    <a class="collapse-item" style="font-size: 16px;"
                    href="<?= base_url('BarangCont'); ?>">Daftar
                    Barang</a>
                <a class="collapse-item" style="font-size: 16px;"
                    href="<?= base_url('Admin/satuan'); ?>">Master
                    Satuan</a>
                    <a class="collapse-item" style="font-size: 16px;"
                    href="<?= base_url('Admin/pelanggan'); ?>">Data
                    Pelanggan</a>
                <a class="collapse-item" style="font-size: 16px;"
                    href="<?= base_url('Admin/supplier'); ?>">Data
                    Supplier</a>
               
            </div>
        </div> -->
        <div id="master" class="collapse" aria-labelledby="headingTransaksi" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <!-- <a class="collapse-item" style="font-size: 16px;"
                    href="<?= base_url('Admin/kelola_user'); ?>">Manjemen user</a>
                    <a class="collapse-item" style="font-size: 16px;"
                    href="<?= base_url('BarangCont'); ?>">Daftar
                    Barang</a> -->
                <a class="collapse-item" style="font-size: 16px;"
                    href="<?= base_url('Admin/paket'); ?>">Master
                    Paket</a>
                    <a class="collapse-item" style="font-size: 16px;"
                    href="<?= base_url('Admin/pelanggan'); ?>">Data
                    Pelanggan</a>
                <a class="collapse-item" style="font-size: 16px;"
                    href="<?= base_url('Admin/tagihan'); ?>">Data
                    Tagihan</a>
               
            </div>
        </div>
    </li>
    <!-- Keuangan -->
    <!-- <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#keuanganMenu" aria-expanded="true"
            aria-controls="keuanganMenu">
            <i class="fas fa-money-check"></i>
            <span style="font-size: 16px;">Keuangan</span>
        </a>
        <div id="keuanganMenu" class="collapse" aria-labelledby="headingKeuangan" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" style="font-size: 16px;"
                    href="<?= base_url('Admin/aset'); ?>">Aset
                    Penjualan</a>
                <a class="collapse-item" style="font-size: 16px;"
                    href="<?= base_url('Admin/hutang'); ?>">Hutang
                </a>
                <a class="collapse-item" style="font-size: 16px;"
                    href="<?= base_url('PenjualanBarangCont/piutang'); ?>">Piutang
                </a>
                <a class="collapse-item" style="font-size: 16px;"
                    href="<?= base_url('Admin/modal'); ?>">Modal
                </a>
                <a class="collapse-item" style="font-size: 16px;"
                    href="<?= base_url('Admin/kas'); ?>">Kas
                    Toko
                </a>

            </div>
        </div>
    </li>


   
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#transaksiMenu" aria-expanded="true"
            aria-controls="transaksiMenu">
            <i class="fas fa-cash-register"></i>
            <span>Transaksi</span>
        </a>
        <div id="transaksiMenu" class="collapse" aria-labelledby="headingTransaksi" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
               
                <a class="collapse-item" style="font-size: 16px;"
                    href="<?= base_url('PenjualanBarangCont'); ?>">Penjualan
                    Barang</a>
                <a class="collapse-item" style="font-size: 16px;"
                    href="<?= base_url('Admin/pengeluaran'); ?>">Pengeluaran</a>
                <a class="collapse-item" style="font-size: 16px;"
                    href="<?= base_url('Admin/restok'); ?>">Restok</a>
            </div>
        </div>
    </li> -->

    <!-- Perkiraan Penjualan -->
    <!-- <li class="nav-item">
        <a class="nav-link"
            href="<?= base_url('Admin/perkiraan'); ?>">
            <i class="fas fa-chart-line"></i>
            <span>Perkiraan Penjualan</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#laporanMenu" aria-expanded="true"
            aria-controls="laporanMenu">
            <i class="fas fa-file-alt"></i>
            <span style="font-size: 16px;">Laporan</span>
        </a>
        <div id="laporanMenu" class="collapse" aria-labelledby="headingLaporan" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" style="font-size: 16px;"
                    href="<?= base_url('Admin/lap_barang'); ?>">Laporan
                    Persediaan</a>
                <a class="collapse-item" style="font-size: 16px;"
                    href="<?= base_url('Admin/lap_arus_kas'); ?>">Laporan
                    Arus Kas</a>
                <a class="collapse-item" style="font-size: 16px;"
                    href="<?= base_url('Admin/lap_laba_rugi'); ?>">Laporan
                    Laba Rugi</a>
                <a class="collapse-item" style="font-size: 16px;"
                    href="<?= base_url('Admin/lap_analisa_arus_kas'); ?>">Laporan
                    Analisa Arus Kas</a>
            </div>
        </div>
    </li> -->

    <!-- Logout -->
    <li class="nav-item">
        <a class="nav-link" 
            href="<?= base_url('logout'); ?>">
            <i class="fas fa-sign-out-alt"></i>
            <span style="font-size: 16px;">Logout</span>
        </a>
    </li>

    <!-- Sidebar Toggle Button -->
    <hr class="sidebar-divider">
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>