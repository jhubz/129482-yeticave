<?php
    session_start();

    require_once "vendor/autoload.php";
    require_once "init.php";

    $required = ['email', 'password'];

    $rules = [
        'email' => 'validate_email',
    ];

    $errors = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        foreach ($_POST as $key => $value) {
            if (in_array($key, $required) && $value === '') {
                $errors[] = $key;
            }

            if (in_array($key, array_keys($rules))) {
                $result = call_user_func($rules[$key], $value);

                if (!$result) {
                    $errors[] = $key;
                }
            }
        }

        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if (!count($errors)) {

            $select_user =
                'SELECT *
                FROM users
                WHERE email = ?
            ';

            $users = select_data($connect, $select_user, [$email]);

            if ($users) {
                foreach ($users as $value) {
                    $user = $value;
                }

                if (password_verify($password, $user['password'])) {
                    $_SESSION['user'] = $user;
                    header("Location: /index.php");
                } else {
                    $errors[] = 'password';
                    $invalid_password_message = "Вы ввели неверный пароль";

                    $page_content = render_template('templates/login.php',
                        [
                            'errors' => $errors,
                            'invalid_password_message' => $invalid_password_message,
                            'email' => $email
                        ]
                    );

                    $layout_content = render_template('templates/layout.php',
                        [
                            'page_content' => $page_content,
                            'categories' => $categories,
                            'categories_id' => $categories_id,
                            'page_title' => 'Вход'
                        ]
                    );

                    print($layout_content);

                    die();
                }
            } else {
                $errors[] = 'email';
                $invalid_email_message = "Такого пользователя нет";

                $page_content = render_template('templates/login.php',
                    [
                        'errors' => $errors,
                        'invalid_email_message' => $invalid_email_message,
                        'email' => $email
                    ]
                );

                $layout_content = render_template('templates/layout.php',
                    [
                        'page_content' => $page_content,
                        'categories' => $categories,
                        'categories_id' => $categories_id,
                        'page_title' => 'Вход'
                    ]
                );

                print($layout_content);

                die();
            }
        } else {
            $page_content = render_template('templates/login.php',
                [
                    'errors' => $errors,
                    'email' => $email
                ]
            );

            $layout_content = render_template('templates/layout.php',
                [
                    'page_content' => $page_content,
                    'categories' => $categories,
                    'categories_id' => $categories_id,
                    'page_title' => 'Вход'
                ]
            );

            print($layout_content);

            die();
        }
    }

    $signup_message = $_SESSION['signup_message'] ?? '';

    $page_content = render_template('templates/login.php',
        [
            'errors' => $errors,
            'message' => $signup_message,
            'email' => ''
        ]
    );

    $layout_content = render_template('templates/layout.php',
        [
            'page_content' => $page_content,
            'categories' => $categories,
            'categories_id' => $categories_id,
            'page_title' => 'Вход'
        ]
    );

    print($layout_content);

    if (isset($_SESSION['signup_message'])) {
        unset($_SESSION['signup_message']);
    }
