<?php
// Налаштування
$uploadDir = 'uploads/'; // Каталог для зберігання завантажених зображень
$allowedTypes = ['image/jpeg', 'image/png', 'image/gif']; // Дозволені типи файлів
$maxFileSize = 5 * 1024 * 1024; // Максимальний розмір файлу (5MB)
$message = ''; // Повідомлення для користувача
$messageType = ''; // Тип повідомлення (success/error)

// Перевірка чи директорія існує і налаштування прав
if (!file_exists($uploadDir)) {
    if (!mkdir($uploadDir, 0777, true)) {
        $message = "Не вдалося створити директорію для завантажень";
        $messageType = 'error';
    } else {
        chmod($uploadDir, 0777); // Встановлюємо максимальні права для діагностики
    }
} else {
    // Перевірка прав доступу для існуючої директорії
    if (!is_writable($uploadDir)) {
        chmod($uploadDir, 0777); // Спроба встановити права запису
        if (!is_writable($uploadDir)) {
            $message = "Директорія $uploadDir існує, але не має прав на запис";
            $messageType = 'error';
        }
    }
}

// Виведення інформації про директорію для діагностики
$dirInfo = "Директорія: " . realpath($uploadDir) . " | ";
$dirInfo .= "Існує: " . (file_exists($uploadDir) ? 'Так' : 'Ні') . " | ";
$dirInfo .= "Права: " . substr(sprintf('%o', fileperms($uploadDir)), -4) . " | ";
$dirInfo .= "Доступ на запис: " . (is_writable($uploadDir) ? 'Так' : 'Ні');

// Обробка запиту на завантаження файлу
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['image'])) {
    // Перевірка на помилки завантаження
    if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        $errorMessages = [
            UPLOAD_ERR_INI_SIZE => 'Розмір файлу перевищує обмеження upload_max_filesize у php.ini',
            UPLOAD_ERR_FORM_SIZE => 'Розмір файлу перевищує обмеження MAX_FILE_SIZE вказане у формі',
            UPLOAD_ERR_PARTIAL => 'Файл був завантажений лише частково',
            UPLOAD_ERR_NO_FILE => 'Файл не був завантажений',
            UPLOAD_ERR_NO_TMP_DIR => 'Відсутня тимчасова директорія',
            UPLOAD_ERR_CANT_WRITE => 'Помилка запису файлу на диск',
            UPLOAD_ERR_EXTENSION => 'PHP-розширення зупинило завантаження файлу'
        ];
        
        $errorCode = $_FILES['image']['error'];
        $message = isset($errorMessages[$errorCode]) ? $errorMessages[$errorCode] : 'Невідома помилка завантаження';
        $messageType = 'error';
    }
    // Перевірка розміру файлу
    elseif ($_FILES['image']['size'] > $maxFileSize) {
        $message = 'Файл занадто великий. Максимальний розмір: ' . ($maxFileSize / 1024 / 1024) . 'MB';
        $messageType = 'error';
    }
    // Перевірка типу файлу (використовуємо функцію для визначення MIME-типу)
    elseif (!in_array($_FILES['image']['type'], $allowedTypes)) {
        $message = 'Дозволені лише зображення формату JPEG, PNG і GIF. Ваш файл має тип: ' . $_FILES['image']['type'];
        $messageType = 'error';
    }
    else {
        // Генеруємо унікальне ім'я файлу
        $fileInfo = pathinfo($_FILES['image']['name']);
        $newFileName = uniqid() . '.' . $fileInfo['extension'];
        $destination = $uploadDir . $newFileName;
        
        // Виводимо інформацію про файл для діагностики
        $fileDebugInfo = "Тимчасовий файл: " . $_FILES['image']['tmp_name'] . " | ";
        $fileDebugInfo .= "Існує: " . (file_exists($_FILES['image']['tmp_name']) ? 'Так' : 'Ні') . " | ";
        $fileDebugInfo .= "Розмір: " . $_FILES['image']['size'] . " | ";
        $fileDebugInfo .= "Тип: " . $_FILES['image']['type'];
        
        // Спроба завантажити файл
        if (move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {
            $message = 'Зображення успішно завантажено: ' . $_FILES['image']['name'];
            $messageType = 'success';
            
            // Встановлюємо права доступу для файлу
            chmod($destination, 0644);
        } else {
            $message = 'Помилка при збереженні файлу на сервері. Перевірте права доступу.';
            $messageType = 'error';
        }
    }
}

// Отримуємо список вже завантажених зображень
$uploadedImages = [];
if (file_exists($uploadDir) && is_dir($uploadDir)) {
    $files = scandir($uploadDir);
    foreach ($files as $file) {
        if ($file != '.' && $file != '..') {
            $filePath = $uploadDir . $file;
            if (is_file($filePath)) {
                // Використовуємо безпечніший спосіб визначення типу файлу або просто перевіряємо розширення
                $fileExt = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                if (in_array($fileExt, ['jpg', 'jpeg', 'png', 'gif'])) {
                    $uploadedImages[] = $file;
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Завантаження зображень</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            line-height: 1.6;
        }
        
        h1, h2 {
            color: #333;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
        }
        
        .upload-form {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        
        .btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        
        .btn:hover {
            background-color: #45a049;
        }
        
        .message {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        
        .message.success {
            background-color: #d4edda;
            border-left: 4px solid #28a745;
            color: #155724;
        }
        
        .message.error {
            background-color: #f8d7da;
            border-left: 4px solid #dc3545;
            color: #721c24;
        }
        
        .debug-info {
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 20px;
            font-family: monospace;
            font-size: 14px;
        }
        
        .gallery {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
        }
        
        .gallery-item {
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 10px;
            text-align: center;
        }
        
        .gallery-item img {
            max-width: 100%;
            height: auto;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Завантаження зображень на сервер</h1>
        
        <?php if (!empty($message)): ?>
            <div class="message <?php echo $messageType; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        
        <!-- Інформація для діагностики -->
        <div class="debug-info">
            <h3>Інформація про систему (для діагностики)</h3>
            <p><?php echo $dirInfo; ?></p>
            <?php if (isset($fileDebugInfo)): ?>
                <p><?php echo $fileDebugInfo; ?></p>
            <?php endif; ?>
        </div>
        
        <div class="upload-form">
            <h2>Обрати зображення для завантаження</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="image">Виберіть зображення:</label>
                    <input type="file" name="image" id="image" accept="image/jpeg, image/png, image/gif" required>
                    <small>Максимальний розмір: <?php echo $maxFileSize / 1024 / 1024; ?>MB. Підтримувані формати: JPEG, PNG, GIF.</small>
                </div>
                
                <!-- Встановлюємо обмеження розміру файлу на рівні форми (HTML5) -->
                <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $maxFileSize; ?>">
                
                <button type="submit" class="btn">Завантажити</button>
            </form>
        </div>
        
        <?php if (!empty($uploadedImages)): ?>
            <h2>Завантажені зображення (<?php echo count($uploadedImages); ?>)</h2>
            <div class="gallery">
                <?php foreach ($uploadedImages as $image): ?>
                    <div class="gallery-item">
                        <img src="<?php echo $uploadDir . htmlspecialchars($image); ?>" alt="Завантажене зображення">
                        <p><?php echo htmlspecialchars($image); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>Поки що немає завантажених зображень.</p>
        <?php endif; ?>
    </div>
</body>
</html>