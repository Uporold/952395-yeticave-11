<?php
require_once 'functions.php';
require_once 'init.php';
$container = 0;

$sql = 'SELECT `id`, `cat_name`, `code` FROM categories';
$result = mysqli_query($con, $sql);
if ($result) {
    $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
    echo "Ошибка подключения: ". mysqli_connect_error();
}

if (!isset($_SESSION['user'])) {
    $error = "Данная страница доступна только зарегистрированным пользователям.";
    $page_content = include_template('error.php', ['error' => $error]);
    header('Refresh: 3; url="/"');
    http_response_code(403);
} else {
    $sql = 'SELECT bets.dt_add, bets.value, lots.id AS lot_id, lots.lot_name, lots.path, lots.dt_end, lots.winner_id, categories.cat_name, (SELECT users.contacts FROM users JOIN lots ON lots.autor_id = users.id WHERE lots.id = lot_id) AS contacts FROM bets '
      . 'JOIN lots ON bets.lot_id = lots.id '
      . 'JOIN users ON bets.user_id = users.id '
      . 'JOIN categories ON lots.cat_id = categories.id '
      . 'WHERE bets.user_id = ? AND bets.value IN (SELECT MAX(bets.value) FROM bets GROUP BY lot_id) '
      . 'ORDER BY bets.dt_add DESC ';

    $stmt = db_get_prepare_stmt($con, $sql, [$_SESSION['user']['id']]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $bets = mysqli_fetch_all($result, MYSQLI_ASSOC);

    $page_content = include_template('_my-bets.php', compact('bets', 'categories'));
}
$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'container' => $container,
    'categories' => $categories,
    'title' => 'YetiCave - Мои ставки',
]);
echo $layout_content;
