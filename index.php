<?php
require_once('functions.php');
require_once('data.php');

$page_content = include_template('main.php', compact('lots', 'categories'));
$layout_content = include_template('layout.php', [
	'content' => $page_content,
	'categories' => $categories,
    'title' => 'YetiCave - Главная страница',
    'is_auth' => rand(0, 1),
    'user_name' => 'Василий'
]);

echo $layout_content;
