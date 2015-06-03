<?php

class TEQuoteGrabber implements iGrabber{

    function grabQuote(){

        $request = "http://thinkexist.com/rss.asp?special=random";
        $httpProxy = new HttpProxiedRequest();
        $response = $httpProxy->httpRequest($request);

        $xml = simplexml_load_string($response);
        $json = json_encode($xml);
        $array = json_decode($json,TRUE);

        $raw_quotes_array = $array['channel']['item'];

        $quotes = array();

        foreach($raw_quotes_array as $row){
            if(strlen($row['description']) <= MAX_CHARACTERS && $row['description']!=null && $row['title']!=null)
                $quotes[] = new Quote($row['title'], $row['description']);
        }

        $rand = rand(0, count($quotes)-1);
        return $quotes[$rand];
    }

}