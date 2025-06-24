<?php
use OpenApi\Annotations as OA;

/**
 * @OA\PathItem(
 *     path="/obtener_usuario.php",
 *
 *     @OA\Get(
 *         summary="Obtener información del usuario autenticado",
 *         description="Devuelve los datos del usuario actual en sesión.",
 *         tags={"Usuarios"},
 *         @OA\Response(
 *             response=200,
 *             description="Datos del usuario",
 *             @OA\JsonContent(
 *                 @OA\Property(property="idUsuario", type="integer", example=1),
 *                 @OA\Property(property="nombre", type="string", example="Juan Pérez"),
 *                 @OA\Property(property="correo", type="string", example="usuario@correo.com")
 *             )
 *         ),
 *         @OA\Response(
 *             response=401,
 *             description="Usuario no autenticado",
 *             @OA\JsonContent(
 *                 @OA\Property(property="status", type="string", example="error"),
 *                 @OA\Property(property="message", type="string", example="Usuario no autenticado")
 *             )
 *         ),
 *         @OA\Response(
 *             response=400,
 *             description="Falta la cookie idUsuario",
 *             @OA\JsonContent(
 *                 @OA\Property(property="mensaje", type="string", example="No se encontró la cookie idUsuario")
 *             )
 *         )
 *     )
 * )
 */


header('Access-Control-Allow-Origin: http://localhost:4200');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require 'database.php';
header('Content-Type: application/json');

// Leer cookie
if (isset($_COOKIE['idUsuario'])) {
    $id = $_COOKIE['idUsuario'];

    $stmt = $pdo->prepare("SELECT * FROM Usuario WHERE id = :id AND estatus = 1");
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
