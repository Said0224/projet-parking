<?php require_once ROOT_PATH . '/app/views/partials/header.php'; ?>

<div class="container">
    <div class="page-header">
        <h1><i class="fas fa-user-circle"></i> Mon Profil</h1>
    </div>

    <?php if (!empty($success)): ?>
        <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="profile-layout">
        <!-- Section 1: Informations personnelles -->
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-id-card"></i> Informations personnelles</h3>
            </div>
            <div class="card-body">
                <form method="POST" action="<?= BASE_URL ?>/profile/update">
                    <div class="form-group">
                        <label for="prenom" class="form-label">Prénom</label>
                        <input type="text" id="prenom" name="prenom" class="form-control" value="<?= htmlspecialchars($user['prenom']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="nom" class="form-label">Nom</label>
                        <input type="text" id="nom" name="nom" class="form-control" value="<?= htmlspecialchars($user['nom']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="email" class="form-label">Adresse email</label>
                        <input type="email" id="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" disabled>
                        <small class="form-text">L'adresse email ne peut pas être modifiée.</small>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Enregistrer les modifications
                    </button>
                </form>
            </div>
        </div>

        <!-- Section 2: Sécurité -->
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-shield-alt"></i> Sécurité</h3>
            </div>
            <div class="card-body">
                <form method="POST" action="<?= BASE_URL ?>/profile/change-password">
                    <div class="form-group">
                        <label for="current_password" class="form-label">Ancien mot de passe</label>
                        <input type="password" id="current_password" name="current_password" class="form-control" required placeholder="Votre mot de passe actuel">
                    </div>
                    <div class="form-group">
                        <label for="password" class="form-label">Nouveau mot de passe</label>
                        <input type="password" id="password" name="password" class="form-control" required minlength="6" placeholder="6 caractères minimum">
                    </div>
                    <div class="form-group">
                        <label for="confirm_password" class="form-label">Confirmer le nouveau mot de passe</label>
                        <input type="password" id="confirm_password" name="confirm_password" class="form-control" required placeholder="Répétez le nouveau mot de passe">
                        <div class="password-match" id="passwordMatchFeedback"></div>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-key"></i> Changer le mot de passe
                    </button>
                </form>
            </div>
        </div>

        <!-- Section 3: Zone de danger -->
        <div class="card danger-zone-trigger">
            <div class="card-header">
                <h3><i class="fas fa-exclamation-triangle"></i> Zone de Danger</h3>
            </div>
            <div class="card-body">
                <p>Actions irréversibles concernant votre compte.</p>
                <button type="button" id="open-delete-modal-btn" class="btn btn-danger">
                    <i class="fas fa-trash-alt"></i> Supprimer mon compte
                </button>
            </div>
        </div>
    </div>
</div>

<!-- MODALE DE CONFIRMATION DE SUPPRESSION (MODIFIÉE) -->
<div id="delete-account-modal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <h2><i class="fas fa-exclamation-triangle"></i> Confirmation requise</h2>
            <button class="close-modal-btn">×</button>
        </div>
        <div class="modal-body">
            <p>Êtes-vous absolument certain de vouloir supprimer votre compte ?</p>
            <p><strong>Cette action est définitive et irréversible.</strong> Toutes vos données, y compris l'historique de vos réservations, seront effacées.</p>
        </div>
        <div class="modal-footer">
            <!-- CHANGEMENT DE CLASSE ICI: de btn-secondary à btn-light -->
            <button type="button" class="btn btn-light close-modal-btn">Annuler</button>
            <form method="POST" action="<?= BASE_URL ?>/profile/delete-account" style="display:inline;">
                <button type="submit" class="btn btn-danger">
                    Oui, supprimer mon compte
                </button>
            </form>
        </div>
    </div>
</div>


