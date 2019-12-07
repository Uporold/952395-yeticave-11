<?php
session_start();
define('CACHE_DIR', basename(__DIR__.DIRECTORY_SEPARATOR.'cache'));
define('UPLOAD_PATH', basename(__DIR__.DIRECTORY_SEPARATOR.'uploads'));

require_once 'functions.php';
$db = require_once 'config/db.php';
$link = mysqli_connect($db['host'], $db['user'], $db['password'],
    $db['database']);
mysqli_set_charset($link, "utf8");

$categories = [];

if ($link === false) {
    $error = "Не удалось соединиться с базой данных.";
    $page_content = include_template('error.php', ['error' => $error]);
    $categories = 0;
}

if (getCategories($link)) {
    $categories = mysqli_fetch_all(getCategories($link), MYSQLI_ASSOC);
}


