<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Psr\Uri;

use InvalidArgumentException;
use Psr\Http\Message\UriInterface;
use WpOrg\Requests\Iri;
use Art4\Requests\Psr\Uri;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;
use Art4\Requests\Tests\TypeProviderHelper;

final class WithPathTest extends TestCase
{
    /**
     * Tests changing the path when using withPath().
     *
     * @covers \Art4\Requests\Psr\Uri::withPath
     *
     * @return void
     */
    public function testWithPathReturnsUri()
    {
        $uri = Uri::fromIri(new Iri('https://example.org'));

        $this->assertInstanceOf(UriInterface::class, $uri->withPath('/path'));
    }

    /**
     * Tests changing the path when using withPath().
     *
     * @covers \Art4\Requests\Psr\Uri::withPath
     *
     * @return void
     */
    public function testWithPathReturnsNewInstance()
    {
        $uri = Uri::fromIri(new Iri('https://example.org'));

        $this->assertNotSame($uri, $uri->withPath('/path'));
    }

    /**
     * Tests receiving an exception when the withPath() method received an invalid input type as `$path`.
     *
     * @dataProvider dataInvalidTypeNotString
     *
     * @covers \Art4\Requests\Psr\Uri::withPath
     *
     * @param mixed $input Invalid parameter input.
     *
     * @return void
     */
    public function testWithPathWithoutStringThrowsInvalidArgumentException($input)
    {
        $uri = Uri::fromIri(new Iri('https://example.org'));

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('%s::withPath(): Argument #1 ($path) must be of type string', Uri::class));

        $uri = $uri->withPath($input);
    }

    /**
     * Data Provider.
     *
     * @return array<string, mixed>
     */
    public static function dataInvalidTypeNotString(): array
    {
        return TypeProviderHelper::getAllExcept(TypeProviderHelper::GROUP_STRING);
    }

    /**
     * Tests changing the path when using withPath().
     *
     * @dataProvider dataWithPath
     *
     * @covers \Art4\Requests\Psr\Uri::withPath
     *
     * @param string $input
     * @param string $expected
     *
     * @return void
     */
    public function testWithPathChangesThePath($input, $expected)
    {
        $uri = Uri::fromIri(new Iri('https://example.org'));

        $uri = $uri->withPath($input);

        $this->assertSame($expected, $uri->getPath());
    }

    /**
     * Data Provider.
     *
     * @return array<string,string[]>
     */
    public static function dataWithPath(): array
    {
        return [
            'The path can be empty' => ['', '/'],
            'The path can be absolute (starting with a slash)' => ['/path', '/path'],
            'The path can be rootless (not starting with a slash)' => ['rootless', 'rootless'],
            'The path can contain encoded path characters' => ['path%5B%5D', 'path%5B%5D'],
            'The path can contain decoded path characters' => ['path[]', 'path%5B%5D'],
        ];
    }
}
