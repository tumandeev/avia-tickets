<?php

namespace App\Http\Controllers;

use App\Services\TicketsDataService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use PHPHtmlParser\Dom;
use Illuminate\Support\Facades\Http;
use Symfony\Component\CssSelector\CssSelectorConverter;
use Symfony\Component\DomCrawler\Crawler;
use Telegram\Bot\Laravel\Facades\Telegram;

class TicketsDataController extends Controller
{
    public function index()
    {

        $response = Telegram::commandsHandler();
        dd($response);

    }
}
