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
 *         path="/logout.php",
 *         @OA\Post(
 *             summary="Cerrar sesión",
 *             description="Destruye la sesión activa del usuario.",
 *             tags={"Autenticación"},
 *             @OA\Response(
 *                 response=200,
 *                 description="Sesión cerrada correctamente",
 *                 @OA\JsonContent(
 *                     @OA\Property(property="cerrado", type="boolean", example=true)
 *                 )
 *             )
 *         )
 *     )
 * )
 */


header('Access-Control-Allow-Origin: http://localhost:4200');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

session_start();
session_unset();
session_destroy();

setcookie("id", "", time() - 3600, "/");
unset($_COOKIE['id']);

header('Content-Type: application/json');
echo json_encode(['cerrado' => true]);
