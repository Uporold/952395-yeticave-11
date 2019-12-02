<?php
require_once 'init.php';
require_once 'functions.php';
$container = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $form = $_POST;
    $errors = [];
    $required = ['email', 'password'];
    $requiredFields = [
        'email' => 'email',
        'password' => 'пароль',
    ];

    $email = mysqli_real_escape_string($link, $form['email']);
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($link, $sql);

    $user = $result ? mysqli_fetch_array($result, MYSQLI_ASSOC) : null;

    if (isset($form['email'], $user['email'])
        && $form['email'] === $user['email']
    ) {
        $errors['email'] = null;
    }
    if (!isset($form['email'], $user['email'])
        && $form['email'] !== $user['email']
        && !empty($form['email'])
    ) {
        $errors['email'] = 'Такой пользователь не найден';
    }
    if (!(filter_var($form['email'], FILTER_VALIDATE_EMAIL))
        && !empty($form['email'])
    ) {
        $errors['email'] = 'Введите корректный email';
    }

    $errors = array_filter(emptyFieldErrors($requiredFields, $errors));

    if (!count($errors) and $user) {
        if (isset($form['password'], $user['password'])
            && password_verify($form['password'], $user['password'])
        ) {
            $_SESSION['user'] = $user;
        } else {
            $errors['password'] = 'Неверный пароль';
        }
    }
    if (count($errors)) {
        $page_content = include_template('_login.php',
            compact('form', 'errors', 'categories', 'email', 'password'));
    } else {
        header("Location: /index.php");
        exit();
    }
} else {
    $page_content = include_template('_login.php', compact('categories'));

    if (isset($_SESSION['user'])) {
        header("Location: /index.php");
        exit();
    }
}

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'categories' => $categories,
    'container' => $container,
    'title' => 'Yeticave - вход',
]);
echo $layout_content;
