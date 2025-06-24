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
 *         path="/verificar_sesion.php",
 *         @OA\Get(
 *             summary="Verificar si hay sesión iniciada",
 *             description="Devuelve si el usuario tiene una sesión activa mediante `\$_SESSION['usuario']`.",
 *             @OA\Response(
 *                 response=200,
 *                 description="Sesión activa",
 *                 @OA\JsonContent(
 *                     @OA\Property(property="autenticado", type="boolean"),
 *                     @OA\Property(property="id", type="integer", nullable=true)
 *                 )
 *             ),
 *             @OA\Response(
 *                 response=401,
 *                 description="Sesión no activa",
 *                 @OA\JsonContent(
 *                     @OA\Property(property="autenticado", type="boolean")
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
header('Content-Type: application/json');

if (isset($_SESSION['usuario'])) {
    echo json_encode(['autenticado' => true, 'id' => $_SESSION['usuario']]);
} else {
    http_response_code(401);
    echo json_encode(['autenticado' => false]);
}