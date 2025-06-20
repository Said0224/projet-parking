<?php 
require_once ROOT_PATH . '/app/views/partials/header.php'; 
?>

<!-- Conteneur pour les notifications AJAX -->
<div id="ajax-notification" class="notification-container"></div>

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
            <!-- ===== DÉBUT DE LA MODIFICATION ===== -->
            <form id="notification-preference-form" action="<?= BASE_URL ?>/notifications/update-preference" method="POST">
                <div class="form-switch">
                    <input type="checkbox" id="email-notifications" name="notif_email" class="form-check-input" <?= $email_preference ? 'checked' : '' ?>>
                    <label for="email-notifications" class="form-check-label"></label>
                </div>
            </form>
            <!-- ===== FIN DE LA MODIFICATION ===== -->
        </div>
    </div>

    <!-- Section de l'Historique -->
    <div class="notif-section">
        <h2><i class="fas fa-history"></i> Historique</h2>
        <div class="notifications-list">
            <?php if (empty($notifications)): ?>
                <div class="notification-item">
                    <div class="notification-content">
                        <p class="notification-message">Vous n'avez aucune notification pour le moment.</p>
                    </div>
                </div>
            <?php else: ?>
                <?php foreach ($notifications as $notification): 
                    $iconClass = 'fas fa-info-circle'; // Icône par défaut
                    if ($notification['type'] === 'account_created') $iconClass = 'fas fa-user-plus';
                    if ($notification['type'] === 'reservation_success') $iconClass = 'fas fa-calendar-check';
                    if ($notification['type'] === 'reservation_cancelled') $iconClass = 'fas fa-calendar-times';
                ?>
                <div class="notification-item <?= $notification['est_lu'] ? 'read' : 'unread' ?>">
                    <div class="notification-icon">
                        <i class="<?= $iconClass ?>"></i>
                    </div>
                    <div class="notification-content">
                        <p class="notification-message"><?= htmlspecialchars($notification['contenu']) ?></p>
                        <span class="notification-date"><?= date('d/m/Y \à H:i', strtotime($notification['date'])) ?></span>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
/* Styles spécifiques pour la page de notifications */
.notifications-page-container {
    max-width: 900px;
    margin: 0 auto;
}
.notif-section { margin-bottom: 2.5rem; }
.notif-section h2 {
    color: white; font-size: 1.8rem; margin-bottom: 1.5rem; padding-bottom: 0.75rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.2); display: flex; align-items: center; gap: 0.75rem;
}
.notif-section h2 i { color: #ffd700; }
.preference-card {
    background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); border-radius: 20px;
    padding: 1.5rem 2rem; display: flex; justify-content: space-between; align-items: center;
    border: 1px solid rgba(255, 255, 255, 0.2); box-shadow: 0 8px 30px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}
.preference-card:hover { transform: translateY(-5px) scale(1.02); box-shadow: 0 12px 40px rgba(0,0,0,0.15); background: rgba(255, 255, 255, 0.15); }
.preference-info { display: flex; align-items: center; gap: 1rem; color: white; font-weight: 500; }
.preference-info i { font-size: 1.5rem; opacity: 0.8; }
.notifications-list { display: flex; flex-direction: column; gap: 1rem; }
.notification-item {
    background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); border-radius: 15px;
    padding: 1.5rem; display: flex; align-items: center; gap: 1.5rem;
    border: 1px solid rgba(255, 255, 255, 0.2); box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    transition: all 0.3s ease; color: white;
}
.notification-item:hover { transform: translateY(-5px) scale(1.02); background: rgba(255, 255, 255, 0.15); box-shadow: 0 8px 25px rgba(0,0,0,0.12); }
.notification-item.read { opacity: 0.7; }
.notification-icon {
    flex-shrink: 0; width: 50px; height: 50px; border-radius: 50%;
    background: linear-gradient(145deg, rgba(255, 255, 255, 0.2), rgba(255, 255, 255, 0.1));
    display: flex; align-items: center; justify-content: center; font-size: 1.2rem; color: white;
}
.notification-message { margin: 0 0 0.25rem 0; font-weight: 500; }
.notification-date { font-size: 0.875rem; opacity: 0.7; }

/* Styles pour les notifications AJAX */
.notification-container { position: fixed; top: 20px; right: 20px; z-index: 9999; }
.notification {
    padding: 15px 25px; border-radius: 8px; color: white; box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    opacity: 0; transform: translateX(100%); transition: all 0.5s ease; margin-bottom: 10px;
}
.notification.show { opacity: 1; transform: translateX(0); }
.notification-success { background: linear-gradient(135deg, #28a745, #20c997); }
.notification-danger { background: linear-gradient(135deg, #dc3545, #fd7e14); }
</style>

<!-- ===== DÉBUT DU SCRIPT JAVASCRIPT AJOUTÉ ===== -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const emailToggle = document.getElementById('email-notifications');
    const form = document.getElementById('notification-preference-form');

    if (emailToggle && form) {
        emailToggle.addEventListener('change', function() {
            const formData = new FormData(form);
            
            // On s'assure que la valeur est 0 si la case n'est pas cochée
            if (!this.checked) {
                // Si on enlève la clé, le `isset` en PHP sera faux
                formData.delete('notif_email');
            }

            fetch(form.action, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json().catch(() => ({}))) // Gère les réponses vides ou non-JSON
            .then(data => {
                // Utilise le message de session s'il existe, sinon un message par défaut
                const message = data.message || (this.checked ? 'Notifications par e-mail activées.' : 'Notifications par e-mail désactivées.');
                const success = data.success !== false; // succès par défaut sauf si explicitement faux
                showNotification(message, success ? 'success' : 'danger');
            })
            .catch(error => {
                console.error('Erreur:', error);
                showNotification('Une erreur de communication est survenue.', 'danger');
            });
        });
    }
});

// Fonction de notification générique
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
<!-- ===== FIN DU SCRIPT JAVASCRIPT AJOUTÉ ===== -->

<?php require_once ROOT_PATH . '/app/views/partials/footer.php'; ?>