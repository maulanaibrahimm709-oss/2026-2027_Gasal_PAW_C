<?php
// Tahap validasi sederhana modul: Data OK! atau Data invalid!
require_once 'validate.inc';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit('Kirim form menggunakan POST.');
}

$errors = [];
validateName($errors, $_POST, 'surname');

if ($errors) {
    echo 'Data invalid!';
} else {
    echo 'Data OK!';
}
?>
