<?php

require_once 'header.php';

$sql_incidencias = "SELECT 
                        i.id,
                        i.titulo, 
                        i.fecha_creacion,
                        i.estado,
                        ti.nombre AS tipo_nombre,
                        p.nombre AS provincia_nombre,
                        m.nombre AS municipio_nombre
                    FROM 
                        incidencias i
                    LEFT JOIN tipos_incidencia ti ON i.tipo_id = ti.id
                    LEFT JOIN provincias p ON i.provincia_id = p.id
                    LEFT JOIN municipios m ON i.municipio_id = m.id
                    ORDER BY i.fecha_creacion DESC";

$resultado = $conexion->query($sql_incidencias);
$incidencias = [];
if ($resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $incidencias[] = $fila;
    }
}
?>

<h1 class="text-3xl font-bold text-gray-800 mb-8 text-center">Panel de Administración de Incidencias</h1>

<div class="bg-white rounded-lg shadow-lg p-8 mb-8 max-w-7xl mx-auto overflow-x-auto">
    <table class="min-w-full bg-white border border-gray-200 rounded-lg">
        <thead class="bg-gray-200">
            <tr>
                <th class="py-3 px-4 text-left font-semibold text-gray-700">ID</th>
                <th class="py-3 px-4 text-left font-semibold text-gray-700">Título</th>
                <th class="py-3 px-4 text-left font-semibold text-gray-700">Tipo</th>
                <th class="py-3 px-4 text-left font-semibold text-gray-700">Ubicación</th>
                <th class="py-3 px-4 text-left font-semibold text-gray-700">Fecha</th>
                <th class="py-3 px-4 text-left font-semibold text-gray-700">Estado</th>
                <th class="py-3 px-4 text-left font-semibold text-gray-700">Acciones</th>
            </tr>
        </thead>
        <tbody class="text-gray-700">
            <?php if (!empty($incidencias)) : ?>
                <?php foreach ($incidencias as $incidencia) : ?>
                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                        <td class="py-3 px-4"><?php echo htmlspecialchars($incidencia['id']); ?></td>
                        <td class="py-3 px-4"><?php echo htmlspecialchars($incidencia['titulo']); ?></td>
                        <td class="py-3 px-4"><?php echo htmlspecialchars($incidencia['tipo_nombre']); ?></td>
                        <td class="py-3 px-4"><?php echo htmlspecialchars("{$incidencia['municipio_nombre']}, {$incidencia['provincia_nombre']}"); ?></td>
                        <td class="py-3 px-4"><?php echo date("d/m/Y", strtotime($incidencia['fecha_creacion'])); ?></td>
                        <td class="py-3 px-4">
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold 
                            <?php 
                                if ($incidencia['estado'] === 'aprobado') {
                                    echo 'bg-green-200 text-green-800';
                                } elseif ($incidencia['estado'] === 'rechazado') {
                                    echo 'bg-red-200 text-red-800';
                                } else {
                                    echo 'bg-yellow-200 text-yellow-800';
                                }
                            ?>">
                                <?php echo ucfirst($incidencia['estado']); ?>
                            </span>
                        </td>
                        <td class="py-3 px-4 flex space-x-2">
                            <a href="incidencia.php?id=<?php echo htmlspecialchars($incidencia['id']); ?>" class="bg-blue-600 text-white font-bold py-1 px-3 rounded-lg hover:bg-blue-700 transition duration-300 text-xs">Ver</a>
                            
                            
                            <form action="actions.php" method="POST" class="inline-block">
                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($incidencia['id']); ?>">
                                <input type="hidden" name="accion" value="aprobar">
                                <button type="submit" class="bg-green-600 text-white font-bold py-1 px-3 rounded-lg hover:bg-green-700 transition duration-300 text-xs">Aprobar</button>
                            </form>
                            
                            
                            <form action="actions.php" method="POST" class="inline-block">
                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($incidencia['id']); ?>">
                                <input type="hidden" name="accion" value="rechazar">
                                <button type="submit" class="bg-red-600 text-white font-bold py-1 px-3 rounded-lg hover:bg-red-700 transition duration-300 text-xs">Rechazar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="7" class="py-4 px-4 text-center text-gray-500">No hay incidencias reportadas.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php
// Incluimos el pie de página.
// require_once 'footer.php';
?>
</body>
</html>