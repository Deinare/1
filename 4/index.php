<?php

header("Content-Type: text/html, charset=UTF-8");
$error = false;

if($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $fio = isset($_POST['fio']) ? $_POST['fio'] : '';
    $number = isset($_POST['number']) ? $_POST['number'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $date = isset($_POST['date']) ? $_POST['date'] : '';
    $radio = isset($_POST['radio']) ? $_POST['radio'] : '';
    $language = isset($_POST['language']) ? $_POST['language'] : [];
    $bio = isset($_POST['bio']) ? $_POST['bio'] : '';
    $check = isset($_POST['check']) ? $_POST['check'] : '';
    
    function pole_prov($cook, $str, $flag)
    {
        global $error;
        $res = false;
        $setval = isset($_POST[$cook]) ? $_POST[$cook] : '';
        if($flag)
        {
            setcookie($cook.'_error', $str, time() + 24*60*60);
            $error = true;
            $res = true;
        }
        if($cook == 'language')
        {
            global $language;
            $setval = ($language != '') ? implode(",", $language) : '';
        }
        setcookie($cook.'_value', $setval, time() + 30*24*60*60);
        return $res;
    }

    if(!pole_prov('fio', 'заполните поле', empty($fio)))
        pole_prov('fio', 'допустимы только русские буквы', !preg_match('/^([а-яё]+-?[а-яё]+)( [а-яё]+-?[а-яё]+){1,2}$/Diu', $fio));
    if(!pole_prov('number', 'заполните поле', empty($number)))
    {
        pole_prov('number', 'поле должно содержать 11 цифр', strlen($number) != 11);
        pole_prov('number', 'другие символы, кроме цифр, не допускаются', $number != preg_replace('/\D/', '', $number));
    }
    if(!pole_prov('email', 'заполните поле', empty($email)))
        pole_prov('email', 'Пожалуйста, введите почту по образцу: example@mail.ru', !preg_match('/^\w+([.-]?\w+)@\w+([.-]?\w+)(.\w{2,3})+$/', $email));
    if(!pole_prov('date', 'заполните поле', empty($date)))
        pole_prov('date', 'дата не может превышать нынешнюю', strtotime('now') < strtotime($date));
    pole_prov('radio', "выберите пол", empty($radio) || !preg_match('/^(M|W)$/', $radio));
    if(!pole_prov('bio', 'заполните поле', empty($bio)))
        pole_prov('bio', 'Пожалуйста, сократите объем сообщения. Максимальное количество символов: 65535', strlen($bio) > 65535);
    pole_prov('check', 'Не ознакомлены с контрактом', empty($check));

	$user = 'u68918'; 
	$pass = '7758388'; 
	$db = new PDO('mysql:host=localhost;dbname=u68918', $user, $pass,
	[PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]); 

    $inQuery = implode(',', array_fill(0, count($language), '?'));

    if(!pole_prov('language', 'Выберите язык программирования', empty($language)))
    {
        try
        {
            $dbLangs = $db->prepare("SELECT id, name FROM languages WHERE name IN ($inQuery)");
            foreach ($language as $key => $value)
                $dbLangs->bindValue(($key+1), $value);
            $dbLangs->execute();
            $languages = $dbLangs->fetchAll(PDO::FETCH_ASSOC);
        }
        catch(PDOException $e)
        {
            print('Error : '.$e->getMessage());
            exit();
        }
        pole_prov('language', 'Неверно выбраны языки', $dbLangs->rowCount() != count($language));
    }
    
    if (!$error)
    {
        setcookie('fio_error', '', time() - 30 * 24 * 60 * 60);
        setcookie('number_error', '', time() - 30 * 24 * 60 * 60);
        setcookie('email_error', '', time() - 30 * 24 * 60 * 60);
        setcookie('date_error', '', time() - 30 * 24 * 60 * 60);
        setcookie('radio_error', '', time() - 30 * 24 * 60 * 60);
        setcookie('language_error', '', time() - 30 * 24 * 60 * 60);
        setcookie('bio_error', '', time() - 30 * 24 * 60 * 60);
        setcookie('check_error', '', time() - 30 * 24 * 60 * 60);
        try
        {
            $stmt = $db->prepare("INSERT INTO form_data (fio, number, email, dat, radio, bio) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$fio, $number, $email, $date, $radio, $bio]);
            $fid = $db->lastInsertId();
            $stmt1 = $db->prepare("INSERT INTO form_data_lang (id_form, id_lang) VALUES (?, ?)");
            foreach($languages as $row)
                $stmt1->execute([$fid, $row['id']]);
        }
        catch(PDOException $e)
        {
            print('Error : ' . $e->getMessage());
            exit();
        }
        setcookie('fio_value', $fio, time() + 60 * 60 * 24 * 365);
        setcookie('number_value', $number, time() + 60 * 60 * 24 * 365);
        setcookie('email_value', $email, time() + 60 * 60 * 24 * 365);
        setcookie('date_value', $date, time() + 60 * 60 * 24 * 365);
        setcookie('radio_value', $radio, time() + 60 * 60 * 24 * 365);
        setcookie('language_value', implode(",", $language), time() + 60 * 60 * 24 * 365);
        setcookie('bio_value', $bio, time() +  60 * 60 * 24 * 365);
        setcookie('check_value', $check, time() + 60 * 60 * 24 * 365);

        setcookie('save', '1');
    }
    header('Location: index.php');
}
else
{
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

    function pole_prov($str, $pole)
    {
        global $errors, $messages, $values;
        $errors[$str] = !empty($pole);
        $messages[$str] = "<div class=\"error\">$pole</div>";
        $values[$str] = empty($_COOKIE[$str.'_value']) ? '' : $_COOKIE[$str.'_value'];
        setcookie($str.'_error', '', time() - 30 * 24 * 60 * 60);
        return;
    }

    if (!empty($_COOKIE['save']))
    {
        setcookie('save', '', 100000);
        $messages['success'] = '<div class="message">Данные сохранены</div>';
    }
    else
        $messages['success'] = '';
       
    pole_prov('fio', $fio);
    pole_prov('number', $number);
    pole_prov('email', $email);
    pole_prov('date', $date);
    pole_prov('radio', $radio);
    pole_prov('language', $language);
    pole_prov('bio', $bio);
    pole_prov('check', $check);

    $languages = explode(',', $values['language']);

    include('form.php');
}
?>
