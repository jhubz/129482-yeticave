<?php
  session_start();
  
  require_once "functions.php";
  require_once "data.php";
  
  if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
  }

  // устанавливаем часовой пояс в Московское время
  date_default_timezone_set('Europe/Moscow');

  // записать в эту переменную оставшееся время в этом формате (ЧЧ:ММ)
  $lot_time_remaining = "00:00";

  // временная метка для полночи следующего дня
  $tomorrow = strtotime('tomorrow midnight');

  // временная метка для настоящего времени
  $now = strtotime('now');
  
  // далее нужно вычислить оставшееся время до начала следующих суток и записать его в переменную $lot_time_remaining
  function time_different_calc($start, $end) {
    $date_diff = $end - $start;
    $hours = floor(($date_diff) / (60 * 60));
    $mins = floor(($date_diff - ($hours * 60 * 60)) / 60);

    if ($hours < 10) {
      $hours = '0' . $hours;
    }

    if ($mins < 10) {
      $mins = '0' . $mins;
    }

    return $hours . ':' . $mins;
  }


  $lot_time_remaining = time_different_calc($now, $tomorrow);


  $page_content = render_template('templates/index.php',
    [
      'categories' => $categories,
      'categories_classes' => $categories_classes,
      'lots' => $lots,
      'lot_time_remaining' => $lot_time_remaining
    ]);

  $layout_content = render_template('templates/layout.php',
    [
      'page_content' => $page_content,
      'categories' => $categories,
      'user' => $user,
      'page_title' => 'Главная',
      'is_index_page' => true
    ]);

  print($layout_content);
