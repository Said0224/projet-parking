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
            <a href="<?= BASE_URL ?>/admin" class="nav-tab">
                <i class="fas fa-tachometer-alt"></i> Admin
            </a>
            <a href="<?= BASE_URL ?>/iot-dashboard" class="nav-tab active">
                <i class="fas fa-microchip"></i> IoT Dashboard
            </a>
            <a href="<?= BASE_URL ?>/admin/users" class="nav-tab">
                <i class="fas fa-users"></i> Utilisateurs
            </a>
            <a href="<?= BASE_URL ?>/logout" class="nav-tab">
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

    <!-- Navigation IoT -->
    <div class="iot-navigation">
        <div class="iot-nav-header">
            <h2><i class="fas fa-microchip"></i> Système IoT</h2>
            <p>Gestion des capteurs et actionneurs du parking intelligent</p>
        </div>
        
        <div class="iot-nav-cards">
            <a href="<?= BASE_URL ?>/iot-dashboard/capteurs" class="iot-nav-card capteurs-card">
                <div class="nav-card-icon">
                    <i class="fas fa-satellite-dish"></i>
                </div>
                <div class="nav-card-content">
                    <h3>Capteurs</h3>
                    <p>Surveillance et monitoring des capteurs de détection</p>
                    <div class="nav-card-stats">
                        <span class="stat-item">
                            <i class="fas fa-eye"></i> 3 Détecteurs
                        </span>
                        <span class="stat-item">
                            <i class="fas fa-thermometer-half"></i> 1 Température
                        </span>
                    </div>
                </div>
                <div class="nav-card-arrow">
                    <i class="fas fa-chevron-right"></i>
                </div>
            </a>
            
             <a href="<?= BASE_URL ?>/iot-dashboard/actionneurs" class="iot-nav-card actionneurs-card">
                <div class="nav-card-icon">
                    <i class="fas fa-cogs"></i>
                </div>
                <div class="nav-card-content">
                    <h3>Actionneurs</h3>
                    <p>Contrôle des LEDs et systèmes de signalisation</p>
                    <div class="nav-card-stats">
                        <span class="stat-item">
                            <i class="fas fa-lightbulb"></i> 2 LEDs
                        </span>
                        <span class="stat-item">
                            <i class="fas fa-charging-station"></i> 1 Borne
                        </span>
                    </div>
                </div>
                <div class="nav-card-arrow">
                    <i class="fas fa-chevron-right"></i>
                </div>
            </a>
        </div>
    </div>

    <!-- Section Affichage OLED -->
    <div class="oled-main-section">
        <div class="content-section oled-section">
            <div class="section-header">
                <h3><i class="fas fa-tv"></i> Affichage OLED Principal</h3>
                <button class="btn-add" onclick="configOLED()">
                    <i class="fas fa-cog"></i> Configurer
                </button>
            </div>
            <div class="section-content">
                <div class="oled-main-content">
                    <div class="oled-info-grid">
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
                            <span class="info-label">Température:</span>
                            <span class="info-value">22°C</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Dernière mise à jour:</span>
                            <span class="info-value"><?= date('d/m/Y H:i:s') ?></span>
                        </div>
                    </div>
                    
                    <div class="oled-preview-container">
                        <div class="oled-preview">
                            <div class="oled-screen">
                                <div class="oled-content">
                                    <h4>PARKING ISEP</h4>
                                    <div class="oled-line">Places: 8/10</div>
                                    <div class="oled-line">Bornes: 1 libre</div>
                                    <div class="oled-line">Tarif: 6€/h</div>
                                    <div class="oled-line">Recharge: 3€</div>
                                    <div class="oled-line">Temp: 22°C</div>
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
        </div>
    </div>

    <!-- Statut système -->
    <div class="system-status">
        <div class="status-header">
            <h3><i class="fas fa-heartbeat"></i> Statut du Système</h3>
        </div>
        <div class="status-grid">
            <div class="status-item">
                <div class="status-icon status-online">
                    <i class="fas fa-wifi"></i>
                </div>
                <div class="status-info">
                    <h4>Connexion IoT</h4>
                    <span class="status-text">En ligne</span>
                </div>
            </div>
            
            <div class="status-item">
                <div class="status-icon status-online">
                    <i class="fas fa-database"></i>
                </div>
                <div class="status-info">
                    <h4>Base de données</h4>
                    <span class="status-text">Connectée</span>
                </div>
            </div>
            
            <div class="status-item">
                <div class="status-icon status-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="status-info">
                    <h4>Maintenance</h4>
                    <span class="status-text">1 capteur</span>
                </div>
            </div>
            
            <div class="status-item">
                <div class="status-icon status-online">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <div class="status-info">
                    <h4>Sécurité</h4>
                    <span class="status-text">Sécurisé</span>
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
    margin-bottom: 3rem;
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

/* Navigation IoT */
.iot-navigation {
    margin-bottom: 3rem;
}

.iot-nav-header {
    text-align: center;
    margin-bottom: 2rem;
}

