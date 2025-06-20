<?php 
// Vérification que l'utilisateur est admin
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header('Location: /login');
    exit;
}

require_once ROOT_PATH . '/app/views/partials/header.php'; 

// --- Fonction d'aide pour générer une carte de capteur ---
function render_sensor_card($sensor_type, $sensor_data, $config) {
    if (empty($sensor_data) || empty($sensor_data['latest'])) {
        // Affiche une carte indiquant que les données sont non disponibles
        echo "
        <div class='sensor-card {$config['class']}'>
            <div class='sensor-header'>
                <div class='sensor-title'><i class='{$config['icon']}'></i><h3>{$config['title']}</h3></div>
                <div class='sensor-status status-off'><i class='fas fa-times-circle'></i> Inactif</div>
            </div>
            <div class='sensor-body'><p class='text-center'>Aucune donnée disponible pour ce capteur.</p></div>
        </div>";
        return;
    }

    $latest = $sensor_data['latest'];
    // La logique du booléen est inversée par rapport à votre demande précédente
    // SENSOR_STATUS 'disponible' -> BDD `valeur`=true
    // SENSOR_STATUS 'occupée' -> BDD `valeur`=false
    $value_display = ($sensor_type === 'proximity') 
        ? ($latest['valeur'] ? '<span class="value text-success">DISPONIBLE</span>' : '<span class="value text-danger">OCCUPÉE</span>')
        : '<span class="value">' . number_format($latest['valeur'], 1) . '</span><span class="unit">' . $config['unit'] . '</span>';
?>
    <div class="sensor-card <?= $config['class'] ?>">
        <div class="sensor-header">
            <div class="sensor-title"><i class="<?= $config['icon'] ?>"></i><h3><?= $config['title'] ?></h3></div>
            <div class="sensor-status status-active"><i class="fas fa-circle"></i> Actif</div>
        </div>
        <div class="sensor-body">
            <div class="sensor-value-display">
                <div class="current-value" id="value-<?= $sensor_type ?>">
                    <?= $value_display ?>
                </div>
            </div>
            <div class="chart-container">
                <canvas id="<?= $sensor_type ?>-chart"></canvas>
            </div>
            <div class="sensor-info-grid">
                <div class="info-item">
                    <span class="label">Dernière lecture:</span>
                    <span class="value" id="time-<?= $sensor_type ?>"><?= date('d/m/Y H:i:s', strtotime($latest['date'] . ' ' . $latest['heure'])) ?></span>
                </div>
            </div>
        </div>
    </div>
<?php
}
?>

<div class="dashboard-container">
    <!-- ... (header et nav inchangés) ... -->
    <div class="dashboard-header">
        <div class="header-content">
            <h1><i class="fas fa-satellite-dish"></i> Surveillance des Capteurs</h1>
            <p>Données et graphiques en temps réel du système IoT</p>
        </div>
    </div>
    <div class="dashboard-nav">
        <div class="nav-tabs">
            <a href="<?= BASE_URL ?>/iot-dashboard" class="nav-tab"><i class="fas fa-arrow-left"></i> Retour IoT</a>
            <a href="<?= BASE_URL ?>/iot-dashboard/capteurs" class="nav-tab active"><i class="fas fa-satellite-dish"></i> Capteurs</a>
            <a href="<?= BASE_URL ?>/iot-dashboard/actionneurs" class="nav-tab"><i class="fas fa-cogs"></i> Actionneurs</a>
        </div>
    </div>


    <!-- Grille des capteurs dynamiques -->
    <div class="sensors-grid">
        <?php
        // On affiche les cartes pour chaque type de capteur
        render_sensor_card('temp', $data['temp'] ?? [], ['class' => 'temperature-sensor', 'title' => 'Température', 'icon' => 'fas fa-thermometer-half', 'unit' => '°C']);
        render_sensor_card('gas', $data['gas'] ?? [], ['class' => 'gas-sensor', 'title' => 'Qualité de l\'Air (Gaz)', 'icon' => 'fas fa-smog', 'unit' => 'ppm']);
        render_sensor_card('light', $data['light'] ?? [], ['class' => 'light-sensor', 'title' => 'Luminosité', 'icon' => 'fas fa-sun', 'unit' => 'lux']);
        render_sensor_card('sound', $data['sound'] ?? [], ['class' => 'sound-sensor', 'title' => 'Niveau Sonore', 'icon' => 'fas fa-microphone', 'unit' => 'dB']);
        
        // ================== MODIFICATION PRINCIPALE ICI ==================
        // Suppression de la boucle 'foreach' et appel unique pour le capteur de proximité
        render_sensor_card('proximity', $data['proximity'] ?? [], ['class' => 'proximity-sensor', 'title' => 'Proximité Place 1', 'icon' => 'fas fa-radar', 'unit' => '']);
        // =================================================================
        ?>
    </div>
