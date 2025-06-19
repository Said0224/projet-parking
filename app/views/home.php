<?php require_once ROOT_PATH . '/app/views/partials/header.php'; ?>

<div class="hero-section">
    <div class="container">
        <div class="hero-content">
            <h1 class="hero-title">Bienvenue sur le système de Parking Intelligent</h1>
            <p class="hero-description">Ce site vous permet de visualiser en temps réel l'état d'occupation des places de notre parking.</p>
            
            <div class="hero-buttons">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="<?= BASE_URL ?><?= $_SESSION['is_admin'] ? '/admin' : '/user/dashboard' ?>" class="btn btn-primary">
                        <i class="fas fa-tachometer-alt"></i>
                        Accéder à mon dashboard
                    </a>
                <?php else: ?>
                    <a href="<?= BASE_URL ?>/login" class="btn btn-primary">
                        <i class="fas fa-sign-in-alt"></i>
                        Se connecter pour voir les places
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="features-section">
    <div class="container">
        <h2 class="section-title">Fonctionnalités</h2>
        
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-car-alt"></i>
                </div>
                <h3>Places en temps réel</h3>
                <p>Visualisez instantanément les places disponibles dans le parking.</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h3>Statistiques d'occupation</h3>
                <p>Analysez les tendances pour mieux planifier vos déplacements.</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-bolt"></i>
                </div>
                <h3>Bornes de recharge</h3>
                <p>Localisez les bornes disponibles pour véhicules électriques.</p>
            </div>
        </div>
    </div>
</div>

<div class="about-section">
    <div class="container">
        <h2 class="section-title">À propos du projet</h2>
        
        <div class="about-content">
            <div class="about-text">
                <p>Le projet de Parking Intelligent est une initiative développée par les étudiants de l'ISEP dans le cadre du projet commun. Il utilise des capteurs IoT pour détecter la présence de véhicules et afficher en temps réel l'état d'occupation des places.</p>
                <p>Notre système permet également de gérer les bornes de recharge pour véhicules électriques et d'analyser les données d'occupation pour optimiser l'utilisation du parking.</p>
            </div>
        </div>
    </div>
</div>

<style>
/* CSS MODIFIÉ ET AMÉLIORÉ POUR LA PAGE D'ACCUEIL */
.hero-section, .features-section, .about-section {
    padding: 5rem 0;
}

.hero-content {
    max-width: 800px;
    margin: 0 auto;
    padding: 3rem;
    background-color: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(15px);
    border-radius: 25px; /* Bords arrondis */
    border: 1px solid rgba(255, 255, 255, 0.2);
    box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.1);
    text-align: center;
    /* AJOUT : Transition pour l'effet de survol */
    transition: all 0.3s ease; 
}

/* AJOUT : Règle de survol pour le bloc "Bienvenue" */
.hero-content:hover {
    transform: translateY(-10px) scale(1.02);
    box-shadow: 0 15px 40px rgba(0,0,0,0.15);
}

.hero-title {
    font-size: clamp(2rem, 5vw, 3.5rem);
    color: white;
    margin-bottom: 1.5rem;
    text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
    line-height: 1.2;
}

.hero-description {
    font-size: 1.25rem;
    color: rgba(255, 255, 255, 0.9);
    margin-bottom: 2.5rem;
}

.hero-buttons .btn {
    padding: 1rem 2.5rem;
    font-size: 1.1rem;
}

.section-title {
    font-size: 2.8rem;
    color: white;
    margin-bottom: 3.5rem;
    text-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
    text-align: center;
}

.features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
}

.feature-card {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(15px);
    border-radius: 20px; /* Bords arrondis */
    border: 1px solid rgba(255, 255, 255, 0.2);
    padding: 2.5rem 2rem;
    text-align: center;
    transition: all 0.3s ease;
}

.feature-card:hover {
    transform: translateY(-10px) scale(1.03);
    background-color: rgba(255, 255, 255, 0.15);
    box-shadow: 0 15px 40px rgba(0,0,0,0.1);
}

.feature-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto 1.5rem auto;
    border-radius: 20px; /* Bords arrondis pour l'icône */
    background: linear-gradient(145deg, rgba(255, 255, 255, 0.2), rgba(255, 255, 255, 0.1));
    display: flex;
    align-items: center;
    justify-content: center;
    transition: transform 0.3s ease;
}

.feature-card:hover .feature-icon {
    transform: rotate(-10deg);
}

.feature-icon i {
    font-size: 2.5rem;
    color: white;
}

.feature-card h3 {
    color: white;
    margin-bottom: 1rem;
    font-size: 1.5rem;
}

.feature-card p {
    color: rgba(255, 255, 255, 0.8);
    line-height: 1.6;
}

.about-text {
    background-color: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(15px);
    padding: 3rem;
    border-radius: 25px; /* Bords arrondis */
    border: 1px solid rgba(255, 255, 255, 0.2);
    color: white;
    max-width: 900px;
    margin: 0 auto;
    box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.1);
    /* AJOUT : Transition pour l'effet de survol */
    transition: all 0.3s ease;
}

/* AJOUT : Règle de survol pour le bloc "À propos" */
.about-text:hover {
    transform: translateY(-10px) scale(1.02);
    box-shadow: 0 15px 40px rgba(0,0,0,0.15);
}

.about-text p {
    margin-bottom: 1.5rem;
    line-height: 1.8;
    font-size: 1.1rem;
}
.about-text p:last-child {
    margin-bottom: 0;
}

@media (max-width: 768px) {
    .hero-title { font-size: 2rem; }
    .hero-buttons { flex-direction: column; align-items: center; }
}
</style>

<?php require_once ROOT_PATH . '/app/views/partials/footer.php'; ?>