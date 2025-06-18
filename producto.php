<?php
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