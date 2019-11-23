<?php
require_once 'init.php';
require_once 'functions.php';
$container = 0;
$errors = null;

$sql = 'SELECT `id`, `cat_name`, `code` FROM categories';
$res = mysqli_query($con, $sql);
if ($res) {
    $categories = mysqli_fetch_all($res, MYSQLI_ASSOC);
}

if (isset($_SESSION['user'])) {
    http_response_code(403);
    header("Location: /");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $form = $_POST;
    $errors = [];

    $required = ['email', 'name', 'password', 'contacts'];
    $rules = [
        'email' => function ($value) {
            return validateLength($value, 10, 200);
        },
        'name' => function ($value) {
            return validateLength($value, 2, 200);
        },
        'password' => function ($value) {
            return validateLength($value, 6, 30);
        },
        'contacts' => function ($value) {
            return validateLength($value, 10, 200);
        }
    ];

    $user = filter_input_array(INPUT_POST, ['email' => FILTER_VALIDATE_EMAIL, 'name' => FILTER_DEFAULT,
    'password' => FILTER_DEFAULT, 'contacts' => FILTER_DEFAULT], true);

    foreach ($user as $key => $value) {
        if (isset($rules[$key])) {
            $rule = $rules[$key];
            $errors[$key] = $rule($value);
        }

        if (in_array($key, $required) && empty($value)) {
            $errors[$key] = "Поле $key надо заполнить";
        }
    }
    $errors = array_filter($errors);

    if (empty($errors)) {
        $email = mysqli_real_escape_string($con, $form['email']);
        $sql = "SELECT id FROM users WHERE email = '$email'";
        $res = mysqli_query($con, $sql);

        if (mysqli_num_rows($res) > 0) {
            $errors[] = 'Пользователь с этим email уже зарегистрирован';
        } else {
            $password = password_hash($form['password'], PASSWORD_DEFAULT);

            $sql = 'INSERT INTO users (dt_reg, email, name, password, contacts) VALUES (NOW(), ?, ?, ?, ?)';
            $stmt = db_get_prepare_stmt($con, $sql, [$form['email'], $form['name'], $password, $form['contacts']]);
            $res = mysqli_stmt_execute($stmt);
        }

        if ($res && empty($errors)) {
            header("Location: /login.php");
            exit();
        }
    }
}

$page_content = include_template('_reg.php', compact('categories', 'errors'));
$layout_content = include_template('layout.php', [
    'content'    => $page_content,
    'container' => $container,
    'categories' => [],
    'title'      => 'Yeticave | Регистрация'
]);
echo $layout_content;
