<?php
require_once __DIR__ . '/database.php';
/** @var PDO $pdo */
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header('Content-Type: application/json');

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        $stmt = $pdo->prepare("SELECT * FROM Usuario WHERE estatus = 1");
        $stmt->execute();
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        $stmt = $pdo->prepare("INSERT INTO Usuario (nombre, apellidoPaterno, apellidoMaterno, correo, contrasena, tipoUsuario, nombreUsuario) 
                               VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['nombre'], $data['apellidoPaterno'], $data['apellidoMaterno'], $data['correo'],
            $data['contrasena'], $data['tipoUsuario'], $data['nombreUsuario']
        ]);
        echo json_encode(['id' => $pdo->lastInsertId()]);
        break;

    case 'PUT':
        $data = json_decode(file_get_contents("php://input"), true);
        $stmt = $pdo->prepare("UPDATE Usuario SET nombre=?, apellidoPaterno=?, apellidoMaterno=?, correo=?, contrasena=?, tipoUsuario=?, nombreUsuario=? WHERE id=? AND estatus=1");
        $stmt->execute([
            $data['nombre'], $data['apellidoPaterno'], $data['apellidoMaterno'], $data['correo'],
            $data['contrasena'], $data['tipoUsuario'], $data['nombreUsuario'], $data['id']
        ]);
        echo json_encode(['status' => 'actualizado']);
        break;

    case 'DELETE':
        $id = isset($_GET['id']) ? $_GET['id'] : 0;
        $stmt = $pdo->prepare("UPDATE Usuario SET estatus=0, fechaBaja=NOW() WHERE id=?");
        $stmt->execute([$id]);
        echo json_encode(['status' => 'desactivado']);
        break;
}
?>
