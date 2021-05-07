<?php
// https://gateway.marvel.com:443/v1/public/characters?name=ironman&apikey=68262693525fbb9a71bf9d03ccd32af7
class Request {

    private $apikey;
    private $privatekey;
    private $ts;
    private $hash;

    public function __construct(){

      $this->apikey = '68262693525fbb9a71bf9d03ccd32af7';
      $this->privatekey = 'fec77bfb5c8af69f5e0670b9bbdd50420a370590';
      $this->ts = time();
      $this->hash = md5($this->ts. $this->privatekey.  $this->apikey);

    }

    public function getMethod($uri){

        $curl = curl_init($uri. '&apikey=' .  $this->apikey. '&hash='. $this->hash.  '&ts='. $this->ts);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
          'Content-Type: application/json'
        ]);
        // $response = json_decode(curl_exec($curl));
        $response = json_decode(curl_exec($curl));
        return $response;
        
    }
}

