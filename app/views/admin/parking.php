<?php require_once ROOT_PATH . '/app/views/partials/header.php'; ?>

<!-- Conteneur pour les notifications AJAX -->
<div id="ajax-notification" class="notification-container"></div>

<div class="container">
    <div class="page-header">
        <h1><i class="fas fa-parking"></i> Gestion du parking</h1>
        <div class="header-actions">
            <button id="open-filter-btn" class="btn btn-primary">
                <i class="fas fa-filter"></i> Filtrer
            </button>
            <a href="<?= BASE_URL ?>/admin" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        </div>
    </div>

    <!-- Ce conteneur sera mis à jour par AJAX -->
    <div id="parking-grid-container">
        <?php require ROOT_PATH . '/app/views/admin/partials/parking_grid.php'; ?>
    </div>
</div>

<!-- ======================== MODAL DE FILTRE ======================== -->
<div id="filter-modal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <h3><i class="fas fa-filter"></i> Filtrer les places</h3>
            <button class="close-modal-btn">×</button>
        </div>
        <div class="modal-body">
            <form id="filter-form">
                <div class="form-group">
                    <label for="filter_status" class="form-label">Filtrer par statut</label>
                    <select id="filter_status" name="filter_status" class="form-control">
                        <option value="">Tous les statuts</option>
                        <option value="disponible">Disponible</option>
                        <option value="occupée">Occupée</option>
                        <option value="réservée">Réservée</option>
                        <option value="maintenance">En maintenance</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="filter_charging" class="form-label">Filtrer par borne de recharge</label>
                    <select id="filter_charging" name="filter_charging" class="form-control">
                        <option value="">Toutes</option>
                        <option value="1">Avec borne</option>
                        <option value="0">Sans borne</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" id="reset-filter-btn" class="btn btn-light">Réinitialiser</button>
                    <button type="submit" class="btn btn-primary">Appliquer les filtres</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* Styles pour la page, la modal, et les notifications */
.header-actions {
    display: flex;
    gap: 1rem;
}

.parking-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 1.5rem;
    margin-top: 2rem;
}

