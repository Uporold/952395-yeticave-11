<?php
require_once 'functions.php';
$db = require_once 'config/db.php';

$con = mysqli_connect($db['host'], $db['user'], $db['password'], $db['database']);
mysqli_set_charset($con, "utf8");

if ($con === false) {
    $error = "Не удалось соединиться с базой данных.";
    $page_content = include_template('error.php', ['error' => $error]);
    $categories = 0;
}
