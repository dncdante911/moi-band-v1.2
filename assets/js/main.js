/**
 * Файл: assets/js/main.js
 * Реализация AJAX-роутинга для непрерывной работы плеера.
 */

const contentContainer = document.getElementById('content-container');
const bodyContainer = document.getElementById('body-container');
const initialUrl = window.location.href;

// Функция загрузки и отображения нового контента
async function loadContent(url, pushState = true) {
    if (!contentContainer) {
        console.error("Контейнер контента (#content-container) не найден.");
        window.location.href = url; // Резервный вариант: полная перезагрузка
        return;
    }

    // 1. Изменение состояния загрузки и URL
    bodyContainer?.classList.add('is-loading'); // Можно добавить стили для затемнения
    console.log(`🚀 Loading content for: ${url}`);

    try {
        // 2. Отправка AJAX-запроса с заголовком, который говорит PHP, что нам нужен только контент
        const response = await fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const html = await response.text();

        // 3. Обновление DOM
        contentContainer.innerHTML = html;

        // 4. Обновление истории браузера
        if (pushState) {
            history.pushState({ path: url }, '', url);
        }
        
        // 5. Обновление заголовка страницы (опционально, требуется PHP-ответ с заголовком)
        const newTitle = contentContainer.querySelector('.page-title-data')?.textContent;
        if (newTitle) {
             document.title = newTitle;
        }

        // 6. Плеер: переинициализация обработчиков треклиста
        if (window.epicPlayer && typeof window.epicPlayer.initTracklistHandlers === 'function') {
            window.epicPlayer.initTracklistHandlers();
        }

    } catch (e) {
        console.error("❌ AJAX Load Error:", e);
        // Загрузка страницы с ошибкой или перезагрузка
        contentContainer.innerHTML = `<div class="error-container">
            <h2>Ошибка загрузки</h2>
            <p>Не удалось загрузить страницу. Пожалуйста, попробуйте <a href="${url}">перезагрузить</a>.</p>
        </div>`;
    } finally {
        // 7. Снятие состояния загрузки
        bodyContainer?.classList.remove('is-loading');
        window.scrollTo(0, 0);
    }
}

// Функция для перехвата кликов по ссылкам
function initAjaxLinks() {
    document.querySelectorAll('a[data-ajax-link]').forEach(link => {
        // Снимаем старые обработчики, чтобы избежать дублирования после AJAX-загрузки
        link.removeEventListener('click', handleLinkClick);
        link.addEventListener('click', handleLinkClick);
    });
}

function handleLinkClick(event) {
    const link = event.currentTarget;
    const url = link.href;

    // Проверяем, что это внутренняя ссылка и не скачивание
    if (url.startsWith(window.location.origin) && !link.hasAttribute('download')) {
        event.preventDefault(); // Отменяем стандартный переход
        loadContent(url);
    }
}

// Обработка навигации кнопками "Назад" / "Вперед"
window.addEventListener('popstate', (event) => {
    // Проверяем, что состояние отличается от начального
    if (event.state && event.state.path) {
        loadContent(event.state.path, false); // false = не добавлять в историю
    } else if (window.location.href !== initialUrl) {
         // Загружаем контент при изменении URL, если он не совпадает с начальным
         loadContent(window.location.href, false);
    }
});

// Инициализация при первой загрузке
document.addEventListener('DOMContentLoaded', () => {
    // Сохраняем состояние для первой страницы
    if (history.state === null) {
        history.replaceState({ path: window.location.href }, '', window.location.href);
    }
    
    // Запускаем перехватчик ссылок
    initAjaxLinks();
    console.log('🔗 AJAX Link Handler Initialized.');
});

// Делаем функцию доступной глобально, чтобы ее можно было вызвать после AJAX-загрузки
window.initAjaxLinks = initAjaxLinks;
window.loadContent = loadContent;

// --- ДОПОЛНИТЕЛЬНАЯ ЛОГИКА ДЛЯ ПЛЕЕРА ---
// Ваш плеер запускается в epic-player.js.
// Мы полагаемся на то, что initTracklistHandlers будет вызван после loadContent.