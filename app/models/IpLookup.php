<?php

namespace app\models;

use app\models\Model;

class IpLookup extends Model
{
    private string $endpoint = 'ip.json';

    /**
     * Retrieves location data from API by IP address and returns city
     * @param string $ip
     * @return string
     */
    public function getLocationByIp(string $ip): string
    {
        $url = $this->getApiUrl($this->endpoint, $ip);
        $data = $this->fetchData($url);
        return $data['city'];
    }
}
