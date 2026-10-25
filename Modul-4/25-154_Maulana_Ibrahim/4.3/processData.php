<?php
// Tahap awal modul: menampilkan data form menggunakan FOREACH.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit('Silakan buka processData_form.html dan kirim form terlebih dahulu.');
}

$data = $_POST;

// Checkbox tidak mengirim data jika tidak dicentang.
if (!isset($data['vegetarian'])) {
    $data['vegetarian'] = 'No';
}

echo '<h1>Data Register</h1>';
echo '<table border="1" cellpadding="6">';
echo '<tr><th>Field</th><th>Value</th></tr>';

foreach ($data as $field => $value) {
    if (is_array($value)) {
        $value = implode(', ', $value);
    }

    echo '<tr><td>' . htmlspecialchars($field, ENT_QUOTES, 'UTF-8') .
         '</td><td>' . htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8') .
         '</td></tr>';
}
echo '</table>';
?>
