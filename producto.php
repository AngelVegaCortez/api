<?php
use OpenApi\Annotations as OA;

/**
 * @OA\PathItem(
 *     path="/producto.php",

 *     @OA\Get(
 *         summary="Listar productos",
 *         description="Obtiene productos activos. Si se pasa `accion=promocion` por query, devuelve solo productos en promoción.",
 *         tags={"Productos"},
 *         @OA\Parameter(
 *             name="accion",
 *             in="query",
 *             required=false,
 *             description="Filtrar productos por promociones (valor: 'promocion')",
 *             @OA\Schema(type="string")
 *         ),
 *         @OA\Response(response=200, description="Lista de productos")
 *     ),

 *     @OA\Post(
 *         summary="Crear nuevo producto",
 *         description="Registra un nuevo producto en el sistema.",
 *         tags={"Productos"},
 *         @OA\RequestBody(
 *             required=true,
 *             @OA\JsonContent(
 *                 required={"nombre", "descripcion", "stock", "precio", "descuento", "categoria", "idAdministrador"},
 *                 @OA\Property(property="nombre", type="string"),
 *                 @OA\Property(property="descripcion", type="string"),
 *                 @OA\Property(property="stock", type="integer"),
 *                 @OA\Property(property="precio", type="number", format="float"),
 *                 @OA\Property(property="descuento", type="number", format="float"),
 *                 @OA\Property(property="categoria", type="string"),
 *                 @OA\Property(property="idAdministrador", type="integer")
 *             )
 *         ),
 *         @OA\Response(response=200, description="Producto creado exitosamente con ID")
 *     ),

 *     @OA\Put(
 *         summary="Actualizar producto",
 *         description="Actualiza un producto existente si está activo.",
 *         tags={"Productos"},
 *         @OA\RequestBody(
 *             required=true,
 *             @OA\JsonContent(
 *                 required={"id", "nombre", "descripcion", "stock", "precio", "descuento", "categoria", "idAdministrador"},
 *                 @OA\Property(property="id", type="integer"),
 *                 @OA\Property(property="nombre", type="string"),
 *                 @OA\Property(property="descripcion", type="string"),
 *                 @OA\Property(property="stock", type="integer"),
 *                 @OA\Property(property="precio", type="number", format="float"),
 *                 @OA\Property(property="descuento", type="number", format="float"),
 *                 @OA\Property(property="categoria", type="string"),
 *                 @OA\Property(property="idAdministrador", type="integer")
 *             )
 *         ),
 *         @OA\Response(response=200, description="Producto actualizado correctamente")
 *     ),

 *     @OA\Delete(
 *         summary="Desactivar producto",
 *         description="Desactiva un producto mediante su ID (soft delete).",
 *         tags={"Productos"},
 *         @OA\Parameter(
 *             name="id",
 *             in="query",
 *             required=true,
 *             description="ID del producto a desactivar",
 *             @OA\Schema(type="integer")
 *         ),
 *         @OA\Response(response=200, description="Producto desactivado correctamente")
 *     )
 * )
 */


require_once __DIR__ . '/database.php';
/** @var PDO $pdo */
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header('Content-Type: application/json');

$accion = isset($_GET['accion']) ? $_GET['accion'] : 'todos';

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        if ($accion == 'promocion') {
            $stmt = $pdo->prepare("SELECT * FROM Producto WHERE descuento > 0 AND activo = 1");
        } else {
            $stmt = $pdo->prepare("SELECT * FROM Producto WHERE activo = 1");
        }
        $stmt->execute();
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        $stmt = $pdo->prepare("INSERT INTO Producto (nombre, descripcion, stock, precio, descuento, categoria, idAdministrador) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['nombre'], $data['descripcion'], $data['stock'], $data['precio'],
            $data['descuento'], $data['categoria'], $data['idAdministrador']
        ]);
        echo json_encode(['id' => $pdo->lastInsertId()]);
        break;

    case 'PUT':
        $data = json_decode(file_get_contents("php://input"), true);
        $stmt = $pdo->prepare("UPDATE Producto SET nombre=?, descripcion=?, stock=?, precio=?, descuento=?, categoria=?, idAdministrador=? WHERE id=? AND activo=1");
        $stmt->execute([
            $data['nombre'], $data['descripcion'], $data['stock'], $data['precio'],
            $data['descuento'], $data['categoria'], $data['idAdministrador'], $data['id']
        ]);
        echo json_encode(['status' => 'actualizado']);
        break;

    case 'DELETE':
        $id = $_GET['id'] ?? 0;
        $stmt = $pdo->prepare("UPDATE Producto SET activo=0 WHERE id=?");
        $stmt->execute([$id]);
        echo json_encode(['status' => 'desactivado']);
        break;
}
?>