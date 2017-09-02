<?php
  require_once "functions.php";
  require_once "data_lots.php";

  $is_auth = (bool) rand(0, 1);

  $user_name = 'Константин';
  $user_avatar = 'img/user.jpg';

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


  $page_content = render_template('templates/index.php', ['categories' => $categories, 'lots' => $lots, 'lot_time_remaining' => $lot_time_remaining]);

  $layout_content = render_template('templates/layout.php', ['page_content' => $page_content, 'is_auth' => $is_auth, 'user_name' => $user_name, 'user_avatar' => $user_avatar, 'page_title' => 'Главная']);

  print($layout_content);
