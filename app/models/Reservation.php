<?php
require_once ROOT_PATH . '/config/database.php';
require_once ROOT_PATH . '/app/models/Notification.php';    
require_once ROOT_PATH . '/vendor/autoload.php'; // Assurez-vous que l'autoloader de Composer est inclus

// Inclusion de l'autoloader de Composer (très important !)
require_once ROOT_PATH . '/vendor/autoload.php';

require_once ROOT_PATH . '/app/models/Notification.php';
require_once ROOT_PATH . '/app/models/User.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

class Reservation {
    private $db;
    
    public function __construct() {
        $this->db = DatabaseManager::getConnection('local');
    }
    
    public function createReservation($user_id, $spot_id, $start_time, $end_time) {
    if (!$this->isSpotdisponible($spot_id, $start_time, $end_time)) {
        return false;
    }

    $query = "INSERT INTO reservations (user_id, spot_id, start_time, end_time, status, created_at, updated_at) 
              VALUES (?, ?, ?, ?, 'active', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)";
    $stmt = $this->db->prepare($query);

    if ($stmt->execute([$user_id, $spot_id, $start_time, $end_time])) {
        $notification = new Notification();

        $contenu = "Réservation confirmée : Place #$spot_id, du $start_time au $end_time.";
        $notification->createNotification($user_id, 'info', $contenu);

        // Vérifie la préférence mail et envoie le reçu
        if ($notification->getMailPreference($user_id)) {
            $this->sendMailReceipt($user_id, $spot_id, $start_time, $end_time);
        }

        return true;
    }

    return false;
}
  
    public function getUserReservations($user_id) {
        $query = "SELECT r.*, ps.spot_number, ps.price_per_hour, ps.has_charging_station 
                  FROM reservations r 
                  JOIN parking_spots ps ON r.spot_id = ps.id 
                  WHERE r.user_id = ? 
                  ORDER BY r.start_time DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getAllReservations() {
        $query = "SELECT r.*, u.email, u.nom, u.prenom, ps.spot_number 
                  FROM reservations r 
                  JOIN users u ON r.user_id = u.id 
                  JOIN parking_spots ps ON r.spot_id = ps.id 
                  ORDER BY r.start_time DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function isSpotdisponible($spot_id, $start_time, $end_time) {
        $query = "SELECT COUNT(*) FROM reservations 
                  WHERE spot_id = ? 
                  AND status = 'active' 
                  AND (
                      (start_time <= ? AND end_time > ?) OR
                      (start_time < ? AND end_time >= ?) OR
                      (start_time >= ? AND end_time <= ?)
                  )";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$spot_id, $start_time, $start_time, $end_time, $end_time, $start_time, $end_time]);
        return $stmt->fetchColumn() == 0;
    }
    
    public function cancelReservation($id, $user_id) {
        $query = "UPDATE reservations SET status = 'annulée', updated_at = CURRENT_TIMESTAMP 
                  WHERE id = ? AND user_id = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$id, $user_id]);
    }

    public function getPaginatedReservations($page = 1, $limit = 10, $filters = []) {
        $offset = ($page - 1) * $limit;
        
        $sql = "SELECT r.*, u.email, u.nom, u.prenom, ps.spot_number 
                FROM reservations r 
                JOIN users u ON r.user_id = u.id 
                JOIN parking_spots ps ON r.spot_id = ps.id";
        
        $where = [];
        $params = [];
        
        // --- NOUVEAUX FILTRES ---
        if (!empty($filters['date'])) {
            // On filtre sur la date de début de la réservation
            $where[] = "DATE(r.start_time) = ?";
            $params[] = $filters['date'];
        }

        if (!empty($filters['spot_id'])) {
            $where[] = "r.spot_id = ?";
            $params[] = $filters['spot_id'];
        }
        // --- FIN DES NOUVEAUX FILTRES ---
        
        if (!empty($filters['status'])) {
            $where[] = "r.status = ?";
            $params[] = $filters['status'];
        }
        
        if (!empty($where)) {
            $sql .= " WHERE " . implode(" AND ", $where);
        }
        
        $sql .= " ORDER BY r.start_time DESC LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // NOUVELLE MÉTHODE POUR COMPTER LE TOTAL
    public function getTotalReservationsCount($filters = []) {
        $sql = "SELECT COUNT(r.id) 
                FROM reservations r
                JOIN users u ON r.user_id = u.id 
                JOIN parking_spots ps ON r.spot_id = ps.id";
                
        $where = [];
        $params = [];
        
        // --- NOUVEAUX FILTRES ---
        if (!empty($filters['date'])) {
            $where[] = "DATE(r.start_time) = ?";
            $params[] = $filters['date'];
        }
        
        if (!empty($filters['spot_id'])) {
            $where[] = "r.spot_id = ?";
            $params[] = $filters['spot_id'];
        }
        // --- FIN DES NOUVEAUX FILTRES ---

        if (!empty($filters['status'])) {
            $where[] = "r.status = ?";
            $params[] = $filters['status'];
        }
        
        if (!empty($where)) {
            $sql .= " WHERE " . implode(" AND ", $where);
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }
    
    private function sendMailReceipt($user_id, $spot_id, $start_time, $end_time) {
    require_once ROOT_PATH . '/app/models/User.php';
    $userModel = new User();
    $user = $userModel->findById($user_id);

    if (!$user || !filter_var($user['email'], FILTER_VALIDATE_EMAIL)) return;

    // Générer le PDF avec Dompdf
    ob_start();
    ?>
    <h1>Reçu de Réservation</h1>
    <p>Nom : <?= $user['prenom'] . ' ' . $user['nom'] ?></p>
    <p>Place : <?= $spot_id ?></p>
    <p>Début : <?= $start_time ?></p>
    <p>Fin : <?= $end_time ?></p>
    <p>Merci pour votre réservation sur Parking Intelligent.</p>
    <?php
    $html = ob_get_clean();

    $dompdf = new \Dompdf\Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $pdfOutput = $dompdf->output();

    // Envoi de mail avec pièce jointe
    $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'teteatete.innowave@gmail.com';
        $mail->Password = 'srod bwtb rnhg xmgw';
        $mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('teteatete.innowave@gmail.com', 'Tête à Tête Parking');
        $mail->addAddress($user['email'], $user['prenom'] . ' ' . $user['nom']);

        $mail->isHTML(true);
        $mail->Subject = 'Votre reçu de réservation - Parking Intelligent';
        $mail->Body = "Veuillez trouver ci-joint votre reçu de réservation au format PDF.";
        $mail->AltBody = "Voir le reçu en pièce jointe.";

        $mail->addStringAttachment($pdfOutput, 'recu_reservation.pdf');

        $mail->send();
    } catch (\PHPMailer\PHPMailer\Exception $e) {
        error_log("Erreur PHPMailer avec PDF : " . $mail->ErrorInfo);
    }
}



}
?>