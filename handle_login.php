<?php

require_once 'includes/db.php';

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    if (isset($_POST['email']) && isset($_POST['password'])) {
        
        $email = $_POST['email'];
        $password = $_POST['password'];
        
        $stmt = $conexion->prepare("SELECT id, nombre, password, rol FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $resultado = $stmt->get_result();
        
        if ($resultado->num_rows === 1) {
            $usuario = $resultado->fetch_assoc();
            
            if (password_verify($password, $usuario['password'])) {
                
                $_SESSION['user_id'] = $usuario['id'];
                $_SESSION['user_name'] = $usuario['nombre'];
                $_SESSION['user_role'] = $usuario['rol'];
                
                header("Location: index.php");
                exit();
            } else {
                
                echo "<div class='container mx-auto mt-8 p-4 bg-red-100 text-red-800 rounded-lg shadow-lg'>
                        <p class='text-center'>Error: Credenciales incorrectas. Por favor, inténtalo de nuevo.</p>
                      </div>";
            }
        } else {
            
            echo "<div class='container mx-auto mt-8 p-4 bg-red-100 text-red-800 rounded-lg shadow-lg'>
                    <p class='text-center'>Error: Credenciales incorrectas. Por favor, inténtalo de nuevo.</p>
                  </div>";
        }
        
        $stmt->close();
    }
}
?>