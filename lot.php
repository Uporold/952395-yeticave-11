<?php
require_once('functions.php');
require_once('init.php');
$container = 0;

$sql = 'SELECT `id`, `cat_name`, `code` FROM categories';
$result = mysqli_query($con, $sql);
if ($result) {
    $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
    print("Ошибка подключения: ". mysqli_connect_error());
}

$sql = 'SELECT * FROM lots';
$res = mysqli_query($con, $sql);
$num = mysqli_num_rows($res);

if (isset($_GET['id'])) {
    $lot_id = $_GET['id'];
}

$sql = 'SELECT * FROM lots l '
    . 'JOIN categories ON categories.id = l.cat_id '
    . 'WHERE l.id = ' . $lot_id;

if ($result = mysqli_query($con, $sql)) {
    $lots = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $page_content = include_template('_lot.php', ['lots' => $lots, 'categories' => $categories]);
}

if ($_GET['id'] > $num) {
    $error = "Не удалось соединиться с базой данных.";
    $page_content = include_template('error.php', ['error' => $error]);
    http_response_code(404);
}


 $layout_content = include_template('layout.php', [
    'content' => $page_content,
    'container' => $container,
    'categories' => $categories,
    'title' => 'YetiCave - Главная страница',
    'is_auth' => rand(0, 1),
    'user_name' => 'Василий'
]);
echo $layout_content;
