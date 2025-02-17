<?php
session_start();

// Обробка форми
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $_SESSION['login'] = htmlspecialchars($_POST['login'] ?? '');
    $_SESSION['gender'] = htmlspecialchars($_POST['gender'] ?? '');
    $_SESSION['city'] = htmlspecialchars($_POST['city'] ?? '');
    $_SESSION['games'] = $_POST['games'] ?? [];
    $_SESSION['about'] = htmlspecialchars($_POST['about'] ?? '');

    // Обробка фото
    if (!empty($_FILES['photo']['name'])) {
        $uploadDir = "uploads/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $maxFileSize = 5 * 1024 * 1024; // 5MB

        $fileTmpPath = $_FILES['photo']['tmp_name'];
        $fileSize = $_FILES['photo']['size'];
        $fileType = mime_content_type($fileTmpPath);
        $fileName = time() . "_" . basename($_FILES['photo']['name']); // Унікальне ім'я файлу
        $targetFile = $uploadDir . $fileName;

        if ($_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            if (!in_array($fileType, $allowedTypes)) {
                echo "<p style='color: red;'>Помилка: Дозволені лише JPG, PNG, GIF!</p>";
            } elseif ($fileSize > $maxFileSize) {
                echo "<p style='color: red;'>Помилка: Максимальний розмір файлу 5MB!</p>";
            } elseif (move_uploaded_file($fileTmpPath, $targetFile)) {
                $_SESSION['photo'] = $targetFile;
            } else {
                echo "<p style='color: red;'>Помилка при збереженні файлу!</p>";
            }
        } else {
            echo "<p style='color: red;'>Помилка при завантаженні файлу: {$_FILES['photo']['error']}</p>";
        }
    }
}

// Обробка вибору мови
if (isset($_GET['lang'])) {
    setcookie('lang', $_GET['lang'], time() + 180 * 24 * 60 * 60);
    $_COOKIE['lang'] = $_GET['lang'];
}

$lang = $_COOKIE['lang'] ?? 'ukr';
$languageText = [
    'ukr' => 'Вибрана мова: Українська',
    'eng' => 'Selected language: English'
];
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Форма реєстрації</title>
</head>
<body>

<h2>Форма реєстрації</h2>
<form enctype="multipart/form-data" method="POST">
    Логін: <input type="text" name="login" value="<?= $_SESSION['login'] ?? '' ?>"><br>
    Пароль: <input type="password" name="password"><br>
    Пароль (ще раз): <input type="password" name="password_confirm"><br>

    Стать: 
    <input type="radio" name="gender" value="чоловік" <?= ($_SESSION['gender'] ?? '') == 'чоловік' ? 'checked' : '' ?>> чоловік
    <input type="radio" name="gender" value="жінка" <?= ($_SESSION['gender'] ?? '') == 'жінка' ? 'checked' : '' ?>> жінка
    <br>

    Місто:
    <select name="city">
        <option value="Житомир" <?= ($_SESSION['city'] ?? '') == 'Житомир' ? 'selected' : '' ?>>Житомир</option>
        <option value="Київ" <?= ($_SESSION['city'] ?? '') == 'Київ' ? 'selected' : '' ?>>Київ</option>
        <option value="Львів" <?= ($_SESSION['city'] ?? '') == 'Львів' ? 'selected' : '' ?>>Львів</option>
    </select>
    <br>

    Улюблені ігри:<br>
    <?php
    $games = ['футбол', 'баскетбол', 'волейбол', 'шахи', 'World of Tanks'];
    foreach ($games as $game) {
        $checked = in_array($game, $_SESSION['games'] ?? []) ? 'checked' : '';
        echo "<input type='checkbox' name='games[]' value='$game' $checked> $game <br>";
    }
    ?>

    Про себе:<br>
    <textarea name="about"><?= $_SESSION['about'] ?? '' ?></textarea><br>

    Фотографія: <input type="file" name="photo"><br>

    <input type="submit" value="Зареєструватися">
</form>

<h2>Результат</h2>
<?php if (!empty($_SESSION['login'])): ?>
    <p><strong>Логін:</strong> <?= $_SESSION['login'] ?></p>
<?php endif; ?>

<?php if (!empty($_SESSION['gender'])): ?>
    <p><strong>Стать:</strong> <?= $_SESSION['gender'] ?></p>
<?php endif; ?>

<?php if (!empty($_SESSION['city'])): ?>
    <p><strong>Місто:</strong> <?= $_SESSION['city'] ?></p>
<?php endif; ?>

<?php if (!empty($_SESSION['games'])): ?>
    <p><strong>Улюблені ігри:</strong> <?= implode(", ", $_SESSION['games']) ?></p>
<?php endif; ?>

<?php if (!empty($_SESSION['about'])): ?>
    <p><strong>Про себе:</strong> <?= $_SESSION['about'] ?></p>
<?php endif; ?>

<?php if (!empty($_SESSION['photo'])): ?>
    <p><strong>Фотографія:</strong><br>
    <img src="<?= $_SESSION['photo'] ?>" width="200" alt="Фото користувача">
    </p>
<?php else: ?>
    <p><strong>Фотографія:</strong> Не завантажено</p>
<?php endif; ?>

<h2>Вибір мови</h2>
<a href="index.php?lang=ukr"><img src="icons/ukr.png" alt="Українська"></a>
<a href="index.php?lang=eng"><img src="icons/eng.png" alt="English"></a>

<p><?= $languageText[$lang] ?></p>

</body>
</html>
