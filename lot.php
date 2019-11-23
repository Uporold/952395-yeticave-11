<?php
require_once 'functions.php';
require_once 'init.php';
$container = 0;

$sql = 'SELECT `id`, `cat_name`, `code` FROM categories';
$result = mysqli_query($con, $sql);
if ($result) {
    $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
    print("Ошибка подключения: ". mysqli_connect_error());
}

if (isset($_GET['id'])) {
    $lot_id = $_GET['id'];
}

    $sql = 'SELECT * FROM lots l '
    . 'JOIN categories ON categories.id = l.cat_id '
    . 'WHERE l.id = ' . $lot_id;
    $result = mysqli_query($con, $sql);
    $num = mysqli_num_rows($result);

    $lotSql = 'SELECT lots.*, categories.cat_name, MAX(bets.value) AS current_price FROM bets '
    . 'JOIN lots ON bets.lot_id = lots.id '
    . 'JOIN categories ON lots.cat_id = categories.id '
    . 'WHERE lots.id = ' . $lot_id;
    $lotResult = mysqli_query($con, $lotSql);
    $lots = mysqli_fetch_all($lotResult, MYSQLI_ASSOC);

    $betsSql = 'SELECT users.name, bets.value, bets.dt_add FROM bets '
    . 'JOIN users ON user_id = users.id '
    . 'WHERE bets.lot_id = ' . $lot_id . ' ORDER BY dt_add DESC';
    $betsResult = mysqli_query($con, $betsSql);
    $bets = mysqli_fetch_all($betsResult, MYSQLI_ASSOC);

    $betsCountSql = 'SELECT COUNT(*) AS cnt FROM bets WHERE lot_id = ' . $lot_id;
    $betsCountResult = mysqli_query($con, $betsCountSql);
    $betsCount = mysqli_fetch_all($betsCountResult, MYSQLI_ASSOC);


if ($result) {
    if (!$num) {
        $error = "Не удалось соединиться с базой данных.";
        $page_content = include_template('error.php', ['error' => $error]);
        http_response_code(404);
    } else {
        $page_content = include_template('_lot.php', compact('lots', 'categories', 'betsCount', 'bets'));
    }
} else {
    show_error($page_content, mysqli_error($con));
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (empty($_POST['value'])) {
        $error = 'Пожалуйста, заполните поле';
    } elseif (!filter_var($_POST['value'], FILTER_VALIDATE_INT)) {
        $error = 'Сумма ставки должна быть целым';
    } elseif ($_POST['value'] < ($price = $lots[0]['current_price'] ?? $lots[0]['st_price']) + $lots[0]['bet_step']) {
        $error = "Минимальная ставка " . ($price + $lots[0]['bet_step']) . " руб.";
    }
    if (isset($error)) {
        $page_content = include_template('_lot.php', compact('error', 'lots', 'categories', 'betsCount', 'bets'));
    } else {
        $sql = 'INSERT INTO bets (dt_add, user_id, lot_id, value) VALUES (NOW(), ?, ?, ?)';
        $stmt = db_get_prepare_stmt($con, $sql, [$_SESSION['user']['id'], $lots[0]['id'], $_POST['value']]);
        $res = mysqli_stmt_execute($stmt);
        if ($res) {
            header("Location: /lot.php?id={$lots[0]['id']}");
        } else {
            $pageContent = include_template('_lot.php', ['error' => mysqli_error($con)]);
        }
    }
}

 $layout_content = include_template('layout.php', [
    'content' => $page_content,
    'container' => $container,
    'categories' => $categories,
    'title' => 'YetiCave - ' . $lots[0]['lot_name'],
    'is_auth' => rand(0, 1),
    'user_name' => 'Василий'
]);
echo $layout_content;
