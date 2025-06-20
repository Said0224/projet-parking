<?php 
require_once ROOT_PATH . '/app/views/partials/header.php'; 
?>

<!-- Conteneur pour les notifications AJAX -->
<div id="ajax-notification" class="notification-container"></div>

<div class="dashboard-container">
    <div class="dashboard-header">
        <div class="header-content">
            <h1><i class="fas fa-cogs"></i> Gestion des Actionneurs IoT</h1>
            <p>Contrôle et pilotage des équipements du système IoT</p>
        </div>
    </div>

    <div class="dashboard-nav">
        <div class="nav-tabs">
            <a href="<?= BASE_URL ?>/iot-dashboard" class="nav-tab"><i class="fas fa-arrow-left"></i> Retour IoT</a>
            <a href="<?= BASE_URL ?>/iot-dashboard/capteurs" class="nav-tab"><i class="fas fa-satellite-dish"></i> Capteurs</a>
            <a href="<?= BASE_URL ?>/iot-dashboard/actionneurs" class="nav-tab active"><i class="fas fa-cogs"></i> Actionneurs</a>
            <a href="<?= BASE_URL ?>/logout" class="nav-tab"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
        </div>
        <div class="user-info">
            <i class="fas fa-user"></i> <?= htmlspecialchars($_SESSION['user_email'] ?? 'admin@isep.fr') ?>
        </div>
    </div>

    <div class="actuators-grid">
        <!-- Buzzer (Géré côté client) -->
        <div class="actuator-card buzzer-card" data-actuator-id="BUZZ_001">
            <div class="actuator-header">
                <div class="actuator-title"><i class="fas fa-volume-up"></i><h3>Buzzer d'Alerte</h3></div>
                <div class="actuator-status status-off"><i class="fas fa-circle"></i> Éteint</div>
            </div>
            <div class="actuator-body">
                <div class="buzzer-controls">
                    <div class="sound-visual"><div class="buzzer-icon"><i class="fas fa-volume-up"></i></div><div class="sound-waves"></div></div>
                    <div class="control-buttons">
                        <button class="control-btn" data-action="toggle-buzzer"><i class="fas fa-play"></i> Activer</button>
                        <button class="control-btn" data-action="test-buzzer"><i class="fas fa-music"></i> Test</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Afficheur 7 segments (Géré côté client) -->
        <div class="actuator-card display-card" data-actuator-id="DISP_001">
            <div class="actuator-header">
                <div class="actuator-title"><i class="fas fa-digital-tachograph"></i><h3>Afficheur 7 Segments</h3></div>
                <div class="actuator-status status-active"><i class="fas fa-circle"></i> Actif</div>
            </div>
            <div class="actuator-body">
                <div class="display-preview">
                    <div class="seven-segment-display">
                        <div class="digit">8</div><div class="digit">8</div><div class="digit">.</div><div class="digit">8</div>
                    </div>
                </div>
                <div class="display-controls">
                    <div class="input-group">
                        <label>Valeur à afficher:</label>
                        <input type="number" step="0.1" value="88.8" data-action="update-display">
                    </div>
                </div>
            </div>
        </div>

        <!-- LEDs (Dynamiques) -->
        <?php foreach ($data['leds'] as $led): ?>
        <div class="actuator-card led-card" data-actuator-id="<?= $led['id'] ?>">
            <div class="actuator-header">
                <div class="actuator-title"><i class="fas fa-lightbulb"></i><h3>LED #<?= $led['id'] ?> (Zone: <?= htmlspecialchars($led['zone']) ?>)</h3></div>
                <div class="actuator-status <?= $led['etat'] ? 'status-active' : 'status-off' ?>"><i class="fas fa-circle"></i> <?= $led['etat'] ? 'Active' : 'Éteinte' ?></div>
            </div>
            <div class="actuator-body">
                <p>Contrôler l'état de la LED.</p>
                <!-- ===== DÉBUT DE LA MODIFICATION ===== -->
                <div class="form-switch">
                    <input class="form-check-input" type="checkbox" id="led-switch-<?= $led['id'] ?>" data-action="toggle-led" <?= $led['etat'] ? 'checked' : '' ?>>
                    <label class="form-check-label" for="led-switch-<?= $led['id'] ?>"></label>
                </div>
                <!-- ===== FIN DE LA MODIFICATION ===== -->
            </div>
        </div>
        <?php endforeach; ?>

        <!-- Moteurs (Dynamiques) -->
        <?php foreach ($data['motors'] as $motor): ?>
        <div class="actuator-card motor-card" data-actuator-id="<?= $motor['id'] ?>">
            <div class="actuator-header">
                <div class="actuator-title"><i class="fas fa-cog"></i><h3>Moteur #<?= $motor['id'] ?> (Zone: <?= htmlspecialchars($motor['zone']) ?>)</h3></div>
                <div class="actuator-status <?= $motor['etat'] ? 'status-running' : 'status-off' ?>"><i class="fas fa-circle"></i> <?= $motor['etat'] ? 'En marche' : 'Arrêté' ?></div>
            </div>
            <div class="actuator-body">
                <div class="motor-visual">
                    <div class="motor-icon <?= $motor['etat'] ? 'rotating' : '' ?>"><i class="fas fa-cog"></i></div>
                    <div class="motor-stats">
                        <div class="stat"><span class="label">Vitesse:</span><span class="value speed-value"><?= $motor['vitesse'] ?> RPM</span></div>
                    </div>
                </div>
                <div class="motor-controls">
                    <div class="control-buttons">
                        <button class="control-btn start" data-action="start-motor"><i class="fas fa-play"></i></button>
                        <button class="control-btn stop" data-action="stop-motor"><i class="fas fa-stop"></i></button>
                    </div>
                    <div class="speed-control">
                        <label>Vitesse:</label>
                        <input type="range" min="0" max="2000" value="<?= $motor['vitesse'] ?>" data-action="set-motor-speed">
                        <span class="speed-display"><?= $motor['vitesse'] ?> RPM</span>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<style>
