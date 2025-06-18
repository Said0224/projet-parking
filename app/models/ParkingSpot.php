<?php
require_once ROOT_PATH . '/config/database.php';

class ParkingSpot {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function getAllSpots() {
        $query = "SELECT * FROM parking_spots ORDER BY spot_number";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getdisponibleSpots() {
        $query = "SELECT * FROM parking_spots WHERE status = 'disponible' ORDER BY spot_number";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getSpotById($id) {
        $query = "SELECT * FROM parking_spots WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function updateSpot($id, $data) {
        $query = "UPDATE parking_spots SET 
                  status = ?, 
                  price_per_hour = ?, 
                  has_charging_station = ?, 
                  updated_at = CURRENT_TIMESTAMP 
                  WHERE id = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            $data['status'],
            $data['price_per_hour'],
            $data['has_charging_station'],
            $id
        ]);
    }
    
    public function createSpot($data) {
        $query = "INSERT INTO parking_spots (spot_number, status, price_per_hour, has_charging_station, created_at, updated_at) 
                  VALUES (?, ?, ?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            $data['spot_number'],
            $data['status'],
            $data['price_per_hour'],
            $data['has_charging_station']
        ]);
    }
    
    public function deleteSpot($id) {
        $query = "DELETE FROM parking_spots WHERE id = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$id]);
    }


    public function updateSpotStatusByNumber($spot_number, $status) {
        $query = "UPDATE parking_spots SET 
                  status = ?, 
                  updated_at = CURRENT_TIMESTAMP 
                  WHERE spot_number = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            $status,
            $spot_number
        ]);
    }


    public function getSpotByNumber($spot_number) {
        $query = "SELECT * FROM parking_spots WHERE spot_number = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$spot_number]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getPaginatedSpots($page = 1, $limit = 6, $filters = []) {
        $offset = ($page - 1) * $limit;

        $sql = "SELECT * FROM parking_spots";
        
        $where = [];
        $params = [];

        if (!empty($filters['status'])) {
            $where[] = "status = ?";
            $params[] = $filters['status'];
        }
        if (isset($filters['has_charging_station']) && $filters['has_charging_station'] !== '') {
            $where[] = "has_charging_station = ?";
            $params[] = $filters['has_charging_station'];
        }

        if (!empty($where)) {
            $sql .= " WHERE " . implode(" AND ", $where);
        }

        $sql .= " ORDER BY spot_number ASC LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // NOUVELLE MÉTHODE POUR COMPTER LE TOTAL
    public function getTotalSpotsCount($filters = []) {
        $sql = "SELECT COUNT(id) FROM parking_spots";
        
        $where = [];
        $params = [];

        if (!empty($filters['status'])) {
            $where[] = "status = ?";
            $params[] = $filters['status'];
        }
        if (isset($filters['has_charging_station']) && $filters['has_charging_station'] !== '') {
            $where[] = "has_charging_station = ?";
            $params[] = $filters['has_charging_station'];
        }

        if (!empty($where)) {
            $sql .= " WHERE " . implode(" AND ", $where);
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }
}
?>