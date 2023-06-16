<?php

namespace App\Telegram\Callback;



use App\Models\User;
use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Objects\Update;

class ChangeYear extends Change
{
    public function __construct($message, $argument)
    {
        parent::__construct($message, $argument);
        $this->user->params = $this->setYearParams($this->user->params, $argument);
        $this->user->save();

        $this->deleteOldMessage();


        $keyboard = [
            [
                Keyboard::inlineButton([
                    'text' => 'Январь',
                    'callback_data' => 'Change-Month-01'
                ]),

                Keyboard::inlineButton([
                    'text' => 'Февраль',
                    'callback_data' => 'Change-Month-02'
                ]),
                Keyboard::inlineButton([
                    'text' => 'Март',
                    'callback_data' => 'Change-Month-03'
                ]),
            ],
            [
                Keyboard::inlineButton([
                    'text' => 'Апрель',
                    'callback_data' => 'Change-Month-04'
                ]),
                Keyboard::inlineButton([
                    'text' => 'Май',
                    'callback_data' => 'Change-Month-05'
                ]),
                Keyboard::inlineButton([
                    'text' => 'Июнь',
                    'callback_data' => 'Change-Month-06'
                ]),
            ],
            [
                Keyboard::inlineButton([
                    'text' => 'Июль',
                    'callback_data' => 'Change-Month-07'
                ]),
                Keyboard::inlineButton([
                    'text' => 'Август',
                    'callback_data' => 'Change-Month-08'
                ]),
                Keyboard::inlineButton([
                    'text' => 'Сентябрь',
                    'callback_data' => 'Change-Month-09'
                ]),
            ],
            [
                Keyboard::inlineButton([
                    'text' => 'Октябрь',
                    'callback_data' => 'Change-Month-10'
                ]),
                Keyboard::inlineButton([
                    'text' => 'Ноябрь',
                    'callback_data' => 'Change-Month-11'
                ]),
                Keyboard::inlineButton([
                    'text' => 'Декабрь',
                    'callback_data' => 'Change-Month-12'
                ]),
            ]
        ];

        $reply_markup = Keyboard::make([
            'inline_keyboard' => $keyboard,
            'resize_keyboard' => true,
            'one_time_keyboard' => true,
        ]);

        $this->replyWithMessage([
            'text' => 'Выберите дату: месяц',
            'reply_markup' => $reply_markup
        ]);

    }
}
