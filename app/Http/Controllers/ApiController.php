<?php

namespace App\Http\Controllers;

use App\Helpers\CurlHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class ApiController extends Controller
{
    protected $curlHelper;

    public function __construct(CurlHelper $curlHelper)
    {
        $this->curlHelper = $curlHelper;
    }

    public function fetchData()
    {
        $url = 'https://jsonplaceholder.typicode.com/posts';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 2);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);

        $response = $this->curlHelper->execute($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);

        curl_close($ch);

        if ($response === false) {
            Log::error('Curl error: ' . $error);
            return response()->json(['error' => 'Request timeout or failed', 'message' => $error], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'status' => $httpCode,
            'data' => json_decode($response, true)
        ]);
    }
}
