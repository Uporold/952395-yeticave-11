<?php
require_once 'functions.php';
require_once 'init.php';

$container = 0;
$categoryIds = [];
$categoryIds = array_column($categories, 'id');
$page_content = include_template('_add.php', ['categories' => $categories]);
if (isset($_SESSION['user'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $requiredFields = [
            'lot_name' => '"Наименование"',
            'categoryId' => '"Категория"',
            'text' => '"Описание"',
            'st_price' => '"Начальная цена"',
            'bet_step' => '"Шаг ставки"',
            'dt_end' => '"Дата окончания торгов"',
        ];
        $errors = [];

        $lot = filter_input_array(INPUT_POST, [
            'lot_name' => FILTER_DEFAULT,
            'text' => FILTER_DEFAULT,
            'st_price' => FILTER_DEFAULT,
            'bet_step' => FILTER_DEFAULT,
            'dt_end' => FILTER_DEFAULT,
            'categoryId' => FILTER_DEFAULT,
        ], true);

        if (!empty($_POST['categoryId'])
            && (!validateCategory($_POST['categoryId'], $categoryIds))
        ) {
            $errors['categoryId'] = "Указана несуществующая категория";
        }
        if (!empty($_POST['lot_name'])
            && (validateLength($_POST['lot_name'], $min = 10, $max = 50))
        ) {
            $errors['lot_name']
                = "Значение поля Наименование должно быть от $min до $max символов";
        }
        if (!empty($_POST['text'])
            && (validateLength($_POST['text'], $min = 15, $max = 300))
        ) {
            $errors['text']
                = "Значение поля Описание должно быть от $min до $max символов";
        }
        if (!empty($_POST['st_price'])
            && (!validateNumber($_POST['st_price']))
        ) {
            $errors['st_price']
                = "Начальная цена должна быть целым положительным числом";
        }
        if (!empty($_POST['bet_step'])
            && (!validateNumber($_POST['bet_step']))
        ) {
            $errors['bet_step']
                = "Шаг ставки должен быть целым положительным числом";
        }
        if (!empty($_POST['dt_end'])) {
            if (!is_date_valid($_POST['dt_end'])) {
                $errors['dt_end'] = "Неверный формат даты";
            } elseif (!dateCheck($_POST['dt_end'])) {
                $errors['dt_end']
                    = "Дата окончания торгов не может быть менее суток!";
            }
        }

        $errors = array_filter(emptyFieldErrors($requiredFields, $errors));

        if (!empty($_FILES['lot_img']['name'])) {
            $tmp_name = $_FILES['lot_img']['tmp_name'];
            $path = $_FILES['lot_img']['name'];
            $filename = uniqid().'.jpg';
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $file_type = finfo_file($finfo, $tmp_name);
            if ($file_type !== "image/jpeg" && $file_type !== "image/png") {
                $errors['lot_img'] = 'Загрузите картинку в формате JPG или PNG';
            } else {
                move_uploaded_file($tmp_name, 'uploads/'.$filename);
                $lot['path'] = $filename;
            }

        } else {
            $errors['file'] = 'Вы не загрузили файл';
        }

        if (count($errors)) {
            $page_content = include_template('_add.php',
                compact('lot', 'errors', 'categories'));
        } else {
            $sql = 'INSERT INTO lots (dt_add, autor_id, lot_name, text, st_price, bet_step, dt_end, categoryId, path)
        VALUES (NOW(), "'.$_SESSION['user']['id'].'", ?, ?, ?, ?, ?, ?, ?)';
            $stmt = db_get_prepare_stmt($link, $sql, $lot);
            $result = mysqli_stmt_execute($stmt);

            if ($result) {
                $lot_id = mysqli_insert_id($link);
                header("Location: lot.php?id=".$lot_id);
            }
        }
    }
} else {
    $error
        = "Публикация лотов доступна только зарегистрированным пользователям, Вы будете перенаправлены на главную страницу через 3 секунды.";
    $page_content = include_template('error.php', ['error' => $error]);
    header('Refresh: 3; url="/"');
    http_response_code(403);
}
$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'container' => $container,
    'categories' => [],
    'title' => 'YetiCave - Добавление лота',
]);
echo $layout_content;
