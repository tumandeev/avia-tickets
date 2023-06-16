<?php

namespace App\Telegram\Callback;

use App\Models\User;
use App\Telegram\MarkUp\CalendarKeyboard;
use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Objects\Update;

class ChangeMonth extends Change
{
    public function __construct($message, $argument)
    {
        parent::__construct($message, $argument);
        $this->user->params = $this->setMonthParams($this->user->params, $argument);
        $this->user->save();


        $this->deleteOldMessage();

        $keyboard = new CalendarKeyboard($this->user);
        $keyboard = $keyboard->generate();

        $reply_markup = Keyboard::make([
            'inline_keyboard' => $keyboard,
            'resize_keyboard' => true,
            'one_time_keyboard' => true,
        ]);

        $this->replyWithMessage([
            'text' => 'Выберите дату: День',
            'reply_markup' => $reply_markup
        ]);

    }
}
