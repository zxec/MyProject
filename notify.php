<?php

require_once('helpers.php');
require_once('linkDatabase.php');
require_once('init.php');
require_once('session.php');
require_once('vendor/autoload.php');

$usersId = dbGetResult($mysqli, 'SELECT `user_id`, `user_email`, `user_name` FROM `tasks`, `users` WHERE `user_id` = `users`.`id` AND `completed` = (?) AND `tasks`.`date` = CURRENT_DATE() GROUP BY `user_id`', array(0));

if (!empty($usersId)) {
    $transport = (new Swift_SmtpTransport($smtp, $port))
        ->setUsername($loginMail)
        ->setPassword($passwordMail)
        ->setEncryption($encryption);

    $mailer = new Swift_Mailer($transport);

    foreach ($usersId as $key => $value) {
        $data = preparingDataOutput(dbGetResult($mysqli, 'SELECT `task_name`, `date` FROM `tasks` WHERE `user_id` = (?) AND `completed` = (?) AND `tasks`.`date` = CURRENT_DATE()', array($value['user_id'], 0)));

        $message = (new Swift_Message($subjectMail))
            ->setFrom([$loginMail => $title])
            ->addTo($value['user_email'], $value['user_name']);
        $messageText = 'Уважаемый, ' . $value['user_name'] . ".\n";

        foreach ($data as $key2 => $value2) {
            $messageText .= 'У вас запланирована задача ' . $value2['task_name'] . ' на ' .  $value2['date'] . ".\n";
        }

        $message->setBody($messageText, 'text/plain');
        $result = $mailer->send($message);
    }
}
$mysqli->close();

header('Location: index.php');
