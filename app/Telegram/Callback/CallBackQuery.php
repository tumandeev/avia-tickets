<?php

namespace App\Telegram\Callback;

use Illuminate\Support\Str;

class CallBackQuery
{
    public function __construct(string $query, $message)
    {
        $explode = explode('-', $query);
        if(count($explode) < 3) return;
        [$action, $object, $argument] = explode('-', $query);

        new ('App\Telegram\Callback\\'."$action$object")($message, $argument);
    }
}
