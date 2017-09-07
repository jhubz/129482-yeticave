<?php
  // функция генерации шаблона
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
  
  // функция фильтрации строки текста
  function filter_text($value) {
    return trim(htmlspecialchars($value));
  }
  
  // создание отпечатка пользователя
  function user_fingerprint($include_ip = true, $include_city = true) {
    $ip_addr = $_SERVER['REMOTE_ADDR'];
    $useragent = $_SERVER['HTTP_USER_AGENT'];
    $geo_data = file_get_contents('https://freegeoip.net/json/' . $ip_addr);
    $json = json_decode($geo_data, true);
    $parts = [$useragent, $json['country_code']];
    
    if ($include_ip) {
      $parts[] = $ip_addr;
    }
    
    if ($include_city) {
      $parts[] = $json['city'];
    }
    
    $str = implode('', $parts);
    $fingerprint = md5($str);
    
    return $fingerprint;
  }
  
  // поиск email пользователя
  function search_user_by_email($email, $users) {
    $result = null;
    
    foreach ($users as $user) {
      if ($user['email'] == $email) {
        $result = $user;
        break;
      }
    }
    return $result;
  }

