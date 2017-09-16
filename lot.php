<?php
  session_start();

  require_once "init.php";
  require_once 'data.php';

  if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
  }

  if (isset($_GET['id'])) {

    $id = (int)$_GET['id'];

    if (!array_key_exists($id, $lots)) {
      http_response_code(404);
      print("Такой страницы не существует (ошибка 404)");
    }
    else {

      $errors = [];
      $errors_messages = [];
      $is_done_bet = false;

      if (isset($_COOKIE['bets_data'])) {
        $bets_data = json_decode($_COOKIE['bets_data'], true);

        foreach ($bets_data as $bet_data) {
          if ($bet_data['id'] === $id) {
            $is_done_bet = true;
          }
        }
      }

      if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $required = ['cost'];

        $rules = [
          'cost' => 'validate_number'
        ];

        foreach ($_POST as $key => $value) {
          if (in_array($key, $required) && $value === '') {
            $errors[] = $key;
            $errors_messages[$key] = 'Обязательное поле';
            break;
          }

          if (in_array($key, array_keys($rules))) {
            $result = call_user_func($rules[$key], $value);

            if (!$result) {
              $errors[] = $key;

              switch ($rules[$key]) {
                case 'validate_number':
                  $errors_messages[$key] = 'Введите целое положительное число';
                  break;
              }
            }
          }
        }

        $cost = filter_text($_POST['cost']);

        if (empty($errors)) {

          $cost = intval($cost);
          $bets_data[] = ['id' => $id, 'date' => strtotime('now'), 'cost' => $cost];
          $expire_date = strtotime('Mon, 25-Jan-2027 10:00:00 GMT');

          setcookie('bets_data', json_encode($bets_data), $expire_date, '/');

          header("Location: /mylots.php");

        }
        else {
          $page_content = render_template('templates/lot.php',
            [
              'categories' => $categories,
              'user' => $user,
              'lots' => $lots,
              'id' => $id,
              'bets' => $bets,
              'errors' => $errors,
              'errors_messages' => $errors_messages,
              'cost' => $cost
            ]);

          $layout_content = render_template('templates/layout.php',
            [
              'page_content' => $page_content,
              'user' => $user,
              'categories' => $categories,
              'page_title' => $lots[$id]['title']
            ]);

          print($layout_content);

          die();
        }
      }

      $page_content = render_template('templates/lot.php',
        [
          'categories' => $categories,
          'user' => $user,
          'lots' => $lots,
          'id' => $id,
          'bets' => $bets,
          'errors' => $errors,
          'errors_messages' => $errors_messages,
          'is_done_bet' => $is_done_bet
        ]);

      $layout_content = render_template('templates/layout.php',
        [
          'page_content' => $page_content,
          'user' => $user,
          'categories' => $categories,
          'page_title' => $lots[$id]['title']
        ]);

      print($layout_content);
    }
  }
  else {
    http_response_code(404);
    print("Такой страницы не существует (ошибка 404)");
  }
