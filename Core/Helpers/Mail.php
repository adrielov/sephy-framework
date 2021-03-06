<?php

namespace Core\Helpers;

use Core\Config;
use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;

class Mail
{
    public static function sendMail($data)
    {
        $transport = Swift_SmtpTransport::newInstance(Config::get('smtp.server'), Config::get('smtp.port'))
            ->setUsername(Config::get('smtp.username'))
            ->setPassword(Config::get('smtp.password'));

        $mailer = Swift_Mailer::newInstance($transport);
        $message = Swift_Message::newInstance($data['subject'])
            ->setFrom([Config::get('smtp.username') => Config::get('framework.title')])
            ->setTo([$data['to']])
            ->setContentType('text/html')
            ->setBody($data['text']);

        return $mailer->send($message);
    }
}
