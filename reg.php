<?php
require_once 'init.php';
require_once 'functions.php';
$container = 0;
$errors = null;

if (isset($_SESSION['user'])) {
    http_response_code(403);
    header("Location: /");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $form = $_POST;
    $errors = [];
    $requiredFields = [
        'email' => '"Email"',
        'name' => '"Имя"',
        'password' => '"Пароль"',
        'contacts' => '"Контактные данные"',
    ];
    $user = filter_input_array(INPUT_POST, [
        'email' => FILTER_VALIDATE_EMAIL,
        'name' => FILTER_DEFAULT,
        'password' => FILTER_DEFAULT,
        'contacts' => FILTER_DEFAULT,
    ], true);

    if (!empty($_POST['email'])) {
        if (validateLength($_POST['email'], $min = 5, $max = 30)) {
            $errors['email']
                = "Значение поля Email должно быть от $min до $max символов";
        }
        if (!filter_var(($_POST['email']), FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Введите корректный email';
        }
        $email = mysqli_real_escape_string($link, $form['email']);
        $sql = "SELECT id FROM users WHERE email = '$email'";
        $result = mysqli_query($link, $sql);
        if (mysqli_num_rows($result) > 0) {
            $errors['email'] = 'Пользователь с этим email уже зарегистрирован';
        }
    }
    if (!empty($_POST['name'])
        && (validateLength($_POST['name'], $min = 2, $max = 20))
    ) {
        $errors['name']
            = "Значение поля Имя должно быть от $min до $max символов";
    }
    if (!empty($_POST['password'])
        && (validateLength($_POST['password'], $min = 6, $max = 30))
    ) {
        $errors['password']
            = "Значение поля Пароль должно быть от $min до $max символов";
    }
    if (!empty($_POST['contacts'])
        && (validateLength($_POST['contacts'], $min = 10, $max = 200))
    ) {
        $errors['contacts']
            = "Значение поля Контакты должно быть от $min до $max символов";
    }

    $errors = array_filter(emptyFieldErrors($requiredFields, $errors));

    if (empty($errors)) {
        if (isset($form['password'])) {
            $password = password_hash($form['password'], PASSWORD_DEFAULT);
        }

        $sql
            = 'INSERT INTO users (dt_reg, email, name, password, contacts) VALUES (NOW(), ?, ?, ?, ?)';
        $stmt = db_get_prepare_stmt($link, $sql,
            [$form['email'], $form['name'], $password, $form['contacts']]);
        $result = mysqli_stmt_execute($stmt);

        if ($result && empty($errors)) {
            header("Location: /login.php");
            exit();
        }
    }
}

$page_content = include_template('_reg.php',
    compact('categories', 'errors', 'error', 'key'));
$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'container' => $container,
    'categories' => [],
    'title' => 'Yeticave | Регистрация',
]);
echo $layout_content;
