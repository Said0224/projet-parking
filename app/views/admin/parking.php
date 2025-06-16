<?php require_once ROOT_PATH . '/app/views/partials/header.php'; ?>

<!-- Conteneur pour les notifications AJAX -->
<div id="ajax-notification" class="notification-container"></div>

<div class="container">
    <div class="page-header">
        <h1><i class="fas fa-parking"></i> Gestion du parking</h1>
        <a href="<?= BASE_URL ?>/admin" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour au dashboard
        </a>
    </div>

    <div class="parking-grid">
        <?php foreach ($spots as $spot): ?>
        <!-- On ajoute un ID unique à chaque carte pour la retrouver facilement -->
        <div id="spot-card-<?= $spot['id'] ?>" class="parking-spot-card status-<?= $spot['status'] ?>">
            <div class="spot-header">
                <h3>Place <?= htmlspecialchars($spot['spot_number']) ?></h3>
                <!-- On ajoute un ID unique au badge de statut -->
                <span id="status-badge-<?= $spot['id'] ?>" class="status-badge status-<?= $spot['status'] ?>">
                    <?= ucfirst($spot['status']) ?>
                </span>
            </div>
            
            <div class="spot-info">
                <p><i class="fas fa-euro-sign"></i> <?= number_format($spot['price_per_hour'], 2) ?>€/h</p>
                <p>
                    <i class="fas fa-charging-station"></i> 
                    <?= $spot['has_charging_station'] ? 'Borne de recharge' : 'Pas de borne' ?>
                </p>
            </div>

            <!-- On ajoute une classe au formulaire pour l'identifier en JS -->
           <form action="<?= BASE_URL ?>/admin/update-spot" method="POST" class="spot-update-form">
                <input type="hidden" name="spot_id" value="<?= $spot['id'] ?>">
                <!-- On ajoute le numéro de la place pour les notifications -->
                <input type="hidden" name="spot_number" value="<?= htmlspecialchars($spot['spot_number']) ?>">
                
                <div class="form-group">
                    <label>Statut</label>
                    <select name="status" class="form-control">
                        <option value="available" <?= $spot['status'] == 'available' ? 'selected' : '' ?>>Disponible</option>
                        <option value="occupied" <?= $spot['status'] == 'occupied' ? 'selected' : '' ?>>Occupée</option>
                        <option value="maintenance" <?= $spot['status'] == 'maintenance' ? 'selected' : '' ?>>Maintenance</option>
                        <option value="reserved" <?= $spot['status'] == 'reserved' ? 'selected' : '' ?>>Réservée</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Prix/heure (€)</label>
                    <input type="number" name="price_per_hour" step="0.01" 
                           value="<?= $spot['price_per_hour'] ?>" class="form-control">
                </div>

                <div class="form-group">
                    <div class="form-check">
                        <input type="checkbox" name="has_charging_station" 
                               <?= $spot['has_charging_station'] ? 'checked' : '' ?> 
                               class="form-check-input">
                        <label class="form-check-label">Borne de recharge</label>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="fas fa-save"></i> Mettre à jour
                </button>
            </form>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- ============================================= -->
<!-- ========== ÉTAPE 3 : LE JAVASCRIPT ========== -->
<!-- ============================================= -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Sélectionner TOUS les formulaires de mise à jour
    const forms = document.querySelectorAll('.spot-update-form');

    forms.forEach(form => {
        form.addEventListener('submit', function(event) {
            // 1. Empêcher le rechargement de la page
            event.preventDefault();

            // 2. Récupérer les données du formulaire
            const formData = new FormData(form);
            const spotId = formData.get('spot_id');
            const newStatus = formData.get('status');

            // 3. Envoyer les données en AJAX avec fetch()
            fetch(form.action, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json()) // On attend une réponse JSON
            .then(data => {
                // 4. Gérer la réponse du serveur
                if (data.success) {
                    // Mettre à jour l'interface utilisateur
                    const card = document.getElementById('spot-card-' + spotId);
                    const badge = document.getElementById('status-badge-' + spotId);

                    // Mettre à jour le texte du badge
                    badge.textContent = newStatus.charAt(0).toUpperCase() + newStatus.slice(1);
                    
                    // Mettre à jour les classes de couleur pour la carte et le badge
                    card.className = 'parking-spot-card status-' + newStatus;
                    badge.className = 'status-badge status-' + newStatus;

                    // Afficher une notification de succès
                    showNotification(data.message, 'success');
                } else {
                    // Afficher une notification d'erreur
                    showNotification(data.message || 'Une erreur est survenue.', 'danger');
                }
            })
            .catch(error => {
                // Gérer les erreurs de réseau
                console.error('Erreur AJAX:', error);
                showNotification('Erreur de connexion avec le serveur.', 'danger');
            });
        });
    });
});

/**
 * Affiche une notification (toast) à l'écran.
 * @param {string} message Le message à afficher.
 * @param {string} type 'success' (vert) ou 'danger' (rouge).
 */
function showNotification(message, type = 'success') {
    const container = document.getElementById('ajax-notification');
    const notification = document.createElement('div');
    
    notification.className = `notification notification-${type}`;
    notification.textContent = message;
    
    container.appendChild(notification);
    
    // Rendre la notification visible
    setTimeout(() => {
        notification.classList.add('show');
    }, 10);

    // Faire disparaître la notification après 4 secondes
    setTimeout(() => {
        notification.classList.remove('show');
        // Supprimer l'élément du DOM après la transition
        setTimeout(() => {
            container.removeChild(notification);
        }, 500);
    }, 4000);
}
</script>


<!-- Styles pour le parking et les notifications -->
<style>
.parking-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 1.5rem;
    margin-top: 2rem;
}

.parking-spot-card {
    background: white;
    border-radius: 10px;
    padding: 1.5rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    border-left: 4px solid;
    transition: border-color 0.3s ease;
}

.parking-spot-card.status-available { border-left-color: #28a745; }
.parking-spot-card.status-occupied { border-left-color: #dc3545; }
.parking-spot-card.status-maintenance { border-left-color: #ffc107; }
.parking-spot-card.status-reserved { border-left-color: #17a2b8; }

.spot-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.spot-header h3 { margin: 0; color: #333; }

.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.875rem;
    font-weight: 500;
    color: white;
    transition: background-color 0.3s ease;
}

.status-badge.status-available { background-color: #28a745; }
.status-badge.status-occupied { background-color: #dc3545; }
.status-badge.status-maintenance { background-color: #ffc107; color: #333; }
.status-badge.status-reserved { background-color: #17a2b8; }

.spot-info { margin-bottom: 1rem; }
.spot-info p { margin: 0.5rem 0; color: #666; }
.spot-form .form-group { margin-bottom: 1rem; }
.spot-form label { display: block; margin-bottom: 0.25rem; font-weight: 500; color: #333; }
.spot-form .form-control { width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 5px; }
.spot-form .form-check { display: flex; align-items: center; gap: 0.5rem; }

/* Styles pour le conteneur de notifications */
.notification-container {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 9999;
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.notification {
    padding: 15px 25px;
    border-radius: 8px;
    color: white;
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    opacity: 0;
    transform: translateX(100%);
    transition: all 0.5s cubic-bezier(0.68, -0.55, 0.27, 1.55);
}

.notification.show {
    opacity: 1;
    transform: translateX(0);
}

.notification-success {
    background: linear-gradient(135deg, #28a745, #20c997);
}

.notification-danger {
    background: linear-gradient(135deg, #dc3545, #fd7e14);
}
</style>

<?php require_once ROOT_PATH . '/app/views/partials/footer.php'; ?>