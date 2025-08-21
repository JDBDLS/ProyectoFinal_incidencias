<?php

header('Content-Type: application/json');


require_once '../includes/db.php';

$incidencias = array();


$sql = "
    SELECT
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
        i.fecha_creacion,
        t.nombre AS tipo_incidencia,
        t.icono AS tipo_icono,
        p.nombre AS provincia,
        m.nombre AS municipio,
        b.nombre AS barrio
    FROM
        incidencias i
    JOIN
        tipos_incidencia t ON i.tipo_id = t.id
    LEFT JOIN
        provincias p ON i.provincia_id = p.id
    LEFT JOIN
        municipios m ON i.municipio_id = m.id
    LEFT JOIN
        barrios b ON i.barrio_id = b.id
    WHERE
        i.estado = 'aprobado' AND
        i.fecha_creacion >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
    ORDER BY
        i.fecha_creacion DESC
";

$resultado = $conexion->query($sql);

if ($resultado) {
    
    while ($fila = $resultado->fetch_assoc()) {
        $incidencias[] = $fila;
    }
}


$conexion->close();


echo json_encode($incidencias, JSON_UNESCAPED_UNICODE);
?>
