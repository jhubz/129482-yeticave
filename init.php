<?php
    require_once "mysql_helper.php";
    require_once "functions.php";

    $connect = mysqli_connect("localhost", "root", "", "yeticave");

    if (!$connect) {

        $page_content = render_template('templates/error.php',
            [
                'error_message' => mysqli_connect_error()
            ]
        );

        $layout_content = render_template('templates/layout.php',
            [
                'page_content' => $page_content,
                'categories' => $categories,
                'user' => $user,
                'page_title' => 'Ошибка'
            ]
        );

        print($layout_content);

        die();
    } else {

        $select_categories_result = select_data($connect, 'SELECT id, name FROM categories');
        foreach ($select_categories_result as $value) {
            $categories_id[] = $value['id'];
            $categories[] = $value['name'];
        }

        $user = null;
    }
