<?php
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