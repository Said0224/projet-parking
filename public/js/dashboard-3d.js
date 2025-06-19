document.addEventListener('DOMContentLoaded', () => {
    const dashboard3D = document.querySelector('.dashboard-main-3d');
    if (!dashboard3D) return;


    // --- Gestion du menu des étages (inchangé) ---
    const floorPanel = document.getElementById('floor-switcher-panel');
    const toggleButton = document.getElementById('floor-switcher-toggle');
    const floorContainer = document.getElementById('floor-buttons-container');
    const floors = dashboard3D.querySelectorAll('.parking-floor-3d');

    if (toggleButton && floorPanel) {
        toggleButton.addEventListener('click', (e) => {
            e.stopPropagation();
            floorPanel.classList.toggle('active');
        });
    }


    if (floorContainer && floors.length > 0) {
        floors.forEach(floor => {
            const floorNum = floor.dataset.floor;
            const btn = document.createElement('button');
            btn.className = 'btn btn-secondary';
            btn.textContent = `Étage -${floorNum}`;
            btn.dataset.floor = floorNum;
            btn.addEventListener('click', () => switchFloor(floorNum));
            floorContainer.appendChild(btn);
        });

        switchFloor(1); 
    }
    
    // --- Correction de la logique Clic vs Drag (inchangé) ---
    const perspectiveView = dashboard3D.querySelector('.parking-perspective');
    const parkingViewContainer = dashboard3D.querySelector('.parking-view-container');
    
    // Constantes de vue
    const initialRotationX = 60,  minRotationX = 30,  maxRotationX = 85;
    const initialRotationZ = -30;
    const initialZoom = 0.6, minZoom = 0.5, maxZoom = 2.5;
    const zoomStep = 0.1;
    
    // NOUVEAU : Sensibilité pour le panoramique (translation)
    const panSensitivity = 1;

    // Variables d'état
    let currentRotationX = initialRotationX;
    let currentRotationZ = initialRotationZ;
    let currentZoom = initialZoom;

    // NOUVEAU : Variables pour la translation
    let currentTranslateX = 0;
    let currentTranslateY = 0;
    
    let isMouseDown = false;
    let isDragging = false;
    let previousMouseX = 0;
    let previousMouseY = 0;

    // --- Gestion du clic sur les places (inchangé) ---
    dashboard3D.querySelectorAll('.parking-spot-3d').forEach(spot => {
        spot.addEventListener('click', () => {
            if (isDragging) {
                return;
            }
            handleSpotClick(spot);
        });
    });

    // MODIFIÉ : La fonction `updateTransform` inclut maintenant la translation
    function updateTransform() {
        if (!perspectiveView) return;
        // On ajoute la partie translate() à la transformation CSS
        perspectiveView.style.transform = `rotateX(${currentRotationX}deg) rotateZ(${currentRotationZ}deg) translate(${currentTranslateX}px, ${currentTranslateY}px) scale(${currentZoom})`;
    }
    
    // --- Contrôles de zoom (inchangé) ---
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

    // MODIFIÉ : Le bouton Reset réinitialise aussi la translation
    const resetViewBtn = document.getElementById('reset-view-btn');
    if (resetViewBtn) resetViewBtn.addEventListener('click', () => {
        perspectiveView.classList.add('view-transition');
        currentRotationX = initialRotationX;
        currentRotationZ = initialRotationZ;
        currentZoom = initialZoom;
        // NOUVEAU : Réinitialisation de la translation
        currentTranslateX = 0;
        currentTranslateY = 0;
        updateTransform();
        setTimeout(() => {
            perspectiveView.classList.remove('view-transition');
        }, 400);
    });

    // --- Logique de déplacement (entièrement revue pour la translation) ---
    if (parkingViewContainer) {
        parkingViewContainer.addEventListener('contextmenu', e => e.preventDefault());
        parkingViewContainer.addEventListener('dragstart', e => e.preventDefault());

        parkingViewContainer.addEventListener('mousedown', (e) => {
            e.preventDefault(); 
            isMouseDown = true;
            isDragging = false;
            previousMouseX = e.clientX;
            previousMouseY = e.clientY;
            parkingViewContainer.style.cursor = 'grabbing';
        });

        window.addEventListener('mouseup', () => {
            isMouseDown = false;
            parkingViewContainer.style.cursor = 'grab';
        });

        window.addEventListener('mousemove', (e) => {
            if (!isMouseDown) return;

            isDragging = true;

            const deltaX = e.clientX - previousMouseX;
            const deltaY = e.clientY - previousMouseY;

            // MODIFIÉ : Au lieu de changer la rotation, on change la translation
            currentTranslateX += deltaX * panSensitivity;
            currentTranslateY += deltaY * panSensitivity;
            
            // On ne modifie plus la rotation ici !
            // currentRotationZ += deltaX * rotationSensitivity;
            // currentRotationX -= deltaY * rotationSensitivity;
            // currentRotationX = Math.max(minRotationX, Math.min(maxRotationX, currentRotationX));

            previousMouseX = e.clientX;
            previousMouseY = e.clientY;

            updateTransform();
        });
    }

    setInterval(updateAllSpotsStatus, 15000);
});


// Le reste du fichier est inchangé

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

                <div class="info-item"><span>Place</span> <span class="info-item"class="status status-${details.status}">${details.status}</span></div>`;


        if (details.status === 'occupée' || details.status === 'réservée') {
            html += `<div class="info-item"><span><i class="fas fa-user"></i> Occupant</span> <span>${details.prenom ? details.prenom + ' ' + details.nom : 'Anonyme'}</span></div>
                     <div class="info-item"><span><i class="fas fa-clock"></i> Depuis</span> <span>${occupationTime}</span></div>`;
        }
        
        html += `<div class="info-item"><span><i class="fas fa-euro-sign"></i> Tarif</span> <span>${parseFloat(details.price_per_hour).toFixed(2)} €/h</span></div>
                 <div class="info-item"><span><i class="fas fa-charging-station"></i> Borne</span> <span>${details.has_charging_station ? 'Oui' : 'Non'}</span></div>
            </div>

            `;


        if (details.status === 'disponible') {
            html += `<button class="btn btn-primary btn-full" onclick="openReservationModal(${details.id}, '${details.spot_number}', ${details.price_per_hour})">Réserver</button>`;
        } else if (details.status === 'réservée' && details.is_owner) {

            html += `<form onsubmit="event.preventDefault(); document.querySelector('#reservation-row-${details.reservation_id} .cancel-reservation-form').dispatchEvent(new Event('submit', {cancelable: true, bubbles: true}))">
                        <button type="submit" class="btn btn-danger btn-full">Annuler ma réservation</button>
                    </form>`;
        } else {

            html += `<p style="text-align:center; opacity:0.7;">Aucune réservation disponible.</p>`;

        }
        html += `</div>`;
        panel.innerHTML = html;
    } catch (error) {
        panel.innerHTML = `<p class="alert alert-danger">${error.message || 'Erreur de chargement.'}</p>`;
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