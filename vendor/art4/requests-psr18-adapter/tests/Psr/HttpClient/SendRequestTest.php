<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Psr\HttpClient;

use Art4\Requests\Exception\Psr\NetworkException;
use Art4\Requests\Exception\Psr\RequestException;
use Art4\Requests\Psr\HttpClient;
use Psr\Http\Message\ResponseInterface;
use WpOrg\Requests\Exception\Transport as ExceptionTransport;
use WpOrg\Requests\Transport;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

final class SendRequestTest extends TestCase
{
    /**
     * Tests receiving a response when using sendRequest().
     *
     * @covers \Art4\Requests\Psr\HttpClient::__construct
     * @covers \Art4\Requests\Psr\HttpClient::sendRequest
     *
     * @return void
     */
    public function testSendRequestWithGetSendsCorrectDataAndReturnsCorrectResponseData()
    {
        $transport = $this->createMock(Transport::class);
        $transport->expects($this->once())->method('request')->willReturnCallback(function ($url, $headers, $data, $options) {
            $this->assertSame('https://example.org/', $url);
            $this->assertSame(['Host' => 'example.org'], $headers);
            $this->assertSame('', $data);
            $this->assertSame('GET', $options['type']);

            return
                'HTTP/1.1 200 OK' . "\r\n".
                'Content-Type:text/plain'. "\r\n".
                "\r\n".
                'foobar';
        });

        $httpClient = new HttpClient([
            'transport' => $transport,
        ]);

        $request = $httpClient->createRequest('GET', 'https://example.org');

        $response = $httpClient->sendRequest($request);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('OK', $response->getReasonPhrase());
        $this->assertSame('1.1', $response->getProtocolVersion());
        $this->assertSame(['content-type' => ['text/plain']], $response->getHeaders());
        $this->assertSame('foobar', $response->getBody()->__toString());
    }

    /**
     * Tests receiving a response when using sendRequest().
     *
     * @covers \Art4\Requests\Psr\HttpClient::sendRequest
     *
     * @return void
     */
    public function testSendRequestWithPostSendsCorrectDataAndReturnsCorrectResponseData()
    {
        $transport = $this->createMock(Transport::class);
        $transport->expects($this->once())->method('request')->willReturnCallback(function ($url, $headers, $data, $options) {
            $this->assertSame('https://example.org/posts', $url);
            $this->assertSame(['Host' => 'example.org'], $headers);
            $this->assertSame('{"title":"Post title"}', $data);
            $this->assertSame('POST', $options['type']);

            return
                'HTTP/1.1 201 Created' . "\r\n".
                'Content-Type:application/json'. "\r\n".
                "\r\n".
                '{"id":1,"title":"Post title"}';
        });

        $httpClient = new HttpClient([
            'transport' => $transport,
        ]);

        $request = $httpClient->createRequest('POST', 'https://example.org/posts');
        $request = $request->withBody($httpClient->createStream('{"title":"Post title"}'));

        $response = $httpClient->sendRequest($request);

        $this->assertSame(201, $response->getStatusCode());
        $this->assertSame('Created', $response->getReasonPhrase());
        $this->assertSame('1.1', $response->getProtocolVersion());
        $this->assertSame(['content-type' => ['application/json']], $response->getHeaders());
        $this->assertSame('{"id":1,"title":"Post title"}', $response->getBody()->__toString());
    }

    /**
     * Tests receiving a response when using sendRequest().
     *
     * @covers \Art4\Requests\Psr\HttpClient::sendRequest
     *
     * @return void
     */
    public function testSendRequestReturnsResponseOn404Error()
    {
        $transport = $this->createMock(Transport::class);
        $transport->expects($this->once())->method('request')->willReturnCallback(function ($url, $headers, $data, $options) {
            $this->assertSame('https://example.org/not-found', $url);
            $this->assertSame(['Host' => 'example.org'], $headers);
            $this->assertSame('', $data);
            $this->assertSame('GET', $options['type']);

            return
                'HTTP/1.1 404 Not Found' . "\r\n".
                'Content-Type:text/plain'. "\r\n".
                "\r\n".
                '404 Not Found';
        });

        $httpClient = new HttpClient([
            'transport' => $transport,
        ]);

        $request = $httpClient->createRequest('GET', 'https://example.org/not-found');

        $response = $httpClient->sendRequest($request);

        $this->assertSame(404, $response->getStatusCode());
        $this->assertSame('Not Found', $response->getReasonPhrase());
        $this->assertSame('1.1', $response->getProtocolVersion());
        $this->assertSame(['content-type' => ['text/plain']], $response->getHeaders());
        $this->assertSame('404 Not Found', $response->getBody()->__toString());
    }

    /**
     * Tests receiving a response when using sendRequest().
     *
     * @covers \Art4\Requests\Psr\HttpClient::sendRequest
     *
     * @return void
     */
    public function testSendRequestReturnsResponseOn503Error()
    {
        $transport = $this->createMock(Transport::class);
        $transport->expects($this->once())->method('request')->willReturnCallback(function ($url, $headers, $data, $options) {
            $this->assertSame('https://example.org/not-available', $url);
            $this->assertSame(['Host' => 'example.org'], $headers);
            $this->assertSame('', $data);
            $this->assertSame('GET', $options['type']);

            return
                'HTTP/1.1 503 Service Unavailable' . "\r\n".
                'Content-Type:text/plain'. "\r\n".
                "\r\n".
                '503 Service Unavailable';
        });

        $httpClient = new HttpClient([
            'transport' => $transport,
        ]);

        $request = $httpClient->createRequest('GET', 'https://example.org/not-available');

        $response = $httpClient->sendRequest($request);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertSame(503, $response->getStatusCode());
        $this->assertSame('Service Unavailable', $response->getReasonPhrase());
        $this->assertSame('1.1', $response->getProtocolVersion());
        $this->assertSame(['content-type' => ['text/plain']], $response->getHeaders());
        $this->assertSame('503 Service Unavailable', $response->getBody()->__toString());
    }

    /**
     * Tests receiving an exception when using sendRequest().
     *
     * @covers \Art4\Requests\Psr\HttpClient::sendRequest
     *
     * @return void
     */
    public function testSendRequestThrowsRequestException()
    {
        $transport = $this->createMock(Transport::class);

        $httpClient = new HttpClient([
            'transport' => $transport,
        ]);

        $request = $httpClient->createRequest('GET', '');

        $this->expectException(RequestException::class);
        $this->expectExceptionMessage('Only HTTP(S) requests are handled.');

        $httpClient->sendRequest($request);
    }

    /**
     * Tests receiving an exception when using sendRequest().
     *
     * @covers \Art4\Requests\Psr\HttpClient::sendRequest
     *
     * @return void
     */
    public function testSendRequestThrowsNetworkException()
    {
        $e = new ExceptionTransport('error message', 'Unknown');

        $transport = $this->createMock(Transport::class);
        $transport->method('request')->willThrowException($e);

        $httpClient = new HttpClient([
            'transport' => $transport,
        ]);

        $request = $httpClient->createRequest('GET', 'https://example.org');

        $this->expectException(NetworkException::class);
        $this->expectExceptionMessage('error message');

        $httpClient->sendRequest($request);
    }
}
