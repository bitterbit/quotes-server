<?php

class NickeysurfQuoteGrabber implements iGrabber{

    private $url = 'http://nickeysurf.com/quotes/q-remote.php';

    function grabQuote(){

        $httpProxy = new HttpProxiedRequest();
        $response = $httpProxy->httpRequest($this->url);

        $quote = $this->getQuoteTextSafe($response);
        $author = $this->getAuthorSafe($response);

        return new Quote($author, $quote);
    }

    private function getQuoteTextSafe($html){
        try{
            return $this->getQuoteText($html);
        }
        catch(Exception $e){
            return '';
        }
    }

    private function getAuthorSafe($html){
        try{
            return $this->getAuthor($html);
        }
        catch(Exception $e){
            return DEFAULT_AUTHOR;
        }
    }


    private function getQuoteText($html){
        if (preg_match_all('/<div class="qs_quotetext">.{5}(.*)&/', $html, $matches)) {
            $matches = $matches[1];
            $quote = $matches[0];
            return $quote;
        }
        else{
            throw new Exception('No quote found');
        }
    }

    private function getAuthor($html){

        if (preg_match_all('/<div class="qs_authortext">.*">(.*)..a/', $html, $matches)) {
            $matches = $matches[1];
            $author = $matches[0];
            $author = substr($author, strpos($author, '('));
            return $this->removeYearBracket($author);
        }
        else {
            throw new Exception('No Author found');
        }
    }

    private function removeYearBracket($text){
        return preg_replace("/(.*)\&#40;.*/", "$1", $text, -1);
    }
}