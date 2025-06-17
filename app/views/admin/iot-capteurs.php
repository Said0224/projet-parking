<?php 
// Vérification que l'utilisateur est admin
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header('Location: /login');
    exit;
}

require_once ROOT_PATH . '/app/views/partials/header.php'; 
?>

<div class="dashboard-container">
    <!-- Header principal -->
    <div class="dashboard-header">
        <div class="header-content">
            <h1><i class="fas fa-satellite-dish"></i> Gestion des Capteurs IoT</h1>
            <p>Surveillance et monitoring en temps réel des capteurs environnementaux</p>
        </div>
    </div>

    <!-- Navigation -->
    <div class="dashboard-nav">
        <div class="nav-tabs">
            <a href="/iot-dashboard" class="nav-tab">
                <i class="fas fa-arrow-left"></i> Retour IoT
            </a>
            <a href="/admin/iot-capteurs" class="nav-tab active">
                <i class="fas fa-satellite-dish"></i> Capteurs
            </a>
            <a href="/admin/iot-actionneurs" class="nav-tab">
                <i class="fas fa-cogs"></i> Actionneurs
            </a>
            <a href="/logout" class="nav-tab">
                <i class="fas fa-sign-out-alt"></i> Déconnexion
            </a>
        </div>
        <div class="user-info">
            <i class="fas fa-user"></i> <?= htmlspecialchars($_SESSION['user_email'] ?? 'admin@isep.fr') ?>
        </div>
    </div>

    <!-- Statistiques capteurs -->
    <div class="sensors-stats">
        <div class="stat-card active-sensors">
            <div class="stat-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-info">
                <div class="stat-number">7</div>
                <div class="stat-label">Capteurs Actifs</div>
            </div>
        </div>
        
        <div class="stat-card inactive-sensors">
            <div class="stat-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="stat-info">
                <div class="stat-number">1</div>
                <div class="stat-label">En Maintenance</div>
            </div>
        </div>
        
        <div class="stat-card temperature">
            <div class="stat-icon">
                <i class="fas fa-thermometer-half"></i>
            </div>
            <div class="stat-info">
                <div class="stat-number">22°C</div>
                <div class="stat-label">Température</div>
            </div>
        </div>
        
        <div class="stat-card data-rate">
            <div class="stat-icon">
                <i class="fas fa-tachometer-alt"></i>
            </div>
            <div class="stat-info">
                <div class="stat-number">2s</div>
                <div class="stat-label">Fréquence</div>
            </div>
        </div>
    </div>

    <!-- Liste des capteurs -->
    <div class="sensors-grid">
        <!-- Capteur température et humidité -->
        <div class="sensor-card temperature-sensor">
            <div class="sensor-header">
                <div class="sensor-title">
                    <i class="fas fa-thermometer-half"></i>
                    <h3>Capteur Température & Humidité</h3>
                </div>
                <div class="sensor-status status-active">
                    <i class="fas fa-circle"></i> Actif
                </div>
            </div>
            
            <div class="sensor-body">
                <div class="sensor-value-display dual-values">
                    <div class="value-item">
                        <div class="current-value">
                            <span class="value">22.3</span>
                            <span class="unit">°C</span>
                        </div>
                        <div class="value-label">Température</div>
                    </div>
                    <div class="value-item">
                        <div class="current-value">
                            <span class="value">65</span>
                            <span class="unit">%</span>
                        </div>
                        <div class="value-label">Humidité</div>
                    </div>
                </div>
                
                <div class="sensor-info-grid">
                    <div class="info-item">
                        <span class="label">ID Capteur:</span>
                        <span class="value">DHT22_001</span>
                    </div>
                    <div class="info-item">
                        <span class="label">Type:</span>
                        <span class="value">DHT22</span>
                    </div>
                    <div class="info-item">
                        <span class="label">Dernière lecture:</span>
                        <span class="value"><?= date('H:i:s') ?></span>
                    </div>
                    <div class="info-item">
                        <span class="label">Précision:</span>
                        <span class="value">±0.5°C / ±2%</span>
                    </div>
                </div>
                
                <div class="sensor-actions">
                    <button class="btn-action" onclick="calibrateSensor('DHT22_001')">
                        <i class="fas fa-cog"></i> Calibrer
                    </button>
                    <button class="btn-action" onclick="viewHistory('DHT22_001')">
                        <i class="fas fa-chart-line"></i> Historique
                    </button>
                </div>
            </div>
        </div>

        <!-- Capteur humidité sol -->
        <div class="sensor-card soil-sensor">
            <div class="sensor-header">
                <div class="sensor-title">
                    <i class="fas fa-seedling"></i>
                    <h3>Capteur Humidité Sol</h3>
                </div>
                <div class="sensor-status status-active">
                    <i class="fas fa-circle"></i> Actif
                </div>
            </div>
            
            <div class="sensor-body">
                <div class="sensor-value-display">
                    <div class="current-value">
                        <span class="value">45</span>
                        <span class="unit">%</span>
                    </div>
                    <div class="value-trend">
                        <i class="fas fa-arrow-down trend-down"></i>
                        <span>Sol sec</span>
                    </div>
                </div>
                
                <div class="sensor-info-grid">
                    <div class="info-item">
                        <span class="label">ID Capteur:</span>
                        <span class="value">SOIL_001</span>
                    </div>
                    <div class="info-item">
                        <span class="label">Type:</span>
                        <span class="value">Capacitif</span>
                    </div>
                    <div class="info-item">
                        <span class="label">Profondeur:</span>
                        <span class="value">5 cm</span>
                    </div>
                    <div class="info-item">
                        <span class="label">État:</span>
                        <span class="value">Sec</span>
                    </div>
                </div>
                
                <div class="sensor-actions">
                    <button class="btn-action" onclick="testSensor('SOIL_001')">
                        <i class="fas fa-play"></i> Tester
                    </button>
                    <button class="btn-action" onclick="setAlert('SOIL_001')">
                        <i class="fas fa-bell"></i> Alerte
                    </button>
                </div>
            </div>
        </div>

        <!-- Capteur couleur / N/B -->
        <div class="sensor-card color-sensor">
            <div class="sensor-header">
                <div class="sensor-title">
                    <i class="fas fa-palette"></i>
                    <h3>Capteur Couleur / N&B</h3>
                </div>
                <div class="sensor-status status-active">
                    <i class="fas fa-circle"></i> Actif
                </div>
            </div>
            
            <div class="sensor-body">
                <div class="sensor-value-display color-display">
                    <div class="color-preview" style="background: rgb(120, 180, 75);"></div>
                    <div class="color-values">
                        <div class="rgb-values">
                            <span>R: 120</span>
                            <span>G: 180</span>
                            <span>B: 75</span>
                        </div>
                        <div class="color-name">Vert clair</div>
                    </div>
                </div>
                
                <div class="sensor-info-grid">
                    <div class="info-item">
                        <span class="label">ID Capteur:</span>
                        <span class="value">TCS3200_001</span>
                    </div>
                    <div class="info-item">
                        <span class="label">Mode:</span>
                        <span class="value">Couleur</span>
                    </div>
                    <div class="info-item">
                        <span class="label">Luminosité:</span>
                        <span class="value">850 lux</span>
                    </div>
                    <div class="info-item">
                        <span class="label">Fréquence:</span>
                        <span class="value">1.2 kHz</span>
                    </div>
                </div>
                
                <div class="sensor-actions">
                    <button class="btn-action" onclick="switchMode('TCS3200_001')">
                        <i class="fas fa-exchange-alt"></i> Mode N&B
                    </button>
                    <button class="btn-action" onclick="calibrateColor('TCS3200_001')">
                        <i class="fas fa-adjust"></i> Calibrer
                    </button>
                </div>
            </div>
        </div>

        <!-- Capteur de proximité -->
        <div class="sensor-card proximity-sensor">
            <div class="sensor-header">
                <div class="sensor-title">
                    <i class="fas fa-radar"></i>
                    <h3>Capteur de Proximité</h3>
                </div>
                <div class="sensor-status status-active">
                    <i class="fas fa-circle"></i> Actif
                </div>
            </div>
            
            <div class="sensor-body">
                <div class="sensor-value-display">
                    <div class="current-value">
                        <span class="value">12.5</span>
                        <span class="unit">cm</span>
                    </div>
                    <div class="value-trend">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span>Objet détecté</span>
                    </div>
                </div>
                
                <div class="sensor-info-grid">
                    <div class="info-item">
                        <span class="label">ID Capteur:</span>
                        <span class="value">HC_SR04_001</span>
                    </div>
                    <div class="info-item">
                        <span class="label">Type:</span>
                        <span class="value">Ultrasonique</span>
                    </div>
                    <div class="info-item">
                        <span class="label">Portée:</span>
                        <span class="value">2-400 cm</span>
                    </div>
                    <div class="info-item">
                        <span class="label">Précision:</span>
                        <span class="value">±3 mm</span>
                    </div>
                </div>
                
                <div class="sensor-actions">
                    <button class="btn-action" onclick="testRange('HC_SR04_001')">
                        <i class="fas fa-ruler"></i> Test portée
                    </button>
                    <button class="btn-action" onclick="setThreshold('HC_SR04_001')">
                        <i class="fas fa-sliders-h"></i> Seuil
                    </button>
                </div>
            </div>
        </div>

        <!-- Boutons / Interrupteurs -->
        <div class="sensor-card button-sensor">
            <div class="sensor-header">
                <div class="sensor-title">
                    <i class="fas fa-hand-pointer"></i>
                    <h3>Boutons & Interrupteurs</h3>
                </div>
                <div class="sensor-status status-active">
                    <i class="fas fa-circle"></i> Actif
                </div>
            </div>
            
            <div class="sensor-body">
                <div class="buttons-status">
                    <div class="button-item">
                        <div class="button-visual pressed"></div>
                        <span>Bouton 1: PRESSÉ</span>
                    </div>
                    <div class="button-item">
                        <div class="button-visual"></div>
                        <span>Bouton 2: RELÂCHÉ</span>
                    </div>
                    <div class="button-item">
                        <div class="button-visual switch-on"></div>
                        <span>Switch 1: ON</span>
                    </div>
                </div>
                
                <div class="sensor-info-grid">
                    <div class="info-item">
                        <span class="label">ID Module:</span>
                        <span class="value">BTN_MODULE_001</span>
                    </div>
                    <div class="info-item">
                        <span class="label">Nb Boutons:</span>
                        <span class="value">2 + 1 Switch</span>
                    </div>
                    <div class="info-item">
                        <span class="label">Debounce:</span>
                        <span class="value">50 ms</span>
                    </div>
                    <div class="info-item">
                        <span class="label">Pull-up:</span>
                        <span class="value">Activé</span>
                    </div>
                </div>
                
                <div class="sensor-actions">
                    <button class="btn-action" onclick="testButtons('BTN_MODULE_001')">
                        <i class="fas fa-mouse"></i> Test
                    </button>
                    <button class="btn-action" onclick="configButtons('BTN_MODULE_001')">
                        <i class="fas fa-cog"></i> Config
                    </button>
                </div>
            </div>
        </div>

        <!-- Capteur de gaz -->
        <div class="sensor-card gas-sensor">
            <div class="sensor-header">
                <div class="sensor-title">
                    <i class="fas fa-smog"></i>
                    <h3>Capteur de Gaz</h3>
                </div>
                <div class="sensor-status status-active">
                    <i class="fas fa-circle"></i> Actif
                </div>
            </div>
            
            <div class="sensor-body">
                <div class="sensor-value-display">
                    <div class="current-value">
                        <span class="value">350</span>
                        <span class="unit">ppm</span>
                    </div>
                    <div class="value-trend">
                        <i class="fas fa-check-circle trend-ok"></i>
                        <span>Niveau normal</span>
                    </div>
                </div>
                
                <div class="sensor-info-grid">
                    <div class="info-item">
                        <span class="label">ID Capteur:</span>
                        <span class="value">MQ135_001</span>
                    </div>
                    <div class="info-item">
                        <span class="label">Type:</span>
                        <span class="value">MQ-135</span>
                    </div>
                    <div class="info-item">
                        <span class="label">Gaz détectés:</span>
                        <span class="value">CO2, NH3, NOx</span>
                    </div>
                    <div class="info-item">
                        <span class="label">Seuil alerte:</span>
                        <span class="value">1000 ppm</span>
                    </div>
                </div>
                
                <div class="sensor-actions">
                    <button class="btn-action" onclick="calibrateGas('MQ135_001')">
                        <i class="fas fa-wind"></i> Calibrer
                    </button>
                    <button class="btn-action" onclick="setGasAlert('MQ135_001')">
                        <i class="fas fa-exclamation-triangle"></i> Alerte
                    </button>
                </div>
            </div>
        </div>

        <!-- Capteur de lumière -->
        <div class="sensor-card light-sensor">
            <div class="sensor-header">
                <div class="sensor-title">
                    <i class="fas fa-sun"></i>
                    <h3>Capteur de Lumière</h3>
                </div>
                <div class="sensor-status status-active">
                    <i class="fas fa-circle"></i> Actif
                </div>
            </div>
            
            <div class="sensor-body">
                <div class="sensor-value-display">
                    <div class="current-value">
                        <span class="value">1250</span>
                        <span class="unit">lux</span>
                    </div>
                    <div class="value-trend">
                        <i class="fas fa-sun trend-bright"></i>
                        <span>Lumineux</span>
                    </div>
                </div>
                
                <div class="sensor-info-grid">
                    <div class="info-item">
                        <span class="label">ID Capteur:</span>
                        <span class="value">LDR_001</span>
                    </div>
                    <div class="info-item">
                        <span class="label">Type:</span>
                        <span class="value">Photorésistance</span>
                    </div>
                    <div class="info-item">
                        <span class="label">Plage:</span>
                        <span class="value">0-10000 lux</span>
                    </div>
                    <div class="info-item">
                        <span class="label">Sensibilité:</span>
                        <span class="value">Haute</span>
                    </div>
                </div>
                
                <div class="sensor-actions">
                    <button class="btn-action" onclick="testLight('LDR_001')">
                        <i class="fas fa-lightbulb"></i> Test
                    </button>
                    <button class="btn-action" onclick="setLightThreshold('LDR_001')">
                        <i class="fas fa-adjust"></i> Seuil
                    </button>
                </div>
            </div>
        </div>

        <!-- Capteur son (en maintenance) -->
        <div class="sensor-card sound-sensor maintenance-sensor">
            <div class="sensor-header">
                <div class="sensor-title">
                    <i class="fas fa-microphone"></i>
                    <h3>Capteur Son (Carte Son)</h3>
                </div>
                <div class="sensor-status status-maintenance">
                    <i class="fas fa-exclamation-triangle"></i> Maintenance
                </div>
            </div>
            
            <div class="sensor-body">
                <div class="sensor-value-display">
                    <div class="current-value maintenance-mode">
                        <span class="value">HORS SERVICE</span>
                    </div>
                    <div class="value-trend">
                        <i class="fas fa-tools"></i>
                        <span>Depuis 2h</span>
                    </div>
                </div>
                
                <div class="sensor-info-grid">
                    <div class="info-item">
                        <span class="label">ID Capteur:</span>
                        <span class="value">MIC_001</span>
                    </div>
                    <div class="info-item">
                        <span class="label">Problème:</span>
                        <span class="value">Connexion USB</span>
                    </div>
                    <div class="info-item">
                        <span class="label">Dernière lecture:</span>
                        <span class="value">Aujourd'hui 14:30</span>
                    </div>
                    <div class="info-item">
                        <span class="label">Fréquence:</span>
                        <span class="value">44.1 kHz</span>
                    </div>
                </div>
                
                <div class="sensor-actions">
                    <button class="btn-action btn-warning" onclick="reconnectSound('MIC_001')">
                        <i class="fas fa-plug"></i> Reconnecter
                    </button>
                    <button class="btn-action" onclick="scheduleRepair('MIC_001')">
                        <i class="fas fa-calendar"></i> Planifier
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions globales -->
    <div class="global-actions">
        <div class="actions-header">
            <h3><i class="fas fa-tools"></i> Actions Globales</h3>
        </div>
        <div class="actions-grid">
            <button class="action-btn" onclick="addNewSensor()">
                <i class="fas fa-plus"></i>
                <span>Ajouter un Capteur</span>
            </button>
            <button class="action-btn" onclick="runDiagnostics()">
                <i class="fas fa-stethoscope"></i>
                <span>Diagnostic Complet</span>
            </button>
            <button class="action-btn" onclick="exportData()">
                <i class="fas fa-download"></i>
                <span>Exporter les Données</span>
            </button>
            <button class="action-btn" onclick="configureAlerts()">
                <i class="fas fa-bell"></i>
                <span>Configurer Alertes</span>
            </button>
        </div>
    </div>
