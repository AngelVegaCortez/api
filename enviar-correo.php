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
 *         path="/enviar-correo.php",
 *         @OA\Post(
 *             summary="Enviar correo electrónico",
 *             description="Envía un correo electrónico con nombre, correo y mensaje desde el formulario de contacto.",
 *             tags={"Notificaciones"},
 *             @OA\RequestBody(
 *                 required=true,
 *                 @OA\JsonContent(
 *                     required={"nombre", "correo", "mensaje"},
 *                     @OA\Property(property="nombre", type="string", example="Carlos Torres"),
 *                     @OA\Property(property="correo", type="string", format="email", example="cliente@correo.com"),
 *                     @OA\Property(property="mensaje", type="string", example="Gracias por el pollo al ajillo")
 *                 )
 *             ),
 *             @OA\Response(
 *                 response=200,
 *                 description="Correo enviado",
 *                 @OA\JsonContent(
 *                     @OA\Property(property="status", type="string", example="ok")
 *                 )
 *             )
 *         )
 *     )
 * )
 */


$destino = "teamtrucha69@gmail.com";

$data = json_decode(file_get_contents("php://input"), true);

$nombre = $data["nombre"] ?? "Sin nombre";
$correo = $data["correo"] ?? "Sin correo";
$mensaje = $data["mensaje"] ?? "";

$asunto = "Mensaje desde formulario de contacto";
$cuerpo = "Nombre: $nombre\nCorreo: $correo\nMensaje:\n$mensaje";

$enviado = mail($destino, $asunto, $cuerpo);

echo json_encode(['status' => $enviado ? 'ok' : 'error']);

?>