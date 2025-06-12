<?php require_once ROOT_PATH . '/app/views/partials/header.php'; ?>

<div class="hero-section">
    <div class="container">
        <div class="hero-content animate-fade-in">
            <h1 class="hero-title">Bienvenue sur le système de Parking Intelligent</h1>
            <p class="hero-description">Ce site vous permet de visualiser en temps réel l'état d'occupation des places de notre parking.</p>
            
            <div class="hero-buttons">
                <a href="/dashboard" class="btn btn-primary">
                    <i class="fas fa-parking"></i>
                    Voir les places
                </a>
                <a href="/login" class="btn btn-secondary">
                    <i class="fas fa-sign-in-alt"></i>
                    Se connecter
                </a>
            </div>
        </div>
    </div>
</div>

<div class="features-section">
    <div class="container">
        <h2 class="section-title text-center">Fonctionnalités</h2>
        
        <div class="features-grid">
            <div class="feature-card card">
                <div class="feature-icon">
                    <i class="fas fa-car"></i>
                </div>
                <h3>Places en temps réel</h3>
                <p>Visualisez instantanément les places disponibles dans le parking.</p>
            </div>
            
            <div class="feature-card card">
                <div class="feature-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h3>Statistiques d'occupation</h3>
                <p>Analysez les tendances d'occupation pour mieux planifier vos déplacements.</p>
            </div>
            
            <div class="feature-card card">
                <div class="feature-icon">
                    <i class="fas fa-bolt"></i>
                </div>
                <h3>Bornes de recharge</h3>
                <p>Localisez les bornes de recharge disponibles pour véhicules électriques.</p>
            </div>
        </div>
    </div>
</div>

<div class="about-section">
    <div class="container">
        <h2 class="section-title text-center">À propos du projet</h2>
        
        <div class="about-content">
            <div class="about-text">
                <p>Le projet de Parking Intelligent est une initiative développée par les étudiants de l'ISEP dans le cadre du projet commun. Il utilise des capteurs IoT pour détecter la présence de véhicules et afficher en temps réel l'état d'occupation des places.</p>
                <p>Notre système permet également de gérer les bornes de recharge pour véhicules électriques et d'analyser les données d'occupation pour optimiser l'utilisation du parking.</p>
            </div>
        </div>
    </div>
</div>

<style>
/* Styles spécifiques à la page d'accueil */
.hero-section {
    padding: 6rem 0 4rem;
    text-align: center;
    position: relative;
    overflow: hidden;
}

.hero-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('/placeholder.svg?height=500&width=1000') no-repeat center center;
    background-size: cover;
    opacity: 0.15;
    z-index: -1;
}

.hero-content {
    max-width: 800px;
    margin: 0 auto;
    padding: 2rem;
    background-color: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border-radius: var(--border-radius);
    box-shadow: var(--shadow-lg);
}

.hero-title {
    font-size: 3rem;
    color: white;
    margin-bottom: 1.5rem;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
}

.hero-description {
    font-size: 1.25rem;
    color: rgba(255, 255, 255, 0.9);
    margin-bottom: 2rem;
}

.hero-buttons {
    display: flex;
    gap: 1rem;
    justify-content: center;
}

.features-section {
    padding: 5rem 0;
    background-color: rgba(255, 255, 255, 0.05);
}

.section-title {
    font-size: 2.5rem;
    color: white;
    margin-bottom: 3rem;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
}

.features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
}

.feature-card {
    padding: 2rem;
    text-align: center;
    background-color: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border-radius: var(--border-radius);
    transition: var(--transition);
}

.feature-card:hover {
    transform: translateY(-10px);
    background-color: rgba(255, 255, 255, 0.15);
}

.feature-icon {
    font-size: 3rem;
    color: var(--primary);
    margin-bottom: 1.5rem;
}

.feature-card h3 {
    color: white;
    margin-bottom: 1rem;
}

.feature-card p {
    color: rgba(255, 255, 255, 0.8);
}

.about-section {
    padding: 5rem 0;
}

.about-content {
    display: flex;
    align-items: center;
    gap: 3rem;
}

.about-text {
    background-color: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    padding: 2rem;
    border-radius: var(--border-radius);
    color: white;
}

.about-text p {
    margin-bottom: 1.5rem;
    line-height: 1.8;
}

@media (max-width: 768px) {
    .hero-title {
        font-size: 2rem;
    }
    
    .hero-buttons {
        flex-direction: column;
    }
    
    .features-grid {
        grid-template-columns: 1fr;
    }
    
    .about-content {
        flex-direction: column;
    }
}
</style>

<?php require_once ROOT_PATH . '/app/views/partials/footer.php'; ?>