<?php

namespace Tests\Unit;

use App\Services\EncoderService;
use Illuminate\Foundation\Testing\TestCase;
use Mockery;

class UrlEncoderControllerTest extends TestCase
{
    protected EncoderService $encoderServiceMock;

    public function setUp(): void
    {
        parent::setUp();
        $this->encoderServiceMock = Mockery::mock(EncoderService::class);
        $this->app->instance(EncoderService::class, $this->encoderServiceMock);
    }

    public function testEncodeReturnsSuccessResponse()
    {
        $shortenedData = [
            'shortenedValue' => 'abc123',
            'originalValue' => 'https://example.com'
        ];

        $this->encoderServiceMock
            ->shouldReceive('encode')
            ->once()
            ->with('https://example.com')
            ->andReturn($shortenedData);

        $response = $this->json('POST', '/api/encode', ['url' => 'https://example.com']);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'shortUrl' => 'abc123',
                'originalUrl' => 'https://example.com'
            ]);
    }

    public function testEncodeHandlesValidationError()
    {
        $response = $this->json('POST', '/api/encode');

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['url']);
    }

    public function testDecodeReturnsOriginalUrl()
    {
        $originalUrl = 'https://example.com';
        $this->encoderServiceMock
            ->shouldReceive('decode')
            ->once()
            ->with('abc123')
            ->andReturn($originalUrl);

        $response = $this->json('POST', '/api/decode', ['key' => 'abc123']);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'originalUrl' => $originalUrl
            ]);
    }

    public function testDecodeReturnsNotFound()
    {
        $this->encoderServiceMock
            ->shouldReceive('decode')
            ->once()
            ->with('abc123')
            ->andReturnNull();

        $response = $this->json('POST', '/api/decode', ['key' => 'abc123']);

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'Short URL not found.'
            ]);
    }
}
