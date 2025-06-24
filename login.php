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
 *         path="/login.php",
 *         @OA\Post(
 *             summary="Autenticar usuario",
 *             description="Verifica el correo y la contraseña para iniciar sesión. Si las credenciales son correctas, inicia sesión y devuelve información del usuario.",
 *             tags={"Autenticación"},
 *             @OA\RequestBody(
 *                 required=true,
 *                 description="Credenciales de acceso",
 *                 @OA\JsonContent(
 *                     required={"correo", "contrasena"},
 *                     @OA\Property(property="correo", type="string", format="email", example="usuario@correo.com"),
 *                     @OA\Property(property="contrasena", type="string", format="password", example="secreta123")
 *                 )
 *             ),
 *             @OA\Response(
 *                 response=200,
 *                 description="Inicio de sesión exitoso",
 *                 @OA\JsonContent(
 *                     @OA\Property(property="success", type="boolean", example=true),
 *                     @OA\Property(property="usuario", type="object",
 *                         @OA\Property(property="id", type="integer", example=1),
 *                         @OA\Property(property="nombre", type="string", example="Juan Pérez"),
 *                         @OA\Property(property="tipoUsuario", type="string", example="Administrador"),
 *                         @OA\Property(property="correo", type="string", format="email", example="usuario@correo.com")
 *                     )
 *                 )
 *             ),
 *             @OA\Response(
 *                 response=401,
 *                 description="Credenciales incorrectas",
 *                 @OA\JsonContent(
 *                     @OA\Property(property="success", type="boolean", example=false),
 *                     @OA\Property(property="mensaje", type="string", example="Correo o contraseña incorrectos.")
 *                 )
 *             ),
 *             @OA\Response(
 *                 response=400,
 *                 description="Faltan datos",
 *                 @OA\JsonContent(
 *                     @OA\Property(property="success", type="boolean", example=false),
 *                     @OA\Property(property="mensaje", type="string", example="Datos incompletos")
 *                 )
 *             )
 *         )
 *     )
 * )
 */


// Cabeceras CORS
header('Access-Control-Allow-Origin: http://localhost:4200');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Responder a preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Iniciar sesión y cargar conexión
session_start();
require 'database.php';

header('Content-Type: application/json');

// Recibir datos
$data = json_decode(file_get_contents("php://input"), true);
$correo = $data['correo'] ?? '';
$contrasena = $data['contrasena'] ?? '';

// Buscar usuario por correo
$sql = "SELECT id, nombre, tipoUsuario, contrasena, correo FROM Usuario WHERE correo = :correo";
$stmt = $pdo->prepare($sql);
$stmt->execute([':correo' => $correo]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

// Verificar contraseña (¡Recuerda encriptarla en producción!)
if ($usuario && $contrasena === $usuario['contrasena']) {
    $_SESSION['usuario'] = $usuario['id'];

    setcookie("idUsuario", $usuario['id'], time() + 3600, "/", "localhost", false, true);

    // Devuelve todos los datos necesarios
    echo json_encode([
        'success' => true,
        'usuario' => [
            'id' => $usuario['id'],
            'nombre' => $usuario['nombre'],
            'tipoUsuario' => $usuario['tipoUsuario'],
            'correo' => $usuario['correo']
        ]
    ]);
} else {
    http_response_code(401);
    echo json_encode(['success' => false, 'mensaje' => 'Correo o contraseña incorrectos.']);
}
?>