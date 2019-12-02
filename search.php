<?php
require_once 'init.php';
$container = 0;

$lots = [];
$search = filter_input(INPUT_GET, 'q', FILTER_SANITIZE_SPECIAL_CHARS) ?? '';

if (!empty($search)) {
    $current_page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?? 1;
    $page_items = 9;
    $sql = "SELECT COUNT(*) as count FROM lots "
        ."JOIN categories ON lots.categoryId  = categories.id "
        ."WHERE MATCH(lot_name, text) AGAINST(?) AND dt_end > NOW()";
    $stmt = db_get_prepare_stmt($link, $sql, [$search]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $items_count = mysqli_fetch_assoc($result)['count'];
    $pages_count = ceil($items_count / $page_items);
    $pages = range(1, $pages_count);
    $sql
        = "SELECT lots.id, lot_name, categoryId, categoryName, st_price, path, dt_add, dt_end, text, bet_step FROM lots "
        ."JOIN categories ON lots.categoryId  = categories.id "
        ."WHERE MATCH(lot_name, text) AGAINST(?) AND dt_end > NOW() "
        ."ORDER BY dt_add DESC ";
    $stmt = db_get_prepare_stmt($link,
        pagination($sql, $page_items, $current_page), [$search]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $lots = mysqli_fetch_all($result, MYSQLI_ASSOC);

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
