<?php require_once ROOT_PATH . '/app/views/partials/header.php'; ?>

<!-- Conteneur pour les notifications AJAX -->
<div id="ajax-notification" class="notification-container"></div>

<div class="container">
    <div class="user-header">
        <h1><i class="fas fa-tachometer-alt"></i> Mon Dashboard</h1>
        <p>Bienvenue <?= htmlspecialchars($_SESSION['user_prenom'] ?? 'Utilisateur') ?> !</p>
    </div>

    <div class="dashboard-actions">
        <a href="<?= BASE_URL ?>/user/parking" class="action-card">
            <div class="action-icon"><i class="fas fa-car"></i></div>
            <h3>Gérer mes réservations</h3>
            <p>Consulter toutes les places et gérer vos réservations.</p>
        </a>
    </div>

    <!-- La section des places sera maintenant mise à jour dynamiquement -->
    <div class="parking-status-section">
        <h2>État des places en temps réel</h2>
        <!-- On ajoute une icône de chargement -->
        <div id="spots-loader" class="loader">Chargement des places...</div>
        <div id="spots-grid-container" class="spots-grid" style="display:none;">
            <!-- Les places seront injectées ici par JavaScript -->
        </div>
    </div>

    <div class="my-reservations">
        <h2>Mes réservations</h2>
        <div id="reservations-table-container">
            <?php if (empty($userReservations)): ?>
                <p id="no-reservations-message" class="no-reservations">Vous n'avez aucune réservation active.</p>
                <table class="table" style="display: none;">
                    <thead><tr><th>Place</th><th>Début</th><th>Fin</th><th>Prix/h</th><th>Statut</th><th>Actions</th></tr></thead>
                    <tbody></tbody>
                </table>
            <?php else: ?>
                <p id="no-reservations-message" class="no-reservations" style="display: none;">Vous n'avez aucune réservation active.</p>
                <div class="table-responsive">
                    <table class="table">
                        <thead><tr><th>Place</th><th>Début</th><th>Fin</th><th>Prix/h</th><th>Statut</th><th>Actions</th></tr></thead>
                        <tbody>
                            <?php foreach ($userReservations as $reservation): ?>
                            <tr id="reservation-row-<?= $reservation['id'] ?>">
                                <td><strong><?= htmlspecialchars($reservation['spot_number']) ?></strong>
                                    <?php if ($reservation['has_charging_station']): ?><i class="fas fa-charging-station text-success" title="Borne de recharge"></i><?php endif; ?>
                                </td>
                                <td><?= date('d/m/Y H:i', strtotime($reservation['start_time'])) ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($reservation['end_time'])) ?></td>
                                <td><?= number_format($reservation['price_per_hour'], 2) ?>€</td>
                                <td><span class="status status-<?= $reservation['status'] ?>"><?= ucfirst($reservation['status']) ?></span></td>
                                <td>
                                    <?php if ($reservation['status'] == 'active' && strtotime($reservation['start_time']) > time()): ?>
                                        <form action="<?= BASE_URL ?>/user/cancel-reservation" method="POST" class="cancel-reservation-form" style="display: inline;">
                                            <input type="hidden" name="reservation_id" value="<?= $reservation['id'] ?>">
                                            <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-times"></i> Annuler</button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal de réservation -->
<div id="reservationModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeReservationModal()">×</span>
        <h2>Réserver la place <span id="modalSpotNumber"></span></h2>
        <form id="reservation-form" action="<?= BASE_URL ?>/user/reserve" method="POST">
            <input type="hidden" id="modalSpotId" name="spot_id">
            <div class="form-group"><label for="start_time">Début</label><input type="datetime-local" id="start_time" name="start_time" class="form-control" required></div>
            <div class="form-group"><label for="end_time">Fin</label><input type="datetime-local" id="end_time" name="end_time" class="form-control" required></div>
            <div class="form-group"><p>Prix: <span id="modalPrice"></span>€/heure</p></div>
            <div class="modal-actions">
                <button type="button" class="btn btn-secondary" onclick="closeReservationModal()">Fermer</button>
                <button type="submit" class="btn btn-primary">Confirmer</button>
            </div>
        </form>
    </div>
</div>

