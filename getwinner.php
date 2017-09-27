<?php

    require_once "vendor/autoload.php";
    require_once "init.php";

    $lots_query =
        'SELECT
            id,
            title
        FROM lots
        WHERE
            complete_date <= NOW()
            AND winner_id is NULL
    ';

    $lots = select_data($connect, $lots_query);

    if ($lots) {

        $bets_query =
            'SELECT
                bets.user_id as user_id,
                users.name as user_name,
                users.email as user_email,
                bets.lot_id as lot_id,
                lots.title as lot_name,
                bets.price as price
            FROM bets
            JOIN users
                ON bets.user_id = users.id
            JOIN lots
                ON bets.lot_id = lots.id
            WHERE lot_id = ?
            ORDER BY price DESC
            LIMIT 1
        ';

        $update_winner_id_query =
            'UPDATE lots
            SET winner_id = ?
            WHERE id = ?;
        ';

        foreach ($lots as $index => $lots_item) {
            $winner_bets = select_data($connect, $bets_query, [$lots_item['id']]);

            if ($winner_bets) {

                $winner_bet = null;
                foreach ($winner_bets as $winner_bets_item) {
                    $winner_bet = $winner_bets_item;
                }

                $is_query_exec = exec_query($connect, $update_winner_id_query,
                    [
                        $winner_bet['user_id'],
                        $winner_bet['lot_id']
                    ]
                );

                if ($is_query_exec) {

                    $email_content = render_template('templates/email.php',
                        [
                            'user_name' => $winner_bet['user_name'],
                            'lot_id' => $winner_bet['lot_id'],
                            'lot_name' => $winner_bet['lot_name']
                        ]
                    );

                    $transport = (new Swift_SmtpTransport('smtp.mail.ru', 465, 'ssl'))
                        ->setUsername('doingsdone@mail.ru')
                        ->setPassword('rds7BgcL')
                        ;

                    $message = (new Swift_Message())
                        ->setSubject('Ваша ставка победила')
                        ->setFrom('doingsdone@mail.ru')
                        ->setTo($winner_bet['user_email'], $winner_bet['user_name'])
                        ->setBody($email_content , 'text/html')
                    ;

                    $mailer = new Swift_Mailer($transport);
                    $mailer->send($message);

                }
            }

        }

    }
