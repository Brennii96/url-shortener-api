<?php

namespace App\Services;

use App\Contracts\EncoderInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Config\Repository as Config;
use Illuminate\Support\Str;

class EncoderService implements EncoderInterface
{
    private int $cacheExpiration;
    private int $codeLength;

    public function __construct(Config $config)
    {
        $this->cacheExpiration = $config->get('url_shortener.code_cache_expiration');
        $this->codeLength = $config->get('url_shortener.shortened_code_length');
    }

    /**
     * Generate a random Base62 string.
     *
     * @param int $length
     * @return string
     */
    private function generateBase62(int $length): string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $max = strlen($characters) - 1;

        return collect(range(1, $length))
            ->map(fn() => $characters[random_int(0, $max)])
            ->implode('');
    }

    /**
     * Shorten any value by generating a unique shortened code.
     *
     * @param string $value
     * @return array
     */
    public function encode(string $value): array
    {
        if ($existingShortCode = Cache::get("original_value:$value")) {
            return [
                'shortenedValue' => $existingShortCode,
                'originalValue' => $value
            ];
        }

        $shortCode = null;
        while (!$shortCode) {
            $candidate = Str::random($this->codeLength); //$this->generateBase62($this->codeLength);
            if (Cache::add("shortened:$candidate", $value, $this->cacheExpiration)) {
                $shortCode = $candidate;
            }
        }

        Cache::put("original_value:$value", $shortCode, $this->cacheExpiration);

        return [
            'shortenedValue' => $shortCode,
            'originalValue' => $value
        ];
    }

    /**
     * Decode a shortened code back to its original value.
     *
     * @param string $shortCode
     * @return string|null
     */
    public function decode(string $shortCode): ?string
    {
        return Cache::get("shortened:$shortCode");
    }
}
