<!DOCTYPE html>
<html lang="ru">
  <head>
    <meta charset="UTF-8" />
    <link rel="stylesheet" href="style.css" />
    <link rel="stylesheet" href="bootstrap.min.css" />
    <title>Задание 4</title>
  </head>
<body>
  <div class="card-form">
    <form action="" method="post">
      <div class="form-title">Форма обратной связи</div>
      <div class="mess"><?php echo $messages['success']; ?></div>

      <div class="mb-3">
        <input name="fio" type="text" class="form-control <?php echo ($errors['fio']) ? 'red' : ''; ?>" placeholder="ФИО" value="<?php echo $values['fio']; ?>">
        <div class="error"><?php echo $messages['fio']; ?></div>
      </div>

      <div class="mb-3">
        <input name="number" type="tel" class="form-control <?php echo ($errors['number']) ? 'red' : ''; ?>" placeholder="Номер телефона" value="<?php echo $values['number']; ?>">
        <div class="error"><?php echo $messages['number']; ?></div>
      </div>

      <div class="mb-3">
        <input name="email" type="text" class="form-control <?php echo ($errors['email']) ? 'red' : ''; ?>" placeholder="Email" value="<?php echo $values['email']; ?>">
        <div class="error"><?php echo $messages['email']; ?></div>
      </div>

      <div class="mb-3">
        <input name="date" type="date" class="form-control <?php echo ($errors['date']) ? 'red' : ''; ?>" value="<?php echo $values['date']; ?>">
        <div class="error"><?php echo $messages['date']; ?></div>
      </div>

      <div class="mb-3">
        <label class="form-label">Пол:</label><br>
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" name="radio" value="M" <?php if($values['radio'] == 'M') echo 'checked'; ?>>
          <label class="form-check-label">Мужской</label>
        </div>
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" name="radio" value="W" <?php if($values['radio'] == 'W') echo 'checked'; ?>>
          <label class="form-check-label">Женский</label>
        </div>
        <div class="error"><?php echo $messages['radio']; ?></div>
      </div>

      <div class="mb-3">
        <label class="form-label">Любимый язык программирования:</label>
        <select class="form-control <?php echo ($errors['language']) ? 'red' : ''; ?>" name="language[]" multiple>
          <?php
          $options = ['Pascal', 'C', 'C++', 'JavaScript', 'PHP', 'Python', 'Java', 'Haskel', 'Clojure', 'Scala'];
          foreach ($options as $opt) {
            $selected = in_array($opt, $languages) ? 'selected' : '';
            echo "<option value=\"$opt\" $selected>$opt</option>";
          }
          ?>
        </select>
        <div class="error"><?php echo $messages['language']; ?></div>
      </div>

      <div class="mb-3">
        <label class="form-label">Биография:</label>
        <textarea name="bio" class="form-control <?php echo ($errors['bio']) ? 'red' : ''; ?>"><?php echo $values['bio']; ?></textarea>
        <div class="error"><?php echo $messages['bio']; ?></div>
      </div>

      <div class="mb-3 form-check">
        <input class="form-check-input" type="checkbox" name="check" <?php echo ($values['check']) ? 'checked' : ''; ?>>
        <label class="form-check-label">С контрактом ознакомлен(а)</label>
        <div class="error"><?php echo $messages['check']; ?></div>
      </div>

      <button type="submit" class="btn-submit">Отправить</button>
    </form>
  </div>
</body>
</html>
