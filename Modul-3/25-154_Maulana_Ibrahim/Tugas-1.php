<?php
// 1.1 Menambahkan lima data baru ke array
$fruits = array("Avocado", "Blueberry", "Cherry");

$fruits[] = "Durian";
$fruits[] = "Elderberry";
$fruits[] = "Fig";
$fruits[] = "Grape";
$fruits[] = "Honeydew";
// Menampilkan array setelah penambahan
echo 'fruits = ("' . implode('", "', $fruits) . '")';
echo "<br>";

// Menampilkan indeks tertinggi
$indeksTertinggi = count($fruits) - 1;
echo "Nilai dengan indeks tertinggi: "
    . $fruits[$indeksTertinggi];

echo "<br><br>";


// 1.2 Menghapus data Blueberry dari array
$posisi = array_search("Blueberry", $fruits);

if ($posisi !== false) {
    array_splice($fruits, $posisi, 1);
    echo "Data Blueberry dihapus.<br>";
}

// Menampilkan array setelah penghapusan
echo 'fruits = ("' . implode('", "', $fruits) . '")';
echo "<br>";

// Menampilkan indeks tertinggi setelah penghapusan
$indeksTertinggi = count($fruits) - 1;
echo "Nilai dengan indeks tertinggi: "
    . $fruits[$indeksTertinggi];
?>
