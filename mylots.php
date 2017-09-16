<?php
  session_start();

  require_once "init.php";
  require_once "data.php";

  if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
  }

  if (isset($_COOKIE['bets_data'])) {
    $bets_data = json_decode($_COOKIE['bets_data'], true);
  }
  else {
    $bets_data = [];
  }

  $page_content = render_template('templates/mylots.php',
    [
      'categories' => $categories,
      'lots' => $lots,
      'bets_data' => $bets_data
    ]);

  $layout_content = render_template('templates/layout.php',
    [
      'page_content' => $page_content,
      'categories' => $categories,
      'user' => $user,
      'page_title' => 'Мои ставки'
    ]);

  print($layout_content);
