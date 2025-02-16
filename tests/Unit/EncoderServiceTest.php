<?php

namespace Tests\Unit;

use App\Services\EncoderService;
use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Support\Facades\Cache;
use Mockery;

class EncoderServiceTest extends TestCase
{
    protected $configMock;

    private const CACHE_EXPIRATION = 60;

    public function setUp(): void
    {
        parent::setUp();
        $this->configMock = Mockery::mock('Illuminate\Config\Repository');
        $this->configMock->shouldReceive('get')->with('url_shortener.code_cache_expiration')->andReturn(self::CACHE_EXPIRATION);
        $this->configMock->shouldReceive('get')->with('url_shortener.shortened_code_length')->andReturn(6);
    }

    public function testEncodeGeneratesUniqueShortenedCode()
    {
        Cache::shouldReceive('get')
            ->with('original_value:https://example.com')
            ->andReturn(null);
        Cache::shouldReceive('add')
            ->with(Mockery::any(), 'https://example.com', self::CACHE_EXPIRATION)
            ->andReturn(true);
        Cache::shouldReceive('put')
            ->with('original_value:https://example.com', Mockery::any(), self::CACHE_EXPIRATION)
            ->andReturn(true);

        $service = new EncoderService($this->configMock);
        $result = $service->encode('https://example.com');

        $this->assertArrayHasKey('shortenedValue', $result);
        $this->assertArrayHasKey('originalValue', $result);
    }

    public function testDecodeReturnsOriginalValue()
    {
        Cache::shouldReceive('get')
            ->with('shortened:abc123')
            ->andReturn('https://example.com');

        $service = new EncoderService($this->configMock);
        $result = $service->decode('abc123');

        $this->assertEquals('https://example.com', $result);
    }

    public function testDecodeReturnsNullWhenNotFound()
    {
        Cache::shouldReceive('get')
            ->with('shortened:abc123')
            ->andReturn(null);

        $service = new EncoderService($this->configMock);
        $result = $service->decode('abc123');

        $this->assertNull($result);
    }
}
