<?php require_once ROOT_PATH . '/app/views/partials/header.php'; ?>

<!-- Conteneur pour les notifications AJAX -->
<div id="ajax-notification" class="notification-container"></div>

<div class="container">
    <div class="user-header">
        <h1><i class="fas fa-tachometer-alt"></i> Mon Dashboard</h1>
        <p>Bienvenue <?= htmlspecialchars($_SESSION['user_prenom'] ?? 'Utilisateur') ?> !</p>
    </div>

    <div class="dashboard-actions">
        <a href="<?= BASE_URL ?>/user/parking" class="action-card">
            <div class="action-icon"><i class="fas fa-car"></i></div>
            <h3>Gérer mes réservations</h3>
            <p>Consulter toutes les places et gérer vos réservations.</p>
        </a>
    </div>

    <!-- ========================================================== -->
    <!-- DÉBUT DU BLOC 3D QUI REMPLACE L'ANCIENNE GRILLE -->
    <!-- ========================================================== -->
    <div class="parking-status-section">
        <h2>État des places en temps réel</h2>
        
        <div class="dashboard-main-3d">
            <!-- Colonne centrale: la vue 3D du parking -->
            <div class="parking-view-container">
                
                <!-- Bouton "Burger" pour ouvrir le menu des étages -->
                <button id="floor-switcher-toggle" class="control-btn" title="Changer d'étage">
                    <i class="fas fa-layer-group"></i>
                </button>

                <!-- Panneau coulissant pour les étages -->
                <div id="floor-switcher-panel">
                    <h3><i class="fas fa-building"></i> Choisir un étage</h3>
                    <!-- Le JS génèrera les boutons ici -->
                    <div id="floor-buttons-container"></div>
                </div>

                <div class="parking-perspective">
                    <div class="parking-ground">
                        <div class="traffic-lane lane-1">
                            <i class="fas fa-arrow-up"></i><i class="fas fa-arrow-up"></i><i class="fas fa-arrow-up"></i><i class="fas fa-arrow-up"></i><i class="fas fa-arrow-up"></i>
                        </div>
                        <div class="traffic-lane lane-2">
                            <i class="fas fa-arrow-up"></i><i class="fas fa-arrow-up"></i><i class="fas fa-arrow-up"></i><i class="fas fa-arrow-up"></i><i class="fas fa-arrow-up"></i>
                        </div>

                    <?php
                    // ================= DÉBUT DU BLOC PHP UNIQUE ET CORRIGÉ =================

                    // Fonction d'aide pour afficher une place (définie une seule fois)
                    if (!function_exists('render_spot_3d')) {
                        function render_spot_3d($spot) {
                            if (!is_array($spot) || !isset($spot['spot_number'])) {
                                echo "<div class='parking-spot-3d maintenance' data-id='0'><div class='spot-top'><span class='spot-number-3d'>?</span></div></div>";
                                return;
                            }
                            $status = htmlspecialchars($spot['status']);
                            $id = $spot['id'];
                            $userId = $spot['user_id'] ?? 0;
                            $number = htmlspecialchars($spot['spot_number']);
                            $reservationId = $spot['reservation_id'] ?? 0;
                            echo "
                            <div class='parking-spot-3d {$status}' data-id='{$id}' data-number='{$number}' data-user-id='{$userId}' data-reservation-id='{$reservationId}'>
                                <div class='spot-face front'></div>
                                <div class='spot-top'><span class='spot-number-3d'>{$number}</span></div>
                                <div class='spot-face left'></div>
                                <div class='spot-face right'></div>
                            </div>";
                        }
                    }

                    // Boucle sur chaque étage
                    if (isset($spotsByEtage) && is_array($spotsByEtage) && !empty($spotsByEtage)) {
                        // On récupère le numéro de l'étage le plus haut pour la logique des rampes
                        $maxEtage = max(array_keys($spotsByEtage));

                        foreach ($spotsByEtage as $etage => $spots) {
                            $placeMap = [];
                            foreach ($spots as $spot) {
                                $placeMap[$spot['spot_number']] = $spot;
                            }

                            // Définir un layout SPÉCIFIQUE pour chaque étage
                            $floor_layout = [];

                            if ($etage == 1) {
                                // Layout pour l'étage 1 (Rez-de-chaussée / Sortie)
                                $floor_layout = [
                                    ['floor-access-top'],
                                    [ '101', '102', '103', 'pillar', '104', '105', '106', 'crossing', 'pillar', '107', '108', '109', 'pillar', '110', '111', '112', 'pillar', '113', '114', '115', 'ramp-up'],
                                    ['aisle'],
                                    ['aisle'],
                                    [ '116', '117', '118', 'pillar', '119', '120', '121', 'crossing', 'pillar', '122', '123', '124', 'pillar', '125', '126', '127', 'pillar', "special-zone", null,null, ],
                                    [ '128', '129', '130', 'pillar', '131', '132', '133', 'crossing', 'pillar', '134', '135', '136', 'pillar', '137', '138', '139', 'pillar',  null, null, ],
                                ];
                            } elseif ($etage == 2) {
                                // === À PERSONNALISER POUR L'ÉTAGE 2 ===
                                $floor_layout = [
                                    
                                    ['ramp-down', '201', '202', '203', 'pillar', '204', '205', '206', 'crossing', 'pillar', '207', '208', '209', 'pillar', '210', '211', '212', 'pillar', '213', '214', '215', 'ramp-up'],
                                    ['aisle'],
                                    ['aisle'],
                                    [ null, '216', '217', '218', 'pillar', '219', '220', '221', 'crossing', 'pillar', '222', '223', '224', 'pillar', '225', '226', '227', 'pillar', "special-zone", null, ],
                                    [ null, '228', '229', '230', 'pillar', '231', '232', '233', 'crossing', 'pillar', '234', '235', '236', 'pillar', '237', '238', '239', 'pillar',  null, null, ],
                                ];
                            } else { // ($etage == 3 ou plus, dernier étage)
                                // === À PERSONNALISER POUR L'ÉTAGE 3 (OU LE DERNIER) ===
                                $floor_layout = [
                                    
                                    ['ramp-down', '301', '302', '303', 'pillar', '304', '305', '306', 'crossing', 'pillar', '307', '308', '309', 'pillar', '310', '311', '312', 'pillar', '313', '314', '315', 'ramp-up'],
                                    ['aisle'],
                                    ['aisle'],
                                    [ null, '316', '317', '318', 'pillar', '319', '320', '321', 'crossing', 'pillar', '322', '323', '324', 'pillar', '325', '326', '327', 'pillar', "special-zone", null, ],
                                    [ null, '328', '329', '330', 'pillar', '331', '332', '333', 'crossing', 'pillar', '334', '335', '336', 'pillar', '337', '338', '339', 'pillar',  null, null, ],
                                ];
                            }
                    ?>
                            <div class="parking-floor-3d" id="floor-<?= $etage ?>" data-floor="<?= $etage ?>" style="display: none;">
                                <?php
                                // Boucle de rendu unifiée
                                foreach ($floor_layout as $row) {
                                    foreach ($row as $item) {
                                        if (in_array($item, ['pillar', 'crossing', 'special-zone', 'aisle', 'floor-access-top'])) {
                                            $style = ''; $content = ''; $class_name = $item;
                                            if ($item === 'crossing') $style = "style='grid-column: span 2;'";
                                            if ($item === 'special-zone') { $style = "style='grid-column: span 3;'"; $content = '<span>VÉLO &<br>ASCENSEUR</span>'; }
                                            if ($item === 'aisle' || $item === 'floor-access-top') { $style = "style='grid-column: 1 / -1;'"; }
                                            if ($item === 'floor-access-top') { $content = "<i class='fas fa-arrow-down'></i> ENTREE VOITURES  <i class='fas fa-arrow-down'></i>"; $class_name = 'floor-access'; }
                                            echo "<div class='parking-structure {$class_name}' {$style}>{$content}</div>";
                                        } elseif (in_array($item, ['ramp-up', 'ramp-down'])) {
                                            $ramp_style = "style='grid-row: span 7;'";
                                            $ramp_text = '';
                                            $ramp_icon = '';
                                            if ($item === 'ramp-down') {
                                                $ramp_text = ($etage == 1) ? "VERS SORTIE" : "VERS ÉTAGE " . ($etage - 1);
                                                $ramp_icon = "fa-arrow-down";
                                            } elseif ($item === 'ramp-up') {
                                                if ($etage < $maxEtage) {
                                                    $ramp_text = "VERS ÉTAGE " . ($etage + 1);
                                                    $ramp_icon = "fa-arrow-up";
                                                } else {
                                                    $ramp_text = "VERS ÉTAGE 2";
                                                    $ramp_icon = "fa-ban";
                                                }
                                            }
                                            echo "<div class='parking-structure ramp {$item}' {$ramp_style}><i class='fas {$ramp_icon}'></i><span>{$ramp_text}</span></div>";
                                        } elseif ($item !== null) {
                                            render_spot_3d($placeMap[$item] ?? ['spot_number' => $item, 'status' => 'maintenance', 'id' => 0]);
                                        } else {
                                            echo "<div></div>";
                                        }
                                    }
                                }
                                ?>
                            </div>
                    <?php
                        }
                    } else {
                        echo "<p style='color:white; font-size: 1.2rem; text-align:center;'>Aucune place de parking trouvée. Vérifiez que la base de données est peuplée.</p>";
                    }

                    // ================= FIN DU BLOC PHP UNIQUE =================
                    ?>
                    </div> <!-- Fin du parking-ground -->
                </div>

                <div class="view-manipulation-controls">
                    <button id="reset-view-btn" class="control-btn" title="Réinitialiser la vue"><i class="fas fa-crosshairs"></i></button>
                    <button id="zoom-in-btn" class="control-btn" title="Zoomer"><i class="fas fa-search-plus"></i></button>
                    <button id="zoom-out-btn" class="control-btn" title="Dézoomer"><i class="fas fa-search-minus"></i></button>
                </div>
            </div>
            
            <!-- Colonne de droite: panneau de détails -->
            <div class="details-panel-wrapper">
                <div id="detailsPanel">
                    <p class="placeholder">Sélectionnez une place pour voir les détails.</p>
                </div>
            </div>
        </div>
        <!-- ======================================================== -->
        <!-- FIN DE LA MODIFICATION DE LA STRUCTURE PRINCIPALE -->
        <!-- ======================================================== -->

    </div>
    <!-- Section "Mes réservations" (INCHANGÉE ET CONSERVÉE) -->
    <div class="my-reservations">
    <h2>Mes réservations</h2>
    <div id="reservations-table-container">
        <?php if (empty($userReservations)): ?>
            <div id="no-reservations-message" class="no-reservations-panel">
                <i class="fas fa-info-circle"></i>
                <p>Vous n'avez aucune réservation pour le moment.</p>
                <span>Réservez une place depuis la vue 3D ci-dessus pour la voir apparaître ici.</span>
            </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table">
                        <thead><tr><th>Place</th><th>Début</th><th>Fin</th><th>Prix/h</th><th>Statut</th><th>Actions</th></tr></thead>
                        <tbody>
                            <?php foreach ($userReservations as $reservation): ?>
                            <tr id="reservation-row-<?= $reservation['id'] ?>">
                                <td><strong><?= htmlspecialchars($reservation['spot_number']) ?></strong>
                                    <?php if ($reservation['has_charging_station']): ?><i class="fas fa-charging-station text-success" title="Borne de recharge"></i><?php endif; ?>
                                </td>
                                <td><?= date('d/m/Y H:i', strtotime($reservation['start_time'])) ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($reservation['end_time'])) ?></td>
                                <td><?= number_format($reservation['price_per_hour'], 2) ?>€</td>
                                <td><span class="status status-<?= $reservation['status'] ?>"><?= ucfirst($reservation['status']) ?></span></td>
                                <td>
                                    <?php if ($reservation['status'] == 'active' && strtotime($reservation['start_time']) > time()): ?>
                                        <form action="<?= BASE_URL ?>/user/cancel-reservation" method="POST" class="cancel-reservation-form" style="display: inline;">
                                            <input type="hidden" name="reservation_id" value="<?= $reservation['id'] ?>">
                                            <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-times"></i> Annuler</button>
                                        </form>
                                    <?php endif; ?>
                                </td>
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
<!-- ========== JAVASCRIPT & STYLES ========== -->
<!-- ============================================= -->
<style>
    /* Styles pour le panneau "Aucune réservation" */
