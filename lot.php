<?php
  session_start();
  
  require_once "functions.php";
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
      $page_content = render_template('templates/lot.php',
        [
          'categories' => $categories,
          'user' => $user,
          'lots' => $lots,
          'id' => $id,
          'bets' => $bets
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