</div>


<style>
/* Style général */
/* Base et conteneur */
.dashboard-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 2rem;
}

/* Header du dashboard */
.dashboard-header {
    text-align: center;
    margin-bottom: 2rem;
}

.header-content h1 {
    color: white;
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
}

.header-content h1 i {
    color: #ffd700;
    margin-right: 1rem;
}

.header-content p {
    color: rgba(255, 255, 255, 0.9);
    font-size: 1.1rem;
    margin: 0;
}

/* Navigation secondaire */
.dashboard-nav {
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(15px);
    border-radius: 15px;
    padding: 1rem 2rem;
    margin-bottom: 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.nav-tabs {
    display: flex;
    gap: 1rem;
}

.nav-tab {
    color: rgba(255, 255, 255, 0.8);
    text-decoration: none;
    padding: 0.75rem 1.5rem;
    border-radius: 10px;
    transition: all 0.3s ease;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.nav-tab:hover, .nav-tab.active {
    background: rgba(255, 255, 255, 0.2);
    color: white;
    transform: translateY(-2px);
}

.user-info {
    color: white;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.sensors-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 1.5rem; }
.sensor-card { background: rgba(255,255,255,0.95); border-radius: 15px; box-shadow: 0 8px 25px rgba(0,0,0,0.1); overflow: hidden; }
.sensor-header { padding: 1rem 1.5rem; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e9ecef; }
.sensor-title { display: flex; align-items: center; gap: 0.75rem; color: #1e293b; }
.sensor-title h3 { margin: 0; font-size: 1.1rem; }
.sensor-status { font-size: 0.8rem; font-weight: 600; padding: 0.3rem 0.8rem; border-radius: 20px; }
.status-active { background: #d4edda; color: #155724; }
.status-off { background: #f8d7da; color: #721c24; }
.sensor-body { padding: 1.5rem; }
.sensor-value-display { text-align: center; margin-bottom: 1rem; }
.current-value { font-size: 2.5rem; font-weight: 700; color: #1e293b; }
.current-value .unit { font-size: 1.25rem; color: #6c757d; margin-left: 0.25rem; }
.text-danger { color: #dc3545 !important; } .text-success { color: #28a745 !important; }
.chart-container { height: 200px; margin-bottom: 1rem; }
.sensor-info-grid { border-top: 1px solid #e9ecef; padding-top: 1rem; }
.info-item { display: flex; justify-content: space-between; font-size: 0.9rem; }
.info-item .label { color: #6c757d; }
.info-item .value { font-weight: 600; color: #343a40; }
.text-center { text-align: center; }

</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const charts = {};

    function createChart(ctx, label, labels, data, config) {
        return new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: label,
                    data: data,
                    borderColor: config.borderColor,
                    backgroundColor: config.backgroundColor,
                    borderWidth: 2,
                    pointRadius: 3,
                    pointBackgroundColor: config.borderColor,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { 
                        beginAtZero: false,
                        ticks: config.yTicks || {}
                    },
                    x: { ticks: { display: false } }
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        callbacks: config.tooltipCallbacks || {}
                    }
                }
            }
        });
    }

    // Initialisation des graphiques
    <?php
    function format_history_for_js($history_data, $is_bool = false) {
        $labels = []; $data = [];
        if (!empty($history_data)) {
            foreach($history_data as $record) {
                $labels[] = date('H:i:s', strtotime($record['heure']));
                $data[] = $is_bool ? (int)(bool)$record['valeur'] : $record['valeur'];
            }
        }
        return ['labels' => $labels, 'data' => $data];
    }
    ?>

    // Température
    <?php if(!empty($data['temp']['history'])): $js_data = format_history_for_js($data['temp']['history']); ?>
        charts.temp = createChart(
            document.getElementById('temp-chart').getContext('2d'),
            'Température (°C)',
            <?= json_encode($js_data['labels']) ?>,
            <?= json_encode($js_data['data']) ?>,
            { borderColor: '#3b82f6', backgroundColor: 'rgba(59, 130, 246, 0.1)' }
        );
    <?php endif; ?>

    // Gaz
    <?php if(!empty($data['gas']['history'])): $js_data = format_history_for_js($data['gas']['history']); ?>
        charts.gas = createChart(
            document.getElementById('gas-chart').getContext('2d'),
            'Gaz (ppm)',
            <?= json_encode($js_data['labels']) ?>,
            <?= json_encode($js_data['data']) ?>,
            { borderColor: '#ef4444', backgroundColor: 'rgba(239, 68, 68, 0.1)' }
        );
    <?php endif; ?>

    // Lumière
    <?php if(!empty($data['light']['history'])): $js_data = format_history_for_js($data['light']['history']); ?>
        charts.light = createChart(
            document.getElementById('light-chart').getContext('2d'),
            'Luminosité (lux)',
            <?= json_encode($js_data['labels']) ?>,
            <?= json_encode($js_data['data']) ?>,
            { borderColor: '#f59e0b', backgroundColor: 'rgba(245, 158, 11, 0.1)' }
        );
    <?php endif; ?>

    // Son
    <?php if(!empty($data['sound']['history'])): $js_data = format_history_for_js($data['sound']['history']); ?>
        charts.sound = createChart(
            document.getElementById('sound-chart').getContext('2d'),
            'Son (dB)',
            <?= json_encode($js_data['labels']) ?>,
            <?= json_encode($js_data['data']) ?>,
            { borderColor: '#06b6d4', backgroundColor: 'rgba(6, 182, 212, 0.1)' }
        );
    <?php endif; ?>

    // Proximité
    <?php if(!empty($data['proximity']['history'])): $js_data = format_history_for_js($data['proximity']['history'], true); ?>
        charts.proximity = createChart(
            document.getElementById('proximity-chart').getContext('2d'),
            'État',
            <?= json_encode($js_data['labels']) ?>,
            <?= json_encode($js_data['data']) ?>,
            { 
                borderColor: '#6366f1', 
                backgroundColor: 'rgba(99, 102, 241, 0.1)',
                yTicks: { 
                    stepSize: 1,
                    // MODIFICATION ICI pour correspondre à la nouvelle logique de booléen
                    callback: function(value) { return value === 1 ? 'Disponible' : 'Occupé'; } 
                }
            }
        );
        charts.proximity.data.datasets[0].stepped = true;
        charts.proximity.update();
    <?php endif; ?>

    // Mise à jour AJAX
    async function updateData() {
        try {
            const response = await fetch('<?= BASE_URL ?>/api/iot/sensor-data');
            const result = await response.json();

            if (result.success) {
                const sensorData = result.data;
                updateSensorUI('temp', sensorData.temp, sensorData.latest.temp);
                updateSensorUI('gas', sensorData.gas, sensorData.latest.gas);
                updateSensorUI('light', sensorData.light, sensorData.latest.light);
                updateSensorUI('sound', sensorData.sound, sensorData.latest.sound);
                updateSensorUI('proximity', sensorData.proximity, sensorData.latest.proximity, true);
            }
        } catch (error) {
            console.error("Erreur lors de la mise à jour des données:", error);
        }
    }
    
    function updateSensorUI(type, history, latest, isBool = false) {
        if (!charts[type] || !history || !latest) return;
        
        const valueEl = document.getElementById(`value-${type}`);
        const timeEl = document.getElementById(`time-${type}`);
        
        if(isBool) {
            // Logique de booléen inversée pour correspondre à votre demande
            valueEl.innerHTML = latest.valeur ? '<span class="value text-success">DISPONIBLE</span>' : '<span class="value text-danger">OCCUPÉE</span>';
        } else {
            valueEl.querySelector('.value').textContent = parseFloat(latest.valeur).toFixed(1);
        }
        const date = new Date(latest.date + 'T' + latest.heure);
        timeEl.textContent = date.toLocaleString('fr-FR');

        const newLabels = history.map(r => new Date(latest.date + 'T' + r.heure).toLocaleTimeString('fr-FR'));
        const newData = history.map(r => isBool ? (r.valeur ? 1 : 0) : r.valeur);
        
        charts[type].data.labels = newLabels;
        charts[type].data.datasets[0].data = newData;
        charts[type].update('none');
    }

    setInterval(updateData, 5000);
});
</script>

<?php require_once ROOT_PATH . '/app/views/partials/footer.php'; ?>