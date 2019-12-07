<?php
require_once 'functions.php';
require_once 'init.php';
$container = 0;
$error = null;
$bets = null;
$user = null;

if (!isset($_SESSION['user'])) {
    $error
        = "Данная страница доступна только зарегистрированным пользователям.";
    $page_content = include_template('error.php', ['error' => $error]);
    header('Refresh: 3; url="/"');
    http_response_code(403);
} else {
    $user = $_SESSION['user']['id'];
    $bets = mysqli_fetch_all(getUserBets($link, $user), MYSQLI_ASSOC);
    $page_content = include_template('_my-bets.php',
        compact('bets', 'categories'));
}
$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'container' => $container,
    'categories' => $categories,
    'title' => 'YetiCave - Мои ставки',
]);
echo $layout_content;
