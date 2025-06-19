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

// Leer cookie
if (isset($_COOKIE['id'])) {
    $id = $_COOKIE['id'];

    $stmt = $pdo->prepare("SELECT fecha, medioPago, total, confirmacion FROM compra WHERE idCliente = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        echo json_encode($usuario);
    } else {
        http_response_code(404);
        echo json_encode(['mensaje' => 'Usuario no encontrado']);
    }
} else {
    http_response_code(400);
    echo json_encode(['mensaje' => 'No se encontró la cookie idUsuario']);
}