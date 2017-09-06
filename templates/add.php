<?php if (!empty($errors)): ?>
  <form class="form form--invalid form--add-lot container" action="add.php" method="post" enctype="multipart/form-data"> <!-- form--invalid -->
<?php else: ?>
  <form class="form form--add-lot container" action="add.php" method="post" enctype="multipart/form-data">
<?php endif; ?>
  <h2>Добавление лота</h2>
  <div class="form__container-two">
    <?php if (in_array('lot-name', $errors)): ?>
      <div class="form__item form__item--invalid"> <!-- form__item--invalid -->
    <?php else: ?>
      <div class="form__item">
    <?php endif; ?>
      <label for="lot-name">Наименование</label>
      <input id="lot-name" type="text" name="lot-name" placeholder="Введите наименование лота" value="<?=$lot_name;?>"> <!--required-->
      <span class="form__error">
        <?php
          if (in_array('lot-name', $errors)) {
            print($errors_messages['lot-name']);
          }
        ?>
      </span>
    </div>
    <?php if (in_array('category', $errors)): ?>
        <div class="form__item form__item--invalid">
    <?php else: ?>
        <div class="form__item">
    <?php endif; ?>
      <label for="category">Категория</label>
      <select id="category" name="category"> <!--required-->
        <option>Выберите категорию</option>
            <?php foreach ($categories as $category): ?>
                <?php if ($selected_category === $category): ?>
                    <option value="<?=$category;?>" selected><?=$category;?></option>
                <?php else: ?>
                    <option value="<?=$category;?>"><?=$category;?></option>
                <?php endif; ?>
            <?php endforeach; ?>
      </select>
      <span class="form__error">
        <?php
          if (in_array('category', $errors)) {
            print($errors_messages['category']);
          }
        ?>
      </span>
    </div>
  </div>
  <?php if (in_array('message', $errors)): ?>
    <div class="form__item form__item--wide form__item--invalid">
  <?php else: ?>
    <div class="form__item form__item--wide">
  <?php endif; ?>
    <label for="message">Описание</label>
    <textarea id="message" name="message" placeholder="Напишите описание лота"><?=$message;?></textarea> <!--required-->
    <span class="form__error">
      <?php
        if (in_array('message', $errors)) {
          print($errors_messages['message']);
        }
      ?>
    </span>
  </div>

  <input type="hidden" id="photo-path" name="photo-path" value="<?=$file_url;?>">

  <?php if ($file_url): ?>
    <div class="form__item form__item--file form__item--uploaded">
  <?php else: ?>
    <div class="form__item form__item--file">
  <?php endif; ?>
    <label>Изображение</label>
    <div class="preview">
      <button class="preview__remove" type="button">x</button>
      <div class="preview__img">
        <img src="<?=$file_url;?>" width="113" height="113" alt="Изображение лота">
      </div>
    </div>
    <div class="form__input-file">
      <input class="visually-hidden" type="file" id="photo2" name="photo2" value="">
      <label for="photo2">
        <span>
          + Добавить
          <?php
            if (in_array('photo2', $errors)) {
              print($errors_messages['photo2']);
            }
          ?>
        </span>
      </label>
    </div>
  </div>
  <div class="form__container-three">
    <?php if (in_array('lot-rate', $errors)): ?>
      <div class="form__item form__item--small form__item--invalid">
    <?php else: ?>
      <div class="form__item form__item--small">
    <?php endif; ?>
      <label for="lot-rate">Начальная цена</label>
      <input id="lot-rate" name="lot-rate" placeholder="0" value="<?=$lot_rate;?>"> <!--type="number"--> <!--required-->
      <span class="form__error">
        <?php
          if (in_array('lot-rate', $errors)) {
            print($errors_messages['lot-rate']);
          }
        ?>
      </span>
    </div>
    <?php if (in_array('lot-step', $errors)): ?>
      <div class="form__item form__item--small form__item--invalid">
    <?php else: ?>
      <div class="form__item form__item--small">
    <?php endif; ?>
      <label for="lot-step">Шаг ставки</label>
      <input id="lot-step"  name="lot-step" placeholder="0" value="<?=$lot_step;?>"> <!--type="number"--> <!--required-->
      <span class="form__error">
        <?php
          if (in_array('lot-step', $errors)) {
            print($errors_messages['lot-step']);
          }
        ?>
      </span>
    </div>
    <?php if (in_array('lot-date', $errors)): ?>
      <div class="form__item form__item--invalid">
    <?php else: ?>
      <div class="form__item">
    <?php endif; ?>
      <label for="lot-date">Дата завершения</label>
      <input class="form__input-date" id="lot-date" type="text" name="lot-date" placeholder="20.05.2017" value="<?=$lot_date;?>"> <!--required-->
      <span class="form__error">
        <?php
          if (in_array('lot-date', $errors)) {
            print($errors_messages['lot-date']);
          }
        ?>
      </span>
    </div>
  </div>
  <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
  <button type="submit" class="button">Добавить лот</button>
</form>
