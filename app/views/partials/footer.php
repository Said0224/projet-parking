    </main>
    
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>Parking Intelligent</h3>
                    <p>Un projet développé par les étudiants de l'ISEP dans le cadre du projet commun.</p>
                </div>
                
                <div class="footer-section">
                    <h3>Liens rapides</h3>
                    <ul>
                        <li><a href="<?= BASE_URL ?>/">Accueil</a></li>
                        <li><a href="<?= BASE_URL ?>/dashboard">Dashboard</a></li>
                        <li><a href="<?= BASE_URL ?>/login">Connexion</a></li>
                        <li><a href="<?= BASE_URL ?>/signup">Inscription</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h3>Contact</h3>
                    <ul>
                        <li><i class="fas fa-envelope"></i> contact@isep.fr</li>
                        <li><i class="fas fa-phone"></i> +33 1 49 54 52 00</li>
                        <li><i class="fas fa-map-marker-alt"></i> 10 Rue de Vanves, 92130 Issy-les-Moulineaux</li>
                    </ul>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; <?= date('Y') ?> Parking Intelligent - ISEP. Tous droits réservés.</p>
            </div>
        </div>
    </footer>
    
    <script>
    // Script pour marquer le lien actif dans la navigation
    document.addEventListener('DOMContentLoaded', function() {
        const currentPath = window.location.pathname;
        const navLinks = document.querySelectorAll('.nav-link');
        
        navLinks.forEach(link => {
            const href = link.getAttribute('href');
            if (href === currentPath || (href !== '/' && currentPath.startsWith(href))) {
                link.classList.add('active');
            }
        });
    });
    </script>
</body>
</html>