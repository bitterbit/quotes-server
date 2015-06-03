<?php

define('DEFAULT_AUTHOR_ICON_URL', 'http://gtapps.net/quotes/up.png');
define('ICON_SIZE', 300);

class Quote {

    public $id;
    public $text;
    public $author;
    public $icon_url;

    public function __construct($author, $text){
        $this->text = $this->stripString($text);
        $this->author = $this->stripString($author);
        $this->id = md5($this->text);
        $this->icon_url = $this->generate_artist_icon_url($author);
    }

    public function toJson(){
        $quote_object = new stdClass();
        $quote_object->author = $this->author;
        $quote_object->id = $this->id;
        $quote_object->text = $this->text;
        $quote_object->artist_icon_url = $this->icon_url;

        $json_quote = json_encode((array) $quote_object);

        return $json_quote;
    }

    public function hasAuthor(){
        return ($this->author != DEFAULT_AUTHOR);
    }

    public function hasAuthorIcon(){
        return ($this->icon_url != DEFAULT_AUTHOR_ICON_URL);
    }

    private static function generate_artist_icon_url($author){
        $author = str_replace(' ', '_', $author);
        $url = "http://en.wikipedia.org/w/api.php?action=query&prop=pageimages&format=json&pithumbsize=".ICON_SIZE."&titles=".$author;

        $response = file_get_contents($url);

        $json_obj = json_decode($response, true);

        $json_obj = $json_obj['query'];
        $json_obj = $json_obj['pages'];

        foreach ($json_obj as $value) {
            $json_obj = $value['thumbnail'];
            $source = $json_obj['source'];
            if($source !== null)
                return $source;
        }

        return DEFAULT_AUTHOR_ICON_URL;
    }

    /**
     * String a string from all the html entities
     * @param $string
     * @return string
     */
    private function stripString($string){
		$string = str_replace('"', ' ', $string);
        $string = str_replace("'", '', $string);
		$string = str_replace(";", ', ', $string);
		$string = str_replace("`", '', $string);
        $string = str_replace("-", ' ', $string);

        $string = strip_tags($string);
        //$string = htmlentities($string);

		$string = str_replace("&#39, ", '', $string);

        return $string;
    }
	
	public function getText(){
		return $this->text;
	}
} 