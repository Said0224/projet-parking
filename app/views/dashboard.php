<?php require_once ROOT_PATH . '/app/views/partials/header.php'; ?>

<div class="container dashboard-container">
    <h1>Tableau de Bord</h1>
    <p>Bienvenue, <?= htmlspecialchars($_SESSION['user_email']) ?> !</p>
    
    <div class="dashboard-stats">
        <div class="stat-card">
            <h3>Places Libres</h3>
            <span class="stat-number free"><?= count(array_filter($parking_spaces, function($space) { return $space['status'] === 'libre'; })) ?></span>
        </div>
        <div class="stat-card">
            <h3>Places Occupées</h3>
            <span class="stat-number occupied"><?= count(array_filter($parking_spaces, function($space) { return $space['status'] === 'occupee'; })) ?></span>
        </div>
        <div class="stat-card">
            <h3>Total Places</h3>
            <span class="stat-number total"><?= count($parking_spaces) ?></span>
        </div>
    </div>
    
    <div class="parking-grid">
        <h2>État des Places de Parking</h2>
        <div class="spaces-grid">
            <?php foreach ($parking_spaces as $space): ?>
                <div class="parking-space <?= $space['status'] ?>" 
                     aria-label="<?= $space['name'] ?> - <?= $space['status'] === 'libre' ? 'Libre' : 'Occupée' ?>">
                    <div class="space-name"><?= htmlspecialchars($space['name']) ?></div>
                    <div class="space-status">
                        <?= $space['status'] === 'libre' ? '🟢 Libre' : '🔴 Occupée' ?>
                    </div>
                    <div class="sensor-id">Capteur: <?= htmlspecialchars($space['sensor_id']) ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php require_once ROOT_PATH . '/app/views/partials/footer.php'; ?>