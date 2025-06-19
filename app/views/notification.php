<?php 
require_once ROOT_PATH . '/app/views/partials/header.php'; 
?>

<div class="container notifications-page-container">
    
    <!-- En-tête de la page -->
    <div class="page-header">
        <h1><i class="fas fa-bell"></i> Mes Notifications</h1>
    </div>

    <!-- Section des Préférences -->
    <div class="notif-section">
        <h2><i class="fas fa-cog"></i> Préférences</h2>
        <div class="preference-card">
            <div class="preference-info">
                <i class="fas fa-envelope-open-text"></i>
                <span>Recevoir les notifications importantes par e-mail</span>
            </div>
            <div class="form-switch">
                <input type="checkbox" id="email-notifications" class="form-check-input" checked>
                <label for="email-notifications" class="form-check-label"></label>
            </div>
        </div>
    </div>

    <!-- Section de l'Historique -->
    <div class="notif-section">
        <h2><i class="fas fa-history"></i> Historique</h2>
        <div class="notifications-list">
            <div class="notification-item">
                <div class="notification-icon">
                    <i class="fas fa-user-plus"></i>
                </div>
                <div class="notification-content">
                    <p class="notification-message">Bienvenue ! Votre compte a été créé avec succès.</p>
                    <span class="notification-date">19/06/2025 à 14:14</span>
                </div>
            </div>
            <!-- Vous pourrez ajouter d'autres notifications ici -->
        </div>
    </div>

</div>

<style>
/* Styles spécifiques pour la page de notifications */
.notifications-page-container {
    max-width: 900px;
    margin: 0 auto;
}

.notif-section {
    margin-bottom: 2.5rem;
}

.notif-section h2 {
    color: white;
    font-size: 1.8rem;
    margin-bottom: 1.5rem;
    padding-bottom: 0.75rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.2);
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.notif-section h2 i {
    color: #ffd700;
}

.preference-card {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    padding: 1.5rem 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border: 1px solid rgba(255, 255, 255, 0.2);
    box-shadow: 0 8px 30px rgba(0,0,0,0.1);
    /* AJOUT : Transition pour l'effet de survol */
    transition: all 0.3s ease;
}

/* AJOUT : Règle de survol pour la carte des préférences */
.preference-card:hover {
    transform: translateY(-5px) scale(1.02);
    box-shadow: 0 12px 40px rgba(0,0,0,0.15);
    background: rgba(255, 255, 255, 0.15);
}

.preference-info {
    display: flex;
    align-items: center;
    gap: 1rem;
    color: white;
    font-weight: 500;
}

.preference-info i {
    font-size: 1.5rem;
    opacity: 0.8;
}

.notifications-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.notification-item {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border-radius: 15px;
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1.5rem;
    border: 1px solid rgba(255, 255, 255, 0.2);
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    color: white;
}

/* AJOUT : Règle de survol pour les items de notification */
.notification-item:hover {
    transform: translateY(-5px) scale(1.02);
    background: rgba(255, 255, 255, 0.15);
    box-shadow: 0 8px 25px rgba(0,0,0,0.12);
}

.notification-icon {
    flex-shrink: 0;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: linear-gradient(145deg, rgba(255, 255, 255, 0.2), rgba(255, 255, 255, 0.1));
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    color: white;
}

.notification-message {
    margin: 0 0 0.25rem 0;
    font-weight: 500;
}

.notification-date {
    font-size: 0.875rem;
    opacity: 0.7;
}
</style>

<?php require_once ROOT_PATH . '/app/views/partials/footer.php'; ?>