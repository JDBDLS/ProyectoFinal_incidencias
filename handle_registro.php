<?php

require_once 'includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    if (isset($_POST['nombre']) && isset($_POST['email']) && isset($_POST['password'])) {
        
        $nombre = $_POST['nombre'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        
        $stmt_check_email = $conexion->prepare("SELECT id FROM usuarios WHERE email = ?");
        $stmt_check_email->bind_param("s", $email);
        $stmt_check_email->execute();
        $stmt_check_email->store_result();
        
        if ($stmt_check_email->num_rows > 0) {
            
            echo "<div class='container mx-auto mt-8 p-4 bg-red-100 text-red-800 rounded-lg shadow-lg'>
                    <p class='text-center'>Error: El correo electrónico ya está registrado. Por favor, utiliza otro.</p>
                  </div>";
            exit();
        }
        $stmt_check_email->close();
        
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = $conexion->prepare("INSERT INTO usuarios (nombre, email, password) VALUES (?, ?, ?)");
        
        $stmt->bind_param("sss", $nombre, $email, $password_hash);
        
        if ($stmt->execute()) {
           
            header("Location: login.php");
            exit();
        } else {
            
            error_log("Error al registrar el usuario: " . $stmt->error);
            echo "<div class='container mx-auto mt-8 p-4 bg-red-100 text-red-800 rounded-lg shadow-lg'>
                    <p class='text-center'>Hubo un error al intentar registrarte. Por favor, inténtalo de nuevo más tarde.</p>
                  </div>";
        }
        
        $stmt->close();
    }
}
?>