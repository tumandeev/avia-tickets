<?php

namespace App\Console\Commands;

use App\Telegram\Callback\CallBackQuery;
use Illuminate\Console\Command;
use Telegram\Bot\Laravel\Facades\Telegram;

class TelegramBotRunner extends Command
{
    protected $signature = 'run:bot';
    protected $description = '';

    public function handle(): void
    {
        while(true){
            $response = Telegram::commandsHandler();
            foreach ($response as $item){
                if($item->callback_query?->data){
                    new CallBackQuery($item->callback_query?->data, $item);
                }
            }
            sleep(2);
        }
    }
}
