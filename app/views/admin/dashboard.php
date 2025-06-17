<?php require_once ROOT_PATH . '/app/views/partials/header.php'; ?>

<div class="container">
    <div class="admin-header">
        <h1><i class="fas fa-cog"></i> Administration - Dashboard</h1>
        <p>Bienvenue dans l'interface d'administration du parking intelligent</p>
    </div>

    <div class="admin-stats">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-info">
                <h3><?= count($users) ?></h3>
                <p>Utilisateurs</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-parking"></i>
            </div>
            <div class="stat-info">
                <h3><?= count($spots) ?></h3>
                <p>Places de parking</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-calendar-check"></i>
            </div>
            <div class="stat-info">
                <h3><?= $total_reservations ?></h3>
                <p>Réservations</p>
            </div>
        </div>
    </div>

    <div class="admin-actions">
        <a href="<?= BASE_URL ?>/admin/users" class="btn btn-primary">
            <i class="fas fa-users"></i> Gérer les utilisateurs
        </a>
        <a href="<?= BASE_URL ?>/admin/parking" class="btn btn-primary">
            <i class="fas fa-parking"></i> Gérer le parking
        </a>
    </div>

    <div class="recent-activity">
        <div class="activity-header">
            <h2>Réservations récentes</h2>
            <button id="open-filter-btn" class="btn btn-secondary btn-sm">
                <i class="fas fa-filter"></i> Filtrer
            </button>
        </div>

        <!-- Ce conteneur sera mis à jour par AJAX -->
        <div id="reservations-container">
            <?php require ROOT_PATH . '/app/views/partials/reservations_table.php'; ?>
        </div>
    </div>
</div>


<!-- ======================== POPUP / MODAL DE FILTRE ======================== -->
<div id="filter-modal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3><i class="fas fa-filter"></i> Filtrer les réservations</h3>
            <span class="close-modal">×</span>
        </div>
        <div class="modal-body">
            <form id="filter-form">
                <div class="form-group">
                    <label for="filter_date" class="form-label">Filtrer par date</label>
                    <input type="date" id="filter_date" name="filter_date" class="form-control">
                </div>
                <div class="form-group">
                    <label for="filter_spot_id" class="form-label">Filtrer par place</label>
                    <select id="filter_spot_id" name="filter_spot_id" class="form-control">
                        <option value="">Toutes les places</option>
                        <?php foreach ($spots as $spot): ?>
                            <option value="<?= $spot['id'] ?>"><?= htmlspecialchars($spot['spot_number']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="modal-actions">
                    <button type="button" id="reset-filter-btn" class="btn btn-primary">Réinitialiser</button>
                    <button type="submit" class="btn btn-primary">Appliquer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ======================== STYLES CSS ======================== -->
<style>
.admin-header {
    text-align: center;
    margin-bottom: 2rem;
    padding: 2rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 10px;
}

.btn-sm {
        color: gray
}

.admin-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: white;
    padding: 1.5rem;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    display: flex;
    align-items: center;
    gap: 1rem;
}

.stat-icon {
    background: #667eea;
    color: white;
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}

.stat-info h3 {
    margin: 0;
    font-size: 2rem;
    color: #333;
}

.stat-info p {
    margin: 0;
    color: #666;
}

.admin-actions {
    display: flex;
    gap: 1rem;
    margin-bottom: 2rem;
    justify-content: center;
}

.recent-activity {
    background: white;
    padding: 1.5rem;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.status {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.875rem;
    font-weight: 500;
}

.status-active {
    background-color: #d4edda;
    color: #155724;
}

.status-completed {
    background-color: #cce5ff;
    color: #004085;
}

.status-cancelled {
    background-color: #f8d7da;
    color: #721c24;
}

.activity-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.pagination-container {
    display: flex;
    justify-content: center;
    margin-top: 1.5rem;
}
.pagination {
    list-style: none;
    padding: 0;
    display: flex;
    gap: 0.5rem;
}
.page-item .page-link {
    color: #667eea;
    background-color: #fff;
    border: 1px solid #ddd;
    padding: 0.5rem 1rem;
    text-decoration: none;
    border-radius: 5px;
    transition: all 0.2s;
}
.page-item .page-link:hover {
    background-color: #f0f0f0;
}
.page-item.active .page-link {
    background-color: #667eea;
    color: white;
    border-color: #667eea;
}

.modal {
    display: none;
    position: fixed;
    z-index: 1050;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0,0,0,0.5);
    backdrop-filter: blur(5px);
}
.modal-content {
    position: relative;
    background-color: #fefefe;
    margin: 10% auto;
    padding: 0;
    border: 1px solid #888;
    width: 90%;
    max-width: 500px;
    box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2),0 6px 20px 0 rgba(0,0,0,0.19);
    border-radius: 15px;
    overflow: hidden;
    animation: fadeIn 0.3s;
}
@keyframes fadeIn {
    from {transform: scale(0.9); opacity: 0;}
    to {transform: scale(1); opacity: 1;}
}
.modal-header {
    padding: 1rem 1.5rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.modal-header h3 {
    margin: 0;
}
.close-modal {
    color: white;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
    transition: transform 0.2s;
}
.close-modal:hover {
    transform: scale(1.2);
}
.modal-body {
    padding: 2rem;
}
.modal-actions {
    display: flex;
    justify-content: flex-end;
    gap: 1rem;
    margin-top: 2rem;
}
.text-center {
    text-align: center;
    padding: 2rem;
    color: #666;
}
</style>

<!-- ======================== JAVASCRIPT ======================== -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const reservationsContainer = document.getElementById('reservations-container');
    const filterForm = document.getElementById('filter-form');
    const modal = document.getElementById('filter-modal');
    
    const openBtn = document.getElementById('open-filter-btn');
    const closeBtn = document.querySelector('.close-modal');
    const resetBtn = document.getElementById('reset-filter-btn');

    openBtn.onclick = () => modal.style.display = "block";
    closeBtn.onclick = () => modal.style.display = "none";
    window.onclick = (event) => {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }

    async function fetchReservations(page = 1) {
        reservationsContainer.style.opacity = '0.5';
        const formData = new FormData(filterForm);
        const params = new URLSearchParams();
        
        params.append('page', page);
        if (formData.get('filter_date')) {
            params.append('filter_date', formData.get('filter_date'));
        }
        if (formData.get('filter_spot_id')) {
            params.append('filter_spot_id', formData.get('filter_spot_id'));
        }

        try {
            const response = await fetch(`<?= BASE_URL ?>/admin/api/reservations?${params.toString()}`);
            if (!response.ok) {
                throw new Error('Erreur réseau ou serveur');
            }
            const html = await response.text();
            reservationsContainer.innerHTML = html;
        } catch (error) {
            reservationsContainer.innerHTML = '<p class="text-center">Erreur lors du chargement des données.</p>';
            console.error('Erreur AJAX:', error);
        } finally {
            reservationsContainer.style.opacity = '1';
        }
    }

    filterForm.addEventListener('submit', function(e) {
        e.preventDefault();
        fetchReservations(1);
        modal.style.display = "none";
    });

    resetBtn.addEventListener('click', function() {
        filterForm.reset();
        fetchReservations(1);
        modal.style.display = "none";
    });

    reservationsContainer.addEventListener('click', function(e) {
        if (e.target.matches('.page-link')) {
            e.preventDefault();
            const page = e.target.dataset.page;
            if (page) {
                fetchReservations(page);
            }
        }
    });
});
</script>

<?php require_once ROOT_PATH . '/app/views/partials/footer.php'; ?>