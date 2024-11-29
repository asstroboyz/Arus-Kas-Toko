<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?=base_url();?>">
        <div class="sidebar-brand-icon">
            <i class="fas fa-user-cog"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Admin Panel</div>
    </a>

    <li class="nav-item">
        <a class="nav-link" href="<?=base_url();?>">
            <i class="fas fa-tachometer-alt"></i>
            <span style="font-size: 16px;">Dashboard</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="<?=base_url('Admin/pelanggan');?>">
            <i class="fas fa-users"></i>
            <span>Data Pelanggan</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#keuanganMenu" aria-expanded="false" aria-controls="keuanganMenu">
            <i class="fas fa-wallet"></i>
            <span>Keuangan</span>
        </a>

        <div id="keuanganMenu" class="collapse" aria-labelledby="headingKeuangan" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                
                <div class="submenu">
                    <a class="collapse-item collapsed" href="#" data-toggle="collapse" data-target="#tagihanMenu" aria-expanded="false" aria-controls="tagihanMenu">
                        <i class="fas fa-file-invoice"></i>
                        Data Pembayaran Wifi
                    </a>
                    <div id="tagihanMenu" class="collapse pl-3" aria-labelledby="headingTagihan" data-parent="#keuanganMenu">
                        <a class="collapse-item" href="<?=base_url('Admin/tagihan');?>">
                            <i class="fas fa-list"></i>
                            All Pembayaran
                        </a>
                        <a class="collapse-item" href="<?=base_url('Admin/tagihanbelumbayar');?>">
                            <i class="fas fa-exclamation-circle"></i>
                            Belum Bayar
                        </a>
                        <a class="collapse-item" href="<?=base_url('Admin/tagihandibayar');?>">
                            <i class="fas fa-check-circle"></i>
                            Di Bayar
                        </a>
                    </div>
                </div>

                <div class="submenu">
                    <a class="collapse-item" href="<?=base_url('Admin/saldo');?>">
                        <i class="fas fa-money-bill-wave"></i>
                        Data Saldo
                    </a>
                </div>

                <div class="submenu">
                    <a class="collapse-item" href="<?=base_url('Admin/pemasukan');?>">
                        <i class="fas fa-plus-square"></i>
                        Data Pemasukan
                    </a>
                </div>

                <div class="submenu">
                    <a class="collapse-item" href="<?=base_url('Admin/pengeluaran');?>">
                        <i class="fas fa-minus-square"></i>
                        Data Pengeluaran
                    </a>
                </div>
            </div>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="<?=base_url('Admin/lap_laba_rugi');?>">
            <i class="fas fa-file-alt"></i>
            <span>Laporan</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="<?=base_url('Admin/paket');?>">
            <i class="fas fa-box"></i>
            <span>Master Paket</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="<?=base_url('logout');?>">
            <i class="fas fa-sign-out-alt"></i>
            <span style="font-size: 16px;">Logout</span>
        </a>
    </li>

    <hr class="sidebar-divider">
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>