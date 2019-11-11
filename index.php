<?php
require_once('init.php');
require_once('functions.php');


if ($con == false) {
    $error = "Не удалось соединиться с базой данных.";
    $page_content = include_template('error.php', ['error' => $error]);
    $categories = 0;
} else {
     $container = 1;
     $sql = 'SELECT `id`, `cat_name`, `code` FROM categories';
     $result = mysqli_query($con, $sql);
     if ($result) {
         $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
     } else {
         print("Ошибка подключения: ". mysqli_connect_error());
     }
     $sql = 'SELECT lots.id, lot_name, st_price, path, dt_end, categories.cat_name  FROM lots '
          . 'JOIN categories ON categories.id = lots.cat_id '
          . 'ORDER BY dt_add DESC LIMIT 9';
     if ($res = mysqli_query($con, $sql)) {
         $lots = mysqli_fetch_all($res, MYSQLI_ASSOC);
     }

     $page_content = include_template('main.php', compact('lots', 'categories'));
 }


$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'categories' => $categories,
    'title' => 'YetiCave - Главная страница',
    'is_auth' => rand(0, 1),
    'user_name' => 'Василий'
]);

echo $layout_content;
