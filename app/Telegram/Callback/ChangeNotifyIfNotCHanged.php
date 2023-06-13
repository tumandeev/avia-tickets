<?php

namespace App\Telegram\Callback;

use Telegram\Bot\Laravel\Facades\Telegram;

class ChangeNotifyIfNotCHanged extends Change
{
    public function __construct($message, $argument)
    {
        parent::__construct($message, $argument);

        $this->user->send_if_price_not_changed = $argument;
        $this->user->save();
        $this->deleteOldMessage();
        Telegram::sendMessage([
            'chat_id' => $this->message->callback_query->message->chat->id,
            'text' => 'Настройки успешно сохранены',
        ]);
    }
}
