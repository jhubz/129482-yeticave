<?php
  require_once "functions.php";

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

  // работа с массивами
  $categories = ['Доски и лыжи', 'Крепления', 'Ботинки', 'Одежда', 'Инструменты', 'Разное'];

  $lots = [
    [
      'title' => '2014 Rossignol District Snowboard',
      'category' => $categories[0],
      'price' => 10999,
      'img' => 'img/lot-1.jpg'
    ],
    [
      'title' => 'DC Ply Mens 2016/2017 Snowboard',
      'category' => $categories[0],
      'price' => 159999,
      'img' => 'img/lot-2.jpg'
    ],
    [
      'title' => 'Крепления Union Contact Pro 2015 года размер L/XL',
      'category' => $categories[1],
      'price' => 8000,
      'img' => 'img/lot-3.jpg'
    ],
    [
      'title' => 'Ботинки для сноуборда DC Mutiny Charocal',
      'category' => $categories[2],
      'price' => 10999,
      'img' => 'img/lot-4.jpg'
    ],
    [
      'title' => 'Куртка для сноуборда DC Mutiny Charocal',
      'category' => $categories[3],
      'price' => 7500,
      'img' => 'img/lot-5.jpg'
    ],
    [
      'title' => 'Маска Oakley Canopy',
      'category' => $categories[5],
      'price' => 5400,
      'img' => 'img/lot-6.jpg'
    ]
  ];

  $page_content = render_template('templates/index.php', ['categories' => $categories, 'lots' => $lots, 'lot_time_remaining' => $lot_time_remaining]);

  $layout_content = render_template('templates/layout.php', ['page_content' => $page_content, 'is_auth' => $is_auth, 'user_name' => $user_name, 'user_avatar' => $user_avatar, 'page_title' => 'Главная']);

  print($layout_content);