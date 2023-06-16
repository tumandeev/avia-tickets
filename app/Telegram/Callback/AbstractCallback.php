<?php

namespace App\Telegram\Callback;

use App\Models\User;
use Telegram\Bot\Objects\Update;

abstract class AbstractCallback
{
    use MessageTrait;
    protected Update $message;
    protected string $argument;
    protected User $user;
    public function __construct($message, $argument)
    {
        $this->message = $message;
        $this->argument = $argument;
        $this->user = User::where('tg_id',$this->message->callback_query->from->id)->first();
    }
}
