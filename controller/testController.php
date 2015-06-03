<?php

class testController implements iController{

    function handle(){

        //$this->testSmartQuotes();

        //$this->testAddQuote();

        //$this->testIHQGrabber()->toJson();
        //$this->testNickeysurfQuoteGrabber();
        //$this->testQDBQuoteGrabber();
        $this->testTEGrabber();

        //$proxiedRequest = new HttpProxiedRequest();
        //var_dump( $proxiedRequest->httpRequest("http://www.google.com"));

        //echo 'started testing';

        //$proxy = new HttpProxiedRequest();
        //echo 'response: <br> ' . $proxy->httpRequest('http://thinkexist.com/rss.asp?special=random');
    }

    function testTEGrabber(){
        $grabber = new TEQuoteGrabber();
        echo $grabber->grabQuote()->toJson();
    }

    function testIHQGrabber(){
        $grabber = new IHQQuoteGrabber();
        return $grabber->grabQuote();
    }

    function testNickeysurfQuoteGrabber(){
        $grabber = new NickeysurfQuoteGrabber();
        echo $grabber->grabQuote()->toJson();
    }

    function testQDBQuoteGrabber(){
        $grabber = new QDBQuoteGrabber();
        echo $grabber->grabQuote()->toJson();
    }

    function testAddQuote(){
        $quote = $this->testIHQGrabber();
        $quoteDB = new SmartQuoteDBHandler();

        var_dump($quote);

        $quoteDB->addQuote($quote);

        return $quote;
    }

    function testSmartQuotes(){
        $quoteDB = new SmartQuoteDBHandler();
        $quoteDB->test();
    }


}

