<?php

namespace App\Services;

use Illuminate\Database\Capsule\Manager as Capsule;

class Boot
{
    public static function bootDb()
    {
        // Init Eloquent ORM Connection
        $capsule = new Capsule();
        try {
            // Set default timezone
            date_default_timezone_set($_ENV['timeZone']);
            View::$beginTime = microtime(true);
            // Establish connection
            $capsule->addConnection(Config::getDbConfig());
            $capsule->getConnection()->getPdo();

        } catch (\Exception $e) { // Catch exception
            // HTTP response code 500 - Internal Server Error
            http_response_code(500);

            // Output error message
            echo "Could not connect to the database. " . $e->getMessage();

            // Exit script
            exit();
        }

        // Set as global connection
        $capsule->setAsGlobal();

        // Boot Eloquent ORM
        $capsule->bootEloquent();

        // Set database connection for Views
        View::$connection = $capsule->getDatabaseManager();

        // Enable query log
        $capsule->getDatabaseManager()->connection('default')->enableQueryLog();
    }
}
