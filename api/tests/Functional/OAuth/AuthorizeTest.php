<?php

declare(strict_types=1);

namespace Test\Functional\OAuth;

use Override;
use Test\Functional\ArraySubsetAsserts;
use Test\Functional\Json;
use Test\Functional\WebTestCase;

/**
 * @internal
 */
final class AuthorizeTest extends WebTestCase
{
    use ArraySubsetAsserts;

    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadFixtures([
            AuthorizeFixture::class,
        ]);
    }

    public function testWithoutParams(): void
    {
        $response = $this->app()->handle(self::html('GET', '/authorize'));
        self::assertSame(400, $response->getStatusCode());
    }

    public function testPageWithoutChallenge(): void
    {
        $response = $this->app()->handle(self::html(
            'GET',
            '/authorize?' . http_build_query([
                'response_type' => 'code',
                'client_id' => 'frontend',
                'redirect_uri' => 'http://localhost/oauth',
                'scope' => 'common',
                'state' => 'sTaTe',
            ])
        ));

        self::assertSame(400, $response->getStatusCode());
        self::assertJson($content = (string)$response->getBody());

        $data = Json::decode($content);

        self::assertArraySubset([
            'error' => 'invalid_request',
        ], $data);
    }

    public function testPageWithChallenge(): void
    {
        $response = $this->app()->handle(self::html(
            'GET',
            '/authorize?' . http_build_query([
                'response_type' => 'code',
                'client_id' => 'frontend',
                'code_challenge' => PKCE::challenge(PKCE::verifier()),
                'code_challenge_method' => 'S256',
                'redirect_uri' => 'http://localhost/oauth',
                'scope' => 'common',
                'state' => 'sTaTe',
            ])
        ));

        self::assertSame(200, $response->getStatusCode());
        self::assertNotEmpty($content = (string)$response->getBody());
        self::assertStringContainsString('<title>Auth</title>', $content);
    }

    public function testLang(): void
    {
        $response = $this->app()->handle(self::html(
            'GET',
            '/authorize?' . http_build_query([
                'response_type' => 'code',
                'client_id' => 'frontend',
                'code_challenge' => PKCE::challenge(PKCE::verifier()),
                'code_challenge_method' => 'S256',
                'redirect_uri' => 'http://localhost/oauth',
                'scope' => 'common',
                'state' => 'sTaTe',
            ])
        )->withHeader('Accept-Language', 'ru'));

        self::assertSame(200, $response->getStatusCode());
        self::assertNotEmpty($content = (string)$response->getBody());
        self::assertStringContainsString('<title>Вход</title>', $content);
    }

    public function testInvalidClient(): void
    {
        $response = $this->app()->handle(self::html(
            'GET',
            '/authorize?' . http_build_query([
                'response_type' => 'code',
                'client_id' => 'invalid',
                'redirect_uri' => 'http://localhost/oauth',
                'code_challenge' => PKCE::challenge(PKCE::verifier()),
                'code_challenge_method' => 'S256',
                'scope' => 'common',
                'state' => 'sTaTe',
            ])
        ));

        self::assertSame(401, $response->getStatusCode());
        self::assertJson($content = (string)$response->getBody());

        $data = Json::decode($content);

        self::assertArraySubset([
            'error' => 'invalid_client',
        ], $data);
    }

    public function testAuthActiveUser(): void
    {
        $response = $this->app()->handle(self::html(
            'POST',
            '/authorize?' . http_build_query([
                'response_type' => 'code',
                'client_id' => 'frontend',
                'redirect_uri' => 'http://localhost/oauth',
                'code_challenge' => PKCE::challenge(PKCE::verifier()),
                'code_challenge_method' => 'S256',
                'scope' => 'common',
                'state' => 'sTaTe',
            ]),
            [
                'email' => 'aCTive@app.test',
                'password' => 'password',
            ]
        ));

        self::assertSame(302, $response->getStatusCode());
        self::assertNotEmpty($location = $response->getHeaderLine('Location'));

        /** @var array{query:string} $url */
        $url = parse_url($location);

        self::assertNotEmpty($url['query']);

        /** @var array{code:string,state:string} $query */
        parse_str($url['query'], $query);

        self::assertArrayHasKey('code', $query);
        self::assertNotEmpty($query['code']);
        self::assertArrayHasKey('state', $query);
        self::assertSame('sTaTe', $query['state']);
    }

    public function testAuthWaitUser(): void
    {
        $response = $this->app()->handle(self::html(
            'POST',
            '/authorize?' . http_build_query([
                'response_type' => 'code',
                'client_id' => 'frontend',
                'redirect_uri' => 'http://localhost/oauth',
                'code_challenge' => PKCE::challenge(PKCE::verifier()),
                'code_challenge_method' => 'S256',
                'scope' => 'common',
                'state' => 'sTaTe',
            ]),
            [
                'email' => 'wait@app.test',
                'password' => 'password',
            ]
        ));

        self::assertSame(409, $response->getStatusCode());
        self::assertNotEmpty($content = (string)$response->getBody());
        self::assertStringContainsString('User is not confirmed.', $content);
    }

    public function testAuthInvalidUser(): void
    {
        $response = $this->app()->handle(self::html(
            'POST',
            '/authorize?' . http_build_query([
                'response_type' => 'code',
                'client_id' => 'frontend',
                'redirect_uri' => 'http://localhost/oauth',
                'code_challenge' => PKCE::challenge(PKCE::verifier()),
                'code_challenge_method' => 'S256',
                'scope' => 'common',
                'state' => 'sTaTe',
            ]),
            [
                'email' => 'active@app.test',
                'password' => '',
            ]
        ));

        self::assertSame(400, $response->getStatusCode());
        self::assertNotEmpty($content = (string)$response->getBody());
        self::assertStringContainsString('Incorrect email or password.', $content);
    }

    public function testAuthInvalidUserLang(): void
    {
        $response = $this->app()->handle(self::html(
            'POST',
            '/authorize?' . http_build_query([
                'response_type' => 'code',
                'client_id' => 'frontend',
                'redirect_uri' => 'http://localhost/oauth',
                'code_challenge' => PKCE::challenge(PKCE::verifier()),
                'code_challenge_method' => 'S256',
                'scope' => 'common',
                'state' => 'sTaTe',
            ]),
            [
                'email' => 'active@app.test',
                'password' => '',
            ]
        )->withHeader('Accept-Language', 'ru'));

        self::assertSame(400, $response->getStatusCode());
        self::assertNotEmpty($content = (string)$response->getBody());
        self::assertStringContainsString('Неверный email или пароль.', $content);
    }
}
