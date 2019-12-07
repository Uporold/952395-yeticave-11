<?php

/**
 * Получает значение цены и возвращает отформатированное значение
 * с делением на разряды и добавлением знака рубля.
 *
 * @param int $price Цена для форматирования
 *
 * @return int Отформатированное значение цены
 */
function priceFormatting($price)
{
    $ceilPrice = ceil($price);

    if ($ceilPrice < 1000) {
        return $ceilPrice." ₽";
    } elseif ($ceilPrice >= 1000) {
        $formattedPrice = number_format($ceilPrice, 0, $dec_point = "",
            $thousands_sep = " ");
    }

    return $formattedPrice." ₽";
}

/**
 * Подключает шаблон, передает туда данные и возвращает итоговый HTML контент
 *
 * @param string $name Путь к файлу шаблона относительно папки templates
 * @param array  $data Ассоциативный массив с данными для шаблона
 *
 * @return string Итоговый HTML
 */
function include_template($name, $data)
{
    $name = 'templates/'.$name;
    $result = '';

    if (!file_exists($name)) {
        return $result;
    }

    ob_start();
    extract($data);
    require $name;

    $result = ob_get_clean();

    return $result;
}

/**
 * Получает строку, преобразует специальные символы в HTML сущности
 *
 * @param string $str Строка для преобразования
 *
 * @return string Итоговый преобразованный текст
 */
function esc($str)
{
    $text = htmlspecialchars($str);

    return $text;
}

;

/**
 * Получает дату в формате ГГГГ-ММ-ДД, возвращает массив, где первый элемент — целое количество часов до даты, а второй — остаток в минутах;
 *
 * @param date $date Получаемая дата
 *
 * @return array $expInfo Итоговый массив
 */
function timeExp(string $date)
{
    date_default_timezone_set("Europe/Moscow");
    setlocale(LC_ALL, 'ru_RU');

    $expDate = strtotime($date);
    $secs_to_expire = $expDate - time();
    $hours_to_expire = str_pad(floor($secs_to_expire / 3600), 2, "0",
        STR_PAD_LEFT);
    $minutes_to_expire = str_pad(floor(($secs_to_expire % 3600) / 60), 2, "0",
        STR_PAD_LEFT);
    $expInfo = ['часы' => $hours_to_expire, 'минуты' => $minutes_to_expire];

    return $expInfo;
}

/**
 * Создает подготовленное выражение на основе готового SQL запроса и переданных данных
 *
 * @param       $link mysqli Ресурс соединения
 * @param       $sql  string SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 *
 * @return mysqli_stmt Подготовленное выражение
 */
function db_get_prepare_stmt($link, $sql, $data = [])
{
    $stmt = mysqli_prepare($link, $sql);

    if ($stmt === false) {
        $errorMsg = 'Не удалось инициализировать подготовленное выражение: '
            .mysqli_error($link);
        die($errorMsg);
    }

    if ($data) {
        $types = '';
        $stmt_data = [];

        foreach ($data as $value) {
            $type = 's';

            if (is_int($value)) {
                $type = 'i';
            } elseif (is_string($value)) {
                $type = 's';
            } elseif (is_double($value)) {
                $type = 'd';
            }

            if ($type) {
                $types .= $type;
                $stmt_data[] = $value;
            }
        }

        $values = array_merge([$stmt, $types], $stmt_data);

        $func = 'mysqli_stmt_bind_param';
        $func(...$values);

        if (mysqli_errno($link) > 0) {
            $errorMsg
                = 'Не удалось связать подготовленное выражение с параметрами: '
                .mysqli_error($link);
            die($errorMsg);
        }
    }

    return $stmt;
}

/**
 * Получает значение для сравнения и массив, ищет данное значение в массиве
 *
 * @param int   $id           Получаемое значение
 * @param array $allowed_list Массив-список для сравнения
 *
 * @return bool false Возвращает при отсутствии совпадений
 * @return bool true Возвращает при найденном соответствии
 */
function validateCategory($id, $allowed_list)
{
    if (!in_array($id, $allowed_list)) {
        return false;
    }

    return true;
}

