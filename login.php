<?php
require_once 'init.php';
require_once 'functions.php';
$container = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $form = $_POST;
    $errors = [];
    $required = ['email', 'password'];

    foreach ($required as $field) {
        if (empty($form[$field])) {
            $errors[$field] = 'Это поле надо заполнить';
        }
    }
    $errors = array_filter($errors);

    $email = mysqli_real_escape_string($con, $form['email']);
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $res = mysqli_query($con, $sql);

    $user = $res ? mysqli_fetch_array($res, MYSQLI_ASSOC) : null;

    if (!count($errors) and $user) {
        if (password_verify($form['password'], $user['password'])) {
            $_SESSION['user'] = $user;
        } else {
            $errors['password'] = 'Неверный пароль';
        }
    } elseif ($form['email'] === $user['email']) {
        $errors['email'] = null;
    } elseif ($form['email'] !== $user['email'] && !empty($form['email'])) {
        $errors['email'] = 'Такой пользователь не найден';
    }

    if (count($errors)) {
        $page_content = include_template('_login.php', compact('form', 'errors', 'categories'));

    } else {
        header("Location: /index.php");
        exit();
    }
} else {
    $page_content = include_template('_login.php', ['categories' => $categories]);

    if (isset($_SESSION['user'])) {
        header("Location: /index.php");
        exit();
    }
}

$layout_content = include_template('layout.php', [
    'content'    => $page_content,
    'categories' => $categories,
    'container' => $container,
    'title'      => 'Yeticave - вход'
]);

echo $layout_content;
