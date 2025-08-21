<?php

require_once 'header.php';

$mensaje_estado = "";
$clase_estado = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $titulo = $conexion->real_escape_string($_POST['titulo']);
    $descripcion = $conexion->real_escape_string($_POST['descripcion']);
    $tipo_id = (int)$_POST['tipo_id'];
    $provincia_id = (int)$_POST['provincia_id'];
    $municipio_id = (int)$_POST['municipio_id'];
    $barrio_id = (int)$_POST['barrio_id'];
    $latitud = (float)$_POST['latitud'];
    $longitud = (float)$_POST['longitud'];
    $muertos = (int)$_POST['muertos'];
    $heridos = (int)$_POST['heridos'];
    $perdida_estimada = (float)$_POST['perdida_estimada'];
    $link_social = $conexion->real_escape_string($_POST['link_social']);
    
    $foto_url = null;
    
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $nombre_archivo = $_FILES['foto']['name'];
        $tipo_mime = $_FILES['foto']['type'];
        $tamano_archivo = $_FILES['foto']['size'];
        $archivo_temporal = $_FILES['foto']['tmp_name'];
        
        $directorio_destino = "uploads/";
        
        $extension = pathinfo($nombre_archivo, PATHINFO_EXTENSION);
        $nombre_unico = uniqid('foto_', true) . '.' . $extension;
        $ruta_destino = $directorio_destino . $nombre_unico;
        
        if (move_uploaded_file($archivo_temporal, $ruta_destino)) {
            $foto_url = $ruta_destino;
        } else {
            $mensaje_estado = "Error al subir la imagen. Por favor, inténtalo de nuevo.";
            $clase_estado = "bg-red-500";
        }
    }
    

    if (empty($mensaje_estado)) {
        
        $stmt = $conexion->prepare("INSERT INTO incidencias (titulo, descripcion, latitud, longitud, muertos, heridos, perdida_estimada, link_social, foto_url, tipo_id, provincia_id, municipio_id, barrio_id, estado, fecha_creacion) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
        
        $estado = "pendiente";
        
        $stmt->bind_param("ssddiidsisiiis", 
            $titulo, 
            $descripcion, 
            $latitud, 
            $longitud, 
            $muertos, 
            $heridos, 
            $perdida_estimada, 
            $link_social, 
            $foto_url, 
            $tipo_id, 
            $provincia_id, 
            $municipio_id, 
            $barrio_id,
            $estado);
        
        if ($stmt->execute()) {
            $mensaje_estado = "¡Reporte enviado con éxito! Un validador revisará la incidencia pronto.";
            $clase_estado = "bg-green-500";
        } else {
            $mensaje_estado = "Error al guardar el reporte: " . $stmt->error;
            $clase_estado = "bg-red-500";
        }
        
        $stmt->close();
    }
}


$sql_provincias = "SELECT id, nombre FROM provincias ORDER BY nombre ASC";
$resultado_provincias = $conexion->query($sql_provincias);
$provincias = $resultado_provincias->fetch_all(MYSQLI_ASSOC);

$sql_tipos = "SELECT id, nombre FROM tipos_incidencia ORDER BY nombre ASC";
$resultado_tipos = $conexion->query($sql_tipos);
$tipos_incidencia = $resultado_tipos->fetch_all(MYSQLI_ASSOC);

?>

<h1 class="text-3xl font-bold text-center text-gray-800 mb-8">Reportar una Nueva Incidencia</h1>

<?php if ($mensaje_estado) : ?>
    <div class="p-4 rounded-lg text-white mb-6 text-center <?php echo $clase_estado; ?>">
        <?php echo htmlspecialchars($mensaje_estado); ?>
    </div>
<?php endif; ?>

