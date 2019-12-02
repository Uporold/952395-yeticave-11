<?php
require_once './vendor/autoload.php';
require_once 'init.php';
require_once 'functions.php';

$transport = (new Swift_SmtpTransport('smtp.mailtrap.io', 2525))
    ->setUsername('04b5d112c4945a')
    ->setPassword('e1a35afe86a578');
$mailer = new Swift_Mailer($transport);

$sql = 'SELECT bets.* FROM bets '
    .'WHERE lot_id IN (SELECT id FROM lots WHERE dt_end < NOW() AND winner_id IS NULL) '
    .'AND bets.value IN (SELECT MAX(bets.value) FROM bets GROUP BY lot_id)';
$result = mysqli_query($link, $sql);
$winners = mysqli_fetch_all($result, MYSQLI_ASSOC);

if (count($winners)) {
    foreach ($winners as $winner) {
        mysqli_query($link,
            'UPDATE lots SET winner_id = '.$winner['user_id'].' WHERE id = '
            .$winner['lot_id']);
        $sql
            = 'SELECT lots.id, lots.lot_name AS lot_name, users.email, users.name AS user_name FROM lots '
            .'JOIN users ON lots.autor_id = users.id '
            .'WHERE lots.id = '.$winner['lot_id'];
        $result = mysqli_query($link, $sql);
        $data = mysqli_fetch_assoc($result);
        $mail_content = include_template('email.php', compact('data'));
        $message = (new Swift_Message('Ваша ставка победила'))
            ->setFrom(['5485543590-cf457f@inbox.mailtrap.io' => 'YetiCave'])
            ->setTo([$data['email'] => $data['user_name']])
            ->setBody($mail_content, 'text/html');
        $result = $mailer->send($message);
    }
}
