<?php
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