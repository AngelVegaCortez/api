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
 *         path="/verifica_admin.php",
 *         @OA\Get(
 *             summary="Verificar si el usuario es administrador",
 *             description="Verifica mediante la cookie 'id' si el usuario autenticado tiene rol de administrador.",
 *             @OA\Response(
 *                 response=200,
 *                 description="Resultado de la verificación",
 *                 @OA\JsonContent(
 *                     @OA\Property(property="admin", type="boolean"),
 *                     @OA\Property(property="error", type="string", nullable=true)
 *                 )
 *             )
 *         )
 *     )
 * )
 */


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
?>