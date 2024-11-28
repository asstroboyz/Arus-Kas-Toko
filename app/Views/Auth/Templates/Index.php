<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Halaman Landing </title>

    <!-- Custom fonts for this template-->
    <link href="<?php echo base_url() ?>/assets/img/10.png"
        rel="icon">
    <link
        href="<?= base_url() ?>/vendor/fontawesome-free/css/all.min.css"
        rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link
        href="<?= base_url('css/custom.css'); ?>"
        rel="stylesheet">
    <link href="<?= base_url() ?>/css/sb-admin-2.min.css"
        rel="stylesheet">
    <link href="<?= base_url() ?>/assets/timeline.css"
        rel="stylesheet">
    <link rel="stylesheet" type="text/css"
        href="<?= base_url(); ?>/vendor/datatables/dataTables.bootstrap4.min.css">

</head>

<body>

    <?= $this->renderSection('content'); ?>

    <!-- Bootstrap core JavaScript-->
    <!-- Bootstrap core JavaScript-->
    <script src="<?= base_url() ?>/vendor/jquery/jquery.min.js"></script>
    <script src="<?= base_url() ?>/vendor/bootstrap/js/bootstrap.bundle.min.js">
    </script>

    <!-- Core plugin JavaScript-->
    <script src="<?= base_url() ?>/vendor/jquery-easing/jquery.easing.min.js">
    </script>

    <!-- Custom scripts for all pages-->
    <script src="<?= base_url() ?>/js/sb-admin-2.min.js"></script>
    <script src="<?= base_url(); ?>/vendor/datatables/jquery.dataTables.min.js">
    </script>
    <script
        src="<?= base_url(); ?>/vendor/datatables/dataTables.bootstrap4.min.js">
    </script>

    <!-- Page level custom scripts -->
    <script src="<?=base_url();?>/assets/js/demo/datatables-demo.js"></script>
    <?= $this->renderSection('additional-js') ?>

</body>

</html>