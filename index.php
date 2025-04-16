<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Penghitung Diskon</title>
    <link rel="stylesheet" href="index.css"> <!-- Menghubungkan file CSS eksternal -->
</head>
<body>
    <div class="container"> 
        <h1>Penghitung Diskon</h1>

        <!-- Form untuk input harga dan diskon -->
        <form method="post" id="diskonForm">
            <label for="harga">Harga Awal (Rp):</label>
            <input type="text" name="harga" id="harga" required placeholder="Contoh: 10000">

            <label for="diskon">Diskon (%):</label>
            <input type="text" step="any" name="diskon" id="diskon" required placeholder="Contoh: 10">

            <!-- Tombol untuk hitung, hapus, dan reset -->
            <div class="button-group">
                <button type="submit" class="btn hitung">Hitung</button>
                <button type="button" class="btn hapus" onclick="hapusInput()">Hapus</button>
                <button type="button" class="btn reset" onclick="resetForm()">Reset</button>
            </div>
        </form>

        <?php
        // Mengecek apakah form telah dikirim dengan metode POST
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            // Mengganti koma dengan titik agar bisa diparsing sebagai float
            $harga_input = str_replace(',', '.', $_POST["harga"]);
            $price = floatval($harga_input);

            $diskon_input = str_replace(',', '.', $_POST["diskon"]);
            $diskon = floatval($diskon_input);

            echo "<div class='hasil'>";

            // Validasi harga dan diskon
            if ($price <= 0) {
                echo "<p style='color:red;'>Harga tidak boleh nol atau kosong</p>";
            } elseif ($diskon < 1 || $diskon > 100) {
                echo "<p style='color:red;'>Diskon harus antara 1% sampai 100%</p>";
            } else {
                // Menghitung jumlah diskon dan harga setelah diskon
                $jumlah_diskon = ($price * $diskon) / 100;
                $harga_setelah_diskon = $price - $jumlah_diskon;

                // Menampilkan hasil perhitungan
                echo "<p>Harga Awal: Rp " . number_format($price, 2, ',', '.') . "</p>";
                echo "<p>Diskon: " . $diskon . "%</p>";
                echo "<p>Potongan Harga: Rp " . number_format($jumlah_diskon, 2, ',', '.') . "</p>";
                echo "<p><strong>Harga Setelah Diskon: Rp " . number_format($harga_setelah_diskon, 2, ',', '.') . "</strong></p>";
            }

            echo "</div>";
        }
        ?>
    </div>

    <script>
    const hargaInput = document.getElementById("harga");
    const diskonInput = document.getElementById("diskon");
    const form = document.getElementById("diskonForm");

    // Fungsi untuk memformat angka menjadi format rupiah saat mengetik
    hargaInput.addEventListener("input", function (e) {
        let value = this.value.replace(/[^,\d]/g, "").toString();
        const split = value.split(",");
        let sisa = split[0].length % 3;
        let rupiah = split[0].substr(0, sisa);
        const ribuan = split[0].substr(sisa).match(/\d{3}/g);

        if (ribuan) {
            const separator = sisa ? "." : "";
            rupiah += separator + ribuan.join(".");
        }

        rupiah = split[1] !== undefined ? rupiah + "," + split[1] : rupiah;
        this.value = rupiah;
    });

    // Validasi sebelum kirim form
    form.addEventListener("submit", function (e) {
        const hargaValue = parseFloat(hargaInput.value.replace(/\./g, "").replace(',', '.'));
        const diskonValue = parseFloat(diskonInput.value.replace(',', '.'));

        if (isNaN(hargaValue) || hargaValue <= 0) {
            alert("Harga tidak boleh kosong atau nol");
            e.preventDefault();
            return;
        }

        if (isNaN(diskonValue) || diskonValue < 1 || diskonValue > 100) {
            alert("Diskon harus di antara 1% sampai 100%");
            e.preventDefault();
        } else {
            // Set nilai harga yang diformat ke angka sebelum dikirim ke server
            hargaInput.value = hargaValue.toString().replace('.', ','); // Kirim dengan koma untuk PHP
            diskonInput.value = diskonValue.toString().replace('.', ',');
        }
    });

    // Menghapus isi input harga dan diskon
    function hapusInput() {
        hargaInput.value = "";
        diskonInput.value = "";
    }

    // Reset halaman
    function resetForm() {
        window.location.href = window.location.pathname;
    }
</script>

</body>
</html>