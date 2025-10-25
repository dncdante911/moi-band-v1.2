<?php
/**
 * Файл: admin/add_track.php
 * ИСПРАВЛЕННАЯ ВЕРСИЯ - добавление трека с поддержкой новых полей
 */

require_once '../include_config/config.php';
require_once '../include_config/db_connect.php';

// Получаем список альбомов
$stmt_albums = $pdo->query("SELECT id, title FROM Albums ORDER BY title ASC");
$albums = $stmt_albums->fetchAll();

$errors = [];
$title = '';
$description = '';
$albumId = '';
$lyrics = '';

// === ОБРАБОТКА POST ЗАПРОСА ===
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $albumId = filter_var($_POST['albumId'] ?? '', FILTER_VALIDATE_INT);
    $lyrics = trim($_POST['lyrics'] ?? '');
    
    // Валидация
    if (empty($title)) {
        $errors[] = 'Название трека обязательно';
    }
    
    if (empty($_FILES['cover']['name']) || $_FILES['cover']['error'] !== UPLOAD_ERR_OK) {
        $errors[] = 'Ошибка загрузки обложки';
    }
    
    if (empty($_FILES['fullTrack']['name']) || $_FILES['fullTrack']['error'] !== UPLOAD_ERR_OK) {
        $errors[] = 'Ошибка загрузки аудио';
    }
    
    if (empty($errors)) {
        // Функция для сохранения файла
        function saveFile($file, $subfolder) {
            $uploadDir = '../public/uploads/' . $subfolder . '/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $fileName = uniqid() . '.' . $fileExtension;
            $uploadPath = $uploadDir . $fileName;
            
            if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
                return '/public/uploads/' . $subfolder . '/' . $fileName;
            }
            return false;
        }
        
        // Сохраняем обязательные файлы
        $coverPath = saveFile($_FILES['cover'], 'covers');
        $fullTrackPath = saveFile($_FILES['fullTrack'], 'full');
        
        if (!$coverPath || !$fullTrackPath) {
            $errors[] = 'Не удалось сохранить файлы';
        } else {
            // Сохраняем опциональное видео
            $videoPath = null;
            if (!empty($_FILES['video']['name']) && $_FILES['video']['error'] === UPLOAD_ERR_OK) {
                $videoPath = saveFile($_FILES['video'], 'videos');
            }
            
            try {
                // Вставляем трек в БД
                $sql = "INSERT INTO Track (title, description, albumId, coverImagePath, fullAudioPath, videoPath)
                        VALUES (:title, :description, :albumId, :coverImagePath, :fullAudioPath, :videoPath)";
                
                $stmt = $pdo->prepare($sql);
                $result = $stmt->execute([
                    ':title' => $title,
                    ':description' => $description,
                    ':albumId' => $albumId ?: null,
                    ':coverImagePath' => $coverPath,
                    ':fullAudioPath' => $fullTrackPath,
                    ':videoPath' => $videoPath
                ]);
                
                if ($result) {
                    $trackId = $pdo->lastInsertId();
                    
                    // Сохраняем текст если есть
                    if (!empty($lyrics)) {
                        $stmt = $pdo->prepare("
                            INSERT INTO SongLyrics (track_id, lyrics)
                            VALUES (:track_id, :lyrics)
                        ");
                        $stmt->execute([
                            ':track_id' => $trackId,
                            ':lyrics' => $lyrics
                        ]);
                    }
                    
                    // Редирект на главную админки
                    header('Location: index.php?success=1');
                    exit;
                } else {
                    $errors[] = 'Ошибка при сохранении в БД';
                }
            } catch (Exception $e) {
                $errors[] = 'Ошибка БД: ' . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Добавить трек - Админ-панель</title>
    <link rel="stylesheet" href="../assets/css/admin_style.css">
    <style>
        .form-container { max-width: 900px; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: bold; color: #FFD700; }
        .form-group input, .form-group textarea, .form-group select { 
            width: 100%; 
            padding: 10px; 
            background-color: #111; 
            border: 1px solid #FFD700; 
            color: #e0e0e0; 
            border-radius: 4px; 
            box-sizing: border-box;
            font-family: inherit;
        }
        .form-group textarea { resize: vertical; min-height: 100px; }
        .form-group input[type="file"] { padding: 5px; border: 2px dashed #FFD700; }
        .submit-button { 
            padding: 12px 30px; 
            background-color: #2b6cb0; 
            color: #fff; 
            border: none; 
            border-radius: 4px; 
            font-size: 1rem; 
            cursor: pointer;
            font-weight: bold;
        }
        .submit-button:hover { background-color: #3182ce; }
        .errors { 
            background-color: #c53030; 
            color: #fff; 
            padding: 15px; 
            border-radius: 4px; 
            margin-bottom: 20px;
        }
        .error-item { margin: 5px 0; }
        .required { color: #ff6666; }
        .help-text {
            font-size: 0.85rem;
            color: #a0aec0;
            margin-top: 8px;
            padding: 10px;
            background: rgba(100,200,255,0.1);
            border-left: 3px solid #4169E1;
        }
    </style>
</head>
<body>
    <div class="container form-container">
        <div class="breadcrumbs">
            <a href="index.php">Управление треками</a> &raquo;
            <span>Добавить трек</span>
        </div>
        
        <h1>➕ Добавить новый трек</h1>
        
        <?php if (!empty($errors)): ?>
            <div class="errors">
                <strong>❌ Ошибки:</strong>
                <?php foreach ($errors as $error): ?>
                    <div class="error-item">• <?= htmlspecialchars($error) ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <form action="add_track.php" method="POST" enctype="multipart/form-data">
            
            <!-- Основная информация -->
            <h3 style="color: #FFD700; margin-top: 30px; margin-bottom: 20px;">📝 Основная информация</h3>
            
            <div class="form-group">
                <label for="albumId">Альбом (опционально)</label>
                <select id="albumId" name="albumId">
                    <option value="">-- Без альбома --</option>
                    <?php foreach ($albums as $album): ?>
                        <option value="<?= $album['id'] ?>" 
                            <?= ($albumId == $album['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($album['title']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label for="title">Название трека <span class="required">*</span></label>
                <input type="text" id="title" name="title" 
                    value="<?= htmlspecialchars($title) ?>" required>
            </div>
            
            <div class="form-group">
                <label for="description">Описание / Краткая информация</label>
                <textarea id="description" name="description" rows="4" 
                    placeholder="Опишите суть трека, вдохновение и т.д."><?= htmlspecialchars($description) ?></textarea>
                <div class="help-text">💡 Это описание будет показано на странице трека</div>
            </div>
            
            <!-- Файлы -->
            <h3 style="color: #FFD700; margin-top: 30px; margin-bottom: 20px;">📁 Файлы</h3>
            
            <div class="form-group">
                <label for="cover">Обложка трека <span class="required">*</span> (JPG, PNG)</label>
                <input type="file" id="cover" name="cover" accept="image/jpeg, image/png" required>
                <div class="help-text">📷 Рекомендуемый размер: 500x500 пикселей</div>
            </div>
            
            <div class="form-group">
                <label for="fullTrack">Аудиофайл трека <span class="required">*</span> (MP3, WAV)</label>
                <input type="file" id="fullTrack" name="fullTrack" accept=".mp3, .wav" required>
                <div class="help-text">🎵 Максимальный размер: 100MB</div>
            </div>
            
            <div class="form-group">
                <label for="video">Видеоклип (опционально) (MP4, WebM)</label>
                <input type="file" id="video" name="video" accept=".mp4, .webm">
                <div class="help-text">🎬 Для клипов, минусовок или концертных записей (макс. 500MB)</div>
            </div>
            
            <!-- Текст песни -->
            <h3 style="color: #FFD700; margin-top: 30px; margin-bottom: 20px;">📄 Текст песни</h3>
            
            <div class="form-group">
                <label for="lyrics">Текст песни (опционально)</label>
                <textarea id="lyrics" name="lyrics" rows="12" 
                    placeholder="Введите текст песни построчно...
Опционально добавьте время синхронизации:
[00:12.00]Первая строка текста
[00:15.50]Вторая строка текста"><?= htmlspecialchars($lyrics) ?></textarea>
                <div class="help-text">💡 Синхронизация текста: [ММ:СС.ЦЦ]текст (необязательно, если просто текст без времени)</div>
            </div>
            
            <button type="submit" class="submit-button">✅ Сохранить трек</button>
        </form>
    </div>
</body>
</html>