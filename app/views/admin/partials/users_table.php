<?php // Fichier: app/views/admin/partials/users_table.php ?>

<?php if (empty($users)): ?>
    <div class="no-results-card">
        <p>Aucun utilisateur ne correspond aux filtres sélectionnés.</p>
    </div>
<?php else: ?>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Nom & Prénom</th>
                    <th>Email</th>
                    <th>Rôle</th>
                    <th>Inscrit le</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td>
                            <form action="<?= BASE_URL ?>/admin/update-user" method="POST" class="role-update-form">
                                <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                <!-- ===== DÉBUT DE LA MODIFICATION ===== -->
                                <div class="role-switch-container">
                                    <div class="form-switch">
                                        <input class="form-check-input" type="checkbox" name="is_admin"
                                               id="role-switch-<?= $user['id'] ?>"
                                               <?= $user['is_admin'] ? 'checked' : '' ?>
                                               <?= ($_SESSION['user_id'] == $user['id']) ? 'disabled' : '' ?>>
                                        <label class="form-check-label" for="role-switch-<?= $user['id'] ?>"></label>
                                    </div>
                                    <span class="role-text"><?= $user['is_admin'] ? 'Admin' : 'Utilisateur' ?></span>
                                </div>
                                <!-- ===== FIN DE LA MODIFICATION ===== -->
                            </form>
                        </td>
                        <td><?= date('d/m/Y', strtotime($user['created_at'])) ?></td>
                        <td class="actions-cell">
                            <?php if ($_SESSION['user_id'] != $user['id']): ?>
                            <form action="<?= BASE_URL ?>/admin/delete-user" method="POST" class="delete-user-form">
                                <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                <button type="submit" class="btn btn-danger btn-icon" title="Supprimer l'utilisateur">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php if ($total_pages > 1): ?>
<nav class="pagination-container" aria-label="Pagination des utilisateurs">
    <ul class="pagination">
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                <a class="page-link" href="#" data-page="<?= $i ?>"><?= $i ?></a>
            </li>
        <?php endfor; ?>
    </ul>
</nav>
<?php endif; ?>