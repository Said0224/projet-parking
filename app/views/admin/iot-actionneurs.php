<?php 
// Vérification que l'utilisateur est admin
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header('Location: ' . BASE_URL . '/login');
    exit;
}

require_once ROOT_PATH . '/app/views/partials/header.php'; 

$leds_actives = 0;
foreach ($data['leds'] as $led) {
    if ($led['etat']) $leds_actives++;
}
$moteurs_actifs = 0;
foreach ($data['motors'] as $motor) {
    if ($motor['etat']) $moteurs_actifs++;
}
$total_actifs = $leds_actives + $moteurs_actifs;
?>

<div class="dashboard-container">
    <div class="dashboard-header">
        <div class="header-content">
            <h1><i class="fas fa-cogs"></i> Gestion des Actionneurs</h1>
            <p>Contrôle et pilotage des équipements du parking intelligent</p>
        </div>
    </div>
    <div class="dashboard-nav">
        <div class="nav-tabs">
            <a href="<?= BASE_URL ?>/iot-dashboard" class="nav-tab"><i class="fas fa-arrow-left"></i> Retour IoT</a>
            <a href="<?= BASE_URL ?>/iot-dashboard/capteurs" class="nav-tab"><i class="fas fa-satellite-dish"></i> Capteurs</a>
            <a href="<?= BASE_URL ?>/iot-dashboard/actionneurs" class="nav-tab active"><i class="fas fa-cogs"></i> Actionneurs</a>
        </div>
    </div>

    <!-- Statistiques actionneurs -->
    <div class="actuators-stats">
        <div class="stat-card active-actuators">
            <div class="stat-icon"><i class="fas fa-power-off"></i></div>
            <div class="stat-info"><div class="stat-number"><?= $total_actifs ?></div><div class="stat-label">Actionneurs Actifs</div></div>
        </div>
        <div class="stat-card motors-running">
            <div class="stat-icon"><i class="fas fa-cog"></i></div>
            <div class="stat-info"><div class="stat-number"><?= $moteurs_actifs ?></div><div class="stat-label">Moteurs en Marche</div></div>
        </div>
    </div>

     <!-- Grille des actionneurs -->
    <div class="actuators-grid">
        
        <!-- Boucle dynamique pour les LEDs -->
        <?php foreach($data['leds'] as $led): ?>
        <div class="actuator-card led-card led-card-dynamic" data-led-id="<?= $led['id'] ?>">
            <div class="actuator-header">
                <div class="actuator-title">
                    <i class="fas fa-lightbulb"></i>
                    <h3>LED #<?= htmlspecialchars($led['id']) ?> (Zone: <?= htmlspecialchars($led['zone'] ?? 'N/A') ?>)</h3>
                </div>
                <div class="actuator-status <?= $led['etat'] ? 'status-active' : 'status-off' ?>">
                    <i class="fas fa-circle"></i> <?= $led['etat'] ? 'Active' : 'Éteinte' ?>
                </div>
            </div>
            
            <div class="actuator-body">
                <div class="led-preview">
                    <div class="led-visual" style="background-color: <?= $led['etat'] ? htmlspecialchars($led['couleur']) : '#f1f5f9'; ?>; border-color: <?= $led['etat'] ? htmlspecialchars($led['couleur']) : '#e2e8f0'; ?>; box-shadow: <?= $led['etat'] ? '0 0 20px ' . htmlspecialchars($led['couleur']) : 'none' ?>;"></div>
                    <div class="led-info">
                        <span class="led-state"><?= $led['etat'] ? 'ALLUMÉE' : 'ÉTEINTE' ?></span>
                        <span class="led-brightness">Luminosité: <?= htmlspecialchars($led['intensite']) ?>%</span>
                    </div>
                </div>
                
                <div class="led-controls">
                    <div class="color-controls">
                        <button class="color-btn red <?= $led['couleur'] == '#FF0000' ? 'active' : '' ?>" data-color="#FF0000"><i class="fas fa-circle"></i> Rouge</button>
                        <button class="color-btn green <?= $led['couleur'] == '#00FF00' ? 'active' : '' ?>" data-color="#00FF00"><i class="fas fa-circle"></i> Vert</button>
                        <button class="color-btn off <?= !$led['etat'] ? 'active' : '' ?>"><i class="fas fa-power-off"></i> Éteint</button>
                    </div>
                    
                    <div class="brightness-control">
                        <label>Luminosité:</label>
                        <input type="range" class="brightness-slider" min="0" max="100" value="<?= htmlspecialchars($led['intensite']) ?>">
                        <span class="brightness-display"><?= htmlspecialchars($led['intensite']) ?>%</span>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>

        <!-- Boucle dynamique pour les Moteurs -->
        <?php foreach($data['motors'] as $motor): ?>
        <div class="actuator-card motor-card">
            <?php static $motorCount = 0; ?>
            <?php if ($motorCount < 3): $motorCount++; ?>
                        <div class="actuator-header">
                            <div class="actuator-title"><i class="fas fa-cog"></i><h3>Moteur #<?= htmlspecialchars($motor['id']) ?> (Zone: <?= htmlspecialchars($motor['zone'] ?? 'N/A') ?>)</h3></div>
                            <div class="actuator-status <?= $motor['etat'] ? 'status-running' : 'status-off' ?>"><i class="fas fa-circle"></i> <?= $motor['etat'] ? 'En marche' : 'Arrêté' ?></div>
                        </div>
            <?php else: continue; endif; ?>
            <div class="actuator-body">
                <div class="motor-visual">
                    <div class="motor-icon <?= $motor['etat'] ? 'rotating' : '' ?>"><i class="fas fa-cog"></i></div>
                    <div class="motor-stats"><div class="stat"><span class="label">Vitesse:</span><span class="value"><?= htmlspecialchars($motor['vitesse']) ?> RPM</span></div></div>
                </div>
                <form class="motor-update-form" data-id="<?= $motor['id'] ?>">
                    <div class="speed-control">
                        <label>Vitesse:</label>
                        <input type="range" class="motor-speed" min="0" max="2000" value="<?= htmlspecialchars($motor['vitesse']) ?>">
                        <span class="speed-display"><?= htmlspecialchars($motor['vitesse']) ?> RPM</span>
                    </div>
                    <div class="control-buttons" style="margin-top: 1rem;">
                        <!-- ================== DÉBUT MODIFICATION PHP/HTML ================== -->
                        <button type="button" class="control-btn start <?= $motor['etat'] ? 'active' : '' ?>">
                            <i class="fas fa-play"></i> Démarrer
                        </button>
                        <button type="button" class="control-btn stop <?= !$motor['etat'] ? 'active' : '' ?>">
                            <i class="fas fa-stop"></i> Arrêter
                        </button>
                        <!-- =================== FIN MODIFICATION PHP/HTML =================== -->
                    </div>
                </form>
            </div>
        </div>
        <?php endforeach; ?>
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

