<?php require_once ROOT_PATH . '/app/views/partials/header.php'; ?>

<div class="dashboard-container">
    <!-- Header principal -->
    <div class="dashboard-header">
        <div class="header-content">
            <h1><i class="fas fa-microchip"></i> Dashboard IoT</h1>
            <p>Surveillance et contrôle en temps réel du système de parking intelligent</p>
        </div>
        <div class="refresh-indicator">
            <i class="fas fa-sync-alt" id="refresh-icon"></i>
            <span>Dernière mise à jour: <span id="last-update"><?= date('H:i:s') ?></span></span>
        </div>
    </div>

    <!-- Navigation -->
    <div class="dashboard-nav">
        <div class="nav-tabs">
            <a href="<?= BASE_URL ?>/iot-dashboard" class="nav-tab active"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
            <a href="<?= BASE_URL ?>/iot-dashboard/capteurs" class="nav-tab"><i class="fas fa-satellite-dish"></i> Capteurs</a>
            <a href="<?= BASE_URL ?>/iot-dashboard/actionneurs" class="nav-tab"><i class="fas fa-cogs"></i> Actionneurs</a>
            <a href="<?= BASE_URL ?>/admin" class="nav-tab"><i class="fas fa-arrow-left"></i> Retour Admin</a>
        </div>
        <div class="user-info">
            <i class="fas fa-user"></i> <?= htmlspecialchars($_SESSION['user_email'] ?? 'admin@isep.fr') ?>
        </div>
    </div>

    <!-- Statistiques rapides -->
    <div class="stats-grid">
        <div class="stat-card temperature">
            <div class="stat-icon"><i class="fas fa-thermometer-half"></i></div>
            <div class="stat-content">
                <h3>Température</h3>
                <div class="stat-value"><?= !empty($data['temp_sensor']) ? number_format($data['temp_sensor']['valeur'], 1) . '°C' : 'N/A' ?></div>
            </div>
        </div>
        
        <div class="stat-card air-quality">
            <div class="stat-icon"><i class="fas fa-smog"></i></div>
            <div class="stat-content">
                <h3>Qualité Air</h3>
                <div class="stat-value"><?= !empty($data['gas_sensor']) ? number_format($data['gas_sensor']['valeur'], 0) . ' ppm' : 'N/A' ?></div>
            </div>
        </div>
        
        <div class="stat-card parking">
            <div class="stat-icon"><i class="fas fa-car"></i></div>
            <div class="stat-content">
                <h3>Places Libres</h3>
                <div class="stat-value"><?= count(array_filter($data['parking_sensors'] ?? [], function($s) { return !$s['valeur']; })) ?>/<?= count($data['parking_sensors'] ?? []) ?></div>
            </div>
        </div>
        
        <div class="stat-card devices">
            <div class="stat-icon"><i class="fas fa-microchip"></i></div>
            <div class="stat-content">
                <h3>Dispositifs Actifs</h3>
                <div class="stat-value"><?= count($data['leds'] ?? []) + count($data['motors'] ?? []) ?></div>
            </div>
        </div>
    </div>

    <!-- Aperçu des capteurs -->
    <div class="section">
        <div class="section-header">
            <h2><i class="fas fa-satellite-dish"></i> Aperçu des Capteurs</h2>
            <a href="<?= BASE_URL ?>/iot-dashboard/capteurs" class="btn btn-primary">Voir tous les capteurs</a>
        </div>
        
        <div class="sensors-preview">
            <?php if (!empty($data['parking_sensors'])): ?>
                <?php foreach (array_slice($data['parking_sensors'], 0, 3) as $sensor): ?>
                <div class="sensor-mini-card <?= $sensor['valeur'] ? 'occupied' : 'free' ?>">
                    <div class="sensor-mini-header">
                        <i class="fas fa-car"></i>
                        <span>Place <?= htmlspecialchars($sensor['place']) ?></span>
                    </div>
                    <div class="sensor-mini-status">
                        <?= $sensor['valeur'] ? 'OCCUPÉE' : 'LIBRE' ?>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-data">Aucune donnée de capteur disponible</div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Aperçu des actionneurs -->
    <div class="section">
        <div class="section-header">
            <h2><i class="fas fa-cogs"></i> Aperçu des Actionneurs</h2>
            <a href="<?= BASE_URL ?>/iot-dashboard/actionneurs" class="btn btn-primary">Gérer les actionneurs</a>
        </div>
        
        <div class="actuators-preview">
            <?php if (!empty($data['leds'])): ?>
                <?php foreach (array_slice($data['leds'], 0, 4) as $led): ?>
                <div class="actuator-mini-card led">
                    <div class="actuator-mini-header">
                        <i class="fas fa-lightbulb"></i>
                        <span>LED #<?= $led['id'] ?></span>
                    </div>
                    <div class="actuator-mini-status <?= $led['etat'] ? 'active' : 'inactive' ?>">
                        <?= $led['etat'] ? 'ACTIVE' : 'ÉTEINTE' ?>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
            
            <?php if (!empty($data['motors'])): ?>
                <?php foreach (array_slice($data['motors'], 0, 2) as $motor): ?>
                <div class="actuator-mini-card motor">
                    <div class="actuator-mini-header">
                        <i class="fas fa-cog"></i>
                        <span>Moteur #<?= $motor['id'] ?></span>
                    </div>
                    <div class="actuator-mini-status <?= $motor['etat'] ? 'running' : 'stopped' ?>">
                        <?= $motor['etat'] ? $motor['vitesse'] . ' RPM' : 'ARRÊTÉ' ?>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Affichage OLED -->
    <?php if (!empty($data['oled_data'])): ?>
    <div class="section">
        <div class="section-header">
            <h2><i class="fas fa-tv"></i> Affichage OLED</h2>
        </div>
        
        <div class="oled-display">
            <div class="oled-screen">
                <div class="oled-line">Places: <?= $data['oled_data']['places_dispo'] ?></div>
                <div class="oled-line">Bornes: <?= $data['oled_data']['bornes_dispo'] ?></div>
                <?php if (!empty($data['oled_data']['utilisateur'])): ?>
                <div class="oled-line">User: <?= htmlspecialchars($data['oled_data']['utilisateur']) ?></div>
                <?php endif; ?>
                <?php if (!empty($data['oled_data']['plaque_immatriculation'])): ?>
                <div class="oled-line">Plaque: <?= htmlspecialchars($data['oled_data']['plaque_immatriculation']) ?></div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<style>
