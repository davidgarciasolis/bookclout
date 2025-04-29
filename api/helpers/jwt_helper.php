<?php
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

require_once "../vendor/autoload.php";

$jwt_key = "wofziJxyjra9howxub";  // Clave para firmar y verificar JWT

function generarJWT($userId) {
    global $jwt_key;
    $payload = [
        "userId" => $userId,
        "iat" => time(),
        "exp" => time() + (60 * 60)  // 1 hora
    ];
    return JWT::encode($payload, $jwt_key, 'HS256');
}

function validarJWT($jwt) {
    global $jwt_key;
    try {
        return JWT::decode($jwt, new Key($jwt_key, 'HS256'));
    } catch (Exception $e) {
        return null;
    }
}
?>
