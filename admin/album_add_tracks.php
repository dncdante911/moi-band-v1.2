<?php
/**
 * Файл: admin/album_add_tracks.php
 * ИСПРАВЛЕННАЯ ВЕРСИЯ - массовая загрузка треков в альбом
 */

require_once '../include_config/config.php';
require_once '../include_config/db_connect.php';

// Проверяем, что ID альбома передан
if (!isset($_GET['album_id']) || !filter_var($_GET['album_id'], FILTER_VALIDATE_INT)) {
    header('Location: albums_list.php');
    exit;
}
$albumId = (int)$_GET['album_id'];

// Получаем информацию об альбоме
$stmt = $pdo->prepare("SELECT title, coverImagePath FROM Albums WHERE id = ?");
$stmt->execute([$albumId]);
$album = $stmt->fetch();

if (!$album) {
    header('Location: albums_list.php');
    exit;
}

$albumCoverPath = $album['coverImagePath'];
$errors = [];
$success = false;

// === ОБРАБОТКА POST ЗАПРОСА ===
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['tracks']) && is_array($_FILES['tracks']['name'])) {
        $uploadDirFull = '../public/uploads/full/';
        if (!is_dir($uploadDirFull)) {
            mkdir($uploadDirFull, 0755, true);
        }
        
        $tracksAdded = 0;
        
        // Обрабатываем каждый загруженный файл
        foreach ($_FILES['tracks']['name'] as $key => $name) {
            if ($_FILES['tracks']['error'][$key] === UPLOAD_ERR_OK) {
                try {
                    $tmp_name = $_FILES['tracks']['tmp_name'][$key];
                    $fileExtension = pathinfo($name, PATHINFO_EXTENSION);
                    $fileName = uniqid() . '.' . $fileExtension;
                    $uploadPath = $uploadDirFull . $fileName;
                    
                    if (move_uploaded_file($tmp_name, $uploadPath)) {
                        $trackTitle = pathinfo($name, PATHINFO_FILENAME);
                        
                        // Вставляем трек в БД
                        $sql = "INSERT INTO Track (
                            title, 
                            albumId, 
                            coverImagePath, 
                            fullAudioPath
                        ) VALUES (
                            :title, 
                            :albumId, 
                            :coverImagePath, 
                            :fullAudioPath
                        )";
                        
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute([
                            ':title' => $trackTitle,
                            ':albumId' => $albumId,
                            ':coverImagePath' => $albumCoverPath,
                            ':fullAudioPath' => '/public/uploads/full/' . $fileName
                        ]);
                        
                        $tracksAdded++;
                    } else {
                        $errors[] = "❌ Не удалось сохранить файл: $name";
                    }
                } catch (Exception $e) {
                    $errors[] = "❌ Ошибка для файла $name: " . $e->getMessage();
                }
            } else {
                $errors[] = "❌ Ошибка загрузки: $name";
            }
        }
        
        if ($tracksAdded > 0) {
            $success = true;
            header('Location: album_view.php?id=' . $albumId . '&success=' . $tracksAdded);
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Добавить треки в альбом - Админ-панель</title>
    <link rel="stylesheet" href="../assets/css/admin_style.css">
    <style>
        .form-container { max-width: 800px; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: bold; color: #FFD700; }
        .form-group input { 
            width: 100%; 
            padding: 10px; 
            background-color: #111; 
            border: 2px dashed #FFD700; 
            color: #e0e0e0; 
            border-radius: 4px; 
            box-sizing: border-box;
        }
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
        .info-box {
            background: rgba(100,200,255,0.1);
            border-left: 3px solid #4169E1;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="container form-container">
        <div class="breadcrumbs">
            <a href="albums_list.php">Управление альбомами</a> &raquo;
            <a href="album_view.php?id=<?= $albumId ?>">📀 <?= htmlspecialchars($album['title']) ?></a> &raquo;
            <span>Массовая загрузка треков</span>
        </div>
        
        <h1>➕ Добавить треки в альбом: "<?= htmlspecialchars($album['title']) ?>"</h1>
        
        <?php if (!empty($errors)): ?>
            <div class="errors">
                <strong>❌ Ошибки:</strong>
                <?php foreach ($errors as $error): ?>
                    <div class="error-item"><?= htmlspecialchars($error) ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <div class="info-box">
            <strong>💡 Советы:</strong>
            <ul style="margin: 10px 0 0 20px; color: #a0aec0;">
                <li>Вы можете выбрать несколько файлов одновременно (Ctrl+Click или Cmd+Click)</li>
                <li>Поддерживаемые форматы: MP3, WAV</li>
                <li>Максимум 100MB на один файл</li>
                <li>Все треки будут добавлены с обложкой альбома</li>
                <li>После загрузки вы сможете отредактировать каждый трек отдельно</li>
            </ul>
        </div>
        
        <form action="album_add_tracks.php?album_id=<?= $albumId ?>" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="tracks">📁 Выберите файлы треков (MP3, WAV) *</label>
                <input type="file" id="tracks" name="tracks[]" accept=".mp3, .wav" required multiple>
            </div>
            
            <button type="submit" class="submit-button">✅ Загрузить треки</button>
            <a href="album_view.php?id=<?= $albumId ?>" style="margin-left: 15px; color: #FFD700; text-decoration: none;">← Назад</a>
        </form>
    </div>
</body>
</html>