<?php

namespace App\Telegram\Commands;

use App\Models\TicketData;
use App\Models\User;
use Telegram\Bot\Commands\Command;
use Telegram\Bot\Keyboard\Keyboard;
class StartCommand extends Command
{
    protected string $name = 'start';
    protected string $description = '';
    public function handle()
    {

        $keyboard = [
            [
                Keyboard::inlineButton([
                    'text' => '2023',
                    'callback_data' => 'Change-Year-2023'
                ]),
            ],
            [
                Keyboard::inlineButton([
                    'text' => '2024',
                    'callback_data' => 'Change-Year-2024'
                ]),
            ]
        ];


        User::updateOrCreate([
            'tg_id' => $this->update->message->from->id,
        ],[
            'tg_id' => $this->update->message->from->id,
            'name' => $this->update->message->from->first_name . " " . $this->update->message->from->last_name,
            'params' => TicketData::DEFAULT_PARAMS,
        ]);

        $reply_markup = Keyboard::make([
            'inline_keyboard' => $keyboard,
            'resize_keyboard' => true,
            'one_time_keyboard' => true,
        ]);
        $reply_markup->inline();
        $reply_markup->remove();

        $this->replyWithMessage([
            'text' => 'Привет! Пока бот умеет мониторть рейсы только из Ульяновска в москву, но позже обязательно научится выбирать любые направления.',

            ]);

        $this->replyWithMessage([
            'text' => 'А пока. Выберите дату: год',
            'reply_markup' => $reply_markup
        ]);
    }
}
