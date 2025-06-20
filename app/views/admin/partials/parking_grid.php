<?php // Fichier: app/views/admin/partials/parking_grid.php ?>

<?php if (empty($spots)): ?>
    <div class="no-results-card">
        <p>Aucune place de parking ne correspond aux filtres sélectionnés.</p>
    </div>
<?php else: ?>
    <div class="parking-grid">
        <?php foreach ($spots as $spot): ?>
            <div id="spot-card-<?= $spot['id'] ?>" class="parking-spot-card status-<?= $spot['status'] ?>">
                <div class="spot-header">
                    <h3>Place <?= htmlspecialchars($spot['spot_number']) ?></h3>
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

                <form action="<?= BASE_URL ?>/admin/update-spot" method="POST" class="spot-update-form">
                    <input type="hidden" name="spot_id" value="<?= $spot['id'] ?>">
                    <input type="hidden" name="spot_number" value="<?= htmlspecialchars($spot['spot_number']) ?>">
                    
                    <div class="form-group">
                        <label>Statut</label>
                        <select name="status" class="form-control">
                            <option value="disponible" <?= $spot['status'] == 'disponible' ? 'selected' : '' ?>>Disponible</option>
                            <option value="occupée" <?= $spot['status'] == 'occupée' ? 'selected' : '' ?>>Occupée</option>
                            <option value="maintenance" <?= $spot['status'] == 'maintenance' ? 'selected' : '' ?>>Maintenance</option>
                            <option value="réservée" <?= $spot['status'] == 'réservée' ? 'selected' : '' ?>>Réservée</option>
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
<?php endif; ?>


<?php if ($total_pages > 1): ?>
<nav class="pagination-container" aria-label="Pagination des places">
    <ul class="pagination">
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                <a class="page-link" href="#" data-page="<?= $i ?>"><?= $i ?></a>
            </li>
        <?php endfor; ?>
    </ul>
</nav>
<?php endif; ?>