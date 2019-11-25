<?php

/**
 * Получает значение цены и возвращает отформатированное значение
 * с делением на разряды и добавлением знака рубля.
 * @param int $price Цена для форматирования
 * @return int Отформатированное значение цены
 */
function priceFormatting ($price) {
    $ceilPrice = ceil($price);

    if ($ceilPrice < 1000) {
        return $ceilPrice . " ₽";
    } else if ($ceilPrice >= 1000) {
        $formattedPrice = number_format($ceilPrice, 0, $dec_point = "", $thousands_sep = " ");
    }
    return $formattedPrice . " ₽";
}

/**
 * Подключает шаблон, передает туда данные и возвращает итоговый HTML контент
 * @param string $name Путь к файлу шаблона относительно папки templates
 * @param array $data Ассоциативный массив с данными для шаблона
 * @return string Итоговый HTML
 */
function include_template($name, $data) {
    $name = 'templates/' . $name;
    $result = '';

    if (!file_exists($name)) {
        return $result;
    }

    ob_start();
    extract($data);
    require $name;

    $result = ob_get_clean();

    return $result;
}

/**
 * Получает строку, преобразует специальные символы в HTML сущности
 * @param string $str Строка для преобразования
 * @return string Итоговый преобразованный текст
 */
function esc($str) {
	$text = htmlspecialchars($str);
	return $text;
};

/**
 * Получает дату в формате ГГГГ-ММ-ДД, возвращает массив, где первый элемент — целое количество часов до даты, а второй — остаток в минутах;
 * @param date $date Получаемая дата
 * @return array $expInfo Итоговый массив
 */
function timeExp(string $date) {
    date_default_timezone_set("Europe/Moscow");
    setlocale(LC_ALL, 'ru_RU');

    $expDate = strtotime($date);
    $secs_to_expire = $expDate - time();
    $hours_to_expire = str_pad(floor($secs_to_expire / 3600), 2, "0", STR_PAD_LEFT);
    $minutes_to_expire = str_pad(floor(($secs_to_expire % 3600) / 60), 2, "0", STR_PAD_LEFT);
    $expInfo = ['часы' => $hours_to_expire, 'минуты' => $minutes_to_expire];

    return $expInfo;
}

/**
 * Создает подготовленное выражение на основе готового SQL запроса и переданных данных
 *
 * @param $link mysqli Ресурс соединения
 * @param $sql string SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 *
 * @return mysqli_stmt Подготовленное выражение
 */
function db_get_prepare_stmt($link, $sql, $data = []) {
    $stmt = mysqli_prepare($link, $sql);

    if ($stmt === false) {
        $errorMsg = 'Не удалось инициализировать подготовленное выражение: ' . mysqli_error($link);
        die($errorMsg);
    }

    if ($data) {
        $types = '';
        $stmt_data = [];

        foreach ($data as $value) {
            $type = 's';

            if (is_int($value)) {
                $type = 'i';
            }
            else if (is_string($value)) {
                $type = 's';
            }
            else if (is_double($value)) {
                $type = 'd';
            }

            if ($type) {
                $types .= $type;
                $stmt_data[] = $value;
            }
        }

        $values = array_merge([$stmt, $types], $stmt_data);

        $func = 'mysqli_stmt_bind_param';
        $func(...$values);

        if (mysqli_errno($link) > 0) {
            $errorMsg = 'Не удалось связать подготовленное выражение с параметрами: ' . mysqli_error($link);
            die($errorMsg);
        }
    }

    return $stmt;
}

/**
 * Получает значение для сравнения и массив, ищет данное значение в массиве
 * @param int $id Получаемое значение
 * @param array $allowed_list Массив-список для сравнения
 * @return string Возвращает строку с предупреждением при отсутствии совпадений
 * @return null Найдено соответствие
 */
function validateCategory($id, $allowed_list) {
    if (!in_array($id, $allowed_list)) {
        return "Указана несуществующая категория";
    }

    return null;
}
/**
 * Получает значение для сравнения и его допустимые минимальное и максимальное значения
 * @param int $value Получаемое значение
 * @param int $min Минимальное допустимое значение
 * @param int $max Максимальное допустимое значение
 * @return string Возвращает строку с предупреждением при несоотсветсвии $min или $max
 * @return null Несоответствие не найдено
 */
function validateLength($value, $min, $max) {
    if ($value) {
        $len = strlen($value);
        if ($len < $min or $len > $max) {
            return "Значение должно быть от $min до $max символов";
        }
    }

    return null;
}

/**
 * Получает значение для сравнения и сравнивает его с нулём
 * @param int $value Получаемое значение
 * @return string Возвращает строку с предупреждением при $value < 0
 * @return null Возвращает при $value > 0
 */
function validateNumber($value) {
    if ($value > 0) {
        if(!filter_var($value, FILTER_VALIDATE_INT)){
            return "Значение должно быть целым числом!";

        }
        return null;
    }
    else {
        return "Значение должно быть больше нуля!";
    }
}
function is_date_valid(string $date) : bool {
    $format_to_check = 'Y-m-d';
    $dateTimeObj = date_create_from_format($format_to_check, $date);

    return $dateTimeObj !== false && array_sum(date_get_last_errors()) === 0;
}

function getPostVal($name) {
    return filter_input(INPUT_POST, $name);
}

function dateCheck($date) {
    date_default_timezone_set("Europe/Moscow");
    setlocale(LC_ALL, 'ru_RU');
 if (is_date_valid($date) == 1) {
    $expDate = strtotime($date);
    $secs_to_expire = $expDate - time();
    if ($secs_to_expire >= 84000) {
        return null;
    }
    else {
        return "Дата окончания торгов не может быть менее суток!";
    }
 }
 else {
     return "Неверный формат даты";
 }
}
/**
 * Определяет сколько прошло времени с указанной даты
 *
 * @param string $time дата
 * @return string прошедшее время с указанной даты в формате в соответсвтии с условием
 */
function timeAgo($time)
{
    $dif = time() - strtotime($time);
    $days = floor($dif / 86400);
    $hours = floor(($dif % 86400) / 3600);
    $minutes = floor(($dif % 86400 % 3600) / 60);
    if ($days == 0 && $hours == 0 && $minutes < 1) {
        return "Только что";
    } elseif ($days == 0 && $hours == 0 && $minutes < 60) {
        return "$minutes мин. назад";
    } elseif ($days == 0 && $hours < 24) {
        return "$hours час. назад";
    } else {
        return date('d.m.y в H:i', strtotime($time));
    }
}

 function pagination($sql, $page_items, $cur_page) {
    $offset = ($cur_page - 1) * $page_items;
    $sql = $sql . ' LIMIT ' . $page_items . ' OFFSET ' . $offset;
    return $sql;
}
