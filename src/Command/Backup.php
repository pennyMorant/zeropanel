<?php

declare(strict_types=1);

namespace App\Command;

use App\Models\{
    Setting,
    User
};
use App\Services\Mail;
use App\Utils\Telegram;
use Exception;
use RuntimeException;

final class Backup extends Command
{
    public $description = <<<EOL
├─=: php xcat Backup [选项]
│ ├─ full                    - 整体数据备份

EOL;

    public function boot(): void
    {
        if (count($this->argv) === 2) {
            echo $this->description;
        } else {
            $methodName = $this->argv[2];
            if ($methodName === 'full') {
                $this->backup(true);
            } else {
                $this->backup(false);
            }
        }
    }

    public function backup($full = false)
    {
        $configs = Setting::getClass('backup');

        ini_set('memory_limit', '-1');
        $to = $configs['auto_backup_email'];
        if (is_null($to)) {
            return false;
        }
        if (! mkdir('/tmp/backup/') && ! is_dir('/tmp/backup/')) {
            throw new RuntimeException(sprintf('Directory "%s" was not created', '/tmp/backup/'));
        }
        $db_address_array = explode(':', $_ENV['db_host']);
        if ($full) {
            system('mysqldump --user=' . $_ENV['db_username'] . ' --password=' . $_ENV['db_password'] . ' --host=' . $db_address_array[0] . ' ' . (isset($db_address_array[1]) ? '-P ' . $db_address_array[1] : '') . ' ' . $_ENV['db_database'] . ' > /tmp/backup/database.sql');
        }

        system('cp ' . BASE_PATH . '/config/.config.php /tmp/backup/configbak.php', $ret);
        echo $ret;
        $backup_passwd = $configs['auto_backup_password'] === '' ? '' : ' -P ' . $configs['auto_backup_password'];
        system('zip -r /tmp/backup.zip /tmp/backup/* ' . $backup_passwd, $ret);
        $subject = Setting::obtain('website_name') . '-备份成功';
        $text = '您好，系统已经为您自动备份，请查看附件，用您设定的密码解压。';
        try {
            Mail::send($to, $subject, 'news/backup.tpl', [
                'text' => $text,
            ], [
                '/tmp/backup.zip',
            ]);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        system('rm -rf /tmp/backup', $ret);
        system('rm /tmp/backup.zip', $ret);
        if ($configs['auto_backup_notify'] == true) {           
            $messagetext = "备份工作已经完成";                
            Telegram::pushToAdmin($messagetext);               
        }
        echo 'Success ' . date('Y-m-d H:i:s', time()) . PHP_EOL;
    }
}