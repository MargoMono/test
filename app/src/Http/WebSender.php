<?php

namespace App\Http;

use GuzzleHttp\Client;

class WebSender
{
    /**
     * @param Client
     */
    private $client;

    /**
     * @param string $url
     */
    public function __construct(string $url)
    {
        $this->client = new Client([
            'base_uri' => $url
        ]);
    }

    /**
     * @param string $message
     */
    public function sendRequest(string $message)
    {
        $this->client->request('POST', '', [
            'form_params' => [
                'body' => $message
            ]
        ]);
    }
}
