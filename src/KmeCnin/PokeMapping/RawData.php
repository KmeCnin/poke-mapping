<?php

namespace KmeCnin\PokeMapping;

class RawData
{
    /** @var string $url **/
    private $url;
    
    /**
     * @param type $serverUrl
     * @param array $queryString
     */
    public function __construct($serverUrl, array $queryString = [])
    {
        $this->url = sprintf(
            "%s?%s",
            $serverUrl.'/raw_data',
            http_build_query($queryString)
        );
    }
    
    private function pull()
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($curl);
        curl_close($curl);
        return $result;
    }
    
    public function pullAsJson()
    {
        return $this->pull();
    }
    
    public function pullAsArray()
    {
        return json_decode($this->pull());
    }
    
    public function getUrl()
    {
        return $this->url;
    }
}
