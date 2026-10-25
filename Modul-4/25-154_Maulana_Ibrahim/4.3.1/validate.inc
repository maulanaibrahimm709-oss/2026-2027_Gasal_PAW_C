<?php
// Validasi surname sesuai pola alfabet pada modul.
function validateName(&$errors, $field_list, $field_name) {
    $pattern = "/^[a-zA-Z'-]+$/";

    if (!isset($field_list[$field_name]) || trim($field_list[$field_name]) === '') {
        $errors[$field_name] = 'required';
    } elseif (!preg_match($pattern, $field_list[$field_name])) {
        $errors[$field_name] = 'invalid';
    }
}

// Validasi tambahan untuk memenuhi langkah terakhir modul.
function validateOtherFields(&$errors, $field_list) {
    if (!isset($field_list['email']) || trim($field_list['email']) === '') {
        $errors['email'] = 'required';
    } elseif (!filter_var(trim($field_list['email']), FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'invalid';
    }

    if (!isset($field_list['password']) || $field_list['password'] === '') {
        $errors['password'] = 'required';
    } elseif (strlen($field_list['password']) < 8) {
        $errors['password'] = 'minimum 8 characters';
    }

    if (!isset($field_list['address']) || trim($field_list['address']) === '') {
        $errors['address'] = 'required';
    }

    $states = ['Queensland', 'New South Wales', 'Victoria', 'Tasmania', 'Western Australia'];
    if (!isset($field_list['state']) || !in_array($field_list['state'], $states, true)) {
        $errors['state'] = 'required or invalid';
    }

    if (!isset($field_list['gender']) || !in_array($field_list['gender'], ['Male', 'Female'], true)) {
        $errors['gender'] = 'required';
    }
}
?>
