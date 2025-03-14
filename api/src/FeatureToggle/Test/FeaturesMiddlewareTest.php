<?php

declare(strict_types=1);

namespace App\FeatureToggle\Test;

use App\FeatureToggle\FeaturesMiddleware;
use App\FeatureToggle\FeatureSwitch;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Factory\ResponseFactory;
use Slim\Psr7\Factory\ServerRequestFactory;

/**
 * @internal
 */
#[CoversClass(FeaturesMiddleware::class)]
final class FeaturesMiddlewareTest extends TestCase
{
    public function testEmpty(): void
    {
        $switch = $this->createMock(FeatureSwitch::class);
        $switch->expects(self::never())->method('enable');
        $switch->expects(self::never())->method('disable');

        $middleware = new FeaturesMiddleware($switch, 'X-Features');

        $request = self::createRequest();

        $handler = self::createStub(RequestHandlerInterface::class);
        $handler->method('handle')->willReturn($source = self::createResponse());

        $response = $middleware->process($request, $handler);

        self::assertSame($source, $response);
    }

    public function testWithFeatures(): void
    {
        $switch = $this->createMock(FeatureSwitch::class);

        $variants = [['ONE'], ['TWO'], ['THREE']];

        $switch->expects(self::exactly(\count($variants)))->method('enable')->willReturnCallback(
            static function (mixed ...$params) use (&$variants): void {
                foreach ($variants as $key => $variant) {
                    if ($params === $variant) {
                        unset($variants[$key]);
                        return;
                    }
                }
                self::assertContains($params, $variants);
            }
        );

        $switch->expects(self::once())->method('disable')->with('FOUR');

        $middleware = new FeaturesMiddleware($switch, 'X-Features', 'features');

        $request = self::createRequest()
            ->withHeader('X-Features', 'ONE, TWO, !THREE, FOUR')
            ->withCookieParams(['features' => 'ONE, THREE, !FOUR']);

        $handler = self::createStub(RequestHandlerInterface::class);
        $handler->method('handle')->willReturn($source = self::createResponse());

        $response = $middleware->process($request, $handler);

        self::assertSame($source, $response);
    }

    private static function createResponse(): ResponseInterface
    {
        return (new ResponseFactory())->createResponse();
    }

    private static function createRequest(): ServerRequestInterface
    {
        return (new ServerRequestFactory())->createServerRequest('GET', 'http://test');
    }
}