/**
 * Получает значение для сравнения и его допустимые минимальное и максимальное значения
 *
 * @param int $value Получаемое значение
 * @param int $min   Минимальное допустимое значение
 * @param int $max   Максимальное допустимое значение
 *
 * @return bool true Возвращает при несоответсвии одному из допустимых значений
 */
function validateLength($value, int $min, int $max)
{
    return (strlen($value) < $min || strlen($value) > $max);
}

/**
 * Получает значение для сравнения и проверяет, положительное и целое ли оно
 *
 * @param int $value Получаемое значение
 *
 * @return bool false Число не прошло проверку на целочисленность и положительность
 * @return bool true Проверка пройдена успешно
 */

function validateNumber($value)
{
    if (!filter_var($value, FILTER_VALIDATE_INT,
        array("options" => array("min_range" => 1)))
    ) {
        return false;
    }

    return true;
}

/**
 * Проверяет переданную дату на соответствие формату 'ГГГГ-ММ-ДД'
 *
 * Примеры использования:
 * is_date_valid('2019-01-01'); // true
 * is_date_valid('2016-02-29'); // true
 * is_date_valid('2019-04-31'); // false
 * is_date_valid('10.10.2010'); // false
 * is_date_valid('10/10/2010'); // false
 *
 * @param string $date Дата в виде строки
 *
 * @return bool true при совпадении с форматом 'ГГГГ-ММ-ДД', иначе false
 */
function is_date_valid(string $date): bool
{
    $format_to_check = 'Y-m-d';
    $dateTimeObj = date_create_from_format($format_to_check, $date);

    return $dateTimeObj !== false && array_sum(date_get_last_errors()) === 0;
}

/**
 * Функция-помощник для сохранения в форме введеных данных
 *
 * @param string $name Поле данных
 *
 * @return string Введенные данные
 */
function getPostVal($name)
{
    return filter_input(INPUT_POST, $name);
}

/**
 * Проверяет переданную дату с текущей, в зависимости от разницы вернет результат
 *
 * @param string $date Дата в виде строки
 *
 * @return bool true Дата удовлетворяет условию быть больше текущей даты минимум на 1 день
 * @return bool false Дата не удовлетворяет условию быть больше текущей даты минимум на 1 день
 */

function dateCheck($date)
{
    date_default_timezone_set("Europe/Moscow");
    setlocale(LC_ALL, 'ru_RU');
    $expDate = strtotime($date);
    if ($expDate > time()) {
        return true;
    }

    return false;
}

/**
 * Определяет сколько прошло времени с указанной даты
 *
 * @param string $time Дата
 *
 * @return string Прошедшее время с указанной даты в формате в соответсвтии с условием
 */
function timeAgo($time)
{
    $dif = time() - strtotime($time);
    $days = floor($dif / 86400);
    $hours = floor(($dif % 86400) / 3600);
    $minutes = floor(($dif % 86400 % 3600) / 60);
    if ($days == 0 && $hours == 0 && $minutes < 1) {
        return "Только что";
    } elseif ($days == 0 && $hours == 0 && $minutes < 60) {
        return "$minutes мин. назад";
    } elseif ($days == 0 && $hours < 24) {
        return "$hours час. назад";
    }

    return date('d.m.y в H:i', strtotime($time));

}

/**
 * Рассчитывает OFFSET, а затем добавляет к переданному запросу значения LIMIT и OFFSET
 * и передаёт готовый запрос
 *
 * @param string $sql        Запроc MySQL
 * @param int    $page_items Количество для вывода строк из таблицы
 * @param int    $cur_page   Текущая страница
 *
 * @return string Готовый запрос с переданными в него LIMIT и OFFSET
 */
function pagination($sql, $page_items, $cur_page)
{
    $offset = ($cur_page - 1) * $page_items;
    $sql = $sql.' LIMIT '.$page_items.' OFFSET '.$offset;

    return $sql;
}

/**
 * Проверяет существование идентификатора лота
 *
 * @param     $link   mysqli Ресурс соединения
 * @param int $lot_id идентификатор лота для проверки
 *
 * @return bool true Идентификатор найден
 * @return bool false Идентификатор не найден
 */
function getLotByID($link, $lot_id)
{
    $sql = "SELECT * FROM lots WHERE id ='$lot_id'";
    $result = mysqli_query($link, $sql);
    $num = mysqli_num_rows($result);
    if ($num) {
        return true;
    }

    return false;
}

