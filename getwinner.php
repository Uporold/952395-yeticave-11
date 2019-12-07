<?php
require_once './vendor/autoload.php';
require_once 'init.php';
require_once 'functions.php';

$transport = (new Swift_SmtpTransport('smtp.mailtrap.io', 2525))
    ->setUsername('04b5d112c4945a')
    ->setPassword('e1a35afe86a578');
$mailer = new Swift_Mailer($transport);
$winners = mysqli_fetch_all(getWinners($link), MYSQLI_ASSOC);

if (count($winners)) {
    foreach ($winners as $winner) {
        updateWinners($link, $winner['user_id'], $winner['lot_id']);
        $data = mysqli_fetch_assoc(getWinnersData($link, $winner['lot_id']));
        $mail_content = include_template('email.php', compact('data'));
        $message = (new Swift_Message('Ваша ставка победила'))
            ->setFrom(['5485543590-cf457f@inbox.mailtrap.io' => 'YetiCave'])
            ->setTo([$data['email'] => $data['user_name']])
            ->setBody($mail_content, 'text/html');
        $result = $mailer->send($message);
    }
}
