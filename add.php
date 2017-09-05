<?php
    require_once "functions.php";
    require_once "data.php";

    function filter_text($value) {
      return trim(htmlspecialchars($value));
    }

    function validate_category($value) {
      return $value !== 'Выберите категорию';
    }

    function validate_number($value) {
      if ((filter_var($value, FILTER_VALIDATE_INT) === false) || ((int)$value < 0)) {
          return false;
      }
      return true;
    }

    function validate_date($value) {

      $now_ts = strtotime('now');
      $date_ts = strtotime($value);

      if ($now_ts > $date_ts) {
        return false;
      }

      $date_arr = explode('.', $value);
      return checkdate($date_arr[1], $date_arr[0], $date_arr[2]);

    }

    $required = ['lot-name', 'category', 'message', 'lot-rate', 'lot-step', 'lot-date'];

    $rules = [
      'category' => 'validate_category',
      'lot-rate' => 'validate_number',
      'lot-step' => 'validate_number',
      'lot-date' => 'validate_date'
    ];

    $errors = [];
    $errors_messages = [];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      foreach ($_POST as $key => $value) {
        if (in_array($key, $required) && $value === '') {
          $errors[] = $key;
          $errors_messages[$key] = 'Обязательное поле';
          //break;
        }

        // ПОЧЕМУ-ТО НЕ СРАБАТЫВАЕТ
        if (in_array($key, $rules)) {
          $result = call_user_func($rules[$key], $value);

          if (!$result) {
            $errors[] = $key;

            switch ($rules[$key]) {
              case 'validate_number':
                $errors_messages[$key] = 'Введите число';
                break;
              case 'validate_date':
                $errors_messages[$key] = 'Формат даты: дд.мм.гггг. Нельзя указывать сегодняшнюю и прошедшие даты';
                break;
            }
          }
        }
      }

      $title = filter_text($_POST['lot-name']);
      $category = filter_text($_POST['category']);
      $message = filter_text($_POST['message']);

      $lot_rate = filter_text($_POST['lot-rate']);
      $lot_step = filter_text($_POST['lot-step']);
      $lot_date = filter_text($_POST['lot-date']);


      // Делаю проверку здесь, потому что в foreach почему-то не срабатывает
      if (!validate_category($category)) {
        $errors[] = 'category';
        $errors_messages['category'] = 'Выберите категорию';
      }

      if (!validate_number($lot_rate)) {
        $errors[] = 'lot-rate';
        $errors_messages['lot-rate'] = 'Введите целое положительное число';
      }

      if (!validate_number($lot_step)) {
        $errors[] = 'lot-step';
        $errors_messages['lot-step'] = 'Введите целое положительное число';
      }

      if (!validate_date($lot_date)) {
        $errors[] = 'lot-date';
        $errors_messages['lot-date'] = 'Формат даты: дд.мм.гггг. Нельзя указывать сегодняшнюю и прошедшие даты';
      }
      // Конец проверки

      if (empty($errors)) {

        $added_lot = [
          [
            'title' => $title,
            'category' => $category,
            'description' => $message,
            'price' => intval($lot_rate),
            'img' => 'img/lot-1.jpg'
          ]
        ];


        $page_content = render_template('templates/lot.php',
          [
            'categories' => $categories,
            'lots' => $added_lot,
            'id' => 0,
            'bets' => $bets]);

        $layout_content = render_template('templates/layout.php',
          [
            'page_content' => $page_content,
            'categories' => $categories,
            'is_auth' => $is_auth,
            'user_name' => $user_name,
            'user_avatar' => $user_avatar,
            'page_title' => $title
          ]);

        print($layout_content);

        die();

      }
      else {

        $page_content = render_template('templates/add.php',
          [
            'categories' => $categories,
            'errors' => $errors,
            'errors_messages' => $errors_messages,

            'lot_name' => $title,
            'selected_category' => $category,
            'message' => $message,
            'lot_rate' => $lot_rate,
            'lot_step' => $lot_step,
            'lot_date' => $lot_date,
          ]);

        $layout_content = render_template('templates/layout.php',
          [
            'page_content' => $page_content,
            'categories' => $categories,
            'is_auth' => $is_auth,
            'user_name' => $user_name,
            'user_avatar' => $user_avatar,
            'page_title' => $title,
          ]);

        print($layout_content);

        die();
      }

      /*
      if (isset($_FILES['photo2'])) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);

        $file_name = $_FILES['photo2']['tmp_name'];
        $file_size = $_FILES['photo2']['size'];
        $file_path = __DIR__ . '/uploads/';

        $file_type = finfo_file($finfo, $file_name);

        if ($file_type !== 'image/jpeg') {

        }
      }
      */
    }

    $page_content = render_template('templates/add.php',
      [
        'categories' => $categories,
        'errors' => $errors,
        'errors_messages' => $errors_messages
      ]);

    $layout_content = render_template('templates/layout.php',
      [
        'page_content' => $page_content,
        'categories' => $categories,
        'is_auth' => $is_auth,
        'user_name' => $user_name,
        'user_avatar' => $user_avatar,
        'page_title' => 'Добавление лота'
      ]);

    print($layout_content);

