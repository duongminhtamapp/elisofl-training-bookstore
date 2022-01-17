<?php

namespace App\Helper;

class NumverifyApi
{
    private $phone;

    public function __construct(string $phone)
    {
        $this->phone = $phone;
    }

    public function getInternationalPhone()
    {
        $curl = curl_init();
        $url = $_ENV['NUMVERIFY_API_URL'];
        $data = array(
            'access_key' => $_ENV['NUMVERIFY_API_ACCESS_KEY'],
            'number' => $this->phone,
            'country_code' => $_ENV['NUMVERIFY_API_COUNTRY_CODE'],
            'format' => '1'
        );

        curl_setopt($curl, CURLOPT_URL, sprintf("%s?%s", $url, http_build_query($data)));

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        sleep(2); // prevent rate_limit_reached error
        $output = curl_exec($curl);
        curl_close($curl);

        if (isset(json_decode($output)->international_format)) {
            return json_decode($output)->international_format;
        }
        return null;
    }
}