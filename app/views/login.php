<?php require_once ROOT_PATH . '/app/views/partials/header.php'; ?>

<div class="auth-container">
    <div class="auth-card animate-fade-in">
        <div class="auth-header">
            <h1><i class="fas fa-sign-in-alt"></i> Connexion</h1>
            <p>Accédez à votre dashboard de parking intelligent</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i>
                <?= $error ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?= BASE_URL ?>/login/process" class="auth-form">
            <div class="form-group">
                <label for="email">
                    <i class="fas fa-envelope"></i>
                    Adresse email
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
                    Mot de passe
                </label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    class="form-control"
                    required 
                    placeholder="Votre mot de passe"
                >
            </div>

            <div class="form-group checkbox-group">
                <label class="checkbox-label">
                    <input type="checkbox" name="remember" id="remember">
                    <span class="checkmark"></span>
                    Se souvenir de moi
                </label>
            </div>

            <button type="submit" class="btn btn-primary btn-full">
                <i class="fas fa-sign-in-alt"></i>
                Se connecter
            </button>
        </form>

        <div class="auth-footer">
            <p>Pas encore de compte ? <a href="<?= BASE_URL ?>/signup">Créer un compte</a></p>
            <div class="demo-accounts">
                <h4>Comptes de démonstration :</h4>
                <p><strong>Admin :</strong> admin@isep.fr / admin123</p>
                <p><strong>Utilisateur :</strong> test@isep.fr / test123</p>
            </div>
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
    max-width: 450px;
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

.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 500;
    color:blanchedalmond ;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.form-control {
    width: 100%;
    padding: 0.75rem 1rem;
    background-color: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: var(--border-radius);
    color: black;
    font-size: 1rem;
    transition: var(--transition);
}

.form-control::placeholder {
    color: rgba(255, 255, 255, 0.5);
}

.form-control:focus {
    outline: none;
    border-color: white;
    box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.2);
}

.checkbox-group {
    display: flex;
    align-items: center;
}

.checkbox-label {
    display: flex !important;
    align-items: center;
    cursor: pointer;
    font-weight: normal !important;
    margin-bottom: 0 !important;
}

.checkbox-label input[type="checkbox"] {
    width: auto;
    margin-right: 0.5rem;
    accent-color: var(--primary);
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
    color: rgba(0, 0, 0, 0.8);
    margin-bottom: 1rem;
}

.auth-footer a {
    color: white;
    font-weight: 600;
    text-decoration: underline;
}

.demo-accounts {
    background-color: rgba(0, 0, 0, 0.2);
    padding: 1rem;
    border-radius: var(--border-radius);
    margin-top: 1rem;
}

.demo-accounts h4 {
    color: white;
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
}

.demo-accounts p {
    font-size: 0.85rem;
    margin: 0.25rem 0;
    color: rgba(255, 255, 255, 0.8);
}

@media (max-width: 480px) {
    .auth-card {
        padding: 1.5rem;
    }
    
    .auth-header h1 {
        font-size: 1.5rem;
    }
}
</style>

<?php require_once ROOT_PATH . '/app/views/partials/footer.php'; ?>