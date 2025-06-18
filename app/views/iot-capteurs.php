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
            <p>Surveillance et monitoring en temps réel des capteurs</p>
        </div>
    </div>

    <!-- Navigation -->
    <div class="dashboard-nav">
        <div class="nav-tabs">
            <a href="<?= BASE_URL ?>/iot-dashboard" class="nav-tab">
                <i class="fas fa-arrow-left"></i> Retour IoT
            </a>
            <a href="<?= BASE_URL ?>/iot-dashboard/capteurs" class="nav-tab active">
                <i class="fas fa-satellite-dish"></i> Capteurs
            </a>
            <a href="<?= BASE_URL ?>/iot-dashboard/actionneurs" class="nav-tab">
                <i class="fas fa-cogs"></i> Actionneurs
            </a>
            <a href="<?= BASE_URL ?>/logout" class="nav-tab">
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
                <div class="stat-number">3</div>
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
                <div class="stat-number">5s</div>
                <div class="stat-label">Fréquence</div>
            </div>
        </div>
    </div>

    <!-- Liste des capteurs -->
    <div class="sensors-grid">
        <!-- Capteur de température -->
        <div class="sensor-card temperature-sensor">
            <div class="sensor-header">
                <div class="sensor-title">
                    <i class="fas fa-thermometer-half"></i>
                    <h3>Capteur de Température</h3>
                </div>
                <div class="sensor-status status-active">
                    <i class="fas fa-circle"></i> Actif
                </div>
            </div>
            
            <div class="sensor-body">
                <div class="sensor-value-display">
                    <div class="current-value">
                        <span class="value">22.5</span>
                        <span class="unit">°C</span>
                    </div>
                    <div class="value-trend">
                        <i class="fas fa-arrow-up trend-up"></i>
                        <span>+0.3°C</span>
                    </div>
                </div>
                
                <div class="sensor-info-grid">
                    <div class="info-item">
                        <span class="label">ID Capteur:</span>
                        <span class="value">TEMP_001</span>
                    </div>
                    <div class="info-item">
                        <span class="label">Localisation:</span>
                        <span class="value">Zone A</span>
                    </div>
                    <div class="info-item">
                        <span class="label">Dernière lecture:</span>
                        <span class="value"><?= date('H:i:s') ?></span>
                    </div>
                    <div class="info-item">
                        <span class="label">Batterie:</span>
                        <span class="value">85%</span>
                    </div>
                </div>
                
                <div class="sensor-actions">
                    <button class="btn-action" onclick="calibrateSensor('TEMP_001')">
                        <i class="fas fa-cog"></i> Calibrer
                    </button>
                    <button class="btn-action" onclick="viewHistory('TEMP_001')">
                        <i class="fas fa-chart-line"></i> Historique
                    </button>
                </div>
            </div>
        </div>

        <!-- Détecteur Place A01 -->
        <div class="sensor-card presence-sensor">
            <div class="sensor-header">
                <div class="sensor-title">
                    <i class="fas fa-eye"></i>
                    <h3>Détecteur Place A01</h3>
                </div>
                <div class="sensor-status status-active">
                    <i class="fas fa-circle"></i> Actif
                </div>
            </div>
            
            <div class="sensor-body">
                <div class="sensor-value-display">
                    <div class="current-value presence-occupée">
                        <span class="value">OCCUPÉE</span>
                    </div>
                    <div class="value-trend">
                        <i class="fas fa-clock"></i>
                        <span>Depuis 15min</span>
                    </div>
                </div>
                
                <div class="sensor-info-grid">
                    <div class="info-item">
                        <span class="label">ID Capteur:</span>
                        <span class="value">DET_A01</span>
                    </div>
                    <div class="info-item">
                        <span class="label">Type:</span>
                        <span class="value">Ultrasonique</span>
                    </div>
                    <div class="info-item">
                        <span class="label">Distance:</span>
                        <span class="value">45 cm</span>
                    </div>
                    <div class="info-item">
                        <span class="label">Précision:</span>
                        <span class="value">±2 cm</span>
                    </div>
                </div>
                
                <div class="sensor-actions">
                    <button class="btn-action" onclick="testSensor('DET_A01')">
                        <i class="fas fa-play"></i> Tester
                    </button>
                    <button class="btn-action" onclick="resetSensor('DET_A01')">
                        <i class="fas fa-redo"></i> Reset
                    </button>
                </div>
            </div>
        </div>

        <!-- Détecteur Place A02 -->
        <div class="sensor-card presence-sensor">
            <div class="sensor-header">
                <div class="sensor-title">
                    <i class="fas fa-eye"></i>
                    <h3>Détecteur Place A02</h3>
                </div>
                <div class="sensor-status status-active">
                    <i class="fas fa-circle"></i> Actif
                </div>
            </div>
            
            <div class="sensor-body">
                <div class="sensor-value-display">
                    <div class="current-value presence-free">
                        <span class="value">LIBRE</span>
                    </div>
                    <div class="value-trend">
                        <i class="fas fa-clock"></i>
                        <span>Depuis 2h</span>
                    </div>
                </div>
                
                <div class="sensor-info-grid">
                    <div class="info-item">
                        <span class="label">ID Capteur:</span>
                        <span class="value">DET_A02</span>
                    </div>
                    <div class="info-item">
                        <span class="label">Type:</span>
                        <span class="value">Ultrasonique</span>
                    </div>
                    <div class="info-item">
                        <span class="label">Distance:</span>
                        <span class="value">180 cm</span>
                    </div>
                    <div class="info-item">
                        <span class="label">Précision:</span>
                        <span class="value">±2 cm</span>
                    </div>
                </div>
                
                <div class="sensor-actions">
                    <button class="btn-action" onclick="testSensor('DET_A02')">
                        <i class="fas fa-play"></i> Tester
                    </button>
                    <button class="btn-action" onclick="resetSensor('DET_A02')">
                        <i class="fas fa-redo"></i> Reset
                    </button>
                </div>
            </div>
        </div>

        <!-- Capteur en maintenance -->
        <div class="sensor-card maintenance-sensor">
            <div class="sensor-header">
                <div class="sensor-title">
                    <i class="fas fa-eye"></i>
                    <h3>Détecteur Place B01</h3>
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
                        <span>Depuis 1j</span>
                    </div>
                </div>
                
                <div class="sensor-info-grid">
                    <div class="info-item">
                        <span class="label">ID Capteur:</span>
                        <span class="value">DET_B01</span>
                    </div>
                    <div class="info-item">
                        <span class="label">Problème:</span>
                        <span class="value">Batterie faible</span>
                    </div>
                    <div class="info-item">
                        <span class="label">Dernière lecture:</span>
                        <span class="value">Hier 14:30</span>
                    </div>
                    <div class="info-item">
                        <span class="label">Batterie:</span>
                        <span class="value">12%</span>
                    </div>
                </div>
                
                <div class="sensor-actions">
                    <button class="btn-action btn-warning" onclick="replaceBattery('DET_B01')">
                        <i class="fas fa-battery-quarter"></i> Remplacer
                    </button>
                    <button class="btn-action" onclick="scheduleRepair('DET_B01')">
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
/* Correction des débordements et ajustements généraux */
* {
    box-sizing: border-box;
}

