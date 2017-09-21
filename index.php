<?php
    session_start();

    require_once "init.php";

    if (isset($_SESSION['user'])) {
        $user = $_SESSION['user'];
    }

    // устанавливаем часовой пояс в Московское время
    date_default_timezone_set('Europe/Moscow');

    $categories_classes = ['boards', 'attachment', 'boots', 'clothing', 'tools', 'other'];

    $page_number = 1;

    if (isset($_GET['page'])) {
        $page_number = (int)$_GET['page'];
    }

    $limit = 3;
    $offset = ($page_number - 1) * $limit;

    $lots_count_query =
        'SELECT COUNT(*) AS lots_count
        FROM lots
        WHERE complete_date > NOW()
    ';

    $lots_count = 0;
    $lots_count_result = select_data($connect, $lots_count_query);
    if ($lots_count_result) {
        $lots_count = $lots_count_result[0]['lots_count'];
    }

    $lots_query =
        'SELECT
            lots.id as lot_id,
            lots.title as title,
            lots.start_price as start_price,
            lots.img_path as img,
            IFNULL(MAX(bets.price), lots.start_price) as lot_price,
            COUNT(bets.lot_id) as bets_count,
            categories.name as category,
            lots.complete_date as complete_date
        FROM lots
        JOIN categories
            ON categories.id = lots.category_id
        LEFT JOIN bets
            ON bets.lot_id = lots.id
        WHERE lots.complete_date > NOW()
        GROUP BY lots.id
        ORDER BY lots.complete_date DESC
        LIMIT ?
        OFFSET ?
    ';

    $lots = select_data($connect, $lots_query, [$limit, $offset]);

    if ($lots) {
        $pages_count = (int)ceil($lots_count / $limit);

        $page_content = render_template('templates/index.php',
            [
                'categories' => $categories,
                'categories_id' => $categories_id,
                'categories_classes' => $categories_classes,
                'lots' => $lots,
                'page_number' => $page_number,
                'pages_count' => $pages_count
            ]
        );

        $layout_content = render_template('templates/layout.php',
            [
                'page_content' => $page_content,
                'categories' => $categories,
                'categories_id' => $categories_id,
                'user' => $user,
                'page_title' => 'Главная',
                'is_index_page' => true
            ]
        );

        print($layout_content);
    }
