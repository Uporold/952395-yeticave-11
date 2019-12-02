<?php
require_once 'functions.php';
require_once 'init.php';
$container = 0;

if (isset($_GET['id'])) {
    $lot_id = (int)$_GET['id'];
}

$sql = 'SELECT * FROM lots l '
    .'JOIN categories ON categories.id = l.categoryId '
    .'WHERE l.id = '.$lot_id;
$result = mysqli_query($link, $sql);
$num = mysqli_num_rows($result);

$lotSql
    = 'SELECT lots.*, categories.categoryName, MAX(bets.value) AS current_price FROM bets '
    .'JOIN lots ON bets.lot_id = lots.id '
    .'JOIN categories ON lots.categoryId = categories.id '
    .'WHERE lots.id = '.$lot_id;
$lotResult = mysqli_query($link, $lotSql);
$lots = mysqli_fetch_all($lotResult, MYSQLI_ASSOC);

$betsSql = 'SELECT users.name, bets.value, bets.dt_add FROM bets '
    .'JOIN users ON user_id = users.id '
    .'WHERE bets.lot_id = '.$lot_id.' ORDER BY dt_add DESC';
$betsResult = mysqli_query($link, $betsSql);
$bets = mysqli_fetch_all($betsResult, MYSQLI_ASSOC);

$betsCountSql = 'SELECT COUNT(*) AS count FROM bets WHERE lot_id = '.$lot_id;
$betsCountResult = mysqli_query($link, $betsCountSql);
$betsCount = mysqli_fetch_all($betsCountResult, MYSQLI_ASSOC);

if ($result) {
    if (!$num) {
        $error = "Не удалось соединиться с базой данных.";
        $page_content = include_template('error.php', ['error' => $error]);
        http_response_code(404);
    } else {
        $page_content = include_template('_lot.php',
            compact('lots', 'categories', 'betsCount', 'bets'));
    }
} else {
    $error = "Не удалось соединиться с базой данных.";
    $page_content = include_template('error.php', ['error' => $error]);
    http_response_code(404);
}

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    if (getLotByID($link, $lot_id)) {
        if (empty($_POST['value'])) {
            $error = 'Пожалуйста, заполните поле';
        } elseif (!filter_var($_POST['value'], FILTER_VALIDATE_INT)) {
            $error = 'Ставка должна быть целым числом';
        } elseif ($_POST['value'] < ($price = $lots[0]['current_price'] ??
                $lots[0]['st_price']) + $lots[0]['bet_step']
        ) {
            $error = "Минимальная ставка ".($price + $lots[0]['bet_step'])
                ." руб.";
        }
        if (isset($error)) {
            $page_content = include_template('_lot.php',
                compact('error', 'lots', 'categories', 'betsCount', 'bets'));
        } else {
            $sql
                = 'INSERT INTO bets (dt_add, user_id, lot_id, value) VALUES (NOW(), ?, ?, ?)';
            $stmt = db_get_prepare_stmt($link, $sql,
                [$_SESSION['user']['id'], $lot_id, $_POST['value']]);
            $result = mysqli_stmt_execute($stmt);
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
