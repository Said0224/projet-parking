<?php require_once ROOT_PATH . '/app/views/partials/header.php'; ?>

<!-- Conteneur pour les notifications AJAX -->
<div id="ajax-notification" class="notification-container"></div>

<div class="container">
    <div class="page-header">
        <h1><i class="fas fa-users-cog"></i> Gestion des Utilisateurs</h1>
        <a href="<?= BASE_URL ?>/admin" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>

    <!-- Section: Créer un nouvel utilisateur -->
    <div class="card create-user-card">
        <div class="card-header">
            <h3><i class="fas fa-user-plus"></i> Créer un nouvel utilisateur</h3>
        </div>
        <div class="card-body">
            <form action="<?= BASE_URL ?>/admin/create-user" method="POST" id="create-user-form">
                <div class="form-row">
                    <div class="form-group">
                        <label for="prenom">Prénom</label>
                        <input type="text" name="prenom" class="form-control" required placeholder="John">
                    </div>
                    <div class="form-group">
                        <label for="nom">Nom</label>
                        <input type="text" name="nom" class="form-control" required placeholder="Doe">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" class="form-control" required placeholder="john.doe@isep.fr">
                    </div>
                    <div class="form-group">
                        <label for="password">Mot de passe</label>
                        <input type="password" name="password" class="form-control" required placeholder="••••••••">
                    </div>
                </div>
                <div class="form-group">
                    <!-- ===== DÉBUT DE LA CORRECTION HTML ===== -->
                    <div class="create-user-switch-container">
                        <div class="form-switch">
                            <input class="form-check-input" type="checkbox" name="is_admin" id="isAdminCheck">
                            <label class="form-check-label" for="isAdminCheck"></label>
                        </div>
                        <span id="create-user-role-text">Promouvoir en tant qu'administrateur</span>
                    </div>
                    <!-- ===== FIN DE LA CORRECTION HTML ===== -->
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-plus-circle"></i> Ajouter l'utilisateur
                </button>
            </form>
        </div>
    </div>

    <!-- Section: Liste des utilisateurs -->
    <div class="card user-list-card">
        <div class="card-header card-header-flex">
            <h3><i class="fas fa-list-ul"></i> Liste des utilisateurs</h3>
            <button id="open-filter-btn" class="btn btn-primary btn-sm">
                <i class="fas fa-filter"></i> Filtrer
            </button>
        </div>
        <div class="card-body">
            <div id="users-table-container">
                <?php require ROOT_PATH . '/app/views/admin/partials/users_table.php'; ?>
            </div>
        </div>
    </div>
</div>

<!-- MODAL DE FILTRE -->
<div id="filter-modal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <h3><i class="fas fa-filter"></i> Filtrer les utilisateurs</h3>
            <button class="close-modal-btn">×</button>
        </div>
        <div class="modal-body">
            <form id="filter-form">
                <div class="form-group">
                    <label for="filter_role" class="form-label">Filtrer par rôle</label>
                    <select id="filter_role" name="filter_role" class="form-control">
                        <option value="">Tous les rôles</option>
                        <option value="1">Administrateurs</option>
                        <option value="0">Utilisateurs</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" id="reset-filter-btn" class="btn btn-light">Réinitialiser</button>
                    <button type="submit" class="btn btn-primary">Appliquer les filtres</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* En-tête de carte flexible */
.card-header-flex { display: flex; justify-content: space-between; align-items: center; }

/* Style pour le switch */
.form-switch {
    position: relative;
    display: inline-block;
    width: 50px;
    height: 30px;
}
.form-switch .form-check-input {
    opacity: 0;
    width: 0;
    height: 0;
}
.form-switch .form-check-label {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #e9ecef;
    border: 1px solid #ced4da;
    transition: .4s;
    border-radius: 15px;
}
.form-switch .form-check-label:before {
    position: absolute;
    content: "";
    height: 22px;
    width: 22px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    box-shadow: 0 1px 3px rgba(0,0,0,0.2);
    transition: .4s;
    border-radius: 50%;
}
.form-switch .form-check-input:checked + .form-check-label {
    background-color: #1e40af;
    border-color: #1e40af;
}
.form-switch .form-check-input:checked + .form-check-label:before {
    transform: translateX(20px);
}
.form-switch .form-check-input:disabled + .form-check-label {
    opacity: 0.6;
    cursor: not-allowed;
}

