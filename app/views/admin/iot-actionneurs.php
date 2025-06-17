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
            <p>Contrôle et pilotage des équipements du système IoT</p>
        </div>
    </div>

    <!-- Navigation -->
    <div class="dashboard-nav">
        <div class="nav-tabs">
            <a href="/iot-dashboard" class="nav-tab">
                <i class="fas fa-arrow-left"></i> Retour IoT
            </a>
            <a href="/admin/iot-capteurs" class="nav-tab">
                <i class="fas fa-satellite-dish"></i> Capteurs
            </a>
            <a href="/admin/iot-actionneurs" class="nav-tab active">
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
        <div class="stat-card active-actuators">
            <div class="stat-icon">
                <i class="fas fa-power-off"></i>
            </div>
            <div class="stat-info">
                <div class="stat-number">5</div>
                <div class="stat-label">Actionneurs Actifs</div>
            </div>
        </div>
        
        <div class="stat-card motors-running">
            <div class="stat-icon">
                <i class="fas fa-cog"></i>
            </div>
            <div class="stat-info">
                <div class="stat-number">2</div>
                <div class="stat-label">Moteurs en Marche</div>
            </div>
        </div>
        
        <div class="stat-card power-consumption">
            <div class="stat-icon">
                <i class="fas fa-bolt"></i>
            </div>
            <div class="stat-info">
                <div class="stat-number">85W</div>
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

    <!-- Grille des actionneurs -->
    <div class="actuators-grid">
        <!-- Buzzer -->
        <div class="actuator-card buzzer-card">
            <div class="actuator-header">
                <div class="actuator-title">
                    <i class="fas fa-volume-up"></i>
                    <h3>Buzzer d'Alerte</h3>
                </div>
                <div class="actuator-status status-off">
                    <i class="fas fa-circle"></i> Éteint
                </div>
            </div>
            
            <div class="actuator-body">
                <div class="buzzer-controls">
                    <div class="sound-visual">
                        <div class="buzzer-icon">
                            <i class="fas fa-volume-up"></i>
                        </div>
                        <div class="sound-waves"></div>
                    </div>
                    
                    <div class="control-buttons">
                        <button class="control-btn" onclick="toggleBuzzer('BUZZ_001', this)">
                            <i class="fas fa-play"></i> Activer
                        </button>
                        <button class="control-btn" onclick="testBuzzer('BUZZ_001')">
                            <i class="fas fa-music"></i> Test
                        </button>
                    </div>
                    
                    <div class="frequency-control">
                        <label>Fréquence:</label>
                        <input type="range" min="100" max="5000" value="1000" 
                               onchange="setFrequency('BUZZ_001', this.value)">
                        <span id="freq-BUZZ_001">1000 Hz</span>
                    </div>
                </div>
                
                <div class="actuator-info">
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="label">ID:</span>
                            <span class="value">BUZZ_001</span>
                        </div>
                        <div class="info-item">
                            <span class="label">Type:</span>
                            <span class="value">Piézoélectrique</span>
                        </div>
                        <div class="info-item">
                            <span class="label">Puissance:</span>
                            <span class="value">3W</span>
                        </div>
                        <div class="info-item">
                            <span class="label">Plage:</span>
                            <span class="value">100-5000 Hz</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Afficheur 7 segments -->
        <div class="actuator-card display-card">
            <div class="actuator-header">
                <div class="actuator-title">
                    <i class="fas fa-digital-tachograph"></i>
                    <h3>Afficheur 7 Segments</h3>
                </div>
                <div class="actuator-status status-active">
                    <i class="fas fa-circle"></i> Actif
                </div>
            </div>
            
            <div class="actuator-body">
                <div class="display-preview">
                    <div class="seven-segment-display">
                        <div class="digit" id="digit1">8</div>
                        <div class="digit" id="digit2">8</div>
                        <div class="digit" id="digit3">.</div>
                        <div class="digit" id="digit4">8</div>
                    </div>
                    <div class="display-label">Valeur affichée: 88.8</div>
                </div>
                
                <div class="display-controls">
                    <div class="input-group">
                        <label>Valeur à afficher:</label>
                        <input type="number" step="0.1" value="88.8" 
                               onchange="updateDisplay('DISP_001', this.value)">
                    </div>
                    
                    <div class="brightness-control">
                        <label>Luminosité:</label>
                        <input type="range" min="0" max="100" value="80" 
                               onchange="setDisplayBrightness('DISP_001', this.value)">
                        <span id="brightness-DISP_001">80%</span>
                    </div>
                </div>
                
                <div class="actuator-info">
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="label">ID:</span>
                            <span class="value">DISP_001</span>
                        </div>
                        <div class="info-item">
                            <span class="label">Digits:</span>
                            <span class="value">4 + point</span>
                        </div>
                        <div class="info-item">
                            <span class="label">Couleur:</span>
                            <span class="value">Rouge</span>
                        </div>
                        <div class="info-item">
                            <span class="label">Consommation:</span>
                            <span class="value">12W</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- LEDs -->
        <div class="actuator-card led-card">
            <div class="actuator-header">
                <div class="actuator-title">
                    <i class="fas fa-lightbulb"></i>
                    <h3>LEDs de Signalisation</h3>
                </div>
                <div class="actuator-status status-active">
                    <i class="fas fa-circle"></i> Actif
                </div>
            </div>
            
            <div class="actuator-body">
                <div class="leds-preview">
                    <div class="led-strip">
                        <div class="led-item led-red active" data-color="red"></div>
                        <div class="led-item led-green" data-color="green"></div>
                        <div class="led-item led-blue" data-color="blue"></div>
                        <div class="led-item led-yellow active" data-color="yellow"></div>
                    </div>
                    <div class="led-status">2 LEDs actives</div>
                </div>
                
                <div class="led-controls">
                    <div class="color-controls">
                        <button class="color-btn red active" onclick="toggleLED('LED_001', 'red', this)">
                            <i class="fas fa-circle"></i> Rouge
                        </button>
                        <button class="color-btn green" onclick="toggleLED('LED_001', 'green', this)">
                            <i class="fas fa-circle"></i> Vert
                        </button>
                        <button class="color-btn blue" onclick="toggleLED('LED_001', 'blue', this)">
                            <i class="fas fa-circle"></i> Bleu
                        </button>
                        <button class="color-btn yellow active" onclick="toggleLED('LED_001', 'yellow', this)">
                            <i class="fas fa-circle"></i> Jaune
                        </button>
                    </div>
                    
                    <div class="pattern-controls">
                        <button class="pattern-btn" onclick="setPattern('LED_001', 'static')">Fixe</button>
                        <button class="pattern-btn" onclick="setPattern('LED_001', 'blink')">Clignotant</button>
                        <button class="pattern-btn" onclick="setPattern('LED_001', 'fade')">Fondu</button>
                    </div>
                </div>
                
                <div class="actuator-info">
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="label">ID:</span>
                            <span class="value">LED_001</span>
                        </div>
                        <div class="info-item">
                            <span class="label">Nombre:</span>
                            <span class="value">4 LEDs</span>
                        </div>
                        <div class="info-item">
                            <span class="label">Type:</span>
                            <span class="value">RGB + Jaune</span>
                        </div>
                        <div class="info-item">
                            <span class="label">Consommation:</span>
                            <span class="value">8W</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Moteur 1 -->
        <div class="actuator-card motor-card">
            <div class="actuator-header">
                <div class="actuator-title">
                    <i class="fas fa-cog"></i>
                    <h3>Moteur Principal</h3>
                </div>
                <div class="actuator-status status-running">
                    <i class="fas fa-circle"></i> En marche
                </div>
            </div>
            
            <div class="actuator-body">
                <div class="motor-visual">
                    <div class="motor-icon rotating">
                        <i class="fas fa-cog"></i>
                    </div>
                    <div class="motor-stats">
                        <div class="stat">
                            <span class="label">Vitesse:</span>
                            <span class="value" id="speed-MOT_001">1200 RPM</span>
                        </div>
                        <div class="stat">
                            <span class="label">Direction:</span>
                            <span class="value">Horaire</span>
                        </div>
                    </div>
                </div>
                
                <div class="motor-controls">
                    <div class="control-buttons">
                        <button class="control-btn start" onclick="startMotor('MOT_001')">
                            <i class="fas fa-play"></i> Démarrer
                        </button>
                        <button class="control-btn stop" onclick="stopMotor('MOT_001')">
                            <i class="fas fa-stop"></i> Arrêter
                        </button>
                        <button class="control-btn reverse" onclick="reverseMotor('MOT_001')">
                            <i class="fas fa-undo"></i> Inverser
                        </button>
                    </div>
                    
                    <div class="speed-control">
                        <label>Vitesse:</label>
                        <input type="range" min="0" max="2000" value="1200" 
                               onchange="setMotorSpeed('MOT_001', this.value)">
                        <span id="speed-display-MOT_001">1200 RPM</span>
                    </div>
                </div>
                
                <div class="actuator-info">
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="label">ID:</span>
                            <span class="value">MOT_001</span>
                        </div>
                        <div class="info-item">
                            <span class="label">Type:</span>
                            <span class="value">DC 12V</span>
                        </div>
                        <div class="info-item">
                            <span class="label">Puissance:</span>
                            <span class="value">25W</span>
                        </div>
                        <div class="info-item">
                            <span class="label">Couple:</span>
                            <span class="value">0.5 Nm</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Afficheur OLED -->
        <div class="actuator-card oled-card">
            <div class="actuator-header">
                <div class="actuator-title">
                    <i class="fas fa-tv"></i>
                    <h3>Afficheur OLED</h3>
                </div>
                <div class="actuator-status status-active">
                    <i class="fas fa-circle"></i> Actif
                </div>
            </div>
            
            <div class="actuator-body">
                <div class="oled-preview">
                    <div class="oled-screen">
                        <div class="oled-content">
                            <div class="oled-line">Système IoT</div>
                            <div class="oled-line">Temp: 22.3°C</div>
                            <div class="oled-line">Hum: 65%</div>
                            <div class="oled-line">Status: OK</div>
                        </div>
                    </div>
                    <div class="oled-info">128x64 pixels</div>
                </div>
                
                <div class="oled-controls">
                    <div class="text-input">
                        <label>Texte à afficher:</label>
                        <textarea rows="4" placeholder="Entrez le texte..." 
                                  onchange="updateOLED('OLED_001', this.value)">Système IoT
