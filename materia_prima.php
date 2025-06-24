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
 *         path="/materia_prima.php",
 *
 *         @OA\Get(
 *             summary="Listar materia prima",
 *             description="Devuelve un listado de todas las materias primas registradas.",
 *             tags={"Inventario"},
 *             @OA\Response(
 *                 response=200,
 *                 description="Listado de materias primas",
 *                 @OA\JsonContent(
 *                     type="array",
 *                     @OA\Items(
 *                         @OA\Property(property="idMateriaPrima", type="integer", example=1),
 *                         @OA\Property(property="nombre", type="string", example="Pollo entero"),
 *                         @OA\Property(property="cantidad", type="number", example=20),
 *                         @OA\Property(property="unidadMedida", type="string", example="kg")
 *                     )
 *                 )
 *             )
 *         ),

 *         @OA\Post(
 *             summary="Registrar nueva materia prima",
 *             tags={"Inventario"},
 *             @OA\RequestBody(
 *                 required=true,
 *                 @OA\JsonContent(
 *                     required={"nombre", "cantidad", "cantidadMax", "umbral", "unidadMedida", "idAdministrador"},
 *                     @OA\Property(property="nombre", type="string", example="Muslo de pollo"),
 *                     @OA\Property(property="cantidad", type="number", example=10),
 *                     @OA\Property(property="cantidadMax", type="number", example=50),
 *                     @OA\Property(property="umbral", type="number", example=5),
 *                     @OA\Property(property="unidadMedida", type="string", example="kg"),
 *                     @OA\Property(property="idAdministrador", type="integer", example=1)
 *                 )
 *             ),
 *             @OA\Response(
 *                 response=200,
 *                 description="Materia prima registrada",
 *                 @OA\JsonContent(@OA\Property(property="id", type="integer", example=99))
 *             )
 *         ),

 *         @OA\Put(
 *             summary="Actualizar materia prima",
 *             tags={"Inventario"},
 *             @OA\RequestBody(
 *                 required=true,
 *                 @OA\JsonContent(
 *                     required={"id", "nombre", "cantidad", "cantidadMax", "umbral", "unidadMedida", "idAdministrador"},
 *                     @OA\Property(property="id", type="integer", example=99),
 *                     @OA\Property(property="nombre", type="string"),
 *                     @OA\Property(property="cantidad", type="number"),
 *                     @OA\Property(property="cantidadMax", type="number"),
 *                     @OA\Property(property="umbral", type="number"),
 *                     @OA\Property(property="unidadMedida", type="string"),
 *                     @OA\Property(property="idAdministrador", type="integer")
 *                 )
 *             ),
 *             @OA\Response(
 *                 response=200,
 *                 description="Materia prima actualizada",
 *                 @OA\JsonContent(@OA\Property(property="status", type="string", example="actualizado"))
 *             )
 *         ),

 *         @OA\Delete(
 *             summary="Eliminar materia prima",
 *             tags={"Inventario"},
 *             @OA\Parameter(
 *                 name="id",
 *                 in="query",
 *                 required=true,
 *                 description="ID de la materia prima a eliminar",
 *                 @OA\Schema(type="integer")
 *             ),
 *             @OA\Response(
 *                 response=200,
 *                 description="Materia prima eliminada",
 *                 @OA\JsonContent(@OA\Property(property="status", type="string", example="eliminado"))
 *             )
 *         )
 *     )
 * )
 */


require 'database.php';
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header('Content-Type: application/json');

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        $stmt = $pdo->prepare("SELECT * FROM MateriaPrima");
        $stmt->execute();
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        $stmt = $pdo->prepare("INSERT INTO MateriaPrima (nombre, cantidad, cantidadMax, umbral, unidadMedida, idAdministrador) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['nombre'],
            $data['cantidad'],
            $data['cantidadMax'],
            $data['umbral'],
            $data['unidadMedida'],
            $data['idAdministrador']
        ]);
        echo json_encode(['id' => $pdo->lastInsertId()]);
        break;

    case 'PUT':
        $data = json_decode(file_get_contents("php://input"), true);
        $stmt = $pdo->prepare("UPDATE MateriaPrima SET nombre=?, cantidad=?, cantidadMax=?, umbral=?, unidadMedida=?, idAdministrador=? WHERE id=?");
        $stmt->execute([
            $data['nombre'],
            $data['cantidad'],
            $data['cantidadMax'],
            $data['umbral'],
            $data['unidadMedida'],
            $data['idAdministrador'],
            $data['id']
        ]);
        echo json_encode(['status' => 'actualizado']);
        break;

    case 'DELETE':
        $id = $_GET['id'] ?? 0;
        $stmt = $pdo->prepare("DELETE FROM MateriaPrima WHERE id=?");
        $stmt->execute([$id]);
        echo json_encode(['status' => 'eliminado']);
        break;
}
?>