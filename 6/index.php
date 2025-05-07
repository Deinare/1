<?php
$user = 'u68918'; 
$pass = '7758388'; 
$db = new PDO('mysql:host=localhost;dbname=u68918', $user, $pass,
[PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]); 

header("Content-Type: text/html; charset=UTF-8");
session_start();

$error = false;
$log = !empty($_SESSION['login']);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	
	
	
    $fio = isset($_POST['fio']) ? $_POST['fio'] : '';
    $number = isset($_POST['number']) ? $_POST['number'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $date = isset($_POST['date']) ? $_POST['date'] : '';
    $radio = isset($_POST['radio']) ? $_POST['radio'] : '';
    $language = isset($_POST['language']) ? $_POST['language'] : [];
    $bio = isset($_POST['bio']) ? $_POST['bio'] : '';
    $check = isset($_POST['check']) ? $_POST['check'] : '';

    if (isset($_POST['logout_form'])) {
        setcookie('fio_value', '', time() - 30 * 24 * 60 * 60);
        setcookie('number_value', '', time() - 30 * 24 * 60 * 60);
        setcookie('email_value', '', time() - 30 * 24 * 60 * 60);
        setcookie('date_value', '', time() - 30 * 24 * 60 * 60);
        setcookie('radio_value', '', time() - 30 * 24 * 60 * 60);
        setcookie('language_value', '', time() - 30 * 24 * 60 * 60);
        setcookie('bio_value', '', time() - 30 * 24 * 60 * 60);
        setcookie('check_value', '', time() - 30 * 24 * 60 * 60);
        session_destroy();
        header('Location: ./');
        exit();
    }
	
    function check_field($cook, $str, $flag)
    {
        global $error;
        $res = false;
        $setval = isset($_POST[$cook]) ? $_POST[$cook] : '';
        if ($flag) {
            setcookie($cook . '_error', $str, time() + 24 * 60 * 60);
            $error = true;
            $res = true;
        }
        if ($cook == 'language') {
            global $language;
            $setval = ($language != '') ? implode(",", $language) : '';
        }
        setcookie($cook . '_value', $setval, time() + 30 * 24 * 60 * 60);
        return $res;
    }

    if (!check_field('fio', 'Заполните поле', empty($fio)))
        check_field('fio', 'допустимы только русские буквы, формат: Имя Фамилия', !preg_match('/^([а-яё]+-?[а-яё]+)( [а-яё]+-?[а-яё]+){1,2}$/Diu', $fio));
    if (!check_field('number', 'Это поле пустое', empty($number))) {
        check_field('number', 'Поле должно содержать 11 цифр', strlen($number) != 11);
        check_field('number', 'Другие символы, кроме цифр, не допускаются', $number != preg_replace('/\D/', '', $number));
    }
    if (!check_field('email', 'Заполните поле', empty($email)))
        check_field('email', 'Пожалуйста, введите почту по образцу: example@mail.ru', !preg_match('/^\w+([.-]?\w+)@\w+([.-]?\w+)(.\w{2,3})+$/', $email));
    if (!check_field('date', 'Заполните поле', empty($date)))
        check_field('date', 'Дата не может превышать нынешнюю', strtotime('now') < strtotime($date));
    check_field('radio', "Выберите пол", empty($radio) || !preg_match('/^(M|W)$/', $radio));
    if (!check_field('bio', 'Заполните поле', empty($bio)))
        check_field('bio', 'Пожалуйста, сократите объем сообщения. Максимальное количество символов: 65535', strlen($bio) > 65535);
    check_field('check', 'Не ознакомлены с контрактом', empty($check));

    if (!check_field('language', 'Выберите язык программирования', empty($language))) {
        try {
            $inQuery = implode(',', array_fill(0, count($language), '?'));
            $dbLangs = $db->prepare("SELECT id, name FROM all_languages WHERE name IN ($inQuery)");
            foreach ($language as $key => $value)
                $dbLangs->bindValue(($key + 1), $value);
            $dbLangs->execute();
            $languages = $dbLangs->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            print ('Error : ' . $e->getMessage());
            exit();
        }
        check_field('language', 'Неверно выбраны языки', $dbLangs->rowCount() != count($language));
    }

    if (!$error) {
        setcookie('fio_error', '', time() - 30 * 24 * 60 * 60);
        setcookie('number_error', '', time() - 30 * 24 * 60 * 60);
        setcookie('email_error', '', time() - 30 * 24 * 60 * 60);
        setcookie('date_error', '', time() - 30 * 24 * 60 * 60);
        setcookie('radio_error', '', time() - 30 * 24 * 60 * 60);
        setcookie('language_error', '', time() - 30 * 24 * 60 * 60);
        setcookie('bio_error', '', time() - 30 * 24 * 60 * 60);
        setcookie('check_error', '', time() - 30 * 24 * 60 * 60);


        if ($log) {
            $stmt = $db->prepare("UPDATE dannye SET fio = ?, number = ?, email = ?, dat = ?, radio = ?, bio = ? WHERE user_id = ?");
            $stmt->execute([$fio, $number, $email, $date, $radio, $bio, $_SESSION['user_id']]);

            $stmt = $db->prepare("DELETE FROM form_dannd_l WHERE id_form = ?");
            $stmt->execute([$_SESSION['form_id']]);

            $stmt1 = $db->prepare("INSERT INTO form_dannd_l(id_form, id_lang) VALUES (?, ?)");
            foreach ($languages as $row)
                $stmt1->execute([$_SESSION['form_id'], $row['id']]);
        } else {
            $login = uniqid();
            $pass = uniqid();
            setcookie('login', $login);
            setcookie('pass', $pass);
            $mpass = md5($pass);
            try {
                $stmt = $db->prepare("INSERT INTO users (login, password) VALUES (?, ?)");
                $stmt->execute([$login, $mpass]);
                $user_id = $db->lastInsertId();

                $stmt = $db->prepare("INSERT INTO dannye (user_id, fio, number, email, dat, radio, bio) VALUES (?, ?, ?, ?, ?, ?, ? )");
                $stmt->execute([$user_id, $fio, $number, $email, $date, $radio, $bio]);
                $fid = $db->lastInsertId();

                $stmt1 = $db->prepare("INSERT INTO form_dannd_l (id_form, id_lang) VALUES (?, ?)");
                foreach ($languages as $row)
                    $stmt1->execute([$fid, $row['id']]);
            } catch (PDOException $e) {
                print ('Error : ' . $e->getMessage());
                exit();
            }
            setcookie('fio_value', $fio, time() + 24 * 60 * 60 * 365);
            setcookie('number_value', $number, time() + 24 * 60 * 60 * 365);
            setcookie('email_value', $email, time() + 24 * 60 * 60 * 365);
            setcookie('date_value', $date, time() + 24 * 60 * 60 * 365);
            setcookie('radio_value', $radio, time() + 24 * 60 * 60 * 365);
            setcookie('language_value', implode(",", $language), time() + 24 * 60 * 60 * 365);
            setcookie('bio_value', $bio, time() + 24 * 60 * 60 * 365);
            setcookie('check_value', $check, time() + 24 * 60 * 60 * 365);
        }
        setcookie('save', '1');
    }
    header('Location: index.php');
} else {
    $fio = !empty($_COOKIE['fio_error']) ? $_COOKIE['fio_error'] : '';
    $number = !empty($_COOKIE['number_error']) ? $_COOKIE['number_error'] : '';
    $email = !empty($_COOKIE['email_error']) ? $_COOKIE['email_error'] : '';
    $date = !empty($_COOKIE['date_error']) ? $_COOKIE['date_error'] : '';
    $radio = !empty($_COOKIE['radio_error']) ? $_COOKIE['radio_error'] : '';
    $language = !empty($_COOKIE['language_error']) ? $_COOKIE['language_error'] : '';
    $bio = !empty($_COOKIE['bio_error']) ? $_COOKIE['bio_error'] : '';
    $check = !empty($_COOKIE['check_error']) ? $_COOKIE['check_error'] : '';

    $errors = array();
    $messages = array();
    $values = array();
    $error = true;

    function set_val($str, $pole)
    {
        global $values;
        $values[$str] = empty($pole) ? '' : strip_tags($pole);
    }

    function check_field($str, $pole)
    {
        global $errors, $messages, $values, $error;
        if ($error)
            $error = empty($_COOKIE[$str . '_error']);
        $errors[$str] = !empty($_COOKIE[$str . '_error']);
        $messages[$str] = "<div class=\"error\">$pole</div>";
        $values[$str] = empty($_COOKIE[$str . '_value']) ? '' : strip_tags($_COOKIE[$str . '_value']);
        setcookie($str . '_error', '', time() - 30 * 24 * 60 * 60);
        return;
    }

    
	if (!empty($_COOKIE['save'])) {
        setcookie('save', '', 100000);
        setcookie('login', '', 100000);
        setcookie('pass', '', 100000);
        $messages['success'] = 'Спасибо, результаты сохранены.';
        if (!empty($_COOKIE['pass']))
            $messages['info'] = sprintf('Вы можете <a href="login.php">войти</a> с логином <strong>%s</strong><br>
            и паролем <strong>%s</strong> для изменения данных.', strip_tags($_COOKIE['login']), strip_tags($_COOKIE['pass']));
    }

    check_field('fio', $fio);
    check_field('number', $number);
    check_field('email', $email);
    check_field('date', $date);
    check_field('radio', $radio);
    check_field('language', $language);
    check_field('bio', $bio);
    check_field('check', $check);

    $languages = explode(',', $values['language']);
    if ($error && !empty($_SESSION['login'])) {
        try {
			
            $dbLangs = $db->prepare("SELECT * FROM dannye WHERE user_id = ?");
            $dbLangs->execute([$_SESSION['user_id']]);
            $user_inf = $dbLangs->fetchAll(PDO::FETCH_ASSOC)[0];

            $form_id = $user_inf['id'];
            $_SESSION['form_id'] = $form_id;

            $dbL = $db->prepare("SELECT l.name FROM form_dannd_l f
                                JOIN all_languages l ON l.id = f.id_lang
                                WHERE f.id_form = ?");

            $dbL->execute([$form_id]);

            $languages = [];
            foreach ($dbL->fetchAll(PDO::FETCH_ASSOC) as $item)
                $languages[] = $item['name'];

            set_val('fio', $user_inf['fio']);
            set_val('number', $user_inf['number']);
            set_val('email', $user_inf['email']);
            set_val('date', $user_inf['dat']);
            set_val('radio', $user_inf['radio']);
            set_val('language', $language);
            set_val('bio', $user_inf['bio']);
            set_val('check', "1");
			
		/*	printf('Вход с логином %s, user_id %d', $_SESSION['login'], $_SESSION['user_id']);*/
			
        } catch (PDOException $e) {
            print ('Error : ' . $e->getMessage());
            exit();
        }
    }

    include ('form.php');
}
?>
