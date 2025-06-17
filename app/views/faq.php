<?php require_once ROOT_PATH . '/app/views/partials/header.php'; ?>

<div class="container">
    <div class="page-header">
        <h1><i class="fas fa-question-circle"></i> Questions Fréquemment Posées (FAQ)</h1>
    </div>

    <div class="faq-container">
        <?php if (empty($faqs)): ?>
            <div class="card">
                <div class="card-body text-center">
                    <p>Aucune question n'est disponible dans la FAQ pour le moment.</p>
                </div>
            </div>
        <?php else: ?>
            <?php foreach ($faqs as $faq): ?>
                <details class="faq-item">
                    <summary class="faq-question">
                        <?= htmlspecialchars($faq['question']) ?>
                    </summary>
                    <div class="faq-answer">
                        <p><?= nl2br(htmlspecialchars($faq['answer'])) ?></p>
                    </div>
                </details>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<style>
.faq-container {
    max-width: 900px;
    margin: 2rem auto;
}

.faq-item {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-radius: 15px;
    margin-bottom: 1rem;
    border: 1px solid rgba(255, 255, 255, 0.2);
    box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
}

.faq-item:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 25px rgba(0,0,0,0.1);
}

.faq-question {
    padding: 1.5rem 2rem;
    font-weight: 600;
    cursor: pointer;
    list-style: none; /* Cache la flèche par défaut */
    position: relative;
    color: #1e40af;
    font-size: 1.1rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.faq-question::-webkit-details-marker {
    display: none; /* Cache la flèche sur Chrome/Safari */
}

.faq-question::after { /* Crée une nouvelle flèche personnalisée */
    content: '\f078'; /* Icône FontAwesome chevron-down */
    font-family: 'Font Awesome 6 Free';
    font-weight: 900;
    font-size: 1rem;
    color: #3b82f6;
    transition: transform 0.3s ease-in-out;
}

.faq-item[open] > .faq-question::after {
    transform: rotate(180deg);
}

.faq-answer {
    padding: 0 2rem 1.5rem 2rem;
    color: #333;
    line-height: 1.7;
    border-top: 1px solid rgba(30, 64, 175, 0.1);
}
</style>

<?php require_once ROOT_PATH . '/app/views/partials/footer.php'; ?>