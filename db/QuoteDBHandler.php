<?php

define("DB_TABLE_QUOTES", "quotes");
define("MAX_QUOTE_LENGTH", 120);

class QuoteDBHandler{

    private $dbHandler;

    public function __construct(){
        $this->dbHandler = new DBHandler();
    }

    public function getDbHandler(){
        return $this->dbHandler;
    }

    public function getRandomQuote(){

        $sql = "SELECT * FROM ".DB_TABLE_QUOTES." ORDER BY RAND() LIMIT 1";

        $res = $this->dbHandler->query($sql, null);
        $res = $res[0];
        return new Quote($res['author'], $res['text']);
    }

    public function isQuoteInDB($quote){
        $id = $quote->id;
        return $this->isQuoteIdInDB($id);
    }

    public function isQuoteIdInDB($id){
        $sql = "SELECT id FROM ".DB_TABLE_QUOTES." WHERE id=:id";
        $params = array(":id" => $id);
        $result = $this->dbHandler->query($sql, $params);
        return count($result) > 0;
    }

    /**
     * Get the total number of likes for all the quotes in the database
     * @return float total number of likes
     */
    public function getTotalLikes(){
        //SELECT SUM(Quantity) AS TotalItemsOrdered FROM OrderDetails;
        $sql = "SELECT SUM(likes) AS total_likes FROM ".DB_TABLE_QUOTES;
        $result = $this->dbHandler->query($sql);
        $result = $result[0];
        return (double)$result ['total_likes'];
    }

    /**
     * Get the total number of views for all the quotes in the database
     * @return float total number of views
     */
    public function getTotalViews(){
        $sql = "SELECT SUM(views) AS total_views FROM ".DB_TABLE_QUOTES;
        $result = $this->dbHandler->query($sql);
        $result = $result[0];
        return (double)$result ['total_views'];
    }
}