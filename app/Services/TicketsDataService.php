<?php

namespace App\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

class TicketsDataService
{
    public function getData()
    {
        $body = [
            "searchGroupId" => "standard",
            "segmentsCount" => 1,
            "date[0]" => "25.09.2023",
            "origin-city-code[0]" => "ULY",
            "destination-city-code[0]" => "MOW",
            "origin-port-code[0]" => "ULY",
            "adultsCount" => 1,
            "youngAdultsCount" => 0,
            "childrenCount" => 0,
            "infantsWithSeatCount" => 0,
            "infantsWithoutSeatCount" => 0,
            "search-engine" => "aviasales",
            "redirect-id" => "jeyf16yblogw"
        ];

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

                $flights[$flight['date']][$flight['id']] = [
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
            }
        }

        return $flights;
    }
}
