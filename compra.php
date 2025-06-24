<?php
/**
 * @OA\Info(
 *     title="API PorSuPollo",
 *     version="1.0.0",
 *     description="CRUD de la tabla Compra"
 * )
 */

/**
 * @OA\Get(
 *     path="/compra.php",
 *     summary="Obtener todas las compras",
 *     tags={"Compras"},
 *     @OA\Response(
 *         response=200,
 *         description="Listado de compras",
 *         @OA\JsonContent(type="array", @OA\Items(
 *             @OA\Property(property="id", type="integer"),
 *             @OA\Property(property="fecha", type="string", format="date-time"),
 *             @OA\Property(property="medioPago", type="string"),
 *             @OA\Property(property="total", type="number"),
 *             @OA\Property(property="idCliente", type="integer")
 *         ))
 *     )
 * )
 */

/**
 * @OA\Post(
 *     path="/compra.php",
 *     summary="Registrar una compra",
 *     tags={"Compras"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"medioPago", "total", "idCliente"},
 *             @OA\Property(property="medioPago", type="string", example="Tarjeta"),
 *             @OA\Property(property="total", type="number", example=123.45),
 *             @OA\Property(property="idCliente", type="integer", example=1)
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Compra registrada",
 *         @OA\JsonContent(@OA\Property(property="idCompra", type="integer", example=1))
 *     )
 * )
 */

/**
 * @OA\Put(
 *     path="/compra.php",
 *     summary="Actualizar una compra",
 *     tags={"Compras"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"id", "medioPago", "total", "confirmacion"},
 *             @OA\Property(property="id", type="integer", example=1),
 *             @OA\Property(property="medioPago", type="string", example="Efectivo"),
 *             @OA\Property(property="total", type="number", example=199.99),
 *             @OA\Property(property="confirmacion", type="boolean", example=true)
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Compra actualizada",
 *         @OA\JsonContent(@OA\Property(property="status", type="string", example="actualizado"))
 *     )
 * )
 */

/**
 * @OA\Delete(
 *     path="/compra.php",
 *     summary="Eliminar una compra",
 *     tags={"Compras"},
 *     @OA\Parameter(
 *         name="id",
 *         in="query",
 *         required=true,
 *         description="ID de la compra a eliminar",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Compra eliminada",
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
        $stmt = $pdo->query("SELECT * FROM Compra");
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        $medioPago = $data['medioPago'] ?? '';
        $total = $data['total'] ?? 0;
        $idCliente = $data['idCliente'] ?? 0;

        $stmt = $pdo->prepare("INSERT INTO Compra (medioPago, total, idCliente) VALUES (?, ?, ?)");
        $stmt->execute([$medioPago, $total, $idCliente]);
        echo json_encode(['idCompra' => $pdo->lastInsertId()]);
        break;

    case 'PUT':
        $data = json_decode(file_get_contents("php://input"), true);
        $stmt = $pdo->prepare("UPDATE Compra SET medioPago=?, total=?, confirmacion=? WHERE id=?");
        $stmt->execute([
            $data['medioPago'],
            $data['total'],
            $data['confirmacion'],
            $data['id']
        ]);
        echo json_encode(['status' => 'actualizado']);
        break;

    case 'DELETE':
        $id = $_GET['id'] ?? 0;
        $stmt = $pdo->prepare("DELETE FROM Compra WHERE id=?");
        $stmt->execute([$id]);
        echo json_encode(['status' => 'eliminado']);
        break;
}
?>