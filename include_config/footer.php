<?php 
/**
 * ФАЙЛ: include_config/footer.php
 * Master of Illusion - Epic Footer
 * Power Metal & Hard Rock Style
 */
?>
    </main>

    <!-- === ПОДВАЛ САЙТА === -->
    <footer class="site-footer">
        <!-- Декоративная линия -->
        <div class="footer-divider"></div>

        <div class="container">
            <div class="footer-content">
                
                <!-- === ЛЕВАЯ КОЛОНКА: О ПРОЕКТЕ === -->
                <div class="footer-section footer-about">
                    <h3>⚡ Master of Illusion</h3>
                    <p>
                        Музыкальный проект <strong>Power Metal / Hard & Heavy / Punk Rock</strong>
                    </p>
                    <p class="footer-subtitle">
                        🎸 Музыка собрана при помощи АИ<br>
                        📝 Тексты — авторские и оригинальные<br>
                        🎤 Один в группе, но громче всех
                    </p>
                    <div class="footer-stats">
                        <div class="stat-item">
                            <span class="stat-number">100+</span>
                            <span class="stat-label">Треков</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">9</span>
                            <span class="stat-label">Альбомов</span>
                        </div>
                    </div>
                </div>

                <!-- === СРЕДНЯЯ КОЛОНКА: БЫСТРЫЕ ССЫЛКИ === -->
                <div class="footer-section footer-links">
                    <h4>🔗 Навигация</h4>
                    <ul>
                        <li><a href="/">Главная</a></li>
                        <li><a href="/pages/albums.php">Альбомы</a></li>
                        <li><a href="/pages/gallery.php">Галерея</a></li>
                        <li><a href="/pages/news.php">Новости</a></li>
                        <li><a href="/pages/about.php">О проекте</a></li>
                        <li><a href="/pages/chat.php">Комьюнити</a></li>
                    </ul>
                </div>

                <!-- === ПРАВАЯ КОЛОНКА: КОНТАКТЫ И СОЦСЕТИ === -->
                <div class="footer-section footer-contact">
                    <h4>💬 Контакты</h4>
                    <p>
                        <a href="mailto:<?= htmlspecialchars(SITE_EMAIL) ?>" class="footer-link">
                            ✉️ <?= htmlspecialchars(SITE_EMAIL) ?>
                        </a>
                    </p>
                    <p>
                        <a href="/pages/contact.php" class="footer-link">
                            📬 Написать сообщение
                        </a>
                    </p>
                    <div class="footer-socials">
                        <h5>🎵 Слушай везде:</h5>
                        <div class="social-links">
                            <a href="#" class="social-btn" title="Spotify" aria-label="Spotify">🎵</a>
                            <a href="#" class="social-btn" title="YouTube" aria-label="YouTube">📺</a>
                            <a href="#" class="social-btn" title="SoundCloud" aria-label="SoundCloud">☁️</a>
                            <a href="#" class="social-btn" title="Discord" aria-label="Discord">💎</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- === НИЖНЯЯ СТРОКА === -->
            <div class="footer-bottom">
                <div class="footer-bottom-left">
                    <p class="copyright">
                        &copy; 2024–2025 <strong><?= htmlspecialchars(SITE_NAME) ?></strong><br>
                        <span class="footer-tagline">🎸 Where Legends Are Born 🔥</span>
                    </p>
                </div>
                
                <div class="footer-bottom-right">
                    <p class="footer-credits">
                        Музыка: SUNO AI • Тексты: Собственные • Идея: One Man Show
                    </p>
                </div>
            </div>
        </div>

        <!-- Декоративная линия внизу -->
        <div class="footer-divider-bottom"></div>
    </footer>

    <!-- === СТИЛИ ФУТЕРА === -->
    <style>
        /* === ОСНОВНОЙ ФУТЕР === */
        .site-footer {
            background: linear-gradient(180deg, rgba(26, 20, 16, 0.95), rgba(10, 8, 6, 1));
            border-top: 3px solid #FFD700;
            color: #ccc;
            padding: 60px 0 20px;
            margin-top: 80px;
            position: relative;
            overflow: hidden;
        }

        .site-footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, #FFD700, transparent);
        }

        /* === ДЕКОРАТИВНЫЕ ЛИНИИ === */
        .footer-divider {
            height: 2px;
            background: linear-gradient(90deg, transparent, #FFA500, transparent);
            margin-bottom: 40px;
            opacity: 0.6;
        }

        .footer-divider-bottom {
            height: 1px;
            background: linear-gradient(90deg, transparent, #666, transparent);
            margin-top: 30px;
        }

        /* === ОСНОВНОЙ КОНТЕНТ === */
        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 40px;
            margin-bottom: 40px;
        }

        .footer-section h3,
        .footer-section h4,
        .footer-section h5 {
            color: #FFD700;
            font-size: 1.2rem;
            margin-bottom: 15px;
            text-transform: uppercase;
            font-weight: bold;
            letter-spacing: 1px;
        }

        /* === ЛЕВАЯ КОЛОНКА: О ПРОЕКТЕ === */
        .footer-about {
            border-right: 2px solid rgba(255, 215, 0, 0.2);
            padding-right: 20px;
        }

        .footer-about p {
            line-height: 1.8;
            margin-bottom: 10px;
            font-size: 0.95rem;
        }

        .footer-subtitle {
            color: #FFA500;
            font-size: 0.9rem;
            margin-top: 15px !important;
            margin-bottom: 20px !important;
        }

        /* === СТАТИСТИКА === */
        .footer-stats {
            display: flex;
            gap: 20px;
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid rgba(255, 215, 0, 0.1);
        }

        .stat-item {
            flex: 1;
            text-align: center;
            padding: 10px;
            background: rgba(255, 215, 0, 0.05);
            border-radius: 8px;
            border: 1px solid rgba(255, 215, 0, 0.2);
            transition: all 0.3s;
        }

        .stat-item:hover {
            background: rgba(255, 215, 0, 0.1);
            border-color: #FFD700;
            transform: translateY(-2px);
        }

        .stat-number {
            display: block;
            font-size: 1.5rem;
            color: #FFD700;
            font-weight: bold;
        }

        .stat-label {
            display: block;
            font-size: 0.8rem;
            color: #aaa;
            margin-top: 5px;
        }

        /* === ССЫЛКИ === */
        .footer-links ul {
            list-style: none;
            padding: 0;
        }

        .footer-links li {
            margin-bottom: 10px;
        }

        .footer-links a {
            color: #ccc;
            text-decoration: none;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .footer-links a:hover {
            color: #FFD700;
            transform: translateX(5px);
        }

        .footer-links a::before {
            content: '▶';
            font-size: 0.7rem;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .footer-links a:hover::before {
            opacity: 1;
        }

        /* === КОНТАКТЫ И СОЦСЕТИ === */
        .footer-contact p {
            margin-bottom: 12px;
        }

        .footer-link {
            color: #FFA500;
            text-decoration: none;
            transition: all 0.3s;
            display: inline-block;
        }

        .footer-link:hover {
            color: #FFD700;
            text-decoration: underline;
        }

        .footer-socials {
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid rgba(255, 215, 0, 0.1);
        }

        .footer-socials h5 {
            font-size: 0.95rem;
            color: #ccc;
            margin-bottom: 10px;
        }

        .social-links {
            display: flex;
            gap: 12px;
        }

        .social-btn {
            width: 40px;
            height: 40px;
            border: 2px solid #666;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: #ccc;
            text-decoration: none;
            transition: all 0.3s;
            background: transparent;
        }

        .social-btn:hover {
            border-color: #FFD700;
            color: #FFD700;
            background: rgba(255, 215, 0, 0.1);
            transform: scale(1.15) rotateZ(-10deg);
        }

        /* === НИЖНЯЯ СТРОКА === */
        .footer-bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 30px;
            border-top: 1px solid rgba(255, 215, 0, 0.1);
            flex-wrap: wrap;
            gap: 20px;
        }

        .copyright {
            color: #999;
            font-size: 0.9rem;
            margin: 0;
            line-height: 1.6;
        }

        .footer-tagline {
            color: #FFD700;
            font-weight: bold;
            font-style: italic;
            display: block;
            margin-top: 5px;
        }

        .footer-credits {
            color: #666;
            font-size: 0.85rem;
            margin: 0;
            text-align: right;
        }

        /* === АДАПТИВНОСТЬ === */
        @media (max-width: 768px) {
            .site-footer {
                padding: 40px 0 20px;
                margin-top: 60px;
            }

            .footer-content {
                grid-template-columns: 1fr;
                gap: 30px;
            }

            .footer-about {
                border-right: none;
                border-bottom: 2px solid rgba(255, 215, 0, 0.2);
                padding-right: 0;
                padding-bottom: 20px;
            }

            .footer-bottom {
                flex-direction: column;
                text-align: center;
            }

            .footer-credits {
                text-align: center;
            }

            .footer-section h3,
            .footer-section h4 {
                font-size: 1.1rem;
            }

            .social-links {
                justify-content: center;
            }
        }

        @media (max-width: 480px) {
            .site-footer {
                padding: 30px 0 15px;
            }

            .footer-divider {
                margin-bottom: 25px;
            }

            .footer-content {
                gap: 20px;
                margin-bottom: 25px;
            }

            .footer-section h3,
            .footer-section h4 {
                font-size: 1rem;
            }

            .footer-stats {
                margin-top: 15px;
                gap: 15px;
            }

            .stat-number {
                font-size: 1.3rem;
            }

            .copyright,
            .footer-credits {
                font-size: 0.8rem;
            }
        }
    </style>

    <!-- === ОСТАЛЬНЫЕ СКРИПТЫ === -->
    <script>
    // Обработка бургер-меню
    document.addEventListener('DOMContentLoaded', function() {
        const hamburger = document.getElementById('hamburger');
        const mainNav = document.getElementById('mainNav');
        
        if (hamburger && mainNav) {
            hamburger.addEventListener('click', function() {
                mainNav.classList.toggle('active');
                this.setAttribute('aria-expanded', mainNav.classList.contains('active'));
            });
            
            // Закрыть меню при клике на ссылку
            const navLinks = mainNav.querySelectorAll('a');
            navLinks.forEach(link => {
                link.addEventListener('click', function() {
                    mainNav.classList.remove('active');
                    hamburger.setAttribute('aria-expanded', 'false');
                });
            });
        }
    });
    </script>
    <script src="/assets/js/theme-manager-pro.js"></script>
    
<script src="/assets/js/epic-player.js"></script>
    
</body>
</html>