<div class="bg-white rounded-lg shadow-lg p-8 mb-8 max-w-2xl mx-auto">
    
    <form action="reportar.php" method="POST" enctype="multipart/form-data">
        
        <div class="mb-4">
            <label for="titulo" class="block text-gray-700 font-semibold mb-2">Título de la Incidencia</label>
            <input type="text" id="titulo" name="titulo" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        
        <div class="mb-4">
            <label for="descripcion" class="block text-gray-700 font-semibold mb-2">Descripción Detallada</label>
            <textarea id="descripcion" name="descripcion" rows="4" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
        </div>

        
        <div class="mb-4">
            <label for="tipo_id" class="block text-gray-700 font-semibold mb-2">Tipo de Incidencia</label>
            <select id="tipo_id" name="tipo_id" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Selecciona un tipo</option>
                <?php foreach ($tipos_incidencia as $tipo) : ?>
                    <option value="<?php echo htmlspecialchars($tipo['id']); ?>"><?php echo htmlspecialchars($tipo['nombre']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

       
        <div class="mb-4 grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label for="provincia_id" class="block text-gray-700 font-semibold mb-2">Provincia</label>
                <select id="provincia_id" name="provincia_id" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Selecciona una provincia</option>
                    <?php foreach ($provincias as $provincia) : ?>
                        <option value="<?php echo htmlspecialchars($provincia['id']); ?>"><?php echo htmlspecialchars($provincia['nombre']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label for="municipio_id" class="block text-gray-700 font-semibold mb-2">Municipio</label>
                <select id="municipio_id" name="municipio_id" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Selecciona un municipio</option>
                </select>
            </div>
            <div>
                <label for="barrio_id" class="block text-gray-700 font-semibold mb-2">Barrio</label>
                <select id="barrio_id" name="barrio_id" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Selecciona un barrio</option>
                </select>
            </div>
        </div>

        
        <div class="mb-4 grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label for="muertos" class="block text-gray-700 font-semibold mb-2">Muertos</label>
                <input type="number" id="muertos" name="muertos" min="0" value="0" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label for="heridos" class="block text-gray-700 font-semibold mb-2">Heridos</label>
                <input type="number" id="heridos" name="heridos" min="0" value="0" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label for="perdida_estimada" class="block text-gray-700 font-semibold mb-2">Pérdida Estimada (RD$)</label>
                <input type="number" id="perdida_estimada" name="perdida_estimada" min="0" step="0.01" value="0.00" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
        </div>

        
        <div class="mb-4">
            <h3 class="text-gray-700 font-semibold mb-2">Coordenadas del Incidente</h3>
            <div id="map-form" class="w-full h-96 rounded-lg mb-4"></div>
            <div class="flex space-x-4">
                <div class="w-1/2">
                    <label for="latitud" class="block text-gray-700 font-semibold mb-2">Latitud</label>
                    <input type="text" id="latitud" name="latitud" required readonly class="w-full px-4 py-2 border rounded-lg bg-gray-100 focus:outline-none">
                </div>
                <div class="w-1/2">
                    <label for="longitud" class="block text-gray-700 font-semibold mb-2">Longitud</label>
                    <input type="text" id="longitud" name="longitud" required readonly class="w-full px-4 py-2 border rounded-lg bg-gray-100 focus:outline-none">
                </div>
            </div>
        </div>
        
        
        <div class="mb-4">
            <label for="link_social" class="block text-gray-700 font-semibold mb-2">Enlace a Redes Sociales</label>
            <input type="url" id="link_social" name="link_social" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Ej: https://x.com/...">
        </div>

        
        <div class="mb-4">
            <label for="foto" class="block text-gray-700 font-semibold mb-2">Foto del Hecho</label>
            <input type="file" id="foto" name="foto" accept="image/*" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        
        <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3 px-4 rounded-lg hover:bg-blue-700 transition duration-300">
            <i class="fa-solid fa-paper-plane mr-2"></i>
            Enviar Reporte
        </button>
    </form>
</div>


<!-- Ya lo crearemos en la siguiente fase para cerrar el div del container -->
<!-- require_once 'footer.php'; -->

<script>
    document.addEventListener('DOMContentLoaded', (event) => {
        
        const mapForm = L.map('map-form').setView([18.48605, -69.93045], 10);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(mapForm);

        let marker; 

        
        mapForm.on('click', function(e) {
            
            if (marker) {
                mapForm.removeLayer(marker);
            }
            
            marker = L.marker(e.latlng).addTo(mapForm);

            
            document.getElementById('latitud').value = e.latlng.lat.toFixed(6);
            document.getElementById('longitud').value = e.latlng.lng.toFixed(6);
        });

      
        const provinciaSelect = document.getElementById('provincia_id');
        const municipioSelect = document.getElementById('municipio_id');
        const barrioSelect = document.getElementById('barrio_id');

        provinciaSelect.addEventListener('change', function() {
            const provinciaId = this.value;
            municipioSelect.innerHTML = '<option value="">Cargando...</option>';
            barrioSelect.innerHTML = '<option value="">Selecciona un barrio</option>';

            if (provinciaId) {
                
                fetch(`api/get_municipios.php?provincia_id=${provinciaId}`)
                    .then(response => response.json())
                    .then(municipios => {
                        municipioSelect.innerHTML = '<option value="">Selecciona un municipio</option>';
                        municipios.forEach(municipio => {
                            const option = document.createElement('option');
                            option.value = municipio.id;
                            option.textContent = municipio.nombre;
                            municipioSelect.appendChild(option);
                        });
                    })
                    .catch(error => console.error('Error al cargar municipios:', error));
            } else {
                municipioSelect.innerHTML = '<option value="">Selecciona un municipio</option>';
            }
        });

        municipioSelect.addEventListener('change', function() {
            const municipioId = this.value;
            barrioSelect.innerHTML = '<option value="">Cargando...</option>';

            if (municipioId) {
                
                fetch(`api/get_barrios.php?municipio_id=${municipioId}`)
                    .then(response => response.json())
                    .then(barrios => {
                        barrioSelect.innerHTML = '<option value="">Selecciona un barrio</option>';
                        barrios.forEach(barrio => {
                            const option = document.createElement('option');
                            option.value = barrio.id;
                            option.textContent = barrio.nombre;
                            barrioSelect.appendChild(option);
                        });
                    })
                    .catch(error => console.error('Error al cargar barrios:', error));
            } else {
                barrioSelect.innerHTML = '<option value="">Selecciona un barrio</option>';
            }
        });
    });
</script>

<?php
require_once 'footer.php';
?>
</body>
</html>