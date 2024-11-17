<?= $this->extend('Admin/Templates/Index') ?>

<?=$this->section('page-content');?>
<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-900"></h1>

    <?php if (session()->getFlashdata('error-msg')): ?>
    <div class="row">
        <div class="col-12">
            <div class="alert alert-danger" role="alert">
                <?=session()->getFlashdata('error-msg');?>
            </div>
        </div>
    </div>
    <?php endif;?>

    <?php if (session()->getFlashdata('msg')): ?>
    <div class="row">
        <div class="col-12">
            <div class="alert alert-success alert-dismissible show fade" role="alert">

                <div class="alert-body">
                    <button class="close" data-dismiss>x</button>
                    <b><i class="fa fa-check"></i></b>
                    <?=session()->getFlashdata('msg');?>
                </div>
            </div>
        </div>
    </div>
    <?php endif;?>

    <div class="row">
        <div class="col-12">

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h3>Perkiraan Penjualan</h3>
                    <a href="/Admin/tambah_perkiraan" class="btn btn-primary"><i class="fa fa-plus"></i> Tambah
                        Perkiraan Penjualan</a>

                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal Dibuat</th>
                                    <th>Periode perkiraan</th>
                                    <th>Kode Barang</th>
                                    <th>Hasil perkiraan terjual</th>

                                    <th>Metode perkiraan</th>



                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>No</th>    
                                     <th>Tanggal Dibuat</th>

                                    <th>Periode perkiraan</th>
                                    <th>Kode Barang</th>
                                    <th>Hasil perkiraan terjual</th>

                                    <th>Metode perkiraan</th>



                                </tr>
                            </tfoot>
                            <tbody>

                                <?php if ($perkiraan) {?>
                                <?php foreach ($perkiraan as $num => $data) {?>


                                <tr>
                                    <td><?=$num + 1;?></td>
                                    <td>  <?php 
    $createdAt = new DateTime($data['created_at']);
    echo $createdAt->format(' d-m-Y H:i:s '); // Format tanggal dan waktu yang diinginkan
    ?>
                                        
                                    </td>
                                    <td><?=$data['periode_perkiraan'];?>
                                        hari kedepan
                                    </td>
                                    <td><?= $data['nama_brg']; ?>
                                        -
                                        <?= $data['merk']; ?>

                                    </td>



                                    <td><?=$data['hasil_perkiraan'];?>
                                        -
                                        <?= $data['nama_satuan']; ?>

                                    </td>

                                    <td>
                                        <?php
        switch ($data['metode_perkiraan']) {
            case 'moving_average':
                echo 'Moving Average';
                break;
            case 'exponential_smoothing':
                echo 'Exponential Smoothing';
                break;
            case 'time_series':
                echo 'Time Series';
                break;
            default:
                echo 'Unknown';
                break;
        }
                                    ?>
                                    </td>



                                </tr>
                                <?php }?>
                                <!-- end of foreach                -->
                                <?php } else {?>
                                <tr>
                                    <td colspan="4">
                                        <h3 class="text-gray-900 text-center">Data belum ada.</h3>
                                    </td>
                                </tr>
                                <?php }?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>

<?=$this->endSection();?>
<?=$this->section('additional-js');?>
<script>
    window.setTimeout(function() {
        $(".alert").fadeTo(500, 0).slideUp(500, function() {
            $($this).remove();
        })

    }, 3000);
</script>
<?=$this->endSection();?>