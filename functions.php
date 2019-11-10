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

