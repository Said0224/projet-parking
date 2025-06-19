<?php require_once ROOT_PATH . '/app/views/partials/header.php'; ?>

<!-- Conteneur pour les notifications AJAX -->
<div id="ajax-notification" class="notification-container"></div>

<div class="container">

    <!-- ========================================================== -->
    <!-- BLOC 1 MODIFIÉ : EN-TÊTE "MON DASHBOARD" ESTHÉTIQUE -->
    <!-- ========================================================== -->
    <div class="user-header-new">
        <h1><i class="fas fa-clock"></i> Mon Dashboard</h1>
        <p>Bienvenue, <?= htmlspecialchars($_SESSION['user_prenom'] ?? 'Utilisateur') ?> ! Gérez votre parking en toute simplicité.</p>
    </div>

    <!-- ========================================================== -->
    <!-- BLOC 2 MODIFIÉ : CARTE D'ACTION "GÉRER MES RÉSERVATIONS" -->
    <!-- ========================================================== -->
    <div class="dashboard-actions-new">
        <div class="action-card-new">
            <div class="action-card-new-content">
                <div class="action-card-new-icon">
                    <i class="fas fa-tasks"></i>
                </div>
                <div class="action-card-new-text">
                    <h3>Gestion des réservations</h3>
                    <p>Consultez l'historique, suivez vos réservations actives et planifiez vos prochaines visites.</p>
                </div>
            </div>
            <div class="action-card-new-footer">
                <a href="<?= BASE_URL ?>/user/parking" class="btn btn-primary">Voir mes réservations</a>
            </div>
        </div>
    </div>


    <!-- ========================================================== -->
    <!-- SECTION 3D (INCHANGÉE) -->
    <!-- ========================================================== -->
    <div class="parking-status-section">
        <h2>État des places en temps réel</h2>
        
        <div class="dashboard-main-3d">
            <!-- Colonne de gauche: contrôles -->
            <div class="parking-controls">
                <div class="floor-switcher">
                    <h3>Étages</h3>
                </div>
            </div>

            <!-- Colonne centrale: la vue 3D -->
            <div class="parking-view-container">
                <div class="parking-perspective">
                    <?php
                    function render_spot_3d($spot) {
                        if (!is_array($spot) || !isset($spot['spot_number'])) {
                            echo "<div class='parking-spot-3d maintenance' data-id='0'><div class='spot-top'><span class='spot-number-3d'>?</span></div></div>";
                            return;
                        }
                        $status = htmlspecialchars($spot['status']); $id = $spot['id']; $userId = $spot['user_id'] ?? 0; $number = htmlspecialchars($spot['spot_number']); $reservationId = $spot['reservation_id'] ?? 0;
                        echo "<div class='parking-spot-3d {$status}' data-id='{$id}' data-number='{$number}' data-user-id='{$userId}' data-reservation-id='{$reservationId}'><div class='spot-face front'></div><div class='spot-top'><span class='spot-number-3d'>{$number}</span></div><div class='spot-face left'></div><div class='spot-face right'></div></div>";
                    }

                    if (isset($spotsByEtage) && is_array($spotsByEtage) && !empty($spotsByEtage)) {
                        foreach ($spotsByEtage as $etage => $spots) {
                            $placeMap = [];
                            foreach ($spots as $spot) { $placeMap[$spot['spot_number']] = $spot; }
                    ?>
                        <div class="parking-floor-3d" id="floor-<?= $etage ?>" data-floor="<?= $etage ?>" style="display: none;">
                            <?php
                            if ($etage == 1) {
                                $layout_etage_1 = ['101', '102', '103', 'pillar', '104', '105', '106', 'crossing', 'pillar', '107', '108', '109', 'pillar', '110', '111', '112', 'pillar', '113', '114', '115', 'aisle', '116', '117', '118', 'pillar', '119', '120', '121', 'crossing', 'pillar', '122', '123', '124', 'pillar', '125', '126', '127', 'pillar', 'special-zone', null];
                                foreach ($layout_etage_1 as $item) {
                                    if (in_array($item, ['pillar', 'crossing', 'special-zone', 'aisle'])) {
                                        $style = ''; if ($item === 'crossing' || $item === 'special-zone') $style = "style='grid-column: span 2;'"; if ($item === 'aisle') $style = "style='grid-column: 1 / -1;'"; $content = ($item === 'special-zone') ? '<span>VÉLO &<br>ASCENSEUR</span>' : '';
                                        echo "<div class='parking-structure {$item}' {$style}>{$content}</div>";
                                    } elseif ($item !== null) { render_spot_3d($placeMap[$item] ?? ['spot_number' => $item, 'status' => 'maintenance', 'id' => 0]);
                                    } else { echo "<div></div>"; }
                                }
                            } else { foreach ($spots as $spot) { render_spot_3d($spot); } }
                            ?>
                        </div>
                    <?php } } else { echo "<p style='color:white; font-size: 1.2rem; text-align:center;'>Aucune place de parking trouvée.</p>"; } ?>
                </div>
            </div>

            <!-- Colonne de droite: panneau de détails -->
            <div class="details-panel-wrapper">
                <div id="detailsPanel">
                    <p class="placeholder">Sélectionnez une place pour voir les détails.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Section "Mes réservations" (INCHANGÉE) -->
    <div class="my-reservations">
        <h2>Mes réservations</h2>
        <div id="reservations-table-container">
            <?php if (empty($userReservations)): ?>
                <p id="no-reservations-message" class="no-reservations">Vous n'avez aucune réservation active.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table">
                         <thead><tr><th>Place</th><th>Début</th><th>Fin</th><th>Prix/h</th><th>Statut</th><th>Actions</th></tr></thead>
                        <tbody>
                            <?php foreach ($userReservations as $reservation): ?>
                            <tr id="reservation-row-<?= $reservation['id'] ?>">
                                <td><strong><?= htmlspecialchars($reservation['spot_number']) ?></strong><?php if ($reservation['has_charging_station']): ?><i class="fas fa-charging-station text-success" title="Borne de recharge"></i><?php endif; ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($reservation['start_time'])) ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($reservation['end_time'])) ?></td>
                                <td><?= number_format($reservation['price_per_hour'], 2) ?>€</td>
                                <td><span class="status status-<?= $reservation['status'] ?>"><?= ucfirst($reservation['status']) ?></span></td>
                                <td><?php if ($reservation['status'] == 'active' && strtotime($reservation['start_time']) > time()): ?><form action="<?= BASE_URL ?>/user/cancel-reservation" method="POST" class="cancel-reservation-form" style="display: inline;"><input type="hidden" name="reservation_id" value="<?= $reservation['id'] ?>"><button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-times"></i> Annuler</button></form><?php endif; ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal de réservation (INCHANGÉ) -->