.no-reservations-panel {
    text-align: center;
    padding: 3rem 2rem;
    background-color: rgba(255, 255, 255, 0.05); /* Fond subtil et transparent */
    border: 2px dashed rgba(255, 255, 255, 0.2);
    border-radius: 15px; /* Bords arrondis cohérents */
    margin-top: 1rem;
    color: rgba(255, 255, 255, 0.8);
    backdrop-filter: blur(5px);
}

.no-reservations-panel i {
    font-size: 2.5rem;
    color: #ffd700; /* Couleur d'accentuation du site */
    margin-bottom: 1rem;
    display: block; /* Permet au margin-bottom de fonctionner */
}

.no-reservations-panel p {
    font-size: 1.2rem;
    font-weight: 500;
    margin-bottom: 0.5rem;
    color: white; /* Texte principal plus visible */
}

.no-reservations-panel span {
    font-size: 1rem;
    opacity: 0.7; /* Texte secondaire plus discret */
}
    .dashboard-main-3d { 
    display: grid; 
    /* La première colonne est supprimée, on passe à une grille à 2 colonnes */
    grid-template-columns: 1fr 350px; 
    gap: 1.5rem; 
    min-height: 600px; 
    margin-top: 2rem; 
    margin-bottom: 2rem; 
}

/* Le conteneur de la vue 3D doit permettre le positionnement absolu de ses enfants */
.parking-view-container {
    position: relative;
    /* ... (les autres styles restent inchangés) ... */
}

