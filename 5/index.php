<?php
session_start();
header("Content-Type: text/html; charset=UTF-8");

$error = false;
$isLoggedIn = !empty($_SESSION['login']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Логаут
    if (isset($_POST['logout_form'])) {
        foreach (['fio', 'number', 'email', 'date', 'radio', 'language', 'bio', 'check'] as $field) {
            setcookie($field . '_value', '', time() - 3600);
        }
        session_destroy();
        header('Location: ./');
        exit();
    }

    // Получение данных
    $fields = [
        'fio' => $_POST['fio'] ?? '',
        'number' => $_POST['number'] ?? '',
        'email' => $_POST['email'] ?? '',
        'date' => $_POST['date'] ?? '',
        'radio' => $_POST['radio'] ?? '',
        'language' => $_POST['language'] ?? [],
        'bio' => $_POST['bio'] ?? '',
        'check' => $_POST['check'] ?? ''
    ];

    // Валидация
    function setFieldCookie($name, $value, $isError = false, $errorMessage = '') {
        if ($isError) {
            setcookie("{$name}_error", $errorMessage, time() + 86400);
            global $error;
            $error = true;
        }
        $saveValue = is_array($value) ? implode(',', $value) : $value;
        setcookie("{$name}_value", $saveValue, time() + 30 * 24 * 60 * 60);
    }

    setFieldCookie('fio', $fields['fio'], empty($fields['fio']) || !preg_match('/^([а-яё]+-?[а-яё]+)( [а-яё]+-?[а-яё]+){1,2}$/iu', $fields['fio']), 'Некорректное ФИО');
    setFieldCookie('number', $fields['number'], empty($fields['number']) || strlen($fields['number']) !== 11 || !ctype_digit($fields['number']), 'Некорректный телефон');
    setFieldCookie('email', $fields['email'], empty($fields['email']) || !filter_var($fields['email'], FILTER_VALIDATE_EMAIL), 'Некорректный email');
    setFieldCookie('date', $fields['date'], empty($fields['date']) || strtotime($fields['date']) > time(), 'Неверная дата');
    setFieldCookie('radio', $fields['radio'], empty($fields['radio']) || !in_array($fields['radio'], ['M', 'W']), 'Не выбран пол');
    setFieldCookie('bio', $fields['bio'], empty($fields['bio']) || strlen($fields['bio']) > 65535, 'Ошибка в биографии');
    setFieldCookie('check', $fields['check'], empty($fields['check']), 'Не подтверждено соглашение');
    setFieldCookie('language', $fields['language'], empty($fields['language']), 'Языки не выбраны');

    // Проверка языков из базы
    if (!empty($fields['language'])) {
        try {
            $placeholders = implode(',', array_fill(0, count($fields['language']), '?'));
            $stmt = $db->prepare("SELECT id FROM all_languages WHERE name IN ($placeholders)");
            foreach ($fields['language'] as $index => $lang) {
                $stmt->bindValue($index + 1, $lang);
            }
            $stmt->execute();
            if ($stmt->rowCount() !== count($fields['language'])) {
                setFieldCookie('language', $fields['language'], true, 'Неверно выбраны языки');
            }
            $languages = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die('Ошибка: ' . htmlspecialchars($e->getMessage()));
        }
    }

    if (!$error) {
        foreach (['fio', 'number', 'email', 'date', 'radio', 'language', 'bio', 'check'] as $field) {
            setcookie($field . '_error', '', time() - 3600);
        }

        if ($isLoggedIn) {
            // Обновление данных пользователя
            $stmt = $db->prepare("UPDATE dannye SET fio=?, number=?, email=?, dat=?, radio=?, bio=? WHERE user_id=?");
            $stmt->execute([$fields['fio'], $fields['number'], $fields['email'], $fields['date'], $fields['radio'], $fields['bio'], $_SESSION['user_id']]);

            $stmt = $db->prepare("DELETE FROM form_dannd_l WHERE id_form=?");
            $stmt->execute([$_SESSION['form_id']]);

            $insertLang = $db->prepare("INSERT INTO form_dannd_l (id_form, id_lang) VALUES (?, ?)");
            foreach ($languages as $lang) {
                $insertLang->execute([$_SESSION['form_id'], $lang['id']]);
            }
        } else {
            // Регистрация нового пользователя
            $login = uniqid();
            $password = uniqid();
            setcookie('login', $login, time() + 365 * 24 * 3600);
            setcookie('pass', $password, time() + 365 * 24 * 3600);

            $hashedPass = md5($password);
            try {
                $db->prepare("INSERT INTO users (login, password) VALUES (?, ?)")
                    ->execute([$login, $hashedPass]);
                $userId = $db->lastInsertId();

                $db->prepare("INSERT INTO dannye (user_id, fio, number, email, dat, radio, bio) VALUES (?, ?, ?, ?, ?, ?, ?)")
                    ->execute([$userId, $fields['fio'], $fields['number'], $fields['email'], $fields['date'], $fields['radio'], $fields['bio']]);

                $formId = $db->lastInsertId();

                $insertLang = $db->prepare("INSERT INTO form_dannd_l (id_form, id_lang) VALUES (?, ?)");
                foreach ($languages as $lang) {
                    $insertLang->execute([$formId, $lang['id']]);
                }
            } catch (PDOException $e) {
                die('Ошибка: ' . htmlspecialchars($e->getMessage()));
            }
        }

        setcookie('save', '1', time() + 3600);
        header('Location: index.php');
        exit();
    }

    header('Location: index.php');
    exit();
}

// Обработка GET-запроса
$messages = [];
$values = [];
$errors = [];

foreach (['fio', 'number', 'email', 'date', 'radio', 'language', 'bio', 'check'] as $field) {
    $values[$field] = $_COOKIE[$field . '_value'] ?? '';
    if (!empty($_COOKIE[$field . '_error'])) {
        $errors[$field] = true;
        $messages[$field] = "<div class='error'>{$_COOKIE[$field . '_error']}</div>";
    }
    setcookie($field . '_error', '', time() - 3600);
}

// Сообщение об успешном сохранении
if (!empty($_COOKIE['save'])) {
    setcookie('save', '', time() - 3600);
    $messages['success'] = 'Спасибо, данные сохранены.';

    if (!empty($_COOKIE['login']) && !empty($_COOKIE['pass'])) {
        $messages['info'] = sprintf(
            'Вы можете <a href="login.php">войти</a> под логином <strong>%s</strong> и паролем <strong>%s</strong> для редактирования.',
            htmlspecialchars($_COOKIE['login']),
            htmlspecialchars($_COOKIE['pass'])
        );
    }
}

// Если пользователь авторизован - подтянуть его данные
if (!empty($_SESSION['login'])) {
    try {
        $stmt = $db->prepare("SELECT * FROM dannye WHERE user_id=?");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $_SESSION['form_id'] = $user['id'];

            $stmt = $db->prepare("SELECT l.name FROM form_dannd_l f JOIN all_languages l ON l.id = f.id_lang WHERE f.id_form=?");
            $stmt->execute([$user['id']]);
            $langs = $stmt->fetchAll(PDO::FETCH_COLUMN);

            $values = [
                'fio' => $user['fio'],
                'number' => $user['number'],
                'email' => $user['email'],
                'date' => $user['dat'],
                'radio' => $user['radio'],
                'language' => implode(',', $langs),
                'bio' => $user['bio'],
                'check' => '1'
            ];
        }
    } catch (PDOException $e) {
        die('Ошибка: ' . htmlspecialchars($e->getMessage()));
    }
}

include('form.php');
?>