body {
    margin: 0;
    padding: 0;
    overflow-x: hidden;
}

.dashboard-container {
    max-width: 100vw;
    width: 100%;
    margin: 0 auto;
    padding: 1rem;
    overflow-x: hidden;
}

/* Header du dashboard */
.dashboard-header {
    text-align: center;
    margin-bottom: 1.5rem;
    padding: 0 1rem;
}

.header-content h1 {
    color: white;
    font-size: clamp(1.5rem, 4vw, 2.5rem);
    font-weight: 700;
    margin-bottom: 0.5rem;
    text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
    word-wrap: break-word;
}

.header-content h1 i {
    color: #ffd700;
    margin-right: 0.5rem;
}

.header-content p {
    color: rgba(255, 255, 255, 0.9);
    font-size: clamp(0.9rem, 2vw, 1.1rem);
    margin: 0;
    word-wrap: break-word;
}

/* Navigation secondaire */
.dashboard-nav {
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(15px);
    border-radius: 15px;
    padding: 1rem;
    margin-bottom: 1.5rem;
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    align-items: center;
    border: 1px solid rgba(255, 255, 255, 0.2);
    gap: 1rem;
}

.nav-tabs {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.nav-tab {
    color: rgba(255, 255, 255, 0.8);
    text-decoration: none;
    padding: 0.5rem 1rem;
    border-radius: 10px;
    transition: all 0.3s ease;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
    white-space: nowrap;
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
    font-size: 0.875rem;
    white-space: nowrap;
}

/* Grille des statistiques */
.sensors-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-bottom: 2rem;
}

.sensors-stats .stat-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-radius: 15px;
    padding: 1rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    transition: all 0.3s ease;
    min-width: 0;
}

.sensors-stats .stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
}

.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    color: white;
    flex-shrink: 0;
}

