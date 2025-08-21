<?php


header('Content-Type: application/json');


require_once '../includes/db.php';


if (isset($_GET['provincia_id'])) {
    $provincia_id = $_GET['provincia_id'];
    
    
    $stmt = $conexion->prepare("SELECT id, nombre FROM municipios WHERE provincia_id = ? ORDER BY nombre ASC");
    $stmt->bind_param("i", $provincia_id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    
    $municipios = $resultado->fetch_all(MYSQLI_ASSOC);
    
    
    echo json_encode($municipios, JSON_UNESCAPED_UNICODE);
    
    $stmt->close();
} else {
    
    echo json_encode([]);
}

$conexion->close();
?>