Temp: 22.3°C
Hum: 65%
Status: OK</textarea>
                    </div>
                    
                    <div class="display-options">
                        <button class="option-btn" onclick="clearOLED('OLED_001')">
                            <i class="fas fa-eraser"></i> Effacer
                        </button>
                        <button class="option-btn" onclick="invertOLED('OLED_001')">
                            <i class="fas fa-adjust"></i> Inverser
                        </button>
                    </div>
                </div>
                
                <div class="actuator-info">
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="label">ID:</span>
                            <span class="value">OLED_001</span>
                        </div>
                        <div class="info-item">
                            <span class="label">Résolution:</span>
                            <span class="value">128x64</span>
                        </div>
                        <div class="info-item">
                            <span class="label">Interface:</span>
                            <span class="value">I2C</span>
                        </div>
                        <div class="info-item">
                            <span class="label">Consommation:</span>
                            <span class="value">0.5W</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Moteur 2 -->
        <div class="actuator-card motor-card">
            <div class="actuator-header">
                <div class="actuator-title">
                    <i class="fas fa-cog"></i>
                    <h3>Moteur Secondaire</h3>
                </div>
                <div class="actuator-status status-off">
                    <i class="fas fa-circle"></i> Arrêté
                </div>
            </div>
            
            <div class="actuator-body">
                <div class="motor-visual">
                    <div class="motor-icon">
                        <i class="fas fa-cog"></i>
                    </div>
                    <div class="motor-stats">
                        <div class="stat">
                            <span class="label">Vitesse:</span>
                            <span class="value" id="speed-MOT_002">0 RPM</span>
                        </div>
                        <div class="stat">
                            <span class="label">Direction:</span>
                            <span class="value">-</span>
                        </div>
                    </div>
                </div>
                
                <div class="motor-controls">
                    <div class="control-buttons">
                        <button class="control-btn start" onclick="startMotor('MOT_002')">
                            <i class="fas fa-play"></i> Démarrer
                        </button>
                        <button class="control-btn stop" onclick="stopMotor('MOT_002')">
                            <i class="fas fa-stop"></i> Arrêter
                        </button>
                        <button class="control-btn reverse" onclick="reverseMotor('MOT_002')">
                            <i class="fas fa-undo"></i> Inverser
                        </button>
                    </div>
                    
                    <div class="speed-control">
                        <label>Vitesse:</label>
                        <input type="range" min="0" max="1500" value="0" 
                               onchange="setMotorSpeed('MOT_002', this.value)">
                        <span id="speed-display-MOT_002">0 RPM</span>
                    </div>
                </div>
                
                <div class="actuator-info">
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="label">ID:</span>
                            <span class="value">MOT_002</span>
                        </div>
                        <div class="info-item">
                            <span class="label">Type:</span>
                            <span class="value">Servo 9V</span>
                        </div>
                        <div class="info-item">
                            <span class="label">Puissance:</span>
                            <span class="value">15W</span>
                        </div>
                        <div class="info-item">
                            <span class="label">Angle:</span>
                            <span class="value">180°</span>
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
.actuators-stats {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1.5rem;
    margin-bottom: 3rem;
}

