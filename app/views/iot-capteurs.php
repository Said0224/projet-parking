<?php 
require_once ROOT_PATH . '/app/views/partials/header.php'; 
?>

<div class="dashboard-container">
    <!-- Header principal -->
    <div class="dashboard-header">
        <div class="header-content">
            <h1><i class="fas fa-satellite-dish"></i> Gestion des Capteurs IoT</h1>
            <p>Surveillance et monitoring en temps réel des capteurs du parking</p>
        </div>
        <div class="refresh-controls">
            <button id="refresh-sensors" class="btn btn-secondary">
                <i class="fas fa-sync-alt"></i> Actualiser
            </button>
            <span class="last-update">Dernière mise à jour: <span id="last-update"><?= date('H:i:s') ?></span></span>
        </div>
    </div>

    <!-- Navigation -->
    <div class="dashboard-nav">
        <div class="nav-tabs">
            <a href="<?= BASE_URL ?>/iot-dashboard" class="nav-tab"><i class="fas fa-arrow-left"></i> Retour IoT</a>
            <a href="<?= BASE_URL ?>/iot-dashboard/capteurs" class="nav-tab active"><i class="fas fa-satellite-dish"></i> Capteurs</a>
            <a href="<?= BASE_URL ?>/iot-dashboard/actionneurs" class="nav-tab"><i class="fas fa-cogs"></i> Actionneurs</a>
            <a href="<?= BASE_URL ?>/logout" class="nav-tab"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
        </div>
        <div class="user-info">
            <i class="fas fa-user"></i> <?= htmlspecialchars($_SESSION['user_email'] ?? 'admin@isep.fr') ?>
        </div>
    </div>

    <!-- Liste des capteurs -->
    <div class="sensors-grid" id="sensors-grid">
        <!-- Capteur Température -->
        <?php if (!empty($data['temp_sensor'])): $sensor = $data['temp_sensor']; ?>
        <div class="sensor-card temperature-sensor" data-sensor-type="temperature">
            <div class="sensor-header">
                <div class="sensor-title">
                    <i class="fas fa-thermometer-half"></i>
                    <h3>Température Ambiante</h3>
                </div>
                <div class="sensor-status status-active">
                    <i class="fas fa-circle"></i> Actif
                </div>
            </div>
            <div class="sensor-body">
                <div class="sensor-value-display">
                    <div class="current-value">
                        <span class="value" data-value="temperature"><?= number_format($sensor['valeur'], 1) ?></span>
                        <span class="unit">°C</span>
                    </div>
                    <div class="value-indicator <?= $sensor['valeur'] > 25 ? 'high' : ($sensor['valeur'] < 15 ? 'low' : 'normal') ?>"></div>
                </div>
                <div class="sensor-info-grid">
                    <div class="info-item">
                        <span class="label">ID Capteur:</span>
                        <span class="value">TEMP_001</span>
                    </div>
                    <div class="info-item">
                        <span class="label">Dernière lecture:</span>
                        <span class="value" data-timestamp="temperature"><?= date('H:i:s', strtotime($sensor['heure'])) ?></span>
                    </div>
                </div>
            </div>
        </div>
        <?php else: ?>
        <div class="sensor-card temperature-sensor maintenance-sensor">
            <div class="sensor-header">
                <div class="sensor-title">
                    <i class="fas fa-thermometer-half"></i>
                    <h3>Température Ambiante</h3>
                </div>
                <div class="sensor-status status-maintenance">
                    <i class="fas fa-circle"></i> Inactif
                </div>
            </div>
            <div class="sensor-body">
                <div class="sensor-value-display">
                    <div class="current-value maintenance-mode">
                        <span class="value">Pas de données</span>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Capteur de Gaz -->
        <?php if (!empty($data['gas_sensor'])): $sensor = $data['gas_sensor']; ?>
        <div class="sensor-card gas-sensor" data-sensor-type="gas">
            <div class="sensor-header">
                <div class="sensor-title">
                    <i class="fas fa-smog"></i>
                    <h3>Qualité de l'Air (Gaz)</h3>
                </div>
                <div class="sensor-status status-active">
                    <i class="fas fa-circle"></i> Actif
                </div>
            </div>
            <div class="sensor-body">
                <div class="sensor-value-display">
                    <div class="current-value">
                        <span class="value" data-value="gas"><?= number_format($sensor['valeur'], 0) ?></span>
                        <span class="unit">ppm</span>
                    </div>
                    <div class="value-indicator <?= $sensor['valeur'] > 1000 ? 'high' : ($sensor['valeur'] > 500 ? 'medium' : 'normal') ?>"></div>
                </div>
                <div class="sensor-info-grid">
                    <div class="info-item">
                        <span class="label">ID Capteur:</span>
                        <span class="value">GAS_001</span>
                    </div>
                    <div class="info-item">
                        <span class="label">Dernière lecture:</span>
                        <span class="value" data-timestamp="gas"><?= date('H:i:s', strtotime($sensor['heure'])) ?></span>
                    </div>
                </div>
            </div>
        </div>
        <?php else: ?>
        <div class="sensor-card gas-sensor maintenance-sensor">
            <div class="sensor-header">
                <div class="sensor-title">
                    <i class="fas fa-smog"></i>
                    <h3>Qualité de l'Air (Gaz)</h3>
                </div>
                <div class="sensor-status status-maintenance">
                    <i class="fas fa-circle"></i> Inactif
                </div>
            </div>
            <div class="sensor-body">
                <div class="sensor-value-display">
                    <div class="current-value maintenance-mode">
                        <span class="value">Pas de données</span>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Capteur de Luminosité -->
        <?php if (!empty($data['light_sensor'])): $sensor = $data['light_sensor']; ?>
        <div class="sensor-card light-sensor" data-sensor-type="light">
            <div class="sensor-header">
                <div class="sensor-title">
                    <i class="fas fa-sun"></i>
                    <h3>Luminosité</h3>
                </div>
                <div class="sensor-status status-active">
                    <i class="fas fa-circle"></i> Actif
                </div>
            </div>
            <div class="sensor-body">
                <div class="sensor-value-display">
                    <div class="current-value">
                        <span class="value" data-value="light"><?= number_format($sensor['valeur'], 0) ?></span>
                        <span class="unit">lux</span>
                    </div>
                    <div class="value-indicator <?= $sensor['valeur'] > 1000 ? 'high' : ($sensor['valeur'] < 100 ? 'low' : 'normal') ?>"></div>
                </div>
                <div class="sensor-info-grid">
                    <div class="info-item">
                        <span class="label">ID Capteur:</span>
                        <span class="value">LIGHT_001</span>
                    </div>
                    <div class="info-item">
                        <span class="label">Dernière lecture:</span>
                        <span class="value" data-timestamp="light"><?= date('H:i:s', strtotime($sensor['heure'])) ?></span>
                    </div>
                </div>
            </div>
        </div>
        <?php else: ?>
        <div class="sensor-card light-sensor maintenance-sensor">
            <div class="sensor-header">
                <div class="sensor-title">
                    <i class="fas fa-sun"></i>
                    <h3>Luminosité</h3>
                </div>
                <div class="sensor-status status-maintenance">
                    <i class="fas fa-circle"></i> Inactif
                </div>
            </div>
            <div class="sensor-body">
                <div class="sensor-value-display">
                    <div class="current-value maintenance-mode">
                        <span class="value">Pas de données</span>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Capteur Son -->
        <?php if (!empty($data['sound_sensor'])): $sensor = $data['sound_sensor']; ?>
        <div class="sensor-card sound-sensor" data-sensor-type="sound">
            <div class="sensor-header">
                <div class="sensor-title">
                    <i class="fas fa-microphone"></i>
                    <h3>Niveau Sonore</h3>
                </div>
                <div class="sensor-status status-active">
                    <i class="fas fa-circle"></i> Actif
                </div>
            </div>
            <div class="sensor-body">
                <div class="sensor-value-display">
                    <div class="current-value">
                        <span class="value" data-value="sound"><?= number_format($sensor['valeur'], 1) ?></span>
                        <span class="unit">dB</span>
                    </div>
                    <div class="value-indicator <?= $sensor['valeur'] > 80 ? 'high' : ($sensor['valeur'] > 60 ? 'medium' : 'normal') ?>"></div>
                </div>
                <div class="sensor-info-grid">
                    <div class="info-item">
                        <span class="label">ID Capteur:</span>
                        <span class="value">SOUND_001</span>
                    </div>
                    <div class="info-item">
                        <span class="label">Dernière lecture:</span>
                        <span class="value" data-timestamp="sound"><?= date('H:i:s', strtotime($sensor['heure'])) ?></span>
                    </div>
                </div>
            </div>
        </div>
        <?php else: ?>
        <div class="sensor-card sound-sensor maintenance-sensor">
            <div class="sensor-header">
                <div class="sensor-title">
                    <i class="fas fa-microphone"></i>
                    <h3>Niveau Sonore</h3>
                </div>
                <div class="sensor-status status-maintenance">
                    <i class="fas fa-circle"></i> Inactif
                </div>
            </div>
            <div class="sensor-body">
                <div class="sensor-value-display">
                    <div class="current-value maintenance-mode">
                        <span class="value">Pas de données</span>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Capteurs de Proximité (LIMITÉ À 3 PLACES) -->
        <?php if (!empty($data['parking_sensors'])): ?>
            <?php foreach ($data['parking_sensors'] as $index => $sensor): ?>
            <div class="sensor-card proximity-sensor" data-sensor-type="proximity" data-place="<?= $sensor['place'] ?>">
                <div class="sensor-header">
                    <div class="sensor-title">
                        <i class="fas fa-car"></i>
                        <h3>Détecteur Place <?= htmlspecialchars($sensor['place']) ?></h3>
                    </div>
                    <div class="sensor-status status-active">
                        <i class="fas fa-circle"></i> Actif
                    </div>
                </div>
                <div class="sensor-body">
                    <div class="sensor-value-display">
                        <div class="current-value <?= $sensor['valeur'] ? 'presence-occupied' : 'presence-free' ?>">
                            <span class="value" data-value="proximity-<?= $sensor['place'] ?>"><?= $sensor['valeur'] ? 'OCCUPÉE' : 'LIBRE' ?></span>
                            <div class="parking-visual">
                                <div class="car-icon <?= $sensor['valeur'] ? 'present' : 'absent' ?>">
                                    <i class="fas fa-car"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="sensor-info-grid">
                        <div class="info-item">
                            <span class="label">ID Capteur:</span>
                            <span class="value">PROX_<?= htmlspecialchars($sensor['place']) ?></span>
                        </div>
                        <div class="info-item">
                            <span class="label">Dernière lecture:</span>
                            <span class="value" data-timestamp="proximity-<?= $sensor['place'] ?>"><?= date('H:i:s', strtotime($sensor['heure'])) ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
             <div class="sensor-card proximity-sensor maintenance-sensor">
                <div class="sensor-header">
                    <div class="sensor-title">
                        <i class="fas fa-car"></i>
                        <h3>Détecteurs de Place</h3>
                    </div>
                    <div class="sensor-status status-maintenance">
                        <i class="fas fa-circle"></i> Inactif
                    </div>
                </div>
                <div class="sensor-body">
                    <div class="sensor-value-display">
                        <div class="current-value maintenance-mode">
                            <span class="value">Pas de données</span>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
