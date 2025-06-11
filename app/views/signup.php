<?php require_once 'partials/header.php'; ?>

<div class="container auth-container">
    <h1>Inscription</h1>

    <?php if (isset($error)): ?>
        <p class="error-message" role="alert"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <?php if (isset($success)): ?>
        <p class="success-message" role="status"><?= htmlspecialchars($success) ?></p>
    <?php endif; ?>

    <form action="/signup/process" method="post" class="auth-form">
        <div class="form-group">
            <label for="email">Adresse email</label>
            <input type="email" id="email" name="email" placeholder="adresse.email@example.com" required 
                   aria-required="true" aria-describedby="email-hint">
            <span id="email-hint" class="sr-only">Veuillez entrer une adresse email valide.</span>
        </div>
        <div class="form-group">
            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password" placeholder="Minimum 8 caractères" required
                   aria-required="true" aria-describedby="password-hint">
            <span id="password-hint" class="sr-only">Le mot de passe doit contenir au moins 8 caractères.</span>
        </div>
        <div class="form-group">
            <label for="confirm_password">Confirmer le mot de passe</label>
            <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirmer votre mot de passe" required
                   aria-required="true" aria-describedby="confirm-password-hint">
            <span id="confirm-password-hint" class="sr-only">Veuillez confirmer votre mot de passe.</span>
        </div>
        
        <button type="submit" class="btn btn-primary">S'inscrire</button>
    </form>
    <div class="auth-links">
        <a href="/login">Déjà un compte ? Se connecter</a>
    </div>
</div>

<?php require_once 'partials/footer.php'; ?>