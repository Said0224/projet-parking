<?php
session_start();
require_once '../models/Notification.php';

$notification = new Notification();
$user_id = $_SESSION['user_id'];
$notifications = $notification->getNotifications($user_id);
$userPref = $notification->getMailPreference($user_id);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes notifications</title>
    <style>
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
        }

        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
        }

        .toggle-switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 30px;
        }

        .toggle-switch input {
            display: none;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0; left: 0;
            right: 0; bottom: 0;
            background-color: #ccc;
            transition: 0.4s;
            border-radius: 34px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 24px;
            width: 24px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: 0.4s;
            border-radius: 50%;
        }

        input:checked + .slider {
            background-color: #4CAF50;
        }

        input:checked + .slider:before {
            transform: translateX(30px);
        }
    </style>
</head>
<body>

<?php
if (isset($_SESSION['notif_success'])) {
    echo "<div class='alert-success'>{$_SESSION['notif_success']}</div>";
    unset($_SESSION['notif_success']);
}
if (isset($_SESSION['notif_error'])) {
    echo "<div class='alert-error'>{$_SESSION['notif_error']}</div>";
    unset($_SESSION['notif_error']);
}
?>

<h2>Mes notifications</h2>

<form method="post" action="../controllers/notificationController.php">
    <label>
        Recevoir les notifications par mail :
        <label class="toggle-switch">
            <input type="checkbox" name="notif_email" value="1" onchange="this.form.submit()" <?php if ($userPref) echo 'checked'; ?>>
            <span class="slider"></span>
        </label>
    </label>
    <input type="hidden" name="updateMailPref" value="1">
</form>

<ul>
    <?php foreach ($notifications as $notif): ?>
        <li>
            <?php echo htmlspecialchars($notif['contenu']); ?>
            <form method="post" action="../controllers/notificationController.php" style="display:inline;">
                <input type="hidden" name="notification_id" value="<?php echo $notif['id']; ?>">
                <button type="submit" name="deleteNotification">Supprimer</button>
            </form>

            <?php if ($notif['type'] === 'reservation_payee'): ?>
                <a href="../receipts/genererRecu.php?id_reservation=<?php echo $notif['reservation_id']; ?>" target="_blank">
                    Télécharger le reçu
                </a>
            <?php endif; ?>
        </li>
    <?php endforeach; ?>
</ul>

</body>
</html>
