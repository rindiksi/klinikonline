<?php

namespace App\Helpers;

class CurlHelper
{
    public function execute($ch)
    {
        return curl_exec($ch);
    }
}
