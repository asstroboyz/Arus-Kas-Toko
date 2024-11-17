<?= $this->extend('Admin/Templates/Index'); ?>

<?= $this->section('page-content'); ?>
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-900">Form Tambah Barang</h1>

    <?php if (session()->getFlashdata('msg')) : ?>
    <div class="row">
        <div class="col-12">
            <div class="alert alert-success" role="alert">
                <?= session()->getFlashdata('msg'); ?>
            </div>
        </div>
    </div>

    <?php endif; ?>
    <form
        action="<?= base_url('/Admin/simpanPenjualanBrg') ?> "
        method="post" enctype="multipart/form-data">
        <?= csrf_field(); ?>
        <div class="row">
            <div class="col-12">

                <div class="card shadow">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-6">

                                <a href="/Admin/penjualan">&laquo; Kembali ke daftar barang</a>
                            </div>
                            <div class="col-6 text-right">

                                <button type="button" class="btn btn-primary" id="add-form">Tambah form</button>

                            </div>
                        </div>
                    </div>
                    <div class="card-body">

                        <div class="row">
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                <div class="row mx-2">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="stok">Stok Yang Tersedia</label>
                                            <input type="text" id="stok" class="form-control" readonly
                                                value="<?= $selectedProduk['stok'] ?? '' ?>">

                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="harga_jual">Harga </label>
                                            <input type="hidden" name="harga_jual_raw" id="harga_jual_raw"
                                                value="<?= $selectedProduk['harga_jual'] ?? '' ?>">
                                            <input type="text" id="harga_jual" class="form-control" readonly>
                                        </div>


                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="satuan_barang">Satuan</label>
                                        <input type="text" id="satuan_barang" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="kode_barang">Pilih Barang</label>
                                        <select name="kode_barang[]" id="kode_barang" class="form-control" required>
                                            <option value="">Pilih Barang</option>
                                            <?php foreach ($barangList as $brg) : ?>
                                            <option
                                                value="<?= $brg['kode_barang']; ?>"
                                                data-satuan="<?= $brg['nama_satuan']; ?>"
                                                data-hj="<?= $brg['harga_jual']; ?>"
                                                data-stok="<?= $brg['stok']; ?>">
                                                <?= $brg['nama_brg']; ?>(<?= $brg['merk']; ?>)
                                            </option>
                                            <?php endforeach; ?>
                                        </select>

                                    </div>
                                </div>


                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="jumlah">jumlah</label>
                                    <input type="number" name="jumlah[]" id="jumlah" class="form-control" autofocus
                                        required>
                                </div>

                                <div class="form-group">
                                    <label for="metode_pembayaran">Metode Pembayaran</label>
                                    <select name="metode_pembayaran[]" id="metode_pembayaran" class="form-control"
                                        required>
                                        <option value="">Pilih Metode Pembayaran</option>
                                        <option value="Tunai">Tunai</option>
                                        <option value="Non-Tunai">Non-Tunai</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="sub_total">Sub Total</label>
                                    <input type="text" name="sub_total[]" id="sub_total" class="form-control" readonly>
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

