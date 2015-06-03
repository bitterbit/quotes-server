<?php

class AddLikeController implements iController{

    function handle(){

        if(isset($_GET['id']) == false)
            return;

        $quoteDB = new SmartQuoteDBHandler();
        $quoteDB->addLike($_GET['id']);
    }

}