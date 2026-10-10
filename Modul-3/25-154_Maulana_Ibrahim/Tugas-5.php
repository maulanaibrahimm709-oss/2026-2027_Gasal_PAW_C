<?php
//5.1

$mahasiswa = array(
    array("Alex", "220401", "0812345678"),
    array("Bianca", "220402", "0812345687"),
    array("Candice", "220403", "0812345665")
);

echo "<h4>Data awal</h4>";
echo "<pre>";
print_r($mahasiswa);
echo "</pre>";

$mahasiswa[] = array("Daniel", "220404", "0812345611");
$mahasiswa[] = array("Elena", "220405", "0812345622");
$mahasiswa[] = array("Fiona", "220406", "0812345633");
$mahasiswa[] = array("Gloria", "220407", "0812345644");
$mahasiswa[] = array("Hannah", "220408", "0812345655");

echo "<h4>Data setelah ditambah 5 data lain</h4>";
echo "<pre>";
print_r($mahasiswa);
echo "</pre>";

echo "<h4>Tabel Data Mahasiswa</h4>";
echo "<table border='1' cellpadding='5' cellspacing='0'>";
echo "<tr>
        <th>Name</th>
        <th>NIM</th>
        <th>Mobile</th>
      </tr>";

foreach ($mahasiswa as $data) {
    echo "<tr>";
    echo "<td>" . htmlspecialchars($data[0]) . "</td>";
    echo "<td>" . htmlspecialchars($data[1]) . "</td>";
    echo "<td>" . htmlspecialchars($data[2]) . "</td>";
    echo "</tr>";
}

echo "</table>";
?>