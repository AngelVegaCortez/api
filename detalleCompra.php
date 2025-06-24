<?php
use OpenApi\Annotations as OA;

/**
 * @OA\PathItem(path="/detalleCompra.php")
 */

/**
 * @OA\Get(
 *     path="/detalleCompra.php",
 *     summary="Obtener todos los detalles de compra",
 *     tags={"DetalleCompra"},
 *     @OA\Response(
 *         response=200,
 *         description="Listado de detalles de compra",
 *         @OA\JsonContent(type="array", @OA\Items(
 *             @OA\Property(property="idCompra", type="integer", example=42),
 *             @OA\Property(property="idProducto", type="integer", example=2),
 *             @OA\Property(property="cantidad", type="number", format="float", example=5.5),
 *             @OA\Property(property="precioUnitario", type="number", format="float", example=20.00),
 *             @OA\Property(property="subtotal", type="number", format="float", example=110.00)
 *         ))
 *     )
 * )
 */

/**
 * @OA\Post(
 *     path="/detalleCompra.php",
 *     summary="Agregar detalle de compra",
 *     tags={"DetalleCompra"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"idCompra", "idProducto", "cantidad", "precioUnitario", "subtotal"},
 *             @OA\Property(property="idCompra", type="integer", example=42),
 *             @OA\Property(property="idProducto", type="integer", example=2),
 *             @OA\Property(property="cantidad", type="number", format="float", example=5.5),
 *             @OA\Property(property="precioUnitario", type="number", format="float", example=20.00),
 *             @OA\Property(property="subtotal", type="number", format="float", example=110.00)
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Detalle registrado correctamente",
 *         @OA\JsonContent(@OA\Property(property="status", type="string", example="ok"))
 *     )
 * )
 */

/**
 * @OA\Put(
 *     path="/detalleCompra.php",
 *     summary="Actualizar un detalle de compra",
 *     tags={"DetalleCompra"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"idCompra", "idProducto", "cantidad", "precioUnitario", "subtotal"},
 *             @OA\Property(property="idCompra", type="integer", example=42),
 *             @OA\Property(property="idProducto", type="integer", example=2),
 *             @OA\Property(property="cantidad", type="number", format="float", example=6.0),
 *             @OA\Property(property="precioUnitario", type="number", format="float", example=22.00),
 *             @OA\Property(property="subtotal", type="number", format="float", example=132.00)
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Detalle actualizado",
 *         @OA\JsonContent(@OA\Property(property="status", type="string", example="actualizado"))
 *     )
 * )
 */

/**
 * @OA\Delete(
 *     path="/detalleCompra.php",
 *     summary="Eliminar un detalle de compra",
 *     tags={"DetalleCompra"},
 *     @OA\Parameter(
 *         name="idCompra",
 *         in="query",
 *         required=true,
 *         description="ID de la compra",
 *         @OA\Schema(type="integer", example=42)
 *     ),
 *     @OA\Parameter(
 *         name="idProducto",
 *         in="query",
 *         required=true,
 *         description="ID del producto",
 *         @OA\Schema(type="integer", example=2)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Detalle eliminado",
 *         @OA\JsonContent(@OA\Property(property="status", type="string", example="eliminado"))
 *     )
 * )
 */

require_once __DIR__ . '/database.php';
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        $stmt = $pdo->query("SELECT * FROM DetalleCompra");
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        $stmt = $pdo->prepare("INSERT INTO DetalleCompra (idCompra, idProducto, cantidad, precioUnitario, subtotal) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['idCompra'], $data['idProducto'], $data['cantidad'],
            $data['precioUnitario'], $data['subtotal']
        ]);
        http_response_code(201);
        echo json_encode(['status' => 'ok']);
        break;

    case 'PUT':
        $data = json_decode(file_get_contents("php://input"), true);
        $stmt = $pdo->prepare("UPDATE DetalleCompra SET cantidad=?, precioUnitario=?, subtotal=? WHERE idCompra=? AND idProducto=?");
        $stmt->execute([
            $data['cantidad'], $data['precioUnitario'], $data['subtotal'],
            $data['idCompra'], $data['idProducto']
        ]);
        echo json_encode(['status' => 'actualizado']);
        break;

    case 'DELETE':
        $idCompra = $_GET['idCompra'] ?? 0;
        $idProducto = $_GET['idProducto'] ?? 0;
        $stmt = $pdo->prepare("DELETE FROM DetalleCompra WHERE idCompra=? AND idProducto=?");
        $stmt->execute([$idCompra, $idProducto]);
        echo json_encode(['status' => 'eliminado']);
        break;
}
?>
