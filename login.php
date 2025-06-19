<?php
// Cabeceras CORS
header('Access-Control-Allow-Origin: http://localhost:4200');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Responder a preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Iniciar sesión y cargar conexión
session_start();
require 'database.php';

header('Content-Type: application/json');

// Recibir datos
$data = json_decode(file_get_contents("php://input"), true);
$correo = $data['correo'] ?? '';
$contrasena = $data['contrasena'] ?? '';

// Buscar usuario por correo
$sql = "SELECT id, nombre, tipoUsuario, contrasena, correo FROM Usuario WHERE correo = :correo";
$stmt = $pdo->prepare($sql);
$stmt->execute([':correo' => $correo]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

// Verificar contraseña (¡Recuerda encriptarla en producción!)
if ($usuario && $contrasena === $usuario['contrasena']) {
    $_SESSION['usuario'] = $usuario['id'];

    setcookie("id", $usuario['id'], time() + (3600), "/"); // 30 días

    // Devuelve todos los datos necesarios
    echo json_encode([
        'success' => true,
        'usuario' => [
            'id' => $usuario['id'],
            'nombre' => $usuario['nombre'],
            'tipoUsuario' => $usuario['tipoUsuario'],
            'correo' => $usuario['correo']
        ]
    ]);
} else {
    http_response_code(401);
    echo json_encode(['success' => false, 'mensaje' => 'Correo o contraseña incorrectos.']);
}
?>