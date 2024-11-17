<?=$this->extend('Kasir/Templates/Index');?>

<?=$this->section('page-content');?>
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->


    <?php if (session()->getFlashdata('msg')): ?>
    <div class="row">
        <div class="col-12">
            <div class="alert alert-success" role="alert">
                <?=session()->getFlashdata('msg');?>
            </div>
        </div>
    </div>

    <?php endif;?>
    <form
        action="<?= base_url('/Kasir/simpanPenjualanBrg') ?> "
        method="post" enctype="multipart/form-data">
        <?=csrf_field();?>
        <div class="row">
            <div class="col-12">

                <div class="card shadow">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-6">
                                <h1 class="h3 mb-4 text-gray-900">Form Penjualan Barang</h1>
                                <a href="/Kasir/PenjualanBarang">&laquo; Kembali ke daftar barang</a>
                            </div>
                            <div class="col-6 text-right">

                                <button type="button" class="btn btn-primary" id="add-form">Tambah form</button>

                            </div>
                        </div>
                    </div>
                    <div class="card-body">

                        <div class="row">
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                <div class="row mx-0">

                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label for="stok">Stok </label>
                                            <input type="text" id="stok_satuan" class="form-control" readonly
                                                value="<?=$selectedProduk['stok'] ?? ''?> <?=$selectedProduk['satuan_barang'] ?? ''?>">
                                        </div>
                                    </div>

                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label for="harga_jual">Harga</label>
                                            <input type="hidden" name="harga_jual_raw" id="harga_jual_raw"
                                                value="<?= $selectedProduk['harga_jual'] ?? '' ?>">
                                            <input type="text" id="harga_jual" class="form-control" required readonly
                                                name="harga[]">
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label for="jumlah">Jumlah</label>
                                            <input type="number" name="jumlah[]" id="jumlah" class="form-control"
                                                required>
                                        </div>
                                    </div>

                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label for="sub_total">Total per item</label>
                                            <input type="number" name="sub_total[]" id="sub_total" class="form-control"
                                                readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mx-0">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="kode_barang">Pilih Barang</label>
                                            <select name="kode_barang[]" id="kode_barang" class="form-control select2"
                                                autofocus required>
                                                <option value="">Pilih Barang</option>
                                                <?php foreach ($barangList as $brg): ?>
                                                <option
                                                    value="<?=$brg['kode_barang'];?>"
                                                    data-satuan="<?=$brg['nama_satuan'];?>"
                                                    data-hj="<?=$brg['harga_jual'];?>"
                                                    data-stok="<?=$brg['stok'];?>">
                                                    <?=$brg['nama_brg'] . ' - ' . $brg['merk']  . ' - ' . $brg['nama_satuan'];?>
                                                </option>
                                                <?php endforeach;?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="id_supplier">Nama Supplier</label>
                                            <select name="id_pelanggan[]" id="id_pelanggan" class="form-control select2"
                                                required>
                                                <option value="">Pilih Pelanggan</option>
                                                <?php foreach ($pelangganList as $pelanggan) : ?>
                                                <option
                                                    value="<?= $pelanggan['id_pelanggan']; ?>">
                                                    <?= $pelanggan['nama']; ?>
                                                </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                <div class="row mx-0">
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="jumlah_pembayaran">Total Belanja</label>
                                            <input type="text" name="jumlah_pembayaran" id="jumlah_pembayaran"
                                                class="form-control" readonly>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="jumlah_uang">Jumlah Uang</label>
                                            <input type="number" name="jumlah_uang" id="jumlah_uang"
                                                class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="kembalian">Kembalian</label>
                                            <input type="text" id="kembalian" class="form-control" name="kembalian"
                                                readonly required>
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="" id="form"></div>
                <button class="btn btn-block btn-primary my-2" id="btn_tambah">Tambah Data</button>
            </div>
        </div>

    </form>
</div>
<div class="modal" tabindex="-1" role="dialog" id="alertModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Peringatan!!!</h5>
                <button type="button" class="btn btn-danger" id="closeModal" aria-label="Close">X</button>
            </div>
            <div class="modal-body">
                <p id="modalContent"></p>
            </div>

        </div>
    </div>
