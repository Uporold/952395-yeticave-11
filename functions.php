<?php

function priceFormatting ($price) {
    $ceilPrice = ceil($price);

    if ($ceilPrice < 1000) {
        return $ceilPrice;
    } else if ($ceilPrice >= 1000) {
        $formattedPrice = number_format($ceilPrice, 0, $dec_point = "", $thousands_sep = " ");
    }
    return $formattedPrice . " ₽";
};

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
};

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
};


?>
