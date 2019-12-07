<?php
require_once 'functions.php';
require_once 'init.php';
$container = 0;
$lot_id = null;
$page_content = null;
$error = null;

if (isset($_GET['id'])) {
    $lot_id = filter_var($_GET['id'], FILTER_VALIDATE_INT);
}

$lotId = mysqli_real_escape_string($link, $lot_id);
$lots = mysqli_fetch_all(getLot($link, $lotId), MYSQLI_ASSOC);
$bets = mysqli_fetch_all(getLotBets($link, $lotId), MYSQLI_ASSOC);
$betsCount = mysqli_fetch_all(getLotBetsCount($link, $lotId), MYSQLI_ASSOC);

if (getLotByID($link, $lotId)) {
    $page_content = include_template('_lot.php',
        compact('lots', 'categories', 'betsCount', 'bets'));
} else {
    $error = "Не удалось соединиться с базой данных.";
    $page_content = include_template('error.php', ['error' => $error]);
    http_response_code(404);
}

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    if (getLotByID($link, $lotId)) {
        if (empty($_POST['value'])) {
            $error = 'Пожалуйста, заполните поле';
        } elseif (!filter_var($_POST['value'], FILTER_VALIDATE_INT)) {
            $error = 'Ставка должна быть целым числом';
        } elseif ($_POST['value'] < ($price = $lots[0]['current_price'] ??
                $lots[0]['st_price']) + $lots[0]['bet_step']
        ) {
            $error = "Минимальная ставка ".($price + $lots[0]['bet_step'])
                ." руб.";
        } elseif ($_SESSION['user']['id'] === $lots[0]['autor_id']) {
            $error = "Нельзя делать ставку на свой лот!";
        } elseif (strtotime($lots[0]['dt_end']) < time()) {
            $error = "Нельзя делать ставку на проданный лот!";
        }elseif (($bets[0]['user_id'] ?? 0) === $_SESSION['user']['id']) {
            $error = "Вашу ставку еще не перебили!";
        }
        if (isset($error)) {
            $page_content = include_template('_lot.php',
                compact('error', 'lots', 'categories', 'betsCount', 'bets'));
        } else {
            $result = addBet($link, $_SESSION['user']['id'], $lot_id,
                $_POST['value']);
            if ($result) {
                header("Location: /lot.php?id={$lot_id}");
            }
            $pageContent = include_template('_lot.php',
                compact('lots', 'categories', 'betsCount', 'bets'));
        }
    }
}

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'container' => $container,
    'categories' => $categories,
    'title' => 'YetiCave - '.esc($lots[0]['lot_name']),
]);
echo $layout_content;
