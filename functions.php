<?php
function priceFormatting ($price) {
    $ceilPrice = ceil($price);

    if ($ceilPrice < 1000) {
    return $ceilPrice;
    }

    else if ($ceilPrice >= 1000) {
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
}
?>
