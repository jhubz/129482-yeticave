<?php
  function render_template($path, $data) {
    if (!file_exists($path)) {
      return '';
    }

    extract($data);

    ob_start();
    require $path;

    return ob_get_clean();
  }
  
  // функция, преобразующая временную метку в "человеческий" вид
  function time_format($ts) {
    $now = strtotime('now');
    $time_diff = $now - $ts;
    $ts_day = 60 * 60 * 24;
    $ts_hour = 60 * 60;

    if ($time_diff > $ts_day) {
      return date("d.m.y в H:i", $ts);
    }

    if ($time_diff >= $ts_hour) {
      return gmdate("G часов назад", $time_diff);
    }

    return ltrim(gmdate("i минут назад", $time_diff), 0);
  }
