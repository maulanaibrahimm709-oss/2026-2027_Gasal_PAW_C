<?php
// Tahap validasi dengan detail error sesuai modul.
require_once 'validate.inc';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit('Kirim form menggunakan POST.');
}

$errors = [];
validateName($errors, $_POST, 'surname');

if ($errors) {
    echo 'Errors:<br>';
    foreach ($errors as $field => $error) {
        echo htmlspecialchars($field . ' ' . $error, ENT_QUOTES, 'UTF-8') . '<br>';
    }
} else {
    echo 'Data OK!';
}
?>
