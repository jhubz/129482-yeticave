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

        print($page_content);

        die();
    } else {

        mysqli_set_charset($connect, "utf8");

        $select_categories_result = select_data($connect, 'SELECT id, name FROM categories');
        foreach ($select_categories_result as $value) {
            $categories_id[] = $value['id'];
            $categories[] = $value['name'];
        }

        $user = null;
    }
