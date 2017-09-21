<?php
    session_start();

    require_once "init.php";

    if (isset($_SESSION['user'])) {
        $user = $_SESSION['user'];
    }

    if (isset($_GET['category_id']) && isset($_GET['page'])) {

        $category_name = null;

        foreach ($categories_id as $index => $value) {
            if ($value === intval($_GET['category_id'])) {
                $category_name = $categories[$index];
            }
        }

        $category_id = (int)$_GET['category_id'];
        $page_number = (int)$_GET['page'];
        $limit = 9;
        $offset = ($page_number - 1) * $limit;

        if ($category_name) {

            $lots_count_query =
                'SELECT COUNT(*) AS lots_count
                FROM lots
                WHERE complete_date > NOW()
                AND category_id = ?
            ';

            $lots_count = 0;
            $lots_count_result = select_data($connect, $lots_count_query, [$category_id]);
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
                    AND categories.id = ?
                GROUP BY lots.id
                ORDER BY
                    lots.complete_date DESC
                LIMIT ?
                OFFSET ?
            ';

            $lots = select_data($connect, $lots_query,
                [
                    $category_id,
                    $limit,
                    $offset
                ]
            );

            $pages_count = (int)ceil($lots_count / $limit);

            $page_content = render_template('templates/all-lots.php',
                [
                    'lots' => $lots,
                    'page_number' => $page_number,
                    'pages_count' => $pages_count,
                    'category_name' => $category_name,
                    'current_category_id' => $category_id
                ]
            );

            $layout_content = render_template('templates/layout.php',
                [
                    'page_content' => $page_content,
                    'categories' => $categories,
                    'categories_id' => $categories_id,
                    'current_category_id' => $category_id,
                    'user' => $user,
                    'page_title' => 'Мои ставки'
                ]
            );

            print($layout_content);

        }
        else {
            $page_content = render_template('templates/error.php',
                [
                    'error_message' => 'Такой страницы не существует'
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
    }
    else {
        $page_content = render_template('templates/error.php',
            [
                'error_message' => 'Такой страницы не существует'
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
