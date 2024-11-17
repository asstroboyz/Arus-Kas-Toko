<?= $this->extend('Admin/Templates/Index'); ?>

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
        <div class="col-lg-6">
            <div class="card shadow">
                <div class="card-header">
                    <a href="/Admin/kas">&laquo; Kembali ke daftar kas</a>
                </div>
                <div class="card-body">
                    <!-- Form Tambah Kas -->
                    <div class="container">
                        <form action="/admin/saveKas" method="post">
                            <?= csrf_field() ?>

                            <!-- Tanggal -->
                            <div class="form-group">
                                <label for="tanggal">Tanggal</label>
                                <input type="date" class="form-control" id="tanggal" name="tanggal" required>
                            </div>

                            <!-- Jenis Transaksi -->
                            <div class="form-group">
                                <label for="jenis_transaksi">Jenis Transaksi</label>
                                <select class="form-control" id="jenis_transaksi" name="jenis_transaksi" required>
                                    <option value="penerimaan">Penerimaan</option>
                                    <option value="pengeluaran">Pengeluaran</option>
                                </select>
                            </div>

                            <!-- Keterangan -->
                            <div class="form-group">
                                <label for="keterangan">Keterangan</label>
                                <input type="text" class="form-control" id="keterangan" name="keterangan" required>
                            </div>

                            <!-- Jumlah Masuk -->
                            <div class="form-group" id="jumlah_masuk_field">
                                <label for="jumlah_masuk">Jumlah Masuk</label>
                                <input type="text" class="form-control harga-input" id="jumlah_masuk"
                                    name="jumlah_masuk">
                            </div>

                            <!-- Jumlah Keluar -->
                            <div class="form-group" id="jumlah_keluar_field" style="display: none;">
                                <label for="jumlah_keluar">Jumlah Keluar</label>
                                <input type="text" class="form-control harga-input" id="jumlah_keluar"
                                    name="jumlah_keluar">
                            </div>

                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('additional-js'); ?>
<script>
    // Function to toggle visibility of jumlah_masuk and jumlah_keluar fields
    document.getElementById("jenis_transaksi").addEventListener("change", function() {
        var jenisTransaksi = this.value;
        var jumlahMasukField = document.getElementById("jumlah_masuk_field");
        var jumlahKeluarField = document.getElementById("jumlah_keluar_field");

        if (jenisTransaksi === "penerimaan") {
            jumlahMasukField.style.display = "block";
            jumlahKeluarField.style.display = "none";
        } else if (jenisTransaksi === "pengeluaran") {
            jumlahMasukField.style.display = "none";
            jumlahKeluarField.style.display = "block";
        }
    });

    // Format input jumlah masuk dan jumlah keluar untuk tampilan saja
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
        var number_string = angka.toString().replace(/[^,\d]/g, ''),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        // tambahkan titik jika yang di input sudah menjadi angka ribuan
        if (ribuan) {
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
        return rupiah;
    }

    // Validasi saat pengiriman formulir
    $('form').submit(function() {
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
</script>
<?= $this->endSection(); ?>