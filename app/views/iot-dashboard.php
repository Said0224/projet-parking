<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? htmlspecialchars($page_title) : 'Dashboard IoT - Parking Intelligent' ?></title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            color: white;
            margin-bottom: 30px;
        }

        .header h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .header p {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .nav-bar {
            background: white;
            border-radius: 10px;
            padding: 1rem 2rem;
            margin-bottom: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .nav-links {
            display: flex;
            gap: 2rem;
        }

        .nav-links a {
            text-decoration: none;
            color: #667eea;
            font-weight: 600;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            transition: background 0.3s;
        }

        .nav-links a:hover, .nav-links a.active {
            background: #667eea;
            color: white;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .stat-value {
            font-size: 2rem;
            font-weight: bold;
            color: #667eea;
        }

        .stat-value.free { color: #28a745; }
        .stat-value.occupied { color: #dc3545; }

        .stat-label {
            color: #666;
            margin-top: 5px;
        }

        .sensor-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }

        .sensor-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        .sensor-card:hover {
            transform: translateY(-5px);
        }

        .card-header {
            padding: 20px;
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-title {
            font-size: 1.3rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .add-btn {
            background: rgba(255,255,255,0.2);
            border: none;
            color: white;
            padding: 8px 12px;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .add-btn:hover {
            background: rgba(255,255,255,0.3);
        }

        .table-container {
            max-height: 300px;
            overflow-y: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #f8f9fa;
            padding: 12px 8px;
            text-align: center;
            font-weight: 600;
            color: #495057;
            border-bottom: 2px solid #dee2e6;
            position: sticky;
            top: 0;
        }

        td {
            padding: 10px 8px;
            text-align: center;
            border-bottom: 1px solid #dee2e6;
        }

        tr:hover td {
            background: #f8f9fa;
        }

        .badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .badge-success {
            background-color: rgba(40, 167, 69, 0.2);
            color: #28a745;
        }

        .badge-danger {
            background-color: rgba(220, 53, 69, 0.2);
            color: #dc3545;
        }

        .action-buttons {
            display: flex;
            gap: 5px;
            justify-content: center;
        }

        .btn {
            padding: 5px 8px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.8rem;
            transition: all 0.2s ease;
        }

        .btn-edit {
            background: #28a745;
            color: white;
        }

        .btn-delete {
            background: #dc3545;
            color: white;
        }

        .btn:hover {
            opacity: 0.8;
            transform: scale(1.05);
        }

        .alert {
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .alert-success {
            background-color: rgba(40, 167, 69, 0.2);
            border-left: 5px solid #28a745;
            color: #155724;
        }

        .alert-danger {
            background-color: rgba(220, 53, 69, 0.2);
            border-left: 5px solid #dc3545;
            color: #721c24;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
        }

        .modal-content {
            background: white;
            margin: 10% auto;
            padding: 30px;
            border-radius: 15px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #555;
        }

        .form-group input, .form-group select {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
        }

        .form-group input:focus, .form-group select:focus {
            outline: none;
            border-color: #667eea;
        }

        .btn-primary {
            background: #667eea;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1rem;
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-car"></i> Dashboard Parking Intelligent</h1>
            <p>Surveillance en temps réel de votre parking connecté</p>
        </div>

        <!-- Navigation -->
        <div class="nav-bar">
            <div class="nav-links">
                <a href="/dashboard" class="active">Parking</a>
                <a href="/iot-dashboard">IoT Dashboard</a>
                <a href="/logout">Déconnexion</a>
            </div>
            <span>👤 <?= htmlspecialchars($_SESSION['user_email']) ?></span>
        </div>

        <?php if (!empty($errorMessage)): ?>
        <div class="alert alert-danger">
            <div><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($errorMessage) ?></div>
        </div>
        <?php endif; ?>

        <?php if (!empty($successMessage)): ?>
        <div class="alert alert-success">
            <div><i class="fas fa-check-circle"></i> <?= htmlspecialchars($successMessage) ?></div>
        </div>
        <?php endif; ?>

        <!-- Statistiques -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-value free"><?= $freeSpaces ?></div>
                <div class="stat-label">Places Libres</div>
            </div>
            <div class="stat-card">
                <div class="stat-value occupied"><?= $occupiedSpaces ?></div>
                <div class="stat-label">Places Occupées</div>
            </div>
            <div class="stat-card">
                <div class="stat-value"><?= $totalSpaces ?></div>
                <div class="stat-label">Total Places</div>
            </div>
            <div class="stat-card">
                <div class="stat-value"><?= count($leds) ?></div>
                <div class="stat-label">LEDs Actives</div>
            </div>
        </div>

        <!-- Grille des capteurs et actionneurs -->
        <div class="sensor-grid">
            <!-- Capteurs de Proximité (Places de Parking) -->
            <div class="sensor-card">
                <div class="card-header">
                    <div class="card-title">
                        <i class="fas fa-car sensor-icon"></i>
                        Places de Parking
                    </div>
                    <button class="add-btn" onclick="openModal('create', 'capteurProximite')">
                        <i class="fas fa-plus"></i> Ajouter
                    </button>
                </div>
                
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Place</th>
                                <th>Date</th>
                                <th>Heure</th>
                                <th>État</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($parkingSpaces as $space): ?>
                                <tr>
                                    <td><?= htmlspecialchars($space['id']) ?></td>
                                    <td><strong>Place <?= htmlspecialchars($space['place']) ?></strong></td>
                                    <td><?= htmlspecialchars($space['date']) ?></td>
                                    <td><?= htmlspecialchars($space['heure']) ?></td>
                                    <td>
                                        <?php if ($space['valeur'] == 't' || $space['valeur'] == true): ?>
                                            <span class="badge badge-danger"><i class="fas fa-car"></i> Occupée</span>
                                        <?php else: ?>
                                            <span class="badge badge-success"><i class="fas fa-check"></i> Libre</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="action-buttons">
                                        <button class="btn btn-edit" onclick="openModal('edit', 'capteurProximite', <?= htmlspecialchars(json_encode($space)) ?>)">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-delete" onclick="deleteRecord('capteurProximite', <?= $space['id'] ?>)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- LEDs de Signalisation -->
            <div class="sensor-card">
                <div class="card-header">
                    <div class="card-title">
                        <i class="fas fa-lightbulb sensor-icon"></i>
                        LEDs de Signalisation
                    </div>
                    <button class="add-btn" onclick="openModal('create', 'LED')">
                        <i class="fas fa-plus"></i> Ajouter
                    </button>
                </div>
                
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>État</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($leds as $led): ?>
                                <tr>
                                    <td><?= htmlspecialchars($led['id']) ?></td>
                                    <td>
                                        <?php if ($led['etat'] == 't' || $led['etat'] == true): ?>
                                            <span class="badge badge-success"><i class="fas fa-lightbulb"></i> Allumée</span>
                                        <?php else: ?>
                                            <span class="badge badge-danger"><i class="fas fa-times"></i> Éteinte</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="action-buttons">
                                        <button class="btn btn-edit" onclick="openModal('edit', 'LED', <?= htmlspecialchars(json_encode($led)) ?>)">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Informations OLED -->
            <?php if ($oledData): ?>
            <div class="sensor-card">
                <div class="card-header">
                    <div class="card-title">
                        <i class="fas fa-display sensor-icon"></i>
                        Affichage OLED
                    </div>
                </div>
                
                <div style="padding: 20px;">
                    <p><strong>Places disponibles:</strong> <?= htmlspecialchars($oledData['places_dispo']) ?></p>
                    <p><strong>Bornes de recharge:</strong> <?= htmlspecialchars($oledData['bornes_dispo']) ?></p>
                    <p><strong>Prix parking:</strong> <?= htmlspecialchars($oledData['prix_parking']) ?>€</p>
                    <p><strong>Prix recharge:</strong> <?= htmlspecialchars($oledData['prix_recharge']) ?>€</p>
                    <p><strong>Dernière mise à jour:</strong> <?= htmlspecialchars($oledData['heure']) ?></p>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Modal pour CRUD -->
    <div id="crudModal" class="modal">
        <div class="modal-content">
            <h2 id="modalTitle">Ajouter une donnée</h2>
            <form id="crudForm" method="POST">
                <input type="hidden" id="action" name="action">
                <input type="hidden" id="table" name="table">
                <input type="hidden" id="recordId" name="id">
                
                <div id="dynamicFields"></div>
                
                <div style="display: flex; gap: 10px; justify-content: flex-end; margin-top: 20px;">
                    <button type="button" class="btn-secondary" onclick="closeModal()">Annuler</button>
                    <button type="submit" class="btn-primary" id="submitBtn">Ajouter</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal(action, table, data = null) {
            const modal = document.getElementById('crudModal');
            const form = document.getElementById('crudForm');
            const title = document.getElementById('modalTitle');
            const submitBtn = document.getElementById('submitBtn');
            const dynamicFields = document.getElementById('dynamicFields');
            
            dynamicFields.innerHTML = '';
            
            document.getElementById('action').value = action;
            document.getElementById('table').value = table;
            
            if (action === 'create') {
                title.textContent = `Ajouter - ${table}`;
                submitBtn.textContent = 'Ajouter';
                form.reset();
            } else if (action === 'edit' && data) {
                title.textContent = `Modifier - ${table}`;
                submitBtn.textContent = 'Modifier';
                document.getElementById('recordId').value = data.id;
            }
            
            // Générer les champs en fonction de la table
            if (table === 'capteurProximite') {
                const fieldHtml = `
                    <div class="form-group">
                        <label for="place">Numéro de place :</label>
                        <input type="number" id="place" name="place" value="${data ? data.place : ''}" required min="1">
                    </div>
                    <div class="form-group">
                        <label for="valeur">État :</label>
                        <select id="valeur" name="valeur" required>
                            <option value="false" ${data && (data.valeur === false || data.valeur === 'f') ? 'selected' : ''}>Libre</option>
                            <option value="true" ${data && (data.valeur === true || data.valeur === 't') ? 'selected' : ''}>Occupée</option>
                        </select>
                    </div>
                `;
                dynamicFields.innerHTML = fieldHtml;
            } else if (table === 'LED') {
                const fieldHtml = `
                    <div class="form-group">
                        <label for="etat">État :</label>
                        <select id="etat" name="etat" required>
                            <option value="false" ${data && (data.etat === false || data.etat === 'f') ? 'selected' : ''}>Éteinte</option>
                            <option value="true" ${data && (data.etat === true || data.etat === 't') ? 'selected' : ''}>Allumée</option>
                        </select>
                    </div>
                `;
                dynamicFields.innerHTML = fieldHtml;
            }
            
            modal.style.display = 'block';
        }

        function closeModal() {
            document.getElementById('crudModal').style.display = 'none';
        }

        function deleteRecord(table, id) {
            if (confirm('Êtes-vous sûr de vouloir supprimer cette donnée ?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = `
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="table" value="${table}">
                    <input type="hidden" name="id" value="${id}">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }

        // Fermer le modal en cliquant à l'extérieur
        window.onclick = function(event) {
            const modal = document.getElementById('crudModal');
            if (event.target === modal) {
                closeModal();
            }
        }

        // Auto-refresh toutes les 30 secondes
        setInterval(() => {
            location.reload();
        }, 30000);
    </script>
</body>
</html>