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
    $res = $this->client->request('GET', 'https://staging.arawanbet.com/api/lotto/rewardsoidown');
    $result = json_decode($res->getBody());
    return $result;
  }

  
}