<div id="reservationModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeReservationModal()">×</span>
        <h2>Réserver la place <span id="modalSpotNumber"></span></h2>
        <form id="reservation-form" onsubmit="handleReservationSubmit(event)">
            <input type="hidden" id="modalSpotId" name="spot_id">
            <div class="form-group"><label for="start_time">Début</label><input type="datetime-local" id="start_time" name="start_time" class="form-control" required></div>
            <div class="form-group"><label for="end_time">Fin</label><input type="datetime-local" id="end_time" name="end_time" class="form-control" required></div>
            <div class="form-group"><p>Prix: <span id="modalPrice"></span>€/heure</p></div>
            <div class="modal-actions">
                <button type="button" class="btn btn-secondary" onclick="closeReservationModal()">Fermer</button>
                <button type="submit" class="btn btn-primary">Confirmer</button>
            </div>
        </form>
    </div>
</div>

<!-- ============================================= -->
<!-- ========== STYLES & JAVASCRIPT ========== -->
<!-- ============================================= -->
<style>
/* NOUVEAUX STYLES SPÉCIFIQUES POUR LES ÉLÉMENTS REDESSINÉS */
.user-header-new {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(15px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.1);
    border-radius: 20px;
    padding: 2.5rem 2rem;
    text-align: center;
    color: white;
    margin-bottom: 2rem;
}
.user-header-new h1 {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 0.75rem;
    text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
}
.user-header-new h1 i {
    color: #ffd700;
    margin-right: 1rem;
}
.user-header-new p {
    font-size: 1.1rem;
    opacity: 0.9;
    max-width: 600px;
    margin: 0 auto;
}

.dashboard-actions-new {
    margin-bottom: 3rem;
}

.action-card-new {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(15px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 20px;
    box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.1);
    padding: 1.5rem;
    display: flex;
    flex-direction: column;
    transition: all 0.3s ease;
}
.action-card-new:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
}

.action-card-new-content {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    flex-grow: 1;
}

