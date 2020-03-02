<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class News extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'task:news';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Retrieve top headlines and post to discord.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     * http://newsapi.org/v2/top-headlines?country=us&apiKey=ed63e5d9f6534131a5352c35a85a9baa
     * @return mixed
     */
    public function handle()
    {
        $curl = curl_init("https://newsapi.org/v2/top-headlines?country=us&apiKey=ed63e5d9f6534131a5352c35a85a9baa");
        $response = json_decode(curl_exec($curl));
        Log::info($response);

        $data = array(
            "content" => $response['articles'][0],
            "username" => "News"
        );
        $curlDisc = curl_init("nothing");
        curl_setopt($curlDisc, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curlDisc, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($curlDisc, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
        curl_setopt($curlDisc, CURLOPT_RETURNTRANSFER, true);
        return curl_exec($curlDisc);
    }
}
