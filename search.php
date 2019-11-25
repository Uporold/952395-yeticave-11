<?php
require_once 'init.php';
$container = 0;

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
        . "WHERE MATCH(lot_name, text) AGAINST( '". esc($search) ."') AND dt_end > NOW()");

        $items_count = mysqli_fetch_assoc($result)['cnt'];
        if ($page_items > 0) {
            $pages_count = ceil($items_count / $page_items);
        }
        $pages = range(1, $pages_count);
        $sql = "SELECT lots.id, lot_name, cat_id, cat_name, st_price, path, dt_add, dt_end, text, bet_step FROM lots "
          . "JOIN categories ON lots.cat_id  = categories.id "
          . "WHERE MATCH(lot_name, text) AGAINST(?) AND dt_end > NOW() "
          . "ORDER BY dt_add DESC ";
        $stmt = db_get_prepare_stmt($con, pagination($sql, $page_items, $cur_page), [$search]);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $lots = mysqli_fetch_all($result, MYSQLI_ASSOC);
        if ($pages_count <= 0) {
            $error = "Ничего не найдено по вашему запросу.";
            $page_content = include_template('error.php', ['error' => $error]);
        } else {
            $page_content = include_template('_search.php', compact('lots', 'categories', 'pages', 'pages_count', 'cur_page', 'link', 'val'));
        }
    }

    $layout_content = include_template('layout.php', [
        'content'    => $page_content,
        'container' => $container,
        'categories' => [],
        'is_auth' => rand(0, 1),
        'user_name' => 'Василий',
        'title'      => 'Yeticave - результат по запросу ' . esc($_GET['q'])
    ]);
    echo $layout_content;
