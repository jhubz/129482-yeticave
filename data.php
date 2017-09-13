<?php

  $is_auth = (bool) rand(0, 1);

  $user_name = 'Константин';
  $user_avatar = 'img/user.jpg';

  $categories = ['Доски и лыжи', 'Крепления', 'Ботинки', 'Одежда', 'Инструменты', 'Разное'];
  $categories_classes = ['boards', 'attachment', 'boots', 'clothing', 'tools', 'other'];

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
      'price' => 15999,
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
  
  // ставки пользователей, которыми надо заполнить таблицу
  $bets = [
    ['name' => 'Иван', 'price' => 11500, 'ts' => strtotime('-' . rand(1, 50) .' minute')],
    ['name' => 'Константин', 'price' => 11000, 'ts' => strtotime('-' . rand(1, 18) .' hour')],
    ['name' => 'Евгений', 'price' => 10500, 'ts' => strtotime('-' . rand(25, 50) .' hour')],
    ['name' => 'Семён', 'price' => 10000, 'ts' => strtotime('last week')]
  ];
