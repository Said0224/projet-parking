<?php require_once ROOT_PATH . '/app/views/partials/header.php'; ?>

<div class="container">
    <div class="page-header">
        <h1><i class="fas fa-parking"></i> Gestion du parking</h1>
        <a href="<?= BASE_URL ?>/admin" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour au dashboard
        </a>
    </div>

    <div class="parking-grid">
        <?php foreach ($spots as $spot): ?>
        <div class="parking-spot-card status-<?= $spot['status'] ?>">
            <div class="spot-header">
                <h3>Place <?= htmlspecialchars($spot['spot_number']) ?></h3>
                <span class="status-badge status-<?= $spot['status'] ?>">
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

           <form action="<?= BASE_URL ?>/admin/update-spot" method="POST" class="spot-form">
                <input type="hidden" name="spot_id" value="<?= $spot['id'] ?>">
                
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
}

.parking-spot-card.status-available {
    border-left-color: #28a745;
}

.parking-spot-card.status-occupied {
    border-left-color: #dc3545;
}

.parking-spot-card.status-maintenance {
    border-left-color: #ffc107;
}

.parking-spot-card.status-reserved {
    border-left-color: #17a2b8;
}

.spot-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.spot-header h3 {
    margin: 0;
    color: #333;
}

.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.875rem;
    font-weight: 500;
}

.status-badge.status-available {
    background-color: #d4edda;
    color: #155724;
}

.status-badge.status-occupied {
    background-color: #f8d7da;
    color: #721c24;
}

.status-badge.status-maintenance {
    background-color: #fff3cd;
    color: #856404;
}

.status-badge.status-reserved {
    background-color: #d1ecf1;
    color: #0c5460;
}

.spot-info {
    margin-bottom: 1rem;
}

.spot-info p {
    margin: 0.5rem 0;
    color: #666;
}

.spot-form .form-group {
    margin-bottom: 1rem;
}

.spot-form label {
    display: block;
    margin-bottom: 0.25rem;
    font-weight: 500;
    color: #333;
}

.spot-form .form-control {
    width: 100%;
    padding: 0.5rem;
    border: 1px solid #ddd;
    border-radius: 5px;
}

.spot-form .form-check {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
</style>

<?php require_once ROOT_PATH . '/app/views/partials/footer.php'; ?>