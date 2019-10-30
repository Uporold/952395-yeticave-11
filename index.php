<?php
require_once('functions.php');
require_once('data.php');

$page_content = include_template('main.php', ['lots' => $lots,
                                              'categories' => $categories]);
$layout_content = include_template('layout.php', [
	'content' => $page_content,
	'categories' => $categories,
	'title' => 'YetiCave - Главная страница'
]);

print($layout_content);
