<?php

namespace App\Telegram\Callback;

use App\Models\User;
use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Objects\Update;

class ChangeDay extends Change
{
    public function __construct($message, $argument)
    {
        parent::__construct($message, $argument);
        $this->user->params = $this->setDayParams($this->user->params, $argument);
        $this->user->save();


        $this->deleteOldMessage();

        Telegram::sendMessage([
            'chat_id' => $this->message->callback_query->message->chat->id,
            'text' => 'Спасибо! Дата вылета настроена, Теперь можно проверить цену билетов на этот день. Выполните команду /check',
        ]);

        Telegram::sendMessage([
            'chat_id' => $this->message->callback_query->message->chat->id,
            'text' => 'Бот будет автоматически опрашивать сайт победы каждые 30 минут, и если цена изменится,  вы получите уведомление.',
        ]);



        $keyboard = [
            [
                Keyboard::inlineButton([
                    'text' => 'Да',
                    'callback_data' => 'Change-NotifyIfNotCHanged-1'
                ]),

                Keyboard::inlineButton([
                    'text' => 'Нет',
                    'callback_data' => 'Change-NotifyIfNotCHanged-0'
                ]),
            ]
        ];

        $reply_markup = Keyboard::make([
            'inline_keyboard' => $keyboard,
            'resize_keyboard' => true,
            'one_time_keyboard' => true,
        ]);

        $this->replyWithMessage([
            'text' => 'Уведомлять если цена не изменилась?',
            'reply_markup' => $reply_markup
        ]);
    }
}
