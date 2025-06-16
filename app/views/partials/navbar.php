<nav class="navbar" role="navigation" aria-label="Navigation principale">
    <a href="/" class="nav-brand">🚗 Parking Intelligent</a>
    <ul class="nav-links">
        <li><a href="/" <?= ($_SERVER['REQUEST_URI'] == '/') ? 'aria-current="page"' : '' ?>>Accueil</a></li>

        <?php if (isset($_SESSION['user_id'])): ?>
            <!-- Utilisateur connecté -->
            <li><a href="/dashboard" <?= (strpos($_SERVER['REQUEST_URI'], '/dashboard') === 0) ? 'aria-current="page"' : '' ?>>Tableau de Bord</a></li>
            <li><a href="/profile" <?= (strpos($_SERVER['REQUEST_URI'], '/profile') === 0) ? 'aria-current="page"' : '' ?>>Profil</a></li>
            <li><a href="/notifications" <?= (strpos($_SERVER['REQUEST_URI'], '/notifications') === 0) ? 'aria-current="page"' : '' ?>>🔔 Notifications</a></li>
            <li><a href="/logout">Déconnexion</a></li>
            <li><span class="user-info">👤 <?= htmlspecialchars($_SESSION['user_email']) ?></span></li>
        <?php else: ?>
            <!-- Utilisateur non connecté -->
            <li><a href="/login" <?= (strpos($_SERVER['REQUEST_URI'], '/login') === 0) ? 'aria-current="page"' : '' ?>>Connexion</a></li>
            <li><a href="/signup" <?= (strpos($_SERVER['REQUEST_URI'], '/signup') === 0) ? 'aria-current="page"' : '' ?>>Inscription</a></li>
        <?php endif; ?>
    </ul>
</nav>
<!--