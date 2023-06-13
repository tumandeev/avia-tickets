<?php

namespace App\Telegram\Commands;

use App\Models\User;
use App\Services\TicketsDataService;
use Telegram\Bot\Api;

use Telegram\Bot\Commands\Command;
use Telegram\Bot\Objects\Update;

class CheckCommand extends Command
{
    protected string $name = 'check';
    protected string $description = 'Проверить рейсы на выбранную дату';
    public function handle()
    {

        $ticketService = new TicketsDataService(User::where('tg_id',$this->update->message->from->id)->first());
        $messages = [];
        foreach ($ticketService->getData() as $tickets){
            foreach ($tickets as $ticket){

                $dateString = $ticket['departuredate'] !== $ticket['arrivaldate'] ? "$ticket[departuredate] -> $ticket[arrivaldate]" : $ticket['arrivaldate'];
                $messages[] = implode("\n",[
                    'Рейс - ' . $ticket['raceCode'],
                    'Цена базовый - ' . $ticket['prices']['ST'],
                    'Цена выгодный - ' . $ticket['prices']['AD'],
                    'Цена максимум - ' . $ticket['prices']['MX'],
                    'Дата - ' . $dateString,
                    'Время - ' . "$ticket[departuretime] -> $ticket[arrivaltime]",
                    'Самолёт - ' . $ticket['airplaneName'],
                    'Аэропорт прибытие - ' . $ticket['destinationportName'],
                    'Город отправления - ' . $ticket['origincityName'],
                    'Город назначение - ' . $ticket['destinationcityName'],
                ]);
            }
        }


        if(empty($messages)){
            $this->replyWithMessage([
                'text' => 'На эту дату рейсы не найдены. Выполните /settings что бы изменить параметры поиска',
            ]);
        }else{
            foreach ($messages as $message){
                $this->replyWithMessage([
                    'text' => $message,
                ]);
            }
        }
    }
}
