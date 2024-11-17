  <style>
    .navbar {
        background: linear-gradient(180deg, #705425, #825e3c);
        /* Gradasi warna dari #C89361 ke #705425 */
    }

    .navbar {
        background-color: #C89361;
        /* Warna amber dari gambar */
    }

    .navbar .nav-item .nav-link {
        color: #ffffff;
        /* Warna teks putih untuk kontras lebih baik */
    }

    .navbar .nav-item .nav-link:hover {
        background-color: #705425;
        /* Warna gradasi yang lebih gelap untuk efek hover */
        color: #ffffff;
        /* Warna teks putih saat hover */
    }

    .navbar .navbar-brand {
        color: #ffffff;
        /* Warna teks putih untuk sidebar brand */
    }
</style>
 <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

     <!-- Sidebar Toggle (Topbar) -->
     <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
         <i class="fa fa-bars"></i>
     </button>

     <!-- Topbar Search -->

     <!-- Topbar Navbar -->
     <ul class="navbar-nav ml-auto">





         <!-- Messages Dropdown -->



         <div class="topbar-divider d-none d-sm-block"></div>

         <!-- Nav Item - User Information -->
         <li class="nav-item dropdown no-arrow">
             <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown"
                 aria-haspopup="true" aria-expanded="false">
                 <span
                     class="mr-2 d-none d-lg-inline text-white-600 large"><?=user()->username;?></span>
                 <img class="img-profile rounded-circle"
                     src="<?= empty(user()->foto) ? '/sbassets/img/undraw_profile.svg' : '/uploads/profile/' . user()->foto; ?>">
             </a>
             <!-- Dropdown - User Information -->
             <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                 <a class="dropdown-item"
                     href="<?=base_url('pemilik/profil');?>">
                     <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                     Profile
                 </a>

                 <div class="dropdown-divider"></div>
                 <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                     <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                     Logout
                 </a>
             </div>
         </li>

     </ul>

 </nav>