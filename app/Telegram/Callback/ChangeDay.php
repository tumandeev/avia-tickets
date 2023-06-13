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
dd($this->user->params);
    }
}
