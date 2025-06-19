<?php
// Fichier : app/controllers/NotificationController.php

// Inclusion des classes PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

// Inclusion de l'autoloader de Composer (très important !)
require_once ROOT_PATH . '/vendor/autoload.php';

// Inclusion des modèles nécessaires
require_once ROOT_PATH . '/app/models/Notification.php';
require_once ROOT_PATH . '/app/models/User.php';

class NotificationController {
    private $notificationModel;
    private $userModel;

    public function __construct() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
        $this->notificationModel = new Notification();
        $this->userModel = new User();
    }

    /**
     * Affiche la page des notifications.
     */
    public function index() {
        $user_id = $_SESSION['user_id'];
        $page_title = "Mes Notifications";
        
        $notifications = $this->notificationModel->getNotifications($user_id);
        $email_preference = $this->notificationModel->getMailPreference($user_id);
        
        // Marquer les notifications comme lues
        $this->notificationModel->markAsRead($user_id);

        require_once ROOT_PATH . '/app/views/notification.php';
    }

    /**
     * Met à jour la préférence de réception d'e-mails et envoie un mail de confirmation.
     */
    public function updateMailPreference() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/notifications');
            exit;
        }

        $user_id = $_SESSION['user_id'];
        $new_preference = isset($_POST['notif_email']) ? 1 : 0;
        $current_preference = $this->notificationModel->getMailPreference($user_id);

        // On ne fait rien si la préférence n'a pas changé
        if ($new_preference == $current_preference) {
            header('Location: ' . BASE_URL . '/notifications');
            exit;
        }

        // Mettre à jour la préférence dans la base de données
        if ($this->notificationModel->updateMailPreference($user_id, $new_preference)) {
            $_SESSION['notif_success'] = "Préférences de notification mises à jour.";
            
            // --- Logique d'envoi d'e-mail ---
            $user = $this->userModel->findById($user_id);
            if ($user && $user['email']) {
                $this->sendConfirmationEmail($user['email'], $new_preference);
            }
        } else {
            $_SESSION['notif_error'] = "Erreur lors de la mise à jour des préférences.";
        }

        header('Location: ' . BASE_URL . '/notifications');
        exit;
    }

    /**
     * Fonction privée pour envoyer l'e-mail de confirmation.
     */
    private function sendConfirmationEmail($user_email, $new_preference) {
        $mail = new PHPMailer(true);

        try {
            // --- CONFIGURATION SMTP (À MODIFIER AVEC VOS VRAIS IDENTIFIANTS) ---
            
            // Active le debug pour voir les erreurs. Mettre à 0 en production.
            $mail->SMTPDebug = 0; // Mettre à SMTP::DEBUG_SERVER pour voir le dialogue complet

            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com'; // Ex: 'smtp.gmail.com' ou votre serveur
            $mail->SMTPAuth   = true;
            $mail->Username   = 'appg9e@gmail.com'; // VOTRE ADRESSE EMAIL
            $mail->Password   = 'sxwm ltbj jutq awog'; // VOTRE MOT DE PASSE D'APPLICATION (voir ci-dessous)
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;
            
            // --- FIN DE LA CONFIGURATION SMTP ---

            // Destinataires
            $mail->setFrom('appg9e@gmail.com', 'Parking Intelligent ISEP');
            $mail->addAddress($user_email);

            // Contenu de l'e-mail
            $mail->isHTML(true);
            $subject = $new_preference ? "Activation des notifications par e-mail" : "Désactivation des notifications par e-mail";
            $body = $new_preference 
                ? "Bonjour,<br><br>Vous avez activé la réception des notifications par e-mail pour votre compte sur le Parking Intelligent ISEP."
                : "Bonjour,<br><br>Vous avez désactivé la réception des notifications par e-mail pour votre compte sur le Parking Intelligent ISEP.";
            
            $mail->Subject = $subject;
            $mail->Body    = $body;
            $mail->AltBody = strip_tags($body);

            $mail->send();
            $_SESSION['notif_success'] = "Préférences mises à jour et e-mail de confirmation envoyé.";

        } catch (Exception $e) {
            // En cas d'erreur, on stocke un message d'erreur plus détaillé
            $_SESSION['notif_error'] = "Le message n'a pas pu être envoyé. Erreur Mailer: {$mail->ErrorInfo}";
            // Pour le debug, vous pouvez aussi logger l'erreur
            error_log("Erreur PHPMailer: " . $e->getMessage());
        }
    }
}