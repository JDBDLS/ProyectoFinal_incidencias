<?php

require_once 'header.php';

$sql_incidencias = "SELECT 
                        i.id,
                        i.titulo, 
                        i.descripcion, 
                        i.latitud, 
                        i.longitud,
                        i.muertos,
                        i.heridos,
                        i.perdida_estimada,
                        i.link_social,
                        i.foto_url,
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
                    WHERE i.estado = 'aprobado'
                    ORDER BY i.fecha_creacion DESC";

$resultado_incidencias = $conexion->query($sql_incidencias);
$incidencias = [];
if ($resultado_incidencias->num_rows > 0) {
    while ($fila = $resultado_incidencias->fetch_assoc()) {
        $incidencias[] = $fila;
    }
}

$json_incidencias = json_encode($incidencias);

?>

<h1 class="text-3xl font-bold text-center text-gray-800 mb-8">Mapa de Incidencias en República Dominicana</h1>

<div class="bg-white rounded-lg shadow-lg p-4 mb-8">
    
    <div id="mapid"></div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', (event) => {
       
        const incidencias = <?php echo $json_incidencias; ?>;
        
        
        const map = L.map('mapid').setView([18.48605, -69.93045], 12);

       
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

       
        incidencias.forEach(incidencia => {
            const lat = parseFloat(incidencia.latitud);
            const lon = parseFloat(incidencia.longitud);

           
            if (!isNaN(lat) && !isNaN(lon)) {
               
                const popupContent = `
                    <div class="font-sans">
                        <h3 class="text-lg font-bold text-blue-800 mb-2">${incidencia.titulo}</h3>
                        <p class="text-sm text-gray-700 mb-2"><b>Descripción:</b> ${incidencia.descripcion.substring(0, 50)}...</p>
                        <p class="text-sm text-gray-700"><b>Tipo:</b> ${incidencia.tipo_nombre}</p>
                        <p class="text-sm text-gray-700"><b>Ubicación:</b> ${incidencia.barrio_nombre}, ${incidencia.municipio_nombre}, ${incidencia.provincia_nombre}</p>
                        <p class="text-sm text-gray-700"><b>Pérdida Estimada:</b> RD$${incidencia.perdida_estimada}</p>
                        <div class="mt-2">
                            <!-- El enlace ahora incluirá el ID de la incidencia para la siguiente fase -->
                            <a href="incidencia.php?id=${incidencia.id}" class="inline-block bg-blue-600 text-white font-bold py-2 px-4 rounded-lg hover:bg-blue-700 transition duration-300">Ver Detalles</a>
                        </div>
                    </div>
                `;

               
                L.marker([lat, lon]).addTo(map)
                    .bindPopup(popupContent, { minWidth: 250 });
            }
        });
    });
</script>

<?php
require_once 'footer.php';
?>
</body>
</html>