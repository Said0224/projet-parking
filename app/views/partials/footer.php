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
                <p>© <?= date('Y') ?> Parking Intelligent - ISEP. Tous droits réservés.</p>
            </div>
        </div>
    </footer>

    <!-- ============================================= -->
    <!-- NOUVELLE BANNIÈRE DE COOKIES -->
    <!-- ============================================= -->
    <div id="cookie-banner">
        <div class="cookie-content">
            <div class="cookie-text">
                <i class="fas fa-cookie-bite"></i>
                <p>
                    Nous utilisons des cookies pour améliorer votre expérience sur notre site. En acceptant, vous consentez à notre utilisation des cookies.
                    <!-- Vous pourrez ajouter un lien vers une page de politique de confidentialité ici -->
                    <!-- <a href="#">En savoir plus</a> -->
                </p>
            </div>
            <div class="cookie-buttons">
                <button id="decline-cookies" class="btn btn-secondary btn-sm">Refuser</button>
                <button id="accept-cookies" class="btn btn-primary btn-sm">Accepter</button>
            </div>
        </div>
    </div>


    <script>
        const BASE_URL = '<?= BASE_URL ?>';
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="<?= BASE_URL ?>/public/js/dashboard-3d.js"></script>

    <script>
    // Script pour marquer le lien actif dans la navigation
    document.addEventListener('DOMContentLoaded', function() {
        const currentPath = window.location.pathname.replace('<?= BASE_URL ?>', '') || '/';
        const navLinks = document.querySelectorAll('.nav-link');
        
        navLinks.forEach(link => {
            const href = link.getAttribute('href').replace('<?= BASE_URL ?>', '');
            if (href === currentPath || (href !== '/' && currentPath.startsWith(href))) {
                link.classList.add('active');
            }
        });

        // =============================================
        // NOUVEAU SCRIPT DE GESTION DES COOKIES
        // =============================================
        const banner = document.getElementById('cookie-banner');
        const acceptBtn = document.getElementById('accept-cookies');
        const declineBtn = document.getElementById('decline-cookies');

        // Vérifie si un cookie de consentement existe déjà
        const consentCookie = document.cookie
            .split(';')
            .find(row => row.trim().startsWith('cookie_consent='));

        // Si le cookie n'existe pas, on affiche la bannière
        if (!consentCookie) {
            // Un petit délai pour ne pas être trop agressif au chargement
            setTimeout(() => {
                banner.classList.add('show');
            }, 500);
        }

        const handleConsent = (consent) => {
            // Durée de vie du cookie : 1 an
            const maxAge = 60 * 60 * 24 * 365;
            document.cookie = `cookie_consent=${consent}; max-age=${maxAge}; path=/; SameSite=Lax`;
            banner.classList.remove('show');
        };

        acceptBtn.addEventListener('click', () => handleConsent('accepted'));
        declineBtn.addEventListener('click', () => handleConsent('declined'));
    });
    </script>
</body>
</html>