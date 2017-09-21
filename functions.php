<?php

    /**
     * Генерирует шаблон страницы
     *
     * @param string $path Путь до шаблона
     * @param array $data Данные для вставки передачи в шаблон
     *
     * @return string Содержимое текущего буфера
     */
    function render_template($path, $data)
    {
        if (!file_exists($path)) {
            return '';
        }

        extract($data);

        ob_start();
        require $path;

        return ob_get_clean();
    }

    /**
     * Преобразует временную метку в 'человеческий' вид
     *
     * @param integer $ts Временная метка
     *
     * @return string Время в 'человеческом' виде
     */
    function time_format($ts)
    {
        $now = strtotime('now');
        $time_diff = $now - $ts;
        $ts_day = 60 * 60 * 24;
        $ts_hour = 60 * 60;
        $ts_minute = 60;

        if ($time_diff > $ts_day) {
            return date("d.m.y в H:i", $ts);
        }

        if ($time_diff >= $ts_hour) {
            return gmdate("G часов назад", $time_diff);
        }

        if ($time_diff >= $ts_minute) {
            return ltrim(gmdate("i минут назад", $time_diff), 0);
        }

        return "Менее минуты назад";
    }

    /**
     * Вычисление разницы времени между двумя датами
     *
     * @param integer $start Временная метка начала
     * @param integer $end Временная метка окончания
     *
     * @return string Время в формате чч:мм:сс
     */
    function time_different_calc($start, $end)
    {
        $date_diff = $end - $start;
        $hours = floor(($date_diff) / (60 * 60));
        $mins = floor(($date_diff - ($hours * 60 * 60)) / 60);
        $seconds = floor(($date_diff - ($hours * 60 * 60) - ($mins * 60)));

        if ($hours < 10) {
            $hours = '0' . $hours;
        }

        if ($mins < 10) {
            $mins = '0' . $mins;
        }

        if ($seconds < 10) {
            $seconds = '0' . $seconds;
        }

        return $hours . ':' . $mins . ':' . $seconds;
    }

    /**
     * Фильтрации строки текста
     *
     * @param string $value Строка
     *
     * @return string Отфильтрованная строка
     */
    function filter_text($value)
    {
        return trim(htmlspecialchars($value));
    }

    // функция проверки на число
    /**
     * Проверка на число
     *
     * @param string|integer $value Входящее значение
     *
     * @return bool
     */
    function validate_number($value)
    {
        if ((filter_var($value, FILTER_VALIDATE_INT) === false) || ((int)$value < 0)) {
            return false;
        }

        return true;
    }

    /**
     * Валидация email
     *
     * @param string $value Проверяемый email
     *
     * @return bool
     */
    function validate_email($value)
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }

    /**
     * Проверка на файл изображения
     *
     * @param array $file Файл из массива $_FILES
     *
     * @return bool
     */
    function validate_image_file($file)
    {
        $image_types = ['image/png', 'image/jpeg'];
        $file_type = mime_content_type($file['tmp_name']);

        foreach ($image_types as $image_type) {
            if ($file_type === $image_type) {
                return true;
            }
        }

        return false;
    }

    /**
     * Перемещение файла в указанную папку
     *
     * @param array $file Файл из массива $_FILES
     * @param string $path Путь до папки, в которую нужно переместить файл
     *
     * @return string Полный путь до файла на сервере
     */
    function move_uploaded_file_to_dir($file, $path)
    {
        $file_name = $file['name'];
        $file_tmp_name = $file['tmp_name'];
        $file_type = $file['type'];
        $file_path = __DIR__ . $path;

        move_uploaded_file($file_tmp_name, $file_path . $file_name);
        $new_file_url = $path . $file_name;

        return $new_file_url;
    }

    /**
     * Выполнения запроса SELECT
     *
     * @param resourse $connect Ресурс соединения
     * @param string $query SQL запрос в базу данных
     * @param array $data Массив данных для подстановки в подготовленное выражение
     *
     * @return array Строки из базы данных
     */
    function select_data($connect, $query, $data = [])
    {
        $rows = [];

        $prepared_query = db_get_prepare_stmt($connect, $query, $data);

        if ($prepared_query) {
            $is_execute = mysqli_stmt_execute($prepared_query);

            if ($is_execute) {
                $result = mysqli_stmt_get_result($prepared_query);

                if ($result) {
                    $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
                }
            }
        }

        return $rows;
    }

    /**
     * Выполнения запроса INSERT
     *
     * @param resourse $connect Ресурс соединения
     * @param string $table Таблица, в которую будет записан запрос
     * @param array $data Массив данных для подстановки в подготовленное выражение
     *
     * @return integer id встравленной строки
     */
    function insert_data($connect, $table, $data)
    {
        $column_names = '';
        $values = [];
        $values_count = '';

        foreach ($data as $key => $value) {
            $column_names .= "$key, ";
            $values[] = $value;
            $values_count .= "?, ";
        }

        $column_names = substr($column_names, 0, -2);
        $values_count = substr($values_count, 0, -2);

        $query = 'INSERT INTO ' . $table . ' ('. $column_names .')' . ' VALUES (' . $values_count .')';

        $prepared_query = db_get_prepare_stmt($connect, $query, $values);

        if ($prepared_query) {

            $is_execute = mysqli_stmt_execute($prepared_query);

            if ($is_execute) {

                $last_id = mysqli_stmt_insert_id($prepared_query);

                return $last_id;
            }
        }

        return false;
    }

    /**
     * Выполнение любых запросов, кроме SELECT и INSERT
     *
     * @param resourse $connect Ресурс соединения
     * @param string $query SQL запрос в базу данных
     * @param array $data Массив данных для подстановки в подготовленное выражение
     *
     * @return bool
     */
    function exec_query($connect, $query, $data = [])
    {
        $prepared_query = db_get_prepare_stmt($connect, $query, $data);

        if ($prepared_query) {
            $is_execute = mysqli_stmt_execute($prepared_query);

            if ($is_execute) {
                return true;
            }
        }

        return false;
    }
