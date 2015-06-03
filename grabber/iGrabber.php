<?php

define("MAX_CHARACTERS", 120);
define("DEFAULT_AUTHOR", "Unknown");

interface iGrabber{

    /**
     * Grab a quote from a source
     * @return Quote
     */
    public function grabQuote();
}