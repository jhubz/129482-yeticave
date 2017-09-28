<?php if (!empty($errors)): ?>
    <form class="form container form--invalid" action="login.php" method="post">
<?php else: ?>
    <form class="form container" action="login.php" method="post">
<?php endif; ?>

    <?php if (isset($message)): ?>
        <p><?=$message;?></p>
    <?php endif; ?>

    <h2>Вход</h2>

    <?php if (in_array('email', $errors)): ?>
        <div class="form__item form__item--invalid">
    <?php else: ?>
        <div class="form__item">
    <?php endif; ?>
        <label for="email">E-mail*</label>
        <input id="email" type="text" name="email" placeholder="Введите e-mail" value="<?=$email;?>">
        <?php if (isset($invalid_email_message)): ?>
            <span class="form__error"><?=$invalid_email_message?></span>
        <?php else: ?>
            <span class="form__error">Введите e-mail</span>
        <?php endif; ?>
    </div>

    <?php if (in_array('password', $errors)): ?>
        <div class="form__item form__item--last form__item--invalid">
    <?php else: ?>
        <div class="form__item form__item--last">
    <?php endif; ?>
        <label for="password">Пароль*</label>
        <input id="password" type="text" name="password" placeholder="Введите пароль">
        <?php if (isset($invalid_password_message)): ?>
            <span class="form__error"><?=$invalid_password_message?></span>
        <?php else: ?>
            <span class="form__error">Введите пароль</span>
        <?php endif; ?>
    </div>

  <button type="submit" class="button">Войти</button>

</form>
