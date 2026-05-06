<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes\Test\Unit\Helper;

use JoomlaShortcoder\Plugin\Content\Shortcodes\Helper\UrlHelper;
use PHPUnit\Framework\TestCase;

class UrlHelperTest extends TestCase
{
    /**
     * @dataProvider parseUrlProvider
     */
    public function testParseUrl(string $url, $expected): void
    {
        $this->assertEquals($expected, UrlHelper::parseUrl($url));
    }

    public static function parseUrlProvider(): array
    {
        return [
            'full URL with extension' => [
                'http://www.example.com/path/to/file.pdf?query=string#fragment',
                [
                    'scheme'    => 'http',
                    'host'      => 'www.example.com',
                    'path'      => '/path/to/file.pdf',
                    'query'     => 'query=string',
                    'fragment'  => 'fragment',
                    'extension' => 'pdf',
                    'type'      => 'absolute',
                ],
            ],
            'URL with path and extension' => [
                '/path/to/image.jpg',
                [
                    'path'      => '/path/to/image.jpg',
                    'extension' => 'jpg',
                    'type'      => 'relative',
                ],
            ],
            'URL with no extension' => [
                'https://www.example.com/path/to/page',
                [
                    'scheme' => 'https',
                    'host'   => 'www.example.com',
                    'path'   => '/path/to/page',
                    'type'   => 'absolute',
                ],
            ],
            'URL with query but no extension' => [
                'https://www.example.com/search?q=test',
                [
                    'scheme' => 'https',
                    'host'   => 'www.example.com',
                    'path'   => '/search',
                    'query'  => 'q=test',
                    'type'   => 'absolute',
                ],
            ],
            'URL with complex path and query' => [
                'ftp://user:pass@host.com:21/etc/passwd.zip?a=b&c=d#something',
                [
                    'scheme'    => 'ftp',
                    'user'      => 'user',
                    'pass'      => 'pass',
                    'host'      => 'host.com',
                    'port'      => 21,
                    'path'      => '/etc/passwd.zip',
                    'query'     => 'a=b&c=d',
                    'fragment'  => 'something',
                    'extension' => 'zip',
                    'type'      => 'absolute',
                ],
            ],
            'URL with only host' => [
                'www.example.com',
                [
                    'path'      => 'www.example.com',
                    'extension' => 'com',
                    'type'      => 'relative',
                ],
            ],
            'URL with only path (no host)' => [
                '/another/file.xml',
                [
                    'path'      => '/another/file.xml',
                    'extension' => 'xml',
                    'type'      => 'relative',
                ],
            ],
            'URL with no path but query' => [
                '?foo=bar',
                [
                    'query' => 'foo=bar',
                    'type'  => 'relative',
                ],
            ],
            'empty URL' => [
                '',
                false,
            ],
            'URL with dot in path but not extension' => [
                'https://example.com/my.folder/file',
                [
                    'scheme' => 'https',
                    'host'   => 'example.com',
                    'path'   => '/my.folder/file',
                    'type'   => 'absolute',
                ],
            ],
            'URL with dot in path and extension' => [
                'https://example.com/my.folder/file.js',
                [
                    'scheme'    => 'https',
                    'host'      => 'example.com',
                    'path'      => '/my.folder/file.js',
                    'extension' => 'js',
                    'type'      => 'absolute',
                ],
            ],
            'URL with filename only' => [
                'image.png',
                [
                    'path'      => 'image.png',
                    'extension' => 'png',
                    'type'      => 'relative',
                ],
            ],
            'URL with no path but fragment' => [
                '#top',
                [
                    'fragment' => 'top',
                    'type'     => 'relative',
                ],
            ],
            'URL with trailing slash' => [
                'http://example.com/path/',
                [
                    'scheme' => 'http',
                    'host'   => 'example.com',
                    'path'   => '/path/',
                    'type'   => 'absolute',
                ],
            ],
            'URL with multiple dots in filename' => [
                'http://example.com/archive.tar.gz',
                [
                    'scheme'    => 'http',
                    'host'      => 'example.com',
                    'path'      => '/archive.tar.gz',
                    'extension' => 'gz',
                    'type'      => 'absolute',
                ],
            ],
            'URL with query param as extension' => [
                'http://example.com/file?format=pdf',
                [
                    'scheme' => 'http',
                    'host'   => 'example.com',
                    'path'   => '/file',
                    'query'  => 'format=pdf',
                    'type'   => 'absolute',
                ],
            ],
            'protocol-relative URL' => [
                '//example.com/path/to/file.js',
                [
                    'host'      => 'example.com',
                    'path'      => '/path/to/file.js',
                    'extension' => 'js',
                    'type'      => 'protocol-relative',
                ],
            ],
            'URL with invalid scheme' => [
                '1http://example.com',
                false,
            ],
            'URL with forbidden characters' => [
                'http://example.com/path{with_invalid_chars}',
                false,
            ],
        ];
    }
}
