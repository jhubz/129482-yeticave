<?php
  session_start();

  require_once "init.php";

  // ВАЛИДАЦИЯ ФОРМЫ  ///////////////////////////////////////////////////////
  $required = ['email', 'password', 'name', 'message'];

  $errors = [];

  $rules = [
    'email' => 'validate_email',
  ];

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

    if (isset($_FILES['photo2'])) {
      $file = $_FILES['photo2'];

      if (!empty($file['name'])) {
        if (validate_image_file($file)) {
          $new_file_url = move_uploaded_file_to_dir($file, '/img/');
          $_SESSION['photo-signup-path'] = $new_file_url;
        }
        else {
          $errors[] = 'photo2';
          $errors_messages['photo2'] = 'Загрузите фото';
        }
      }
    }

    ///////////////////////////////////////////////////////////////////////////

    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $name = $_POST['name'] ?? '';
    $message = $_POST['message'] ?? '';

    $file_url = $_SESSION['photo-signup-path'] ?? '';

    //////////////////////////////////////////////////////////////////////////

    if (!count($errors)) {

      $email_query =
        'SELECT email
        FROM users
        WHERE email = ?
      ';

      $selected_emails = select_data($connect, $email_query, [$email]);

      if ($selected_emails) {

        $errors[] = 'email';
        $errors_messages['email'] = 'Такой пользователь уже существует';

        $page_content = render_template('templates/sign-up.php',
          [
            'errors' => $errors,
            'errors_messages' => $errors_messages,
            'email' => $email,
            'name' => $name,
            'message' => $message,
            'file_url' => $file_url
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
      else {
        unset($_SESSION['photo-signup-path']);

        $inserted_user_id = insert_data($connect, 'users',
          [
            'name' => $name,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'registration_date' => date('Y-m-d H:i:s'),
            'avatar_path' => $file_url,
            'contacts' => $message
          ]
        );

         if ($inserted_user_id) {
           $_SESSION['signup_message'] = 'Теперь вы можете войти, используя свой email и пароль';
           header("Location: /login.php");
         }
      }
    }
    else {

      $page_content = render_template('templates/sign-up.php',
        [
          'errors' => $errors,
          'errors_messages' => $errors_messages,
          'email' => $email,
          'name' => $name,
          'message' => $message,
          'file_url' => $file_url
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

  unset($_SESSION['photo-signup-path']);

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
