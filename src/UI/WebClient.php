<?php

include "ResponseParser.php";

class WebClient {
    function getInfo($url) {
        $ResponseParser = new ResponseParser();
        $response = file_get_contents($url);
        $info = json_decode($response, true);
        return $ResponseParser->parseInfo($info);
    }
}