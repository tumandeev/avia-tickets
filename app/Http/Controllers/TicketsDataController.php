<?php

namespace App\Http\Controllers;

use App\Telegram\Callback\CallBackQuery;
use Telegram\Bot\Laravel\Facades\Telegram;

class TicketsDataController extends Controller
{
    public function index()
    {

        $response = Telegram::commandsHandler();
        foreach ($response as $item){
           if($item->callback_query?->data){
               new CallBackQuery($item->callback_query?->data, $item);
           }
        }
        dd($response);

    }
}
