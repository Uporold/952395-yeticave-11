<?php
require_once 'init.php';
$container = 0;

$lots = [];
$error = null;
$page_content = null;
$pages = null;
$pages_count = null;
$current_page = null;
$items_count = null;
$page_items = null;
$search = filter_input(INPUT_GET, 'q', FILTER_SANITIZE_SPECIAL_CHARS) ?? '';

if (!empty($search)) {
    $current_page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?? 1;
    $page_items = 9;
    $items_count = mysqli_fetch_assoc(getLotsCountBySearch($link,
        $search))['count'];
    $pages_count = ceil($items_count / $page_items);
    $pages = range(1, $pages_count);

    $lots = mysqli_fetch_all(getLotsBySearch($link, $search, $page_items,
        $current_page), MYSQLI_ASSOC);

    if ($pages_count <= 0) {
        $error = "Ничего не найдено по вашему запросу.";
        $page_content = include_template('error.php', ['error' => $error]);
    } else {
        $page_content = include_template('_search.php',
            compact('lots', 'categories', 'pages', 'pages_count',
                'current_page'));
    }
} else {
    $error = "Введите поисковой запрос";
    $page_content = include_template('error.php', ['error' => $error]);
}

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'container' => $container,
    'categories' => [],
    'title' => 'Yeticave - результат по запросу '.esc($_GET['q']),
]);
echo $layout_content;
