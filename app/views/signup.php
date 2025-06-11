<?php require_once ROOT_PATH . '/app/views/partials/header.php'; ?>

<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h1><i class="fas fa-user-plus"></i> Inscription</h1>
            <p>Créez votre compte pour accéder au parking intelligent</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert alert-error">
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

        <form method="POST" action="/signup/process" class="auth-form">
            <div class="form-row">
                <div class="form-group">
                    <label for="prenom">
                        <i class="fas fa-user"></i>
                        Prénom
                    </label>
                    <input 
                        type="text" 
                        id="prenom" 
                        name="prenom" 
                        required 
                        placeholder="Votre prénom"
                        value="<?= htmlspecialchars($_POST['prenom'] ?? '') ?>"
                    >
                </div>

                <div class="form-group">
                    <label for="nom">
                        <i class="fas fa-user"></i>
                        Nom
                    </label>
                    <input 
                        type="text" 
                        id="nom" 
                        name="nom" 
                        required 
                        placeholder="Votre nom"
                        value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>"
                    >
                </div>
            </div>

            <div class="form-group">
                <label for="email">
                    <i class="fas fa-envelope"></i>
                    Adresse email
                </label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    required 
                    placeholder="votre.email@isep.fr"
                    value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                >
            </div>

            <div class="form-group">
                <label for="password">
                    <i class="fas fa-lock"></i>
                    Mot de passe
                </label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    required 
                    placeholder="Au moins 6 caractères"
                    minlength="6"
                >
                <div class="password-strength" id="passwordStrength"></div>
            </div>

            <div class="form-group">
                <label for="confirm_password">
                    <i class="fas fa-lock"></i>
                    Confirmer le mot de passe
                </label>
                <input 
                    type="password" 
                    id="confirm_password" 
                    name="confirm_password" 
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
            <p>Déjà un compte ? <a href="/login">Se connecter</a></p>
        </div>
    </div>
</div>

<style>
.auth-container {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 20px;
}

.auth-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.1);
    padding: 40px;
    width: 100%;
    max-width: 500px;
    animation: slideUp 0.6s ease;
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.auth-header {
    text-align: center;
    margin-bottom: 30px;
}

.auth-header h1 {
    color: #333;
    font-size: 2rem;
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
}

.auth-header p {
    color: #666;
    font-size: 1rem;
}

.alert {
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.alert-error {
    background-color: rgba(220, 53, 69, 0.1);
    border-left: 4px solid #dc3545;
    color: #721c24;
}

.alert-success {
    background-color: rgba(40, 167, 69, 0.1);
    border-left: 4px solid #28a745;
    color: #155724;
}

.auth-form {
    margin-bottom: 30px;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #333;
    display: flex;
    align-items: center;
    gap: 8px;
}

.form-group input {
    width: 100%;
    padding: 12px 15px;
    border: 2px solid #e1e5e9;
    border-radius: 8px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: #f8f9fa;
}

.form-group input:focus {
    outline: none;
    border-color: #667eea;
    background: white;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.password-strength, .password-match {
    margin-top: 5px;
    font-size: 0.85rem;
    height: 20px;
}

.password-strength.weak {
    color: #dc3545;
}

.password-strength.medium {
    color: #ffc107;
}

.password-strength.strong {
    color: #28a745;
}

.password-match.match {
    color: #28a745;
}

.password-match.no-match {
    color: #dc3545;
}

.btn {
    padding: 12px 24px;
    border: none;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.btn-primary {
    background: linear-gradient(45deg, #667eea, #764ba2);
    color: white;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
}

.btn-full {
    width: 100%;
}

.auth-footer {
    text-align: center;
    border-top: 1px solid #e1e5e9;
    padding-top: 20px;
}

.auth-footer p {
    color: #666;
}

.auth-footer a {
    color: #667eea;
    text-decoration: none;
    font-weight: 600;
}

.auth-footer a:hover {
    text-decoration: underline;
}

@media (max-width: 480px) {
    .auth-card {
        padding: 30px 20px;
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