<!-- ============================================= -->
<!-- ========== JAVASCRIPT & STYLES ========== -->
<!-- ============================================= -->
<style>
.user-header, .action-card, .modal, .notification-container { /* Styles existants */ }
/* Ajout de styles pour le loader et les états */
.loader { text-align: center; padding: 2rem; color: white; font-size: 1.2rem; }
.parking-status-section h2 { color: white; text-align: center; margin-bottom: 1.5rem; text-shadow: 1px 1px 3px rgba(0,0,0,0.2); }
.spots-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 1rem; margin-bottom: 1rem; }
.spot-card { background: white; border-radius: 10px; padding: 1rem; box-shadow: 0 2px 4px rgba(0,0,0,0.1); text-align: center; border: 2px solid; transition: all 0.3s ease; }
.spot-card.disponible { border-color: #28a745; }
.spot-card.occupée { border-color: #dc3545; background-color: #f8d7da; }
.spot-card.réservée { border-color: #17a2b8; background-color: #d1ecf1; }
.spot-card.maintenance { border-color: #ffc107; background-color: #fff3cd; }
.spot-number { font-size: 1.5rem; font-weight: bold; margin-bottom: 0.5rem; }
.spot-status-text { font-weight: bold; text-transform: uppercase; font-size: 0.9rem; margin-bottom: 1rem; }
.spot-status-text.disponible { color: #28a745; }
.spot-status-text.occupée { color: #dc3545; }
.spot-status-text.réservée { color: #17a2b8; }
.spot-status-text.maintenance { color: #856404; }
.spot-card .btn { width: 100%; }
.spot-card .btn:disabled { background-color: #6c757d; cursor: not-allowed; }
/* Autres styles (copiés de la version précédente) */
.user-header { text-align: center; margin-bottom: 2rem; padding: 2rem; background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: white; border-radius: 10px; }
.dashboard-actions { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem; }
.action-card { background: white; padding: 2rem; border-radius: 10px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); text-align: center; text-decoration: none; color: inherit; transition: transform 0.2s; }
.action-card:hover { transform: translateY(-5px); text-decoration: none; color: inherit; }
.action-icon { background: #28a745; color: white; width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem; margin: 0 auto 1rem; }
.modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.5); }
.modal-content { background-color: white; margin: 10% auto; padding: 2rem; border-radius: 10px; width: 90%; max-width: 500px; }
.close { color: #aaa; float: right; font-size: 28px; font-weight: bold; cursor: pointer; }
.close:hover { color: black; }
.modal-actions { display: flex; gap: 1rem; justify-content: flex-end; margin-top: 1rem; }
.status { padding: .25em .6em; font-size: 75%; font-weight: 700; line-height: 1; text-align: center; white-space: nowrap; vertical-align: baseline; border-radius: .25rem; color: #fff; }
.status-active { background-color: #28a745; }
.status-annulée { background-color: #dc3545; }
.status-passée { background-color: #6c757d; }
.notification-container { position: fixed; top: 20px; right: 20px; z-index: 9999; display: flex; flex-direction: column; gap: 10px; }
.notification { padding: 15px 25px; border-radius: 8px; color: white; box-shadow: 0 5px 15px rgba(0,0,0,0.2); opacity: 0; transform: translateX(100%); transition: all 0.5s cubic-bezier(0.68, -0.55, 0.27, 1.55); }
.notification.show { opacity: 1; transform: translateX(0); }
.notification-success { background: linear-gradient(135deg, #28a745, #20c997); }
.notification-danger { background: linear-gradient(135deg, #dc3545, #fd7e14); }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // ==========================================================
    // NOUVEAU : MISE À JOUR DYNAMIQUE DE L'ÉTAT DES PLACES
    // ==========================================================
    const spotsGrid = document.getElementById('spots-grid-container');
    const spotsLoader = document.getElementById('spots-loader');
    const apiUrl = '<?= BASE_URL ?>/api/get-all-spots-status';

    function updateSpotDisplay(spot) {
        let card = document.getElementById(`spot-card-${spot.id}`);
        
        // Si la carte n'existe pas, on la crée
        if (!card) {
            card = document.createElement('div');
            card.id = `spot-card-${spot.id}`;
            card.className = 'spot-card';
            
            let buttonHtml = `<button class="btn btn-primary btn-sm" onclick="openReservationModal(${spot.id}, '${spot.spot_number}', ${spot.price_per_hour})">Réserver</button>`;
            if (spot.status !== 'disponible') {
                 buttonHtml = `<button class="btn btn-secondary btn-sm" disabled>Indisponible</button>`;
            }
            
            card.innerHTML = `
                <div class="spot-number">${spot.spot_number}</div>
                <div class="spot-status-text"></div>
                ${buttonHtml}
            `;
            spotsGrid.appendChild(card);
        }

        // Mettre à jour les classes et le contenu
        card.className = `spot-card ${spot.status}`;
        const statusText = card.querySelector('.spot-status-text');
        statusText.className = `spot-status-text ${spot.status}`;
        statusText.textContent = spot.status.replace('disponible', 'disponible');

        // Mettre à jour le bouton
        const button = card.querySelector('button');
        if (spot.status === 'disponible') {
            button.disabled = false;
            button.className = 'btn btn-primary btn-sm';
            button.textContent = 'Réserver';
        } else {
            button.disabled = true;
            button.className = 'btn btn-secondary btn-sm';
            button.textContent = 'Indisponible';
        }
    }

    function fetchSpotsStatus() {
        fetch(apiUrl)
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok');
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    spotsLoader.style.display = 'none';
                    spotsGrid.style.display = 'grid';
                    data.spots.forEach(spot => updateSpotDisplay(spot));
                } else {
                    spotsLoader.textContent = "Erreur lors du chargement des places.";
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
                spotsLoader.textContent = "Impossible de contacter le serveur.";
            });
    }

    // Premier chargement, puis mise à jour toutes les 5 secondes
    fetchSpotsStatus();
    setInterval(fetchSpotsStatus, 5000); // 5000 ms = 5 secondes


    // ==========================================================
    // CODE AJAX EXISTANT POUR LES RÉSERVATIONS ET ANNULATIONS
    // ==========================================================
    const modal = document.getElementById('reservationModal');
    const reservationForm = document.getElementById('reservation-form');
    const reservationsContainer = document.getElementById('reservations-table-container');

    // Réservation
    reservationForm.addEventListener('submit', function(e) { /* ... code inchangé ... */ });

    // Annulation
    reservationsContainer.addEventListener('submit', function(e) { /* ... code inchangé ... */ });
    
    // (Le code pour le modal et les notifications est placé ci-dessous)
});

// ========== GESTION DU MODAL (Inchangé) ==========
const modal = document.getElementById('reservationModal');
function openReservationModal(spotId, spotNumber, price) { /* ... code inchangé ... */ }
function closeReservationModal() { /* ... code inchangé ... */ }
window.onclick = function(event) { if (event.target == modal) closeReservationModal(); };
document.getElementById('start_time').addEventListener('change', function() { /* ... code inchangé ... */ });

// ========== FONCTION DE NOTIFICATION (Inchangée) ==========
function showNotification(message, type = 'success') { /* ... code inchangé ... */ }

// Pour que le copier-coller soit complet, je remets le code JS existant
document.addEventListener('DOMContentLoaded', function() {
    // ... (code de mise à jour des places ci-dessus)

    const reservationForm = document.getElementById('reservation-form');
    reservationForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(reservationForm);
        fetch(reservationForm.action, { method: 'POST', body: formData })
        .then(response => response.json())
        .then(data => {
            showNotification(data.message, data.success ? 'success' : 'danger');
            if (data.success) {
                closeReservationModal();
                setTimeout(() => window.location.reload(), 1500);
            }
        })
        .catch(err => showNotification('Erreur réseau.', 'danger'));
    });

    const reservationsContainer = document.getElementById('reservations-table-container');
    reservationsContainer.addEventListener('submit', function(e) {
        if (e.target.classList.contains('cancel-reservation-form')) {
            e.preventDefault();
            const form = e.target;
            const formData = new FormData(form);
            const reservationId = formData.get('reservation_id');
            if (!confirm('Êtes-vous sûr de vouloir annuler cette réservation ?')) return;
            fetch(form.action, { method: 'POST', body: formData })
            .then(response => response.json())
            .then(data => {
                showNotification(data.message, data.success ? 'success' : 'danger');
                if (data.success) {
                    const row = document.getElementById('reservation-row-' + reservationId);
                    if (row) {
                        row.style.transition = 'opacity 0.5s';
                        row.style.opacity = '0';
                        setTimeout(() => row.remove(), 500);
                    }
                }
            })
            .catch(err => showNotification('Erreur réseau.', 'danger'));
        }
    });
});

function openReservationModal(spotId, spotNumber, price) {
    document.getElementById('modalSpotId').value = spotId;
    document.getElementById('modalSpotNumber').textContent = spotNumber;
    document.getElementById('modalPrice').textContent = price;
    const now = new Date();
    now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
    document.getElementById('start_time').min = now.toISOString().slice(0, 16);
    document.getElementById('reservationModal').style.display = 'block';
}
function closeReservationModal() { document.getElementById('reservationModal').style.display = 'none'; }

function showNotification(message, type = 'success') {
    const container = document.getElementById('ajax-notification');
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.textContent = message;
    container.appendChild(notification);
    setTimeout(() => notification.classList.add('show'), 10);
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => container.removeChild(notification), 500);
    }, 4000);
}
</script>