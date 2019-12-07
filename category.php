<?php
require_once 'functions.php';
require_once 'init.php';
$container = 0;
$lots = [];
$sort_field = null;
$page_content = null;

$tab = filter_input(INPUT_GET, 'tab');
if (isset($tab)) {
    $sort_field = $tab;
}
$current_page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?? 1;
$page_items = 9;
$items_count = mysqli_fetch_assoc(getLotsCountByCategoryCode($link,
    $sort_field))['count'];
$pages_count = ceil($items_count / $page_items);
$pages = range(1, $pages_count);

$categoryName = mysqli_fetch_all(getCategoryNameByCode($link, $sort_field),
    MYSQLI_ASSOC);

$result = getLotsByCategoryCode($link, $sort_field, $page_items, $current_page);

if ($result) {
    $lots = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $page_content = include_template('_category.php',
        compact('lots', 'categories', 'pages', 'pages_count', 'current_page',
            'categoryName'));
} else {
    $error = "Что-то пошло не так, попробуйте позднее";
    $page_content = include_template('error.php', ['error' => $error]);
    http_response_code(404);
}

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'categories' => $categories,
    'container' => $container,
    'title' => 'YetiCave - '.esc($categoryName[0]['categoryName']),
]);
echo $layout_content;
