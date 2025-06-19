<?php
header('Access-Control-Allow-Origin: http://localhost:4200');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

session_start();
header('Content-Type: application/json');

if (isset($_SESSION['usuario'])) {
    echo json_encode(['autenticado' => true, 'id' => $_SESSION['usuario']]);
} else {
    http_response_code(401);
    echo json_encode(['autenticado' => false]);
}