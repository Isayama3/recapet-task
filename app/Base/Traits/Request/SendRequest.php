<?php

namespace App\Base\Traits\Request;

use Illuminate\Support\Str;

trait SendRequest
{

    public function sendAuthRequest($route, $data, $body = false)
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->post(route($route), [
            'form_params' => $data,
            'verify' => app()->isLocal() ? false : true,
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $this->bearerToken(),
            ],
        ]);
        $model_id = json_decode($response->getBody()->getContents())->data->id;
        if ($body) {
            return json_decode($response->getBody()->getContents());
        }
        return $model_id;
    }

    public function sendAuthPostRequest($route, $data = null, $body = false)
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->post($route, [
            'form_params' => $data,
            'verify' => app()->isLocal() ? false : true,
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $this->bearerToken(),
            ],
        ]);
        $model_id = json_decode($response->getBody()->getContents())->data->id;
        if ($body) {
            return json_decode($response->getBody()->getContents());
        }
        return $model_id;
    }
    
    public function sendAuthPutRequest($route, $data = null, $body = false)
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->put($route, [
            'form_params' => $data,
            'verify' => app()->isLocal() ? false : true,
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $this->bearerToken(),
            ],
        ]);
        $model_id = json_decode($response->getBody()->getContents())->data->id;
        if ($body) {
            return json_decode($response->getBody()->getContents());
        }
        return $model_id;
    }

    public function sendRequest($url, $method, $payload = null)
    {
        $client = new \GuzzleHttp\Client();
        return $client->$method($url, $payload);
    }
    public function bearerToken()
    {
        $header = request()->header('Authorization', '');
        if (Str::startsWith($header, 'Bearer ')) {
            return Str::substr($header, 7);
        }
    }
}
