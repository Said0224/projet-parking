<?php 
// Fichier : app/views/user/notifications.php
require_once ROOT_PATH . '/app/views/partials/header.php'; 
?>

<div class="container">
    <div class="page-header">
        <h1><i class="fas fa-bell"></i> Mes Notifications</h1>
    </div>

    <!-- Section des Préférences -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-cog"></i> Préférences</h3>
        </div>
        <div class="card-body">
            <div class="preference-item">
                <div class="preference-info">
                    <i class="fas fa-envelope-open-text"></i>
                    <span>Recevoir les notifications importantes par e-mail</span>
                </div>
                <!-- ===================================== -->
                <!-- NOUVELLE STRUCTURE POUR LE TOGGLE SWITCH -->
                <!-- ===================================== -->
                <div class="form-switch">
                    <input type="checkbox" id="email-notifications" class="form-check-input" checked>
                    <label for="email-notifications" class="form-check-label"></label>
                </div>
            </div>
        </div>
    </div>

    <!-- Section des Notifications -->
    <h2 class="notifications-title">Historique</h2>
    <div class="notifications-list">
        <!-- Notification Fictive -->
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

<style>
/* Styles pour la mise en page des notifications */
.preference-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem 0;
}

.preference-info {
    display: flex;
    align-items: center;
    gap: 1rem;
    color: #333;
    font-weight: 500;
}

.preference-info i {
    color: #3b82f6;
    font-size: 1.5rem;
    width: 30px;
    text-align: center;
}

.notifications-title {
    color: white;
    font-size: 1.8rem;
    margin-top: 3rem;
    margin-bottom: 1.5rem;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.2);
}

.notifications-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.notification-item {
    background: rgba(255, 255, 255, 0.9);
    border-radius: 15px;
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1.5rem;
    border: 1px solid rgba(0,0,0,0.05);
    box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    transition: all 0.3s ease;
}

.notification-item:hover {
    transform: translateY(-3px) scale(1.01);
    box-shadow: 0 8px 20px rgba(0,0,0,0.08);
}

.notification-icon {
    flex-shrink: 0;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background-color: #eef2ff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    color: #3b82f6;
}

.notification-message {
    margin: 0 0 0.25rem 0;
    font-weight: 500;
    color: #1f2937;
}

.notification-date {
    font-size: 0.875rem;
    color: #6b7280;
}
</style>

<?php require_once ROOT_PATH . '/app/views/partials/footer.php'; ?>