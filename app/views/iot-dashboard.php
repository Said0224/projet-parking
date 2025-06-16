<?php 
// Vérification que l'utilisateur est admin
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header('Location: /login');
    exit;
}

require_once ROOT_PATH . '/app/views/partials/header.php'; 
?>

<div class="dashboard-container">
    <!-- Header principal du dashboard -->
    <div class="dashboard-header">
        <div class="header-content">
            <h1><i class="fas fa-car"></i> Dashboard Parking Intelligent</h1>
            <p>Surveillance en temps réel de votre parking connecté</p>
        </div>
    </div>

    <!-- Navigation secondaire -->
    <div class="dashboard-nav">
        <div class="nav-tabs">
            <a href="/admin" class="nav-tab">
                <i class="fas fa-tachometer-alt"></i> Admin
            </a>
            <a href="/iot-dashboard" class="nav-tab active">
                <i class="fas fa-microchip"></i> IoT Dashboard
            </a>
            <a href="/admin/users" class="nav-tab">
                <i class="fas fa-users"></i> Utilisateurs
            </a>
            <a href="/logout" class="nav-tab">
                <i class="fas fa-sign-out-alt"></i> Déconnexion
            </a>
        </div>
        <div class="user-info">
            <i class="fas fa-user"></i> <?= htmlspecialchars($_SESSION['user_email'] ?? 'admin@isep.fr') ?>
        </div>
    </div>

    <!-- Statistiques principales -->
    <div class="stats-grid">
        <div class="stat-card available">
            <div class="stat-number">1</div>
            <div class="stat-label">Places Libres</div>
        </div>
        
        <div class="stat-card occupied">
            <div class="stat-number">2</div>
            <div class="stat-label">Places Occupées</div>
        </div>
        
        <div class="stat-card total">
            <div class="stat-number">3</div>
            <div class="stat-label">Total Places</div>
        </div>
        
        <div class="stat-card leds">
            <div class="stat-number">2</div>
            <div class="stat-label">LEDs Actives</div>
        </div>
    </div>

    <!-- Contenu principal en 3 colonnes -->
    <div class="main-content">
        <!-- Section Places de Parking -->
        <div class="content-section parking-section">
            <div class="section-header">
                <h3><i class="fas fa-car"></i> Places de Parking</h3>
                <button class="btn-add" onclick="addParkingSpot()">
                    <i class="fas fa-plus"></i> Ajouter
                </button>
            </div>
            <div class="section-content">
                <div class="table-container">
                    <table class="dashboard-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Place</th>
                                <th>Date</th>
                                <th>Heure</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td><strong>Place 1</strong></td>
                                <td>2025-06-13</td>
                                <td>01:41:00.236786</td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td><strong>Place 2</strong></td>
                                <td>2025-06-13</td>
                                <td>01:41:00.236786</td>
                            </tr>
                            <tr class="deprecated-row">
                                <td colspan="4">
                                    <div class="deprecated-notice">
                                        <strong>Place Deprecated:</strong><br>
                                        htmlspecialchars(): Passing null to parameter<br>
                                        <small class="text-muted">Erreur système détectée</small>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- Contrôles de navigation -->
                <div class="table-controls">
                    <button class="control-btn" onclick="previousPage()">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <span class="page-info">Page 1/3</span>
                    <button class="control-btn" onclick="nextPage()">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Section LEDs de Signalisation -->
        <div class="content-section leds-section">
            <div class="section-header">
                <h3><i class="fas fa-lightbulb"></i> LEDs de Signalisation</h3>
                <button class="btn-add" onclick="addLED()">
                    <i class="fas fa-plus"></i> Ajouter
                </button>
            </div>
            <div class="section-content">
                <div class="table-container">
                    <table class="dashboard-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>État</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>
                                    <span class="led-status led-off">
                                        <i class="fas fa-times"></i> Éteinte
                                    </span>
                                </td>
                                <td>
                                    <button class="btn-action btn-edit" onclick="editLED(1)">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>
                                    <span class="led-status led-on">
                                        <i class="fas fa-check"></i> Allumée
                                    </span>
                                </td>
                                <td>
                                    <button class="btn-action btn-edit" onclick="editLED(2)">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Section Affichage OLED -->
        <div class="content-section oled-section">
            <div class="section-header">
                <h3><i class="fas fa-tv"></i> Affichage OLED</h3>
            </div>
            <div class="section-content">
                <div class="oled-info">
                    <div class="info-item">
                        <span class="info-label">Places disponibles:</span>
                        <span class="info-value">8</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Bornes de recharge:</span>
                        <span class="info-value">1</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Prix parking:</span>
                        <span class="info-value">6€</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Prix recharge:</span>
                        <span class="info-value">3€</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Dernière mise à jour:</span>
                        <span class="info-value">2025-06-13 01:41:00.236786</span>
                    </div>
                </div>
                
                <div class="oled-preview">
                    <div class="oled-screen">
                        <div class="oled-content">
                            <h4>PARKING ISEP</h4>
                            <div class="oled-line">Places: 8/10</div>
                            <div class="oled-line">Bornes: 1 libre</div>
                            <div class="oled-line">Tarif: 6€/h</div>
                            <div class="oled-line">Recharge: 3€</div>
                            <div class="oled-time"><?= date('H:i:s') ?></div>
                        </div>
                    </div>
                </div>
                
                <div class="oled-controls">
                    <button class="btn-control" onclick="updateOLED()">
                        <i class="fas fa-sync"></i> Actualiser
                    </button>
                    <button class="btn-control" onclick="configOLED()">
                        <i class="fas fa-cog"></i> Configurer
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Section Capteurs et Actionneurs -->
    <div class="sensors-actuators">
        <div class="section-row">
            <!-- Capteurs -->
            <div class="content-section sensors-panel">
                <div class="section-header">
                    <h3><i class="fas fa-satellite-dish"></i> Capteurs</h3>
                </div>
                <div class="section-content">
                    <div class="sensors-grid">
                        <div class="sensor-card">
                            <div class="sensor-icon">
                                <i class="fas fa-thermometer-half"></i>
                            </div>
                            <div class="sensor-info">
                                <h4>Température</h4>
                                <span class="sensor-value">22°C</span>
                                <span class="sensor-status active">Actif</span>
                            </div>
                        </div>
                        
                        <div class="sensor-card">
                            <div class="sensor-icon">
                                <i class="fas fa-eye"></i>
                            </div>
                            <div class="sensor-info">
                                <h4>Détecteur Place A01</h4>
                                <span class="sensor-value">Occupée</span>
                                <span class="sensor-status active">Actif</span>
                            </div>
                        </div>
                        
                        <div class="sensor-card">
                            <div class="sensor-icon">
                                <i class="fas fa-eye"></i>
                            </div>
                            <div class="sensor-info">
                                <h4>Détecteur Place A02</h4>
                                <span class="sensor-value">Libre</span>
                                <span class="sensor-status active">Actif</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actionneurs -->
            <div class="content-section actuators-panel">
                <div class="section-header">
                    <h3><i class="fas fa-cogs"></i> Actionneurs</h3>
                </div>
                <div class="section-content">
                    <div class="actuators-grid">
                        <div class="actuator-card">
                            <div class="actuator-icon">
                                <i class="fas fa-lightbulb"></i>
                            </div>
                            <div class="actuator-info">
                                <h4>LED Place A01</h4>
                                <span class="actuator-value">Rouge</span>
                                <div class="actuator-controls">
                                    <button class="btn-actuator red active" onclick="setLED(1, 'red')"></button>
                                    <button class="btn-actuator green" onclick="setLED(1, 'green')"></button>
                                    <button class="btn-actuator off" onclick="setLED(1, 'off')"></button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="actuator-card">
                            <div class="actuator-icon">
                                <i class="fas fa-lightbulb"></i>
                            </div>
                            <div class="actuator-info">
                                <h4>LED Place A02</h4>
                                <span class="actuator-value">Verte</span>
                                <div class="actuator-controls">
                                    <button class="btn-actuator red" onclick="setLED(2, 'red')"></button>
                                    <button class="btn-actuator green active" onclick="setLED(2, 'green')"></button>
                                    <button class="btn-actuator off" onclick="setLED(2, 'off')"></button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="actuator-card">
                            <div class="actuator-icon">
                                <i class="fas fa-charging-station"></i>
                            </div>
                            <div class="actuator-info">
                                <h4>Borne de Recharge</h4>
                                <span class="actuator-value">Disponible</span>
                                <div class="actuator-controls">
                                    <button class="btn-control" onclick="toggleCharging()">
                                        <i class="fas fa-power-off"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
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
.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    padding: 2rem;
    text-align: center;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
}

