<?php
require_once __DIR__ . '/database.php';
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST");
header('Content-Type: application/json');

switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);

        $idCompra = $data['idCompra'];
        $idProducto = $data['idProducto'];
        $cantidad = $data['cantidad'];
        $precioUnitario = $data['precioUnitario'];
        $subtotal = $data['subtotal'];

        $stmt = $pdo->prepare("INSERT INTO DetalleCompra (idCompra, idProducto, cantidad, precioUnitario, subtotal) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$idCompra, $idProducto, $cantidad, $precioUnitario, $subtotal]);

        echo json_encode(['status' => 'ok']);
        break;
}
?>