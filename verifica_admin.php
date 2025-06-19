<?php
header('Access-Control-Allow-Origin: http://localhost:4200');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require 'database.php';
header('Content-Type: application/json');

// Leer cookie en lugar de sesión
if (isset($_COOKIE['id'])) {
    $id = $_COOKIE['id'];

    $stmt = $pdo->prepare("SELECT tipoUsuario FROM Usuario WHERE id = :id AND estatus = 1");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario && $usuario['tipoUsuario'] === 'Administrador') {
        echo json_encode(['admin' => true]);
    } else {
        echo json_encode(['admin' => false]);
    }
} else {
    echo json_encode(['admin' => false, 'error' => 'No se encontró la cookie id']);
}