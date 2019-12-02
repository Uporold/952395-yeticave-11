<?php
require_once 'init.php';
require_once 'functions.php';
require_once 'getwinner.php';
$container = 1;

$sql
    = 'SELECT lots.id, lot_name, st_price, path, dt_end, categories.categoryName  FROM lots '
    .'JOIN categories ON categories.id = lots.categoryId '
    .'WHERE dt_end > NOW()'
    .'ORDER BY dt_add DESC LIMIT 9';
if ($result = mysqli_query($link, $sql)) {
    $lots = mysqli_fetch_all($result, MYSQLI_ASSOC);
}

$page_content = include_template('main.php', compact('lots', 'categories'));
$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'categories' => $categories,
    'title' => 'YetiCave - Главная страница',
    'container' => $container,
]);
echo $layout_content;
