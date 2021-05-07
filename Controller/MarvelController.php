<?php
// https://gateway.marvel.com:443/v1/public/characters?name=ironman&apikey=68262693525fbb9a71bf9d03ccd32af7
require_once('./Helpers/Request.php');

class MarvelController {

    public function __construct(){

    }

    public function getCharacters(){
        $url = 'https://gateway.marvel.com:443/v1/public/characters?';

        $getMethod = new Request();

        $response = $getMethod->getMethod($url)->data->results;

        $response = array_map(function( $data ){
          $heroe= [
            'name' => $data->name,
            'characterId' => $data->id,
            'description' => $data->description,
          ];
          return $heroe;
        },$response);
        return $response;
        
    }

    public function getColaborators($id){
        $url = 'https://gateway.marvel.com:443/v1/public/characters/'. $id . '/comics?' ;

        $getMethod = new Request();

        $response = $response = $getMethod->getMethod($url)->data->results;

        $response = array_map(function( $data ){

            $editors = array_filter($data->creators->items, function( $data ){
                return $data->role == 'editor';
            });

            $writers = array_filter($data->creators->items, function( $data ){
                return $data->role == 'writer';
            });

            $comic= [
              'editors' => json_decode(json_encode($editors),false),
              'writers' => json_decode(json_encode($writers),false),
              'comic' => $data->title,
            ];
            return $comic;
        },$response);

        return $response;
        
    }
    public function getParners($id){
        $url = 'https://gateway.marvel.com:443/v1/public/characters/'. $id . '/comics?' ;

        $getMethod = new Request();

        $response = $response = $getMethod->getMethod($url)->data->results;

        $response = array_map(function( $data ){

            $comic= [
              'comic' => $data->title,
              'characters' => $data->characters->items
            ];
            return $comic;
        },$response);

        return $response;
        
    }
}

function console_log( $data ){
  echo $data;
}