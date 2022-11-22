<?php
namespace App\Console\Commands;


use App\Channels\SmsChannel;
use Illuminate\Console\Command;

class TestTelegram extends Command
{
    protected $signature = 'test:telegram';
    protected $description = 'Command description';

    public function __construct()
    {
        parent::__construct();
        $this->file = '$file';
    }

    public function handle()
    {
        $channel = new SmsChannel();
        $channel->WEB_SMS_BY('test','375255199907');
    }
}
