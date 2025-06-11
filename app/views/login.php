<?php require_once ROOT_PATH . '/app/views/partials/header.php'; ?>

<div class="container auth-container">
    <h1>Connexion</h1>

    <?php 
    // Gestion des messages depuis l'URL
    if (isset($_GET['message'])) {
        switch ($_GET['message']) {
            case 'registered':
                echo '<p class="success-message" role="status">Votre compte a été créé avec succès ! Vous pouvez maintenant vous connecter.</p>';
                break;
            case 'disconnected':
                echo '<p class="info-message" role="status">Vous avez été déconnecté avec succès.</p>';
                break;
        }
    }
    ?>

    <?php if (isset($error)): ?>
        <p class="error-message" role="alert"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form action="/login/process" method="post" class="auth-form">
        <div class="form-group">
            <label for="email">Adresse email</label>
            <input type="email" id="email" name="email" placeholder="adresse.email@example.com" required 
                   aria-required="true" aria-describedby="email-error"
                   value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
            <span id="email-error" class="sr-only">Veuillez entrer une adresse email valide.</span>
        </div>
        <div class="form-group">
            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password" placeholder="Votre mot de passe" required
                   aria-required="true" aria-describedby="password-error">
            <span id="password-error" class="sr-only">Veuillez entrer votre mot de passe.</span>
        </div>
        
        <button type="submit" class="btn btn-primary">Connexion</button>
    </form>
    <div class="auth-links">
        <a href="/password/forgot">Mot de passe oublié ?</a>
        <a href="/signup">Pas encore de compte ? S'inscrire</a>
    </div>
</div>

<?php require_once ROOT_PATH . '/app/views/partials/footer.php'; ?>