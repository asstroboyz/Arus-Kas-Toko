<?=$this->extend('Admin/Templates/Index');?>

<?=$this->section('page-content');?>
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-900">Form Restok Barang</h1>

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
        action="<?=base_url('/Admin/simpanRestok')?> "
        method="post" enctype="multipart/form-data">
        <?=csrf_field();?>
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
                                <div class="row mx-0">
                                    <!-- <div class="col-lg-3">
                                        <div class="form-group">
                                            <label for="stok">Stok</label>
                                            <input type="text" id="stok" class="form-control" readonly
                                                value="<?=$selectedProduk['stok'] ?? ''?>">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="satuan_barang">Satuan</label>
                                    <input type="text" id="satuan_barang" class="form-control" readonly>
                                </div>
                            </div> -->
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
                                        value="<?=$selectedProduk['harga_jual'] ?? ''?>">
                                    <input type="text" id="harga_jual" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="jumlah">Jml Penambahan</label>
                                    <input type="number" name="jumlah[]" id="jumlah" class="form-control" required>
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
                                    <select name="kode_barang[]" id="kode_barang" class="form-control" autofocus
                                        required>
                                        <option value="">Pilih Barang</option>
                                        <?php foreach ($barangList as $brg): ?>
                                        <option
                                            value="<?=$brg['kode_barang'];?>"
                                            data-satuan="<?=$brg['nama_satuan'];?>"
                                            data-hj="<?=$brg['harga_jual'];?>"
                                            data-stok="<?=$brg['stok'];?>">
                                            <?=$brg['nama_brg'] . ' - ' . $brg['merk'] . ' - ' . $brg['tipe_barang'] . ' - ' . $brg['nama_satuan'];?>
                                        </option>
                                        <?php endforeach;?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="id_supplier">Nama Supplier</label>
                                    <select name="id_supplier[]" id="id_supplier" class="form-control" required>
                                        <option value="">Pilih Supplier</option>
                                        <?php foreach ($supplierList as $supplier): ?>
                                        <option
                                            value="<?=$supplier['id_supplier'];?>">
                                            <?=$supplier['nama'];?>
                                        </option>
                                        <?php endforeach;?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                        <div class="row mx-0">

                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="metode_pembayaran">Metode Pembayaran</label>
                                    <select name="metode_pembayaran[]" id="metode_pembayaran" class="form-control"
                                        required>
                                        <option value="">Pilih Metode Pembayaran</option>
                                        <option value="tunai">Tunai</option>
                                        <option value="non_tunai">Non-Tunai</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="status_bayar">Status Pembayaran</label>
                                    <select name="status_bayar[]" id="status_bayar" class="form-control" required>
                                        <option value="">Pilih Status Pembayaran</option>
                                        <option value="lunas">Lunas</option>
                                        <option value="hutang">Hutang</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="jumlah_pembayaran">Total Belanja</label>
                                    <input type="number" name="jumlah_pembayaran[]" id="jumlah_pembayaran"
                                        class="form-control" readonly>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="jumlah_uang">Jumlah Uang</label>
                                    <input type="number" name="jumlah_uang" id="jumlah_uang" class="form-control"
                                        required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="kembalian">Kembalian</label>
                                    <input type="text" id="kembalian" class="form-control" readonly>
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
            <?=helper('form');?>
            <?=csrf_field();?>
            <div class="row">
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                    <div class="row mx-0">
                        <div class="col-lg-5">
                            <div class="form-group">
                                <label for="stok_satuan">Stok Yang Tersedia & Satuan</label>
                                <input type="text" id="stok_satuan" class="form-control stok_satuan" readonly>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="harga_jual">Harga</label>
                                <input type="hidden" name="harga_jual_raw_2" id="harga_jual_raw_2" value="<?=$selectedProduk['harga_jual'] ?? ''?>">
                                <input type="text" id="harga_jual" class="form-control harga_jual">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for="jumlah">Jumlah</label>
                                <input type="number" name="jumlah[]" id="jumlah" class="form-control jumlah" autofocus required>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="kode_barang">Pilih Barang</label>
                                <select name="kode_barang[]" class="form-control kode_barang" required>
                                    <option value="">Pilih Barang</option>
                                    <?php foreach ($barangList as $brg): ?>
                                    <option value="<?=$brg['kode_barang'];?>" data-satuan="<?=$brg['nama_satuan'];?>" data-hj="<?=$brg['harga_jual'];?>" data-stok="<?=$brg['stok'];?>">
                                        <?=$brg['nama_brg'];?> - <?=$brg['merk'];?> - <?=$brg['tipe_barang'];?>
                                    </option>
                                    <?php endforeach;?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                    <div class="row mx-0">
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for="sub_total">Total per item</label>
                                <input type="number" name="sub_total[]" id="sub_total" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="status_bayar">Status Pembayaran</label>
                                <select name="status_bayar[]" id="status_bayar" class="form-control" required>
                                    <option value="">Pilih Status Pembayaran</option>
                                    <option value="lunas">Lunas</option>
                                    <option value="hutang">Hutang</option>
                                </select>
                            </div>
                        </div>
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

    //     // Ketika terjadi perubahan pada dropdown kode_barang
    //     $('#form').on('change', '.kode_barang', function() {
    //         var selectedOption = $('option:selected', this);
    //         var selectedSatuan = selectedOption.data('satuan');
    //         var selectedStok = selectedOption.data('stok');
    //         var selectedHJ = selectedOption.data('hj');

    //         // Menampilkan data di elemen terkait di dalam form yang ditambahkan dinamis
    //         $(this).closest('.row').find('.satuan_barang').val(selectedSatuan);
    //         $(this).closest('.row').find('.stok').val(selectedStok);

    //         // Menampilkan harga jual dalam format rupiah
    //         var hargaJual =
    //             selectedHJ; // Tidak perlu menghapus karakter non-digit karena kita akan menggunakan formatRupiah() pada subtotal
    //         $(this).closest('.row').find('.harga_jual').val(formatRupiah(hargaJual));
    //     });
    // // Event listener ketika input jumlah berubah pada form yang ditambahkan dinamis
    // $('#form').on('input', '.jumlah', function() {
    //     var value = $(this).val();
    //     var stokTersedia = parseInt($(this).closest('.row').find('.stok').val());

    //     // Validasi agar tidak bisa diisi bilangan minus
    //     if (value < 0) {
    //         $(this).val('');
    //     } else {
    //         // Jika nilai input lebih besar dari stok yang tersedia, tampilkan modal
    //         if (value > stokTersedia) {
    //             $('#modalContent').text('Jumlah barang melebihi stok yang tersedia.');
    //             $('#alertModal').modal('show'); // Tampilkan modal
    //             $(this).val(stokTersedia); // Set nilai input menjadi stok yang tersedia
    //         } else {
    //             // Hitung subtotal saat mengubah nilai input jumlah_barang
    //             var hargaJual = parseFloat($(this).closest('.row').find('.harga_jual').val().replace(/\D/g,
    //                 '')); // Menghapus semua karakter kecuali angka
    //             var jumlah = parseFloat(value);
    //             if (!isNaN(hargaJual) && !isNaN(jumlah)) {
    //                 var subtotal = hargaJual * jumlah;
    //                 $(this).closest('.row').find('.sub').val(formatRupiah(
    //                     subtotal)); // Menampilkan subtotal dengan format Rupiah
    //             }
    //         }
    //     }
    // });


    // Event listener untuk menghapus form
    $('#form').on('click', '#remove-form', function() {
        $(this).closest('.row').remove();
    });

    // Event listener untuk menambahkan form baru
    $('#form').on('click', '#btn_tambah', function() {
        // Kode untuk menambahkan form baru
    });


    //form kedua
    $(document).ready(function() {

        /// form pertama
        $('#kode_barang').change(function() {
            var selectedOption = $('option:selected', this);
            var selectedSatuan = selectedOption.data('satuan');
            var selectedStok = selectedOption.data('stok');
            var selectedHJ = selectedOption.data('hj');
            // Menggabungkan data stok dan satuan
            var stokSatuan = selectedStok + ' ' + selectedSatuan;

            // Menampilkan data di elemen terkait
            $('#satuan_barang').val(selectedSatuan);
            $('#stok').val(selectedStok);
            $('#stok_satuan').val(stokSatuan);

            // Menampilkan harga jual dalam format rupiah
            var hargaJual = formatRupiah(selectedHJ);
            $('#harga_jual').val(hargaJual);

        });

        //form kedua
        $('#form').on('change', '.kode_barang', function() {
            var selectedOption = $('option:selected', this);
            var selectedSatuan = selectedOption.data('satuan');
            var selectedStok = selectedOption.data('stok');
            var selectedHJ = selectedOption.data('hj');
            var stokSatuan = selectedStok + ' ' + selectedSatuan;

            // Menampilkan data di elemen terkait di dalam form yang ditambahkan dinamis
            $(this).closest('.row').find('.stok_satuan').val(stokSatuan);
            // Menampilkan data di elemen terkait di dalam form yang ditambahkan dinamis
            $(this).closest('.row').find('.satuan_barang').val(selectedSatuan);
            $(this).closest('.row').find('.stok').val(selectedStok);

            // Menampilkan harga jual dalam format rupiah
            var hargaJual =
                selectedHJ; // Tidak perlu menghapus karakter non-digit karena kita akan menggunakan formatRupiah() pada subtotal
            $(this).closest('.row').find('.harga_jual').val(formatRupiah(hargaJual));
        });

        // Format angka ke format rupiah
        function formatRupiah(angka) {
            var reverse = angka.toString().split('').reverse().join('');
            var ribuan = reverse.match(/\d{1,3}/g);
            ribuan = ribuan.join('.').split('').reverse().join('');
            return ribuan;
        }

        // Event listener ketika input jumlah berubah form pertama
        $('#jumlah').on('input', function() {
            var value = $(this).val();
            var stokTersedia = parseInt($('#stok').val());

            // Validasi agar tidak bisa diisi bilangan minus
            if (value < 0) {
                $(this).val('');
            } else {
                // Jika nilai input lebih besar dari stok yang tersedia, tampilkan modal
                if (value > stokTersedia) {
                    $('#modalContent').text('Jumlah barang melebihi stok yang tersedia.');
                    $('#alertModal').modal('show'); // Tampilkan modal
                    $(this).val(stokTersedia); // Set nilai input menjadi stok yang tersedia
                } else {
                    // Hitung subtotal saat mengubah nilai input jumlah_barang
                    var hargaJual = parseFloat($('#harga_jual').val().replace(/\D/g,
                        '')); // Menghapus semua karakter kecuali angka
                    var jumlah = parseFloat(value);
                    if (!isNaN(hargaJual) && !isNaN(jumlah)) {
                        var subtotal = hargaJual * jumlah;
                        $('#sub_total').val(formatRupiah(
                            subtotal)); // Menampilkan subtotal dengan format Rupiah
                    }
                }
            }
        });

        // Event listener untuk input jumlah
        $('#form').on('input', '.jumlah', function() {
            var value = $(this).val();
            var stokTersedia = parseInt($(this).closest('.form-group').prev().find('.stok').val());
            var subtotal = parseFloat(value) * parseFloat(hargaJual);

            // Validasi agar tidak bisa diisi bilangan minus
            if (value < 0) {
                $(this).val('');
            } else {
                // Jika nilai input lebih besar dari stok yang tersedia, tampilkan modal
                if (value > stokTersedia) {
                    $('#modalContent').text('Jumlah barang melebihi stok yang tersedia.');
                    $('#alertModal').modal('show'); // Tampilkan modal
                    $(this).val(stokTersedia); // Set nilai input menjadi stok yang tersedia
                } else {
                    // Hitung subtotal saat mengubah nilai input jumlah_barang
                    var hargaJual = parseFloat($(this).closest('.form-group').next().find('.harga_jual')
                        .val().replace(/\D/g, '')); // Ambil harga jual dari input terkait
                    var jumlah = parseFloat(value);
                    if (!isNaN(hargaJual) && !isNaN(jumlah)) {
                        var subtotal = hargaJual * jumlah;
                        $(this).closest('.form-group').next().find('.harga_jual').val(formatRupiah(
                            subtotal)); // Menampilkan subtotal dengan format Rupiah
                    }
                }
            }
        });



        // Validasi jumlah agar tidak melebihi stok yang tersedia
        function validateJumlah() {
            var jumlah = parseInt($('#jumlah').val());
            var stok = parseInt($('#stok').val());

            if (jumlah > stok) {
                $('#jumlah').addClass('is-invalid');
                $('#jumlah').siblings('.invalid-feedback').text(
                    'Jumlah tidak boleh melebihi stok yang tersedia.'
                );
            } else {
                $('#jumlah').removeClass('is-invalid');
                $('#jumlah').siblings('.invalid-feedback').text('');
            }
        }

        $('#jumlah').on('input', function() {
            var value = $(this).val();
            var stokTersedia = parseInt($('#stok').val());

            // Validasi agar tidak bisa diisi bilangan minus
            if (value < 0) {
                $(this).val('');
            } else {
                // Jika nilai input lebih besar dari stok yang tersedia, tampilkan modal
                if (value > stokTersedia) {
                    $('#modalContent').text('Jumlah barang melebihi stok yang tersedia.');
                    $('#alertModal').modal('show'); // Tampilkan modal
                    $(this).val(stokTersedia); // Set nilai input menjadi stok yang tersedia
                } else {
                    // Hitung subtotal saat mengubah nilai input jumlah_barang
                    var hargaJual = parseFloat($('#harga_jual').val().replace(/\D/g,
                        '')); // Menghapus semua karakter kecuali angka
                    var jumlah = parseFloat(value);
                    if (!isNaN(hargaJual) && !isNaN(jumlah)) {
                        var subtotal = hargaJual * jumlah;
                        $('#sub_total').val(formatRupiah(
                            subtotal)); // Menampilkan subtotal dengan format Rupiah
                    }
                }
            }
        });



        // Submit formulir hanya jika input jumlah, perihal, dan detail valid
        $('form').submit(function() {
            var inputJumlah = $('#jumlah').val();
            var nilaiInputJumlah = parseInt(inputJumlah);
            var hargaJualRaw = $('#harga_jual_raw').val();
            var subTotalRaw = $('#sub_total').val();

            // Validasi input jumlah agar tidak bernilai 0 atau negatif
            if (!inputJumlah || nilaiInputJumlah <= 0) {
                $('#jumlah_barang').addClass('is-invalid');
                $('#jumlah_barang').siblings('.invalid-feedback').text(
                    'Jumlah harus diisi dengan angka lebih dari 0.');
                $('#alertModal').modal('show'); // Modal muncul jika jumlah barang kosong
                return false;
            }

            var stokTersedia = parseInt($('#stok').val());

            // Periksa apakah jumlah barang melebihi stok yang tersedia
            if (nilaiInputJumlah > stokTersedia) {
                $('#jumlah_barang').addClass('is-invalid');
                $('#jumlah_barang').siblings('.invalid-feedback').text(
                    'Jumlah barang melebihi stok yang tersedia.');
                $('#alertModal').modal('show'); // Modal muncul jika jumlah barang melebihi stok
                return false;
            }
            var hargaJual = parseFloat($('#harga_jual').val().replace(/\D/g,
                '')); // Menghapus semua karakter kecuali angka
            var subtotal = nilaiInputJumlah * hargaJual;
            // Menghilangkan format Rupiah sebelum mengirimkan data formulir
            $('#sub_total').val(subtotal); // Menggunakan nilai subtotal yang sudah dihitung

            // Atur input subtotal menjadi readonly
            $('#sub_total').prop('readonly', true);
            var hargaJual = $('#harga_jual').val();
            var subTotal = $('#sub_total').val();
            $('#harga_jual_raw').val(hargaJualRaw.replace(/\D/g, ''));
            $('#sub_total').val(subTotalRaw.replace(/\D/g, ''));

            return true; // Mengizinkan pengiriman formulir jika semua input valid
        });
        // Mengatur fungsi untuk menutup modal saat tombol "Close" ditekan

        // Menghilangkan pesan alert setelah 3 detik
        window.setTimeout(function() {
            $(".alert").fadeTo(500, 0).slideUp(500, function() {
                $(this).remove();
            });
        }, 3000);
        $(document).on('click', '#closeModal', function() {
            $('#alertModal').modal('hide');
        });

    });
</script>


<?=$this->endSection();?>