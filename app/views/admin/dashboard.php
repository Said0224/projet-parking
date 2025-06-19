<?php
// Fichier : app/views/admin/dashboard.php
// Sécurise l'accès à la page
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header('Location: ' . BASE_URL . '/login');
    exit;
}

require_once ROOT_PATH . '/app/views/partials/header.php';

// Calcul des statistiques à partir des variables fournies par le contrôleur
$totalUsers = count($users);
$totalSpots = count($spots);
$availableSpots = count(array_filter($spots, fn($s) => $s['status'] === 'disponible'));
$activeReservations = $total_reservations; // La variable est déjà calculée dans le contrôleur
?>

<!-- Conteneur pour les notifications AJAX -->
<div id="ajax-notification" class="notification-container"></div>

<div class="container">
    <div class="admin-header">
        <h1><i class="fas fa-user-shield"></i> Espace Administration</h1>
        <p>Gestion globale du système de parking intelligent</p>
    </div>

    <!-- Statistiques Clés -->
    <div class="admin-stats">
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-users"></i></div>
            <div class="stat-info">
                <!-- Affiche le nombre total d'utilisateurs -->
                <h3><?= $totalUsers ?></h3>
                <p>Utilisateurs Inscrits</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-parking"></i></div>
            <div class="stat-info">
                <!-- Affiche le nombre de places disponibles sur le total -->
                <h3><?= $availableSpots ?> / <?= $totalSpots ?></h3>
                <p>Places Disponibles</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-calendar-check"></i></div>
            <div class="stat-info">
                <!-- Affiche le nombre de réservations (basé sur le total paginé) -->
                <h3><?= $activeReservations ?></h3>
                <p>Réservations (filtrées)</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-microchip"></i></div>
            <div class="stat-info">
                <h3>Connecté</h3>
                <p>Système IoT</p>
            </div>
        </div>
    </div>

    <!-- Actions Rapides -->
    <div class="dashboard-actions card">
        <div class="card-header">
            <h3><i class="fas fa-rocket"></i> Actions Rapides</h3>
        </div>
        <div class="card-body">
            <div class="admin-actions">
                <a href="<?= BASE_URL ?>/admin/users" class="btn btn-primary">
                    <i class="fas fa-users-cog"></i> Gérer les Utilisateurs
                </a>
                <a href="<?= BASE_URL ?>/admin/parking" class="btn btn-primary">
                    <i class="fas fa-parking"></i> Gérer le Parking
                </a>
                <a href="<?= BASE_URL ?>/iot-dashboard" class="btn btn-primary">
                    <i class="fas fa-cogs"></i> Panel IoT
                </a>
            </div>
        </div>
    </div>

    <!-- Activité Récente (Dernières réservations) -->
    <div class="recent-activity card">
        <div class="card-header activity-header">
            <h3><i class="fas fa-history"></i> Dernières Réservations</h3>
            <form id="reservations-filter-form">
                <input type="date" name="filter_date" class="form-control" value="<?= htmlspecialchars($_GET['filter_date'] ?? '') ?>">
                <select name="filter_spot_id" class="form-control">
                    <option value="">Toutes les places</option>
                    <?php foreach ($spots as $spot): ?>
                        <option value="<?= $spot['id'] ?>" <?= (isset($_GET['filter_spot_id']) && $_GET['filter_spot_id'] == $spot['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($spot['spot_number']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="btn btn-sm btn-light">Filtrer</button>
            </form>
        </div>
        <div class="card-body" id="reservations-table-container">
            <!-- La vue partielle pour le tableau des réservations est chargée ici -->
            <?php require_once ROOT_PATH . '/app/views/partials/reservations_table.php'; ?>
        </div>
    </div>
</div>

<style>
/* Styles spécifiques pour le dashboard admin */
.admin-header {
    text-align: center; margin-bottom: 2rem; padding: 2rem;
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(15px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 20px; color: white;
}
.admin-stats {
    display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 1.5rem; margin-bottom: 2rem;
}
.stat-card {
    background: rgba(255, 255, 255, 0.95); padding: 1.5rem; border-radius: 15px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.08); display: flex; align-items: center; gap: 1.5rem;
    transition: all 0.3s ease;
}
.stat-card:hover { transform: translateY(-5px); box-shadow: 0 12px 30px rgba(0,0,0,0.12); }
.stat-icon {
    background: linear-gradient(135deg, #1e40af, #3b82f6); color: white;
    width: 60px; height: 60px; border-radius: 50%; display: flex;
    align-items: center; justify-content: center; font-size: 1.75rem; flex-shrink: 0;
}
.stat-info h3 { margin: 0; font-size: 1.25rem; color: #1e293b; }
.stat-info p { margin: 0; color: #64748b; font-weight: 500; }
.dashboard-actions .card-body { padding: 2rem; }
.admin-actions {
    display: flex; gap: 1rem;  justify-content: center;
}
.admin-actions .btn { padding: 1rem 1.5rem; font-size: 1rem; }
.activity-header {
    display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;
}
#reservations-filter-form { display: flex; gap: 0.5rem; align-items: center; }
.status {
    padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.8rem; font-weight: 600;
    text-transform: capitalize;
}
.status-active { background-color: #d1fae5; color: #065f46; }
.status-passée { background-color: #e5e7eb; color: #4b5563; }
.status-annulée { background-color: #fee2e2; color: #991b1b; }
.pagination-container { display: flex; justify-content: center; margin-top: 1.5rem; }
.pagination { list-style: none; padding: 0; display: flex; gap: 0.5rem; }
.page-item .page-link {
    color: #3b82f6; background-color: #fff; border: 1px solid #ddd; padding: 0.5rem 1rem;
    text-decoration: none; border-radius: 8px; transition: all 0.2s;
}
.page-item.active .page-link { background-color: #3b82f6; color: white; border-color: #3b82f6; }
.text-center { text-align: center; padding: 2rem; color: #666; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tableContainer = document.getElementById('reservations-table-container');
    const filterForm = document.getElementById('reservations-filter-form');

    async function fetchReservations(page = 1) {
        tableContainer.style.opacity = '0.5';
        const formData = new FormData(filterForm);
        const params = new URLSearchParams({ page });

        for (const [key, value] of formData.entries()) {
            if (value) {
                params.append(key, value);
            }
        }
        
        try {
            const response = await fetch(`<?= BASE_URL ?>/admin/api/reservations?${params.toString()}`);
            if (!response.ok) throw new Error('Erreur réseau');
            tableContainer.innerHTML = await response.text();
        } catch (error) {
            tableContainer.innerHTML = '<p class="text-center">Erreur lors du chargement des réservations.</p>';
        } finally {
            tableContainer.style.opacity = '1';
        }
    }

    // Gérer la soumission du formulaire de filtre
    filterForm.addEventListener('submit', function(e) {
        e.preventDefault();
        fetchReservations(1);
    });

    // Gérer les clics sur la pagination
    tableContainer.addEventListener('click', function(e) {
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