<?php
namespace App\Services;
use GuzzleHttp\Client;

class ApiBase
{
  public function __construct()
  {
    $this->client = new Client;
  }

  public function soidown(){
    $soidown = config('app.soidown');
    $res = $this->client->request('GET', $soidown);
    $result = json_decode($res->getBody());
    return $result;
  }

  
}
