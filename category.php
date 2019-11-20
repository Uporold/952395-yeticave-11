<?php
require_once 'functions.php';
require_once 'init.php';
$container = 0;

$link = '/category.php';
$sql = 'SELECT `id`, `cat_name`, `code` FROM categories';
$result = mysqli_query($con, $sql);
if ($result) {
    $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
    print("Ошибка подключения: ". mysqli_connect_error());
}

$q = filter_input(INPUT_GET, 'q');
    if ($q == $_GET['q']) {
        $sort_field = $_GET['q'];
    }
    $cur_page = $_GET['page'] ?? 1;
    $page_items = 1;
    $result = mysqli_query($con, 'SELECT COUNT(*) as cnt FROM lots '
    . 'JOIN categories ON categories.id = lots.cat_id '
    . 'WHERE categories.code ="' . $sort_field . '"'
    . 'ORDER BY dt_add DESC LIMIT 9');
    $items_count = mysqli_fetch_assoc($result)['cnt'];
    $pages_count = ceil($items_count / $page_items);
    $offset = ($cur_page - 1) * $page_items;
    var_dump($result);
    $pages = range(1, $pages_count);


    $sql = 'SELECT lots.id, lot_name, st_price, path, dt_end, categories.cat_name  FROM lots '
    . 'JOIN categories ON categories.id = lots.cat_id '
    . 'WHERE categories.code ="' . $sort_field . '"'
    . 'ORDER BY dt_add DESC LIMIT ' . $page_items . ' OFFSET ' . $offset;

    if ($res = mysqli_query($con, $sql)) {
        $lots = mysqli_fetch_all($res, MYSQLI_ASSOC);
        $page_content = include_template('_category.php', compact('lots', 'categories', 'pages', 'pages_count', 'cur_page', 'link'));
    } else {
        $page_content = include_template('error.php', ['error' => mysqli_error($con)]);
    }
    $layout_content = include_template('layout.php', [
        'content' => $page_content,
        'categories' => $categories,
        'container' => $container,
        'title' => 'YetiCave - Главная страница',
        'is_auth' => rand(0, 1),
        'user_name' => 'Василий'
    ]);

    echo $layout_content;
