<?php require_once ROOT_PATH . '/app/views/partials/header.php'; ?>

<div class="container" style="padding-top: 2rem; padding-bottom: 2rem;">
    <div class="auth-card animate-fade-in" style="max-width: 800px;">
        <div class="auth-header">
            <h1><i class="fas fa-bell"></i> Mes Notifications</h1>
            <p>Retrouvez ici l'historique de vos activités.</p>
        </div>

        <div class="notifications-list">
            <?php if (empty($notifications)): ?>
                <div class="alert alert-info" style="text-align: center;">
                    <i class="fas fa-info-circle"></i> Vous n'avez aucune notification pour le moment.
                </div>
            <?php else: ?>
                <?php foreach ($notifications as $notification): ?>
                    <div class="notification-item <?= !$notification['is_read'] ? 'unread' : '' ?>">
                        <div class="notification-icon">
                            <i class="fas fa-parking"></i>
                        </div>
                        <div class="notification-content">
                            <div class="notification-header">
                                <h4 class="notification-title"><?= htmlspecialchars($notification['title']) ?></h4>
                                <span class="notification-date"><?= date('d/m/Y à H:i', strtotime($notification['created_at'])) ?></span>
                            </div>
                            <p class="notification-message"><?= htmlspecialchars($notification['message']) ?></p>
                            <?php if (!empty($notification['link'])): ?>
                                <a href="<?= BASE_URL . $notification['link'] ?>" class="notification-link">Voir les détails →</a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.notifications-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.notification-item {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    padding: 1rem;
    background-color: rgba(0, 0, 0, 0.1);
    border-radius: var(--border-radius);
    border-left: 4px solid var(--primary);
    color: white;
}

.notification-item.unread {
    background-color: rgba(102, 126, 234, 0.2); /* Un peu plus visible pour les non-lus */
    border-left-color: white;
}

.notification-icon {
    font-size: 1.5rem;
    color: var(--primary);
    padding-top: 0.25rem;
}

.notification-content {
    flex-grow: 1;
}

.notification-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.5rem;
}

.notification-title {
    margin: 0;
    font-size: 1.1rem;
    color: white;
}

.notification-date {
    font-size: 0.8rem;
    color: rgba(255, 255, 255, 0.7);
}

.notification-message {
    margin: 0;
    color: rgba(255, 255, 255, 0.9);
}

.notification-link {
    display: inline-block;
    margin-top: 0.5rem;
    font-size: 0.9rem;
    font-weight: 600;
    color: white;
    text-decoration: underline;
}
</style>

<?php require_once ROOT_PATH . '/app/views/partials/footer.php'; ?>
