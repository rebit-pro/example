<?php

declare(strict_types=1);

namespace Test\Functional;

class HomeTest extends WebTestCase
{
    public function testMethod(): void
    {
        $response = $this->app()->handle(
            self::json('POST', '/')
        );

        self::assertEquals(405, $response->getStatusCode());
    }

    public function testSeccuess(): void
    {
        $response = $this->app()->handle(
            self::json('GET', '/')
        );

        self::assertEquals('application/json', $response->getHeaderLine('Content-Type'));
        self::assertEquals(200, $response->getStatusCode());
        self::assertEquals('{}', (string) $response->getBody());
    }
}

