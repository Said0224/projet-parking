<?php require_once ROOT_PATH . '/app/views/partials/header.php'; ?>

<div class="container">
    <div class="admin-header">
        <h1><i class="fas fa-cog"></i> Administration - Dashboard</h1>
        <p>Bienvenue dans l'interface d'administration du parking intelligent</p>
    </div>

    <div class="admin-stats">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-info">
                <h3><?= count($users) ?></h3>
                <p>Utilisateurs</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-parking"></i>
            </div>
            <div class="stat-info">
                <h3><?= count($spots) ?></h3>
                <p>Places de parking</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-calendar-check"></i>
            </div>
            <div class="stat-info">
                <h3><?= count($reservations) ?></h3>
                <p>Réservations</p>
            </div>
        </div>
    </div>

    <div class="admin-actions">
        <a href="<?= BASE_URL ?>/admin/users" class="btn btn-primary">
            <i class="fas fa-users"></i> Gérer les utilisateurs
        </a>
        <a href="<?= BASE_URL ?>/admin/parking" class="btn btn-primary">
            <i class="fas fa-parking"></i> Gérer le parking
        </a>
    </div>

    <div class="recent-activity">
        <h2>Réservations récentes</h2>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Utilisateur</th>
                        <th>Place</th>
                        <th>Début</th>
                        <th>Fin</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (array_slice($reservations, 0, 10) as $reservation): ?>
                    <tr>
                        <td><?= htmlspecialchars($reservation['prenom'] . ' ' . $reservation['nom']) ?></td>
                        <td><?= htmlspecialchars($reservation['spot_number']) ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($reservation['start_time'])) ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($reservation['end_time'])) ?></td>
                        <td>
                            <span class="status status-<?= $reservation['status'] ?>">
                                <?= ucfirst($reservation['status']) ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
.admin-header {
    text-align: center;
    margin-bottom: 2rem;
    padding: 2rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 10px;
}

.admin-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: white;
    padding: 1.5rem;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    display: flex;
    align-items: center;
    gap: 1rem;
}

.stat-icon {
    background: #667eea;
    color: white;
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}

.stat-info h3 {
    margin: 0;
    font-size: 2rem;
    color: #333;
}

.stat-info p {
    margin: 0;
    color: #666;
}

.admin-actions {
    display: flex;
    gap: 1rem;
    margin-bottom: 2rem;
    justify-content: center;
}

.recent-activity {
    background: white;
    padding: 1.5rem;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.status {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.875rem;
    font-weight: 500;
}

.status-active {
    background-color: #d4edda;
    color: #155724;
}

.status-completed {
    background-color: #cce5ff;
    color: #004085;
}

.status-cancelled {
    background-color: #f8d7da;
    color: #721c24;
}
</style>

<?php require_once ROOT_PATH . '/app/views/partials/footer.php'; ?>