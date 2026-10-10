<?php
//6.1
$array = array("A");
echo 'Array awal: ("A")<br>';
array_push($array, "B");
echo "Hasil array_push: " . implode(" ", $array) . "<br><br>";

$array1 = array("A", "B");
$array2 = array("C");
echo 'Array awal: ("A", "B") digabung dengan ("C")<br>';

$hasilMerge = array_merge($array1, $array2);
echo "Hasil array_merge: "
    . implode(" ", $hasilMerge) . "<br><br>";

$array = array(
    "X" => 1,
    "Y" => 2
);
echo 'Array awal: ("X" => 1, "Y" => 2)<br>';
$hasilValues = array_values($array);
echo "Hasil array_values: "
    . implode(" ", $hasilValues) . "<br><br>";

$array = array("A", "B", "C");
echo 'Mencari "B" pada array: ("A", "B", "C")<br>';
$hasilSearch = array_search("B", $array);

echo "Hasil array_search: "
    . $hasilSearch . "<br><br>";

$array = array(0, 1, false, 2, "", 3, "array");
echo 'Array awal: (0, 1, false, 2, "", 3, "array")<br>';
$hasilFilter = array_filter($array);

echo "Hasil array_filter: "
    . implode(" ", $hasilFilter) . "<br><br>";

$array = array(3, 1, 2);
echo "Array awal: (3, 1, 2)<br>";

$hasilSort = $array;
sort($hasilSort);
echo "Hasil sort: "
    . implode(" ", $hasilSort) . "<br>";

$hasilRsort = $array;
rsort($hasilRsort);
echo "Hasil rsort: "
    . implode(" ", $hasilRsort) . "<br><br>";

$array = array(
    "Peter" => 35,
    "Ben" => 37,
    "Joe" => 43
);
echo 'Array awal: ("Peter"=>35, "Ben"=>37, "Joe"=>43)<br>';

$hasilAsort = $array;
asort($hasilAsort);
echo "Hasil asort: ";
foreach ($hasilAsort as $nama => $umur) {
    echo "$nama=>$umur, ";
}
echo "<br>";

$hasilKsort = $array;
ksort($hasilKsort);
echo "Hasil ksort: ";
foreach ($hasilKsort as $nama => $umur) {
    echo "$nama=>$umur, ";
}
echo "<br>";

$hasilArsort = $array;
arsort($hasilArsort);
echo "Hasil arsort: ";
foreach ($hasilArsort as $nama => $umur) {
    echo "$nama=>$umur, ";
}
echo "<br>";

$hasilKrsort = $array;
krsort($hasilKrsort);
echo "Hasil krsort: ";
foreach ($hasilKrsort as $nama => $umur) {
    echo "$nama=>$umur, ";
}
?>