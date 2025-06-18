<?php // Fichier: app/views/admin/partials/reservations_table.php ?>

<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>Utilisateur</th>
                <th>Place</th>
                <th>Début</th>
                <th>Fin</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($reservations)): ?>
                <tr>
                    <td colspan="5" class="text-center">Aucune réservation trouvée pour ces critères.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($reservations as $reservation): ?>
                <tr>
                    <td><?= htmlspecialchars($reservation['prenom'] . ' ' . $reservation['nom']) ?></td>
                    <td><?= htmlspecialchars($reservation['spot_number']) ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($reservation['start_time'])) ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($reservation['end_time'])) ?></td>
                    <td>
                        <span class="status status-<?= $reservation['status'] ?>">
                            <?= ucfirst($reservation['status']) ?>
                        </span>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php if ($total_pages > 1): ?>
<nav class="pagination-container" aria-label="Pagination des réservations">
    <ul class="pagination">
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                <a class="page-link" href="#" data-page="<?= $i ?>"><?= $i ?></a>
            </li>
        <?php endfor; ?>
    </ul>
</nav>
<?php endif; ?>