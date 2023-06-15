<?php

namespace App\Telegram\MarkUp;

use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Telegram\Bot\Keyboard\Keyboard;

class CalendarKeyboard
{
    private User $user;
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function generate()
    {
        $ite = 1;
        $keyboard[] = $this->getWeekDays();
        $firstDay = Carbon::parse($this->user->params['date[0]'])->startOfMonth()->weekday();
        $firstDay = $firstDay == 0 ? 6 : --$firstDay;
        $keyboard[$ite] = $this->getFirstWeekDays($firstDay);

        $iteDay = 1;
        for($i = $firstDay + 1 ; $i <= Carbon::parse($this->user->params['date[0]'])->daysInMonth; $i++){
            $keyboard[(int)$ite][] = Keyboard::inlineButton([
                'text' => $iteDay,
                'callback_data' => 'Change-Day-' . sprintf('%02d', $iteDay)
            ]);
            if(($i % 7) == 0){
                $ite++;
            }
            if($i == 7){
                $ite = 2;
            }
            $iteDay++;
        }
        $this->fillLastElement($keyboard);

        return $keyboard;

    }

    private function getFirstWeekDays($firstDay): array
    {
        if($firstDay == 0) return [];

        $emptyDays = [];
        for ($i = 0; $i < $firstDay; $i++){
            $emptyDays[$i] = Keyboard::inlineButton([
                'text' => '-',
                'callback_data' => 'Change-Day'
            ]);
        }

        return $emptyDays;
    }

    private function fillLastElement(&$keyboard)
    {
        $lastItemKey = Arr::last(array_keys($keyboard));
        $countElems = 7 - count($keyboard[$lastItemKey]);
        for($i = $countElems - 1; $i <= 6; $i++){
            $keyboard[$lastItemKey][$i] = Keyboard::inlineButton([
                'text' => '-',
                'callback_data' => 'Change-Day'
            ]);
        }
    }
    private function getWeekDays(): array
    {
        return [
            Keyboard::inlineButton([
                'text' => 'пн',
                'callback_data' => 'Change-Day'
            ]),
            Keyboard::inlineButton([
                'text' => 'вт',
                'callback_data' =>'Change-Day'
            ]),
            Keyboard::inlineButton([
                'text' => 'ср',
                'callback_data' => 'Change-Day'
            ]),
            Keyboard::inlineButton([
                'text' => 'чт',
                'callback_data' => 'Change-Day'
            ]),
            Keyboard::inlineButton([
                'text' => 'пт',
                'callback_data' => 'Change-Day'
            ]),
            Keyboard::inlineButton([
                'text' => 'сб',
                'callback_data' => 'Change-Day'
            ]),
            Keyboard::inlineButton([
                'text' => 'вс',
                'callback_data' => 'Change-Day'
            ]),
        ];
    }
}
