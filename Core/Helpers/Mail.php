<?php
namespace Core\Helpers;

use Core\Config;
use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;

class Mail
{

    private static $instance;

    /**
     * @return Mail
     */
    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new Mail();
        }
        return self::$instance;
    }

	/**
     * @param $data
     *
     * @return int
     */
    public static function sendMail($data) {
        $transport = Swift_SmtpTransport::newInstance(Config::get('smtp.server'), Config::get('smtp.port'))
            ->setUsername(Config::get('smtp.username'))
            ->setPassword(Config::get('smtp.password'));

        $mailer  = Swift_Mailer::newInstance($transport);
        $message = Swift_Message::newInstance($data['subject'])
            ->setFrom(array(Config::get('smtp.username') => Config::get('framework.title')))
            ->setTo(array($data['to']))
            ->setContentType("text/html")
            ->setBody($data['text']);

        return $mailer->send($message);
    }
}