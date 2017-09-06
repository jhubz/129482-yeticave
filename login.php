<?php
  session_start();
  
  require_once "functions.php";
  require_once "data.php";
  require_once "userdata.php";
  
  if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
  }
  
  $required = ['email', 'password'];
  
  $errors = [];

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    foreach ($_POST as $key => $value) {
      if (in_array($key, $required) && $value === '') {
        $errors[] = $key;
      }
    }
    
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    if (!count($errors)) {
      
      if ($user = search_user_by_email($email, $users)) {
        if (password_verify($password, $user['password'])) {
          $_SESSION['user'] = $user;
          header("Location: /index.php");
        }
        else {
          $errors[] = 'password';
          $invalid_password_message = "Вы ввели неверный пароль";
          
          $page_content = render_template('templates/login.php',
            [
              'categories' => $categories,
              'errors' => $errors,
              'invalid_password_message' => $invalid_password_message,
              'email' => $email
            ]);

          $layout_content = render_template('templates/layout.php',
            [
              'page_content' => $page_content,
              'categories' => $categories,
              'user' => $user,
              'page_title' => 'Вход'
            ]);

          print($layout_content);
          
          die();
        }
      }
      else {
        $errors[] = 'email';
        $invalid_email_message = "Такого пользователя нет";
        
        $page_content = render_template('templates/login.php',
          [
            'categories' => $categories,
            'errors' => $errors,
            'invalid_email_message' => $invalid_email_message,
            'email' => $email
          ]);

        $layout_content = render_template('templates/layout.php',
          [
            'page_content' => $page_content,
            'categories' => $categories,
            'user' => $user,
            'page_title' => 'Вход'
          ]);

        print($layout_content);
        
        die();
      }
    }
    else {
      $page_content = render_template('templates/login.php',
        [
          'categories' => $categories,
          'errors' => $errors,
          'email' => $email
        ]);

      $layout_content = render_template('templates/layout.php',
        [
          'page_content' => $page_content,
          'categories' => $categories,
          'user' => $user,
          'page_title' => 'Вход'
        ]);

      print($layout_content);
      
      die();
    }
  }
    
  $page_content = render_template('templates/login.php',
    [
      'categories' => $categories,
      'errors' => $errors
    ]);

  $layout_content = render_template('templates/layout.php',
    [
      'page_content' => $page_content,
      'categories' => $categories,
      'user' => $user,
      'page_title' => 'Вход'
    ]);

  print($layout_content);