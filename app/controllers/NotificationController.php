<?php
// Fichier : app/controllers/NotificationController.php

// Inclusion des classes PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

// Inclusion de l'autoloader de Composer (très important !)
require_once ROOT_PATH . '/vendor/autoload.php';

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
        // ===== DÉBUT DE LA MODIFICATION =====
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Méthode non autorisée.']);
            exit;
        }

        $user_id = $_SESSION['user_id'];
        $new_preference = isset($_POST['notif_email']) ? 1 : 0;
        
        if ($this->notificationModel->updateMailPreference($user_id, $new_preference)) {
            $user = $this->userModel->findById($user_id);
            if ($user && $user['email']) {
                $mailSent = $this->sendConfirmationEmail($user['email'], $new_preference);
                if ($mailSent) {
                    echo json_encode(['success' => true, 'message' => 'Préférences mises à jour et e-mail de confirmation envoyé.']);
                } else {
                    echo json_encode(['success' => true, 'message' => 'Préférences mises à jour, mais l\'e-mail n\'a pas pu être envoyé.']);
                }
            } else {
                 echo json_encode(['success' => true, 'message' => 'Préférences mises à jour.']);
            }
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Erreur lors de la mise à jour des préférences.']);
        }
        exit;
        // ===== FIN DE LA MODIFICATION =====
    }

    /**
     * Fonction privée pour envoyer l'e-mail de confirmation.
     * @return bool Vrai si l'email est envoyé, faux sinon.
     */
    private function sendConfirmationEmail($user_email, $new_preference) {
        $mail = new PHPMailer(true);

        try {
            // --- CONFIGURATION SMTP ---
            $mail->SMTPDebug = 0; 
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'appg9e@gmail.com';
            $mail->Password   = 'sxwm ltbj jutq awog';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;
            
            // --- Destinataires ---
            $mail->setFrom('appg9e@gmail.com', 'Parking Intelligent ISEP');
            $mail->addAddress($user_email);

            // --- Contenu de l'e-mail ---
            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            $subject = $new_preference ? "Activation des notifications par e-mail" : "Désactivation des notifications par e-mail";
            $body = $new_preference 
                ? "Bonjour,<br><br>Vous avez activé la réception des notifications par e-mail pour votre compte sur le Parking Intelligent ISEP."
                : "Bonjour,<br><br>Vous avez désactivé la réception des notifications par e-mail pour votre compte sur le Parking Intelligent ISEP.";
            
            $mail->Subject = $subject;
            $mail->Body    = $body;
            $mail->AltBody = strip_tags($body);

            $mail->send();
            return true;

        } catch (Exception $e) {
            error_log("Erreur PHPMailer: " . $e->getMessage());
            return false;
        }
    }
}