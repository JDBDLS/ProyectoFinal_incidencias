<?php

require_once 'includes/db.php';

session_start();

$input = file_get_contents("php://input");
$data = json_decode($input, true);

if (!isset($data['token'])) {
    http_response_code(400); 
    echo json_encode(['success' => false, 'message' => 'Token de Google no recibido.']);
    exit();
}

// Aquí necesitarías una biblioteca para verificar el token de Google.
// Por ejemplo, la biblioteca de cliente de Google para PHP.
// Por ahora, para este ejemplo, simularemos la verificación del token.
// En un entorno de producción, DEBES verificar el token de forma segura.
//
// $client = new Google_Client(['client_id' => 'TU_GOOGLE_CLIENT_ID']);
// $payload = $client->verifyIdToken($data['token']);
// if (!$payload) {
//     // Token inválido
//     http_response_code(401);
//     echo json_encode(['success' => false, 'message' => 'Token de Google inválido.']);
//     exit();
// }
//
// $email = $payload['email'];
// $nombre = $payload['name'];


$id_token_parts = explode('.', $data['token']);
if (count($id_token_parts) !== 3) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Formato de token de Google inválido.']);
    exit();
}
$payload = json_decode(base64_decode(str_replace(['-', '_'], ['+', '/'], $id_token_parts[1])), true);
$email = $payload['email'];
$nombre = $payload['name'];

$stmt_check = $conexion->prepare("SELECT id, nombre, rol FROM usuarios WHERE email = ?");
$stmt_check->bind_param("s", $email);
$stmt_check->execute();
$resultado = $stmt_check->get_result();

if ($resultado->num_rows > 0) {
    
    $usuario = $resultado->fetch_assoc();
    $_SESSION['user_id'] = $usuario['id'];
    $_SESSION['user_name'] = $usuario['nombre'];
    $_SESSION['user_role'] = $usuario['rol'];
} else {
    
    $stmt_insert = $conexion->prepare("INSERT INTO usuarios (nombre, email, rol) VALUES (?, ?, ?)");
    $rol = 'reportero';
    $stmt_insert->bind_param("sss", $nombre, $email, $rol);
    if ($stmt_insert->execute()) {
        $nuevo_id = $conexion->insert_id;
        $_SESSION['user_id'] = $nuevo_id;
        $_SESSION['user_name'] = $nombre;
        $_SESSION['user_role'] = $rol;
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Error al registrar el usuario.']);
        exit();
    }
    $stmt_insert->close();
}

$stmt_check->close();

echo json_encode(['success' => true]);
?>