.active-sensors .stat-icon { background: linear-gradient(135deg, #22c55e, #16a34a); }
.inactive-sensors .stat-icon { background: linear-gradient(135deg, #f59e0b, #d97706); }
.temperature .stat-icon { background: linear-gradient(135deg, #3b82f6, #1e40af); }
.data-rate .stat-icon { background: linear-gradient(135deg, #8b5cf6, #7c3aed); }

.stat-info {
    min-width: 0;
    flex: 1;
}

.stat-info .stat-number {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 0.25rem;
    word-wrap: break-word;
}

.stat-info .stat-label {
    color: #64748b;
    font-size: 0.75rem;
    font-weight: 500;
    word-wrap: break-word;
}

/* Grille des capteurs */
.sensors-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.sensor-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    overflow: hidden;
    transition: all 0.3s ease;
    min-width: 0;
}

.sensor-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
}

.temperature-sensor { border-left: 4px solid #3b82f6; }
.presence-sensor { border-left: 4px solid #22c55e; }
.maintenance-sensor { border-left: 4px solid #f59e0b; }

.sensor-header {
    background: linear-gradient(135deg, rgba(30, 64, 175, 0.1), rgba(59, 130, 246, 0.1));
    padding: 1rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid rgba(59, 130, 246, 0.1);
    flex-wrap: wrap;
    gap: 0.5rem;
}

.sensor-title {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    min-width: 0;
    flex: 1;
}

.sensor-title i {
    color: #3b82f6;
    font-size: 1.125rem;
    flex-shrink: 0;
}

.sensor-title h3 {
    margin: 0;
    color: #1e293b;
    font-size: 1rem;
    font-weight: 600;
    word-wrap: break-word;
    min-width: 0;
}

.sensor-status {
    padding: 0.375rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.375rem;
    white-space: nowrap;
    flex-shrink: 0;
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
    padding: 1rem;
}

.sensor-value-display {
    text-align: center;
    margin-bottom: 1rem;
    padding: 1rem;
    background: #f8fafc;
    border-radius: 15px;
}

.current-value {
    font-size: 1.75rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    word-wrap: break-word;
}

.current-value .value {
    margin-right: 0.5rem;
}

.current-value .unit {
    font-size: 1rem;
    color: #64748b;
}

.presence-occupée .value { color: #ef4444; }
.presence-free .value { color: #22c55e; }
.maintenance-mode .value { color: #f59e0b; }

.value-trend {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    color: #64748b;
    font-size: 0.875rem;
    flex-wrap: wrap;
}

.trend-up { color: #ef4444; }

.sensor-info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    gap: 0.5rem;
    margin-bottom: 1rem;
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
    padding: 0.5rem;
    background: #f8fafc;
    border-radius: 8px;
    min-width: 0;
}

.info-item .label {
    color: #64748b;
    font-size: 0.75rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    white-space: nowrap;
}

.info-item .value {
    color: #1e293b;
    font-weight: 600;
    font-size: 0.875rem;
    word-wrap: break-word;
}

.sensor-actions {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.btn-action {
    flex: 1;
    min-width: 100px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    color: #64748b;
    padding: 0.75rem;
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
    padding: 1.5rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.actions-header h3 {
    color: #1e293b;
    font-size: 1.25rem;
    font-weight: 700;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.actions-header i {
    color: #3b82f6;
}

.actions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 1rem;
}

.action-btn {
    background: linear-gradient(135deg, #f8fafc, #f1f5f9);
    border: 1px solid #e2e8f0;
    color: #64748b;
    padding: 1rem;
    border-radius: 15px;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
    text-align: center;
    min-width: 0;
}

.action-btn:hover {
    background: linear-gradient(135deg, #3b82f6, #1e40af);
    color: white;
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(59, 130, 246, 0.3);
}

.action-btn i {
    font-size: 1.25rem;
    flex-shrink: 0;
}

.action-btn span {
    font-weight: 500;
    font-size: 0.875rem;
    word-wrap: break-word;
}

/* Responsive */
@media (max-width: 1200px) {
    .sensors-grid {
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    }
}

@media (max-width: 768px) {
    .dashboard-container {
        padding: 0.5rem;
    }
    
    .dashboard-nav {
        flex-direction: column;
        text-align: center;
        padding: 0.75rem;
    }
    
    .nav-tabs {
        justify-content: center;
    }
    
    .sensors-stats {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .sensors-grid {
        grid-template-columns: 1fr;
    }
    
    .sensor-info-grid {
        grid-template-columns: 1fr;
    }
    
    .actions-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .sensor-actions {
        flex-direction: column;
    }
}

@media (max-width: 480px) {
    .sensors-stats {
        grid-template-columns: 1fr;
    }
    
    .actions-grid {
        grid-template-columns: 1fr;
    }
    
    .stat-info .stat-number {
        font-size: 1.25rem;
    }
    
    .current-value {
        font-size: 1.5rem;
    }
}
</style>

<script>
function calibrateSensor(sensorId) {
    alert('Calibrage du capteur ' + sensorId);
}

function viewHistory(sensorId) {
    alert('Historique du capteur ' + sensorId);
}

function testSensor(sensorId) {
    alert('Test du capteur ' + sensorId);
}

function resetSensor(sensorId) {
    if (confirm('Êtes-vous sûr de vouloir réinitialiser le capteur ' + sensorId + ' ?')) {
        alert('Capteur ' + sensorId + ' réinitialisé');
    }
}

function replaceBattery(sensorId) {
    alert('Remplacement de la batterie pour ' + sensorId);
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
    // Mettre à jour l'heure de dernière lecture
    const timeElements = document.querySelectorAll('.sensor-info-grid .info-item:nth-child(3) .value');
    timeElements.forEach(element => {
        if (element.textContent.includes(':')) {
            element.textContent = new Date().toLocaleTimeString();
        }
    });
}, 5000);
</script>

<?php require_once ROOT_PATH . '/app/views/partials/footer.php'; ?>

