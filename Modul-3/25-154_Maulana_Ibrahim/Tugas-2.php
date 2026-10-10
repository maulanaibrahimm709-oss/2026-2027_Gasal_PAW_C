<?php
// 2.1 Menambahkan data menggunakan perulangan
$fruits = array("Avocado", "Blueberry", "Cherry");

// Menambahkan lima data menggunakan perulangan
for ($i = 1; $i <= 5; $i++) {
    $fruits[] = "Buah Tambahan " . $i;
}
// Menampilkan panjang array
echo "Panjang array saat ini: " . count($fruits) . "<br>";
// Menampilkan seluruh data buah
foreach ($fruits as $fruit) {
    echo $fruit . "<br>";
}

echo "<br>";

// 2.2 Menampilkan data array veggies
$veggies = array("Carrot", "Broccoli", "Spinach");

// Menampilkan seluruh data sayuran
for ($i = 0; $i < count($veggies); $i++) {
    echo $veggies[$i] . "<br>";
}
?>