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
            <h1><i class="fas fa-cogs"></i> Gestion des Actionneurs IoT</h1>
            <p>Contrôle et pilotage des équipements du parking intelligent</p>
        </div>
    </div>

    <!-- Navigation -->
    <div class="dashboard-nav">
        <div class="nav-tabs">
            <a href="/iot-dashboard" class="nav-tab">
                <i class="fas fa-arrow-left"></i> Retour IoT
            </a>
            <a href="/iot-dashboard/capteurs" class="nav-tab">
                <i class="fas fa-satellite-dish"></i> Capteurs
            </a>
            <a href="/iot-dashboard/actionneurs" class="nav-tab active">
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

    <!-- Statistiques actionneurs -->
    <div class="actuators-stats">
        <div class="stat-card active-leds">
            <div class="stat-icon">
                <i class="fas fa-lightbulb"></i>
            </div>
            <div class="stat-info">
                <div class="stat-number">2</div>
                <div class="stat-label">LEDs Actives</div>
            </div>
        </div>
        
        <div class="stat-card charging-stations">
            <div class="stat-icon">
                <i class="fas fa-charging-station"></i>
            </div>
            <div class="stat-info">
                <div class="stat-number">1</div>
                <div class="stat-label">Bornes Disponibles</div>
            </div>
        </div>
        
        <div class="stat-card power-consumption">
            <div class="stat-icon">
                <i class="fas fa-bolt"></i>
            </div>
            <div class="stat-info">
                <div class="stat-number">45W</div>
                <div class="stat-label">Consommation</div>
            </div>
        </div>
        
        <div class="stat-card automation">
            <div class="stat-icon">
                <i class="fas fa-robot"></i>
            </div>
            <div class="stat-info">
                <div class="stat-number">Auto</div>
                <div class="stat-label">Mode Pilotage</div>
            </div>
        </div>
    </div>

    <!-- Contrôle des LEDs -->
    <div class="actuators-section">
        <div class="section-header">
            <h2><i class="fas fa-lightbulb"></i> Contrôle des LEDs de Signalisation</h2>
        </div>
        
        <div class="leds-grid">
            <!-- LED Place A01 -->
            <div class="actuator-card led-card">
                <div class="actuator-header">
                    <div class="actuator-title">
                        <i class="fas fa-lightbulb"></i>
                        <h3>LED Place A01</h3>
                    </div>
                    <div class="actuator-status status-red">
                        <i class="fas fa-circle"></i> Rouge
                    </div>
                </div>
                
                <div class="actuator-body">
                    <div class="led-preview">
                        <div class="led-visual led-red active"></div>
                        <div class="led-info">
                            <span class="led-state">OCCUPÉE</span>
                            <span class="led-brightness">Luminosité: 100%</span>
                        </div>
                    </div>
                    
                    <div class="led-controls">
                        <div class="color-controls">
                            <button class="color-btn red active" onclick="setLEDColor('LED_A01', 'red', this)">
                                <i class="fas fa-circle"></i>
                                Rouge
                            </button>
                            <button class="color-btn green" onclick="setLEDColor('LED_A01', 'green', this)">
                                <i class="fas fa-circle"></i>
                                Vert
                            </button>
                            <button class="color-btn off" onclick="setLEDColor('LED_A01', 'off', this)">
                                <i class="fas fa-power-off"></i>
                                Éteint
                            </button>
                        </div>
                        
                        <div class="brightness-control">
                            <label>Luminosité:</label>
                            <input type="range" min="0" max="100" value="100" 
                                   onchange="setBrightness('LED_A01', this.value)">
                            <span id="brightness-LED_A01">100%</span>
                        </div>
                    </div>
                    
                    <div class="actuator-info">
                        <div class="info-grid">
                            <div class="info-item">
                                <span class="label">ID:</span>
                                <span class="value">LED_A01</span>
                            </div>
                            <div class="info-item">
                                <span class="label">Consommation:</span>
                                <span class="value">12W</span>
                            </div>
                            <div class="info-item">
                                <span class="label">Durée de vie:</span>
                                <span class="value">45,000h</span>
                            </div>
                            <div class="info-item">
                                <span class="label">Température:</span>
                                <span class="value">35°C</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- LED Place A02 -->
            <div class="actuator-card led-card">
                <div class="actuator-header">
                    <div class="actuator-title">
                        <i class="fas fa-lightbulb"></i>
                        <h3>LED Place A02</h3>
                    </div>
                    <div class="actuator-status status-green">
                        <i class="fas fa-circle"></i> Vert
                    </div>
                </div>
                
                <div class="actuator-body">
                    <div class="led-preview">
                        <div class="led-visual led-green active"></div>
                        <div class="led-info">
                            <span class="led-state">LIBRE</span>
                            <span class="led-brightness">Luminosité: 80%</span>
                        </div>
                    </div>
                    
                    <div class="led-controls">
                        <div class="color-controls">
                            <button class="color-btn red" onclick="setLEDColor('LED_A02', 'red', this)">
                                <i class="fas fa-circle"></i>
                                Rouge
                            </button>
                            <button class="color-btn green active" onclick="setLEDColor('LED_A02', 'green', this)">
                                <i class="fas fa-circle"></i>
                                Vert
                            </button>
                            <button class="color-btn off" onclick="setLEDColor('LED_A02', 'off', this)">
                                <i class="fas fa-power-off"></i>
                                Éteint
                            </button>
                        </div>
                        
                        <div class="brightness-control">
                            <label>Luminosité:</label>
                            <input type="range" min="0" max="100" value="80" 
                                   onchange="setBrightness('LED_A02', this.value)">
                            <span id="brightness-LED_A02">80%</span>
                        </div>
                    </div>
                    
                    <div class="actuator-info">
                        <div class="info-grid">
                            <div class="info-item">
                                <span class="label">ID:</span>
                                <span class="value">LED_A02</span>
                            </div>
                            <div class="info-item">
                                <span class="label">Consommation:</span>
                                <span class="value">9.6W</span>
                            </div>
                            <div class="info-item">
                                <span class="label">Durée de vie:</span>
                                <span class="value">47,500h</span>
                            </div>
                            <div class="info-item">
                                <span class="label">Température:</span>
                                <span class="value">32°C</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contrôle des bornes de recharge -->
    <div class="actuators-section">
        <div class="section-header">
            <h2><i class="fas fa-charging-station"></i> Bornes de Recharge</h2>
        </div>
        
        <div class="charging-stations-grid">
            <!-- Borne de recharge principale -->
            <div class="actuator-card charging-card">
                <div class="actuator-header">
                    <div class="actuator-title">
                        <i class="fas fa-charging-station"></i>
                        <h3>Borne de Recharge #1</h3>
                    </div>
                    <div class="actuator-status status-available">
                        <i class="fas fa-circle"></i> Disponible
                    </div>
                </div>
                
                <div class="actuator-body">
                    <div class="charging-preview">
                        <div class="charging-visual">
                            <div class="charging-port available">
                                <i class="fas fa-plug"></i>
                            </div>
                            <div class="charging-indicator">
                                <div class="power-level" style="width: 0%"></div>
                            </div>
                        </div>
                        <div class="charging-info">
                            <span class="charging-state">PRÊTE</span>
                            <span class="charging-power">Puissance: 22kW</span>
                        </div>
                    </div>
                    
                    <div class="charging-controls">
                        <div class="power-controls">
                            <button class="power-btn" onclick="toggleCharging('CHARGE_01', this)">
                                <i class="fas fa-power-off"></i>
                                Activer
                            </button>
                            <button class="emergency-btn" onclick="emergencyStop('CHARGE_01')">
                                <i class="fas fa-exclamation-triangle"></i>
                                Arrêt d'urgence
                            </button>
                        </div>
                        
                        <div class="power-setting">
                            <label>Puissance de charge:</label>
                            <select onchange="setPowerLevel('CHARGE_01', this.value)">
                                <option value="7">7 kW (Lent)</option>
                                <option value="11">11 kW (Normal)</option>
                                <option value="22" selected>22 kW (Rapide)</option>
                            </select>
                        </div>
                        
                        <div class="pricing-control">
                            <label>Tarif par kWh:</label>
                            <input type="number" value="0.30" step="0.01" min="0" max="1" 
                                   onchange="updatePricing('CHARGE_01', this.value)">
                            <span>€</span>
                        </div>
                    </div>
                    
                    <div class="actuator-info">
                        <div class="info-grid">
                            <div class="info-item">
                                <span class="label">ID:</span>
                                <span class="value">CHARGE_01</span>
                            </div>
                            <div class="info-item">
                                <span class="label">Type:</span>
                                <span class="value">Type 2</span>
                            </div>
                            <div class="info-item">
                                <span class="label">Énergie totale:</span>
                                <span class="value">1,247 kWh</span>
                            </div>
                            <div class="info-item">
                                <span class="label">Sessions:</span>
                                <span class="value">156</span>
                            </div>
                            <div class="info-item">
                                <span class="label">Revenus:</span>
                                <span class="value">374.10 €</span>
                            </div>
                            <div class="info-item">
                                <span class="label">Statut réseau:</span>
                                <span class="value">Connecté</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Affichage OLED -->
    <div class="actuators-section">
        <div class="section-header">
            <h2><i class="fas fa-tv"></i> Affichage OLED</h2>
        </div>
        
        <div class="oled-control-section">
            <div class="actuator-card oled-card">
                <div class="actuator-header">
                    <div class="actuator-title">
                        <i class="fas fa-tv"></i>
                        <h3>Écran Principal</h3>
                    </div>
                    <div class="actuator-status status-active">
                        <i class="fas fa-circle"></i> Actif
                    </div>
                </div>
                
                <div class="actuator-body">
                    <div class="oled-display-area">
                        <div class="oled-preview">
                            <div class="oled-screen">
                                <div class="oled-content" id="oled-display">
                                    <h4>PARKING ISEP</h4>
                                    <div class="oled-line">Places: <span id="oled-places">8/10</span></div>
                                    <div class="oled-line">Bornes: <span id="oled-charging">1 libre</span></div>
                                    <div class="oled-line">Tarif: <span id="oled-price">6€/h</span></div>
                                    <div class="oled-line">Recharge: <span id="oled-charge-price">0.30€/kWh</span></div>
                                    <div class="oled-line">Temp: <span id="oled-temp">22°C</span></div>
                                    <div class="oled-time" id="oled-time"><?= date('H:i:s') ?></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="oled-controls-panel">
                            <div class="display-controls">
                                <button class="control-btn" onclick="toggleOLED()">
                                    <i class="fas fa-power-off"></i>
                                    ON/OFF
                                </button>
                                <button class="control-btn" onclick="adjustBrightness()">
                                    <i class="fas fa-sun"></i>
                                    Luminosité
                                </button>
                                <button class="control-btn" onclick="refreshDisplay()">
                                    <i class="fas fa-sync"></i>
                                    Actualiser
                                </button>
                            </div>
                            
                            <div class="content-editor">
                                <h4>Modifier le contenu:</h4>
                                <div class="editor-field">
                                    <label>Titre:</label>
                                    <input type="text" value="PARKING ISEP" onchange="updateOLEDContent('title', this.value)">
                                </div>
                                <div class="editor-field">
                                    <label>Tarif parking (€/h):</label>
                                    <input type="number" value="6" step="0.5" onchange="updateOLEDContent('parking-price', this.value)">
                                </div>
                                <div class="editor-field">
                                    <label>Tarif recharge (€/kWh):</label>
                                    <input type="number" value="0.30" step="0.01" onchange="updateOLEDContent('charge-price', this.value)">
                                </div>
                                <div class="editor-field">
                                    <label>Message personnalisé:</label>
                                    <textarea placeholder="Message optionnel..." onchange="updateOLEDContent('message', this.value)"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="actuator-info">
                        <div class="info-grid">
                            <div class="info-item">
                                <span class="label">ID:</span>
                                <span class="value">OLED_MAIN</span>
                            </div>
                            <div class="info-item">
                                <span class="label">Résolution:</span>
                                <span class="value">128x64</span>
                            </div>
                            <div class="info-item">
                                <span class="label">Luminosité:</span>
                                <span class="value">85%</span>
                            </div>
                            <div class="info-item">
                                <span class="label">Temps de fonctionnement:</span>
                                <span class="value">2,847h</span>
                            </div>
                        </div>
                    </div>
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
            <button class="action-btn" onclick="addNewActuator()">
                <i class="fas fa-plus"></i>
                <span>Ajouter un Actionneur</span>
            </button>
            <button class="action-btn" onclick="automationMode()">
                <i class="fas fa-robot"></i>
                <span>Mode Automatique</span>
            </button>
            <button class="action-btn" onclick="scheduleActions()">
                <i class="fas fa-calendar-alt"></i>
                <span>Programmer Actions</span>
            </button>
            <button class="action-btn" onclick="exportLogs()">
                <i class="fas fa-file-export"></i>
                <span>Exporter Logs</span>
            </button>
        </div>
    </div>
