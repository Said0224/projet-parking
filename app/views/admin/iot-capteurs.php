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

<!-- SUPPRIMEZ TOUT LE BLOC <style> QUI SE TROUVAIT ICI -->

<?php require_once ROOT_PATH . '/app/views/partials/footer.php'; ?>