<?php

class SmartQuoteController implements iController {

    private $smartQuoteDB;

    public function __construct(){
        $this->smartQuoteDB = new SmartQuoteDBHandler();
    }


    function handle (){

        $risk = 0.01;
        if(isset($_GET['risk']))
            $risk = $_GET['risk'];

        echo $this->safeGetSmartQuote($risk)->toJson();
    }


    function safeGetSmartQuote($risk){
        try{
            return $this->getSmartQuote($risk);
        }
        catch (NoQuoteFound $e){
            $this->getRandomQuote();
        }
    }

    /**
     * @param $risk double Represents the Risk taken while choosing a quote, must be between 0.01 to 0.99 where 1 is the highest risk
     * @return Quote returns a quote
     */
    function getSmartQuote($risk){

        if($this->shouldGetNewQuote($risk)){
            return $this->smartQuoteDB->getRandomNewQuote();
        }
        else{
            return $this->smartQuoteDB->getOldQuote($risk);
        }
    }

    function shouldGetNewQuote($risk){
        // The divider. the higher this is, the lower the chances of getting a new quote are.
        $divider = 3.0;

        // The chance that we will get a new quote
        $chance = (1 / ($risk / $divider)) * 100;

        return rand(0, $chance) == 0;
    }

    function getRandomQuote(){
        $controller = new RandomQuoteController();
        $controller->handle();
    }

}