</div>

<style>
/* Styles spécifiques aux actionneurs */
.actuators-stats {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.actuators-stats .stat-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-radius: 15px;
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    transition: all 0.3s ease;
}

.actuators-stats .stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
}

.active-leds .stat-icon { background: linear-gradient(135deg, #f59e0b, #d97706); }
.charging-stations .stat-icon { background: linear-gradient(135deg, #22c55e, #16a34a); }
.power-consumption .stat-icon { background: linear-gradient(135deg, #ef4444, #dc2626); }
.automation .stat-icon { background: linear-gradient(135deg, #8b5cf6, #7c3aed); }

/* Sections d'actionneurs */
.actuators-section {
    margin-bottom: 3rem;
}

.section-header {
    margin-bottom: 2rem;
}

.section-header h2 {
    color: white;
    font-size: 1.75rem;
    font-weight: 700;
    text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.section-header i {
    color: #ffd700;
}

/* Grilles d'actionneurs */
.leds-grid, .charging-stations-grid, .oled-control-section {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(450px, 1fr));
    gap: 2rem;
}

.actuator-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    overflow: hidden;
    transition: all 0.3s ease;
}

.actuator-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
}

.led-card { border-left: 4px solid #f59e0b; }
.charging-card { border-left: 4px solid #22c55e; }
.oled-card { border-left: 4px solid #3b82f6; }

.actuator-header {
    background: linear-gradient(135deg, rgba(30, 64, 175, 0.1), rgba(59, 130, 246, 0.1));
    padding: 1.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid rgba(59, 130, 246, 0.1);
}

.actuator-title {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.actuator-title i {
    color: #3b82f6;
    font-size: 1.25rem;
}

.actuator-title h3 {
    margin: 0;
    color: #1e293b;
    font-size: 1.125rem;
    font-weight: 600;
}

.actuator-status {
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.status-red { background: #fecaca; color: #991b1b; }
.status-green { background: #dcfce7; color: #166534; }
.status-available { background: #dbeafe; color: #1e40af; }
.status-active { background: #dcfce7; color: #166534; }

.actuator-body {
    padding: 1.5rem;
}

/* Contrôles LED */
.led-preview {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    margin-bottom: 1.5rem;
    padding: 1rem;
    background: #f8fafc;
    border-radius: 15px;
}

.led-visual {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 3px solid #e2e8f0;
    transition: all 0.3s ease;
}

.led-visual.active {
    box-shadow: 0 0 20px currentColor;
}

.led-red.active { background: #ef4444; border-color: #ef4444; }
.led-green.active { background: #22c55e; border-color: #22c55e; }

.led-info {
    flex: 1;
}

.led-state {
    display: block;
    font-size: 1.25rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 0.25rem;
}

.led-brightness {
    color: #64748b;
    font-size: 0.875rem;
}

.led-controls {
    margin-bottom: 1.5rem;
}

.color-controls {
    display: flex;
    gap: 0.75rem;
    margin-bottom: 1rem;
}

.color-btn {
    flex: 1;
    padding: 0.75rem;
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    background: #f8fafc;
    color: #64748b;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    font-weight: 500;
}

.color-btn:hover {
    transform: translateY(-2px);
}

.color-btn.red.active { background: #fecaca; border-color: #ef4444; color: #991b1b; }
.color-btn.green.active { background: #dcfce7; border-color: #22c55e; color: #166534; }
.color-btn.off.active { background: #f3f4f6; border-color: #6b7280; color: #374151; }

.brightness-control {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.brightness-control label {
    color: #64748b;
    font-weight: 500;
    font-size: 0.875rem;
}

.brightness-control input[type="range"] {
    flex: 1;
    height: 6px;
    border-radius: 3px;
    background: #e2e8f0;
    outline: none;
}

.brightness-control span {
    color: #1e293b;
    font-weight: 600;
    font-size: 0.875rem;
    min-width: 40px;
}

/* Contrôles de recharge */
.charging-preview {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    margin-bottom: 1.5rem;
    padding: 1rem;
    background: #f8fafc;
    border-radius: 15px;
}

.charging-visual {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
}

.charging-port {
    width: 60px;
    height: 60px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
    background: linear-gradient(135deg, #22c55e, #16a34a);
}

.charging-indicator {
    width: 60px;
    height: 8px;
    background: #e2e8f0;
    border-radius: 4px;
    overflow: hidden;
}

.power-level {
    height: 100%;
    background: linear-gradient(90deg, #22c55e, #16a34a);
    transition: width 0.3s ease;
}

.charging-info {
    flex: 1;
}

.charging-state {
    display: block;
    font-size: 1.25rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 0.25rem;
}

.charging-power {
    color: #64748b;
    font-size: 0.875rem;
}

.charging-controls {
    margin-bottom: 1.5rem;
}

.power-controls {
    display: flex;
    gap: 0.75rem;
    margin-bottom: 1rem;
}

.power-btn {
    flex: 1;
    padding: 0.75rem 1rem;
    border: none;
    border-radius: 10px;
    background: linear-gradient(135deg, #22c55e, #16a34a);
    color: white;
    cursor: pointer;
    transition: all 0.3s ease;
    font-weight: 500;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.power-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(34, 197, 94, 0.3);
}

.emergency-btn {
    flex: 1;
    padding: 0.75rem 1rem;
    border: none;
    border-radius: 10px;
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: white;
    cursor: pointer;
    transition: all 0.3s ease;
    font-weight: 500;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.emergency-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(239, 68, 68, 0.3);
}

.power-setting, .pricing-control {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 0.75rem;
}

.power-setting label, .pricing-control label {
    color: #64748b;
    font-weight: 500;
    font-size: 0.875rem;
    min-width: 120px;
}

.power-setting select, .pricing-control input {
    flex: 1;
    padding: 0.5rem;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    background: white;
}

/* Contrôles OLED */
.oled-display-area {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
    margin-bottom: 1.5rem;
}

.oled-preview {
    display: flex;
    justify-content: center;
    align-items: center;
}

.oled-screen {
    background: #1a1a1a;
    border-radius: 15px;
    padding: 1.5rem;
    width: 280px;
    border: 3px solid #333;
    box-shadow: 0 0 30px rgba(0, 255, 0, 0.3);
}

.oled-content {
    color: #00ff00;
    font-family: 'Courier New', monospace;
    font-size: 0.875rem;
    text-align: center;
}

.oled-content h4 {
    margin: 0 0 1rem 0;
    color: #ffffff;
    font-size: 1rem;
    border-bottom: 1px solid #333;
    padding-bottom: 0.5rem;
}

.oled-line {
    margin: 0.5rem 0;
    line-height: 1.4;
}

.oled-time {
    margin-top: 1rem;
    color: #ffff00;
    font-weight: bold;
    font-size: 1rem;
    border-top: 1px solid #333;
    padding-top: 0.5rem;
}

.oled-controls-panel {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.display-controls {
    display: flex;
    gap: 0.5rem;
}

.control-btn {
    flex: 1;
    padding: 0.75rem;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    background: #f8fafc;
    color: #64748b;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.75rem;
    font-weight: 500;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.25rem;
}

.control-btn:hover {
    background: #3b82f6;
    color: white;
    transform: translateY(-2px);
}

.content-editor h4 {
    color: #1e293b;
    font-size: 1rem;
    margin-bottom: 1rem;
}

.editor-field {
    margin-bottom: 1rem;
}

.editor-field label {
    display: block;
    color: #64748b;
    font-weight: 500;
    font-size: 0.875rem;
    margin-bottom: 0.5rem;
}

.editor-field input, .editor-field textarea {
    width: 100%;
    padding: 0.5rem;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    background: white;
    font-size: 0.875rem;
}

.editor-field textarea {
    height: 60px;
    resize: vertical;
}

/* Info grids */
.actuator-info {
    border-top: 1px solid #f1f5f9;
    padding-top: 1rem;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 0.75rem;
}

.info-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem;
    background: #f8fafc;
    border-radius: 8px;
}

.info-item .label {
    color: #64748b;
    font-size: 0.75rem;
    font-weight: 500;
}

.info-item .value {
    color: #1e293b;
    font-weight: 600;
    font-size: 0.875rem;
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
    gap: 0.75rem;
}

.actions-header i {
    color: #3b82f6;
}

.actions-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1rem;
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

/* Responsive */
@media (max-width: 1200px) {
    .actuators-stats {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .leds-grid, .charging-stations-grid, .oled-control-section {
        grid-template-columns: 1fr;
    }
    
    .oled-display-area {
        grid-template-columns: 1fr;
    }
    
    .actions-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .actuators-stats {
        grid-template-columns: 1fr;
    }
    
    .info-grid {
        grid-template-columns: 1fr;
    }
    
    .actions-grid {
        grid-template-columns: 1fr;
    }
    
    .color-controls, .power-controls {
        flex-direction: column;
    }
}
</style>

<script>
// Fonctions pour les LEDs
function setLEDColor(ledId, color, button) {
    // Retirer la classe active de tous les boutons de cette LED
    const card = button.closest('.led-card');
    const buttons = card.querySelectorAll('.color-btn');
    buttons.forEach(btn => btn.classList.remove('active'));
    
    // Ajouter la classe active au bouton cliqué
    button.classList.add('active');
    
    // Mettre à jour l'affichage visuel
    const ledVisual = card.querySelector('.led-visual');
    const ledState = card.querySelector('.led-state');
    const statusBadge = card.querySelector('.actuator-status');
    
    ledVisual.className = 'led-visual';
    
    switch(color) {
        case 'red':
            ledVisual.classList.add('led-red', 'active');
            ledState.textContent = 'OCCUPÉE';
            statusBadge.innerHTML = '<i class="fas fa-circle"></i> Rouge';
            statusBadge.className = 'actuator-status status-red';
            break;
        case 'green':
            ledVisual.classList.add('led-green', 'active');
            ledState.textContent = 'LIBRE';
            statusBadge.innerHTML = '<i class="fas fa-circle"></i> Vert';
            statusBadge.className = 'actuator-status status-green';
            break;
        case 'off':
            ledState.textContent = 'ÉTEINTE';
            statusBadge.innerHTML = '<i class="fas fa-power-off"></i> Éteinte';
            statusBadge.className = 'actuator-status status-off';
            break;
    }
    
    console.log('LED ' + ledId + ' définie sur ' + color);
}

function setBrightness(ledId, value) {
    const brightnessSpan = document.getElementById('brightness-' + ledId);
    if (brightnessSpan) {
        brightnessSpan.textContent = value + '%';
    }
    
    const card = document.querySelector(`[data-led="${ledId}"]`);
    if (card) {
        const ledBrightness = card.querySelector('.led-brightness');
        if (ledBrightness) {
            ledBrightness.textContent = 'Luminosité: ' + value + '%';
        }
    }
    
    console.log('Luminosité LED ' + ledId + ' définie à ' + value + '%');
}

// Fonctions pour les bornes de recharge
function toggleCharging(chargeId, button) {
    const card = button.closest('.charging-card');
    const chargingState = card.querySelector('.charging-state');
    const statusBadge = card.querySelector('.actuator-status');
    const powerLevel = card.querySelector('.power-level');
    
    if (button.textContent.includes('Activer')) {
        button.innerHTML = '<i class="fas fa-stop"></i> Arrêter';
        button.style.background = 'linear-gradient(135deg, #ef4444, #dc2626)';
        chargingState.textContent = 'EN CHARGE';
        statusBadge.innerHTML = '<i class="fas fa-circle"></i> En charge';
        statusBadge.className = 'actuator-status status-red';
        
        // Animation de charge
        let progress = 0;
        const interval = setInterval(() => {
            progress += 2;
            powerLevel.style.width = progress + '%';
            if (progress >= 100) {
                clearInterval(interval);
            }
        }, 100);
    } else {
        button.innerHTML = '<i class="fas fa-power-off"></i> Activer';
        button.style.background = 'linear-gradient(135deg, #22c55e, #16a34a)';
        chargingState.textContent = 'PRÊTE';
        statusBadge.innerHTML = '<i class="fas fa-circle"></i> Disponible';
        statusBadge.className = 'actuator-status status-available';
        powerLevel.style.width = '0%';
    }
    
    console.log('Borne ' + chargeId + ' basculée');
}

function emergencyStop(chargeId) {
    if (confirm('Êtes-vous sûr de vouloir effectuer un arrêt d\'urgence ?')) {
        const card = document.querySelector(`[data-charge="${chargeId}"]`);
        if (card) {
            const powerBtn = card.querySelector('.power-btn');
            const chargingState = card.querySelector('.charging-state');
            const statusBadge = card.querySelector('.actuator-status');
            const powerLevel = card.querySelector('.power-level');
            
            powerBtn.innerHTML = '<i class="fas fa-power-off"></i> Activer';
            powerBtn.style.background = 'linear-gradient(135deg, #22c55e, #16a34a)';
            chargingState.textContent = 'ARRÊT D\'URGENCE';
            statusBadge.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Arrêt d\'urgence';
            statusBadge.className = 'actuator-status status-warning';
            powerLevel.style.width = '0%';
        }
        
        alert('Arrêt d\'urgence activé pour ' + chargeId);
    }
}

function setPowerLevel(chargeId, power) {
    console.log('Puissance de ' + chargeId + ' définie à ' + power + ' kW');
}

function updatePricing(chargeId, price) {
    const oledChargePrice = document.getElementById('oled-charge-price');
    if (oledChargePrice) {
        oledChargePrice.textContent = price + '€/kWh';
    }
    console.log('Tarif de ' + chargeId + ' défini à ' + price + '€/kWh');
}

// Fonctions pour l'OLED
function toggleOLED() {
    const oledScreen = document.querySelector('.oled-screen');
    const oledContent = document.getElementById('oled-display');
    
    if (oledContent.style.opacity === '0.3') {
        oledContent.style.opacity = '1';
        oledScreen.style.boxShadow = '0 0 30px rgba(0, 255, 0, 0.3)';
        console.log('OLED activé');
    } else {
        oledContent.style.opacity = '0.3';
        oledScreen.style.boxShadow = '0 0 10px rgba(0, 0, 0, 0.3)';
        console.log('OLED désactivé');
    }
}

function adjustBrightness() {
    const brightness = prompt('Luminosité (0-100):', '85');
    if (brightness !== null && brightness >= 0 && brightness <= 100) {
        const oledScreen = document.querySelector('.oled-screen');
        oledScreen.style.filter = `brightness(${brightness}%)`;
        console.log('Luminosité OLED définie à ' + brightness + '%');
    }
}

function refreshDisplay() {
    const oledTime = document.getElementById('oled-time');
    if (oledTime) {
        oledTime.textContent = new Date().toLocaleTimeString();
    }
    
    // Animation de rafraîchissement
    const oledScreen = document.querySelector('.oled-screen');
    oledScreen.style.boxShadow = '0 0 50px rgba(0, 255, 0, 0.8)';
    setTimeout(() => {
        oledScreen.style.boxShadow = '0 0 30px rgba(0, 255, 0, 0.3)';
    }, 1000);
    
    console.log('Affichage OLED rafraîchi');
}

function updateOLEDContent(field, value) {
    switch(field) {
        case 'title':
            const titleElement = document.querySelector('.oled-content h4');
            if (titleElement) titleElement.textContent = value;
            break;
        case 'parking-price':
            const priceElement = document.getElementById('oled-price');
            if (priceElement) priceElement.textContent = value + '€/h';
            break;
        case 'charge-price':
            const chargePriceElement = document.getElementById('oled-charge-price');
            if (chargePriceElement) chargePriceElement.textContent = value + '€/kWh';
            break;
        case 'message':
            // Ajouter un message personnalisé si nécessaire
            break;
    }
    console.log('Contenu OLED mis à jour: ' + field + ' = ' + value);
}

// Actions globales
function addNewActuator() {
    alert('Ajout d\'un nouvel actionneur');
}

function automationMode() {
    const isAuto = confirm('Activer le mode automatique ?');
    if (isAuto) {
        alert('Mode automatique activé - Les actionneurs seront pilotés automatiquement selon les capteurs');
    }
}

function scheduleActions() {
    alert('Programmation d\'actions automatiques');
}

function exportLogs() {
    alert('Export des logs d\'actionneurs');
}

// Mise à jour automatique de l'heure sur l'OLED
setInterval(() => {
    const oledTime = document.getElementById('oled-time');
    if (oledTime) {
        oledTime.textContent = new Date().toLocaleTimeString();
    }
}, 1000);

// Simulation de données en temps réel
setInterval(() => {
    console.log('Mise à jour des données actionneurs...');
}, 10000);
</script>

<?php require_once ROOT_PATH . '/app/views/partials/footer.php'; ?>