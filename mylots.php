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
                'page_title' => 'Мои ставки'
            ]
        );

        print($layout_content);

        die();
    }

    $bets_query =
        'SELECT
            lots.img_path as img,
            lots.id as lot_id,
            lots.title as title,
            categories.name as category,
            lots.complete_date as lot_complete_date,
            bets.price as bet_price,
            bets.placement_date as bet_date
        FROM bets
        JOIN lots
            ON lots.id = bets.lot_id
        JOIN categories
            ON categories.id = lots.category_id
        WHERE bets.user_id = ?
        ORDER BY bets.placement_date DESC
    ';

    $bets = select_data($connect, $bets_query, [$user['id']]);

    if ($bets) {
        $page_content = render_template('templates/mylots.php',
            [
                'bets' => $bets
            ]
        );

        $layout_content = render_template('templates/layout.php',
            [
                'page_content' => $page_content,
                'categories' => $categories,
                'categories_id' => $categories_id,
                'user' => $user,
                'page_title' => 'Мои ставки'
            ]
        );

        print($layout_content);
    }
