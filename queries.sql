INSERT INTO categories SET name = 'Доски и лыжи';
INSERT INTO categories SET name = 'Крепления';
INSERT INTO categories SET name = 'Ботинки';
INSERT INTO categories SET name = 'Одежда';
INSERT INTO categories SET name = 'Инструменты';
INSERT INTO categories SET name = 'Разное';



INSERT INTO users SET
    name = 'Игнат',
    email = 'ignat.v@gmail.com',
    password = '$2y$10$OqvsKHQwr0Wk6FMZDoHo1uHoXd4UdxJG/5UDtUiie00XaxMHrW8ka',
    registration_date = '2015-10-05 12:45:32',
    avatar_path = 'img/user.jpg',
    contacts = '8-800-456-34-56';

INSERT INTO users SET
    name = 'Леночка',
    email = 'kitty_93@li.ru',
    password = '$2y$10$bWtSjUhwgggtxrnJ7rxmIe63ABubHQs0AS0hgnOo41IEdMHkYoSVa',
    registration_date = '2016-03-20 17:32:27',
    avatar_path = 'img/user.jpg',
    contacts = '8-800-823-35-04';

INSERT INTO users SET
    name = 'Руслан',
    email = 'warrior07@mail.ru',
    password = '$2y$10$2OxpEH7narYpkOT1H5cApezuzh10tZEEQ2axgFOaKW.55LxIJBgWW',
    registration_date = '2017-01-15 08:38:51',
    avatar_path = 'img/user.jpg',
    contacts = '8-800-193-37-76';



INSERT INTO lots SET
    category_id = 1,
    author_id = 2,
    winner_id = 1,
    title = '2014 Rossignol District Snowboard',
    description = 'Отличный сноуборд',
    creation_date = '2017-08-15 08:36:32',
    complete_date = '2017-08-28 08:36:32',
    img_path = 'img/lot-1.jpg',
    start_price = 10999,
    bet_step = 500,
    favorite_count = 0;

INSERT INTO lots SET
    category_id = 1,
    author_id = 1,
    winner_id = NULL,
    title = 'DC Ply Mens 2016/2017 Snowboard',
    description = 'Прекрасный сноуборд',
    creation_date = '2017-09-05 17:16:43',
    complete_date = '2017-09-15 17:16:43',
    img_path = 'img/lot-2.jpg',
    start_price = 15999,
    bet_step = 700,
    favorite_count = 1;

INSERT INTO lots SET
    category_id = 2,
    author_id = 1,
    winner_id = NULL,
    title = 'Крепления Union Contact Pro 2015 года размер L/XL',
    description = 'Замечательные крепления!',
    creation_date = '2017-09-11 13:24:08',
    complete_date = '2017-09-25 13:24:08',
    img_path = 'img/lot-3.jpg',
    start_price = 8000,
    bet_step = 400,
    favorite_count = 3;

INSERT INTO lots SET
    category_id = 3,
    author_id = 3,
    winner_id = NULL,
    title = 'Ботинки для сноуборда DC Mutiny Charocal',
    description = 'Супер удобные ботинки',
    creation_date = '2017-08-29 19:23:18',
    complete_date = '2017-09-30 19:23:18',
    img_path = 'img/lot-4.jpg',
    start_price = 10999,
    bet_step = 500,
    favorite_count = 2;

INSERT INTO lots SET
    category_id = 4,
    author_id = 3,
    winner_id = NULL,
    title = 'Куртка для сноуборда DC Mutiny Charocal',
    description = 'Очень теплая и легкая куртка',
    creation_date = '2017-09-10 15:34:36',
    complete_date = '2017-10-13 15:34:36',
    img_path = 'img/lot-5.jpg',
    start_price = 7500,
    bet_step = 300,
    favorite_count = 0;

INSERT INTO lots SET
    category_id = 6,
    author_id = 2,
    winner_id = 3,
    title = 'Маска Oakley Canopy',
    description = 'Удобная маска, в которой отличо видно трассу',
    creation_date = '2017-06-03 20:26:43',
    complete_date = '2017-06-14 20:26:43',
    img_path = 'img/lot-6.jpg',
    start_price = 5400,
    bet_step = 200,
    favorite_count = 2;



INSERT INTO bets SET
    user_id = 3,
    lot_id = 1,
    placement_date = '2017-08-17 10:43:17',
    price = 11499;

INSERT INTO bets SET
    user_id = 1,
    lot_id = 1,
    placement_date = '2017-08-20 15:26:43',
    price = 11999;

INSERT INTO bets SET
    user_id = 2,
    lot_id = 2,
    placement_date = '2017-09-06 20:15:34',
    price = 16699;

INSERT INTO bets SET
    user_id = 3,
    lot_id = 2,
    placement_date = '2017-09-07 22:45:26',
    price = 17399;

INSERT INTO bets SET
    user_id = 3,
    lot_id = 3,
    placement_date = '2017-09-11 13:48:08',
    price = 8400;

INSERT INTO bets SET
    user_id = 2,
    lot_id = 3,
    placement_date = '2017-09-11 14:00:45',
    price = 9200;

INSERT INTO bets SET
    user_id = 1,
    lot_id = 6,
    placement_date = '2017-06-10 15:45:02',
    price = 6000;




SELECT name FROM categories;


SELECT
    lots.title,
    lots.start_price,
    lots.img_path,
    IFNULL(MAX(bets.price), lots.start_price) as bet_price,
    COUNT(bets.lot_id) as bets_count,
    categories.name
FROM lots
JOIN categories
    ON categories.id = lots.category_id
LEFT JOIN bets
    ON bets.lot_id = lots.id
WHERE lots.complete_date > NOW()
GROUP BY lots.id
ORDER BY lots.complete_date DESC;


SELECT * FROM lots
WHERE title = 'Ботинки для сноуборда DC Mutiny Charocal'
OR description LIKE 'ботинки';


UPDATE lots SET title = 'Измененное название'
WHERE id = 6;


SELECT * FROM bets
WHERE lot_id = 2
ORDER BY placement_date DESC;
