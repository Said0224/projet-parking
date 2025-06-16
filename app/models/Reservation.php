<?php
require_once ROOT_PATH . '/config/database.php';

class Reservation {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function createReservation($user_id, $spot_id, $start_time, $end_time) {
        // Vérifier si la place est disponible
        if (!$this->isSpotAvailable($spot_id, $start_time, $end_time)) {
            return false;
        }
        
        $query = "INSERT INTO reservations (user_id, spot_id, start_time, end_time, status, created_at, updated_at) 
                  VALUES (?, ?, ?, ?, 'active', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$user_id, $spot_id, $start_time, $end_time]);
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
    
    public function isSpotAvailable($spot_id, $start_time, $end_time) {
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
        $query = "UPDATE reservations SET status = 'cancelled', updated_at = CURRENT_TIMESTAMP 
                  WHERE id = ? AND user_id = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$id, $user_id]);
    }
}
?>