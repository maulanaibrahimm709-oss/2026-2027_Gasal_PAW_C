<?php
// Tahap akhir modul: self-submission, validasi, dan mempertahankan isian.
$errors = [];
$old = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old = $_POST;
    require_once 'validate.inc';

    validateName($errors, $_POST, 'surname');
    validateOtherFields($errors, $_POST);

    if ($errors) {
        echo '<h2>Invalid, correct the following errors:</h2>';
        foreach ($errors as $field => $error) {
            echo htmlspecialchars($field . ' ' . $error, ENT_QUOTES, 'UTF-8') . '<br>';
        }
        include 'form.inc';
    } else {
        echo '<h2>Form submitted successfully with no errors</h2>';
        echo '<h3>Data yang diterima</h3><table border="1" cellpadding="6">';
        foreach ($_POST as $field => $value) {
            if (is_array($value)) {
                $value = implode(', ', $value);
            }
            echo '<tr><th>' . htmlspecialchars($field, ENT_QUOTES, 'UTF-8') .
                 '</th><td>' . htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8') . '</td></tr>';
        }
        if (!isset($_POST['vegetarian'])) {
            echo '<tr><th>vegetarian</th><td>No</td></tr>';
        }
        echo '</table>';
    }
} else {
    include 'form.inc';
}
?>
