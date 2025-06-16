<?php require_once __DIR__ . '/../partials/header.php'; ?>
<?php require_once __DIR__ . '/../partials/navbar.php'; ?>

<main class="container">
    <h1>Notifications</h1>

    <?php if (isset($_SESSION['user_id'])): ?>
        <ul>
            <li>🔔 Votre réservation est confirmée.</li>
            <li>🔔 Un nouveau parking est disponible près de chez vous.</li>
            <li>🔔 N'oubliez pas votre créneau demain à 14h.</li>
        </ul>
    <?php else: ?>
        <p>Veuillez vous connecter pour voir vos notifications.</p>
        <a href="/login">Se connecter</a>
    <?php endif; ?>
</main>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
