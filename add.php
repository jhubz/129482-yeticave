<?php
    session_start();

    require_once "vendor/autoload.php";
    require_once "init.php";

    if (isset($_SESSION['user'])) {
        $user = $_SESSION['user'];
    } else {
        http_response_code(403);

        $page_content = render_template('templates/error.php', ['error_message' => 'Неавторизованный пользователь (ошибка 403)']);

        $layout_content = render_template('templates/layout.php',
            [
                'page_content' => $page_content,
                'categories' => $categories,
                'categories_id' => $categories_id,
                'user' => $user,
                'page_title' => 'Добавление лота'
            ]
        );

        print($layout_content);

        die();
    }

    /**
     * Проверка категории
     *
     * @param string $value Проверяемая категория
     *
     * @return bool
     */
    function validate_category($value) {
        return $value !== 'Выберите категорию';
    }

    /**
     * Проверка даты на формат дд.мм.гггг
     *
     * @param string $value Проверяемая дата
     *
     * @return bool
     */
    function validate_date($value) {

        $now_ts = strtotime('now');
        $date_ts = strtotime($value);

        if ($now_ts > $date_ts) {
            return false;
        }

        if (strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox')) {
            $date_arr = explode('.', $value);
            return checkdate($date_arr[1], $date_arr[0], $date_arr[2]);
        } else {
            $date_arr = explode('-', $value);
            return checkdate($date_arr[1], $date_arr[2], $date_arr[0]);
        }



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

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        foreach ($_POST as $key => $value) {
            if (in_array($key, $required) && $value === '') {
                $errors[] = $key;
                $errors_messages[$key] = 'Обязательное поле';
            }

            if (in_array($key, array_keys($rules))) {
                $result = call_user_func($rules[$key], $value);

                if (!$result) {
                    $errors[] = $key;

                    switch ($rules[$key]) {
                        case 'validate_category':
                            $errors_messages[$key] = 'Выберите категорию';
                            break;
                        case 'validate_number':
                            $errors_messages[$key] = 'Введите целое положительное число';
                            break;
                        case 'validate_date':
                            $errors_messages[$key] = 'Формат даты: дд.мм.гггг. Нельзя указывать сегодняшнюю и прошедшие даты';
                            break;
                    }
                }
            }
        }

        $file_url = null;
        if (isset($_FILES['photo2'])) {
            $file = $_FILES['photo2'];

            if (!empty($file['name'])) {
                if (validate_image_file($file)) {
                    $new_file_url = resize_and_upload_image($file, 730, 548, '/img/');
                    $_SESSION['photo-add-path'] = $new_file_url;
                    $file_url = $new_file_url;
                } else {
                    $errors[] = 'photo2';
                    $errors_messages['photo2'] = 'Загрузите фото';
                }
            } elseif (isset($_SESSION['photo-add-path'])) {
                $file_url = $_SESSION['photo-add-path'];
            } else {
                $errors[] = 'photo2';
                $errors_messages['photo2'] = 'Загрузите фото';
            }
        }

        $title = $_POST['lot-name'] ?? '';
        $category = $_POST['category'] ?? '';
        $message = $_POST['message'] ?? '';

        $lot_rate = $_POST['lot-rate'] ?? '';
        $lot_step = $_POST['lot-step'] ?? '';
        $lot_date = $_POST['lot-date'] ?? '';

        if (empty($errors)) {

            $category_query =
                'SELECT id
                FROM categories
                WHERE name = ?
            ';

            $selected_categories = select_data($connect, $category_query, [$category]);

            if ($selected_categories) {

                $selected_category_id = null;
                foreach ($selected_categories as $value) {
                    $selected_category_id = $value['id'];
                }

                $inserted_lot_id = insert_data($connect, 'lots',
                    [
                        'category_id' => $selected_category_id,
                        'author_id' => $user['id'],
                        'title' => $title,
                        'description' => $message,
                        'creation_date' => date('Y-m-d H:i:s'),
                        'complete_date' => date('Y-m-d H:i:s', strtotime($lot_date)),
                        'img_path' => $file_url,
                        'start_price' => $lot_rate,
                        'bet_step' => $lot_step
                    ]
                );

                if ($inserted_lot_id) {
                    if (isset($_SESSION['photo-add-path'])) {
                        unset($_SESSION['photo-add-path']);
                    }

                    header("Location: /lot.php?id=$inserted_lot_id");
                }
            }
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
                    'file_url' => $file_url,
                    'lot_rate' => $lot_rate,
                    'lot_step' => $lot_step,
                    'lot_date' => $lot_date,
                ]
            );

            $layout_content = render_template('templates/layout.php',
                [
                    'page_content' => $page_content,
                    'categories' => $categories,
                    'categories_id' => $categories_id,
                    'user' => $user,
                    'page_title' => $title,
                ]
            );

            print($layout_content);

            die();
        }
    }

    if (isset($_SESSION['photo-add-path'])) {
        unset($_SESSION['photo-add-path']);
    }

    $page_content = render_template('templates/add.php',
        [
            'categories' => $categories,
            'errors' => $errors,
            'errors_messages' => $errors_messages,

            'lot_name' => '',
            'message' => '',
            'file_url' => null,
            'lot_rate' => null,
            'lot_step' => null,
            'lot_date' => null
        ]
    );

    $layout_content = render_template('templates/layout.php',
        [
            'page_content' => $page_content,
            'categories' => $categories,
            'categories_id' => $categories_id,
            'user' => $user,
            'page_title' => 'Добавление лота'
        ]
    );

    print($layout_content);
