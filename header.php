<?php
// --------------------------------------------------------
// Archivo de cabecera del proyecto
// Ubicación: htdocs/incidencias/header.php
// --------------------------------------------------------

// Iniciamos la sesión de PHP
session_start();

require_once 'includes/db.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Incidencias | Inicio</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
      body { font-family: 'Inter', sans-serif; }
      
      #mapid { height: 600px; width: 100%; border-radius: 0.5rem; }
    </style>
    
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
      xintegrity="sha512-xodxYpFU/P72eFvK7W4J/1k8L6c7K9fJ4SgA/Nf1pL6c9J3M1lJd+4B+l5o+6K0l"
      crossorigin=""/>
    
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
      xintegrity="sha512-XQo4n+7l4dC+VzYj+8K+4B+wFvWqg+X9d7h6t+l5b+Xp+1h+tJ5y+W+v+fL6l"
      crossorigin=""></script>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
      xintegrity="sha512-Fo3j/sB8f8N+rN+t8Wv6pXp8p+l5h7bCjQ5N5V3M2p5E2R+n9J3c8F6l+c9s" crossorigin="anonymous" />
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">

<nav class="bg-white shadow-lg p-4">
    <div class="container mx-auto flex justify-between items-center">
        <a href="index.php" class="text-2xl font-bold text-gray-800">Incidencias RD</a>
        <div class="space-x-4 flex items-center">
            <a href="index.php" class="text-gray-600 hover:text-blue-500 transition duration-300">Mapa</a>
            <a href="reportar.php" class="text-gray-600 hover:text-blue-500 transition duration-300">Reportar Incidencia</a>
            <a href="estadisticas.php" class="text-gray-600 hover:text-blue-500 transition duration-300">Estadísticas</a>
            
            <?php if (isset($_SESSION['user_id'])) : ?>
                <!-- Si el usuario es un validador, mostramos el enlace al panel de administración -->
                <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'validador') : ?>
                    <a href="admin.php" class="text-gray-600 hover:text-blue-500 transition duration-300">Admin</a>
                <?php endif; ?>

                <!-- Si el usuario ha iniciado sesión, mostramos el nombre -->
                <span class="text-gray-800 font-semibold">Hola, <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                
                <a href="logout.php" class="bg-red-500 text-white font-bold py-2 px-4 rounded-full hover:bg-red-600 transition duration-300">Cerrar Sesión</a>
            <?php else : ?>
                <!-- Si el usuario no ha iniciado sesión -->
                <a href="login.php" class="bg-blue-600 text-white font-bold py-2 px-4 rounded-full hover:bg-blue-700 transition duration-300">Acceder</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<div class="container mx-auto mt-8 p-4">