<?= $this->endSection(); ?>
<?= $this->section('additional-js'); ?>
<script>
    // add new form
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
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                <div class="form-group">
                                        <label for="kode_barang">Pilih Barang</label>
                                        <select name="kode_barang[]" class="form-control kode_barang" required>
                                            <option value="">Pilih Barang</option>
                                            <?php foreach ($barangList as $brg) : ?>
                                            <option value="<?= $brg['kode_barang']; ?>"
                                                data-satuan="<?= $brg['nama_satuan']; ?>"><?= $brg['merk']; ?>
                                                - <?= $brg['nama_satuan']; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="satuan_barang">Satuan</label>
                                        <input type="text" class="form-control satuan_barang" readonly>
                                    </div>
                                </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                               <div class="form-group">
                                    <label for="jumlah">jumlah</label>
                                    <input type="number" name="jumlah[]" id="jumlah" class="form-control" autofocus
                                        required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <button class="btn btn-block btn-primary my-2" id="btn_tambah">Tambah Data</button>
        `);
    });

    // remove form
    $('#form').on('click', '#remove-form', function() {
        $(this).closest('.row').remove();
    });

    // Fungsi form baru
    $('#form').on('change', '.kode_barang', function() {
        // Mendapatkan satuan dari data-satuan yang disimpan di opsi terpilih
        var selectedSatuan = $(this).find('option:selected').data('satuan');


        // Menampilkan satuan di elemen dengan class 'satuan_barang' yang berada di dalam parent dari dropdown yang berubah
        $(this).closest('.row').find('.satuan_barang').val(selectedSatuan);

    });

    $(document).ready(function() {

        // fungsi for atas awal Saat dropdown 'kode_barang' berubah
        $('#kode_barang').change(function() {
            // Mendapatkan satuan dari data-satuan yang disimpan di opsi terpilih
            var selectedOption = $('option:selected', this);
            var selectedSatuan = selectedOption.data('satuan');
            var selectedStok = selectedOption.data('stok');
            var selectedHJ = selectedOption.data('hj');
            // Menampilkan data di elemen terkait
            $('#satuan_barang').val(selectedSatuan);
            $('#stok').val(selectedStok);

            // Menampilkan harga jual dalam format rupiah
            var hargaJual = formatRupiah(selectedHJ);
            $('#harga_jual').val(hargaJual);

            // Menghitung subtotal dan menampilkannya dalam format rupiah
            hitungSubtotal();
        });

        function hitungSubtotal() {
            var hargaJual = $('#harga_jual_raw').val();
            var jumlah = $('#jumlah').val();
            var subtotal = parseInt(hargaJual) * parseInt(jumlah);

            // Menampilkan subtotal dalam format rupiah
            var subtotalFormatted = formatRupiah(subtotal);
            $('#sub_total').val(subtotalFormatted);
        }

        // Fungsi untuk format angka ke format rupiah
        function formatRupiah(angka) {
            var reverse = angka.toString().split('').reverse().join('');
            var ribuan = reverse.match(/\d{1,3}/g);
            ribuan = ribuan.join('.').split('').reverse().join('');
            return ribuan;
        }

        // Event listener ketika input jumlah berubah
        $('#jumlah').on('input', function() {
            hitungSubtotal();
        });
        // Validasi input perihal agar tidak kosong

        // Submit formulir hanya jika input jumlah, perihal, dan detail valid
        $('form').submit(function() {
            var inputJumlah = $('#jumlah').val();
            var nilaiInputJumlah = parseInt(inputJumlah);
            var hargaJualRaw = $('#harga_jual_raw').val();
            var subTotalRaw = $('#sub_total').val();
            // Validasi input jumlah agar tidak bernilai 0 atau negatif
            if (isNaN(nilaiInputJumlah) || nilaiInputJumlah <= 0) {
                $('#jumlah').val('');
                $('#jumlah').addClass('is-invalid');
                $('#jumlah').siblings('.invalid-feedback').text(
                    'Jumlah harus diisi dengan angka bulat positif lebih dari 0.'
                );
                return false;
            } else {
                $('#jumlah').removeClass('is-invalid');
                $('#jumlah').siblings('.invalid-feedback').text('');
            }

            // Menghilangkan format Rupiah sebelum mengirimkan data formulir
            var hargaJual = $('#harga_jual').val();
            var subTotal = $('#sub_total').val();
            $('#harga_jual_raw').val(hargaJualRaw.replace(/\D/g, ''));
            $('#sub_total').val(subTotalRaw.replace(/\D/g, ''));

            return true; // Mengizinkan pengiriman formulir jika semua input valid
        });

        // Menghilangkan pesan alert setelah 3 detik
        window.setTimeout(function() {
            $(".alert").fadeTo(500, 0).slideUp(500, function() {
                $(this).remove();
            });
        }, 3000);
    });


    // $(document).ready(function() {


    //     // fungsi for atas awal Saat dropdown 'kode_barang' berubah
    //     $('#kode_barang').change(function() {
    //         // Mendapatkan satuan dari data-satuan yang disimpan di opsi terpilih
    //         var selectedOption = $('option:selected', this);
    //         var selectedSatuan = selectedOption.data('satuan');
    //         var selectedStok = selectedOption.data('stok');
    //         var selectedHJ = selectedOption.data('hj');

    //         // Menampilkan data di elemen terkait
    //         $('#satuan_barang').val(selectedSatuan);
    //         $('#stok').val(selectedStok);
    //         $('#harga_jual').val(selectedHJ);
    //     });

    //     // Validasi input jumlah agar tidak bernilai 0 atau negatif
    //     $('#jumlah').on('input', function() {
    //         var inputJumlah = $(this).val();

    //         // Menggunakan parseInt untuk mengonversi nilai input ke tipe data integer
    //         var nilaiInput = parseInt(inputJumlah);

    //         // Memastikan nilaiInput adalah angka bulat positif dan tidak 0
    //         if (isNaN(nilaiInput) || nilaiInput <= 0) {
    //             $(this).val('');
    //             $(this).addClass('is-invalid');
    //             $(this).siblings('.invalid-feedback').text(
    //                 'Jumlah harus diisi dengan angka bulat positif lebih dari 0.'
    //             );
    //         } else {
    //             $(this).removeClass('is-invalid');
    //             $(this).siblings('.invalid-feedback').text('');
    //         }
    //     });

    //     // Validasi input perihal agar tidak kosong

    //     // Submit formulir hanya jika input jumlah, perihal, dan detail valid
    //     $('form').submit(function() {
    //         var inputJumlah = $('#jumlah').val();
    //         // var inputPerihal = $('#perihal').val();
    //         // var inputDetail = $('#detail').val();

    //         var nilaiInputJumlah = parseInt(inputJumlah);

    //         if (isNaN(nilaiInputJumlah) || nilaiInputJumlah <= 0) {
    //             $('#jumlah').val('');
    //             $('#jumlah').addClass('is-invalid');
    //             $('#jumlah').siblings('.invalid-feedback').text(
    //                 'Jumlah harus diisi dengan angka bulat positif lebih dari 0.'
    //             );
    //         } else {
    //             $('#jumlah').removeClass('is-invalid');
    //             $('#jumlah').siblings('.invalid-feedback').text('');
    //         }

    //         // if (inputPerihal.trim() === '') {
    //         //     $('#perihal').addClass('is-invalid');
    //         //     $('#perihal').siblings('.invalid-feedback').text(
    //         //         'Perihal tidak boleh kosong.');
    //         // } else {
    //         //     $('#perihal').removeClass('is-invalid');
    //         //     $('#perihal').siblings('.invalid-feedback').text('');
    //         // }

    //         // if (inputDetail.trim() === '') {
    //         //     $('#detail').addClass('is-invalid');
    //         //     $('#detail').siblings('.invalid-feedback').text(
    //         //         'Detail tidak boleh kosong.');
    //         // } else {
    //         //     $('#detail').removeClass('is-invalid');
    //         //     $('#detail').siblings('.invalid-feedback').text('');
    //         // }

    //         // Mencegah pengiriman formulir jika ada input yang tidak valid
    //         if (isNaN(nilaiInputJumlah) || nilaiInputJumlah <= 0 || inputPerihal
    //             .trim() === '' ||
    //             inputDetail.trim() === '') {
    //             return false;
    //         }

    //         return true; // Mengizinkan pengiriman formulir jika semua input valid
    //     });

    //     // Menghilangkan pesan alert setelah 3 detik
    //     window.setTimeout(function() {
    //         $(".alert").fadeTo(500, 0).slideUp(500, function() {
    //             $(this).remove();
    //         });
    //     }, 3000);
    // });
</script>


<?= $this->endSection(); ?>