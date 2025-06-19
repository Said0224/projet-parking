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
    <div class="sensors-grid">
        <!-- Capteur Température -->
        <?php if (!empty($data['temp_sensor'])): $sensor = $data['temp_sensor']; ?>
        <div class="sensor-card temperature-sensor">
            <div class="sensor-header"><div class="sensor-title"><i class="fas fa-thermometer-half"></i><h3>Température Ambiante</h3></div><div class="sensor-status status-active"><i class="fas fa-circle"></i> Actif</div></div>
            <div class="sensor-body">
                <div class="sensor-value-display"><div class="current-value"><span class="value"><?= number_format($sensor['valeur'], 1) ?></span><span class="unit">°C</span></div></div>
                <div class="sensor-info-grid">
                    <div class="info-item"><span class="label">ID Capteur:</span><span class="value">TEMP_001</span></div>
                    <div class="info-item"><span class="label">Dernière lecture:</span><span class="value"><?= date('H:i:s', strtotime($sensor['heure'])) ?></span></div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Capteur de Gaz -->
        <?php if (!empty($data['gas_sensor'])): $sensor = $data['gas_sensor']; ?>
        <div class="sensor-card gas-sensor">
            <div class="sensor-header"><div class="sensor-title"><i class="fas fa-smog"></i><h3>Qualité de l'Air (Gaz)</h3></div><div class="sensor-status status-active"><i class="fas fa-circle"></i> Actif</div></div>
            <div class="sensor-body">
                <div class="sensor-value-display"><div class="current-value"><span class="value"><?= number_format($sensor['valeur'], 0) ?></span><span class="unit">ppm</span></div></div>
                <div class="sensor-info-grid">
                    <div class="info-item"><span class="label">ID Capteur:</span><span class="value">GAS_001</span></div>
                    <div class="info-item"><span class="label">Dernière lecture:</span><span class="value"><?= date('H:i:s', strtotime($sensor['heure'])) ?></span></div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Capteur de Luminosité -->
        <?php if (!empty($data['light_sensor'])): $sensor = $data['light_sensor']; ?>
        <div class="sensor-card light-sensor">
            <div class="sensor-header"><div class="sensor-title"><i class="fas fa-sun"></i><h3>Luminosité</h3></div><div class="sensor-status status-active"><i class="fas fa-circle"></i> Actif</div></div>
            <div class="sensor-body">
                <div class="sensor-value-display"><div class="current-value"><span class="value"><?= number_format($sensor['valeur'], 0) ?></span><span class="unit">lux</span></div></div>
                <div class="sensor-info-grid">
                    <div class="info-item"><span class="label">ID Capteur:</span><span class="value">LIGHT_001</span></div>
                    <div class="info-item"><span class="label">Dernière lecture:</span><span class="value"><?= date('H:i:s', strtotime($sensor['heure'])) ?></span></div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Capteur Son -->
        <?php if (!empty($data['sound_sensor'])): $sensor = $data['sound_sensor']; ?>
        <div class="sensor-card sound-sensor">
            <div class="sensor-header"><div class="sensor-title"><i class="fas fa-microphone"></i><h3>Niveau Sonore</h3></div><div class="sensor-status status-active"><i class="fas fa-circle"></i> Actif</div></div>
            <div class="sensor-body">
                <div class="sensor-value-display"><div class="current-value"><span class="value"><?= number_format($sensor['valeur'], 1) ?></span><span class="unit">dB</span></div></div>
                <div class="sensor-info-grid">
                    <div class="info-item"><span class="label">ID Capteur:</span><span class="value">SOUND_001</span></div>
                    <div class="info-item"><span class="label">Dernière lecture:</span><span class="value"><?= date('H:i:s', strtotime($sensor['heure'])) ?></span></div>
                </div>
            </div>
        </div>
        <?php else: ?>
        <div class="sensor-card sound-sensor maintenance-sensor">
            <div class="sensor-header"><div class="sensor-title"><i class="fas fa-microphone"></i><h3>Niveau Sonore</h3></div><div class="sensor-status status-maintenance"><i class="fas fa-circle"></i> Inactif</div></div>
            <div class="sensor-body"><div class="sensor-value-display"><div class="current-value maintenance-mode"><span class="value">Pas de données</span></div></div></div>
        </div>
        <?php endif; ?>
        
        <!-- Capteurs de Proximité -->
        <?php if (!empty($data['parking_sensors'])): ?>
            <?php foreach ($data['parking_sensors'] as $sensor): ?>
            <div class="sensor-card proximity-sensor">
                <div class="sensor-header">
                    <div class="sensor-title"><i class="fas fa-car"></i><h3>Détecteur Place <?= htmlspecialchars($sensor['place']) ?></h3></div>
                    <div class="sensor-status status-active"><i class="fas fa-circle"></i> Actif</div>
                </div>
                <div class="sensor-body">
                    <div class="sensor-value-display">
                        <div class="current-value <?= $sensor['valeur'] ? 'presence-occupée' : 'presence-free' ?>">
                            <span class="value"><?= $sensor['valeur'] ? 'OCCUPÉE' : 'LIBRE' ?></span>
                        </div>
                    </div>
                     <div class="sensor-info-grid">
                        <div class="info-item"><span class="label">ID Capteur:</span><span class="value">PROX_<?= htmlspecialchars($sensor['place']) ?></span></div>
                        <div class="info-item"><span class="label">Dernière lecture:</span><span class="value"><?= date('H:i:s', strtotime($sensor['heure'])) ?></span></div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
             <div class="sensor-card proximity-sensor maintenance-sensor">
                <div class="sensor-header"><div class="sensor-title"><i class="fas fa-car"></i><h3>Détecteurs de Place</h3></div><div class="sensor-status status-maintenance"><i class="fas fa-circle"></i> Inactif</div></div>
                <div class="sensor-body"><div class="sensor-value-display"><div class="current-value maintenance-mode"><span class="value">Pas de données</span></div></div></div>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
