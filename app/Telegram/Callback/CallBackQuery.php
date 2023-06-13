<?php

namespace App\Telegram\Callback;

use Illuminate\Support\Str;

class CallBackQuery
{
    public function __construct(string $query, $message)
    {
        [$action, $object, $argument] = explode('-', $query);

        new ('App\Telegram\Callback\\'."$action$object")($message, $argument);
    }
}
