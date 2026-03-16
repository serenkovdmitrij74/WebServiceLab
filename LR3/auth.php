<?php
require_once 'db.php';

function isAuthorized() {
    return isset($_SESSION['user_id']);
}


function getCurrentUser($pdo) {
    if (!isAuthorized()) {
        return null;
    }
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetch();
}


function validatePassword($password) {
    $errors = [];
    
    if (strlen($password) <= 6) 
        $errors[] = 'Пароль должен быть длиннее 6 символов';
    if (!preg_match('/[A-Z]/', $password))
        $errors[] = 'Пароль должен содержать заглавные латинские буквы';
    if (!preg_match('/[a-z]/', $password))
        $errors[] = 'Пароль должен содержать строчные латинские буквы';
    if (!preg_match('/[0-9]/', $password))
        $errors[] = 'Пароль должен содержать цифры';
    if (!preg_match('/[!@#$%^&*(),.?":{}|<>~`\-=_+\[\]\\\\\/]/', $password))
        $errors[] = 'Пароль должен содержать спецсимволы';
    if (strpos($password, ' ') === false)
        $errors[] = 'Пароль должен содержать пробел';
    if (strpos($password, '-') === false)
        $errors[] = 'Пароль должен содержать дефис (-)';
    if (strpos($password, '_') === false)
        $errors[] = 'Пароль должен содержать подчеркивание (_)';
    if (preg_match('/[^\x20-\x7E]/', $password))
        $errors[] = 'Пароль должен содержать только латинские символы (ASCII)';
    return $errors;
}


function redirect($url) {
    header("Location: $url");
    exit;
}