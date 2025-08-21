<?php

require_once 'header.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {

    echo "<div class='container mx-auto mt-8 p-4 bg-red-100 text-red-800 rounded-lg shadow-lg'>
            <p class='text-center'>Error: ID de incidencia no válido.</p>
          </div>";
    require_once 'footer.php';
    exit();
}

$incidencia_id = (int)$_GET['id'];

$stmt = $conexion->prepare("SELECT 
                                i.titulo, 
                                i.descripcion, 
                                i.latitud, 
                                i.longitud,
                                i.muertos,
                                i.heridos,
                                i.perdida_estimada,
                                i.link_social,
                                i.foto_url,
                                i.fecha_creacion,
                                ti.nombre AS tipo_nombre,
                                p.nombre AS provincia_nombre,
                                m.nombre AS municipio_nombre,
                                b.nombre AS barrio_nombre
                            FROM 
                                incidencias i
                            LEFT JOIN tipos_incidencia ti ON i.tipo_id = ti.id
                            LEFT JOIN provincias p ON i.provincia_id = p.id
                            LEFT JOIN municipios m ON i.municipio_id = m.id
                            LEFT JOIN barrios b ON i.barrio_id = b.id
                            WHERE i.id = ?");


$stmt->bind_param("i", $incidencia_id);
$stmt->execute();
$resultado = $stmt->get_result();


if ($resultado->num_rows === 0) {
    echo "<div class='container mx-auto mt-8 p-4 bg-red-100 text-red-800 rounded-lg shadow-lg'>
            <p class='text-center'>Error: No se encontró la incidencia con el ID #{$incidencia_id}.</p>
          </div>";
    require_once 'footer.php';
    exit();
}

$incidencia = $resultado->fetch_assoc();

$stmt->close();
?>

<div class="bg-white rounded-lg shadow-lg p-8 mb-8 max-w-4xl mx-auto">
    <div class="flex flex-col md:flex-row gap-8">
        
        <?php if ($incidencia['foto_url']) : ?>
            <div class="w-full md:w-1/2">
                <img src="<?php echo htmlspecialchars($incidencia['foto_url']); ?>" alt="Foto de la incidencia" class="rounded-lg shadow-md w-full h-auto object-cover">
            </div>
        <?php endif; ?>

        <div class="w-full <?php echo ($incidencia['foto_url']) ? 'md:w-1/2' : 'md:w-full'; ?>">
            <h1 class="text-4xl font-bold text-gray-800 mb-4"><?php echo htmlspecialchars($incidencia['titulo']); ?></h1>
            <p class="text-sm text-gray-500 mb-6">Reportado el <?php echo date("d/m/Y", strtotime($incidencia['fecha_creacion'])); ?></p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-gray-700 mb-6">
                <p><b>Tipo de Incidencia:</b> <?php echo htmlspecialchars($incidencia['tipo_nombre']); ?></p>
                <p><b>Ubicación:</b> <?php echo htmlspecialchars("{$incidencia['barrio_nombre']}, {$incidencia['municipio_nombre']}, {$incidencia['provincia_nombre']}"); ?></p>
                <p><b>Muertos:</b> <?php echo htmlspecialchars($incidencia['muertos']); ?></p>
                <p><b>Heridos:</b> <?php echo htmlspecialchars($incidencia['heridos']); ?></p>
                <p><b>Pérdida Estimada:</b> RD$<?php echo number_format($incidencia['perdida_estimada'], 2); ?></p>
            </div>

            <p class="text-gray-700 mb-6 leading-relaxed"><?php echo htmlspecialchars($incidencia['descripcion']); ?></p>

            <?php if ($incidencia['link_social']) : ?>
                <a href="<?php echo htmlspecialchars($incidencia['link_social']); ?>" target="_blank" class="text-blue-600 hover:text-blue-800 font-semibold transition duration-300">
                    <i class="fa-solid fa-link mr-2"></i>Ver en Redes Sociales
                </a>
            <?php endif; ?>
        </div>
    </div>
    
    <div id="incidencia-map" class="mt-8 rounded-lg shadow-md" style="height: 400px;"></div>
</div>

<?php
require_once 'footer.php';
?>

<script>
    document.addEventListener('DOMContentLoaded', (event) => {
        const lat = parseFloat("<?php echo $incidencia['latitud']; ?>");
        const lon = parseFloat("<?php echo $incidencia['longitud']; ?>");

        if (!isNaN(lat) && !isNaN(lon)) {
            const map = L.map('incidencia-map').setView([lat, lon], 15);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            L.marker([lat, lon]).addTo(map);
        }
    });
</script>

</body>
</html>