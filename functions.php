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
    $ts_minute = 60;

    if ($time_diff > $ts_day) {
      return date("d.m.y в H:i", $ts);
    }

    if ($time_diff >= $ts_hour) {
      return gmdate("G часов назад", $time_diff);
    }

    if ($time_diff >= $ts_minute) {
      return ltrim(gmdate("i минут назад", $time_diff), 0);
    }

    return "Менее минуты назад";
  }

  // функция фильтрации строки текста
  function filter_text($value) {
    return trim(htmlspecialchars($value));
  }

  // функция проверки на число
  function validate_number($value) {
    if ((filter_var($value, FILTER_VALIDATE_INT) === false) || ((int)$value < 0)) {
        return false;
    }
    return true;
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

  // функция выполнения запроса SELECT
  function select_data($connect, $query, $data = []) {
    $rows = [];
    $prepared_query = db_get_prepare_stmt($connect, $query, $data);

    if ($prepared_query) {
      $is_execute = mysqli_stmt_execute($prepared_query);

      if ($is_execute) {
        $result = mysqli_stmt_get_result($prepared_query);

        if ($result) {
          $rows = mysqli_fetch_all($result);
        }
      }
    }

    return $rows;
  }

  // функция выполнения запроса INSERT
  function insert_data($connect, $table, $data) {
    $column_names = '';
    $values = [];
    $values_count = '';

    foreach ($data as $key => $value) {
      $column_names .= "$key, ";
      $values[] = $value;
      $values_count .= "?, ";
    }

    $column_names = substr($column_names, 0, -2);
    $values_count = substr($values_count, 0, -2);

    $query = 'INSERT INTO ' . $table . ' ('. $column_names .')' . ' VALUES (' . $values_count .')';

    $prepared_query = db_get_prepare_stmt($connect, $query, $values);

    if ($prepared_query) {
      $is_execute = mysqli_stmt_execute($prepared_query);

      if ($is_execute) {
        $last_id = mysqli_stmt_insert_id($prepared_query);

        return $last_id;
      }
    }

    return false;
  }

  // функция выполнения любых запросов, кроме SELECT и INSERT
  function exec_query($connect, $query, $data = []) {
    $prepared_query = db_get_prepare_stmt($connect, $query, $data);

    if ($prepared_query) {
      $is_execute = mysqli_stmt_execute($prepared_query);

      if ($is_execute) {
        return true;
      }
    }

    return false;
  }
