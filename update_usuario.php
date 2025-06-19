<?php
header('Access-Control-Allow-Origin: http://localhost:4200');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require 'database.php';
header('Content-Type: application/json');

if (!isset($_COOKIE['id'])) {
    http_response_code(400);
    echo json_encode(['mensaje' => 'No se encontró la cookie idUsuario']);
    exit;
}

$id = $_COOKIE['id'];
$data = json_decode(file_get_contents("php://input"), true);

// Validación básica
$campos = ['nombre', 'apellidoPaterno', 'apellidoMaterno', 'direccion', 'nombreUsuario'];
foreach ($campos as $campo) {
    if (!isset($data[$campo])) {
        http_response_code(400);
        echo json_encode(['mensaje' => "Falta el campo: $campo"]);
        exit;
    }
}

$stmt = $pdo->prepare("
    UPDATE Usuario 
    SET nombre = :nombre,
        apellidoPaterno = :apellidoPaterno,
        apellidoMaterno = :apellidoMaterno,
        direccion = :direccion,
        nombreUsuario = :nombreUsuario
    WHERE id = :id
");

$exito = $stmt->execute([
    ':nombre' => $data['nombre'],
    ':apellidoPaterno' => $data['apellidoPaterno'],
    ':apellidoMaterno' => $data['apellidoMaterno'],
    ':direccion' => $data['direccion'],
    ':nombreUsuario' => $data['nombreUsuario'],
    ':id' => $id
]);

if ($exito) {
    echo json_encode(['success' => true, 'mensaje' => 'Usuario actualizado']);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'mensaje' => 'Error al actualizar']);
}