/* Style du bouton "burger" */
#floor-switcher-toggle {
    position: absolute;
    top: 25px;
    left: 25px;
    z-index: 1010; /* Doit être au-dessus du panneau et de la vue 3D */
    width: 50px;
    height: 50px;
    font-size: 1.2rem;
    padding: 0; /* On retire le padding pour que l'icône soit bien centrée */
}

/* Style du panneau coulissant */
#floor-switcher-panel {
    position: absolute;
    top: 0;
    left: 0;
    bottom: 0;
    width: 250px;
    background: rgba(15, 23, 42, 0.8); /* Fond sombre et semi-transparent */
    backdrop-filter: blur(10px);
    border-right: 1px solid rgba(255, 255, 255, 0.2);
    padding: 1.5rem;
    z-index: 1000;
    display: flex;
    flex-direction: column;
    gap: 1rem;
    
    /* Animation */
    transform: translateX(-100%);
    transition: transform 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
}

/* État actif du panneau (quand il est visible) */
#floor-switcher-panel.active {
    transform: translateX(0);
}

#floor-switcher-panel h3 {
    color: #ffd700;
    margin: 0 0 1rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.2);
    font-size: 1.25rem;
    text-align: center;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
}

#floor-buttons-container {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

#floor-buttons-container .btn {
    width: 100%;
    justify-content: center;
}