/**
 * Ищет пустые поля, которые нужно валидировать и
 * присваивает соответствующему ключу значение в массиве,
 * после возвращает массив ошибок
 *
 * @param array $requiredFields Поля для валидации
 * @param array $errors         Массив ошибок
 *
 * @return array $errors Массив ошибок с незаполненными полями
 */
function emptyFieldErrors($requiredFields, $errors)
{
    foreach ($requiredFields as $key => $name) {
        if (isset($key) && empty($_POST[$key])) {
            $errors[$key] = "Поле $name надо заполнить";
        }
    }

    return $errors;
}

/**
 * Выполняет запрос к бд на получение страницы лота
 *
 * @param        $link  mysqli Ресурс соединения
 * @param string $lotId Экранированный ID лота
 *
 * @return object $lotResult объект mysqli_result
 */
function getLot($link, $lotId)
{
    $lotSql
        = "SELECT lots.*, categories.categoryName, MAX(bets.value) AS current_price FROM bets
JOIN lots ON bets.lot_id = lots.id
JOIN categories ON lots.categoryId = categories.id
WHERE lots.id = '$lotId'";
    $lotResult = mysqli_query($link, $lotSql);

    return $lotResult;
}

/**
 * Выполняет запрос к бд на получение ставок лота по его ID
 *
 * @param        $link  mysqli Ресурс соединения
 * @param string $lotId Экранированный ID лота
 *
 * @return object $betsResult объект mysqli_result
 */
function getLotBets($link, $lotId)
{
    $betsSql = "SELECT user_id, users.name, bets.value, bets.dt_add FROM bets
    JOIN users ON user_id = users.id
    WHERE bets.lot_id = '$lotId' ORDER BY dt_add DESC";
    $betsResult = mysqli_query($link, $betsSql);

    return $betsResult;
}

/**
 * Выполняет запрос к бд на получение количества ставок лота по его ID
 *
 * @param        $link  mysqli Ресурс соединения
 * @param string $lotId Экранированный ID лота
 *
 * @return object $betsCountResult объект mysqli_result
 */
function getLotBetsCount($link, $lotId)
{
    $betsCountSql
        = "SELECT COUNT(*) AS count FROM bets WHERE lot_id = '$lotId'";
    $betsCountResult = mysqli_query($link, $betsCountSql);

    return $betsCountResult;
}

/**
 * Выполняет подготовленный запрос к бд на получение количества лотов,
 * которые соответствуют коду категории
 *
 * @param        $link       mysqli Ресурс соединения
 * @param string $sort_field Экранированный код катетегории
 *
 * @return object $result объект mysqli_stmt_get_result
 */
