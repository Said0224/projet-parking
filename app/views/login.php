<?php require_once 'partials/header.php'; ?>

<div class="container auth-container">
    <h1>Connexion</h1>

    <?php if (isset($error)): ?>
        <p class="error-message"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form action="/login/process" method="post" class="auth-form">
        <div class="form-group">
            <label for="email">Adresse email</label>
            <input type="email" id="email" name="email" placeholder="adresse.email@example.com" required 
                   aria-required="true" aria-describedby="email-error">
            <span id="email-error" class="sr-only">Veuillez entrer une adresse email valide.</span>
        </div>
        <div class="form-group">
            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password" placeholder="Votre mot de passe" required
                   aria-required="true" aria-describedby="password-error">
            <span id="password-error" class="sr-only">Veuillez entrer votre mot de passe.</span>
        </div>
        <!-- 
        <div class="form-group checkbox-group">
            <input type="checkbox" id="remember_me" name="remember_me">
            <label for="remember_me">Se souvenir de moi</label>
        </div>
        -->
        <button type="submit" class="btn btn-primary">Connexion</button>
    </form>
    <div class="auth-links">
        <a href="/password/forgot">Mot de passe oublié ?</a>
        <a href="/signup">Pas encore de compte ? S'inscrire</a>
    </div>
</div>

<?php require_once 'partials/footer.php'; ?>