.iot-nav-header h2 {
    color: white;
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
}

.iot-nav-header h2 i {
    color: #ffd700;
    margin-right: 1rem;
}

.iot-nav-header p {
    color: rgba(255, 255, 255, 0.8);
    font-size: 1rem;
}

.iot-nav-cards {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
}

.iot-nav-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    padding: 2rem;
    display: flex;
    align-items: center;
    gap: 1.5rem;
    text-decoration: none;
    color: inherit;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.iot-nav-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.5s;
}

.iot-nav-card:hover::before {
    left: 100%;
}

.iot-nav-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
}

.capteurs-card:hover {
    border-left: 4px solid #22c55e;
}

.actionneurs-card:hover {
    border-left: 4px solid #f59e0b;
}

.nav-card-icon {
    width: 80px;
    height: 80px;
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: white;
    flex-shrink: 0;
}

.capteurs-card .nav-card-icon {
    background: linear-gradient(135deg, #22c55e, #16a34a);
}

.actionneurs-card .nav-card-icon {
    background: linear-gradient(135deg, #f59e0b, #d97706);
}

.nav-card-content {
    flex: 1;
}

.nav-card-content h3 {
    margin: 0 0 0.5rem 0;
    color: #1e293b;
    font-size: 1.5rem;
    font-weight: 700;
}

.nav-card-content p {
    color: #64748b;
    margin: 0 0 1rem 0;
    line-height: 1.5;
}

.nav-card-stats {
    display: flex;
    gap: 1rem;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #64748b;
    font-size: 0.875rem;
    font-weight: 500;
}

.stat-item i {
    color: #3b82f6;
}

.nav-card-arrow {
    color: #64748b;
    font-size: 1.5rem;
    transition: all 0.3s ease;
}

.iot-nav-card:hover .nav-card-arrow {
    color: #3b82f6;
    transform: translateX(5px);
}

/* Section OLED principale */
.oled-main-section {
    margin-bottom: 3rem;
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
    font-size: 1.25rem;
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
    padding: 2rem;
}

.oled-main-content {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
    align-items: start;
}

.oled-info-grid {
    display: grid;
    gap: 1rem;
}

.info-item {
    display: flex;
    justify-content: space-between;
    padding: 1rem;
    background: #f8fafc;
    border-radius: 10px;
    border-left: 4px solid #3b82f6;
}

.info-label {
    color: #64748b;
    font-weight: 500;
}

.info-value {
    color: #1e293b;
    font-weight: 600;
}

.oled-preview-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1.5rem;
}

.oled-preview {
    display: flex;
    justify-content: center;
}

.oled-screen {
    background: #1a1a1a;
    border-radius: 15px;
    padding: 1.5rem;
    width: 250px;
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

.oled-controls {
    display: flex;
    gap: 1rem;
}

.btn-control {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    color: #64748b;
    padding: 0.75rem 1.5rem;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.875rem;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-control:hover {
    background: #3b82f6;
    color: white;
    transform: translateY(-2px);
}

/* Statut système */
.system-status {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.status-header h3 {
    color: #1e293b;
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.status-header i {
    color: #3b82f6;
}

.status-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1.5rem;
}

.status-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: #f8fafc;
    border-radius: 10px;
    transition: all 0.3s ease;
}

.status-item:hover {
    background: #f1f5f9;
    transform: translateY(-2px);
}

.status-icon {
    width: 50px;
    height: 50px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    color: white;
}

.status-online {
    background: linear-gradient(135deg, #22c55e, #16a34a);
}

.status-warning {
    background: linear-gradient(135deg, #f59e0b, #d97706);
}

.status-info h4 {
    margin: 0 0 0.25rem 0;
    color: #1e293b;
    font-size: 0.875rem;
    font-weight: 600;
}

.status-text {
    color: #64748b;
    font-size: 0.75rem;
}

/* Responsive */
@media (max-width: 1200px) {
    .iot-nav-cards {
        grid-template-columns: 1fr;
    }
    
    .oled-main-content {
        grid-template-columns: 1fr;
    }
    
    .status-grid {
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
    
    .iot-nav-card {
        flex-direction: column;
        text-align: center;
    }
    
    .status-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
function updateOLED() {
    // Simuler une mise à jour
    const timeElement = document.querySelector('.oled-time');
    if (timeElement) {
        timeElement.textContent = new Date().toLocaleTimeString();
    }
    
    // Animation de mise à jour
    const screen = document.querySelector('.oled-screen');
    screen.style.boxShadow = '0 0 50px rgba(0, 255, 0, 0.8)';
    setTimeout(() => {
        screen.style.boxShadow = '0 0 30px rgba(0, 255, 0, 0.3)';
    }, 1000);
    
    console.log('OLED mis à jour');
}

function configOLED() {
    alert('Configuration de l\'affichage OLED');
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
    console.log('Mise à jour des données IoT...');
}, 10000);
</script>

<?php require_once ROOT_PATH . '/app/views/partials/footer.php'; ?>