/* Styles pour la page des capteurs */
.dashboard-container { max-width: 1400px; margin: 0 auto; padding: 2rem; }
.dashboard-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; }
.header-content h1 { color: white; font-size: 2.5rem; font-weight: 700; margin-bottom: 0.5rem; text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3); }
.header-content h1 i { color: #ffd700; margin-right: 1rem; }
.header-content p { color: rgba(255, 255, 255, 0.9); font-size: 1.1rem; margin: 0; }
.refresh-controls { display: flex; align-items: center; gap: 1rem; }
.last-update { color: rgba(255, 255, 255, 0.8); font-size: 0.9rem; }
.dashboard-nav { background: rgba(255, 255, 255, 0.15); backdrop-filter: blur(15px); border-radius: 15px; padding: 1rem 2rem; margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center; border: 1px solid rgba(255, 255, 255, 0.2); }
.nav-tabs { display: flex; gap: 1rem; }
.nav-tab { color: rgba(255, 255, 255, 0.8); text-decoration: none; padding: 0.75rem 1.5rem; border-radius: 10px; transition: all 0.3s ease; font-weight: 500; display: flex; align-items: center; gap: 0.5rem; }
.nav-tab:hover, .nav-tab.active { background: rgba(255, 255, 255, 0.2); color: white; transform: translateY(-2px); }
.user-info { color: white; font-weight: 500; display: flex; align-items: center; gap: 0.5rem; }
.btn { background: #3b82f6; color: white; padding: 0.75rem 1.5rem; border-radius: 10px; text-decoration: none; font-weight: 500; transition: all 0.3s ease; border: none; cursor: pointer; display: flex; align-items: center; gap: 0.5rem; }
.btn:hover { background: #2563eb; transform: translateY(-2px); }
.btn-secondary { background: rgba(255, 255, 255, 0.2); }
.btn-secondary:hover { background: rgba(255, 255, 255, 0.3); }

/* Grille des capteurs */
.sensors-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 2rem; margin-bottom: 3rem; }
.sensor-card { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); border-radius: 20px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1); border: 1px solid rgba(255, 255, 255, 0.2); overflow: hidden; transition: all 0.3s ease; }
.sensor-card:hover { transform: translateY(-5px); box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15); }
.temperature-sensor { border-left: 4px solid #f59e0b; }
.gas-sensor { border-left: 4px solid #8b5cf6; }
.light-sensor { border-left: 4px solid #fbbf24; }
.sound-sensor { border-left: 4px solid #06b6d4; }
.proximity-sensor { border-left: 4px solid #22c55e; }
.maintenance-sensor { border-left: 4px solid #64748b; opacity: 0.7; }
.sensor-header { background: linear-gradient(135deg, rgba(30, 64, 175, 0.1), rgba(59, 130, 246, 0.1)); padding: 1.5rem; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid rgba(59, 130, 246, 0.1); }
.sensor-title { display: flex; align-items: center; gap: 0.75rem; }
.sensor-title i { font-size: 1.25rem; }
.temperature-sensor .sensor-title i { color: #f59e0b; }
.gas-sensor .sensor-title i { color: #8b5cf6; }
.light-sensor .sensor-title i { color: #fbbf24; }
.sound-sensor .sensor-title i { color: #06b6d4; }
.proximity-sensor .sensor-title i { color: #22c55e; }
.sensor-title h3 { margin: 0; color: #1e293b; font-size: 1.125rem; font-weight: 600; }
.sensor-status { padding: 0.5rem 1rem; border-radius: 25px; font-size: 0.875rem; font-weight: 600; display: flex; align-items: center; gap: 0.5rem; }
.status-active { background: #dcfce7; color: #16a34a; }
.status-maintenance { background: #f1f5f9; color: #64748b; }
.sensor-body { padding: 1.5rem; }
.sensor-value-display { text-align: center; margin-bottom: 1.5rem; position: relative; }
.current-value { display: flex; align-items: baseline; justify-content: center; gap: 0.5rem; margin-bottom: 1rem; }
.current-value .value { font-size: 2.5rem; font-weight: 700; color: #1e293b; }
.current-value .unit { font-size: 1.25rem; color: #64748b; font-weight: 500; }
.maintenance-mode .value { font-size: 1.25rem; color: #64748b; font-style: italic; }
.value-indicator { width: 100%; height: 4px; border-radius: 2px; margin-top: 1rem; }
.value-indicator.normal { background: #22c55e; }
.value-indicator.medium { background: #f59e0b; }
.value-indicator.high { background: #ef4444; }
.value-indicator.low { background: #3b82f6; }
.presence-free .value { color: #22c55e; }
.presence-occupied .value { color: #ef4444; }
.parking-visual { margin-top: 1rem; }
.car-icon { font-size: 2rem; transition: all 0.3s ease; }
.car-icon.present { color: #ef4444; }
.car-icon.absent { color: #d1d5db; }
.sensor-info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
.info-item { display: flex; flex-direction: column; gap: 0.25rem; }
.info-item .label { font-size: 0.875rem; color: #64748b; font-weight: 500; }
.info-item .value { font-size: 0.875rem; color: #1e293b; font-weight: 600; }

/* Responsive */
@media (max-width: 768px) {
    .dashboard-header { flex-direction: column; gap: 1rem; text-align: center; }
    .dashboard-nav { flex-direction: column; gap: 1rem; text-align: center; }
    .nav-tabs { flex-wrap: wrap; justify-content: center; }
    .sensors-grid { grid-template-columns: 1fr; }
    .sensor-info-grid { grid-template-columns: 1fr; }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const refreshBtn = document.getElementById('refresh-sensors');
    const lastUpdateSpan = document.getElementById('last-update');
    
    // Fonction pour actualiser les données des capteurs
    async function refreshSensorsData() {
        if (refreshBtn) {
            const icon = refreshBtn.querySelector('i');
            icon.classList.add('fa-spin');
            refreshBtn.disabled = true;
        }
        
        try {
            const response = await fetch('<?= BASE_URL ?>/iot-dashboard/get-sensors-data');
            if (!response.ok) throw new Error('Erreur réseau');
            
            const result = await response.json();
            if (result.success) {
                updateSensorValues(result.data);
                if (lastUpdateSpan) {
                    lastUpdateSpan.textContent = new Date().toLocaleTimeString();
                }
            }
        } catch (error) {
            console.error('Erreur lors de l\'actualisation:', error);
        } finally {
            if (refreshBtn) {
                const icon = refreshBtn.querySelector('i');
                icon.classList.remove('fa-spin');
                refreshBtn.disabled = false;
            }
        }
    }
    
    // Fonction pour mettre à jour les valeurs affichées
    function updateSensorValues(data) {
        // Température
        if (data.temp_sensor) {
            updateSensorValue('temperature', data.temp_sensor.valeur, '°C');
            updateTimestamp('temperature', data.temp_sensor.heure);
        }
        
        // Gaz
        if (data.gas_sensor) {
            updateSensorValue('gas', Math.round(data.gas_sensor.valeur), 'ppm');
            updateTimestamp('gas', data.gas_sensor.heure);
        }
        
        // Luminosité
        if (data.light_sensor) {
            updateSensorValue('light', Math.round(data.light_sensor.valeur), 'lux');
            updateTimestamp('light', data.light_sensor.heure);
        }
        
        // Son
        if (data.sound_sensor) {
            updateSensorValue('sound', data.sound_sensor.valeur.toFixed(1), 'dB');
            updateTimestamp('sound', data.sound_sensor.heure);
        }
        
        // Capteurs de proximité
        if (data.parking_sensors) {
            data.parking_sensors.forEach(sensor => {
                const status = sensor.valeur ? 'OCCUPÉE' : 'LIBRE';
                updateSensorValue(`proximity-${sensor.place}`, status);
                updateTimestamp(`proximity-${sensor.place}`, sensor.heure);
                
                // Mettre à jour l'apparence visuelle
                const card = document.querySelector(`[data-place="${sensor.place}"]`);
                if (card) {
                    const valueDiv = card.querySelector('.current-value');
                    const carIcon = card.querySelector('.car-icon');
                    
                    if (sensor.valeur) {
                        valueDiv.className = 'current-value presence-occupied';
                        if (carIcon) carIcon.className = 'car-icon present';
                    } else {
                        valueDiv.className = 'current-value presence-free';
                        if (carIcon) carIcon.className = 'car-icon absent';
                    }
                }
            });
        }
    }
    
    function updateSensorValue(type, value, unit = '') {
        const valueElement = document.querySelector(`[data-value="${type}"]`);
        if (valueElement) {
            valueElement.textContent = value;
        }
    }
    
    function updateTimestamp(type, timestamp) {
        const timestampElement = document.querySelector(`[data-timestamp="${type}"]`);
        if (timestampElement) {
            const date = new Date(timestamp);
            timestampElement.textContent = date.toLocaleTimeString();
        }
    }
    
    // Event listener pour le bouton d'actualisation
    if (refreshBtn) {
        refreshBtn.addEventListener('click', refreshSensorsData);
    }
    
    // Actualisation automatique toutes les 30 secondes
    setInterval(refreshSensorsData, 30000);
});
</script>

<?php require_once ROOT_PATH . '/app/views/partials/footer.php'; ?>
