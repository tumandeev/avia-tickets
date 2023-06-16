<?php

namespace App\Telegram\Commands;

use App\Models\TicketData;
use App\Models\User;
use App\Services\TicketsDataService;
use Telegram\Bot\Api;

use Telegram\Bot\Commands\Command;
use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Objects\Update;

class SettingsCommand extends Command
{
    protected string $name = 'settings';
    protected string $description = 'Изменить настроки';
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


        $keyboard = [
            [
                Keyboard::inlineButton([
                    'text' => 'Создать профиль',
                    'callback_data' => 'Create-Profile-new'
                ]),
            ],
        ];


        $reply_markup = Keyboard::make([
            'inline_keyboard' => $keyboard,
            'resize_keyboard' => true,
            'one_time_keyboard' => true,
        ]);
        $reply_markup->inline();



        $this->replyWithMessage([
            'text' => 'Выберите дату: год',
            'reply_markup' => $reply_markup
        ]);
    }
}
