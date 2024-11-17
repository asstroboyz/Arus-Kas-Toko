<style>
    .sidebar {
        background: linear-gradient(180deg, #705425, #705425);
        /* Gradasi warna dari #C89361 ke #705425 */
    }

    .sidebar {
        background-color: #705425;
        /* Warna amber dari gambar */
    }

    .sidebar .nav-item .nav-link {
        color: #ffffff;
        /* Warna teks putih untuk kontras lebih baik */
    }

    .sidebar .nav-item .nav-link:hover {
        background-color: #705425;
        /* Warna gradasi yang lebih gelap untuk efek hover */
        color: #ffffff;
        /* Warna teks putih saat hover */
    }

    .sidebar .sidebar-brand {
        color: #ffffff;
        /* Warna teks putih untuk sidebar brand */
    }
</style>
<ul class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar">
    <!-- Sidebar Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center"
        href="<?= base_url(); ?>">
        <img src="<?= base_url() ?>/assets/img/10.png" width="75px"
            height="75px">
        <div class="sidebar-brand-text mx-3">Kasir Panel</div>
    </a>

    <!-- Dashboard -->
    <li class="nav-item">
        <a class="nav-link" href="<?= base_url(); ?>">
            <i class="fas fa-home"></i>
            <span style="font-size: 16px;">Dashboard</span>
        </a>
    </li>


    <!-- Keuangan -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#MasterMenu" aria-expanded="true"
            aria-controls="MasterMenu">
            <i class="fas fa-money-check"></i>
            <span style="font-size: 16px;">Master</span>
        </a>
        <div id="MasterMenu" class="collapse" aria-labelledby="headingKeuangan" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
            <a class="collapse-item" style="font-size: 16px;"
                    href="<?= base_url('Kasir/barang'); ?>">Daftar
                    Barang</a>
                <a class="collapse-item" style="font-size: 16px;"
                    href="<?= base_url('Kasir/satuan'); ?>">
                    Satuan</a>
                
                    <a class="collapse-item" style="font-size: 16px;"
                    href="<?= base_url('Kasir/pelanggan'); ?>">Data
                    Pelanggan</a>
                <a class="collapse-item" style="font-size: 16px;"
                    href="<?= base_url('Kasir/supplier'); ?>">Data
                    Supplier</a>
            </div>
        </div>
    </li>
    <!-- Keuangan -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#keuanganMenu" aria-expanded="true"
            aria-controls="keuanganMenu">
            <i class="fas fa-money-check"></i>
            <span style="font-size: 16px;">Keuangan</span>
        </a>
        <div id="keuanganMenu" class="collapse" aria-labelledby="headingKeuangan" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" style="font-size: 16px;"
                    href="<?= base_url('Kasir/aset'); ?>">Aset
                    Penjualan</a>
                <a class="collapse-item" style="font-size: 16px;"
                    href="<?= base_url('Kasir/hutang'); ?>">Hutang
                </a>
                <a class="collapse-item" style="font-size: 16px;"
                    href="<?= base_url('Kasir/piutang'); ?>">Piutang
                </a>
                <a class="collapse-item" style="font-size: 16px;"
                    href="<?= base_url('Kasir/modal'); ?>">Modal
                </a>
                <a class="collapse-item" style="font-size: 16px;"
                    href="<?= base_url('Kasir/kas'); ?>">Kas
                    Toko
                </a>

            </div>
        </div>
    </li>


    <!-- Transaksi -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#transaksiMenu" aria-expanded="true"
            aria-controls="transaksiMenu">
            <i class="fas fa-cash-register"></i>
            <span>Transaksi</span>
        </a>
        <div id="transaksiMenu" class="collapse" aria-labelledby="headingTransaksi" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
              
                <a class="collapse-item" style="font-size: 16px;"
                    href="<?= base_url('Kasir/penjualanBarang'); ?>">Penjualan
                    Barang</a>
                <a class="collapse-item" style="font-size: 16px;"
                    href="<?= base_url('Kasir/pengeluaran'); ?>">Pengeluaran</a>
                <a class="collapse-item" style="font-size: 16px;"
                    href="<?= base_url('Kasir/restok'); ?>">Restok</a>
            </div>
        </div>
    </li>

    <!-- Perkiraan Penjualan -->
    <li class="nav-item">
        <a class="nav-link"
            href="<?= base_url('Kasir/perkiraan'); ?>">
            <i class="fas fa-chart-line"></i>
            <span>Perkiraan Penjualan</span>
        </a>
    </li>



    <!-- Pelanggan dan Supplier
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#pelangganMenu" aria-expanded="true"
            aria-controls="pelangganMenu">
            <i class="fas fa-users"></i>
            <span>Pelanggan dan Supplier</span>
        </a>
        <div id="pelangganMenu" class="collapse" aria-labelledby="headingPelanggan" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" style="font-size: 16px;"
                    href="<?= base_url('Kasir/pelanggan'); ?>">Data
                    Pelanggan</a>
                <a class="collapse-item" style="font-size: 16px;"
                    href="<?= base_url('Kasir/supplier'); ?>">Data
                    Supplier</a>
            </div>
        </div>
    </li> -->

    <!-- Laporan -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#laporanMenu" aria-expanded="true"
            aria-controls="laporanMenu">
            <i class="fas fa-file-alt"></i>
            <span>Laporan</span>
        </a>
        <div id="laporanMenu" class="collapse" aria-labelledby="headingLaporan" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" style="font-size: 16px;"
                    href="<?= base_url('Kasir/lap_barang'); ?>">Laporan
                    Persediaan</a>
                <a class="collapse-item" style="font-size: 16px;"
                    href="<?= base_url('Kasir/lap_arus_kas'); ?>">Laporan
                    Arus Kas</a>
                <a class="collapse-item" style="font-size: 16px;"
                    href="<?= base_url('Kasir/lap_laba_rugi'); ?>">Laporan
                    Laba Rugi</a>
                <a class="collapse-item" style="font-size: 16px;"
                    href="<?= base_url('Kasir/lap_analisa_arus_kas'); ?>">Laporan
                    Analisa Arus Kas</a>
            </div>
        </div>
    </li>

    <!-- Logout -->
    <li class="nav-item">
        <a class="nav-link" style="font-size: 16px;"
            href="<?= base_url('logout'); ?>">
            <i class="fas fa-sign-out-alt"></i>
            <span>Logout</span>
        </a>
    </li>

    <!-- Sidebar Toggle Button -->
    <hr class="sidebar-divider">
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>