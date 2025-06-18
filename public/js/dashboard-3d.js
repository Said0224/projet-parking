document.addEventListener('DOMContentLoaded', () => {
    const dashboard3D = document.querySelector('.dashboard-main-3d');
    if (!dashboard3D) return;

    const floorContainer = dashboard3D.querySelector('.floor-switcher');
    const floors = dashboard3D.querySelectorAll('.parking-floor-3d');
    
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

    dashboard3D.querySelectorAll('.parking-spot-3d').forEach(spot => {
        spot.addEventListener('click', () => handleSpotClick(spot));
    });

    setInterval(updateAllSpotsStatus, 15000);
});

function switchFloor(floorNum) {
    document.querySelectorAll('.parking-floor-3d').forEach(f => f.style.display = 'none');
    const floorToShow = document.querySelector(`#floor-${floorNum}`);
    if (floorToShow) floorToShow.style.display = 'grid';

    document.querySelectorAll('.floor-switcher .btn').forEach(b => b.classList.remove('active'));
    const buttonToActivate = document.querySelector(`.floor-switcher .btn[data-floor="${floorNum}"]`);
    if(buttonToActivate) buttonToActivate.classList.add('active');
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
            </div>
            <div class="details-panel-section"><h4><i class="fas fa-hand-pointer"></i> Actions</h4>`;

        if (details.status === 'disponible') {
            html += `<button class="btn btn-primary btn-full" onclick="openReservationModal(${details.id}, '${details.spot_number}', ${details.price_per_hour})">Réserver</button>`;
        } else if (details.status === 'réservée' && details.is_owner) {
            // Lier l'annulation au tableau du bas pour la cohérence
            html += `<form onsubmit="event.preventDefault(); document.querySelector('#reservation-row-${details.reservation_id} .cancel-reservation-form').dispatchEvent(new Event('submit', {cancelable: true, bubbles: true}))">
                        <button type="submit" class="btn btn-danger btn-full">Annuler ma réservation</button>
                    </form>`;
        } else {
            html += `<p style="text-align:center; opacity:0.7;">Aucune action disponible.</p>`;
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