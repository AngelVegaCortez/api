<?php
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
