<?php

namespace App\Telegram\Callback;

use App\Models\User;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Objects\Update;

trait MessageTrait
{
    protected function deleteOldMessage(): void
    {
        Telegram::deleteMessage([
            'message_id' => $this->message->callback_query->message->message_id,
            'chat_id' => $this->message->callback_query->message->chat->id,
        ]);
    }

    protected function replyWithMessage($params): void
    {
        $params = array_merge($params, [
            'chat_id' => $this->message->callback_query->message->chat->id,
        ]);

        Telegram::sendMessage($params);
    }
}
