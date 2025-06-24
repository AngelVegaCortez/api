<?php
use OpenApi\Annotations as OA;

/**
 * @OA\PathItem(
 *     path="/obtener_compras.php",
 *
 *     @OA\Get(
 *         summary="Listar compras",
 *         description="Devuelve un listado de compras registradas en el sistema.",
 *         tags={"Compras"},
 *         @OA\Response(
 *             response=200,
 *             description="Listado de compras",
 *             @OA\JsonContent(
 *                 type="array",
 *                 @OA\Items(
 *                     @OA\Property(property="idCompra", type="integer", example=1),
 *                     @OA\Property(property="fecha", type="string", format="date", example="2025-06-24"),
 *                     @OA\Property(property="total", type="number", example=123.45),
 *                     @OA\Property(property="medioPago", type="string", example="Efectivo"),
 *                     @OA\Property(property="confirmacion", type="boolean", example=true)
 *                 )
 *             )
 *         ),
 *         @OA\Response(
 *             response=404,
 *             description="Usuario no encontrado"
 *         ),
 *         @OA\Response(
 *             response=400,
 *             description="Falta cookie"
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