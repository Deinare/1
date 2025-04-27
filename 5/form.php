<?php
function error_class($field) {
    global $errors;
    return !empty($errors[$field]) ? 'red' : '';
}

function display_error($field) {
    global $messages;
    return !empty($messages[$field]) ? "<div class='error'>{$messages[$field]}</div>" : '';
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8" />
  <link rel="stylesheet" href="style.css" />
  <link rel="stylesheet" href="bootstrap.min.css" />
  <title>Форма обратной связи</title>
</head>

<body>
  <form action="" method="post" class="form">
    <div class="head">
      <h2><b>Форма обратной связи</b></h2>
    </div>

    <?php if (!empty($messages['success'])): ?>
      <div class="mess"><?= $messages['success']; ?></div>
    <?php endif; ?>

    <?php if (!empty($messages['info'])): ?>
      <div class="mess mess_info"><?= $messages['info']; ?></div>
    <?php endif; ?>

    <div>
      <label>
        <input name="fio" class="input <?= error_class('fio'); ?>" value="<?= htmlspecialchars($values['fio']); ?>" type="text" placeholder="ФИО" />
      </label>
      <?= display_error('fio'); ?>
    </div>

    <div>
      <label>
        <input name="number" class="input <?= error_class('number'); ?>" value="<?= htmlspecialchars($values['number']); ?>" type="tel" placeholder="Номер телефона" />
      </label>
      <?= display_error('number'); ?>
    </div>

    <div>
      <label>
        <input name="email" class="input <?= error_class('email'); ?>" value="<?= htmlspecialchars($values['email']); ?>" type="text" placeholder="Почта" />
      </label>
      <?= display_error('email'); ?>
    </div>

    <div>
      <label>
        <input name="date" class="input <?= error_class('date'); ?>" value="<?= (strtotime($values['date']) > 100000) ? htmlspecialchars($values['date']) : ''; ?>" type="date" />
      </label>
      <?= display_error('date'); ?>
    </div>

    <div>
      <div>Пол</div>
      <div class="mb-1">
        <label>
          <input name="radio" class="ml-2" type="radio" value="M" <?= ($values['radio'] == 'M') ? 'checked' : ''; ?> />
          <span class="<?= error_class('radio'); ?>">Мужской</span>
        </label>
        <label>
          <input name="radio" class="ml-4" type="radio" value="W" <?= ($values['radio'] == 'W') ? 'checked' : ''; ?> />
          <span class="<?= error_class('radio'); ?>">Женский</span>
        </label>
      </div>
      <?= display_error('radio'); ?>
    </div>

    <div>
      <div>Любимый язык программирования</div>
      <select class="my-2 <?= error_class('language'); ?>" name="language[]" multiple>
        <?php 
        $options = ['Pascal', 'C', 'C++', 'JavaScript', 'PHP', 'Python', 'Java', 'Haskel', 'Clojure', 'Scala'];
        foreach ($options as $option): ?>
          <option value="<?= $option; ?>" <?= in_array($option, $languages) ? 'selected' : ''; ?>><?= $option; ?></option>
        <?php endforeach; ?>
      </select>
      <?= display_error('language'); ?>
    </div>

    <div class="my-2">
      <div>Биография</div>
      <label>
        <textarea name="bio" class="input <?= error_class('bio'); ?>" placeholder="Биография"><?= htmlspecialchars($values['bio']); ?></textarea>
      </label>
      <?= display_error('bio'); ?>
    </div>

    <div>
      <label>
        <input name="check" type="checkbox" <?= !empty($values['check']) ? 'checked' : ''; ?> />
        С контрактом ознакомлен(а)
      </label>
      <?= display_error('check'); ?>
    </div>

    <div class="buttons mt-3">
      <?php if ($log): ?>
        <button class="button edbut" type="submit">Изменить</button>
        <button class="button" type="submit" name="logout_form">Выйти</button>
      <?php else: ?>
        <button class="button" type="submit">Отправить</button>
        <a class="btnlike" href="login.php">Войти</a>
      <?php endif; ?>
    </div>
  </form>
</body>
</html>
