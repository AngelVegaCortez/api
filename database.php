<?php
$host = "localhost";
$dbname = "polleria";
$username = "root";
$password = "n0m3l0";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
} catch (PDOException $e) {
    die("Conexión fallida: " . $e->getMessage());
}
?>
