<?php

class NoQuoteFound extends Exception{

}

class SmartQuoteDBHandler {

    private $quoteDB;
    private $dbHandler;

    public function __construct(){
        $this->quoteDB = new QuoteDBHandler();
        $this->dbHandler = $this->quoteDB->getDbHandler();
    }

    public function test(){

        echo 'like ratio ' . $this->getLikeRatio();
        echo "<br>";
        echo "threashold " . $this->getThreshold();
        echo "<br>";

        //echo $this->getRandomNewQuote()->toJson();
        echo "<br>";

        echo $this->getOldQuote(100)->toJson();
        echo "<br>";

        /* if($this->quoteDB->isQuoteIdInDB($_GET['id']))
				echo 'yes';
		   else
				echo 'no'; */
		   //var_dump($this->quoteDB->getTotalLikes());
		   //echo 'likes' . $this->quoteDB->getTotalLikes();
		   //echo 'views' . $this->quoteDB->getTotalViews();
    }


    // TODO: implement getRandomNewQuote
    public function getRandomNewQuote(){
        // SELECT * FROM DB_TABLE WHERE views<:th ORDER BY RAND() LIMIT 1
        $th = $this->getThreshold();

        $sql = "SELECT * FROM ". DB_TABLE_QUOTES ." WHERE views<:th and CHAR_LENGTH(text)<".MAX_QUOTE_LENGTH." ORDER BY RAND() LIMIT 1";
        $params = array(":th"=>$th);
        $result = $this->dbHandler->query($sql, $params);
        $result= $result[0];
        return new Quote($result['author'], $result['text']);
    }

	// Get a trusted quote. The lower the risk, chances are that the quote will be better
    public function getOldQuote($risk){

        $th = $this->getThreshold();

        // get count
        $sql = "SELECT COUNT(id) FROM ". DB_TABLE_QUOTES ." WHERE views>=:th";

        $params = array(":th"=>$th);
        $res = $this->dbHandler->query($sql, $params);
        $res = $res[0];
        $count = $res['COUNT(id)'];

        // Get the index of the item we want to return
        $index = rand(0, ($risk /100) * $count);


        // Get the item we want to return
        $sql = "SELECT * FROM ".DB_TABLE_QUOTES." WHERE views>=:th AND CHAR_LENGTH(text)<".MAX_QUOTE_LENGTH." ORDER BY likes/views";

		$params = array(":th"=>$th);
        $result = $this->dbHandler->query($sql, $params);


        if(count($result) == 0){
            throw new NoQuoteFound();
        }

        $result = $result[$index];

        return new Quote($result['author'], $result['text']);
    }

    public function getLikeRatio(){
        return $this->quoteDB->getTotalLikes() / $this->quoteDB->getTotalViews();
    }

    public function addQuote($quote){

        if($this->quoteDB->isQuoteInDB($quote))
            return;

        $sql = "INSERT INTO ".DB_TABLE_QUOTES." (id, text, author) VALUES (:id, :text, :author)";
        $params = array(":id"=>$quote->id, ":text"=>$quote->text, ":author"=>$quote->author);
        $this->dbHandler->query($sql, $params);
    }

    public function addLike($id){

        $sql = "UPDATE ". DB_TABLE_QUOTES. " SET likes=likes+1, confidence=:confidence WHERE id=:id";

        $confidence = $this->getQuoteConfidence($id);

        $params = array(":id"=>$id, ":confidence"=>$confidence);
        $this->dbHandler->query($sql, $params);
    }

    public function addView($id){
        $sql = "UPDATE ". DB_TABLE_QUOTES. " SET views=views+1 WHERE id=:id";
        $params = array(":id"=>$id);
        $this->dbHandler->query($sql, $params);
    }

    private function getQuoteViews($id){
        $sql = "SELECT views FROM ".DB_TABLE_QUOTES." WHERE id=:id";
        $params = array(":id" => $id);
        $results = $this->dbHandler->query($sql, $params);
        $results = $results[0];
        return $results['views'];
    }

    /**
     * Get an indicator to how confidence we are in a quote
     * @param $id string the id of the wanted quote
     * @return float returns an number that indicates how much confidence we have in a quote where 1 is the max and 0 is the min
     */
    private function getQuoteConfidence($id){
        return $this->getConfidence($this->getQuoteViews($id));
    }

    /**
     * Calculate how confidence we are in a quote according to how many views it has
     * @param $views int the amount of views the quote has
     * @return float returns the amount of confidence we have in the quote
     */
    private function getConfidence($views){
        $confidence = $views / $this->getThreshold();

        // Confidence cant be above 1, if it is it means we trust this quote and it will be equal to 1
        return min($confidence, 1);
    }

    /**
     * Get the threshold from which a quote can be called old(trusted).
     * The threshold is compared to the quote confidence [ min(views/threshold, 1) ]
     * @return float
     */
    private function getThreshold(){
        return ( 10/$this->getLikeRatio() );
    }


}