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
        return $ceilPrice;
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

function validateCategory($id, $allowed_list) {
    if (!in_array($id, $allowed_list)) {
        return "Указана несуществующая категория";
    }

    return null;
}

function validateLength($value, $min, $max) {
    if ($value) {
        $len = strlen($value);
        if ($len < $min or $len > $max) {
            return "Значение должно быть от $min до $max символов";
        }
    }

    return null;
}

function validateNumber($value) {
    if ($value > 0) {
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
