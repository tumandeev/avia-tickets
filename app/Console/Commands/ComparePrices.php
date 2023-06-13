<?php

namespace App\Console\Commands;

use App\Models\TicketData;
use App\Models\User;
use App\Services\TicketsDataService;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Telegram\Bot\Laravel\Facades\Telegram;

class ComparePrices extends Command
{
    protected $signature = 'compare:prices';

    public function handle()
    {
        dd('test');
           
        User::query()->chunk(50, function ($chunk){
            $chunk->each(function ($user){
                /** @var User $user */
                $ticketService = new TicketsDataService($user);

                foreach (Arr::first(array:$ticketService->getData(), default: []) as $ticket){

                    $dataHistory = TicketData::where('query_hash', $user->getHashParams())
                        ->where('departuretime', Carbon::parse($ticket['departuredate'] . ' ' . $ticket['departuretime']))
                        ->orderByDesc('created_at')
                        ->first();

                    if((int)$ticket['prices']['ST'] > $dataHistory?->price_st){
                        Telegram::sendMessage([
                            'chat_id' => $user->tg_id,
                            'text' => "Стоимость билета на $ticket[departuredate] в $ticket[departuretime] поднялась и составляет " . $ticket['prices']['ST'] . "р за базовый тариф"
                        ]);
                    }

                    if((int)$ticket['prices']['ST'] < $dataHistory?->price_st){
                        Telegram::sendMessage([
                            'chat_id' => $user->tg_id,
                            'text' => "Стоимость билета на $ticket[departuredate] в $ticket[departuretime] упала и составляет " . $ticket['prices']['ST'] . "р за базовый тариф"
                        ]);
                    }

                    if((int)$ticket['prices']['ST'] == $dataHistory?->price_st && $user->send_if_price_not_changed){

                        Telegram::sendMessage([
                            'chat_id' => $user->tg_id,
                            'text' => "Стоимость билета на $ticket[departuredate] в $ticket[departuretime] не изменилас и составляет " . $ticket['prices']['ST'] . "р за базовый тариф"
                        ]);
                    }

                    $ticketService->saveData($ticket);
                }

                sleep(2);
            });
        });
    }
}
