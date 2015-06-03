<?php

class QDBQuoteGrabber implements iGrabber {

    private $url = "http://www.quotedb.com/quote/quote.php?action=random_quote";

    function __construct(){
        // init curl
    }

    function grabQuote(){

        $html = $this->getResponse();

        $start_pos = strpos($html, "document.write('", 10000) + strlen("document.write('");

        $text = substr($html, $start_pos);

        $end_pos = strpos($text, "document.write") - strlen("');'");
        $text = substr($text, 0, $end_pos);

        // Trim the quote author
        $author_start_pos = strpos($html, "More quotes from", $start_pos + $end_pos) + strlen("More quotes from ") ;
        $author_end_pos = strpos($html, "');", $author_start_pos);

        $author = substr($html, $author_start_pos, $author_end_pos - $author_start_pos);
        $author = strip_tags($author);


        // If the quote is too long get a different obe
        if(strlen($text) > MAX_CHARACTERS){
            return $this->grabQuote();
        }


        return new Quote($author, $text);
    }

    function getResponse(){
        $httpProxy = new HttpProxiedRequest();
        return $httpProxy->httpRequest($this->url);
    }

}