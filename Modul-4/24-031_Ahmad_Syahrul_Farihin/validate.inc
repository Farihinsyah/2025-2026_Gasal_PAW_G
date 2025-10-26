<?php
function validateSurname($field_list, $field_name, &$errors)
{
    if (!isset($field_list[$field_name]) || trim($field_list[$field_name]) === '') {
        $errors[$field_name] = "Field '$field_name' harus diisi.";
        return false;
    }

    $pattern = "/^[a-zA-Z'-]+$/"; // hanya huruf, tanda ' dan -
    if (!preg_match($pattern, $field_list[$field_name])) {
        $errors[$field_name] = "Field '$field_name' hanya boleh berisi huruf dan tanda (' atau -).";
        return false;
    }

    return true;
}

function validateUmur($field_list, $field_name, &$errors)
{
    if (!isset($field_list[$field_name]) || trim($field_list[$field_name]) === '') {
        $errors[$field_name] = "Field '$field_name' harus diisi.";
        return false;
    }

    if (!is_numeric($field_list[$field_name]) || $field_list[$field_name] <= 0) {
        $errors[$field_name] = "Field '$field_name' harus berupa angka positif.";
        return false;
    }

    return true;
}

function validateEmail($field_list, $field_name, &$errors)
{
    if (!isset($field_list[$field_name]) || trim($field_list[$field_name]) === '') {
        $errors[$field_name] = "Field '$field_name' harus diisi.";
        return false;
    }

    $email = trim($field_list[$field_name]);

    // Validasi harus mengandung '@' dan '.'
    if (strpos($email, '@') === false || strpos($email, '.') === false) {
        $errors[$field_name] = "Format email tidak valid (harus mengandung '@' dan '.').";
        return false;
    }

    // validasi agar '@' tidak di awal/akhir
    if (strpos($email, '@') === 0 || strrpos($email, '.') < strpos($email, '@')) {
        $errors[$field_name] = "Format email tidak valid.";
        return false;
    }

    return true;
}
?>