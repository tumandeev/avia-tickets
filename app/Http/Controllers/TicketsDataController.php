<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Telegram\Callback\CallBackQuery;
use App\Telegram\MarkUp\CalendarKeyboard;
use Illuminate\Support\Facades\Artisan;
use Telegram\Bot\Laravel\Facades\Telegram;

class TicketsDataController extends Controller
{
    public function index()
    {



//        $keyboard = new CalendarKeyboard(User::find(1));
//        $keyboard = $keyboard->generate();
//        dump($keyboard);



        $response = Telegram::commandsHandler();
        foreach ($response as $item){
           if($item->callback_query?->data){
               new CallBackQuery($item->callback_query?->data, $item);
           }
        }
    }
}
