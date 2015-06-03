<?php

class AddViewController implements iController{

    function handle(){

        if(isset($_GET['id']) == false)
            return;

        $quoteDB = new SmartQuoteDBHandler();
        $quoteDB->addView($_GET['id']);
    }

}