</div>

<style>
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

/* Grille des statistiques */
.sensors-stats {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1.5rem;
    margin-bottom: 3rem;
}

.sensors-stats .stat-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    padding: 2rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    transition: all 0.3s ease;
}

.sensors-stats .stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
    flex-shrink: 0;
}

.active-sensors .stat-icon { background: linear-gradient(135deg, #22c55e, #16a34a); }
.inactive-sensors .stat-icon { background: linear-gradient(135deg, #f59e0b, #d97706); }
.temperature .stat-icon { background: linear-gradient(135deg, #3b82f6, #1e40af); }
.data-rate .stat-icon { background: linear-gradient(135deg, #8b5cf6, #7c3aed); }

.stat-info .stat-number {
    font-size: 2rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 0.5rem;
}

.stat-info .stat-label {
    color: #64748b;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.875rem;
    letter-spacing: 1px;
}

/* Grille des capteurs */
.sensors-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 2rem;
    margin-bottom: 3rem;
}

.sensor-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    overflow: hidden;
    transition: all 0.3s ease;
}

.sensor-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
}

.temperature-sensor { border-left: 4px solid #3b82f6; }
.soil-sensor { border-left: 4px solid #22c55e; }
.color-sensor { border-left: 4px solid #8b5cf6; }
.proximity-sensor { border-left: 4px solid #f59e0b; }
.button-sensor { border-left: 4px solid #64748b; }
.gas-sensor { border-left: 4px solid #ef4444; }
.light-sensor { border-left: 4px solid #fbbf24; }
.sound-sensor { border-left: 4px solid #06b6d4; }
.maintenance-sensor { border-left: 4px solid #f59e0b; }

.sensor-header {
    background: linear-gradient(135deg, rgba(30, 64, 175, 0.1), rgba(59, 130, 246, 0.1));
    padding: 1.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid rgba(59, 130, 246, 0.1);
}

.sensor-title {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.sensor-title i {
    color: #3b82f6;
    font-size: 1.25rem;
}

.sensor-title h3 {
    margin: 0;
    color: #1e293b;
    font-size: 1.125rem;
    font-weight: 600;
}

.sensor-status {
    padding: 0.5rem 1rem;
    border-radius: 25px;
    font-size: 0.875rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.status-active {
    background: #dcfce7;
    color: #166534;
}

.status-maintenance {
    background: #fef3c7;
    color: #92400e;
}

.sensor-body {
    padding: 1.5rem;
}

.sensor-value-display {
    text-align: center;
    margin-bottom: 1.5rem;
    padding: 1.5rem;
    background: #f8fafc;
    border-radius: 15px;
}

.dual-values {
    display: flex;
    justify-content: space-around;
    align-items: center;
}

.value-item {
    text-align: center;
}

.current-value {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.current-value .value {
    margin-right: 0.5rem;
}

.current-value .unit {
    font-size: 1.25rem;
    color: #64748b;
}

.value-label {
    color: #64748b;
    font-size: 0.875rem;
    font-weight: 500;
}

.maintenance-mode .value { color: #f59e0b; }

.value-trend {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    color: #64748b;
    font-size: 0.875rem;
}

.trend-up { color: #ef4444; }
.trend-down { color: #f59e0b; }
.trend-ok { color: #22c55e; }
.trend-bright { color: #fbbf24; }

/* Affichage couleur */
.color-display {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.color-preview {
    width: 60px;
    height: 60px;
    border-radius: 10px;
    border: 2px solid #e2e8f0;
    flex-shrink: 0;
}

.color-values {
    flex: 1;
}

.rgb-values {
    display: flex;
    gap: 1rem;
    margin-bottom: 0.5rem;
    font-family: monospace;
    font-size: 0.875rem;
}

.color-name {
    font-weight: 600;
    color: #1e293b;
}

/* Statut des boutons */
.buttons-status {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
    margin-bottom: 1rem;
}

.button-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.5rem;
    background: #f8fafc;
    border-radius: 8px;
}

.button-visual {
    width: 20px;
    height: 20px;
    border-radius: 4px;
    background: #e2e8f0;
    border: 2px solid #94a3b8;
    transition: all 0.3s ease;
}

.button-visual.pressed {
    background: #ef4444;
    border-color: #dc2626;
    transform: scale(0.9);
}

.button-visual.switch-on {
    background: #22c55e;
    border-color: #16a34a;
    border-radius: 10px;
}

.sensor-info-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
    padding: 0.75rem;
    background: #f8fafc;
    border-radius: 10px;
}

.info-item .label {
    color: #64748b;
    font-size: 0.75rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.info-item .value {
    color: #1e293b;
    font-weight: 600;
    font-size: 0.875rem;
}

.sensor-actions {
    display: flex;
    gap: 1rem;
}

.btn-action {
    flex: 1;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    color: #64748b;
    padding: 0.75rem 1rem;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.875rem;
    font-weight: 500;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.btn-action:hover {
    background: #3b82f6;
    color: white;
    transform: translateY(-2px);
}

.btn-action.btn-warning {
    background: #fef3c7;
    border-color: #f59e0b;
    color: #92400e;
}

.btn-action.btn-warning:hover {
    background: #f59e0b;
    color: white;
}

/* Actions globales */
.global-actions {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.actions-header h3 {
    color: #1e293b;
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.actions-header i {
    color: #3b82f6;
}

.actions-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1.5rem;
}

.action-btn {
    background: linear-gradient(135deg, #f8fafc, #f1f5f9);
    border: 1px solid #e2e8f0;
    color: #64748b;
    padding: 1.5rem;
    border-radius: 15px;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.75rem;
    text-align: center;
}

.action-btn:hover {
    background: linear-gradient(135deg, #3b82f6, #1e40af);
    color: white;
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(59, 130, 246, 0.3);
}

.action-btn i {
    font-size: 1.5rem;
}

.action-btn span {
    font-weight: 500;
    font-size: 0.875rem;
}

/* CORRECTION: Styles pour les sliders avec compatibilité cross-browser */
input[type="range"] {
    /* Suppression de l'apparence par défaut - TOUS NAVIGATEURS */
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    
    /* Styles de base */
    width: 100%;
    height: 6px;
    border-radius: 3px;
    background: #e2e8f0;
    outline: none;
    border: none;
    cursor: pointer;
}

/* Thumb pour WebKit (Chrome, Safari, Edge) */
input[type="range"]::-webkit-slider-thumb {
    -webkit-appearance: none;
    appearance: none;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: #3b82f6;
    cursor: pointer;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
    border: none;
}

/* Thumb pour Firefox */
input[type="range"]::-moz-range-thumb {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: #3b82f6;
    cursor: pointer;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
    border: none;
}

/* Track pour Firefox */
input[type="range"]::-moz-range-track {
    width: 100%;
    height: 6px;
    border-radius: 3px;
    background: #e2e8f0;
    border: none;
}

/* Focus states */
input[type="range"]:focus::-webkit-slider-thumb {
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3);
}

input[type="range"]:focus::-moz-range-thumb {
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3);
}

/* Responsive */
@media (max-width: 1200px) {
    .sensors-stats {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .sensors-grid {
        grid-template-columns: 1fr;
    }
    
    .actions-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .dashboard-container {
        padding: 1rem;
    }
    
    .dashboard-nav {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
    
    .nav-tabs {
        flex-wrap: wrap;
        justify-content: center;
    }
    
    .sensors-stats {
        grid-template-columns: 1fr;
    }
    
    .sensor-info-grid {
        grid-template-columns: 1fr;
    }
    
    .actions-grid {
        grid-template-columns: 1fr;
    }
    
    .sensor-actions {
        flex-direction: column;
    }
    
    .dual-values {
        flex-direction: column;
        gap: 1rem;
    }
    
    .color-display {
        flex-direction: column;
        text-align: center;
    }
}
</style>

<script>
// Fonctions pour les capteurs
function calibrateSensor(sensorId) {
    alert('Calibrage du capteur ' + sensorId);
}

function viewHistory(sensorId) {
    alert('Historique du capteur ' + sensorId);
}

function testSensor(sensorId) {
    alert('Test du capteur ' + sensorId);
}

function setAlert(sensorId) {
    alert('Configuration d\'alerte pour ' + sensorId);
}

function switchMode(sensorId) {
    alert('Changement de mode pour ' + sensorId);
}

function calibrateColor(sensorId) {
    alert('Calibrage couleur pour ' + sensorId);
}

function testRange(sensorId) {
    alert('Test de portée pour ' + sensorId);
}

function setThreshold(sensorId) {
    alert('Configuration du seuil pour ' + sensorId);
}

function testButtons(sensorId) {
    alert('Test des boutons ' + sensorId);
}

function configButtons(sensorId) {
    alert('Configuration des boutons ' + sensorId);
}

function calibrateGas(sensorId) {
    alert('Calibrage du capteur de gaz ' + sensorId);
}

function setGasAlert(sensorId) {
    alert('Configuration d\'alerte gaz pour ' + sensorId);
}

function testLight(sensorId) {
    alert('Test du capteur de lumière ' + sensorId);
}

function setLightThreshold(sensorId) {
    alert('Configuration du seuil de lumière pour ' + sensorId);
}

function reconnectSound(sensorId) {
    alert('Reconnexion du capteur son ' + sensorId);
}

function scheduleRepair(sensorId) {
    alert('Planification de la réparation pour ' + sensorId);
}

function addNewSensor() {
    alert('Ajout d\'un nouveau capteur');
}

function runDiagnostics() {
    alert('Lancement du diagnostic complet...');
}

function exportData() {
    alert('Export des données des capteurs');
}

function configureAlerts() {
    alert('Configuration des alertes');
}

// Simulation de mise à jour des données en temps réel
setInterval(() => {
    // Mise à jour température
    const tempValue = document.querySelector('.temperature-sensor .current-value .value');
    if (tempValue) {
        const newTemp = (20 + Math.random() * 10).toFixed(1);
        tempValue.textContent = newTemp;
    }
    
    // Mise à jour humidité sol
    const soilValue = document.querySelector('.soil-sensor .current-value .value');
    if (soilValue) {
        const newSoil = Math.floor(40 + Math.random() * 20);
        soilValue.textContent = newSoil;
    }
    
    // Mise à jour proximité
    const proxValue = document.querySelector('.proximity-sensor .current-value .value');
    if (proxValue) {
        const newProx = (10 + Math.random() * 50).toFixed(1);
        proxValue.textContent = newProx;
    }
    
    // Mise à jour gaz
    const gasValue = document.querySelector('.gas-sensor .current-value .value');
    if (gasValue) {
        const newGas = Math.floor(300 + Math.random() * 100);
        gasValue.textContent = newGas;
    }
    
    // Mise à jour lumière
    const lightValue = document.querySelector('.light-sensor .current-value .value');
    if (lightValue) {
        const newLight = Math.floor(1000 + Math.random() * 500);
        lightValue.textContent = newLight;
    }
}, 5000);
</script>

<?php require_once ROOT_PATH . '/app/views/partials/footer.php'; ?>