<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property  integer $owner_query_id
 * @property  string $query_hash
 * @property  integer $price_st
 * @property  integer $price_ad
 * @property  integer $price_mx
 * @property  string $airplaneName
 * @property  string $destinationportName
 * @property  string $origincityName
 * @property  string $destinationcityName
 * @property  Carbon $departuretime
 * @property  Carbon $departuredate
 * @property  Carbon $arrivaltime
 * @property  Carbon $arrivaldate
 * @property  $data
 */
class TicketData extends Model
{
    use HasFactory;

    const DEFAULT_PARAMS = [
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
    protected $casts = [
        'departuretime' => 'datetime',
        'departuredate' => 'date',
        'arrivaltime' => 'datetime',
        'arrivaldate' => 'date',
    ];
    protected $fillable = [
        'owner_query_id',
        'query_hash',
        'price_st',
        'price_ad',
        'price_mx',
        'airplaneName',
        'destinationportName',
        'origincityName',
        'destinationcityName',
        'departuretime',
        'departuredate',
        'arrivaltime',
        'arrivaldate',
        'data',
    ];
}
