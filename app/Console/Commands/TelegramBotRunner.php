<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Telegram\Bot\Laravel\Facades\Telegram;

class TelegramBotRunner extends Command
{
    protected $signature = 'run:bot';
    protected $description = '';

    public function handle(): void
    {
        while(true){
            Telegram::commandsHandler();
            sleep(2);
        }
    }
}
