<?php
/**
 * ФАЙЛ: pages/news.php
 * Страница новостей и событий
 */

require_once __DIR__ . '/../include_config/header.php';

// Получаем новости из БД
$query = $pdo->query("SELECT * FROM news ORDER BY created_at DESC LIMIT 20");
$news = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container">
    <div class="news-banner">
        <div class="banner-content">
            <h1>📰 Новости и События</h1>
            <p>Следи за последними обновлениями проекта</p>
        </div>
    </div>

    <div class="page-content news-page">
        
        <!-- === ФИЛЬТР === -->
        <div class="news-filter">
            <button class="filter-btn active" data-filter="all">Все</button>
            <button class="filter-btn" data-filter="release">Релизы</button>
            <button class="filter-btn" data-filter="event">События</button>
            <button class="filter-btn" data-filter="update">Обновления</button>
        </div>

        <!-- === СЕТКА НОВОСТЕЙ === -->
        <div class="news-grid">
            <?php if (count($news) > 0): ?>
                <?php foreach ($news as $item): ?>
                    <article class="news-card" data-category="<?= htmlspecialchars($item['category']) ?>">
                        <?php if (!empty($item['image'])): ?>
                            <div class="news-image">
                                <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['title']) ?>">
                            </div>
                        <?php endif; ?>
                        
                        <div class="news-content">
                            <div class="news-meta">
                                <span class="news-category"><?= htmlspecialchars($item['category']) ?></span>
                                <span class="news-date"><?= date('d.m.Y', strtotime($item['created_at'])) ?></span>
                            </div>
                            
                            <h3><?= htmlspecialchars($item['title']) ?></h3>
                            <p><?= htmlspecialchars(substr($item['content'], 0, 150)) ?>...</p>
                            
                            <a href="/pages/news-detail.php?id=<?= $item['id'] ?>" class="read-more">
                                Читать полностью →
                            </a>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-content">
                    <p>Новостей пока нет. Следи за обновлениями!</p>
                </div>
            <?php endif; ?>
        </div>

    </div>
</div>

<!-- === CSS === -->
<style>
.news-banner {
    background: linear-gradient(135deg, rgba(255, 215, 0, 0.1), rgba(255, 165, 0, 0.05));
    padding: 60px 20px;
    text-align: center;
    margin-bottom: 40px;
    border-bottom: 2px solid #FFD700;
}

.news-banner h1 {
    font-size: 2.5rem;
    color: #FFD700;
    margin-bottom: 10px;
}

.news-banner p {
    color: #ccc;
    font-size: 1.1rem;
}

.news-filter {
    display: flex;
    gap: 15px;
    margin-bottom: 40px;
    justify-content: center;
    flex-wrap: wrap;
}

.filter-btn {
    padding: 10px 20px;
    background: transparent;
    border: 2px solid #666;
    color: #ccc;
    border-radius: 20px;
    cursor: pointer;
    transition: all 0.3s;
    font-weight: bold;
}

.filter-btn:hover,
.filter-btn.active {
    border-color: #FFD700;
    color: #FFD700;
    background: rgba(255, 215, 0, 0.1);
}

.news-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 30px;
    margin-bottom: 40px;
}

.news-card {
    background: rgba(26, 20, 16, 0.85);
    border: 2px solid #666;
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.3s;
}

.news-card:hover {
    border-color: #FFD700;
    transform: translateY(-5px);
    box-shadow: 0 0 20px rgba(255, 215, 0, 0.3);
}

.news-image {
    width: 100%;
    height: 200px;
    overflow: hidden;
}

.news-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s;
}

.news-card:hover .news-image img {
    transform: scale(1.05);
}

.news-content {
    padding: 20px;
}

.news-meta {
    display: flex;
    gap: 15px;
    margin-bottom: 10px;
    font-size: 0.9rem;
}

.news-category {
    background: rgba(255, 215, 0, 0.2);
    color: #FFD700;
    padding: 4px 12px;
    border-radius: 12px;
    text-transform: capitalize;
}

.news-date {
    color: #999;
}

.news-card h3 {
    color: #FFD700;
    margin: 10px 0;
    font-size: 1.3rem;
}

.news-card p {
    color: #ccc;
    line-height: 1.6;
    margin-bottom: 15px;
}

.read-more {
    color: #FFA500;
    text-decoration: none;
    font-weight: bold;
    transition: all 0.3s;
}

.read-more:hover {
    color: #FFD700;
    text-decoration: underline;
}

.no-content {
    grid-column: 1 / -1;
    text-align: center;
    padding: 40px;
    color: #999;
}

@media (max-width: 768px) {
    .news-grid {
        grid-template-columns: 1fr;
    }

    .news-banner h1 {
        font-size: 2rem;
    }
}
</style>

<!-- === JAVASCRIPT === -->
<script>
document.querySelectorAll('.filter-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        // Обновляем активную кнопку
        document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');

        // Фильтруем карточки
        const filter = this.dataset.filter;
        document.querySelectorAll('.news-card').forEach(card => {
            if (filter === 'all' || card.dataset.category === filter) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    });
});
</script>

<?php require_once __DIR__ . '/../include_config/footer.php'; ?>