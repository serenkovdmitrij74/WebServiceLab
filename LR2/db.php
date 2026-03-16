<?php

$host = 'localhost';
$db   = 'tsj_service';
$user = 'root';
$pass = '';

$dsn = "mysql:host=$host;dbname=$db";

try {
    $pdo = new PDO($dsn, $user, $pass);
} catch (\PDOException $e) {
    die('Ошибка подключения к БД: ' . $e->getMessage());
}
?>  
