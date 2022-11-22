<?php


namespace App\Services\Log;


use Illuminate\Support\Facades\Log;

class TelegramLog extends Log
{
    public static function telegram($note)
    {
        $token = '748145752:AAFqlqftefRB66X-CPQj3HIfQstfZAMLx4o';
        $chat_id = '-281493874';

        $message = '<b>' . env('APP_NAME') . '</b>' . PHP_EOL
            . '<b>' . env('APP_ENV') . '</b>' . PHP_EOL
            . '<i>Message:</i>' . PHP_EOL
            . '<code>' . $note . '</code>';

        try {
            $ids = explode(',', $chat_id);

            foreach ($ids as $id) {
                file_get_contents(
                    'https://api.telegram.org/bot' . $token . '/sendMessage?'
                    . http_build_query([
                        'text' => $message,
                        'chat_id' => $id,
                        'parse_mode' => 'html'
                    ])
                );
            }
        } catch (\Exception $e) {
            Log::error('TelegramLog bad token/chat_id.');
        }
    }
}