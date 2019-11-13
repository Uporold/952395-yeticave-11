<?php
require_once('functions.php');
require_once('init.php');
$container = 0;



$cats_ids = [];
$sql = 'SELECT `id`, `cat_name`, `code` FROM categories';
$res = mysqli_query($con, $sql);
if ($res) {
    $categories = mysqli_fetch_all($res, MYSQLI_ASSOC);
    $cats_ids = array_column($categories, 'id');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $required = ['lot_name', 'cat_id', 'text', 'st_price', 'bet_step', 'dt_end'];
    $errors = [];

    $rules = [
        'cat_id' => function ($value) use ($cats_ids) {
            return validateCategory($value, $cats_ids);
        },
        'lot_name' => function ($value) {
            return validateLength($value, 10, 200);
        },
        'text' => function ($value) {
            return validateLength($value, 10, 3000);
        },
        'st_price' => function ($value) {
            return validateNumber($value);
        },
        'bet_step' => function ($value) {
            return validateNumber($value);
        },
        'dt_end' => function ($value) {
            return dateCheck($value);
        }
    ];

    $lot = filter_input_array(INPUT_POST, ['lot_name' => FILTER_DEFAULT, 'text' => FILTER_DEFAULT,
    'lot_name' => FILTER_DEFAULT, 'st_price' => FILTER_DEFAULT, 'bet_step' => FILTER_DEFAULT, 'dt_end' => FILTER_DEFAULT,
    'cat_id' => FILTER_DEFAULT], true);

    foreach ($lot as $key => $value) {
        if (isset($rules[$key])) {
            $rule = $rules[$key];
            $errors[$key] = $rule($value);
        }

        if (in_array($key, $required) && empty($value)) {
            $errors[$key] = "Поле $key надо заполнить";
        }
    }

    $errors = array_filter($errors);

    if (!empty($_FILES['lot_img']['name'])) {
        $tmp_name = $_FILES['lot_img']['tmp_name'];
        $path = $_FILES['lot_img']['name'];
        $filename = uniqid() . '.jpg';

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $file_type = finfo_file($finfo, $tmp_name);
        if ($file_type !== "image/jpeg") {
            $errors['file'] = 'Загрузите картинку в формате JPG';
        } else {
            move_uploaded_file($tmp_name, 'uploads/' . $filename);
            $lot['path'] = $filename;
        }
    } else {
        $errors['file'] = 'Вы не загрузили файл';
    }
    if (count($errors)) {
        $page_content = include_template('_add.php', ['lot' => $lot, 'errors' => $errors, 'categories' => $categories]);
    } else {
        $sql = 'INSERT INTO lots (dt_add, autor_id, lot_name, cat_id, text, st_price, bet_step, dt_end, path)
            VALUES (NOW(), 1, ?, ?, ?, ?, ?, ?, ?)';

        $stmt = db_get_prepare_stmt($con, $sql, $lot);
        $res = mysqli_stmt_execute($stmt);

        if ($res) {
            $lot_id = mysqli_insert_id($con);
            header("Location: lot.php?id=" . $lot_id);
        }
    }
} else {
    $page_content = include_template('_add.php', ['categories' => $categories]);
}

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'container' => $container,
    'categories' => [],
    'title' => 'YetiCave - Добавление лота',
    'is_auth' => rand(0, 1),
    'user_name' => 'Василий'
]);
echo $layout_content;
