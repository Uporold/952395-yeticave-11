<?php
require_once 'functions.php';
require_once 'init.php';
$container = 0;
$sql = 'SELECT `id`, `cat_name`, `code` FROM categories';
$result = mysqli_query($con, $sql);
if ($result) {
    $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
    echo "Ошибка подключения: ". mysqli_connect_error();
}

$tab = filter_input(INPUT_GET, 'tab');
    if ($tab == $_GET['tab']) {
        $sort_field = $_GET['tab'];
    }
    $cur_page = $_GET['page'] ?? 1;
    $page_items = 1;
    $result = mysqli_query($con, 'SELECT COUNT(*) as cnt FROM lots '
    . 'JOIN categories ON categories.id = lots.cat_id '
    . 'WHERE categories.code ="' . $sort_field . '" AND dt_end > NOW()'
    . 'ORDER BY dt_add DESC LIMIT 9');
    $items_count = mysqli_fetch_assoc($result)['cnt'];
    if ($page_items > 0) {
        $pages_count = ceil($items_count / $page_items);
    }
    $pages = range(1, $pages_count);


    $sql = 'SELECT lots.id, lot_name, st_price, path, dt_end, categories.cat_name  FROM lots '
    . 'JOIN categories ON categories.id = lots.cat_id '
    . 'WHERE categories.code ="' . $sort_field . '" AND dt_end > NOW()'
    . 'ORDER BY dt_add DESC ';
    if ($res = mysqli_query($con, pagination($sql, $page_items, $cur_page))) {
        $lots = mysqli_fetch_all($res, MYSQLI_ASSOC);
        $page_content = include_template('_category.php', compact('lots', 'categories', 'pages', 'pages_count', 'cur_page', 'link', 'val'));
    } else {
        $page_content = include_template('error.php', ['error' => mysqli_error($con)]);
    }
    $layout_content = include_template('layout.php', [
        'content' => $page_content,
        'categories' => $categories,
        'container' => $container,
        'title' => 'YetiCave - ' . esc($lots[0]['cat_name'])
    ]);

    echo $layout_content;
