<?php

class RandomQuoteController implements iController{

    private $quoteDB;

    function __construct(){
        $this->quoteDB = new QuoteDBHandler();
    }

    function handle(){
        //$quote = $this->quoteDB->getRandomQuote();
        //echo $quote->toJson();

        $grabber = new TEQuoteGrabber();
        echo $grabber->grabQuote()->toJson();
    }

}