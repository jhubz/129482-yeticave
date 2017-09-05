<?php
  require_once "functions.php";
  require_once 'data.php';

  if (isset($_GET['id'])) {

    $id = (int)$_GET['id'];

    if (!array_key_exists($id, $lots)) {
      http_response_code(404);
      print("Такой страницы не существует (ошибка 404)");
    }
    else {
      
      $page_content = render_template('templates/lot.php',
        ['categories' => $categories,
          'lots' => $lots,
          'id' => $id,
          'bets' => $bets]);

      $layout_content = render_template('templates/layout.php',
        ['page_content' => $page_content,
        'categories' => $categories,
        'is_auth' => $is_auth,
        'user_name' => $user_name,
        'user_avatar' => $user_avatar,
        'page_title' => $lots[$id]['title']]);

      print($layout_content);

    }
  }
