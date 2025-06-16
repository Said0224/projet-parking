<!-- HTML structure -->
<h2>Notifications</h2>

<div class="notification-header">
    <select id="notif-filter">
        <option value="all">Toutes</option>
        <option value="info">Infos</option>
        <option value="alerte">Alertes</option>
        <option value="erreur">Erreurs</option>
    </select>

    <button id="mark-all-read">Tout marquer comme lu</button>

    <label class="toggle-switch">
        <input type="checkbox" id="email-toggle" <?= $emailNotif ? 'checked' : '' ?>>
        <span class="slider"></span> Notifications par e-mail
    </label>
</div>

<div id="notification-list">
    <?php foreach ($notifications as $notif): ?>
        <div class="notif <?= !$notif['is_read'] ? 'unread' : '' ?>">
            <h4><?= htmlspecialchars($notif['titre']) ?></h4>
            <p><?= nl2br(htmlspecialchars($notif['contenu'])) ?></p>
            <span class="meta"><?= $notif['date'] ?> à <?= $notif['heure'] ?></span>
        </div>
    <?php endforeach; ?>
</div>

<!-- JavaScript -->
<script>
document.getElementById('mark-all-read').addEventListener('click', function () {
    fetch('/notification/mark-all-as-read', { method: 'POST' })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                document.querySelectorAll('.notif').forEach(n => n.classList.remove('unread'));
            }
        });
});

document.getElementById('notif-filter').addEventListener('change', function () {
    const type = this.value;
    if (type === 'all') {
        location.reload();
    } else {
        fetch(`/notification/filter/${type}`)
            .then(res => res.json())
            .then(data => {
                const container = document.getElementById('notification-list');
                container.innerHTML = '';
                data.forEach(n => {
                    container.innerHTML += `
                        <div class="notif ${n.is_read ? '' : 'unread'}">
                            <h4>${n.titre}</h4>
                            <p>${n.contenu}</p>
                            <span class="meta">${n.date} à ${n.heure}</span>
                        </div>`;
                });
            });
    }
});

document.getElementById('email-toggle').addEventListener('change', function () {
    fetch('/notification/toggle-email', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `enabled=${this.checked}`
    });
});
</script>


<!-- CSS -->
<style>
.notif {
    background: #fff;
    border: 1px solid #ddd;
    margin-bottom: 10px;
    padding: 10px;
    border-radius: 6px;
    animation: slideIn 0.3s ease;
}

.notif.unread {
    background-color: #f0f8ff;
    border-left: 4px solid #007BFF;
}

.meta {
    color: gray;
    font-size: 12px;
}

/* Animation */
@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Toggle Switch */
.toggle-switch {
    display: inline-block;
    margin-left: 20px;
    position: relative;
}

.toggle-switch input {
    display: none;
}

.toggle-switch .slider {
    width: 50px;
    height: 24px;
    background: #ccc;
    border-radius: 24px;
    display: inline-block;
    position: relative;
    cursor: pointer;
    transition: background 0.3s;
}

.toggle-switch .slider::before {
    content: '';
    position: absolute;
    left: 3px;
    top: 3px;
    width: 18px;
    height: 18px;
    background: white;
    border-radius: 50%;
    transition: transform 0.3s;
}

.toggle-switch input:checked + .slider {
    background: #28a745;
}

.toggle-switch input:checked + .slider::before {
    transform: translateX(26px);
}
</style>