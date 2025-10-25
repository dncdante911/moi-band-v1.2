<?php
class FileValidator {
    private $allowed_mimes = [
        'audio/mpeg' => ['mp3'],
        'audio/wav' => ['wav'],
        'audio/x-wav' => ['wav'],
        'image/jpeg' => ['jpg', 'jpeg'],
        'image/png' => ['png'],
        'image/gif' => ['gif']
    ];
    
    private $max_size;
    
    public function __construct($max_size = 104857600) {
        $this->max_size = $max_size;
    }
    
    public function validate($file, $type = 'audio') {
        // Проверка наличия файла
        if (!isset($file['tmp_name']) || !file_exists($file['tmp_name'])) {
            return ['valid' => false, 'error' => 'Файл не найден'];
        }
        
        // Проверка размера
        if ($file['size'] > $this->max_size) {
            return ['valid' => false, 'error' => 'Файл слишком большой'];
        }
        
        // Проверка MIME-типа
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        
        if (!isset($this->allowed_mimes[$mime])) {
            return ['valid' => false, 'error' => 'Недопустимый MIME-тип: ' . $mime];
        }
        
        if (!in_array($ext, $this->allowed_mimes[$mime])) {
            return ['valid' => false, 'error' => 'Расширение не совпадает с типом файла'];
        }
        
        return ['valid' => true];
    }
}