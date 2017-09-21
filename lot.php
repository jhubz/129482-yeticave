<?php
    session_start();

    require_once "init.php";

    if (isset($_SESSION['user'])) {
        $user = $_SESSION['user'];
    }

    if (isset($_GET['id'])) {

        $lot_query =
            'SELECT
                lots.id as lot_id,
                users.id as author_id,
                lots.title as title,
                lots.img_path as img,
                categories.name as category,
                lots.description as description,
                IFNULL(MAX(bets.price), lots.start_price) as lot_price,
                lots.bet_step as bet_step,
                lots.complete_date as complete_date
            FROM lots
            JOIN users
                ON users.id = lots.author_id
            JOIN categories
                ON categories.id = lots.category_id
            LEFT JOIN bets
                ON bets.lot_id = lots.id
            WHERE lots.id = ?
            GROUP BY lots.id
        ';

        $bets_query =
            'SELECT
                users.name as user_name,
                users.id as user_id,
                bets.price as bet_price,
                bets.placement_date as bet_date
            FROM bets
            JOIN users
                ON users.id = bets.user_id
            WHERE bets.lot_id = ?
            ORDER BY bets.placement_date DESC
        ';

        $lot_id = (int)$_GET['id'];

        $lots = select_data($connect, $lot_query, [$lot_id]);
        foreach ($lots as $value) {
            $lot = $value;
        }

        $bets_count = 0;
        $is_done_bet = false;

        $bets = select_data($connect, $bets_query, [$lot_id]);
        if ($bets) {
            $bets_count = count($bets);

            foreach ($bets as $bet) {
                if ($bet['user_id'] === $user['id']) {
                    $is_done_bet = true;
                }
            }
        }

        if (!isset($lot)) {
            http_response_code(404);

            $page_content = render_template('templates/error.php', ['error_message' => 'Такой страницы не существует (ошибка 404)']);

            $layout_content = render_template('templates/layout.php',
                [
                    'page_content' => $page_content,
                    'categories' => $categories,
                    'categories_id' => $categories_id,
                    'user' => $user,
                    'page_title' => 'Лот не найден'
                ]
            );

            print($layout_content);

            die();
            
        } else {

            $errors = [];
            $errors_messages = [];

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                $required = ['cost'];

                $rules = [
                    'cost' => 'validate_number'
                ];

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
                                case 'validate_number':
                                    $errors_messages[$key] = 'Введите целое положительное число';
                                    break;
                            }
                        }
                    }
                }

                if (!(in_array('cost', $errors)) && $value < ($lot['lot_price'] + $lot['bet_step'])) {
                    $errors[] = $key;
                    $errors_messages[$key] = 'Слишком низкая ставка';
                }

                $cost = (int)$_POST['cost'] ?? '';

                if (empty($errors)) {

                    $inserted_bet_id = insert_data($connect, 'bets',
                        [
                            'user_id' => $user['id'],
                            'lot_id' => $lot['lot_id'],
                            'placement_date' => date('Y-m-d H:i:s'),
                            'price' => $cost
                        ]
                    );

                    if ($inserted_bet_id) {
                        header("Location: /mylots.php");
                    }

                } else {
                    $page_content = render_template('templates/lot.php',
                        [
                            'categories' => $categories,
                            'user' => $user,
                            'lot' => $lot,
                            'bets' => $bets,
                            'bets_count' => $bets_count,
                            'errors' => $errors,
                            'errors_messages' => $errors_messages,
                            'cost' => $cost
                        ]
                    );

                    $layout_content = render_template('templates/layout.php',
                        [
                            'page_content' => $page_content,
                            'user' => $user,
                            'categories' => $categories,
                            'categories_id' => $categories_id,
                            'page_title' => $lot['title']
                        ]
                    );

                    print($layout_content);

                    die();
                }
            }

            $page_content = render_template('templates/lot.php',
                [
                    'categories' => $categories,
                    'user' => $user,
                    'lot' => $lot,
                    'bets' => $bets,
                    'bets_count' => $bets_count,
                    'errors' => $errors,
                    'errors_messages' => $errors_messages,
                    'is_done_bet' => $is_done_bet
                ]
            );

            $layout_content = render_template('templates/layout.php',
                [
                    'page_content' => $page_content,
                    'user' => $user,
                    'categories' => $categories,
                    'categories_id' => $categories_id,
                    'page_title' => $lot['title']
                ]
            );

            print($layout_content);
        }
    } else {
        http_response_code(404);

        $page_content = render_template('templates/error.php', ['error_message' => 'Такой страницы не существует (ошибка 404)']);

        $layout_content = render_template('templates/layout.php',
            [
                'page_content' => $page_content,
                'categories' => $categories,
                'categories_id' => $categories_id,
                'user' => $user,
                'page_title' => 'Лот не найден'
            ]
        );

        print($layout_content);
    }