.actuators-stats .stat-card {
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

.actuators-stats .stat-card:hover {
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

.active-actuators .stat-icon { background: linear-gradient(135deg, #22c55e, #16a34a); }
.motors-running .stat-icon { background: linear-gradient(135deg, #f59e0b, #d97706); }
.power-consumption .stat-icon { background: linear-gradient(135deg, #ef4444, #dc2626); }
.automation .stat-icon { background: linear-gradient(135deg, #8b5cf6, #7c3aed); }

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

/* Grille des actionneurs */
.actuators-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(450px, 1fr));
    gap: 2rem;
    margin-bottom: 3rem;
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

.buzzer-card { border-left: 4px solid #f59e0b; }
.display-card { border-left: 4px solid #3b82f6; }
.led-card { border-left: 4px solid #8b5cf6; }
.motor-card { border-left: 4px solid #22c55e; }
.oled-card { border-left: 4px solid #06b6d4; }

.actuator-header {
    background: linear-gradient(135deg, rgba(245, 158, 11, 0.1), rgba(217, 119, 6, 0.1));
    padding: 1.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid rgba(245, 158, 11, 0.1);
}

.actuator-title {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.actuator-title i {
    color: #f59e0b;
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
    border-radius: 25px;
    font-size: 0.875rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.status-active {
    background: #dcfce7;
    color: #16a34a;
}

.status-running {
    background: #dbeafe;
    color: #1d4ed8;
}

.status-off {
    background: #f1f5f9;
    color: #64748b;
}

.actuator-body {
    padding: 1.5rem;
}

/* Contrôles Buzzer */
.buzzer-controls {
    text-align: center;
    margin-bottom: 1.5rem;
}

.sound-visual {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1rem;
    margin-bottom: 1.5rem;
    padding: 2rem;
    background: #f8fafc;
    border-radius: 15px;
}

.buzzer-icon {
    font-size: 3rem;
    color: #f59e0b;
}

.sound-waves {
    width: 60px;
    height: 60px;
    position: relative;
}

.sound-waves::before,
.sound-waves::after {
    content: '';
    position: absolute;
    border: 2px solid #f59e0b;
    border-radius: 50%;
    opacity: 0;
}

.sound-waves.active::before {
    width: 30px;
    height: 30px;
    top: 15px;
    left: 15px;
    animation: soundWave 1s infinite;
}

.sound-waves.active::after {
    width: 50px;
    height: 50px;
    top: 5px;
    left: 5px;
    animation: soundWave 1s infinite 0.3s;
}

@keyframes soundWave {
    0% { transform: scale(0.5); opacity: 1; }
    100% { transform: scale(1.5); opacity: 0; }
}

.control-buttons {
    display: flex;
    gap: 1rem;
    margin-bottom: 1rem;
}

.control-btn {
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

.control-btn:hover {
    background: #f59e0b;
    color: white;
    transform: translateY(-2px);
}

.control-btn.start:hover { background: #22c55e; }
.control-btn.stop:hover { background: #ef4444; }
.control-btn.reverse:hover { background: #8b5cf6; }

.frequency-control {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: #f8fafc;
    border-radius: 10px;
}

.frequency-control label {
    color: #64748b;
    font-weight: 500;
    font-size: 0.875rem;
}

.frequency-control span {
    color: #1e293b;
    font-weight: 600;
    font-size: 0.875rem;
    min-width: 60px;
}

/* Afficheur 7 segments */
.display-preview {
    text-align: center;
    margin-bottom: 1.5rem;
}

.seven-segment-display {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 0.5rem;
    padding: 2rem;
    background: #1a1a1a;
    border-radius: 15px;
    margin-bottom: 1rem;
}

.digit {
    font-family: 'Courier New', monospace;
    font-size: 3rem;
    font-weight: bold;
    color: #ff0000;
    text-shadow: 0 0 10px #ff0000;
    min-width: 50px;
    text-align: center;
}

.display-label {
    color: #64748b;
    font-weight: 500;
}

.display-controls {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.input-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.input-group label {
    color: #64748b;
    font-weight: 500;
    font-size: 0.875rem;
}

.input-group input {
    padding: 0.75rem;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    font-size: 1rem;
}

.brightness-control {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: #f8fafc;
    border-radius: 10px;
}

.brightness-control label {
    color: #64748b;
    font-weight: 500;
    font-size: 0.875rem;
}

.brightness-control span {
    color: #1e293b;
    font-weight: 600;
    font-size: 0.875rem;
    min-width: 40px;
}

/* LEDs */
.leds-preview {
    text-align: center;
    margin-bottom: 1.5rem;
    padding: 2rem;
    background: #f8fafc;
    border-radius: 15px;
}

.led-strip {
    display: flex;
    justify-content: center;
    gap: 1rem;
    margin-bottom: 1rem;
}

.led-item {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    border: 3px solid #e2e8f0;
    transition: all 0.3s ease;
    position: relative;
}

.led-item.active {
    box-shadow: 0 0 20px currentColor;
}

.led-item.active::before {
    content: '';
    position: absolute;
    width: 60px;
    height: 60px;
    border-radius: 50%;
    top: -10px;
    left: -10px;
    background: currentColor;
    opacity: 0.2;
    animation: pulse 2s infinite;
}

.led-red { background: #ef4444; color: #ef4444; }
.led-green { background: #22c55e; color: #22c55e; }
.led-blue { background: #3b82f6; color: #3b82f6; }
.led-yellow { background: #fbbf24; color: #fbbf24; }

.led-red:not(.active) { background: #fee2e2; }
.led-green:not(.active) { background: #dcfce7; }
.led-blue:not(.active) { background: #dbeafe; }
.led-yellow:not(.active) { background: #fef3c7; }

.led-status {
    color: #64748b;
    font-weight: 500;
}

.led-controls {
    margin-bottom: 1.5rem;
}

.color-controls {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 0.75rem;
    margin-bottom: 1rem;
}

.color-btn {
    padding: 0.75rem;
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    background: white;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    font-size: 0.875rem;
    font-weight: 500;
}

.color-btn.red { color: #dc2626; }
.color-btn.green { color: #16a34a; }
.color-btn.blue { color: #1d4ed8; }
.color-btn.yellow { color: #d97706; }

.color-btn.active {
    transform: scale(1.05);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.color-btn.red.active { background: #fee2e2; border-color: #dc2626; }
.color-btn.green.active { background: #dcfce7; border-color: #16a34a; }
.color-btn.blue.active { background: #dbeafe; border-color: #1d4ed8; }
.color-btn.yellow.active { background: #fef3c7; border-color: #d97706; }

.pattern-controls {
    display: flex;
    gap: 0.5rem;
}

.pattern-btn {
    flex: 1;
    padding: 0.5rem;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    background: white;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.75rem;
    font-weight: 500;
    color: #64748b;
}

.pattern-btn:hover {
    background: #8b5cf6;
    color: white;
}

/* Moteurs */
.motor-visual {
    display: flex;
    align-items: center;
    gap: 2rem;
    margin-bottom: 1.5rem;
    padding: 2rem;
    background: #f8fafc;
    border-radius: 15px;
}

.motor-icon {
    font-size: 4rem;
    color: #22c55e;
    transition: transform 0.3s ease;
}

.motor-icon.rotating {
    animation: rotate 2s linear infinite;
}

@keyframes rotate {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.motor-stats {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.stat {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem;
    background: white;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
}

.stat .label {
    color: #64748b;
    font-weight: 500;
    font-size: 0.875rem;
}

.stat .value {
    color: #1e293b;
    font-weight: 600;
    font-size: 0.875rem;
}

.speed-control {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: #f8fafc;
    border-radius: 10px;
    margin-top: 1rem;
}

.speed-control label {
    color: #64748b;
    font-weight: 500;
    font-size: 0.875rem;
}

.speed-control span {
    color: #1e293b;
    font-weight: 600;
    font-size: 0.875rem;
    min-width: 70px;
}

/* OLED */
.oled-preview {
    text-align: center;
    margin-bottom: 1.5rem;
}

.oled-screen {
    background: #000;
    border: 3px solid #333;
    border-radius: 10px;
    padding: 1rem;
    margin: 0 auto 1rem;
    width: 200px;
    height: 120px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.oled-content {
    color: #00ff00;
    font-family: 'Courier New', monospace;
    font-size: 0.75rem;
    line-height: 1.2;
    text-align: left;
}

.oled-line {
    margin-bottom: 0.25rem;
}

.oled-info {
    color: #64748b;
    font-size: 0.875rem;
    font-weight: 500;
}

.oled-controls {
    margin-bottom: 1.5rem;
}

.text-input {
    margin-bottom: 1rem;
}

.text-input label {
    display: block;
    color: #64748b;
    font-weight: 500;
    font-size: 0.875rem;
    margin-bottom: 0.5rem;
}

.text-input textarea {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    font-family: 'Courier New', monospace;
    font-size: 0.875rem;
    resize: vertical;
}

.display-options {
    display: flex;
    gap: 1rem;
}

.option-btn {
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

.option-btn:hover {
    background: #06b6d4;
    color: white;
    transform: translateY(-2px);
}

/* Informations actionneur */
.actuator-info {
    margin-top: 1.5rem;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
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
    color: #f59e0b;
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
    background: linear-gradient(135deg, #f59e0b, #d97706);
    color: white;
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(245, 158, 11, 0.3);
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
    flex: 1;
}

/* Thumb pour WebKit (Chrome, Safari, Edge) */
input[type="range"]::-webkit-slider-thumb {
    -webkit-appearance: none;
    appearance: none;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: #f59e0b;
    cursor: pointer;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
    border: none;
}

/* Thumb pour Firefox */
input[type="range"]::-moz-range-thumb {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: #f59e0b;
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

/* Couleurs spécifiques pour différents sliders */
.brightness-control input[type="range"]::-webkit-slider-thumb {
    background: #3b82f6;
}

.brightness-control input[type="range"]::-moz-range-thumb {
    background: #3b82f6;
}

.speed-control input[type="range"]::-webkit-slider-thumb {
    background: #22c55e;
}

.speed-control input[type="range"]::-moz-range-thumb {
    background: #22c55e;
}

/* Focus states */
input[type="range"]:focus::-webkit-slider-thumb {
    box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.3);
}

input[type="range"]:focus::-moz-range-thumb {
    box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.3);
}

.brightness-control input[type="range"]:focus::-webkit-slider-thumb {
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3);
}

.brightness-control input[type="range"]:focus::-moz-range-thumb {
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3);
}

.speed-control input[type="range"]:focus::-webkit-slider-thumb {
    box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.3);
}

.speed-control input[type="range"]:focus::-moz-range-thumb {
    box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.3);
}

/* Responsive */
@media (max-width: 1200px) {
    .actuators-stats {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .actuators-grid {
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
    
    .actuators-stats {
        grid-template-columns: 1fr;
    }
    
    .actuators-grid {
        grid-template-columns: 1fr;
    }
    
    .color-controls {
        grid-template-columns: 1fr;
    }
    
    .info-grid {
        grid-template-columns: 1fr;
    }
    
    .actions-grid {
        grid-template-columns: 1fr;
    }
    
    .control-buttons {
        flex-direction: column;
    }
    
    .motor-visual {
        flex-direction: column;
        text-align: center;
    }
}
</style>

<script>
// Fonctions pour le buzzer
function toggleBuzzer(buzzerId, button) {
    const soundWaves = document.querySelector('.sound-waves');
    const status = document.querySelector('.buzzer-card .actuator-status');
    
    if (button.innerHTML.includes('Activer')) {
        button.innerHTML = '<i class="fas fa-stop"></i> Désactiver';
        soundWaves.classList.add('active');
        status.innerHTML = '<i class="fas fa-circle"></i> Actif';
        status.className = 'actuator-status status-active';
        console.log('Buzzer ' + buzzerId + ' activé');
    } else {
        button.innerHTML = '<i class="fas fa-play"></i> Activer';
        soundWaves.classList.remove('active');
        status.innerHTML = '<i class="fas fa-circle"></i> Éteint';
        status.className = 'actuator-status status-off';
        console.log('Buzzer ' + buzzerId + ' désactivé');
    }
}

function testBuzzer(buzzerId) {
    const soundWaves = document.querySelector('.sound-waves');
    soundWaves.classList.add('active');
    setTimeout(() => {
        soundWaves.classList.remove('active');
    }, 2000);
    alert('Test du buzzer ' + buzzerId);
}

function setFrequency(buzzerId, value) {
    const freqSpan = document.getElementById(`freq-${buzzerId}`);
    if (freqSpan) {
        freqSpan.textContent = value + ' Hz';
    }
    console.log(`Fréquence ${buzzerId}: ${value} Hz`);
}

// Fonctions pour l'afficheur 7 segments
function updateDisplay(displayId, value) {
    const digits = value.toString().padStart(4, '0').split('');
    for (let i = 0; i < 4; i++) {
        const digit = document.getElementById(`digit${i + 1}`);
        if (digit) {
            digit.textContent = digits[i] || '0';
        }
    }
    console.log(`Afficheur ${displayId}: ${value}`);
}

function setDisplayBrightness(displayId, value) {
    const brightnessSpan = document.getElementById(`brightness-${displayId}`);
    if (brightnessSpan) {
        brightnessSpan.textContent = value + '%';
    }
    console.log(`Luminosité ${displayId}: ${value}%`);
}

// Fonctions pour les LEDs
function toggleLED(ledId, color, button) {
    const ledItem = document.querySelector(`.led-item[data-color="${color}"]`);
    const colorButtons = button.parentElement.querySelectorAll('.color-btn');
    
    // Toggle active state
    if (button.classList.contains('active')) {
        button.classList.remove('active');
        ledItem.classList.remove('active');
    } else {
        button.classList.add('active');
        ledItem.classList.add('active');
    }
    
    // Update status
    const activeCount = document.querySelectorAll('.led-item.active').length;
    const ledStatus = document.querySelector('.led-status');
    ledStatus.textContent = `${activeCount} LEDs actives`;
    
    console.log(`LED ${color} ${button.classList.contains('active') ? 'activée' : 'désactivée'}`);
}

function setPattern(ledId, pattern) {
    alert(`Pattern ${pattern} appliqué aux LEDs ${ledId}`);
}

// Fonctions pour les moteurs
function startMotor(motorId) {
    const motorIcon = document.querySelector(`#speed-${motorId}`).closest('.actuator-card').querySelector('.motor-icon');
    const status = document.querySelector(`#speed-${motorId}`).closest('.actuator-card').querySelector('.actuator-status');
    
    motorIcon.classList.add('rotating');
    status.innerHTML = '<i class="fas fa-circle"></i> En marche';
    status.className = 'actuator-status status-running';
    
    // Update speed display
    const speedValue = document.querySelector(`#speed-display-${motorId}`).textContent;
    document.getElementById(`speed-${motorId}`).textContent = speedValue;
    
    console.log('Moteur ' + motorId + ' démarré');
}

function stopMotor(motorId) {
    const motorIcon = document.querySelector(`#speed-${motorId}`).closest('.actuator-card').querySelector('.motor-icon');
    const status = document.querySelector(`#speed-${motorId}`).closest('.actuator-card').querySelector('.actuator-status');
    
    motorIcon.classList.remove('rotating');
    status.innerHTML = '<i class="fas fa-circle"></i> Arrêté';
    status.className = 'actuator-status status-off';
    
    document.getElementById(`speed-${motorId}`).textContent = '0 RPM';
    
    console.log('Moteur ' + motorId + ' arrêté');
}

function reverseMotor(motorId) {
    alert('Inversion du sens de rotation pour ' + motorId);
}

function setMotorSpeed(motorId, value) {
    const speedSpan = document.getElementById(`speed-display-${motorId}`);
    const speedValue = document.getElementById(`speed-${motorId}`);
    
    if (speedSpan) {
        speedSpan.textContent = value + ' RPM';
    }
    if (speedValue) {
        speedValue.textContent = value + ' RPM';
    }
    
    console.log(`Vitesse ${motorId}: ${value} RPM`);
}

// Fonctions pour l'OLED
function updateOLED(oledId, text) {
    const oledContent = document.querySelector('.oled-content');
    if (oledContent) {
        const lines = text.split('\n').slice(0, 4); // Max 4 lignes
        oledContent.innerHTML = lines.map(line => `<div class="oled-line">${line}</div>`).join('');
    }
    console.log(`OLED ${oledId} mis à jour`);
}

function clearOLED(oledId) {
    const oledContent = document.querySelector('.oled-content');
    if (oledContent) {
        oledContent.innerHTML = '';
    }
    console.log(`OLED ${oledId} effacé`);
}

function invertOLED(oledId) {
    const oledScreen = document.querySelector('.oled-screen');
    if (oledScreen.style.background === 'rgb(0, 255, 0)') {
        oledScreen.style.background = '#000';
        oledScreen.style.color = '#00ff00';
    } else {
        oledScreen.style.background = '#00ff00';
        oledScreen.style.color = '#000';
    }
    console.log(`OLED ${oledId} inversé`);
}

// Actions globales
function addNewActuator() {
    alert('Ajout d\'un nouvel actionneur');
}

function automationMode() {
    alert('Activation du mode automatique');
}

function scheduleActions() {
    alert('Programmation des actions');
}

function exportLogs() {
    alert('Export des logs des actionneurs');
}

// Simulation de mise à jour des données en temps réel
setInterval(() => {
    // Mise à jour des consommations
    const powerElements = document.querySelectorAll('.info-item .value');
    powerElements.forEach(element => {
        if (element.textContent.includes('W')) {
            const baseValue = parseInt(element.textContent);
            const variation = Math.floor(Math.random() * 5) - 2;
            element.textContent = Math.max(0, baseValue + variation) + 'W';
        }
    });
    
    console.log('Mise à jour des données des actionneurs...');
}, 10000);
</script>

<?php require_once ROOT_PATH . '/app/views/partials/footer.php'; ?>