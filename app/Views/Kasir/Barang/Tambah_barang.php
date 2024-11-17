<?= $this->extend('Kasir/Templates/Index'); ?>

<?= $this->section('page-content'); ?>
<div class="container-fluid">

    <!-- Flash Message -->
    <?php if (session()->getFlashdata('error-msg')) : ?>
    <div class="row">
        <div class="col-12">
            <div class="alert alert-danger" role="alert">
                <?= session()->getFlashdata('error-msg'); ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('msg')) : ?>
    <div class="row">
        <div class="col-12">
            <div class="alert alert-success" role="alert">
                <?= session()->getFlashdata('msg'); ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow">

                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <p class="h3 mb-4 text-white-900">Form Tambah Barang</p>
                    <a href="/BarangCont" class="btn btn-secondary">&laquo; Kembali ke daftar barang
                    </a>
                </div>
                <div class="card-body">
                    <form
                        action="<?= base_url('/BarangCont/saveBarang') ?>"
                        method="post" enctype="multipart/form-data">
                        <?= csrf_field(); ?>
                        <div class="row">
                            <div class="col-6">
                                <!-- Nama Barang -->
                                <div class="form-group">
                                    <label for="nama_barang">Nama Barang</label>
                                    <input type="text" name="nama_barang" id="nama_barang"
                                        class="form-control form-control-user <?= $validation->hasError('nama_barang') ? 'is-invalid' : ''; ?>"
                                        value="<?= old('nama_barang'); ?>"
                                        autofocus>
                                    <div id="nama_barangFeedback" class="invalid-feedback">
                                        <?= $validation->getError('nama_barang'); ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="jenis_brg">Jenis Barang</label>
                                    <input type="text" name="jenis_brg" id="jenis_brg"
                                        class="form-control form-control-user <?= $validation->hasError('jenis_brg') ? 'is-invalid' : ''; ?>"
                                        value="<?= old('jenis_brg'); ?>"
                                        autofocus>
                                    <div id="jenis_brgFeedback" class="invalid-feedback">
                                        <?= $validation->getError('jenis_brg'); ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="merk">Merk Barang</label>
                                    <input type="text" name="merk" id="merk"
                                        class="form-control form-control-user <?= $validation->hasError('merk') ? 'is-invalid' : ''; ?>"
                                        value="<?= old('merk'); ?>"
                                        autofocus>
                                    <div id="merkFeedback" class="invalid-feedback">
                                        <?= $validation->getError('merk'); ?>
                                    </div>
                                </div>
                                <!-- Satuan Barang -->
                                <div class="form-group">
                                    <label for="satuan_barang">Satuan Barang</label>
                                    <select name="satuan_barang" id="satuan_barang"
                                        class="form-control form-control-user <?= ($validation->hasError('satuan_barang')) ? 'is-invalid' : ''; ?>">
                                        <option value="">Pilih Satuan Barang</option>
                                        <?php foreach ($satuan as $satuan) : ?>
                                        <option
                                            value="<?= $satuan['satuan_id']; ?>">
                                            <?= $satuan['nama_satuan']; ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div id="satuan_barangFeedback" class="invalid-feedback">
                                        <?= $validation->getError('satuan_barang'); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <!-- Nama Supplier -->
                                <div class="form-group">
                                    <label for="id_supplier">Nama Supplier</label>
                                    <select name="id_supplier" id="id_supplier" class="form-control" required>
                                        <option value="">Pilih Supplier</option>
                                        <?php foreach ($supplierList as $supplier): ?>
                                        <option
                                            value="<?=$supplier['id_supplier'];?>">
                                            <?=$supplier['nama'];?>
                                        </option>
                                        <?php endforeach;?>
                                    </select>
                                </div>

                                <!-- Stok -->
                                <div class="form-group">
                                    <label for="stok">Stok</label>
                                    <input type="number" name="stok" id="stok"
                                        class="form-control form-control-user <?= $validation->hasError('stok') ? 'is-invalid' : ''; ?>"
                                        value="<?= old('stok'); ?>"
                                        autofocus>
                                    <div class="invalid-feedback">
                                        <?= $validation->getError('stok'); ?>
                                    </div>
                                </div>
                                <!-- Harga Beli -->
                                <div class="form-group">
                                    <label for="harga_beli">Harga Beli</label>
                                    <input type="text" name="harga_beli" id="harga_beli"
                                        class="form-control form-control-user <?= $validation->hasError('harga_beli') ? 'is-invalid' : ''; ?>"
                                        value="<?= old('harga_beli'); ?>">
                                    <div class="invalid-feedback">
                                        <?= $validation->getError('harga_beli'); ?>
                                    </div>
                                </div>
                                <!-- Harga Jual -->
                                <div class="form-group">
                                    <label for="harga_jual">Harga Jual</label>
                                    <input type="text" name="harga_jual" id="harga_jual"
                                        class="form-control form-control-user <?= $validation->hasError('harga_jual') ? 'is-invalid' : ''; ?>"
                                        value="<?= old('harga_jual'); ?>">
                                    <div class="invalid-feedback">
                                        <?= $validation->getError('harga_jual'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <button class="btn btn-block btn-primary">Tambah Data</button>
                             
                            </div>
                        </div>
                    </form>
                </div>


            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('additional-js'); ?>
<script>
    $(document).ready(function() {
        // Format input harga beli dan harga jual untuk tampilan saja
        $('.harga-input').on('input', function() {
            // Hilangkan karakter selain angka dan titik
            var val = $(this).val().replace(/[^0-9.]/g, '');

            // Format angka dengan titik sebagai pemisah ribuan
            var formattedVal = formatRupiah(val);

            // Tampilkan nilai yang diformat di input
            $(this).val(formattedVal);
        });

        // Fungsi untuk memformat angka menjadi format Rupiah
        function formatRupiah(angka) {
            var reverse = angka.toString().split('').reverse().join('');
            var ribuan = reverse.match(/\d{1,3}/g);
            ribuan = ribuan.join('.').split('').reverse().join('');
            return ribuan;
        }

        // Validasi untuk field stok
        $('#stok').on('input', function() {
            var nilaiInput = parseInt($(this).val());

            if (isNaN(nilaiInput) || nilaiInput <= 0) {
                $(this).val('');
                $(this).addClass('is-invalid');
                $(this).siblings('.invalid-feedback').text(
                    'Stok harus diisi dengan angka lebih dari 0.');
            } else {
                $(this).removeClass('is-invalid');
                $(this).siblings('.invalid-feedback').text('');
            }
        });

        // Validasi untuk field nama_barang
        $('#nama_barang').on('input', function() {
            var namaBarangInput = $(this).val().trim();

            if (namaBarangInput === '') {
                $(this).addClass('is-invalid');
                $(this).siblings('.invalid-feedback').text('Nama Barang harus diisi.');
            } else {
                $(this).removeClass('is-invalid');
                $(this).siblings('.invalid-feedback').text('');
            }
        });

        // Validasi untuk field satuan_barang
        $('#satuan_barang').on('change', function() {
            var satuanBarangInput = $(this).val().trim();

            if (satuanBarangInput === '') {
                $(this).addClass('is-invalid');
                $(this).siblings('.invalid-feedback').text('Satuan Barang harus dipilih.');
            } else {
                $(this).removeClass('is-invalid');
                $(this).siblings('.invalid-feedback').text('');
            }
        });

        // Validasi saat pengiriman formulir
        $('form').submit(function() {
            var inputJumlah = $('#stok').val();
            var nilaiInputJumlah = parseInt(inputJumlah);

            if (isNaN(nilaiInputJumlah) || nilaiInputJumlah <= 0) {
                $('#stok').val('');
                $('#stok').addClass('is-invalid');
                $('#stok').siblings('.invalid-feedback').text(
                    'Stok harus diisi dengan angka lebih dari 0.');
                return false;
            }

            var hargaBeli = parseFloat($('#harga_beli').val().replace(/\./g, '').replace(',', '.'));
            var hargaJual = parseFloat($('#harga_jual').val().replace(/\./g, '').replace(',', '.'));

            if (hargaJual <= hargaBeli) {
                $('#harga_jual').addClass('is-invalid');
                $('#harga_jual').siblings('.invalid-feedback').text(
                    'Harga Jual harus lebih besar dari Harga Beli.');
                return false;
            }

            var namaBarangInput = $('#nama_barang').val().trim();
            if (namaBarangInput === '') {
                $('#nama_barang').addClass('is-invalid');
                $('#nama_barang').siblings('.invalid-feedback').text('Nama Barang harus diisi.');
                return false;
            }

            var satuanBarangInput = $('#satuan_barang').val().trim();
            if (satuanBarangInput === '') {
                $('#satuan_barang').addClass('is-invalid');
                $('#satuan_barang').siblings('.invalid-feedback').text(
                    'Satuan Barang harus dipilih.');
                return false;
            }

            // Hapus titik dari nilai input sebelum disubmit
            $('.harga-input').each(function() {
                var val = $(this).val();
                $(this).val(val.replace(/\./g, ''));
            });

            return true;
        });

        // Fungsi untuk menyembunyikan pesan sukses setelah beberapa detik
        window.setTimeout(function() {
            $(".alert").fadeTo(500, 0).slideUp(500, function() {
                $(this).remove();
            });
        });
    });
</script>
<?= $this->endSection(); ?>