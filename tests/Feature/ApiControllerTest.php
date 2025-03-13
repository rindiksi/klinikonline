<?php

namespace Tests\Feature;

use App\Helpers\CurlHelper;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Mockery;
use Tests\TestCase;

class ApiControllerTest extends TestCase
{
    public function test_fetch_data_success()
    {
        $mockCurl = Mockery::mock(CurlHelper::class);
        $mockCurl->shouldReceive('execute')
                 ->once()
                 ->andReturn(json_encode([
                     ['id' => 1, 'title' => 'Test Title', 'body' => 'Test Body']
                 ]));

        $this->app->instance(CurlHelper::class, $mockCurl);

        $response = $this->get('/api/fetch-data');

        $response->assertStatus(Response::HTTP_OK)
                 ->assertJsonStructure([
                     'status',
                     'data' => [['id', 'title', 'body']]
                 ]);
    }

    public function test_fetch_data_timeout()
    {
        $mockCurl = Mockery::mock(CurlHelper::class);
        $mockCurl->shouldReceive('execute')
                 ->once()
                 ->andReturn(false); // Simulasi timeout

        $this->app->instance(CurlHelper::class, $mockCurl);

        $response = $this->get('/api/fetch-data');

        $response->assertStatus(500)
                 ->assertJsonStructure(['error']);
    }
}
