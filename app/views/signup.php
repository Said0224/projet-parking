<?php require_once ROOT_PATH . '/app/views/partials/header.php'; ?>

<div class="auth-container">
    <div class="auth-card animate-fade-in">
        <div class="auth-header">
            <h1><i class="fas fa-user-plus"></i> Inscription</h1>
            <p>Créez votre compte pour accéder au parking intelligent</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i>
                <?= $error ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <?= $success ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?= BASE_URL ?>/signup/process" class="auth-form">
            <div class="form-row">
                <div class="form-group">
                    <label for="prenom">
                        <i class="fas fa-user"></i>
                        Prénom *
                    </label>
                    <input 
                        type="text" 
                        id="prenom" 
                        name="prenom" 
                        class="form-control"
                        required 
                        placeholder="Votre prénom"
                        value="<?= htmlspecialchars($_POST['prenom'] ?? '') ?>"
                    >
                </div>

                <div class="form-group">
                    <label for="nom">
                        <i class="fas fa-user"></i>
                        Nom *
                    </label>
                    <input 
                        type="text" 
                        id="nom" 
                        name="nom" 
                        class="form-control"
                        required 
                        placeholder="Votre nom"
                        value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>"
                    >
                </div>
            </div>

            <div class="form-group">
                <label for="email">
                    <i class="fas fa-envelope"></i>
                    Adresse email *
                </label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    class="form-control"
                    required 
                    placeholder="votre.email@isep.fr"
                    value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                >
            </div>

            <div class="form-group">
                <label for="password">
                    <i class="fas fa-lock"></i>
                    Mot de passe *
                </label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    class="form-control"
                    required 
                    placeholder="Au moins 6 caractères"
                    minlength="6"
                >
                <div class="password-strength" id="passwordStrength"></div>
            </div>

            <div class="form-group">
                <label for="confirm_password">
                    <i class="fas fa-lock"></i>
                    Confirmer le mot de passe *
                </label>
                <input 
                    type="password" 
                    id="confirm_password" 
                    name="confirm_password" 
                    class="form-control"
                    required 
                    placeholder="Répétez votre mot de passe"
                >
                <div class="password-match" id="passwordMatch"></div>
            </div>

            <button type="submit" class="btn btn-primary btn-full">
                <i class="fas fa-user-plus"></i>
                Créer mon compte
            </button>
        </form>

        <div class="auth-footer">
            <p>Déjà un compte ? <a href="<?= BASE_URL ?>/login">Se connecter</a></p>
        </div>
    </div>
</div>

<style>
.auth-container {
    min-height: calc(100vh - 150px);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2rem 1rem;
}

.auth-card {
    background-color: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border-radius: var(--border-radius);
    box-shadow: var(--shadow-lg);
    padding: 2.5rem;
    width: 100%;
    max-width: 500px;
    color: white;
}

.auth-header {
    text-align: center;
    margin-bottom: 2rem;
}

.auth-header h1 {
    color: white;
    font-size: 2rem;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
}

.auth-header p {
    color: rgba(255, 255, 255, 0.8);
}

.auth-form {
    margin-bottom: 2rem;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 500;
    color: white;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.form-control {
    width: 100%;
    padding: 0.75rem 1rem;
    background-color: rgba(255, 255, 255, 0.9);
    border: 1px solid rgba(176, 187, 248, 0.2);
    border-radius: var(--border-radius);
    color: black;
    font-size: 1rem;
    transition: var(--transition);
}

.form-control::placeholder {
    color: rgba(134, 129, 129, 0.5);
}

.form-control:focus {
    outline: none;
    border-color: white;
    box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.5);
}

.password-strength, .password-match {
    margin-top: 0.5rem;
    font-size: 0.85rem;
    height: 20px;
}

.password-strength.weak {
    color: #f56565;
}

.password-strength.medium {
    color: #ecc94b;
}

.password-strength.strong {
    color: #48bb78;
}

.password-match.match {
    color: #48bb78;
}

.password-match.no-match {
    color: #f56565;
}

.btn-primary {
    background: linear-gradient(45deg, var(--primary), var(--secondary));
    border: none;
    color: white;
    padding: 0.75rem 1.5rem;
    border-radius: var(--border-radius);
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition);
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.btn-primary:hover {
    background: linear-gradient(45deg, var(--primary-dark), var(--secondary-dark));
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
}

.auth-footer {
    text-align: center;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    padding-top: 1.5rem;
}

.auth-footer p {
    color: rgba(255, 255, 255, 0.8);
}

.auth-footer a {
    color: white;
    font-weight: 600;
    text-decoration: underline;
}

@media (max-width: 480px) {
    .auth-card {
        padding: 1.5rem;
    }
    
    .form-row {
        grid-template-columns: 1fr;
    }
    
    .auth-header h1 {
        font-size: 1.5rem;
    }
}
</style>

<script>
// Validation en temps réel du mot de passe
document.getElementById('password').addEventListener('input', function() {
    const password = this.value;
    const strengthDiv = document.getElementById('passwordStrength');
    
    if (password.length === 0) {
        strengthDiv.textContent = '';
        strengthDiv.className = 'password-strength';
        return;
    }
    
    let strength = 0;
    if (password.length >= 6) strength++;
    if (password.match(/[a-z]/)) strength++;
    if (password.match(/[A-Z]/)) strength++;
    if (password.match(/[0-9]/)) strength++;
    if (password.match(/[^a-zA-Z0-9]/)) strength++;
    
    switch (strength) {
        case 0:
        case 1:
            strengthDiv.textContent = 'Mot de passe faible';
            strengthDiv.className = 'password-strength weak';
            break;
        case 2:
        case 3:
            strengthDiv.textContent = 'Mot de passe moyen';
            strengthDiv.className = 'password-strength medium';
            break;
        case 4:
        case 5:
            strengthDiv.textContent = 'Mot de passe fort';
            strengthDiv.className = 'password-strength strong';
            break;
    }
});

// Vérification de la correspondance des mots de passe
document.getElementById('confirm_password').addEventListener('input', function() {
    const password = document.getElementById('password').value;
    const confirmPassword = this.value;
    const matchDiv = document.getElementById('passwordMatch');
    
    if (confirmPassword.length === 0) {
        matchDiv.textContent = '';
        matchDiv.className = 'password-match';
        return;
    }
    
    if (password === confirmPassword) {
        matchDiv.textContent = '✓ Les mots de passe correspondent';
        matchDiv.className = 'password-match match';
    } else {
        matchDiv.textContent = '✗ Les mots de passe ne correspondent pas';
        matchDiv.className = 'password-match no-match';
    }
});
</script>

<?php require_once ROOT_PATH . '/app/views/partials/footer.php'; ?>