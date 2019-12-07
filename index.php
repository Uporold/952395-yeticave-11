<?php
require_once 'init.php';
require_once 'functions.php';
require_once 'getwinner.php';
$container = 1;

$lots = mysqli_fetch_all(getLastLots($link), MYSQLI_ASSOC);
$page_content = include_template('main.php', compact('lots', 'categories'));

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'categories' => $categories,
    'title' => 'YetiCave - Главная страница',
    'container' => $container,
]);
echo $layout_content;
