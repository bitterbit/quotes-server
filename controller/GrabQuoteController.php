<?php

/**
 * Grab a quotes from the web and add the to the database
 * Class GrabQuoteCollector
 */
class GrabQuoteController implements iController{

    private $grabbers;

    function __construct(){
        $this->grabbers = array('NickeysurfQuoteGrabber','QDBQuoteGrabber', 'TEQuoteGrabber');
    }

    public function  handle(){

        $quotes = array();

        foreach ($this->grabbers as $grabber_name){
            $grabber = new $grabber_name();
            $quote = $grabber->grabQuote();
            array_push($quotes, $quote);
        }

        $quoteDB = new SmartQuoteDBHandler();

        foreach($quotes as $quote){
            $quoteDB->addQuote($quote);
			//echo $quote->getText();
        }

        echo date("Y-m-d H:i:s") . ' Added '. count($quotes). ' quotes';
    }

}