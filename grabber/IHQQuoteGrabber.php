<?php

/**
 * Grab quotes from I Heart quotes
 * Class IHQQuoteGrabber
 */
class IHQQuoteGrabber implements iGrabber{

    function grabQuote(){

        $request = "http://iheartquotes.com/api/v1/random?format=json&max_characters=".MAX_CHARACTERS;

        $httpProxy = new HttpProxiedRequest();
        $response = $httpProxy->httpRequest($request);

        // Decode json
        $json_object = json_decode($response);

        // Return the quote
        $quote_string = $json_object->{'quote'};
        $quote_string = trim(preg_replace('/\s\s+/', ' ', $quote_string));

        // Get the author if is in the quote
        $split = explode('--', $quote_string);
        $author = DEFAULT_AUTHOR;

        if(count($split) > 1)
            list($quote_string,$author) = $split;

        return new Quote($author, $quote_string);

    }

}