<?php
session_start();
require_once '../models/Notification.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (isset($_POST["updateMailPref"])) {
        $user_id = $_SESSION['user_id'];
        $notif_mail = isset($_POST["notif_email"]) ? 1 : 0;

        $notification = new Notification();
        $current_pref = $notification->getMailPreference($user_id);

        if ($notif_mail !== $current_pref) {
            $notification->updateMailPreference($user_id, $notif_mail);

            $user_email = $notification->getUserEmail($user_id);

            $subject = $notif_mail ? "Notifications activées" : "Notifications désactivées";
            $body = $notif_mail
                ? "Vous avez activé la réception des notifications par mail."
                : "Vous avez désactivé la réception des notifications par mail.";

            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.example.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'tonmail@example.com';
                $mail->Password = 'motdepasse';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                $mail->setFrom('tonmail@example.com', 'NomDuSite');
                $mail->addAddress($user_email);
                $mail->isHTML(true);
                $mail->Subject = $subject;
                $mail->Body = "<p>$body</p>";
                $mail->send();

                $_SESSION['notif_success'] = $subject;
            } catch (Exception $e) {
                $_SESSION['notif_error'] = "Erreur lors de l'envoi de l'email : " . $mail->ErrorInfo;
            }
        }
        header("Location: ../views/notification.php");
        exit;
    }

    if (isset($_POST["deleteNotification"])) {
        $notif_id = $_POST["notification_id"];
        $notification = new Notification();
        $notification->deleteNotification($notif_id);
    }
}