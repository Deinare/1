<?php
header('Content-Type: text/html; charset=UTF-8');
session_start();

if (!empty($_SESSION['login'])) {
    header('Location: ./');
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require('database.php');

    $login = trim($_POST['login'] ?? '');
    $password = md5($_POST['password'] ?? '');

    try {
        $stmt = $db->prepare("SELECT id FROM users WHERE login = ? AND password = ?");
        $stmt->execute([$login, $password]);

        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            $_SESSION['login'] = $login;
            $_SESSION['user_id'] = $user['id'];

            header('Location: ./');
            exit();
        } else {
            $error = 'Неверный логин или пароль';
        }
    } catch (PDOException $e) {
        echo 'Ошибка: ' . htmlspecialchars($e->getMessage());
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Авторизация</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<form action="" method="post" class="form">
    <?php if (!empty($error)): ?>
        <div class="mess"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <h2>Вход в систему</h2>

    <input type="text" name="login" class="input" placeholder="Логин" required>

    <input type="password" name="password" class="input" placeholder="Пароль" required>

    <button type="submit" class="button">Войти</button>
</form>

</body>
</html>
