<?php
// Файл: admin/album_edit.php
// ИСПРАВЛЕННАЯ ВЕРСИЯ - редактирование альбома с обложкой

require_once '../include_config/config.php';
require_once '../include_config/db_connect.php';

// Проверяем, что ID альбома передан
if (!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    header('Location: albums_list.php');
    exit;
}
$albumId = (int)$_GET['id'];

// Получаем информацию об альбоме
$stmt = $pdo->prepare("SELECT * FROM Albums WHERE id = ?");
$stmt->execute([$albumId]);
$album = $stmt->fetch();

if (!$album) {
    header('Location: albums_list.php');
    exit;
}

$errors = [];
$title = $album['title'];
$description = $album['description'];
$releaseDate = $album['releaseDate'];
$coverImagePath = $album['coverImagePath'];

// === ОБРАБОТКА POST ЗАПРОСА ===
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $releaseDate = trim($_POST['releaseDate'] ?? '');
    
    // Валидация
    if (empty($title)) {
        $errors[] = 'Название альбома обязательно';
    }
    
    if (empty($errors)) {
        // Функция для сохранения файла
        function saveAlbumCover($file, $subfolder) {
            if (empty($file['name']) || $file['error'] !== UPLOAD_ERR_OK) {
                return null;
            }
            
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
        
        // Если загружена новая обложка
        if (!empty($_FILES['cover']['name'])) {
            $newCoverPath = saveAlbumCover($_FILES['cover'], 'album_covers');
            
            if ($newCoverPath) {
                // Удаляем старую обложку
                @unlink('..' . $coverImagePath);
                $coverImagePath = $newCoverPath;
            } else {
                $errors[] = 'Ошибка загрузки обложки';
            }
        }
        
        if (empty($errors)) {
            try {
                $sql = "UPDATE Albums SET 
                    title = :title, 
                    description = :description, 
                    releaseDate = :releaseDate,
                    coverImagePath = :coverImagePath
                WHERE id = :id";
                
                $stmt = $pdo->prepare($sql);
                $result = $stmt->execute([
                    ':title' => $title,
                    ':description' => $description,
                    ':releaseDate' => !empty($releaseDate) ? $releaseDate : null,
                    ':coverImagePath' => $coverImagePath,
                    ':id' => $albumId
                ]);
                
                if ($result) {
                    // Редирект с уведомлением об успехе
                    header('Location: album_view.php?id=' . $albumId . '&success=1');
                    exit;
                } else {
                    $errors[] = 'Ошибка при сохранении альбома';
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
    <title>Редактировать альбом - Админ-панель</title>
    <link rel="stylesheet" href="../assets/css/admin_style.css">
    <style>
        .form-container { max-width: 800px; }
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
            margin-right: 10px;
        }
        .submit-button:hover { background-color: #3182ce; }
        .delete-button {
            padding: 12px 30px;
            background-color: #8B0000;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 1rem;
            cursor: pointer;
            font-weight: bold;
        }
        .delete-button:hover { background-color: #c53030; }
        .errors { 
            background-color: #c53030; 
            color: #fff; 
            padding: 15px; 
            border-radius: 4px; 
            margin-bottom: 20px;
        }
        .error-item { margin: 5px 0; }
        .current-cover {
            margin-top: 15px;
            padding: 15px;
            background: rgba(255,215,0,0.1);
            border-left: 3px solid #FFD700;
            border-radius: 4px;
        }
        .current-cover img {
            max-width: 150px;
            border-radius: 4px;
            margin-top: 10px;
        }
        .button-group {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container form-container">
        <div class="breadcrumbs">
            <a href="albums_list.php">Управление альбомами</a> &raquo;
            <a href="album_view.php?id=<?= $albumId ?>">📀 <?= htmlspecialchars($album['title']) ?></a> &raquo;
            <span>Редактировать</span>
        </div>
        
        <h1>✏️ Редактировать альбом</h1>
        
        <?php if (!empty($errors)): ?>
            <div class="errors">
                <strong>❌ Ошибки:</strong>
                <?php foreach ($errors as $error): ?>
                    <div class="error-item">• <?= htmlspecialchars($error) ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <form action="album_edit.php?id=<?= $albumId ?>" method="POST" enctype="multipart/form-data">
            
            <div class="form-group">
                <label for="title">Название альбома *</label>
                <input type="text" id="title" name="title" 
                    value="<?= htmlspecialchars($title) ?>" required>
            </div>
            
            <div class="form-group">
                <label for="description">Описание альбома</label>
                <textarea id="description" name="description" rows="5" 
                    placeholder="Опишите альбом..."><?= htmlspecialchars($description) ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="releaseDate">Дата релиза</label>
                <input type="date" id="releaseDate" name="releaseDate" 
                    value="<?= htmlspecialchars($releaseDate ?? '') ?>">
            </div>
            
            <div class="form-group">
                <label for="cover">Обложка альбома (JPG, PNG)</label>
                <input type="file" id="cover" name="cover" accept="image/jpeg, image/png">
                <div class="current-cover">
                    📷 Текущая обложка:
                    <img src="../<?= htmlspecialchars(ltrim($coverImagePath, '/')) ?>" 
                        alt="<?= htmlspecialchars($title) ?>">
                </div>
            </div>
            
            <div class="button-group">
                <button type="submit" class="submit-button">💾 Сохранить изменения</button>
                <a href="album_view.php?id=<?= $albumId ?>" style="padding: 12px 30px; background-color: #555; color: #fff; text-decoration: none; border-radius: 4px; display: inline-block; font-weight: bold;">← Назад</a>
            </div>
        </form>
    </div>
</body>
</html>