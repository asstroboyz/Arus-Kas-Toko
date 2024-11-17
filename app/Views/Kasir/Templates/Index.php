<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?=$title;?></title>

    <!-- Custom fonts for this template-->
    <link href="<?php echo base_url() ?>/assets/img/hera.png"
        rel="icon">
    <link
        href="<?=base_url()?>/vendor/fontawesome-free/css/all.min.css"
        rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Custom styles for this template-->
    <link href="<?=base_url('css/custom.css');?>"
        rel="stylesheet">
    <link href="<?=base_url()?>/css/sb-admin-2.min.css"
        rel="stylesheet">
    <link href="<?=base_url()?>/assets/timeline.css"
        rel="stylesheet">


    <link
        href="<?=base_url()?>/assets/css/bootstrap-datepicker.min.css"
        rel="stylesheet">
    <link rel="stylesheet" type="text/css"
        href="<?=base_url();?>/vendor/datatables/dataTables.bootstrap4.min.css">
    <style>
        #content {
            background-image: url('<?=base_url()?>/img/lb/lb.jpeg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        /* Tambahkan bagian ini untuk mengatur tampilan footer */
        .sticky-footer {
            padding: 10px 0;
            /* Sesuaikan padding untuk membuat footer lebih kecil */
            background-color: #705425 !important;
            /* Tambahkan !important */
            color: #ffffff !important;
            /* Tambahkan !important */
            /* Pastikan warna latar belakang sesuai dengan tema Anda */
            /* position: fixed; */
            bottom: 0;
            width: 100%;
            z-index: 1000;
            /* Pastikan tetap di atas konten lainnya */
            box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.1);
            /* Tambahkan bayangan untuk lebih jelas */
        }

        .sticky-footer .container {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .sticky-footer .copyright {
            font-size: 14px;
            /* Sesuaikan ukuran font jika diperlukan */
            margin: 0;
        }

        /* Tambahkan margin-bottom ke body untuk mencegah tumpang tindih */
        body {
            margin-bottom: 60px;
            /* Sesuaikan dengan tinggi footer */
        }

        /* Tambahkan padding-bottom ke konten utama jika diperlukan */
        #content {
            padding-bottom: 60px;
            /* Sesuaikan dengan tinggi footer */
        }
    </style>

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <?=$this->include('Kasir/Templates/Sidebar');?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?=$this->include('Kasir/Templates/Topbar');?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <?=$this->renderSection('page-content');?>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container">
                    <div class="copyright text-center my-auto">
                        <span> &copy; Toko Hera Nolokerto
                            <?php echo date('Y'); ?></span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary"
                        href="<?=base_url('logout');?>">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->

    <script src="<?=base_url();?>/vendor/jquery/jquery.min.js"></script>
    <script
        src="<?=base_url('autoNumeric')?>/src/AutoNumeric.js">
    </script>
    <script src="<?=base_url()?>/vendor/bootstrap/js/bootstrap.bundle.min.js">
    </script>

    <script src="<?=base_url()?>/vendor/bootstrap/js/bootstrap.bundle.min.js">
    </script>
    <script src="<?=base_url()?>/vendor/jquery-easing/jquery.easing.min.js">
    </script>
    <script src="<?=base_url();?>/vendor/datatables/jquery.dataTables.min.js">
    </script>
    <script
        src="<?=base_url();?>/vendor/datatables/dataTables.bootstrap4.min.js">
    </script>
    <script src="<?=base_url()?>/js/sb-admin-2.min.js"></script>
    <script src="<?=base_url();?>/sbassets/js/demo/datatables-demo.js"></script>
    <script src="<?=base_url()?>/assets/js/bootstrap-datepicker.min.js">
    </script>

    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <?=$this->renderSection('additional-js')?>
    <script>
        $('.btn-change-group').on('click', function() {
            const id = $(this).data('id');

            $('.id').val(id);
            $('#changeGroupModal').modal('show');
        });

        $('.btn-change-password').on('click', function() {

            const id = $(this).data('id');
            // Set nilai pada input fields di modal
            $('#user_id').val(id);
            // Tampilkan modal untuk mengubah password
            $('#ubah_password').modal('show');
        });
        $('.btn-detail').on('click', function(e) {
            e.preventDefault(); // Menghentikan perilaku default tautan

            const id = $(this).data('id');
            const url = $(this).data('url');

            // Lakukan permintaan AJAX
            $.ajax({
                type: 'GET',
                url: url,
                data: {
                    id: id
                },
                success: function(response) {
                    // Lakukan sesuatu dengan data yang diterima dari server
                    console.log(response);

                    // Pindahkan pengguna ke halaman detail
                    window.location.href = url;
                },
                error: function(error) {
                    console.error('Error:', error);
                }
            });
        });


        $('.btn-active-users').on('click', function() {
            const id = $(this).data('id');
            const isActive = $(this).data('active');

            // Kirim permintaan AJAX untuk mengaktifkan atau menonaktifkan pengguna
            $.ajax({
                url: '/activate-user/' + id + '/' + (isActive == 1 ? 0 : 1),
                method: 'GET',
                success: function(response) {
                    // Tampilkan pesan atau lakukan tindakan lain jika diperlukan
                    console.log(response);

                    // Jika Anda ingin memperbarui tampilan tombol sesuai dengan status pengguna
                    if (isActive == 1) {
                        $(this).data('active', 0);
                        $(this).html('<i class="fas fa-times-circle"></i>');
                    } else {
                        $(this).data('active', 1);
                        $(this).html('<i class="fas fa-check-circle"></i>');
                    }
                },
                error: function(error) {
                    // Tampilkan pesan kesalahan jika diperlukan
                    console.error(error);
                }
            });
        });
    </script>
</body>

</html>