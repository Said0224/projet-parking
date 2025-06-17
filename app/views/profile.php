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
                        <label for="password" class="form-label">Nouveau mot de passe</label>
                        <input type="password" id="password" name="password" class="form-control" required minlength="6" placeholder="6 caractères minimum">
                    </div>
                    <div class="form-group">
                        <label for="confirm_password" class="form-label">Confirmer le mot de passe</label>
                        <input type="password" id="confirm_password" name="confirm_password" class="form-control" required placeholder="Répétez le mot de passe">
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-key"></i> Changer le mot de passe
                    </button>
                </form>
            </div>
        </div>

        <!-- Section 3: Zone de danger -->
        <div class="danger-zone">
            <div class="card-header">
                <h3><i class="fas fa-exclamation-triangle"></i> Zone de Danger</h3>
            </div>
            <div class="card-body">
                <p>La suppression de votre compte est une action irréversible. Toutes vos données, y compris vos réservations, seront définitivement effacées.</p>
                <form method="POST" action="<?= BASE_URL ?>/profile/delete-account" onsubmit="return confirm('Êtes-vous absolument certain de vouloir supprimer votre compte ? Cette action est irréversible.');">
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash-alt"></i> Supprimer mon compte
                    </button>
                </form>
            </div>
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
        display: block;
        margin-top: 0.5rem;
        font-size: 0.875rem;
        color: #6c757d; /* Couleur de texte secondaire */
    }

    .form-control:disabled {
        background-color: rgba(230, 230, 230, 0.7);
        cursor: not-allowed;
    }

    .danger-zone .card-header {
        background: linear-gradient(135deg, #b91c1c 0%, #dc2626 100%);
    }

    .danger-zone p {
        color: #495057;
        margin-bottom: 1.5rem;
        line-height: 1.7;
    }

    /* Responsive */
    @media (min-width: 992px) {
        .profile-layout {
            grid-template-columns: repeat(2, 1fr);
            grid-template-areas:
                "info security"
                "danger danger";
        }
        .profile-layout .card:nth-child(1) { grid-area: info; }
        .profile-layout .card:nth-child(2) { grid-area: security; }
        .profile-layout .danger-zone { grid-area: danger; }
    }
</style>

<?php require_once ROOT_PATH . '/app/views/partials/footer.php'; ?>