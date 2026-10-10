<?php
// 3.1 Mendeklarasikan array asosiatif height

$height = array(
    "Andy" => "176",
    "Barry" => "165",
    "Charlie" => "170"
);

$height["David"] = "180";
$height["Ethan"] = "172";
$height["Frank"] = "168";
$height["George"] = "175";
$height["Harry"] = "182";

echo 'height = ("Andy"=>"176", "Barry"=>"165", "Charlie"=>"170", ';
echo '"David"=>"180", "Ethan"=>"172", "Frank"=>"168", ';
echo '"George"=>"175", "Harry"=>"182")<br>';
echo "Nilai dengan indeks terakhir: " . end($height) . "<br>";

// Menghapus data tertentu, yaitu Barry
unset($height["Barry"]);

// Menampilkan data setelah penghapusan
echo '<br>height = (';

$items = array();
foreach ($height as $nama => $tinggi) {
    $items[] = '"' . $nama . '"=>"' . $tinggi . '"';
}
echo implode(", ", $items) . ")<br>";
// Menampilkan nilai terakhir setelah penghapusan
echo "Nilai dengan indeks terakhir: " . end($height);

echo "<br><br>";

// 3.2

$weight = array(
    "Andy" => "70",
    "Barry" => "65",
    "Charlie" => "75"
);
// Menampilkan data kedua
$keys = array_keys($weight);
$nama = $keys[1];
echo 'weight = ("Andy"=>"70", "Barry"=>"65", "Charlie"=>"75")<br>';
echo "Data kedua: " . $weight[$nama];
?>
