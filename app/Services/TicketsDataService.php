<?php

namespace App\Services;

use App\Models\TicketData;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

class TicketsDataService
{

    protected User $user;
    public function __construct($user)
    {
        $this->user = $user;
    }

    public function getData()
    {
        $body = $this->user->params;

        $headers = [
            "Accept-Language" =>  "ru-RU,ru;q=0.9,en-US;q=0.8,en;q=0.7",
        ];


        $result = null;

        while($result?->json() === null){
            $result = Http::withHeaders($headers)->withBody(http_build_query($body), "application/x-www-form-urlencoded;charset=UTF-8")
                ->post("https://ticket.pobeda.aero/websky/json/search-variants-mono-brand-cartesian");

            if($result?->json() === null) sleep(2);
        }

        return $this->convertData($result->json());
    }

    public function convertData($data)
    {
        $flights = [];
        if(empty($data['flights'])){
            return [];
        }
        foreach ($data['flights'] as $_flight){
            foreach($_flight['flights'] as $flight){

                $costs = Arr::where(Arr::first($data['prices']), function ($arr, $key) use($flight){
                    return $flight['id'] == $key;
                });

                $costST = Arr::first(Arr::where(Arr::first($costs), function ($arr, $key) use($flight){
                    return $arr['requestedBrands'] == 'ST';
                }));

                $costAD = Arr::first(Arr::where(Arr::first($costs), function ($arr, $key) use($flight){
                    return $arr['requestedBrands'] == 'AD';
                }));

                $costMX = Arr::first(Arr::where(Arr::first($costs), function ($arr, $key) use($flight){
                    return $arr['requestedBrands'] == 'MX';
                }));

                $resultData = [
                    'raceCode' => "$flight[carrier] $flight[racenumber]",
                    'prices' => [
                        'ST' => $costST['price'],
                        'AD' => $costAD['price'],
                        'MX' => $costMX['price'],
                    ],

                    'departuretime' => $flight['departuretime'],
                    'departuredate' => $flight['departuredate'],
                    'arrivaltime' => $flight['arrivaltime'],
                    'arrivaldate' => $flight['arrivaldate'],

                    'airplaneName' => $flight['airplaneName'],
                    'destinationportName' => $flight['destinationportName'],
                    'origincityName' => $flight['origincityName'],
                    'destinationcityName' => $flight['destinationcityName'],
                ];
                $flights[$flight['date']][$flight['id']] = $resultData;
            }
        }

        return $flights;
    }


    public function saveData($data): void
    {

        $dataHistory = TicketData::where('query_hash', $this->user->getHashParams())
            ->where('departuretime',  Carbon::parse($data['departuredate'] . ' ' . $data['departuretime']))
            ->where('created_at','>' , now()->subMinutes(20))
            ->exists();

        if($dataHistory) return;


        $ticketData = new TicketData;

        $ticketData->fill($data);
        $ticketData->query_hash = $this->user->getHashParams();
        $ticketData->departuretime = Carbon::parse($data['departuredate'] . ' ' . $data['departuretime']);
        $ticketData->price_st = $data['prices']['ST'];
        $ticketData->price_ad = $data['prices']['AD'];
        $ticketData->price_mx = $data['prices']['MX'];
        $ticketData->owner_query_id = $this->user->getKey();
        $ticketData->data = $this->user->params;
        $ticketData->save();
    }
}
