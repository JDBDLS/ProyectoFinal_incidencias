<?php

header('Content-Type: application/json');

require_once '../includes/db.php';

if (isset($_GET['municipio_id'])) {
    $municipio_id = $_GET['municipio_id'];
    
    
    $stmt = $conexion->prepare("SELECT id, nombre FROM barrios WHERE municipio_id = ? ORDER BY nombre ASC");
    $stmt->bind_param("i", $municipio_id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    
    $barrios = $resultado->fetch_all(MYSQLI_ASSOC);
    
    
    echo json_encode($barrios, JSON_UNESCAPED_UNICODE);
    
    $stmt->close();
} else {
    
    echo json_encode([]);
}

$conexion->close();
?>
