<?= $this->extend('Admin/Templates/Index'); ?>

<?= $this->section('page-content'); ?>
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->


    <?php if (session()->getFlashdata('msg')) : ?>
    <div class="row">
        <div class="col-12">
            <div class="alert alert-success" role="alert">
                <?= session()->getFlashdata('msg'); ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')) : ?>
    <div class="row">
        <div class="col-12">
            <div class="alert alert-danger" role="alert">
                <?= session()->getFlashdata('error'); ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <!-- Modal Peringatan -->
    <div class="modal" id="warningModal" tabindex="-1" role="dialog" aria-labelledby="warningModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="warningModalLabel">Peringatan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Perhitungan perkiraan penjualan tidak dapat dilakukan. Silakan pilih metode perkiraan yang lain.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    <form
        action="<?= base_url('/Admin/save_perkiraan') ?>"
        method="post" enctype="multipart/form-data">
        <?= csrf_field(); ?>
        <div class="row">
            <div class="col-lg-6">
                <div class="card shadow">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="kode_barang">Pilih Barang</label>
                            <select name="kode_barang" id="kode_barang" class="form-control" autofocus required>
                                <option value="">Pilih Barang</option>
                                <?php foreach ($barangList as $brg) : ?>
                                <option
                                    value="<?= $brg['kode_barang']; ?>"
                                    data-satuan="<?= $brg['nama_satuan']; ?>"
                                    data-hj="<?= $brg['harga_jual']; ?>"
                                    data-stok="<?= $brg['stok']; ?>"
                                    data-id_satuan="<?= $brg['id_satuan']; ?>">

                                    <?= $brg['nama_brg'] . ' - ' . $brg['merk']; ?>

                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="harga_jual">Harga</label>
                            <input type="hidden" name="harga_jual_raw" id="harga_jual_raw"
                                value="<?= $selectedProduk['harga_jual'] ?? '' ?>">
                            <input type="text" id="harga_jual" class="form-control" readonly>
                        </div>

                        <div class="form-group">
                            <label for="satuan_barang">Satuan</label>
                            <input type="text" id="satuan_barang" class="form-control" readonly>
                            <input type="hidden" id="id_satuan" name="id_satuan">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card shadow">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="tanggal_mulai">Tanggal Mulai</label>
                                    <input type="text" id="tanggal_mulai" name="tanggal_mulai" class="form-control"
                                        readonly required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="tanggal_akhir">Tanggal Akhir</label>
                                    <input type="text" id="tanggal_akhir" name="tanggal_akhir" class="form-control"
                                        required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="metode_perkiraan">Metode Perkiraan:</label>
                            <select class="form-control" name="metode_perkiraan" id="metode_perkiraan" required>
                                <option value="" selected>Pilih metode</option>
                                <option value="moving_average">Moving Average</option>
                                <option value="exponential_smoothing">Exponential Smoothing</option>
                                <option value="time_series">Time Series</option>
                            </select>
                            <?php if (session()->has('error_metode')) : ?>
                            <div class="alert alert-danger mt-2" role="alert">
                                <?= session('error_metode') ?>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-block btn-primary my-2" id="btn_tambah">Tambah Data</button>
    </form>
</div>
<!-- /.container-fluid -->

<?= $this->endSection(); ?>

<?= $this->section('additional-js'); ?>
<script>
    $(document).ready(function() {
        // Inisialisasi datepicker untuk input tanggal_mulai
        $("#tanggal_mulai").datepicker({
            dateFormat: 'yy-mm-dd', // Format tanggal yang diinginkan
            minDate: 0, // Mulai dari tanggal hari ini
            onSelect: function(selectedDate) {
                // Ketika tanggal mulai dipilih, atur tanggal akhir agar tidak lebih awal dari tanggal mulai
                $("#tanggal_akhir").datepicker("option", "minDate", selectedDate);
            }
        });

        // Inisialisasi datepicker untuk input tanggal_akhir
        $("#tanggal_akhir").datepicker({
            dateFormat: 'yy-mm-dd', // Format tanggal yang diinginkan
            minDate: 0, // Mulai dari tanggal hari ini
            onSelect: function(selectedDate) {
                // Ketika tanggal akhir dipilih, atur tanggal mulai agar tidak lebih dari tanggal akhir
                $("#tanggal_mulai").datepicker("option", "maxDate", selectedDate);
            }
        });
        var today = new Date().toISOString().slice(0, 10);

        // Set tanggal saat ini ke input tanggal_mulai dan tanggal_akhir
        $('#tanggal_mulai').val(today);
        $('#tanggal_akhir').val(today);

        // Tampilkan satuan dan harga berdasarkan barang yang dipilih
        $('#kode_barang').on('change', function() {
            var harga = $(this).find(':selected').data('hj');
            var satuan = $(this).find(':selected').data('satuan');
            var id_satuan = $(this).find(':selected').data('id_satuan');

            $('#harga_jual').val(harga);
            $('#satuan_barang').val(satuan);
            $('#id_satuan').val(id_satuan);
        });
    });
</script>
<?= $this->endSection(); ?>