/* ===== DÉBUT DE LA CORRECTION CSS ===== */
/* Conteneurs pour les interrupteurs */
.role-switch-container, .create-user-switch-container {
    display: flex;
    align-items: center;
    gap: 12px; /* Espace entre l'interrupteur et le texte */
}
/* Style pour le texte à côté de l'interrupteur */
.role-text, #create-user-role-text {
    font-weight: 500;
    color: #495057;
}
/* ===== FIN DE LA CORRECTION CSS ===== */

.actions-cell { text-align: right; }
.btn-icon {
    width: 36px; height: 36px; padding: 0; border-radius: 50%;
    display: inline-flex; align-items: center; justify-content: center;
}
tr.fading-out {
    opacity: 0;
    transform: translateX(50px);
    transition: opacity 0.4s ease-out, transform 0.4s ease-out;
}
.create-user-card, .user-list-card { margin-top: 2rem; }
.create-user-form .form-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; }
.modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.6); backdrop-filter: blur(5px); display: none; align-items: center; justify-content: center; z-index: 1000; opacity: 0; transition: opacity 0.3s ease; }
.modal-overlay.show { display: flex; opacity: 1; }
.modal-content { background: white; border-radius: 20px; box-shadow: 0 10px 40px rgba(0,0,0,0.2); width: 90%; max-width: 500px; overflow: hidden; transform: translateY(-20px); transition: transform 0.3s ease; }
.modal-overlay.show .modal-content { transform: translateY(0); }
.modal-header { padding: 1.5rem; background: linear-gradient(135deg, #1e40af, #3b82f6); color: white; display: flex; justify-content: space-between; align-items: center; }
.modal-header h3 { margin: 0; }
.close-modal-btn { background: none; border: none; font-size: 2rem; color: white; cursor: pointer; }
.modal-body { padding: 2rem; }
.modal-footer { padding: 1.5rem; background-color: #f7f7f7; display: flex; justify-content: flex-end; gap: 1rem; }
.no-results-card { background: #f8f9fa; border-radius: 15px; padding: 2rem; text-align: center; color: #6c757d; }
.pagination-container { display: flex; justify-content: center; margin-top: 2rem; }
.pagination { list-style: none; padding: 0; display: flex; gap: 0.5rem; }
.page-item .page-link { color: #1e40af; border: 1px solid #ddd; padding: 0.5rem 1rem; text-decoration: none; border-radius: 5px; }
.page-item.active .page-link { background-color: #1e40af; color: white; }
.notification-container { position: fixed; top: 20px; right: 20px; z-index: 9999; }
.notification { padding: 15px 25px; border-radius: 8px; color: white; box-shadow: 0 5px 15px rgba(0,0,0,0.2); opacity: 0; transform: translateX(100%); transition: all 0.5s ease; margin-bottom: 10px; }
.notification.show { opacity: 1; transform: translateX(0); }
.notification-success { background: linear-gradient(135deg, #28a745, #20c997); }
.notification-danger { background: linear-gradient(135deg, #dc3545, #fd7e14); }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tableContainer = document.getElementById('users-table-container');
    const filterForm = document.getElementById('filter-form');
    const createUserForm = document.getElementById('create-user-form');
    const modal = document.getElementById('filter-modal');
    
    // --- Gestion de la modale de filtre ---
    const openBtn = document.getElementById('open-filter-btn');
    const closeBtn = document.querySelector('.close-modal-btn');
    const resetBtn = document.getElementById('reset-filter-btn');
    if(openBtn) openBtn.onclick = () => modal.classList.add('show');
    if(closeBtn) closeBtn.onclick = () => modal.classList.remove('show');
    if(resetBtn) resetBtn.addEventListener('click', () => {
        filterForm.reset();
        fetchUsers(1);
        modal.classList.remove('show');
    });
    window.onclick = (event) => {
        if (event.target == modal) modal.classList.remove('show');
    };

    // --- Gestion de l'interrupteur du formulaire de création ---
    const createUserSwitch = document.getElementById('isAdminCheck');
    const createUserRoleText = document.getElementById('create-user-role-text');

    if (createUserSwitch && createUserRoleText) {
        createUserSwitch.addEventListener('change', function() {
            createUserRoleText.textContent = this.checked 
                ? "Promouvoir en tant qu'administrateur" 
                : "Promouvoir en tant qu'administrateur";
        });
    }
    
    // --- Fonction principale pour charger les utilisateurs ---
    async function fetchUsers(page = 1) {
        tableContainer.style.opacity = '0.5';
        const formData = new FormData(filterForm);
        const params = new URLSearchParams();
        params.append('page', page);
        if (formData.get('filter_role') !== '') {
            params.append('filter_role', formData.get('filter_role'));
        }

        try {
            const response = await fetch(`<?= BASE_URL ?>/admin/api/users?${params.toString()}`);
            if (!response.ok) throw new Error('Erreur réseau');
            tableContainer.innerHTML = await response.text();
            initActionListeners(); 
        } catch (error) {
            tableContainer.innerHTML = `<p class="no-results-card">Erreur: ${error.message}</p>`;
        } finally {
            tableContainer.style.opacity = '1';
        }
    }

    // --- Initialisation de tous les listeners d'action ---
    function initActionListeners() {
        // Pagination
        tableContainer.querySelectorAll('.page-link').forEach(link => {
            link.addEventListener('click', e => {
                e.preventDefault();
                fetchUsers(e.target.dataset.page);
            });
        });

        // Suppression d'utilisateur
        tableContainer.querySelectorAll('.delete-user-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                if (!confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')) return;

                const row = this.closest('tr');
                fetch(this.action, { method: 'POST', body: new FormData(this) })
                    .then(res => res.json())
                    .then(data => {
                        showNotification(data.message, data.success ? 'success' : 'danger');
                        if (data.success) {
                            row.classList.add('fading-out');
                            row.addEventListener('transitionend', () => row.remove());
                        }
                    }).catch(err => showNotification('Erreur de connexion.', 'danger'));
            });
        });

        // Changement de rôle
        tableContainer.querySelectorAll('.role-update-form .form-check-input').forEach(input => {
            input.addEventListener('change', function() {
                const form = this.closest('form');
                fetch(form.action, { method: 'POST', body: new FormData(form) })
                    .then(res => res.json())
                    .then(data => {
                        showNotification(data.message, data.success ? 'success' : 'danger');
                        if (data.success) {
                            const roleTextSpan = this.closest('.role-switch-container').querySelector('.role-text');
                            if (roleTextSpan) {
                                roleTextSpan.textContent = this.checked ? 'Admin' : 'Utilisateur';
                            }
                        } else {
                            this.checked = !this.checked;
                        }
                    }).catch(err => {
                        showNotification('Erreur de connexion.', 'danger');
                        this.checked = !this.checked;
                    });
            });
        });
    }

    // --- Listeners pour les filtres et la création ---
    filterForm.addEventListener('submit', (e) => {
        e.preventDefault();
        fetchUsers(1);
        modal.classList.remove('show');
    });

    createUserForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const submitButton = this.querySelector('button[type="submit"]');
        const originalButtonHTML = submitButton.innerHTML;

        submitButton.disabled = true;
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Création en cours...';

        fetch(this.action, { method: 'POST', body: new FormData(this) })
            .then(res => res.json())
            .then(data => {
                showNotification(data.message, data.success ? 'success' : 'danger');
                if (data.success) {
                    this.reset();
                    if(createUserRoleText) createUserRoleText.textContent = "Promouvoir en tant qu'administrateur";
                    fetchUsers(1);
                }
            }).catch(err => showNotification('Erreur de connexion.', 'danger'))
            .finally(() => {
                submitButton.disabled = false;
                submitButton.innerHTML = originalButtonHTML;
            });
    });
    
    initActionListeners();
});

function showNotification(message, type = 'success') {
    const container = document.getElementById('ajax-notification');
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.textContent = message;
    container.appendChild(notification);
    setTimeout(() => notification.classList.add('show'), 10);
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => { if (notification.parentNode) notification.parentNode.removeChild(notification); }, 500);
    }, 4000);
}
</script>

<?php require_once ROOT_PATH . '/app/views/partials/footer.php'; ?>