<?php
require_once __DIR__ . '/database.php';
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, POST");
header('Content-Type: application/json');

switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        $medioPago = $data['medioPago'] ?? '';
        $total = $data['total'] ?? 0;
        $idCliente = $data['idCliente'] ?? 0;

        $stmt = $pdo->prepare("INSERT INTO Compra (medioPago, total, idCliente) VALUES (?, ?, ?)");
        $stmt->execute([$medioPago, $total, $idCliente]);

        echo json_encode(['idCompra' => $pdo->lastInsertId()]);
        break;
}
?>