<style>
    /* Styles spécifiques pour la page de profil */
    .profile-layout {
        display: grid;
        grid-template-columns: 1fr;
        gap: 2rem;
    }
    .form-text {
        display: block; margin-top: 0.5rem; font-size: 0.875rem; color: #6c757d;
    }
    .form-control:disabled {
        background-color: rgba(230, 230, 230, 0.7); cursor: not-allowed;
    }
    
    .danger-zone-trigger .card-header {
        background: linear-gradient(135deg, 
            #b91c1c, 
            #dc2626, 
            #ef4444,
            #dc2626, 
            #b91c1c
        );
        background-size: 300% 100%;
        animation: wave-animation 4s ease-in-out infinite;
    }

    @keyframes wave-animation {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }

    .danger-zone-trigger p {
        color: #495057; margin-bottom: 1.5rem; line-height: 1.7;
    }

    /* Styles pour la modale */
    .modal-overlay {
        position: fixed; top: 0; left: 0; width: 100%; height: 100%;
        background-color: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(5px);
        display: none;
        align-items: center; justify-content: center; z-index: 1000;
        opacity: 0;
        transition: opacity 0.3s ease-in-out;
    }
    .modal-overlay.show {
        display: flex; opacity: 1;
    }
    .modal-content {
        background: white; border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        width: 90%; max-width: 500px;
        overflow: hidden;
        transform: translateY(20px);
        transition: transform 0.3s ease-in-out;
    }
    .modal-overlay.show .modal-content {
        transform: translateY(0);
    }
    .modal-header {
        padding: 1.5rem;
        background: linear-gradient(135deg, #b91c1c 0%, #dc2626 100%);
        color: white;
        display: flex; justify-content: space-between; align-items: center;
    }
    .modal-header h2 { margin: 0; font-size: 1.5rem; }
    .close-modal-btn {
        background: none; border: none; font-size: 2rem; color: white; cursor: pointer; opacity: 0.8;
        line-height: 1; 
    }
    .close-modal-btn:hover { opacity: 1; }
    .modal-body { padding: 2rem; }
    .modal-body p { margin-bottom: 1rem; color: #333; }
    .modal-footer {
        padding: 1.5rem; background-color: #f7f7f7; display: flex; justify-content: flex-end; gap: 1rem;
    }
    
    .password-match {
        margin-top: 0.5rem;
        font-size: 0.85rem;
        height: 20px;
        font-weight: 500;
        transition: color 0.3s;
    }
    .password-match.match {
        color: #155724; 
    }
    .password-match.no-match {
        color: #721c24;
    }

    /* Responsive */
    @media (min-width: 992px) {
        .profile-layout {
            grid-template-columns: repeat(2, 1fr);
            grid-template-areas: "info security" "danger danger";
        }
        .profile-layout .card:nth-child(1) { grid-area: info; }
        .profile-layout .card:nth-child(2) { grid-area: security; }
        .profile-layout .danger-zone-trigger { grid-area: danger; }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modalOverlay = document.getElementById('delete-account-modal');
    const openModalBtn = document.getElementById('open-delete-modal-btn');
    const closeModalBtns = document.querySelectorAll('.close-modal-btn');

    if (openModalBtn) {
        openModalBtn.addEventListener('click', () => modalOverlay.classList.add('show'));
    }
    closeModalBtns.forEach(btn => btn.addEventListener('click', () => modalOverlay.classList.remove('show')));
    if(modalOverlay) {
        modalOverlay.addEventListener('click', e => { if (e.target === modalOverlay) modalOverlay.classList.remove('show'); });
    }

    const newPasswordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirm_password');
    const feedbackDiv = document.getElementById('passwordMatchFeedback');

    function checkPasswordMatch() {
        const password = newPasswordInput.value;
        const confirmPassword = confirmPasswordInput.value;

        if (confirmPassword.length === 0 && password.length === 0) {
            feedbackDiv.textContent = '';
            feedbackDiv.className = 'password-match';
            return;
        }

        if (password === confirmPassword) {
            feedbackDiv.textContent = '✓ Les mots de passe correspondent';
            feedbackDiv.className = 'password-match match';
        } else {
            feedbackDiv.textContent = '✗ Les mots de passe ne correspondent pas';
            feedbackDiv.className = 'password-match no-match';
        }
    }

    if (newPasswordInput && confirmPasswordInput) {
        newPasswordInput.addEventListener('input', checkPasswordMatch);
        confirmPasswordInput.addEventListener('input', checkPasswordMatch);
    }
});
</script>

<?php require_once ROOT_PATH . '/app/views/partials/footer.php'; ?>