<?php
  session_start();

  require_once "init.php";

  // ВАЛИДАЦИЯ ФОРМЫ  ///////////////////////////////////////////////////////
  $required = ['email', 'password', 'name', 'message'];

  $errors = [];

  $rules = [
    'email' => 'validate_email',
  ];

  var_dump($_POST);
  echo '<br>';

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    foreach ($_POST as $key => $value) {
      if (in_array($key, $required) && $value === '') {
        $errors[] = $key;
        $errors_messages[$key] = 'Обязательное поле';
      }

      if (in_array($key, array_keys($rules))) {
        $result = call_user_func($rules[$key], $value);

        if (!$result) {
          $errors[] = $key;

          switch ($rules[$key]) {
            case 'validate_email':
              $errors_messages[$key] = 'Введите правильный email';
              break;
          }
        }
      }
    }
    ///////////////////////////////////////////////////////////////////////////

    var_dump($_FILES);
    echo '<br>';

    if (isset($_FILES['photo2'])) {
      $file = $_FILES['photo2'];

      if (!empty($file['name'])) {
        if (validate_jpeg_file($file)) {
          $new_file_url = move_uploaded_file_to_dir($file, '/img/');
          $_SESSION['photo-path'] = $new_file_url;
        }
        else {
          $errors[] = 'photo2';
          $errors_messages['photo2'] = 'Загрузите фото в jpg формате';
        }
      }
    }

    ///////////////////////////////////////////////////////////////////////////

    $email = $_POST['email'] ?? '';
    $name = $_POST['name'] ?? '';
    $message = $_POST['message'] ?? '';

    $file_url = $_SESSION['photo-path'] ?? '';

    if (!count($errors)) {

      print('Все замечательно отправилось');

      unset($_SESSION['photo-path']);

      die();
    }
    else {

      $page_content = render_template('templates/sign-up.php',
        [
          'errors' => $errors,
          'errors_messages' => $errors_messages,
          'email' => $email,
          'name' => $name,
          'message' => $message
        ]);

      $layout_content = render_template('templates/layout.php',
        [
          'page_content' => $page_content,
          'categories' => $categories,
          'user' => $user,
          'page_title' => 'Регистрация нового аккаунта'
        ]);

      print($layout_content);

      die();
    }

  }

  $page_content = render_template('templates/sign-up.php',
    [
      'errors' => $errors
    ]);

  $layout_content = render_template('templates/layout.php',
    [
      'page_content' => $page_content,
      'categories' => $categories,
      'user' => $user,
      'page_title' => 'Регистрация нового аккаунта'
    ]);

  print($layout_content);
