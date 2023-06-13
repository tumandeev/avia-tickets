<?php

namespace App\Telegram\Callback;

use App\Models\User;
use Illuminate\Support\Carbon;
use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Objects\Update;

class Change
{
    protected Update $message;
    protected string $argument;
    protected User $user;
    public function __construct($message, $argument)
    {
        $this->message = $message;
        $this->argument = $argument;
        $this->user = User::where('tg_id',$this->message->callback_query->from->id)->first();
    }

    protected function setYearParams($params, $value)
    {
        $date = explode('.',$params['date[0]']);
        $date[2] = $value;
        $date = implode('.',$date);
        $params['date[0]'] = $date;
        return $params;
    }

    protected function setMonthParams($params, $value)
    {
        $date = explode('.',$params['date[0]']);
        $date[1] = $value;
        $date = implode('.',$date);
        $params['date[0]'] = $date;
        return $params;
    }


    protected function setDayParams($params, $value)
    {
        $date = explode('.',$params['date[0]']);
        $date[0] = $value;
        $date = implode('.',$date);
        $params['date[0]'] = $date;
        return $params;
    }

    protected function deleteOldMessage(): void
    {
        Telegram::deleteMessage([
            'message_id' => $this->message->callback_query->message->message_id,
            'chat_id' => $this->message->callback_query->message->chat->id,
        ]);
    }

    protected function generateDayKeyboard()
    {
        $keyboard = [];
        $ite = 0;
        for($i = 1; $i < Carbon::parse($this->user->params['date[0]'])->daysInMonth; $i++){
            if(($i % 3) == 0){
                $ite++;
            }
            if($i == 3){
                $ite = 1;
            }
            $keyboard[(int)$ite][] = Keyboard::inlineButton([
                'text' => $i,
                'callback_data' => 'Change-Day-' . sprintf('%02d', $i)
            ]);
        }

        return $keyboard;

    }
}