function getLotsCountByCategoryCode($link, $sort_field)
{
    $sql = "SELECT COUNT(*) as count FROM lots
    JOIN categories ON categories.id = lots.categoryId
    WHERE categories.code = (?) AND dt_end > NOW()
    ORDER BY dt_add DESC LIMIT 9";
    $stmt = db_get_prepare_stmt($link, $sql, [$sort_field]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    return $result;
}

/**
 * Выполняет подготовленный запрос к бд на получение количества лотов,
 * которые соответствуют коду категории
 *
 * @param        $link       mysqli Ресурс соединения
 * @param string $sort_field Экранированный код катетегории
 *
 * @return object $result объект mysqli_stmt_get_result
 */
function getCategoryNameByCode($link, $sort_field)
{
    $sql = "SELECT categoryName FROM categories
    WHERE code = (?) ";
    $stmt = db_get_prepare_stmt($link, $sql, [$sort_field]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    return $result;
}

/**
 * Выполняет подготовленный запрос к бд на получение лотов,
 * которые соответствуют коду категории
 *
 * @param        $link         mysqli Ресурс соединения
 * @param string $sort_field   код категории
 * @param int    $page_items   количество лотов на страницу
 * @param int    $current_page текущая страница
 *
 * @return object $result объект mysqli_stmt_get_result
 */
function getLotsByCategoryCode($link, $sort_field, $page_items, $current_page)
{
    $sql
        = "SELECT lots.id, lot_name, st_price, path, dt_end, categories.categoryName  FROM lots
    JOIN categories ON categories.id = lots.categoryId
    WHERE categories.code =(?) AND dt_end > NOW()
    ORDER BY dt_add DESC ";
    $stmt = db_get_prepare_stmt($link,
        pagination($sql, $page_items, $current_page),
        [$sort_field]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    return $result;
}

/**
 * Выполняет подготовленный запрос к бд на списка ставок
 * залогиненного пользователя
 *
 * @param        $link  mysqli Ресурс соединения
 * @param string $user  Экранированное значение ID залогиненного пользователя
 *
 * @return object $result объект mysqli_stmt_get_result
 */
function getUserBets($link, $user)
{
    $sql
        = "SELECT bets.dt_add, bets.value, lots.id AS lot_id, lots.lot_name, lots.path, lots.dt_end, lots.winner_id, categories.categoryName, (SELECT users.contacts FROM users JOIN lots ON lots.autor_id = users.id WHERE lots.id = lot_id) AS contacts FROM bets
        JOIN lots ON bets.lot_id = lots.id
        JOIN users ON bets.user_id = users.id
        JOIN categories ON lots.categoryId = categories.id
        WHERE bets.user_id = ? AND bets.value IN (SELECT MAX(bets.value) FROM bets GROUP BY lot_id, user_id)
        ORDER BY bets.dt_add DESC ";

    $stmt = db_get_prepare_stmt($link, $sql, [$user]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    return $result;
}

/**
 * Выполняет запрос к бд на поиск ID пользователя с заданным email
 *
 * @param        $link  mysqli Ресурс соединения
 * @param string $email email пользователя
 *
 * @return object $result объект mysqli_query
 */
function getUserIDByEmail($link, $email)
{
    $sql = "SELECT id FROM users WHERE email = '$email'";
    $result = mysqli_query($link, $sql);

    return $result;
}

/**
 * Выполняет запрос в бд на добавление нового пользователя с указанными данными
 *
 * @param        $link     mysqli Ресурс соединения
 * @param string $email    email пользователя
 * @param string $name     имя пользователя
 * @param string $password пароль пользователя
 * @param string $contacts контакты пользователя
 *
 * @return object $result объект mysqli_stmt_get_result
 */
function insertUser($link, $email, $name, $password, $contacts)
{
    $sql
        = 'INSERT INTO users (dt_reg, email, name, password, contacts) VALUES (NOW(), ?, ?, ?, ?)';
    $stmt = db_get_prepare_stmt($link, $sql,
        [$email, $name, $password, $contacts]);
    $result = mysqli_stmt_execute($stmt);

    return $result;
}

/**
 * Выполняет запрос к бд на поиск данных пользователя с заданным email
 *
 * @param        $link  mysqli Ресурс соединения
 * @param string $email email пользователя
 *
 * @return object $result объект mysqli_query
 */
function getUserByEmail($link, $email)
{
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($link, $sql);

    return $result;
}

/**
 * Выполняет запрос на поиск последних добавленных 9 лотов
 *
 * @param        $link  mysqli Ресурс соединения
 *
 * @return object $result объект mysqli_query
 */
function getLastLots($link)
{
    $sql
        = "SELECT lots.id, lot_name, st_price, path, dt_end, categories.categoryName  FROM lots
    JOIN categories ON categories.id = lots.categoryId
    WHERE dt_end > NOW()
    ORDER BY dt_add DESC LIMIT 9";
    $result = mysqli_query($link, $sql);

    return $result;
}

/**
 * Выполняет запрос на поиск лотов, время которых истекло,
 * пользователю с максимальной ставкой становится победителем
 *
 * @param        $link  mysqli Ресурс соединения
 *
 * @return object $result объект mysqli_query
 */
function getWinners($link)
{
    $sql = "SELECT bets.* FROM bets
    WHERE lot_id IN (SELECT id FROM lots WHERE dt_end < NOW() AND winner_id IS NULL)
    AND bets.value IN (SELECT MAX(bets.value) FROM bets GROUP BY lot_id)";
    $result = mysqli_query($link, $sql);

    return $result;
}

/**
 * Выполняет запрос на добавление к лоту победителя
 *
 * @param        $link  mysqli Ресурс соединения
 *
 * @return object $result объект mysqli_query
 */
function updateWinners($link, $user_id, $lot_id)
{
    $sql = "UPDATE lots SET winner_id = {$user_id} WHERE id = {$lot_id}";
    $result = mysqli_query($link, $sql);

    return $result;
}

/**
 * Выполняет запрос на получение данных победителя
 *
 * @param        $link  mysqli Ресурс соединения
 *
 * @return object $result объект mysqli_query
 */
function getWinnersData($link, $lot_id)
{
    $sql
        = "SELECT lots.id, lots.lot_name AS lot_name, users.email, users.name AS user_name FROM lots
    JOIN users ON lots.autor_id = users.id
    WHERE lots.id = {$lot_id}";
    $result = mysqli_query($link, $sql);

    return $result;
}

/**
 * Выполняет подготовленный запрос к бд на получение количества лотов,
 * которые соответствуют написанному в поисковой строке
 *
 * @param        $link       mysqli Ресурс соединения
 * @param string $sort_field поисковой запрос
 *
 * @return object $result объект mysqli_stmt_get_result
 */
function getLotsCountBySearch($link, $search)
{
    $sql = "SELECT COUNT(*) as count FROM lots "
        ."JOIN categories ON lots.categoryId  = categories.id "
        ."WHERE MATCH(lot_name, text) AGAINST(?) AND dt_end > NOW()";
    $stmt = db_get_prepare_stmt($link, $sql, [$search]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    return $result;
}

/**
 * Выполняет подготовленный запрос к бд на получение количества лотов,
 * которые соответствуют написанному в поисковой строке
 *
 * @param        $link         mysqli Ресурс соединения
 * @param string $sort_field   поисковой запрос
 * @param int    $page_items   количество лотов на страницу
 * @param int    $current_page текущая страница
 *
 * @return object $result объект mysqli_stmt_get_result
 */
function getLotsBySearch($link, $search, $page_items, $current_page)
{
    $sql
        = "SELECT lots.id, lot_name, categoryId, categoryName, st_price, path, dt_add, dt_end, text, bet_step FROM lots "
        ."JOIN categories ON lots.categoryId  = categories.id "
        ."WHERE MATCH(lot_name, text) AGAINST(?) AND dt_end > NOW() "
        ."ORDER BY dt_add DESC ";
    $stmt = db_get_prepare_stmt($link,
        pagination($sql, $page_items, $current_page), [$search]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    return $result;
}

/**
 * Выполняет подготовленный запрос к бд на публикацию лота
 *
 * @param        $link  mysqli Ресурс соединения
 * @param string $user  ID залогиненного пользователя
 * @param array  $lot   массив из полей формы
 *
 * @return object $result объект mysqli_stmt_get_result
 */
function addLot($link, $user, $lot)
{
    $sql = "INSERT INTO lots (dt_add, autor_id, lot_name, text, st_price, bet_step, dt_end, categoryId, path)
    VALUES (NOW(), {$user}, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = db_get_prepare_stmt($link, $sql, $lot);
    $result = mysqli_stmt_execute($stmt);

    return $result;
}

/**
 * Выполняет подготовленный запрос к бд на публикацию ставки
 *
 * @param        $link   mysqli Ресурс соединения
 * @param string $user   ID залогиненного пользователя
 * @param int    $lot_id ID лота
 * @param string $value  значение ставки из формы
 *
 * @return object $result объект mysqli_stmt_get_result
 */
function addBet($link, $user, $lot_id, $value)
{
    $sql
        = "INSERT INTO bets (dt_add, user_id, lot_id, value) VALUES (NOW(), ?, ?, ?)";
    $stmt = db_get_prepare_stmt($link, $sql,
        [$user, $lot_id, $value]);
    $result = mysqli_stmt_execute($stmt);

    return $result;
}

/**
 * Выполняет запрос к бд получение категорий
 *
 * @param        $link  mysqli Ресурс соединения
 *
 * @return object $result объект mysqli_query
 */
function getCategories($link)
{
    $sql = "SELECT id, categoryName, code FROM categories";
    $result = mysqli_query($link, $sql);

    return $result;
}