/* Base et conteneur */
.dashboard-container { max-width: 1400px; margin: 0 auto; padding: 2rem; }
.dashboard-header { text-align: center; margin-bottom: 2rem; }
.header-content h1 { color: white; font-size: 2.5rem; font-weight: 700; margin-bottom: 0.5rem; text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3); }
.header-content h1 i { color: #ffd700; margin-right: 1rem; }
.header-content p { color: rgba(255, 255, 255, 0.9); font-size: 1.1rem; margin: 0; }
.dashboard-nav { background: rgba(255, 255, 255, 0.15); backdrop-filter: blur(15px); border-radius: 15px; padding: 1rem 2rem; margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center; border: 1px solid rgba(255, 255, 255, 0.2); }
.nav-tabs { display: flex; gap: 1rem; }
.nav-tab { color: rgba(255, 255, 255, 0.8); text-decoration: none; padding: 0.75rem 1.5rem; border-radius: 10px; transition: all 0.3s ease; font-weight: 500; display: flex; align-items: center; gap: 0.5rem; }
.nav-tab:hover, .nav-tab.active { background: rgba(255, 255, 255, 0.2); color: white; transform: translateY(-2px); }
.user-info { color: white; font-weight: 500; display: flex; align-items: center; gap: 0.5rem; }

/* Grille des actionneurs */
.actuators-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 2rem; margin-bottom: 3rem; }
.actuator-card { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); border-radius: 20px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1); border: 1px solid rgba(255, 255, 255, 0.2); overflow: hidden; transition: all 0.3s ease; }
.actuator-card:hover { transform: translateY(-5px); box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15); }
.buzzer-card { border-left: 4px solid #f59e0b; }
.display-card { border-left: 4px solid #3b82f6; }
.led-card { border-left: 4px solid #8b5cf6; }
.motor-card { border-left: 4px solid #22c55e; }
.actuator-header { background: linear-gradient(135deg, rgba(30, 64, 175, 0.1), rgba(59, 130, 246, 0.1)); padding: 1.5rem; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid rgba(59, 130, 246, 0.1); }
.actuator-title { display: flex; align-items: center; gap: 0.75rem; }
.actuator-title i { font-size: 1.25rem; }
.led-card .actuator-title i { color: #8b5cf6; }
.motor-card .actuator-title i { color: #22c55e; }
.buzzer-card .actuator-title i { color: #f59e0b; }
.display-card .actuator-title i { color: #3b82f6; }
.actuator-title h3 { margin: 0; color: #1e293b; font-size: 1.125rem; font-weight: 600; }
.actuator-status { padding: 0.5rem 1rem; border-radius: 25px; font-size: 0.875rem; font-weight: 600; display: flex; align-items: center; gap: 0.5rem; }
.status-active, .status-running { background: #dcfce7; color: #16a34a; }
.status-off { background: #f1f5f9; color: #64748b; }
.actuator-body { padding: 1.5rem; }

/* Styles pour les contrôles (Buzzer, Moteur, etc.) */
.buzzer-controls, .motor-controls, .display-controls { display: flex; flex-direction: column; gap: 1rem; }
.sound-visual, .motor-visual, .display-preview { text-align: center; padding: 1.5rem; background: #f8fafc; border-radius: 15px; }
.buzzer-icon, .motor-icon { font-size: 3rem; }
.motor-icon.rotating { animation: rotate 2s linear infinite; }
@keyframes rotate { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
.control-buttons { display: flex; gap: 1rem; }
.control-btn { flex: 1; background: #f8fafc; border: 1px solid #e2e8f0; color: #64748b; padding: 0.75rem 1rem; border-radius: 10px; cursor: pointer; transition: all 0.3s ease; font-size: 0.875rem; font-weight: 500; display: flex; align-items: center; justify-content: center; gap: 0.5rem; }
.control-btn:hover { background: #64748b; color: white; transform: translateY(-2px); }
.control-btn.start:hover { background-color: #22c55e; }
.control-btn.stop:hover { background-color: #ef4444; }
.speed-control { display: flex; align-items: center; gap: 1rem; padding: 1rem; background: #f8fafc; border-radius: 10px; }
.speed-control label { color: #64748b; font-weight: 500; font-size: 0.875rem; }
.speed-control span { color: #1e293b; font-weight: 600; font-size: 0.875rem; min-width: 70px; }
input[type="range"] { flex: 1; -webkit-appearance: none; appearance: none; height: 6px; border-radius: 3px; background: #e2e8f0; outline: none; }
input[type="range"]::-webkit-slider-thumb { -webkit-appearance: none; appearance: none; width: 20px; height: 20px; border-radius: 50%; background: #22c55e; cursor: pointer; }
input[type="range"]::-moz-range-thumb { width: 20px; height: 20px; border-radius: 50%; background: #22c55e; cursor: pointer; border: none; }
.seven-segment-display { display: flex; justify-content: center; background: #1a1a1a; padding: 1rem; border-radius: 10px; }
.digit { font-family: 'Courier New', monospace; font-size: 2.5rem; color: #ff0000; text-shadow: 0 0 10px #ff0000; }
.input-group { display: flex; flex-direction: column; gap: 0.5rem; }
.input-group label { color: #64748b; font-weight: 500; font-size: 0.875rem; }
.input-group input { padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 1rem; }

/* Responsive */
@media (max-width: 768px) {
    .dashboard-nav { flex-direction: column; gap: 1rem; text-align: center; }
    .nav-tabs { flex-wrap: wrap; justify-content: center; }
    .actuators-grid { grid-template-columns: 1fr; }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.querySelector('.actuators-grid');

    // Fonction générique pour envoyer des requêtes AJAX
    async function sendRequest(url, formData) {
        try {
            const response = await fetch(url, { method: 'POST', body: formData });
            if (!response.ok) {
                const errorData = await response.json().catch(() => ({ message: 'Erreur HTTP ' + response.status }));
                throw new Error(errorData.message || 'Réponse non valide du serveur.');
            }
            const data = await response.json();
            showNotification(data.message, data.success ? 'success' : 'danger');
            return data.success;
        } catch (error) {
            showNotification(error.message || 'Erreur de connexion.', 'danger');
            return false;
        }
    }
    
    // --- GESTION DES CLICS SUR LES BOUTONS (MOTEURS) ---
    container.addEventListener('click', (e) => {
        const target = e.target.closest('[data-action]');
        if (!target) return;

        const action = target.dataset.action;
        const card = target.closest('.actuator-card');
        const id = card.dataset.actuatorId;
        
        if (action === 'start-motor' || action === 'stop-motor') {
            const isStarting = action === 'start-motor';
            const speedInput = card.querySelector('input[type="range"]');
            const formData = new FormData();
            formData.append('id', id);
            formData.append('etat', isStarting ? '1' : '0');
            formData.append('vitesse', isStarting ? speedInput.value : '0');

            sendRequest('<?= BASE_URL ?>/iot-dashboard/update-motor-state', formData)
                .then(success => {
                    if (success) {
                        updateMotorUI(card, isStarting, speedInput.value);
                    }
                });
        }
    });

    // --- GESTION DES CHANGEMENTS (LEDS, SLIDERS) ---
    container.addEventListener('change', (e) => {
        const target = e.target;
        const action = target.dataset.action;
        if (!action) return;

        const card = target.closest('.actuator-card');
        const id = card.dataset.actuatorId;

        // --- GESTION DES LEDS ---
        if (action === 'toggle-led') {
            const formData = new FormData();
            formData.append('id', id);
            formData.append('etat', target.checked ? '1' : '0');
            sendRequest('<?= BASE_URL ?>/iot-dashboard/update-led-state', formData)
                .then(success => {
                    if (success) {
                        updateLedUI(card, target.checked);
                    } else {
                        target.checked = !target.checked; // Revert on failure
                    }
                });
        }
        
        // --- GESTION DES SLIDERS MOTEUR (AU RELÂCHEMENT) ---
        if (action === 'set-motor-speed') {
            const speedDisplay = card.querySelector('.speed-display');
            if(speedDisplay) speedDisplay.textContent = `${target.value} RPM`;
            
            const icon = card.querySelector('.motor-icon');
            // On met à jour la vitesse uniquement si le moteur est déjà en marche
            if (icon.classList.contains('rotating')) {
                const formData = new FormData();
                formData.append('id', id);
                formData.append('etat', '1'); // Le moteur est en marche
                formData.append('vitesse', target.value);
                sendRequest('<?= BASE_URL ?>/iot-dashboard/update-motor-state', formData)
                    .then(success => {
                        if (success) {
                            const speedValue = card.querySelector('.speed-value');
                            if (speedValue) speedValue.textContent = `${target.value} RPM`;
                        }
                    });
            }
        }
    });

    // --- Fonctions de mise à jour de l'interface ---
    function updateLedUI(card, isChecked) {
        const statusDiv = card.querySelector('.actuator-status');
        if (isChecked) {
            statusDiv.className = 'actuator-status status-active';
            statusDiv.innerHTML = '<i class="fas fa-circle"></i> Active';
        } else {
            statusDiv.className = 'actuator-status status-off';
            statusDiv.innerHTML = '<i class="fas fa-circle"></i> Éteinte';
        }
    }
    
    function updateMotorUI(card, isRunning, speed) {
        const statusDiv = card.querySelector('.actuator-status');
        const icon = card.querySelector('.motor-icon');
        const speedValue = card.querySelector('.speed-value');

        if (isRunning) {
            statusDiv.className = 'actuator-status status-running';
            statusDiv.innerHTML = '<i class="fas fa-circle"></i> En marche';
            icon.classList.add('rotating');
            speedValue.textContent = `${speed} RPM`;
        } else {
            statusDiv.className = 'actuator-status status-off';
            statusDiv.innerHTML = '<i class="fas fa-circle"></i> Arrêté';
            icon.classList.remove('rotating');
            speedValue.textContent = '0 RPM';
        }
    }
});

// Fonction de notification (générique)
function showNotification(message, type = 'success') {
    const container = document.getElementById('ajax-notification');
    if (!container) return;
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.textContent = message;
    container.appendChild(notification);
    setTimeout(() => notification.classList.add('show'), 10);
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => { if (notification.parentNode) notification.parentNode.removeChild(notification); }, 500);
    }, 4000);
}
</script>

<?php require_once ROOT_PATH . '/app/views/partials/footer.php'; ?>