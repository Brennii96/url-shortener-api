<?php

namespace App\Http\Controllers\Api;

use App\Contracts\EncoderInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\ShortenUrlRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UrlEncoderController extends Controller
{
    /**
     * @param EncoderInterface $encoderService
     */
    public function __construct(private readonly EncoderInterface $encoderService)
    {
    }

    /**
     * @param ShortenUrlRequest $request
     * @return JsonResponse
     */
    public function encode(ShortenUrlRequest $request): JsonResponse
    {
        $validatedRequest = $request->validated();
        $shortenedUrlData = $this->encoderService->encode($validatedRequest['url']);
        return new JsonResponse([
            'success' => true,
            'shortUrl' => $shortenedUrlData['shortenedValue'],
            'originalUrl' => $shortenedUrlData['originalValue']
        ], 201);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function decode(Request $request): JsonResponse
    {
        $request->validate([
            'key' => ['required', 'string']
        ]);

        $originalUrl = $this->encoderService->decode($request->get('key'));

        if (!$originalUrl) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Short URL not found.',
            ], 404);
        }

        return new JsonResponse([
            'success' => true,
            'originalUrl' => $originalUrl,
        ]);
    }
}
