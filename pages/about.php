<?php
/**
 * Файл: pages/about.php
 * Красиво оформленная страница о проекте
 */
$page_css = '/assets/css/about.css';
require_once __DIR__ . '/../include_config/header.php';
?>

<div class="about-banner">
    <div class="banner-overlay"></div>
    <div class="banner-glow banner-glow-1"></div>
    <div class="banner-glow banner-glow-2"></div>
    
    <div class="banner-content">
        <h1 class="banner-title">🎸 Master of Illusion</h1>
        <p class="banner-subtitle">Музыкальный проект, рожденный в пламени AI</p>
    </div>
</div>

<div class="container page-content about-page">
    
    <!-- === ВСТУПЛЕНИЕ === -->
    <section class="intro-section">
        <div class="intro-content">
            <h2 class="section-title">✨ О проекте</h2>
            <p class="intro-text">
                <strong>Master of Illusion</strong> — это музыкальный проект, рожденный в пламени нейросети, 
                где холодный код встречается с неукротимым духом рока. Это доказательство того, что даже в 
                алгоритмах может жить душа настоящего метала.
            </p>
            <p class="intro-text">
                Всё началось как эксперимент в мире AI-музыки <strong>Suno</strong>, но быстро переросло в нечто большее. 
                Каждая композиция создается с использованием современных технологий, а тексты пишутся вручную, 
                вкладывая в них эмоции и историю.
            </p>
        </div>
        
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number">9</div>
                <div class="stat-label">Альбомов</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">100+</div>
                <div class="stat-label">Треков</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">4</div>
                <div class="stat-label">Жанра</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">∞</div>
                <div class="stat-label">Эпичности</div>
            </div>
        </div>
    </section>
    
    <!-- === ОСНОВНЫЕ АЛЬБОМЫ === -->
    <section class="albums-showcase">
        <h2 class="section-title">💿 Основные Работы</h2>
        
        <!-- Альбом 1: Power Metal -->
        <div class="album-showcase power-metal">
            <div class="album-showcase-inner">
                <div class="album-showcase-text">
                    <h3 class="album-showcase-title">
                        ⚔️ Хроники Забытых Миров (2025)
                    </h3>
                    <p class="album-showcase-subtitle">Power Metal Epic</p>
                    
                    <p class="album-showcase-description">
                        Последняя работа на данный момент — это грандиозное путешествие через легенды, которые мир забыл. 
                        Музыкальное путешествие через 12 треков, вдохновленное классиками жанра: 
                        <strong>Epidemia, Helloween и Blind Guardian</strong>.
                    </p>
                    
                    <p class="album-showcase-description">
                        Этот альбом представляет собой концентрированный удар чистого Power Metal, полного доблести, 
                        магии и забытых легенд. От пробуждения древних стражей до триумфального рассвета новой эры, 
                        каждая песня — это глава эпической саги.
                    </p>
                    
                    <div class="album-showcase-tags">
                        <span class="tag">🏰 Эпоса</span>
                        <span class="tag">🎸 Гитара</span>
                        <span class="tag">⚡ Энергия</span>
                        <span class="tag">🌟 Магия</span>
                    </div>
                </div>
                <div class="album-showcase-accent"></div>
            </div>
        </div>
        
        <!-- Альбом 2: Gothic Metal -->
        <div class="album-showcase gothic-metal">
            <div class="album-showcase-inner">
                <div class="album-showcase-text">
                    <h3 class="album-showcase-title">
                        🦇 Театр крови и костей (Live)
                    </h3>
                    <p class="album-showcase-subtitle">Punk-Rock Saga</p>
                    
                    <p class="album-showcase-description">
                        Зловещая панк-рок сказка в духе легендарного <strong>"Короля и Шута"</strong>, 
                        где каждая песня — это история, рассказанная безумным шутом на краю могилы.
                    </p>
                    
                    <p class="album-showcase-description">
                        Здесь Master of Illusion сбрасывает оковы эпика и погружается в мир анархии и мрачных фантазий. 
                        Это музыка для тех, кто не боится смерти и смеется в лицо судьбе.
                    </p>
                    
                    <div class="album-showcase-tags">
                        <span class="tag">🎭 Театр</span>
                        <span class="tag">🤡 Безумие</span>
                        <span class="tag">🎵 Панк</span>
                        <span class="tag">💀 Мрак</span>
                    </div>
                </div>
                <div class="album-showcase-accent"></div>
            </div>
        </div>
        
        <!-- Трилогия -->
        <div class="album-showcase grimoire-trilogy">
            <div class="album-showcase-inner">
                <div class="album-showcase-text">
                    <h3 class="album-showcase-title">
                        📖 Трилогия "Гримуары" (2024-2025)
                    </h3>
                    <p class="album-showcase-subtitle">Metal Opera Masterpiece</p>
                    
                    <p class="album-showcase-description">
                        Монументальная метал-опера, объединившая три вселенные: готический ужас <strong>Hellsing</strong>, 
                        демонический стиль <strong>Devil May Cry</strong> и магический танец <strong>Bayonetta</strong>. 
                        Это не просто альбомы, а три книги судьбы, записанные в звуках стали и крови.
                    </p>
                    
                    <div class="grimoire-list">
                        <div class="grimoire-item sin">
                            <h4>⚫ Гримуар Греха</h4>
                            <p>Пробуждение истинной силы и ее цена. Герои стоят на пороге выбора — принять свою темную природу или остаться теми, кем их хотят видеть.</p>
                        </div>
                        <div class="grimoire-item wrath">
                            <h4>🔴 Гримуар Гнева</h4>
                            <p>Апофеоз могущества, обернувшийся катастрофой. Самый агрессивный и тяжелый альбом, где каждая песня — батальная сцена эпического масштаба.</p>
                        </div>
                        <div class="grimoire-item eternity">
                            <h4>✨ Гримуар Вечности</h4>
                            <p>Рождение надежды из пепла. Герои находят силу не в разрушении, а в защите, принося финальную жертву, которая преображает саму реальность.</p>
                        </div>
                    </div>
                </div>
                <div class="album-showcase-accent"></div>
            </div>
        </div>
        
        <!-- Дебютный альбом -->
        <div class="album-showcase debut">
            <div class="album-showcase-inner">
                <div class="album-showcase-text">
                    <h3 class="album-showcase-title">
                        🌙 Эхо миров (Live)
                    </h3>
                    <p class="album-showcase-subtitle">Dark Atmospheric Journey</p>
                    
                    <p class="album-showcase-description">
                        Дебютный альбом, с которого все началось. Мрачный сборник, сотканный из туманных миров <strong>Гоголя</strong>, 
                        готической меланхолии <strong>Эдгара По</strong> и мистических глубин <strong>"Фауста"</strong>.
                    </p>
                    
                    <p class="album-showcase-description">
                        Это было началом легенды, первым криком в пустоту, который разбудил что-то глубокое и древнее 
                        в сердцах слушателей.
                    </p>
                    
                    <div class="album-showcase-tags">
                        <span class="tag">📚 Литература</span>
                        <span class="tag">🌫️ Атмосфера</span>
                        <span class="tag">👻 Мистика</span>
                        <span class="tag">🖤 Дебют</span>
                    </div>
                </div>
                <div class="album-showcase-accent"></div>
            </div>
        </div>
    </section>
    
    <!-- === ЖАНРЫ === -->
    <section class="genres-section">
        <h2 class="section-title">🎵 Жанровый Спектр</h2>
        
        <div class="genres-grid">
            <div class="genre-card power-metal-genre">
                <div class="genre-icon">⚔️</div>
                <h3>Power Metal</h3>
                <p>Эпические композиции, полные света и боевого духа. Быстрые гитарные рифы, мощные вокалы и вдохновляющие мелодии.</p>
                <div class="genre-examples">
                    <span>Героизм</span>
                    <span>Магия</span>
                    <span>Эпоса</span>
                </div>
            </div>
            
            <div class="genre-card gothic-metal-genre">
                <div class="genre-icon">🦇</div>
                <h3>Gothic Metal</h3>
                <p>Готические узоры, мрачная атмосфера и меланхолия. Медленные, зловещие мелодии, смешанные с тяжелым звуком.</p>
                <div class="genre-examples">
                    <span>Мрак</span>
                    <span>Мистика</span>
                    <span>Меланхолия</span>
                </div>
            </div>
            
            <div class="genre-card punk-rock-genre">
                <div class="genre-icon">🤘</div>
                <h3>Punk Rock</h3>
                <p>Сырая энергия, быстрые ритмы и бунтарский дух. Простые, но мощные мелодии, которые вызывают желание действовать.</p>
                <div class="genre-examples">
                    <span>Энергия</span>
                    <span>Бунт</span>
                    <span>Свобода</span>
                </div>
            </div>
        </div>
    </section>
    
    <!-- === ВДОХНОВЕНИЕ === -->
    <section class="inspiration-section">
        <h2 class="section-title">🎸 Музыкальные Влияния</h2>
        
        <div class="influences-grid">
            <div class="influence-card">
                <h3>Классики Metal</h3>
                <ul>
                    <li>🎵 Helloween</li>
                    <li>🎵 Blind Guardian</li>
                    <li>🎵 Epidemia</li>
                    <li>🎵 Iron Maiden</li>
                </ul>
            </div>
            
            <div class="influence-card">
                <h3>Литературные Вдохновения</h3>
                <ul>
                    <li>📚 Николай Гоголь</li>
                    <li>📚 Эдгар Алан По</li>
                    <li>📚 Иоганн Вольфганг Гёте</li>
                    <li>📚 Англиканские легенды</li>
                </ul>
            </div>
            
            <div class="influence-card">
                <h3>Визуальное Искусство</h3>
                <ul>
                    <li>🎬 Hellsing</li>
                    <li>🎮 Devil May Cry</li>
                    <li>🎮 Bayonetta</li>
                    <li>🎭 Gothic Fashion</li>
                </ul>
            </div>
        </div>
    </section>
    
    <!-- === ФИЛОСОФИЯ === -->
    <section class="philosophy-section">
        <h2 class="section-title">💭 Философия Проекта</h2>
        
        <div class="philosophy-cards">
            <div class="philosophy-card">
                <div class="philosophy-icon">🔮</div>
                <h3>Искусство без границ</h3>
                <p>AI и человек создают вместе. Технология — это инструмент, а не ограничение. Важна эмоция, которая передается через музыку.</p>
            </div>
            
            <div class="philosophy-card">
                <div class="philosophy-icon">🌍</div>
                <h3>Рассказ историй</h3>
                <p>Каждый альбом — это эпос. Каждая песня рассказывает истории о героях, богах и монстрах. О любви и войне, о жизни и смерти.</p>
            </div>
            
            <div class="philosophy-card">
                <div class="philosophy-icon">⚡</div>
                <h3>Чистая энергия</h3>
                <p>Metal — это энергия, которая разбивает стены. Это крик, который слышен даже в пустоте. Это дух, который невозможно убить.</p>
            </div>
            
            <div class="philosophy-card">
                <div class="philosophy-icon">🎸</div>
                <h3>Сообщество</h3>
                <p>Мы создаем музыку для вас. Для тех, кто верит в силу metal, кто понимает, что музыка — это язык, который понимает каждый.</p>
            </div>
        </div>
    </section>
    
    <!-- === CTA === -->
    <section class="about-cta">
        <h2>Готовы погрузиться в эпос?</h2>
        <p>Слушайте музыку, присоединяйтесь к сообществу, делитесь впечатлениями</p>
        
        <div class="cta-buttons">
            <a href="/" class="cta-button primary">🎵 Слушать музыку</a>
            <a href="/pages/chat.php" class="cta-button secondary">💬 Чат сообщества</a>
        </div>
    </section>
    
</div>

<?php require_once __DIR__ . '/../include_config/footer.php'; ?>