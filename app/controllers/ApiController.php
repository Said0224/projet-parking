<?php
// Emplacement : C:\xampp\htdocs\projet-parking\app\controllers\ApiController.php

require_once ROOT_PATH . '/app/models/ParkingSpot.php';

class ApiController {

    private $parkingSpotModel;

    public function __construct() {
        $this->parkingSpotModel = new ParkingSpot();
    }

    /**
     * Met à jour le statut d'une place de parking depuis un capteur IoT.
     * Reçoit en POST : spot_number et status ('disponible' ou 'occupée').
     */
    public function updateSpotStatus() {
        // Définir le header pour une réponse en format JSON
        header('Content-Type: application/json');

        // S'assurer que la requête est de type POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405); // Method Not Allowed
            echo json_encode(['success' => false, 'message' => 'Méthode non autorisée. Seul POST est accepté.']);
            exit;
        }

        $spot_number = $_POST['spot_number'] ?? null;
        $status = $_POST['status'] ?? null;

        // Valider les données reçues
        if (empty($spot_number) || empty($status)) {
            http_response_code(400); // Bad Request
            echo json_encode(['success' => false, 'message' => 'Données manquantes : spot_number et status sont requis.']);
            exit;
        }

        if (!in_array($status, ['disponible', 'occupée'])) {
            http_response_code(400); // Bad Request
            echo json_encode(['success' => false, 'message' => 'Statut invalide. Utilisez "disponible" ou "occupée".']);
            exit;
        }

        // Mettre à jour la base de données
        try {
            if ($this->parkingSpotModel->updateSpotStatusByNumber($spot_number, $status)) {
                http_response_code(200); // OK
                echo json_encode(['success' => true, 'message' => "Place $spot_number mise à jour avec le statut $status."]);
            } else {
                http_response_code(500); // Internal Server Error
                echo json_encode(['success' => false, 'message' => "Erreur lors de la mise à jour de la place $spot_number."]);
            }
        } catch (Exception $e) {
            http_response_code(500);
            error_log("API Error updateSpotStatus: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Erreur interne du serveur.']);
        }
    }

    /**
     * Récupère le statut actuel d'une place de parking.
     * Reçoit en GET : spot_number
     */
    public function getSpotStatus() {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            http_response_code(405); // Method Not Allowed
            echo json_encode(['success' => false, 'message' => 'Méthode non autorisée. Seul GET est accepté.']);
            exit;
        }

        $spot_number = $_GET['spot_number'] ?? null;

        if (empty($spot_number)) {
            http_response_code(400); // Bad Request
            echo json_encode(['success' => false, 'message' => 'Le paramètre spot_number est requis.']);
            exit;
        }

        try {
            $spot = $this->parkingSpotModel->getSpotByNumber($spot_number);
            if ($spot) {
                http_response_code(200); // OK
                // On renvoie un objet JSON avec le statut de la place
                echo json_encode(['success' => true, 'status' => $spot['status']]);
            } else {
                http_response_code(404); // Not Found
                echo json_encode(['success' => false, 'message' => "La place $spot_number n'a pas été trouvée."]);
            }
        } catch (Exception $e) {
            http_response_code(500);
            error_log("API Error getSpotStatus: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Erreur interne du serveur.']);
        }
    }

    public function getSpotDetails() {
        header('Content-Type: application/json');
        
        // Vérification de sécurité de base
        if (!isset($_SESSION['user_id'])) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Accès non autorisé.']);
            exit;
        }

        $spot_id = $_GET['id'] ?? null;
        if (!$spot_id) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'ID de la place manquant.']);
            exit;
        }

        try {
            $spotDetails = $this->parkingSpotModel->getSpotDetailsById((int)$spot_id);
            if ($spotDetails) {
                // On vérifie si l'utilisateur connecté est le propriétaire de la réservation
                $spotDetails['is_owner'] = isset($spotDetails['user_id']) && $spotDetails['user_id'] == $_SESSION['user_id'];
                echo json_encode(['success' => true, 'details' => $spotDetails]);
            } else {
                http_response_code(404);
                echo json_encode(['success' => false, 'message' => 'Place non trouvée.']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Erreur interne du serveur.']);
        }
    }
}