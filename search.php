<?php
require_once 'init.php';
$container = 0;
$link = '/search.php';

    $sql = 'SELECT `id`, `cat_name`, `code` FROM categories';
    $res = mysqli_query($con, $sql);

    if ($res) {
        $categories = mysqli_fetch_all($res, MYSQLI_ASSOC);
    }

    $lots = [];


    $search = $_GET['q'] ?? '';

    if ($search) {
        $cur_page = $_GET['page'] ?? 1;
        $page_items = 1;
        $result = mysqli_query($con, "SELECT COUNT(*) as cnt FROM lots "
        . "JOIN categories ON lots.cat_id  = categories.id "
        . "WHERE MATCH(lot_name, text) AGAINST( '". $search ."')");

        $items_count = mysqli_fetch_assoc($result)['cnt'];
        $pages_count = ceil($items_count / $page_items);
        $offset = ($cur_page - 1) * $page_items;
        var_dump($result);
        $pages = range(1, $pages_count);

        $sql = "SELECT lots.id, lot_name, cat_id, cat_name, st_price, path, dt_add, dt_end, text, bet_step FROM lots "
          . "JOIN categories ON lots.cat_id  = categories.id "
          . "WHERE MATCH(lot_name, text) AGAINST(?)"
          . "ORDER BY dt_add DESC LIMIT " . $page_items . " OFFSET " . $offset;
        $stmt = db_get_prepare_stmt($con, $sql, [$search]);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $lots = mysqli_fetch_all($result, MYSQLI_ASSOC);
        if ($pages_count <= 0) {
            $error = "Ничего не найдено по вашему запросу.";
            $page_content = include_template('error.php', ['error' => $error]);
        } else {
            $page_content = include_template('_category.php', compact('lots', 'categories', 'pages', 'pages_count', 'cur_page', 'link'));
        }
    }

    $layout_content = include_template('layout.php', [
        'content'    => $page_content,
        'container' => $container,
        'categories' => [],
        'is_auth' => rand(0, 1),
        'user_name' => 'Василий',
        'title'      => 'Yeticave | Регистрация'
    ]);
    echo $layout_content;