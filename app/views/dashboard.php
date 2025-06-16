<?php require_once ROOT_PATH . '/app/views/partials/header.php'; ?>

<div class="container dashboard-container">
    <div class="dashboard-header">
        <h1><i class="fas fa-th"></i> Tableau de Bord 3D</h1>
        <p>Bienvenue, <?= htmlspecialchars($_SESSION['user_prenom'] . ' ' . $_SESSION['user_nom']) ?> !</p>
    </div>

    <div class="dashboard-main-3d">
        <!-- Colonne de gauche : Contrôles -->
        <div class="parking-controls">
            <div class="floor-switcher card-body">
                <h3>Étages</h3>
                <!-- Les boutons seront générés par JS -->
            </div>
        </div>

        <!-- Colonne centrale : Vue 3D -->
        <div class="parking-view-container">
            <div class="parking-perspective">
                <?php
                // Fonction pour afficher une place de parking
                function render_parking_spot($place) {
                    if (!is_object($place)) return;
                    $statut = htmlspecialchars($place->statut);
                    $id = $place->id;
                    $numero = $place->numero;

                    echo "
                    <div class=\"parking-spot-3d {$statut}\" data-id=\"{$id}\">
                        <div class=\"spot-face top\"><span>{$numero}</span></div>
                    </div>";
                }

                if (!empty($placesByEtage)) {
                    foreach ($placesByEtage as $etage => $places):
                    ?>
                        <div class="parking-floor-3d" id="floor-<?= $etage ?>" data-floor="<?= $etage ?>" style="display: none;">
                            <?php
                            // Affichage simple en grille pour tous les étages
                            foreach ($places as $place) {
                                render_parking_spot($place);
                            }
                            ?>
                        </div>
                    <?php 
                    endforeach;
                } else {
                    echo "<p class='text-center' style='color:white;'>Aucune donnée de place de parking n'a pu être chargée.</p>";
                }
                ?>
            </div>
        </div>

        <!-- Colonne de droite : Panneau de détails -->
        <div class="details-panel-wrapper">
            <div class="details-panel card-body" id="detailsPanel">
                <p class="placeholder">Sélectionnez une place pour voir les détails.</p>
            </div>
        </div>
    </div>
</div>

<!-- Le SCRIPT est maintenant directement dans la vue pour plus de simplicité -->
<script>
document.addEventListener('DOMContentLoaded', () => {

    const BASE_URL = '<?= BASE_URL ?>';
    let activeFloor = 1;

    // --- Initialisation ---
    function initDashboard() {
        const floorContainer = document.querySelector('.floor-switcher');
        const floors = document.querySelectorAll('.parking-floor-3d');
        
        if (!floors.length) {
            floorContainer.innerHTML += '<p class="text-center text-muted">Aucun étage à afficher.</p>';
            return;
        }

        // Créer les boutons pour chaque étage
        floors.forEach(floor => {
            const floorNum = floor.dataset.floor;
            const btn = document.createElement('button');
            btn.className = 'btn btn-secondary btn-full';
            btn.textContent = `Étage -${floorNum}`;
            btn.dataset.floor = floorNum;
            btn.addEventListener('click', () => switchFloor(floorNum));
            floorContainer.appendChild(btn);
        });

        // Afficher le premier étage par défaut
        switchFloor(floors[0].dataset.floor);

        // Ajouter les écouteurs de clic sur les places
        document.querySelectorAll('.parking-spot-3d').forEach(spot => {
            spot.addEventListener('click', () => handleSpotClick(spot));
        });
    }

    // --- Logique de changement d'étage ---
    function switchFloor(floorNum) {
        activeFloor = floorNum;
        document.querySelectorAll('.parking-floor-3d').forEach(f => f.style.display = 'none');
        const floorToShow = document.querySelector(`#floor-${floorNum}`);
        if(floorToShow) {
            floorToShow.style.display = 'grid';
        }

        document.querySelectorAll('.floor-switcher .btn').forEach(b => {
            if (b.dataset.floor === floorNum) {
                b.classList.add('active', 'btn-primary');
                b.classList.remove('btn-secondary');
            } else {
                b.classList.remove('active', 'btn-primary');
                b.classList.add('btn-secondary');
            }
        });
    }

    // --- Logique de clic sur une place ---
    function handleSpotClick(spot) {
        document.querySelectorAll('.parking-spot-3d').forEach(s => s.classList.remove('selected'));
        spot.classList.add('selected');
        displaySpotDetails(spot.dataset.id);
    }

    // --- Affichage des détails ---
    async function displaySpotDetails(placeId) {
        const panel = document.getElementById('detailsPanel');
        panel.innerHTML = '<p class="placeholder">Chargement...</p>';
        
        try {
            const response = await fetch(`${BASE_URL}/dashboard/api/getPlaceDetails?id=${placeId}`);
            if (!response.ok) {
                throw new Error(`Erreur HTTP: ${response.status}`);
            }
            const data = await response.json();

            if (!data.success || !data.details) {
                panel.innerHTML = `<div class="alert alert-danger">Impossible de charger les détails.</div>`;
                return;
            }

            const { details } = data;
            
            let html = `
                <h3>Place N°${details.numero}</h3>
                <div class="details-section">
                    <h4><i class="fas fa-info-circle"></i> Informations</h4>
                    <div class="info-item">
                        <span>Statut</span> 
                        <span class="status-badge status-${details.statut}">${details.statut}</span>
                    </div>
                    <div class="info-item">
                        <span>Étage</span> 
                        <span>-${details.etage}</span>
                    </div>
                    <div class="info-item">
                        <span>Dernière MàJ</span> 
                        <span>${details.derniere_maj || 'N/A'}</span>
                    </div>
                </div>`;

            if (details.statut === 'libre') {
                html += `
                <div class="details-section">
                    <h4><i class="fas fa-hand-pointer"></i> Actions</h4>
                    <button class="btn btn-success btn-full">Réserver cette place</button>
                </div>`;
            } else {
                 html += `<p class="mt-3 text-center" style="color: rgba(255,255,255,0.6)">Aucune action disponible.</p>`;
            }

            panel.innerHTML = html;

        } catch (error) {
            console.error("Erreur API:", error);
            panel.innerHTML = `<div class="alert alert-danger">Erreur de communication avec le serveur.</div>`;
        }
    }

    // Lancer l'initialisation
    initDashboard();
});
</script>

<?php require_once ROOT_PATH . '/app/views/partials/footer.php'; ?>