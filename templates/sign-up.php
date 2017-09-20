<?php if (!empty($errors)): ?>
  <form class="form form--invalid container" action="sign-up.php" method="post" enctype="multipart/form-data">
<?php else: ?>
  <form class="form container" action="sign-up.php" method="post" enctype="multipart/form-data">
<?php endif; ?>

    <h2>Регистрация нового аккаунта</h2>

    <?php if (in_array('email', $errors)): ?>
        <div class="form__item form__item--invalid">
    <?php else: ?>
        <div class="form__item">
    <?php endif; ?>
        <label for="email">E-mail*</label>
        <input id="email" type="text" name="email" placeholder="Введите e-mail" value="<?=htmlspecialchars($email);?>">
        <span class="form__error">
            <?php
                if (in_array('email', $errors)) {
                    print($errors_messages['email']);
                }
            ?>
        </span>
    </div>

    <?php if (in_array('password', $errors)): ?>
        <div class="form__item form__item--invalid">
    <?php else: ?>
        <div class="form__item">
    <?php endif; ?>
        <label for="password">Пароль*</label>
        <input id="password" type="text" name="password" placeholder="Введите пароль">
        <span class="form__error">
            <?php
                if (in_array('password', $errors)) {
                    print($errors_messages['password']);
                }
            ?>
        </span>
    </div>

    <?php if (in_array('name', $errors)): ?>
        <div class="form__item form__item--invalid">
    <?php else: ?>
        <div class="form__item">
    <?php endif; ?>
        <label for="name">Имя*</label>
        <input id="name" type="text" name="name" placeholder="Введите имя" value="<?=htmlspecialchars($name);?>">
        <span class="form__error">
            <?php
                if (in_array('name', $errors)) {
                    print($errors_messages['name']);
                }
            ?>
        </span>
    </div>

    <?php if (in_array('message', $errors)): ?>
        <div class="form__item form__item--invalid">
    <?php else: ?>
        <div class="form__item">
    <?php endif; ?>
        <label for="message">Контактные данные*</label>
        <textarea id="message" name="message" placeholder="Напишите как с вами связаться"><?=htmlspecialchars($message);?></textarea>
        <span class="form__error">
            <?php
                if (in_array('message', $errors)) {
                    print($errors_messages['message']);
                }
            ?>
        </span>
    </div>

    <?php if ($file_url): ?>
        <div class="form__item form__item--file form__item--uploaded">
    <?php else: ?>
        <div class="form__item form__item--file form__item--last">
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

    <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
    <button type="submit" class="button">Зарегистрироваться</button>
    <a class="text-link" href="login.php">Уже есть аккаунт</a>
</form>
