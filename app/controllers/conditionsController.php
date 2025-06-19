<?php
// Contrôleur
class conditionsController {
    public function conditions() {
        $page_title = "Conditions Générales d'Utilisation";
        require_once ROOT_PATH . '/app/views/conditions.php'; // chemin relatif correct vers la vue
    }
}
