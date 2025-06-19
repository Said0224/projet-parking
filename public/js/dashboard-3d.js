document.addEventListener('DOMContentLoaded', () => {
    const dashboard3D = document.querySelector('.dashboard-main-3d');
    if (!dashboard3D) return;

    // --- Gestion du menu des étages ---
    const floorPanel = document.getElementById('floor-switcher-panel');
    const toggleButton = document.getElementById('floor-switcher-toggle');
    const floorContainer = document.getElementById('floor-buttons-container');
    const floors = dashboard3D.querySelectorAll('.parking-floor-3d');

    if (toggleButton && floorPanel) {
        toggleButton.addEventListener('click', (e) => {
            e.stopPropagation();
            floorPanel.classList.toggle('active');
        });
        // Close panel if clicking outside
        document.addEventListener('click', (e) => {
            if (!floorPanel.contains(e.target) && e.target !== toggleButton) {
                floorPanel.classList.remove('active');
            }
        });
    }

    if (floorContainer && floors.length > 0) {
        floors.forEach(floor => {
            const floorNum = floor.dataset.floor;
            const btn = document.createElement('button');
            btn.className = 'btn btn-secondary';
            btn.textContent = `Étage ${floorNum}`;
            btn.dataset.floor = floorNum;
            btn.addEventListener('click', () => switchFloor(floorNum));
            floorContainer.appendChild(btn);
        });
        switchFloor(1); 
    }
    
    // --- Logique Clic vs Drag ---
    const perspectiveView = dashboard3D.querySelector('.parking-perspective');
    const parkingViewContainer = dashboard3D.querySelector('.parking-view-container');
    
    // Constantes de vue
    const initialRotationX = 60,  minRotationX = 30,  maxRotationX = 85;
    const initialRotationZ = -30;
    const initialZoom = 0.6, minZoom = 0.5, maxZoom = 2.5;
    const zoomStep = 0.1;
    const panSensitivity = 1;

    // Variables d'état
    let currentRotationX = initialRotationX;
    let currentRotationZ = initialRotationZ;
    let currentZoom = initialZoom;
    let currentTranslateX = 0;
    let currentTranslateY = 0;
    
    let isMouseDown = false;
    let isDragging = false;
    let startX = 0;
    let startY = 0;

    // --- Gestion du clic sur les places ---
    dashboard3D.querySelectorAll('.parking-spot-3d').forEach(spot => {
        spot.addEventListener('mousedown', (e) => {
            isDragging = false;
        });
        spot.addEventListener('mousemove', (e) => {
            isDragging = true;
        });
        spot.addEventListener('mouseup', () => {
            if (!isDragging) {
                handleSpotClick(spot);
            }
        });
    });

    function updateTransform() {
        if (!perspectiveView) return;
        perspectiveView.style.transform = `rotateX(${currentRotationX}deg) rotateZ(${currentRotationZ}deg) translate(${currentTranslateX}px, ${currentTranslateY}px) scale(${currentZoom})`;
    }
    
    // --- Contrôles de zoom ---
    const zoomInBtn = document.getElementById('zoom-in-btn');
    const zoomOutBtn = document.getElementById('zoom-out-btn');
    if (zoomInBtn) zoomInBtn.addEventListener('click', () => {
        currentZoom = Math.min(maxZoom, currentZoom + zoomStep);
        updateTransform();
    });
    if (zoomOutBtn) zoomOutBtn.addEventListener('click', () => {
        currentZoom = Math.max(minZoom, currentZoom - zoomStep);
        updateTransform();
    });

    // --- Bouton Reset ---
    const resetViewBtn = document.getElementById('reset-view-btn');
    if (resetViewBtn) resetViewBtn.addEventListener('click', () => {
        perspectiveView.classList.add('view-transition');
        currentRotationX = initialRotationX;
        currentRotationZ = initialRotationZ;
        currentZoom = initialZoom;
        currentTranslateX = 0;
        currentTranslateY = 0;
        updateTransform();
        setTimeout(() => {
            perspectiveView.classList.remove('view-transition');
        }, 400);
    });

    // --- Logique de déplacement de la vue ---
    if (parkingViewContainer) {
        parkingViewContainer.addEventListener('contextmenu', e => e.preventDefault());
        parkingViewContainer.addEventListener('dragstart', e => e.preventDefault());

        parkingViewContainer.addEventListener('mousedown', (e) => {
            isMouseDown = true;
            startX = e.clientX - currentTranslateX;
            startY = e.clientY - currentTranslateY;
            parkingViewContainer.style.cursor = 'grabbing';
        });

        window.addEventListener('mouseup', () => {
            isMouseDown = false;
            parkingViewContainer.style.cursor = 'grab';
        });

        window.addEventListener('mousemove', (e) => {
            if (!isMouseDown) return;
            e.preventDefault();
            currentTranslateX = e.clientX - startX;
            currentTranslateY = e.clientY - startY;
            updateTransform();
        });
    }

    // Mise à jour périodique des statuts
    setInterval(updateAllSpotsStatus, 15000);
});

