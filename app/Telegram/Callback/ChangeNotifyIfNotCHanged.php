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
        $this->replyWithMessage([
            'text' => 'Настройки успешно сохранены',
        ]);
    }
}
