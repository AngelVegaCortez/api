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
 *         path="/inventario_sugerencia.php",
 *         @OA\Get(
 *             summary="Sugerencias de compra de materia prima",
 *             description="Calcula qué materias primas necesitan ser reabastecidas según el umbral configurado.",
 *             tags={"Inventario"},
 *             @OA\Response(
 *                 response=200,
 *                 description="Listado de sugerencias",
 *                 @OA\JsonContent(
 *                     type="array",
 *                     @OA\Items(
 *                         @OA\Property(property="id", type="integer", example=3),
 *                         @OA\Property(property="nombre", type="string", example="Pechuga de pollo"),
 *                         @OA\Property(property="cantidad", type="number", example=5),
 *                         @OA\Property(property="cantidadMaxima", type="number", example=10),
 *                         @OA\Property(property="umbral", type="number", example=3),
 *                         @OA\Property(property="unidadMedida", type="string", example="kg"),
 *                         @OA\Property(property="estado", type="string", example="Crítico"),
 *                         @OA\Property(property="diasRestantes", type="number", example=2.5)
 *                     )
 *                 )
 *             )
 *         )
 *     )
 * )
 */

ob_clean();
require_once 'database.php'; // esto carga $pdo

header('Access-Control-Allow-Origin: http://localhost:4200');
header('Access-Control-Allow-Credentials: true');
header('Content-Type: application/json; charset=utf-8');

$sql = "SELECT 
            id, 
            nombre, 
            cantidad, 
            cantidadMax AS cantidadMaxima,
            umbral, 
            unidadMedida, 
            consumoPromedioDiario,
            CASE 
                WHEN cantidad <= umbral THEN 'Crítico'
                WHEN cantidad >= cantidadMax * 0.8 THEN 'Suficiente'
                ELSE 'Precaución'
            END AS estado,
            ROUND(cantidad / NULLIF(consumoPromedioDiario, 0), 1) AS diasRestantes
        FROM MateriaPrima";

try {
    $stmt = $pdo->prepare($sql); // usa $pdo, NO $con
    $stmt->execute();
    $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($datos);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error en la consulta: ' . $e->getMessage()]);
}
?>