// --- Fonctions globales ---

function switchFloor(floorNum) {
    document.querySelectorAll('.parking-floor-3d').forEach(f => f.style.display = 'none');
    const floorToShow = document.querySelector(`#floor-${floorNum}`);
    if (floorToShow) floorToShow.style.display = 'grid';

    const floorButtonsContainer = document.getElementById('floor-buttons-container');
    if (floorButtonsContainer) {
        floorButtonsContainer.querySelectorAll('.btn').forEach(b => b.classList.remove('active'));
        const buttonToActivate = floorButtonsContainer.querySelector(`.btn[data-floor="${floorNum}"]`);
        if(buttonToActivate) buttonToActivate.classList.add('active');
    }

    const floorPanel = document.getElementById('floor-switcher-panel');
    if (floorPanel) {
        floorPanel.classList.remove('active');
    }
}

function handleSpotClick(spotElement) {
    if (spotElement.dataset.id === '0') return; 
    document.querySelectorAll('.parking-spot-3d.selected').forEach(s => s.classList.remove('selected'));
    spotElement.classList.add('selected');
    displaySpotDetails(spotElement.dataset.id);
}

async function displaySpotDetails(spotId) {
    const panel = document.getElementById('detailsPanel');
    panel.innerHTML = '<div class="loader">Chargement...</div>';

    try {
        const response = await fetch(`${BASE_URL}/api/get-spot-details?id=${spotId}`);
        if (!response.ok) throw new Error('Réponse du serveur non valide.');
        
        const result = await response.json();
        if (!result.success) throw new Error(result.message);

        const details = result.details;
        let occupationTime = 'N/A';
        if (details.start_time) {
            const diffMins = Math.round((new Date() - new Date(details.start_time.replace(' ', 'T'))) / 60000);
            occupationTime = (diffMins < 60) ? `${diffMins} min` : `${Math.floor(diffMins / 60)}h ${diffMins % 60}min`;
        }
        
        let html = `<h3>Place ${details.spot_number}</h3>
            <div class="details-panel-section">
                <h4><i class="fas fa-info-circle"></i> Informations</h4>
                <div class="info-item"><span>Statut</span> <span class="status status-${details.status}">${details.status}</span></div>`;

        if (details.status === 'occupée' || details.status === 'réservée') {
            html += `<div class="info-item"><span><i class="fas fa-user"></i> Occupant</span> <span>${details.prenom ? details.prenom + ' ' + details.nom : 'Anonyme'}</span></div>
                     <div class="info-item"><span><i class="fas fa-clock"></i> Depuis</span> <span>${occupationTime}</span></div>`;
        }
        
        html += `<div class="info-item"><span><i class="fas fa-euro-sign"></i> Tarif</span> <span>${parseFloat(details.price_per_hour).toFixed(2)} €/h</span></div>
                 <div class="info-item"><span><i class="fas fa-charging-station"></i> Borne</span> <span>${details.has_charging_station ? 'Oui' : 'Non'}</span></div>
            </div>`;

        // === BLOC D'ACTIONS CORRIGÉ ET FIABILISÉ ===
        html += '<div class="details-panel-section"><h4><i class="fas fa-tasks"></i> Actions</h4>';

        if (details.status === 'disponible') {
            html += `<button class="btn btn-primary btn-full" onclick="openReservationModal(${details.id}, '${details.spot_number}', ${details.price_per_hour})">Réserver cette place</button>`;
        } else if (details.status === 'réservée' && details.is_owner) {
            // Bouton qui appelle directement la fonction JS d'annulation
            html += `<button class="btn btn-danger btn-full" onclick="cancelReservation('${details.reservation_id}', '${BASE_URL}/user/cancel-reservation')">Annuler ma réservation</button>`;
        } else {
            html += `<p style="text-align:center; opacity:0.7;">Aucune action disponible.</p>`;
        }
        html += `</div>`;
        panel.innerHTML = html;

    } catch (error) {
        panel.innerHTML = `<p class="alert alert-danger" style="margin:1rem; color: #721c24;">${error.message || 'Erreur de chargement.'}</p>`;
    }
}

async function updateAllSpotsStatus() {
    try {
        const response = await fetch(`${BASE_URL}/api/get-all-spots-status`);
        if (!response.ok) return;
        const result = await response.json();
        if (result.success) {
            result.spots.forEach(spot => {
                const spotElement = document.querySelector(`.parking-spot-3d[data-id="${spot.id}"]`);
                if (spotElement) {
                    spotElement.className = `parking-spot-3d ${spot.status}`;
                    spotElement.dataset.userId = spot.user_id || 0;
                    spotElement.dataset.reservationId = spot.reservation_id || 0;
                }
            });
        }
    } catch (error) {
        console.warn("Mise à jour périodique des places échouée:", error);
    }
}