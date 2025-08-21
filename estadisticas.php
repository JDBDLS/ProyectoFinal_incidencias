<?php

require_once 'header.php';

$sql_stats = "SELECT
                  ti.nombre AS tipo_nombre,
                  COUNT(i.id) AS total_incidencias
              FROM
                  tipos_incidencia ti
              LEFT JOIN
                  incidencias i ON ti.id = i.tipo_id
              GROUP BY
                  ti.id
              ORDER BY
                  total_incidencias DESC";

$resultado_stats = $conexion->query($sql_stats);

$labels = [];
$data = [];
if ($resultado_stats->num_rows > 0) {
    while ($fila = $resultado_stats->fetch_assoc()) {
        $labels[] = $fila['tipo_nombre'];
        $data[] = $fila['total_incidencias'];
    }
}
?>

<h1 class="text-3xl font-bold text-center text-gray-800 mb-8">Estadísticas de Incidencias</h1>

<div class="bg-white rounded-lg shadow-lg p-8 mb-8 max-w-4xl mx-auto">
    <h2 class="text-xl font-semibold text-gray-700 mb-4">Incidencias por Tipo</h2>
    <div class="p-4 bg-gray-50 rounded-lg">
        <canvas id="incidenciasChart" width="800" height="400"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', (event) => {
        const ctx = document.getElementById('incidenciasChart').getContext('2d');
        const incidenciasChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($labels); ?>,
                datasets: [{
                    label: 'Número de Incidencias',
                    data: <?php echo json_encode($data); ?>,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.6)',
                        'rgba(54, 162, 235, 0.6)',
                        'rgba(255, 206, 86, 0.6)',
                        'rgba(75, 192, 192, 0.6)',
                        'rgba(153, 102, 255, 0.6)',
                        'rgba(255, 159, 64, 0.6)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                    }
                }
            }
        });
    });
</script>

<?php
require_once 'footer.php';
?>