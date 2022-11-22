<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 020 20.01.19
 * Time: 2:05
 */

namespace App\Services\Support;

use Telegram\Bot\Api;

class HandlerError
{
    public static function index(\Exception $e)
    {
        if ($e->getMessage()) {
            $items = [
                'System' => env('APP_URL'),
                'Message' => $e->getMessage(),
                'File' => $e->getFile(),
                'Line' => $e->getLine(),
            ];

            $json = json_encode($items, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
            $message = '<b>' . env('APP_NAME') . '</b>' . PHP_EOL
                . '<b>' . env('APP_ENV') . '</b>' . PHP_EOL
                . '<i>Message:</i>' . PHP_EOL
                . '<code>' . $json . '</code>';

            /*file_get_contents('https://api.telegram.org/bot1048441889:AAHHBnc0HuGpRbVygMcs8YK5tCmXujQ-yIA/sendMessage?'
                . http_build_query([
                    'text' => $message,
                    'chat_id' => '-281493874',
                    'parse_mode' => 'html'
                ])
            );*/
        }
    }
}