<?php require_once ROOT_PATH . '/app/views/partials/header.php'; ?>

<div class="container">
    <div class="page-header">
        <h1><i class="fas fa-bell"></i> Mes Notifications</h1>
    </div>

    <?php if (isset($_SESSION['notif_success'])): ?>
        <div class="alert alert-success"><?= $_SESSION['notif_success'] ?></div>
        <?php unset($_SESSION['notif_success']); ?>
    <?php endif; ?>
    <?php if (isset($_SESSION['notif_error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['notif_error'] ?></div>
        <?php unset($_SESSION['notif_error']); ?>
    <?php endif; ?>

    <!-- Carte des préférences -->
    <div class="card preferences-card">
        <div class="card-header">
            <h3><i class="fas fa-cog"></i> Préférences</h3>
        </div>
        <div class="card-body">
            <form method="post" action="<?= BASE_URL ?>/notifications/update-preference">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="notif_email" id="emailPrefSwitch" 
                           onchange="this.form.submit()" <?= $email_preference ? 'checked' : '' ?>>
                    <label class="form-check-label" for="emailPrefSwitch">Recevoir les notifications importantes par e-mail</label>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des notifications -->
    <div class="notifications-list">
        <?php if (empty($notifications)): ?>
            <div class="no-notifications">
                <i class="fas fa-bell-slash"></i>
                <p>Vous n'avez aucune notification pour le moment.</p>
            </div>
        <?php else: ?>
            <?php foreach ($notifications as $notif): ?>
                <div class="notification-item <?= $notif['est_lu'] ? 'read' : 'unread' ?>">
                    <div class="notification-icon">
                        <?php
                        switch ($notif['type']) {
                            case 'reservation_success': echo '<i class="fas fa-calendar-check"></i>'; break;
                            case 'reservation_cancelled': echo '<i class="fas fa-calendar-times"></i>'; break;
                            case 'account_created': echo '<i class="fas fa-user-plus"></i>'; break;
                            default: echo '<i class="fas fa-info-circle"></i>'; break;
                        }
                        ?>
                    </div>
                    <div class="notification-content">
                        <p><?= htmlspecialchars($notif['contenu']) ?></p>
                        <span class="notification-date"><?= date('d/m/Y \à H:i', strtotime($notif['date'])) ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<style>
.preferences-card { margin-bottom: 2rem; }
.form-switch { align-items: center; }
.form-check-label { margin-left: 1rem; color: #333; }
.notifications-list { display: flex; flex-direction: column; gap: 1rem; }
.notification-item {
    background-color: white; padding: 1.5rem; border-radius: 15px;
    display: flex; align-items: center; gap: 1.5rem;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    border-left: 5px solid transparent;
    transition: all 0.3s ease;
}
.notification-item.unread { border-left-color: var(--primary); background-color: #f0f5ff; }
.notification-item.read { opacity: 0.8; }
.notification-icon {
    font-size: 1.5rem; color: var(--primary);
    width: 50px; height: 50px; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    background-color: rgba(30, 64, 175, 0.1); border-radius: 50%;
}
.notification-content p { margin: 0; font-weight: 500; color: #333; }
.notification-date { font-size: 0.875rem; color: #6c757d; }
.no-notifications { text-align: center; padding: 3rem; color: #6c757d; }
.no-notifications i { font-size: 3rem; margin-bottom: 1rem; display: block; }
</style>

<?php require_once ROOT_PATH . '/app/views/partials/footer.php'; ?>