.action-card-new-icon {
    flex-shrink: 0;
    background: linear-gradient(145deg, #1e40af, #3b82f6);
    color: white;
    width: 70px;
    height: 70px;
    border-radius: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.2rem;
    box-shadow: 0 8px 20px rgba(30, 64, 175, 0.3);
}

.action-card-new-text {
    text-align: left;
    color: white;
}
.action-card-new-text h3 {
    margin: 0 0 0.5rem 0;
    font-size: 1.5rem;
    font-weight: 600;
}
.action-card-new-text p {
    margin: 0;
    opacity: 0.8;
    line-height: 1.5;
}

.action-card-new-footer {
    padding-top: 1.5rem;
    margin-top: 1.5rem;
    border-top: 1px solid rgba(255, 255, 255, 0.2);
}
.action-card-new-footer .btn {
    width: 100%;
}

/* Styles existants nécessaires au bon fonctionnement */
.loader { text-align: center; padding: 2rem; color: white; font-size: 1.2rem; }
.parking-status-section h2 { color: white; text-align: center; margin-bottom: 1.5rem; text-shadow: 1px 1px 3px rgba(0,0,0,0.2); }
.modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.5); }
.modal-content { background-color: white; margin: 10% auto; padding: 2rem; border-radius: 10px; width: 90%; max-width: 500px; }
.close { color: #aaa; float: right; font-size: 28px; font-weight: bold; cursor: pointer; }
.close:hover { color: black; }
.modal-actions { display: flex; gap: 1rem; justify-content: flex-end; margin-top: 1rem; }
.status { padding: .25em .6em; font-size: 75%; font-weight: 700; line-height: 1; text-align: center; white-space: nowrap; vertical-align: baseline; border-radius: .25rem; color: #fff; }
.status-active { background-color: #28a745; }
.status-annulée { background-color: #dc3545; }
.status-passée { background-color: #6c757d; }
.notification-container { position: fixed; top: 20px; right: 20px; z-index: 9999; display: flex; flex-direction: column; gap: 10px; }
.notification { padding: 15px 25px; border-radius: 8px; color: white; box-shadow: 0 5px 15px rgba(0,0,0,0.2); opacity: 0; transform: translateX(100%); transition: all 0.5s cubic-bezier(0.68, -0.55, 0.27, 1.55); }
.notification.show { opacity: 1; transform: translateX(0); }
.notification-success { background: linear-gradient(135deg, #28a745, #20c997); }
.notification-danger { background: linear-gradient(135deg, #dc3545, #fd7e14); }
</style>

<!-- JavaScript (INCHANGÉ) -->
<script>
    function handleReservationSubmit(event) {
        event.preventDefault();
        const form = event.target;
        const formData = new FormData(form);
        fetch('<?= BASE_URL ?>/user/reserve', { method: 'POST', body: formData })
        .then(response => response.json())
        .then(data => {
            showNotification(data.message, data.success ? 'success' : 'danger');
            if (data.success) {
                closeReservationModal();
                setTimeout(() => window.location.reload(), 1500); 
            }
        })
        .catch(err => showNotification('Erreur réseau.', 'danger'));
    }

    document.querySelectorAll('.cancel-reservation-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(form);
            const reservationId = formData.get('reservation_id');
            if (!confirm('Êtes-vous sûr de vouloir annuler cette réservation ?')) return;
            fetch(form.action, { method: 'POST', body: formData })
            .then(response => response.json())
            .then(data => {
                showNotification(data.message, data.success ? 'success' : 'danger');
                if (data.success) {
                    const row = document.getElementById('reservation-row-' + reservationId);
                    if (row) {
                        row.style.transition = 'opacity 0.5s';
                        row.style.opacity = '0';
                        setTimeout(() => row.remove(), 500);
                    }
                }
            })
            .catch(err => showNotification('Erreur réseau.', 'danger'));
        });
    });

    function openReservationModal(spotId, spotNumber, price) {
        document.getElementById('modalSpotId').value = spotId;
        document.getElementById('modalSpotNumber').textContent = spotNumber;
        document.getElementById('modalPrice').textContent = parseFloat(price).toFixed(2);
        const now = new Date();
        now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
        document.getElementById('start_time').min = now.toISOString().slice(0, 16);
        document.getElementById('reservationModal').style.display = 'block';
    }

    function closeReservationModal() {
        const modal = document.getElementById('reservationModal');
        if(modal) modal.style.display = 'none';
    }

    window.onclick = function(event) {
        const modal = document.getElementById('reservationModal');
        if (event.target == modal) closeReservationModal();
    };

    function showNotification(message, type = 'success') {
        const container = document.getElementById('ajax-notification');
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.textContent = message;
        container.appendChild(notification);
        setTimeout(() => notification.classList.add('show'), 10);
        setTimeout(() => {
            notification.classList.remove('show');
            setTimeout(() => container.removeChild(notification), 500);
        }, 4000);
    }
</script>

<?php require_once ROOT_PATH . '/app/views/partials/footer.php'; ?>