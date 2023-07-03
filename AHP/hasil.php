<!DOCTYPE html>
<html>
<head>
    <title>Pemilihan Smartphone dengan AHP - Hasil</title>
    <link rel="stylesheet" type="text/css" href="hasil.css">
</head>
<body>
    <h1>Pemilihan Smartphone dengan AHP - Hasil</h1>
    <?php
    // Menerima data input dari form
    $harga1 = $_POST['harga1'];
    $harga2 = $_POST['harga2'];
    $harga3 = $_POST['harga3'];

    $kamera1 = $_POST['kamera1'];
    $kamera2 = $_POST['kamera2'];
    $kamera3 = $_POST['kamera3'];

    $kapasitas1 = $_POST['kapasitas1'];
    $kapasitas2 = $_POST['kapasitas2'];
    $kapasitas3 = $_POST['kapasitas3'];

    $alternatif1 = $_POST['alternatif1'];
    $alternatif2 = $_POST['alternatif2'];
    $alternatif3 = $_POST['alternatif3'];

    // Langkah-langkah perhitungan AHP

    // Step 1: Normalisasi Matriks Perbandingan Berpasangan untuk setiap kriteria
    $matriks_harga = array(
        array(1, $harga1, $harga2),
        array(1/$harga1, 1, $harga3),
        array(1/$harga2, 1/$harga3, 1)
    );

    $matriks_kamera = array(
        array(1, 1/$kamera1, 1/$kamera2),
        array($kamera1, 1, $kamera3),
        array($kamera2, 1/$kamera3, 1)
    );

    $matriks_kapasitas = array(
        array(1, 1/$kapasitas1, 1/$kapasitas2),
        array($kapasitas1, 1, $kapasitas3),
        array($kapasitas2, $kapasitas3, 1)
    );

    // Step 2: Menghitung Vektor Prioritas Relatif untuk setiap kriteria
    $prioritas_harga = hitungPrioritasRelatif($matriks_harga);
    $prioritas_kamera = hitungPrioritasRelatif($matriks_kamera);
    $prioritas_kapasitas = hitungPrioritasRelatif($matriks_kapasitas);

    // Step 3: Menghitung Matriks Gabungan untuk alternatif
    $matriks_gabungan = array(
        array($prioritas_harga[0], $prioritas_kamera[0], $prioritas_kapasitas[0]),
        array($prioritas_harga[1], $prioritas_kamera[1], $prioritas_kapasitas[1]),
        array($prioritas_harga[2], $prioritas_kamera[2], $prioritas_kapasitas[2])
    );

    // Step 4: Menghitung Prioritas Akhir untuk setiap alternatif
    $prioritas_akhir = hitungPrioritasAkhir($matriks_gabungan);

    // Step 5: Menentukan alternatif terbaik sesuai preferensi (prioritas tertinggi)
    $alternatif_terbaik_index = array_search(max($prioritas_akhir), $prioritas_akhir);
    $alternatif_terbaik = "";

    switch ($alternatif_terbaik_index) {
        case 0:
            $alternatif_terbaik = $alternatif1;
            break;
        case 1:
            $alternatif_terbaik = $alternatif2;
            break;
        case 2:
            $alternatif_terbaik = $alternatif3;
            break;
    }

    // Tampilkan hasil prioritas akhir
    echo "<h2>Prioritas Akhir:</h2>";
    echo "<p>Alternatif 1 ($alternatif1): " . $prioritas_akhir[0] . "</p>";
    echo "<p>Alternatif 2 ($alternatif2): " . $prioritas_akhir[1] . "</p>";
    echo "<p>Alternatif 3 ($alternatif3): " . $prioritas_akhir[2] . "</p>";

    // Tampilkan alternatif terbaik
    echo "<h2>Alternatif Terbaik:</h2>";
    echo "<p>Alternatif terbaik berdasarkan preferensi: $alternatif_terbaik</p>";

    // Fungsi untuk menghitung prioritas relatif
    function hitungPrioritasRelatif($matriks)
    {
        $jumlah_baris = count($matriks);
        $jumlah_kolom = count($matriks[0]);
        $prioritas_relatif = array();

        for ($i = 0; $i < $jumlah_kolom; $i++) {
            $total = 0;
            for ($j = 0; $j < $jumlah_baris; $j++) {
                $total += $matriks[$j][$i];
            }
            $prioritas_relatif[] = $total / $jumlah_baris;
        }

        return $prioritas_relatif;
    }

    // Fungsi untuk menghitung prioritas akhir
    function hitungPrioritasAkhir($matriks)
    {
        $jumlah_baris = count($matriks);
        $jumlah_kolom = count($matriks[0]);
        $prioritas_akhir = array();

        for ($i = 0; $i < $jumlah_baris; $i++) {
            $total = 0;
            for ($j = 0; $j < $jumlah_kolom; $j++) {
                $total += $matriks[$i][$j];
            }
            $prioritas_akhir[] = $total;
        }

        $total_prioritas = array_sum($prioritas_akhir);

        // Normalisasi prioritas akhir
        for ($i = 0; $i < $jumlah_baris; $i++) {
            $prioritas_akhir[$i] = $prioritas_akhir[$i] / $total_prioritas;
        }

        return $prioritas_akhir;
    }
    ?>
</body>
</html>
