<?php
require_once 'functions.php';
require_once 'init.php';
$container = 0;
$lots = [];

$tab = filter_input(INPUT_GET, 'tab');
if (isset($tab)) {
    $sort_field = $tab;
}
$current_page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?? 1;
$page_items = 9;
$sql = 'SELECT COUNT(*) as count FROM lots '
    .'JOIN categories ON categories.id = lots.categoryId '
    .'WHERE categories.code = (?) AND dt_end > NOW()'
    .'ORDER BY dt_add DESC LIMIT 9';
$stmt = db_get_prepare_stmt($link, $sql, [$sort_field]);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$items_count = mysqli_fetch_assoc($result)['count'];
$pages_count = ceil($items_count / $page_items);
$pages = range(1, $pages_count);

$sql = 'SELECT categoryName FROM categories '
    .' WHERE code = (?) ';
$stmt = db_get_prepare_stmt($link, $sql, [$sort_field]);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$categoryName = mysqli_fetch_all($result, MYSQLI_ASSOC);

$sql
    = 'SELECT lots.id, lot_name, st_price, path, dt_end, categories.categoryName  FROM lots '
    .'JOIN categories ON categories.id = lots.categoryId '
    .'WHERE categories.code =(?) AND dt_end > NOW()'
    .'ORDER BY dt_add DESC ';
$stmt = db_get_prepare_stmt($link, pagination($sql, $page_items, $current_page),
    [$sort_field]);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

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
