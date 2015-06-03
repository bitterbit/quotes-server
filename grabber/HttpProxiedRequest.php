<?php

class HttpProxiedRequest {

    public function httpRequest($url){

        $proxy = 'http://127.0.0.1:8118/';
        $referrer = 'http://www.google.com/';
        $user_agent = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.8) Gecko/2009032609 Firefox/3.0.8';
        $show_header = 0; // Dont show the http header in the response
        $timeout = 10;

        $result = $this->getPage($proxy, $url, $referrer, $user_agent, $show_header, $timeout);

        // Return result only if no errors
        if (empty($result['ERR'])) {
            return $result['EXE'];
        }
    }

    private function getPage($proxy, $url, $referer, $agent, $header, $timeout) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_PROXY, $proxy);
        curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_REFERER, $referer);
        curl_setopt($ch, CURLOPT_USERAGENT, $agent);

        $result['EXE'] = curl_exec($ch);
        $result['INF'] = curl_getinfo($ch);
        $result['ERR'] = curl_error($ch);

        curl_close($ch);

        return $result;
    }
}