#floor-buttons-container .btn.active {
    background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
    color: white;
    border-color: #3b82f6;
}

/* Ajustement responsive: on passe le panneau en pleine largeur sur mobile */
@media (max-width: 768px) {
    #floor-switcher-panel {
        width: 100%;
    }
    .dashboard-main-3d {
        /* On repasse en une seule colonne sur mobile */
        grid-template-columns: 1fr;
    }
}
.user-header, .action-card, .modal, .notification-container { /* Styles existants */ }
/* Ajout de styles pour le loader et les états */
.loader { text-align: center; padding: 2rem; color: white; font-size: 1.2rem; }
.parking-status-section h2 { color: white; text-align: center; margin-bottom: 1.5rem; text-shadow: 1px 1px 3px rgba(0,0,0,0.2); }
.spots-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 1rem; margin-bottom: 1rem; }
.spot-card { background: white; border-radius: 10px; padding: 1rem; box-shadow: 0 2px 4px rgba(0,0,0,0.1); text-align: center; border: 2px solid; transition: all 0.3s ease; }
.spot-card.disponible { border-color: #28a745; }
.spot-card.occupée { border-color: #dc3545; background-color: #f8d7da; }
.spot-card.réservée { border-color: #17a2b8; background-color: #d1ecf1; }
.spot-card.maintenance { border-color: #ffc107; background-color: #fff3cd; }
.spot-number { font-size: 1.5rem; font-weight: bold; margin-bottom: 0.5rem; }
.spot-status-text { font-weight: bold; text-transform: uppercase; font-size: 0.9rem; margin-bottom: 1rem; }
.spot-status-text.disponible { color: #28a745; }
.spot-status-text.occupée { color: #dc3545; }
.spot-status-text.réservée { color: #17a2b8; }
.spot-status-text.maintenance { color: #856404; }
.spot-card .btn { width: 100%; }
.spot-card .btn:disabled { background-color: #6c757d; cursor: not-allowed; }
/* Autres styles (copiés de la version précédente) */
.user-header { text-align: center; margin-bottom: 2rem; padding: 2rem; background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: white; border-radius: 10px; }
.dashboard-actions { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem; }
.action-card { background: white; padding: 2rem; border-radius: 10px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); text-align: center; text-decoration: none; color: inherit; transition: transform 0.2s; }
.action-card:hover { transform: translateY(-5px); text-decoration: none; color: inherit; }
.action-icon { background: #28a745; color: white; width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem; margin: 0 auto 1rem; }
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

<!-- JavaScript du modal et des notifications (INCHANGÉ) -->
<script>
    // Le JavaScript que vous aviez déjà pour la gestion du modal et des notifications
    // est déplacé ici pour une meilleure clarté.
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