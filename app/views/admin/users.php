<?php require_once ROOT_PATH . '/app/views/partials/header.php'; ?>

<!-- Ajouter le CSS admin -->
<link rel="stylesheet" href="/css/admin-style.css">

<div class="container">
    <div class="page-header">
        <h1><i class="fas fa-users"></i> Gestion des utilisateurs</h1>
        <a href="/admin" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour au dashboard
        </a>
    </div>

    <!-- Formulaire d'ajout d'utilisateur -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-user-plus"></i> Ajouter un nouvel utilisateur</h3>
        </div>
        <div class="card-body">
            <form action="/admin/create-user" method="POST">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email" class="form-label">Email *</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   placeholder="exemple@isep.fr" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="password" class="form-label">Mot de passe *</label>
                            <input type="password" class="form-control" id="password" name="password" 
                                   placeholder="Minimum 6 caractères" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nom" class="form-label">Nom *</label>
                            <input type="text" class="form-control" id="nom" name="nom" 
                                   placeholder="Nom de famille" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="prenom" class="form-label">Prénom *</label>
                            <input type="text" class="form-control" id="prenom" name="prenom" 
                                   placeholder="Prénom" required>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_admin" name="is_admin">
                            <label class="form-check-label" for="is_admin">
                                <strong>Droits d'administrateur</strong>
                            </label>
                        </div>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Créer l'utilisateur
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des utilisateurs -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-list"></i> Liste des utilisateurs (<?= count($users) ?>)</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Email</th>
                            <th>Nom complet</th>
                            <th>Statut</th>
                            <th>Créé le</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                        <tr>
                            <td><strong>#<?= $user['id'] ?></strong></td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td><?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']) ?></td>
                            <td>
                                <form action="/admin/update-user" method="POST" style="display: inline;">
                                    <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                    <div class="form-switch">
                                        <input class="form-check-input" type="checkbox" name="is_admin" 
                                               <?= $user['is_admin'] ? 'checked' : '' ?>
                                               onchange="this.form.submit()">
                                        <label class="form-check-label">
                                            <?= $user['is_admin'] ? '<strong style="color: #28a745;">Admin</strong>' : 'Utilisateur' ?>
                                        </label>
                                    </div>
                                </form>
                            </td>
                            <td><?= date('d/m/Y', strtotime($user['created_at'])) ?></td>
                            <td>
                                <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                <form action="/admin/delete-user" method="POST" style="display: inline;" 
                                      onsubmit="return confirm('⚠️ Êtes-vous sûr de vouloir supprimer cet utilisateur ?\n\nCette action est irréversible.')">
                                    <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                    <button type="submit" class="btn btn-danger btn-sm" title="Supprimer l'utilisateur">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                <?php else: ?>
                                <span class="text-muted"><i class="fas fa-user-shield"></i> Vous</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once ROOT_PATH . '/app/views/partials/footer.php'; ?>