/* Styles existants de la page */
.dashboard-container { max-width: 1400px; margin: 0 auto; padding: 2rem; }
.dashboard-header { text-align: center; margin-bottom: 2rem; }
.header-content h1 { color: white; font-size: 2.5rem; font-weight: 700; margin-bottom: 0.5rem; text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3); }
.header-content h1 i { color: #ffd700; margin-right: 1rem; }
.header-content p { color: rgba(255, 255, 255, 0.9); font-size: 1.1rem; margin: 0; }
.dashboard-nav { background: rgba(255, 255, 255, 0.15); backdrop-filter: blur(15px); border-radius: 15px; padding: 1rem 2rem; margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center; border: 1px solid rgba(255, 255, 255, 0.2); }
.nav-tabs { display: flex; gap: 1rem; flex-wrap: wrap; }
.nav-tab { color: rgba(255, 255, 255, 0.8); text-decoration: none; padding: 0.75rem 1.5rem; border-radius: 10px; transition: all 0.3s ease; font-weight: 500; display: flex; align-items: center; gap: 0.5rem; }
.nav-tab:hover, .nav-tab.active { background: rgba(255, 255, 255, 0.2); color: white; transform: translateY(-2px); }
.user-info { color: white; font-weight: 500; display: flex; align-items: center; gap: 0.5rem; }
.sensors-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 1.5rem; margin-bottom: 2rem; }
.sensor-card { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); border-radius: 20px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1); border: 1px solid rgba(255, 255, 255, 0.2); overflow: hidden; transition: all 0.3s ease; }
.sensor-card:hover { transform: translateY(-5px); box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15); }
.temperature-sensor { border-left: 4px solid #3b82f6; }
.proximity-sensor { border-left: 4px solid #10b981; }
.gas-sensor { border-left: 4px solid #ef4444; }
.light-sensor { border-left: 4px solid #f59e0b; }
.sound-sensor { border-left: 4px solid #8b5cf6; }
.maintenance-sensor { border-left: 4px solid #6b7280; }
.sensor-header { background: linear-gradient(135deg, rgba(30, 64, 175, 0.1), rgba(59, 130, 246, 0.1)); padding: 1.5rem; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid rgba(59, 130, 246, 0.1); }
.sensor-title { display: flex; align-items: center; gap: 0.75rem; }
.sensor-title i { font-size: 1.25rem; }
.temperature-sensor .sensor-title i { color: #3b82f6; }
.proximity-sensor .sensor-title i { color: #10b981; }
.gas-sensor .sensor-title i { color: #ef4444; }
.light-sensor .sensor-title i { color: #f59e0b; }
.sound-sensor .sensor-title i { color: #8b5cf6; }
.sensor-title h3 { margin: 0; color: #1e293b; font-size: 1.125rem; font-weight: 600; }
.sensor-status { padding: 0.5rem 1rem; border-radius: 25px; font-size: 0.875rem; font-weight: 600; display: flex; align-items: center; gap: 0.5rem; }
.status-active { background: #dcfce7; color: #166534; }
.status-maintenance { background: #f3f4f6; color: #4b5563; }
.sensor-body { padding: 1.5rem; }
.sensor-value-display { text-align: center; margin-bottom: 1.5rem; padding: 1.5rem; background: #f8fafc; border-radius: 15px; }
.current-value { font-size: 2.5rem; font-weight: 700; margin-bottom: 0.25rem; }
.current-value .unit { font-size: 1.5rem; color: #64748b; margin-left: 0.5rem; }
.presence-occupée .value { color: #ef4444; }
.presence-free .value { color: #22c55e; }
.maintenance-mode .value { color: #6b7280; font-size: 1.5rem; }
.sensor-info-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem; }
.info-item { display: flex; flex-direction: column; gap: 0.25rem; padding: 0.75rem; background: #f8fafc; border-radius: 10px; }
.info-item .label { color: #64748b; font-size: 0.75rem; font-weight: 500; text-transform: uppercase; }
.info-item .value { color: #1e293b; font-weight: 600; font-size: 0.875rem; }
@media (max-width: 768px) {
    .dashboard-nav { flex-direction: column; }
    .sensors-grid { grid-template-columns: 1fr; }
    .sensor-info-grid { grid-template-columns: 1fr; }
}
</style>

<?php require_once ROOT_PATH . '/app/views/partials/footer.php'; ?>