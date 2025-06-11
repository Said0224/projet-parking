<?php 
// Vérifier si le fichier header.php existe
if (file_exists(ROOT_PATH . '/app/views/partials/header.php')) {
    require_once ROOT_PATH . '/app/views/partials/header.php';
} else {
    echo "<p>Erreur: Le fichier header.php est introuvable.</p>";
}
?>

<div class="container">
    <h1>Bienvenue sur le système de Parking Intelligent</h1>
    <p>Ce site vous permet de visualiser en temps réel l'état d'occupation des places de notre parking.</p>
    
    <div class="cta-buttons">
        <a href="/dashboard" class="btn btn-primary">Voir les places</a>
        <a href="/login" class="btn">Se connecter</a>
    </div>
</div>

<?php 
// Vérifier si le fichier footer.php existe
if (file_exists(ROOT_PATH . '/app/views/partials/footer.php')) {
    require_once ROOT_PATH . '/app/views/partials/footer.php';
} else {
    echo "<p>Erreur: Le fichier footer.php est introuvable.</p>";
}
?>