.parking-spot-card {
    background: white; border-radius: 15px; padding: 1.5rem;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    border-left: 5px solid; transition: all 0.3s ease;
}
.parking-spot-card.status-disponible { border-left-color: #28a745; }
.parking-spot-card.status-occupée { border-left-color: #dc3545; }
.parking-spot-card.status-maintenance { border-left-color: #ffc107; }
.parking-spot-card.status-réservée { border-left-color: #17a2b8; }

.spot-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; }
.spot-header h3 { margin: 0; color: #333; }
.status-badge { padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.875rem; font-weight: 600; color: white; }
.status-badge.status-disponible { background-color: #28a745; }
.status-badge.status-occupée { background-color: #dc3545; }
.status-badge.status-maintenance { background-color: #ffc107; color: #333; }
.status-badge.status-réservée { background-color: #17a2b8; }

.spot-info { margin-bottom: 1rem; color: #666; }
.spot-update-form .form-group { margin-bottom: 1rem; }
.spot-update-form .form-control { width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 5px; }

.pagination-container { display: flex; justify-content: center; margin-top: 2rem; }
.pagination { list-style: none; padding: 0; display: flex; gap: 0.5rem; }
.page-item .page-link { color: #1e40af; border: 1px solid #ddd; padding: 0.5rem 1rem; text-decoration: none; border-radius: 5px; transition: all 0.2s; }
.page-item.active .page-link { background-color: #1e40af; color: white; border-color: #1e40af; }

.no-results-card {
    background: white; border-radius: 15px; padding: 2rem; text-align: center;
    color: #666; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08); grid-column: 1 / -1;
}

/* Modal Styles */
.modal-overlay {
    position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.6);
    backdrop-filter: blur(5px); display: none; align-items: center; justify-content: center; z-index: 1000;
    opacity: 0; transition: opacity 0.3s ease-in-out;
}
.modal-overlay.show { display: flex; opacity: 1; }
.modal-content {
    background: white; border-radius: 20px; box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
    width: 90%; max-width: 500px; overflow: hidden; transform: translateY(-20px); transition: transform 0.3s ease-in-out;
}
.modal-overlay.show .modal-content { transform: translateY(0); }
.modal-header { padding: 1.5rem; background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%); color: white; display: flex; justify-content: space-between; align-items: center; }
.modal-header h3 { margin: 0; font-size: 1.5rem; }
.close-modal-btn { background: none; border: none; font-size: 2rem; color: white; cursor: pointer; opacity: 0.8; line-height: 1; }
.modal-body { padding: 2rem; }
.modal-footer { padding: 1.5rem; background-color: #f7f7f7; display: flex; justify-content: flex-end; gap: 1rem; }

/* Notification Styles */
.notification-container { position: fixed; top: 20px; right: 20px; z-index: 9999; }
.notification {
    padding: 15px 25px; border-radius: 8px; color: white; box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    opacity: 0; transform: translateX(100%); transition: all 0.5s ease; margin-bottom: 10px;
}
.notification.show { opacity: 1; transform: translateX(0); }
.notification-success { background: linear-gradient(135deg, #28a745, #20c997); }
.notification-danger { background: linear-gradient(135deg, #dc3545, #fd7e14); }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const gridContainer = document.getElementById('parking-grid-container');
    const filterForm = document.getElementById('filter-form');
    const modal = document.getElementById('filter-modal');
    
    // --- Gestion de la modale ---
    const openBtn = document.getElementById('open-filter-btn');
    const closeBtn = document.querySelector('.close-modal-btn');
    const resetBtn = document.getElementById('reset-filter-btn');

    openBtn.onclick = () => modal.classList.add('show');
    closeBtn.onclick = () => modal.classList.remove('show');
    window.onclick = (event) => {
        if (event.target == modal) {
            modal.classList.remove('show');
        }
    }

    // --- Logique AJAX ---
    async function fetchSpots(page = 1) {
        gridContainer.style.opacity = '0.5';
        const formData = new FormData(filterForm);
        const params = new URLSearchParams();
        
        params.append('page', page);
        if (formData.get('filter_status')) {
            params.append('filter_status', formData.get('filter_status'));
        }
        if (formData.get('filter_charging')) {
            params.append('filter_charging', formData.get('filter_charging'));
        }

        try {
            const response = await fetch(`<?= BASE_URL ?>/admin/api/parking-spots?${params.toString()}`);
            if (!response.ok) throw new Error('Erreur réseau ou serveur');
            
            const html = await response.text();
            gridContainer.innerHTML = html;
            addUpdateListeners(); // Ré-attacher les listeners aux nouveaux formulaires
        } catch (error) {
            gridContainer.innerHTML = '<p class="no-results-card">Erreur lors du chargement des données.</p>';
            console.error('Erreur AJAX:', error);
        } finally {
            gridContainer.style.opacity = '1';
        }
    }

    // Appliquer les filtres
    filterForm.addEventListener('submit', function(e) {
        e.preventDefault();
        fetchSpots(1);
        modal.classList.remove('show');
    });

    // Réinitialiser les filtres
    resetBtn.addEventListener('click', function() {
        filterForm.reset();
        fetchSpots(1);
        modal.classList.remove('show');
    });

    // Gérer la pagination
    gridContainer.addEventListener('click', function(e) {
        if (e.target.matches('.page-link')) {
            e.preventDefault();
            const page = e.target.dataset.page;
            if (page) {
                fetchSpots(page);
            }
        }
    });

    // --- Gérer la mise à jour d'une place (doit être ré-attaché après chaque AJAX) ---
    function addUpdateListeners() {
        const updateForms = document.querySelectorAll('.spot-update-form');
        updateForms.forEach(form => {
            form.addEventListener('submit', function(event) {
                event.preventDefault();
                const formData = new FormData(form);
                const spotId = formData.get('spot_id');
                const newStatus = formData.get('status');

                fetch(form.action, { method: 'POST', body: formData })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const card = document.getElementById('spot-card-' + spotId);
                        const badge = document.getElementById('status-badge-' + spotId);
                        badge.textContent = newStatus.charAt(0).toUpperCase() + newStatus.slice(1);
                        card.className = 'parking-spot-card status-' + newStatus;
                        badge.className = 'status-badge status-' + newStatus;
                        showNotification(data.message, 'success');
                    } else {
                        showNotification(data.message || 'Une erreur est survenue.', 'danger');
                    }
                })
                .catch(error => {
                    console.error('Erreur AJAX:', error);
                    showNotification('Erreur de connexion avec le serveur.', 'danger');
                });
            });
        });
    }

    // Attacher les listeners la première fois
    addUpdateListeners();
});

// Fonction pour afficher les notifications (toast)
function showNotification(message, type = 'success') {
    const container = document.getElementById('ajax-notification');
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.textContent = message;
    container.appendChild(notification);
    setTimeout(() => notification.classList.add('show'), 10);
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => { if (notification.parentNode) { notification.parentNode.removeChild(notification); } }, 500);
    }, 4000);
}
</script>

<?php require_once ROOT_PATH . '/app/views/partials/footer.php'; ?>