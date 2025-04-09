<?php

declare(strict_types=1);

namespace Test\Unit\Http;

use PHPUnit\Framework\TestCase;
use App\Http\JsonResponse;

/**
 * @covers App\Http\JsonResponse
 */
class JsonResponseTest extends TestCase
{
    public function testInt() : void 
    {
        $response = new JsonResponse(12);
        self::assertEquals(200, $response->getStatusCode());
        self::assertEquals('12', $response->getBody()->getContents());
    }


    #[\PHPUnit\Framework\Attributes\DataProvider('getCases')]
    public function testResponse($source, $expect) : void 
    {
        $response = new JsonResponse($source);

        self::assertEquals('application/json', $response->getHeaderLine('Content-Type'));
        self::assertEquals(200, $response->getStatusCode());
        self::assertEquals($expect, $response->getBody()->getContents());
    }

    /**
     * @return array<mixed>
     */
    public static function getCases() : array
    {
        return [
            'null' => [null, 'null'],
            'empty string' => ['', '""'],
            'number' => [12, '12'],
            'string' => ['test', '"test"'],
            'array' => [['test'], '["test"]'],
            'object' => [(object)['test' => 123], '{"test":123}'],
        ];
    }
}

