<?php
use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="API PorSuPollo",
 *     version="1.0.0",
 *     description="API para gestionar usuarios"
 * )
 */

/**
 * @OA\Get(
 *     path="/usuario.php",
 *     summary="Obtener lista de usuarios",
 *     tags={"Usuario"},
 *     @OA\Response(
 *         response=200,
 *         description="Lista de usuarios",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="usuarios",
 *                 type="array",
 *                 @OA\Items(type="object")
 *             )
 *         )
 *     )
 * )
 */

/**
 * @OA\Post(
 *     path="/usuario.php",
 *     summary="Registrar nuevo usuario",
 *     tags={"Usuario"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"nombre", "correo", "password"},
 *             @OA\Property(property="nombre", type="string", example="Juan Perez"),
 *             @OA\Property(property="correo", type="string", format="email", example="juan@ejemplo.com"),
 *             @OA\Property(property="password", type="string", format="password", example="123456")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Usuario registrado exitosamente",
 *         @OA\JsonContent(
 *             @OA\Property(property="mensaje", type="string", example="Usuario registrado")
 *         )
 *     )
 * )
 */



require_once __DIR__ . '/database.php';
/** @var PDO $pdo */
header("Access-Control-Allow-Origin: http://localhost:4200");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        $stmt = $pdo->prepare("SELECT * FROM Usuario WHERE estatus = 1");
        $stmt->execute();
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        break;

    case 'POST':
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 0) {
            $permitidos = ['image/jpeg', 'image/png', 'image/webp'];
            $tipoArchivo = mime_content_type($_FILES['foto']['tmp_name']);

            if (in_array($tipoArchivo, $permitidos)) {
                $extension = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
                $nuevoNombre = uniqid('perfil_') . '.' . $extension;
                $ruta = 'uploads/' . $nuevoNombre;

                if (!is_dir('uploads')) {
                    mkdir('uploads', 0777, true);
                }

                if (move_uploaded_file($_FILES['foto']['tmp_name'], $ruta)) {
                    // Subido correctamente
                } else {
                    http_response_code(500);
                    echo json_encode(['error' => 'Error al mover el archivo.']);
                    exit;
                }
            } else {
                http_response_code(400);
                echo json_encode(['error' => 'Formato de imagen no permitido.']);
                exit;
            }
        } else {
            $ruta = null; // No se envió archivo
        }

        $stmt = $pdo->prepare("INSERT INTO Usuario (nombre, apellidoPaterno, apellidoMaterno, correo, contrasena, tipoUsuario, nombreUsuario, fotoPerfil) 
                           VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $_POST['nombre'], $_POST['apellidoPaterno'], $_POST['apellidoMaterno'], $_POST['correo'],
            $_POST['contrasena'], $_POST['tipoUsuario'], $_POST['nombreUsuario'], $ruta

        ]);
        $idInsertado = $pdo->lastInsertId();
        setcookie('idUsuario', $idInsertado, time() + 3600, "/", "localhost", false, true); // cuidado: debe ser antes de echo
        echo json_encode(['id' => $idInsertado]);
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