/* Styles pour le dashboard IoT */
.dashboard-container { max-width: 1400px; margin: 0 auto; padding: 2rem; }
.dashboard-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; }
.header-content h1 { color: white; font-size: 2.5rem; font-weight: 700; margin-bottom: 0.5rem; text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3); }
.header-content h1 i { color: #ffd700; margin-right: 1rem; }
.header-content p { color: rgba(255, 255, 255, 0.9); font-size: 1.1rem; margin: 0; }
.refresh-indicator { color: rgba(255, 255, 255, 0.8); font-size: 0.9rem; display: flex; align-items: center; gap: 0.5rem; }
.dashboard-nav { background: rgba(255, 255, 255, 0.15); backdrop-filter: blur(15px); border-radius: 15px; padding: 1rem 2rem; margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center; border: 1px solid rgba(255, 255, 255, 0.2); }
.nav-tabs { display: flex; gap: 1rem; }
.nav-tab { color: rgba(255, 255, 255, 0.8); text-decoration: none; padding: 0.75rem 1.5rem; border-radius: 10px; transition: all 0.3s ease; font-weight: 500; display: flex; align-items: center; gap: 0.5rem; }
.nav-tab:hover, .nav-tab.active { background: rgba(255, 255, 255, 0.2); color: white; transform: translateY(-2px); }
.user-info { color: white; font-weight: 500; display: flex; align-items: center; gap: 0.5rem; }

/* Grille de statistiques */
.stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 3rem; }
.stat-card { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); border-radius: 15px; padding: 1.5rem; display: flex; align-items: center; gap: 1rem; box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1); border: 1px solid rgba(255, 255, 255, 0.2); transition: all 0.3s ease; }
.stat-card:hover { transform: translateY(-5px); box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15); }
.stat-icon { width: 60px; height: 60px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: white; }
.temperature .stat-icon { background: linear-gradient(135deg, #f59e0b, #f97316); }
.air-quality .stat-icon { background: linear-gradient(135deg, #8b5cf6, #a855f7); }
.parking .stat-icon { background: linear-gradient(135deg, #3b82f6, #2563eb); }
.devices .stat-icon { background: linear-gradient(135deg, #22c55e, #16a34a); }
.stat-content h3 { margin: 0 0 0.5rem 0; color: #64748b; font-size: 0.9rem; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px; }
.stat-value { font-size: 1.5rem; font-weight: 700; color: #1e293b; }

/* Sections */
.section { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); border-radius: 20px; padding: 2rem; margin-bottom: 2rem; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1); border: 1px solid rgba(255, 255, 255, 0.2); }
.section-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }
.section-header h2 { margin: 0; color: #1e293b; font-size: 1.5rem; font-weight: 600; display: flex; align-items: center; gap: 0.75rem; }
.section-header h2 i { color: #3b82f6; }
.btn { background: #3b82f6; color: white; padding: 0.75rem 1.5rem; border-radius: 10px; text-decoration: none; font-weight: 500; transition: all 0.3s ease; border: none; cursor: pointer; }
.btn:hover { background: #2563eb; transform: translateY(-2px); }

/* Aperçus */
.sensors-preview, .actuators-preview { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; }
.sensor-mini-card, .actuator-mini-card { background: #f8fafc; border-radius: 12px; padding: 1rem; text-align: center; border: 2px solid transparent; transition: all 0.3s ease; }
.sensor-mini-card.free { border-color: #22c55e; background: #f0fdf4; }
.sensor-mini-card.occupied { border-color: #ef4444; background: #fef2f2; }
.sensor-mini-header, .actuator-mini-header { display: flex; align-items: center; justify-content: center; gap: 0.5rem; margin-bottom: 0.5rem; font-weight: 600; color: #64748b; }
.sensor-mini-status, .actuator-mini-status { font-weight: 700; font-size: 0.9rem; }
.sensor-mini-card.free .sensor-mini-status { color: #22c55e; }
.sensor-mini-card.occupied .sensor-mini-status { color: #ef4444; }
.actuator-mini-status.active, .actuator-mini-status.running { color: #22c55e; }
.actuator-mini-status.inactive, .actuator-mini-status.stopped { color: #64748b; }

/* Affichage OLED */
.oled-display { display: flex; justify-content: center; }
.oled-screen { background: #1a1a1a; color: #00ff00; font-family: 'Courier New', monospace; padding: 1.5rem; border-radius: 10px; min-width: 300px; text-align: center; box-shadow: inset 0 0 20px rgba(0, 255, 0, 0.1); }
.oled-line { margin-bottom: 0.5rem; font-size: 1.1rem; text-shadow: 0 0 5px #00ff00; }

.no-data { text-align: center; color: #64748b; font-style: italic; padding: 2rem; }

/* Responsive */
@media (max-width: 768px) {
    .dashboard-header { flex-direction: column; gap: 1rem; text-align: center; }
    .dashboard-nav { flex-direction: column; gap: 1rem; text-align: center; }
    .nav-tabs { flex-wrap: wrap; justify-content: center; }
    .stats-grid { grid-template-columns: 1fr; }
    .section-header { flex-direction: column; gap: 1rem; text-align: center; }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Actualisation automatique des données toutes les 30 secondes
    setInterval(updateDashboard, 30000);
    
    function updateDashboard() {
        const refreshIcon = document.getElementById('refresh-icon');
        const lastUpdate = document.getElementById('last-update');
        
        if (refreshIcon) {
            refreshIcon.classList.add('fa-spin');
        }
        
        // Simuler une mise à jour (vous pouvez implémenter un appel AJAX ici)
        setTimeout(() => {
            if (refreshIcon) {
                refreshIcon.classList.remove('fa-spin');
            }
            if (lastUpdate) {
                lastUpdate.textContent = new Date().toLocaleTimeString();
            }
        }, 1000);
    }
});
</script>

<?php require_once ROOT_PATH . '/app/views/partials/footer.php'; ?>
