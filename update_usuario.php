<?php
use OpenApi\Annotations as OA;

/**
 * @OA\OpenApi(
 *     @OA\Info(
 *         title="API Pollería PorSuPollo",
 *         version="1.0",
 *         description="Documentación para la API del inventario"
 *     ),
 *     @OA\Server(
 *         url="http://localhost",
 *         description="Servidor local"
 *     ),
 *     @OA\PathItem(
 *         path="/update_usuario.php",
 *         @OA\Post(
 *             summary="Actualizar datos del usuario autenticado",
 *             description="Permite al usuario autenticado (usando cookie `id`) actualizar su nombre, apellidos, dirección y nombre de usuario.",
 *             @OA\RequestBody(
 *                 required=true,
 *                 @OA\JsonContent(
 *                     required={"nombre", "apellidoPaterno", "apellidoMaterno", "direccion", "nombreUsuario"},
 *                     @OA\Property(property="nombre", type="string"),
 *                     @OA\Property(property="apellidoPaterno", type="string"),
 *                     @OA\Property(property="apellidoMaterno", type="string"),
 *                     @OA\Property(property="direccion", type="string"),
 *                     @OA\Property(property="nombreUsuario", type="string")
 *                 )
 *             ),
 *             @OA\Response(response=200, description="Usuario actualizado correctamente"),
 *             @OA\Response(response=400, description="Faltan datos o cookie no encontrada"),
 *             @OA\Response(response=500, description="Error interno al actualizar")
 *         )
 *     )
 * )
 */


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
