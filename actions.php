<?php

require_once 'includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id']) && isset($_POST['accion'])) {
    
    $incidencia_id = (int)$_POST['id'];
    $accion = $_POST['accion']; 
    
    $estado_nuevo = null;
    if ($accion === 'aprobar') {
        $estado_nuevo = 'aprobado';
    } elseif ($accion === 'rechazar') {
        $estado_nuevo = 'rechazado';
    }
    
    if ($estado_nuevo !== null) {
        
        $stmt = $conexion->prepare("UPDATE incidencias SET estado = ? WHERE id = ?");
        
        $stmt->bind_param("si", $estado_nuevo, $incidencia_id);
        
        if ($stmt->execute()) {
            
        } else {
            
            error_log("Error al actualizar la incidencia: " . $stmt->error);
        }
        
        $stmt->close();
    }
}

header("Location: admin.php");
exit();
?>
