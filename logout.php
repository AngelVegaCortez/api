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
session_unset();
session_destroy();

setcookie("id", "", time() - 3600, "/");
unset($_COOKIE['id']);

header('Content-Type: application/json');
echo json_encode(['cerrado' => true]);