</div>
<?=$this->endSection();?>
<?=$this->section('additional-js');?>
<script>
    $(document).ready(function() {
        // Fungsi untuk menginisialisasi Select2
        function initializeSelect2() {
            $('.select2').select2({
                placeholder: "Pilih ",
                allowClear: true
            });
        }

        // Inisialisasi Select2 pada halaman saat ini
        initializeSelect2();

        // Menambahkan form baru
        $('#add-form').on('click', function() {
            $('#btn_tambah').remove();
            $('#form').append(`
        <div class="row my-2">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header">
                        <button type="button" class="btn btn-danger" id="remove-form">Hapus</button>
                    </div>
                    <div class="card-body">
                        <?= helper('form'); ?>
                        <?= csrf_field(); ?>
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="stok_satuan">Stok Yang Tersedia & Satuan</label>
                                    <input type="text" id="stok_satuan" class="form-control stok_satuan" readonly>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="harga_jual">Harga</label>
                                    <input type="hidden" name="harga_jual_raw_2" id="harga_jual_raw_2" value="<?= $selectedProduk['harga_jual'] ?? '' ?>">
                                    <input type="text" id="harga_jual" class="form-control harga_jual" readonly name="harga[]">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="jumlah">Jumlah</label>
                                    <input type="number" name="jumlah[]" id="jumlah" class="form-control jumlah" required>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="sub_total">Total per item</label>
                                    <input type="number" name="sub_total[]" id="sub_total2" class="form-control sub_total" readonly>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="kode_barang">Pilih Barang</label>
                                    <select name="kode_barang[]" id="kode_barang" class="form-control select2" required>
                                        <option value="">Pilih Barang</option>
                                        <?php foreach ($barangList as $brg): ?>
                                        <option value="<?= $brg['kode_barang']; ?>"
                                                data-satuan="<?= $brg['nama_satuan']; ?>"
                                                data-hj="<?= $brg['harga_jual']; ?>"
                                                data-stok="<?= $brg['stok']; ?>">
                                            <?= $brg['nama_brg'] . ' - ' . $brg['merk']  . ' - ' . $brg['nama_satuan']; ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button class="btn btn-block btn-primary my-2" id="btn_tambah">Tambah Data</button>
        `);

            // Inisialisasi Select2 pada elemen yang baru ditambahkan
            initializeSelect2();
        });

        // Event listener untuk menghapus form
        $('#form').on('click', '#remove-form', function() {
            $(this).closest('.row').remove();
            getTotal(); // Hitung ulang total setelah form dihapus
        });

        // Event listener untuk perubahan pada kode_barang di form utama
        $('#kode_barang').change(function() {
            var selectedOption = $('option:selected', this);
            var selectedSatuan = selectedOption.data('satuan');
            var selectedStok = selectedOption.data('stok');
            var selectedHJ = selectedOption.data('hj');
            var stokSatuan = selectedStok + ' ' + selectedSatuan;

            // Menampilkan data di elemen terkait
            $('#stok_satuan').val(stokSatuan);
            $('#harga_jual').val(formatRupiah(selectedHJ));
            hitungSubtotal(); // Hitung subtotal saat harga berubah
        });

        // Event listener untuk perubahan pada kode_barang di form dinamis
        $('#form').on('change', '.select2', function() {
            var selectedOption = $('option:selected', this);
            var selectedSatuan = selectedOption.data('satuan');
            var selectedStok = selectedOption.data('stok');
            var selectedHJ = selectedOption.data('hj');
            var stokSatuan = selectedStok + ' ' + selectedSatuan;

            // Menampilkan data di elemen terkait di dalam form yang ditambahkan dinamis
            $(this).closest('.row').find('.stok_satuan').val(stokSatuan);
            $(this).closest('.row').find('.harga_jual').val(formatRupiah(selectedHJ));
            hitungSubtotal(); // Hitung subtotal saat harga berubah
        });

        // Event listener ketika input jumlah berubah di form utama
        $('#jumlah').on('input', function() {
            hitungSubtotal(); // Panggil fungsi hitungSubtotal
        });

        // Event listener ketika input jumlah berubah di form dinamis
        $('#form').on('input', '.jumlah', function() {
            hitungSubtotal(); // Panggil fungsi hitungSubtotal
        });

        // Fungsi untuk menghitung subtotal
        function hitungSubtotal() {
            // Ambil nilai harga dan jumlah dari form utama
            var hargaJual = parseFloat($('#harga_jual').val().replace(/\D/g, ''));
            var jumlah = parseFloat($('#jumlah').val());

            // Cek apakah hargaJual dan jumlah valid
            if (!isNaN(hargaJual) && !isNaN(jumlah)) {
                var subtotal = hargaJual * jumlah;
                $('#sub_total').val(formatRupiah(subtotal));
            } else {
                $('#sub_total').val('0');
            }

            // Menghitung subtotal untuk form dinamis
            $('.jumlah').each(function() {
                var hargaJual = parseFloat($(this).closest('.row').find('.harga_jual').val().replace(
                    /\D/g, ''));
                var jumlah = parseFloat($(this).val());
                if (!isNaN(hargaJual) && !isNaN(jumlah)) {
                    var subtotal = hargaJual * jumlah;
                    $(this).closest('.row').find('.sub_total').val(formatRupiah(subtotal));
                } else {
                    $(this).closest('.row').find('.sub_total').val('0');
                }
            });

            // Update total keseluruhan
            getTotal();
        }

        // Fungsi untuk menghitung total dari semua sub_total
        function getTotal() {
            var total = 0;
            // $('.sub_total').each(function() {
            //     var subTotal = $(this).val().replace(/\D/g, '');
            //     total += parseInt(subTotal);
            // });

            // $('#jumlah_pembayaran').val(formatRupiah(total)); 
            var subTotal = $('#sub_total').val().replace(/\D/g, '');
            var total = parseInt(subTotal, 10);
            $('.sub_total').each(function() {
                var subTotal = $(this).val().replace(/\D/g, '');
                total += parseInt(subTotal);
            });

            // Format total ke dalam format Rupiah dan set ke input jumlah_pembayaran
            $('#jumlah_pembayaran').val(formatRupiah(total));
        }
        $('#jumlah_uang').focusout(function() {
            var jumlahUang = parseInt($('#jumlah_uang').val().replace(/\D/g, ''));
            var jumlahPembayaran = parseInt($('#jumlah_pembayaran').val().replace(/\D/g, ''));

            // Menghitung kembalian
            var kembalian = jumlahUang - jumlahPembayaran;

            // Format kembalian sebagai format Rupiah dengan minus jika kurang dari 0
            var formattedKembalian = formatRupiah(kembalian);

            // Menetapkan nilai kembalian ke dalam input kembalian
            $('#kembalian').val(formattedKembalian);
        });

        // Format angka ke format rupiah
        function formatRupiah(angka) {
            var reverse = angka.toString().split('').reverse().join('');
            var ribuan = reverse.match(/\d{1,3}/g);
            ribuan = ribuan.join('.').split('').reverse().join('');
            return ribuan;
        }
    });
</script>


<?=$this->endSection();?>