.control-btn.start.active {
    background: #22c55e; /* Vert */
    color: white;
    border-color: #16a34a;
}

.control-btn.stop.active {
    background: #ef4444; /* Rouge */
    color: white;
    border-color: #dc2626;
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
document.addEventListener('DOMContentLoaded', function() {
    // ... (code pour les LEDs inchangé) ...
    document.querySelectorAll('.led-card-dynamic').forEach(card => {
        const ledId = card.dataset.ledId;
        const colorButtons = card.querySelectorAll('.color-btn');
        const brightnessSlider = card.querySelector('.brightness-slider');
        
        const updateLedOnServer = () => {
            const activeButton = card.querySelector('.color-btn.active');
            let is_on = true;
            let color = '#FFFFFF';

            if (activeButton && activeButton.classList.contains('off')) {
                is_on = false;
            } else if (activeButton) {
                color = activeButton.dataset.color;
            }
            
            const intensity = brightnessSlider.value;
            const formData = new FormData();
            formData.append('id', ledId);
            formData.append('etat', is_on ? 1 : 0);
            formData.append('couleur', color);
            formData.append('intensite', intensity);
            
            fetch('<?= BASE_URL ?>/iot-dashboard/update-led-details', { method: 'POST', body: formData })
                .then(res => res.json())
                .then(data => {
                    if (!data.success) alert("Erreur de mise à jour.");
                }).catch(err => alert("Erreur de connexion."));
        };

        colorButtons.forEach(button => {
            button.addEventListener('click', function() {
                colorButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                updateLedOnServer();
            });
        });

        brightnessSlider.addEventListener('change', updateLedOnServer);
        brightnessSlider.addEventListener('input', () => {
            card.querySelector('.brightness-display').textContent = `${brightnessSlider.value}%`;
        });
    });

    // ================== DÉBUT MODIFICATION JAVASCRIPT ==================
    document.querySelectorAll('.motor-update-form').forEach(form => {
        const motorId = form.dataset.id;
        const startBtn = form.querySelector('.start');
        const stopBtn = form.querySelector('.stop');
        const speedSlider = form.querySelector('.motor-speed');

        const updateMotor = (etat, vitesse, formElement) => {
            const formData = new FormData();
            formData.append('id', motorId);
            formData.append('etat', etat ? 1 : 0);
            formData.append('vitesse', vitesse);

            fetch('<?= BASE_URL ?>/iot-dashboard/update-motor-state', { method: 'POST', body: formData })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    // Mettre à jour l'interface utilisateur
                    const card = formElement.closest('.actuator-card');
                    const motorIcon = card.querySelector('.motor-icon');
                    const statusBadge = card.querySelector('.actuator-status');
                    const startButton = formElement.querySelector('.start');
                    const stopButton = formElement.querySelector('.stop');

                    if (etat) { // Si le moteur démarre
                        motorIcon.classList.add('rotating');
                        statusBadge.innerHTML = '<i class="fas fa-circle"></i> En marche';
                        statusBadge.className = 'actuator-status status-running';
                        startButton.classList.add('active');
                        stopButton.classList.remove('active');
                    } else { // Si le moteur s'arrête
                        motorIcon.classList.remove('rotating');
                        statusBadge.innerHTML = '<i class="fas fa-circle"></i> Arrêté';
                        statusBadge.className = 'actuator-status status-off';
                        startButton.classList.remove('active');
                        stopButton.classList.add('active');
                    }
                } else {
                    alert('Erreur de mise à jour du moteur.');
                }
            });
        };
        
        speedSlider.addEventListener('input', () => {
             form.querySelector('.speed-display').textContent = `${speedSlider.value} RPM`;
        });
        
        speedSlider.addEventListener('change', () => {
            const isRunning = form.closest('.actuator-card').querySelector('.motor-icon').classList.contains('rotating');
            updateMotor(isRunning, speedSlider.value, form);
        });

        startBtn.addEventListener('click', () => updateMotor(true, speedSlider.value, form));
        stopBtn.addEventListener('click', () => updateMotor(false, speedSlider.value, form));
    });
    // =================== FIN MODIFICATION JAVASCRIPT ===================
});
</script>

<?php require_once ROOT_PATH . '/app/views/partials/footer.php'; ?>