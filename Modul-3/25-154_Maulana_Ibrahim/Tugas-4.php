<?php
//4.1
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

foreach ($height as $nama => $tinggi) {
    echo "$nama is $tinggi cm tall.<br>";
}

echo "<br><br>";

//4.2
$weight = array(
    "Andy" => "70",
    "Barry" => "65",
    "Charlie" => "75"
);


echo 'weight = ("Andy"=>"70", "Barry"=>"65", "Charlie"=>"75")<br>';
// Mengambil kunci array
$nama = array_keys($weight);
// Menampilkan seluruh data menggunakan for
for ($i = 0; $i < count($weight); $i++) {
    $orang = $nama[$i];
    echo "$orang is " . $weight[$orang] . " kg.<br>";
}
?>