.stat-number {
    font-size: 3rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.stat-card.available .stat-number { color: #22c55e; }
.stat-card.occupied .stat-number { color: #ef4444; }
.stat-card.total .stat-number { color: #3b82f6; }
.stat-card.leds .stat-number { color: #f59e0b; }

.stat-label {
    color: #64748b;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.875rem;
    letter-spacing: 1px;
}

/* Contenu principal */
.main-content {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    gap: 2rem;
    margin-bottom: 2rem;
}

.content-section {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    overflow: hidden;
}

.section-header {
    background: linear-gradient(135deg, rgba(30, 64, 175, 0.9) 0%, rgba(59, 130, 246, 0.9) 100%);
    color: white;
    padding: 1.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.section-header h3 {
    margin: 0;
    font-size: 1.1rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.section-header i {
    color: #ffd700;
}

.btn-add {
    background: rgba(255, 255, 255, 0.2);
    color: white;
    border: none;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    font-size: 0.875rem;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-add:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: translateY(-1px);
}

.section-content {
    padding: 1.5rem;
}

/* Tableaux */
.table-container {
    max-height: 250px;
    overflow-y: auto;
    margin-bottom: 1rem;
}

.dashboard-table {
    width: 100%;
    border-collapse: collapse;
}

.dashboard-table th {
    background: #f8fafc;
    padding: 0.75rem 0.5rem;
    text-align: left;
    font-weight: 600;
    color: #475569;
    font-size: 0.875rem;
    border-bottom: 1px solid #e2e8f0;
    position: sticky;
    top: 0;
}

.dashboard-table td {
    padding: 0.75rem 0.5rem;
    border-bottom: 1px solid #f1f5f9;
    font-size: 0.875rem;
}

.dashboard-table tbody tr:hover {
    background: #f8fafc;
}

.deprecated-row {
    background: #fef2f2 !important;
}

.deprecated-notice {
    color: #dc2626;
    font-size: 0.75rem;
    padding: 0.5rem;
}

/* Contrôles de table */
.table-controls {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 1rem;
    padding-top: 1rem;
    border-top: 1px solid #f1f5f9;
}

.control-btn {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    color: #64748b;
    padding: 0.5rem;
    border-radius: 5px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.control-btn:hover {
    background: #3b82f6;
    color: white;
}

.page-info {
    color: #64748b;
    font-size: 0.875rem;
}

/* LEDs Status */
.led-status {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.led-off {
    background: #fecaca;
    color: #991b1b;
}

.led-on {
    background: #dcfce7;
    color: #166534;
}

/* Section OLED */
.oled-info {
    margin-bottom: 1.5rem;
}

.info-item {
    display: flex;
    justify-content: space-between;
    padding: 0.5rem 0;
    border-bottom: 1px solid #f1f5f9;
}

.info-label {
    color: #64748b;
    font-weight: 500;
}

.info-value {
    color: #1e293b;
    font-weight: 600;
}

.oled-preview {
    display: flex;
    justify-content: center;
    margin-bottom: 1rem;
}

.oled-screen {
    background: #1a1a1a;
    border-radius: 10px;
    padding: 1rem;
    width: 200px;
    border: 2px solid #333;
    box-shadow: 0 0 20px rgba(0, 255, 0, 0.3);
}

.oled-content {
    color: #00ff00;
    font-family: 'Courier New', monospace;
    font-size: 0.75rem;
    text-align: center;
}

.oled-content h4 {
    margin: 0 0 0.5rem 0;
    color: #ffffff;
    font-size: 0.875rem;
}

.oled-line {
    margin: 0.25rem 0;
    line-height: 1.2;
}

.oled-time {
    margin-top: 0.5rem;
    color: #ffff00;
    font-weight: bold;
}

.oled-controls {
    display: flex;
    gap: 0.5rem;
    justify-content: center;
}

.btn-control {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    color: #64748b;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.875rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-control:hover {
    background: #3b82f6;
    color: white;
}

/* Section Capteurs et Actionneurs */
.sensors-actuators {
    margin-top: 2rem;
}

.section-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
}

.sensors-grid, .actuators-grid {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.sensor-card, .actuator-card {
    background: #f8fafc;
    border-radius: 10px;
    padding: 1rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    border-left: 4px solid #3b82f6;
    transition: all 0.3s ease;
}

.sensor-card:hover, .actuator-card:hover {
    background: #f1f5f9;
    transform: translateX(5px);
}

.sensor-icon, .actuator-icon {
    background: linear-gradient(135deg, #3b82f6, #1e40af);
    color: white;
    width: 50px;
    height: 50px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
}

.sensor-info, .actuator-info {
    flex: 1;
}

.sensor-info h4, .actuator-info h4 {
    margin: 0 0 0.25rem 0;
    color: #1e293b;
    font-size: 0.875rem;
}

.sensor-value, .actuator-value {
    color: #64748b;
    font-size: 0.75rem;
    display: block;
    margin-bottom: 0.5rem;
}

.sensor-status {
    padding: 0.125rem 0.5rem;
    border-radius: 10px;
    font-size: 0.625rem;
    font-weight: 600;
    text-transform: uppercase;
}

.sensor-status.active {
    background: #dcfce7;
    color: #166534;
}

/* Contrôles des actionneurs */
.actuator-controls {
    display: flex;
    gap: 0.5rem;
    align-items: center;
}

.btn-actuator {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    border: 2px solid #e2e8f0;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-actuator.red {
    background: #ef4444;
}

.btn-actuator.green {
    background: #22c55e;
}

.btn-actuator.off {
    background: #64748b;
}

.btn-actuator.active {
    border-color: #1e293b;
    box-shadow: 0 0 0 2px rgba(30, 41, 59, 0.2);
}

.btn-action {
    background: none;
    border: none;
    color: #64748b;
    cursor: pointer;
    padding: 0.5rem;
    border-radius: 5px;
    transition: all 0.3s ease;
}

.btn-action:hover {
    background: #f1f5f9;
    color: #3b82f6;
}

/* Responsive */
@media (max-width: 1200px) {
    .main-content {
        grid-template-columns: 1fr 1fr;
    }
    
    .stats-grid {
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
    
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .main-content {
        grid-template-columns: 1fr;
    }
    
    .section-row {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
// Fonctions pour les places de parking
function addParkingSpot() {
    alert('Ajouter une nouvelle place de parking');
}

function previousPage() {
    console.log('Page précédente');
}

function nextPage() {
    console.log('Page suivante');
}

// Fonctions pour les LEDs
function addLED() {
    alert('Ajouter une nouvelle LED');
}

function editLED(id) {
    alert('Éditer LED #' + id);
}

function setLED(id, color) {
    // Retirer la classe active de tous les boutons de cette LED
    const card = event.target.closest('.actuator-card');
    const buttons = card.querySelectorAll('.btn-actuator');
    buttons.forEach(btn => btn.classList.remove('active'));
    
    // Ajouter la classe active au bouton cliqué
    event.target.classList.add('active');
    
    // Mettre à jour la valeur affichée
    const valueSpan = card.querySelector('.actuator-value');
    switch(color) {
        case 'red':
            valueSpan.textContent = 'Rouge';
            break;
        case 'green':
            valueSpan.textContent = 'Verte';
            break;
        case 'off':
            valueSpan.textContent = 'Éteinte';
            break;
    }
    
    console.log('LED ' + id + ' définie sur ' + color);
}

// Fonctions pour l'OLED
function updateOLED() {
    // Simuler une mise à jour
    const timeElement = document.querySelector('.oled-time');
    if (timeElement) {
        timeElement.textContent = new Date().toLocaleTimeString();
    }
    
    // Animation de mise à jour
    const screen = document.querySelector('.oled-screen');
    screen.style.boxShadow = '0 0 30px rgba(0, 255, 0, 0.8)';
    setTimeout(() => {
        screen.style.boxShadow = '0 0 20px rgba(0, 255, 0, 0.3)';
    }, 500);
    
    console.log('OLED mis à jour');
}

function configOLED() {
    alert('Configuration de l\'affichage OLED');
}

function toggleCharging() {
    const button = event.target.closest('button');
    const valueSpan = button.closest('.actuator-card').querySelector('.actuator-value');
    
    if (valueSpan.textContent === 'Disponible') {
        valueSpan.textContent = 'En charge';
        button.style.background = '#ef4444';
    } else {
        valueSpan.textContent = 'Disponible';
        button.style.background = '#22c55e';
    }
}

// Mise à jour automatique de l'heure sur l'OLED
setInterval(() => {
    const timeElement = document.querySelector('.oled-time');
    if (timeElement) {
        timeElement.textContent = new Date().toLocaleTimeString();
    }
}, 1000);

// Simulation de données en temps réel
setInterval(() => {
    // Simuler des changements de capteurs
    console.log('Mise à jour des données IoT...');
}, 5000);
</script>

<?php require_once ROOT_PATH . '/app/views/partials/footer.php'; ?>