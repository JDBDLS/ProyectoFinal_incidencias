<?php

require_once 'header.php';
?>

<div class="bg-white rounded-lg shadow-lg p-8 mb-8 max-w-md mx-auto">
    <h1 class="text-3xl font-bold text-center text-gray-800 mb-6">Acceder a tu Cuenta</h1>
    
    <p class="text-center text-gray-600 mb-6">
        Accede para reportar incidencias como validador o reportero.
    </p>

    <div class="space-y-4">
        
        <div id="g_id_onload"
            data-client_id="TU_GOOGLE_CLIENT_ID"
            data-callback="handleCredentialResponse">
        </div>
        <div class="g_id_signin" data-type="standard"></div>
        
        <a id="office-login-btn" href="#" class="block w-full text-center py-3 px-4 border border-gray-300 rounded-lg shadow-sm text-gray-700 font-semibold hover:bg-gray-100 transition duration-300">
            <i class="fab fa-microsoft mr-2"></i> Acceder con Office 365
        </a>
    </div>

    <div class="relative flex py-5 items-center">
        <div class="flex-grow border-t border-gray-300"></div>
        <span class="flex-shrink mx-4 text-gray-400">o</span>
        <div class="flex-grow border-t border-gray-300"></div>
    </div>
    
    <form action="handle_login.php" method="POST">
        <div class="mb-4">
            <label for="email" class="block text-gray-700 font-semibold mb-2">Correo Electrónico</label>
            <input type="email" id="email" name="email" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div class="mb-6">
            <label for="password" class="block text-gray-700 font-semibold mb-2">Contraseña</label>
            <input type="password" id="password" name="password" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3 px-4 rounded-lg hover:bg-blue-700 transition duration-300">
            Acceder
        </button>
    </form>
    
    <p class="text-center text-sm text-gray-600 mt-4">
        ¿No tienes una cuenta? <a href="registro.php" class="text-blue-600 hover:underline font-semibold">Regístrate aquí</a>
    </p>
</div>

<script src="https://accounts.google.com/gsi/client" async defer></script>
<script>
    function handleCredentialResponse(response) {
        const idToken = response.credential;
        
        fetch('/google_login.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ token: idToken }),
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = 'index.php';
            } else {
                alert('Error al iniciar sesión con Google.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }

    
    document.getElementById('office-login-btn').addEventListener('click', function(event) {
        event.preventDefault(); 
        alert('Esta funcionalidad requiere configuración adicional con la API de Microsoft. Por ahora, inicia sesión con correo y contraseña, o con Google.');
       
    });
</script>

<?php
require_once 'footer.php';
?>