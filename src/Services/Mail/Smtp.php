<?php


namespace App\Services\Mail;

use PHPMailer\PHPMailer\PHPMailer;
use App\Services\Config;
use App\Models\Setting;

class Smtp extends Base
{
    private $mail;
    private $config;

    public function __construct()
    {
        $this->config = $this->getConfig();
        $mail = new PHPMailer();
        //$mail->SMTPDebug = 3;                               // Enable verbose debug output
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = $this->config['host'];                  // Specify main and backup SMTP servers
        $mail->Port = $this->config['port'];                  // TCP port to connect to
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->CharSet = 'UTF-8';                             // Set utf-8 character set
        $mail->Username = $this->config['username'];          // SMTP username
        $mail->Password = $this->config['passsword'];         // SMTP password
        $mail->setFrom($this->config['sender'], $this->config['name']);
        if ($this->config['smtp_ssl'] == true) {
            // Enable TLS encryption, `ssl` also accepted
            $mail->SMTPSecure = ($this->config['port'] == '587' ? 'tls' : 'ssl');
        }

        $this->mail = $mail;
    }

    public function getConfig()
    {
        $configs = Setting::getClass('smtp');
        
        return [
            'host' => $configs['smtp_host'],
            'port' => $configs['smtp_port'],
            'username' => $configs['smtp_username'],
            'passsword' => $configs['smtp_password'],
            'smtp_ssl' => $configs['smtp_ssl'],
            'name' => $configs['smtp_name'],
            'sender' => $configs['smtp_sender']
        ];
    }

    public function send($to, $subject, $text, $files)
    {
        $mail = $this->mail;
        $mail->addAddress($to);     // Add a recipient
        $mail->isHTML();
        $mail->Subject = $subject;
        $mail->Body = $text;
        foreach ($files as $file) {
            $mail->addAttachment($file);
        }
        // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
        if (!$mail->send()) {
            return true;
        }
        return false;
    }
}
