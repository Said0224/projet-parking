<?php require_once ROOT_PATH . '/app/views/partials/header.php'; ?>

<div class="container">
    <div class="user-header">
        <h1><i class="fas fa-tachometer-alt"></i> Mon Dashboard</h1>
        <p>Bienvenue <?= htmlspecialchars($_SESSION['user_prenom'] ?? 'Utilisateur') ?> !</p>
    </div>

    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success">
            <?= $_SESSION['success_message'] ?>
        </div>
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="alert alert-danger">
            <?= $_SESSION['error_message'] ?>
        </div>
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>

    <div class="dashboard-actions">
        <a href="/user/parking" class="action-card">
            <div class="action-icon">
                <i class="fas fa-search"></i>
            </div>
            <h3>Voir les places</h3>
            <p>Consulter la disponibilité des places de parking</p>
        </a>
    </div>

    <div class="available-spots">
        <h2>Places disponibles (<?= count($availableSpots) ?>)</h2>
        <div class="spots-grid">
            <?php foreach (array_slice($availableSpots, 0, 6) as $spot): ?>
            <div class="spot-card available">
                <div class="spot-number">
                    <?= htmlspecialchars($spot['spot_number']) ?>
                </div>
                <div class="spot-details">
                    <p class="price"><?= number_format($spot['price_per_hour'], 2) ?>€/h</p>
                    <?php if ($spot['has_charging_station']): ?>
                        <p class="charging"><i class="fas fa-charging-station"></i> Borne de recharge</p>
                    <?php endif; ?>
                </div>
                <button class="btn btn-primary btn-sm" onclick="openReservationModal(<?= $spot['id'] ?>, '<?= $spot['spot_number'] ?>', <?= $spot['price_per_hour'] ?>)">
                    Réserver
                </button>
            </div>
            <?php endforeach; ?>
        </div>
        <?php if (count($availableSpots) > 6): ?>
            <div class="text-center mt-3">
                <a href="/user/parking" class="btn btn-outline-primary">Voir toutes les places</a>
            </div>
        <?php endif; ?>
    </div>

    <div class="my-reservations">
        <h2>Mes réservations</h2>
        <?php if (empty($userReservations)): ?>
            <p class="no-reservations">Vous n'avez aucune réservation.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Place</th>
                            <th>Début</th>
                            <th>Fin</th>
                            <th>Prix/h</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($userReservations as $reservation): ?>
                        <tr>
                            <td>
                                <strong><?= htmlspecialchars($reservation['spot_number']) ?></strong>
                                <?php if ($reservation['has_charging_station']): ?>
                                    <i class="fas fa-charging-station text-success" title="Borne de recharge"></i>
                                <?php endif; ?>
                            </td>
                            <td><?= date('d/m/Y H:i', strtotime($reservation['start_time'])) ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($reservation['end_time'])) ?></td>
                            <td><?= number_format($reservation['price_per_hour'], 2) ?>€</td>
                            <td>
                                <span class="status status-<?= $reservation['status'] ?>">
                                    <?= ucfirst($reservation['status']) ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($reservation['status'] == 'active' && strtotime($reservation['start_time']) > time()): ?>
                                    <form action="/user/cancel-reservation" method="POST" style="display: inline;" 
                                          onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cette réservation ?')">
                                        <input type="hidden" name="reservation_id" value="<?= $reservation['id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-times"></i> Annuler
                                        </button>
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

<!-- Modal de réservation -->
<div id="reservationModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Réserver la place <span id="modalSpotNumber"></span></h2>
        <form action="/user/reserve" method="POST">
            <input type="hidden" id="modalSpotId" name="spot_id">
            
            <div class="form-group">
                <label for="start_time">Début de la réservation</label>
                <input type="datetime-local" id="start_time" name="start_time" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label for="end_time">Fin de la réservation</label>
                <input type="datetime-local" id="end_time" name="end_time" class="form-control" required>
            </div>
            
            <div class="form-group">
                <p>Prix: <span id="modalPrice"></span>€/heure</p>
            </div>
            
            <div class="modal-actions">
                <button type="button" class="btn btn-secondary" onclick="closeReservationModal()">Annuler</button>
                <button type="submit" class="btn btn-primary">Confirmer la réservation</button>
            </div>
        </form>
    </div>
</div>

<style>
.user-header {
    text-align: center;
    margin-bottom: 2rem;
    padding: 2rem;
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
    border-radius: 10px;
}

.dashboard-actions {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.action-card {
    background: white;
    padding: 2rem;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    text-align: center;
    text-decoration: none;
    color: inherit;
    transition: transform 0.2s;
}

.action-card:hover {
    transform: translateY(-5px);
    text-decoration: none;
    color: inherit;
}

.action-icon {
    background: #28a745;
    color: white;
    width: 80px;
    height: 80px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    margin: 0 auto 1rem;
}

.spots-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 1rem;
    margin-bottom: 1rem;
}

.spot-card {
    background: white;
    border-radius: 10px;
    padding: 1rem;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    text-align: center;
    border-left: 4px solid #28a745;
}

.spot-number {
    font-size: 1.5rem;
    font-weight: bold;
    color: #333;
    margin-bottom: 0.5rem;
}

.spot-details {
    margin-bottom: 1rem;
}

.price {
    font-weight: bold;
    color: #28a745;
    margin: 0;
}

.charging {
    color: #17a2b8;
    margin: 0.25rem 0 0 0;
    font-size: 0.875rem;
}

.alert {
    padding: 1rem;
    border-radius: 5px;
    margin-bottom: 1rem;
}

.alert-success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.alert-danger {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.5);
}

.modal-content {
    background-color: white;
    margin: 15% auto;
    padding: 2rem;
    border-radius: 10px;
    width: 90%;
    max-width: 500px;
}

.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
}

.close:hover {
    color: black;
}

.modal-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    margin-top: 1rem;
}
</style>

<script>
function openReservationModal(spotId, spotNumber, price) {
    document.getElementById('modalSpotId').value = spotId;
    document.getElementById('modalSpotNumber').textContent = spotNumber;
    document.getElementById('modalPrice').textContent = price;
    
    // Définir l'heure actuelle comme minimum
    const now = new Date();
    now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
    document.getElementById('start_time').min = now.toISOString().slice(0, 16);
    
    document.getElementById('reservationModal').style.display = 'block';
}

function closeReservationModal() {
    document.getElementById('reservationModal').style.display = 'none';
}

// Fermer le modal en cliquant sur X ou en dehors
document.querySelector('.close').onclick = closeReservationModal;
window.onclick = function(event) {
    const modal = document.getElementById('reservationModal');
    if (event.target == modal) {
        closeReservationModal();
    }
}

// Mettre à jour l'heure de fin automatiquement
document.getElementById('start_time').addEventListener('change', function() {
    const startTime = new Date(this.value);
    const endTime = new Date(startTime.getTime() + 2 * 60 * 60 * 1000); // +2 heures
    document.getElementById('end_time').min = this.value;
    document.getElementById('end_time').value = endTime.toISOString().slice(0, 16);
});
</script>

<?php require_once ROOT_PATH . '/app/views/partials/footer.php'; ?>