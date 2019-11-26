<?php
session_start();

define('CACHE_DIR', basename(__DIR__ . DIRECTORY_SEPARATOR . 'cache'));
define('UPLOAD_PATH', basename(__DIR__ . DIRECTORY_SEPARATOR . 'uploads'));

require_once 'functions.php';
$db = require_once 'config/db.php';

$con = mysqli_connect($db['host'], $db['user'], $db['password'], $db['database']);
mysqli_set_charset($con, "utf8");

if ($con === false) {
    $error = "Не удалось соединиться с базой данных.";
    $page_content = include_template('error.php', ['error' => $error]);
    $categories = 0;
}
$sql = 'SELECT `id`, `cat_name`, `code` FROM categories';
$result = mysqli_query($con, $sql);
if ($result) {
    $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
    echo "Ошибка подключения: ". mysqli_connect_error();
}
