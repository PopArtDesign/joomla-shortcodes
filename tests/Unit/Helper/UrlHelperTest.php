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
                ],
            ],
            'URL with path and extension' => [
                '/path/to/image.jpg',
                [
                    'path'      => '/path/to/image.jpg',
                    'extension' => 'jpg',
                ],
            ],
            'URL with no extension' => [
                'https://www.example.com/path/to/page',
                [
                    'scheme' => 'https',
                    'host'   => 'www.example.com',
                    'path'   => '/path/to/page',
                ],
            ],
            'URL with query but no extension' => [
                'https://www.example.com/search?q=test',
                [
                    'scheme' => 'https',
                    'host'   => 'www.example.com',
                    'path'   => '/search',
                    'query'  => 'q=test',
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
                ],
            ],
            'URL with only host' => [
                'www.example.com',
                [
                    'path'      => 'www.example.com',
                    'extension' => 'com',
                ],
            ],
            'URL with only path (no host)' => [
                '/another/file.xml',
                [
                    'path'      => '/another/file.xml',
                    'extension' => 'xml',
                ],
            ],
            'URL with no path but query' => [
                '?foo=bar',
                [
                    'query' => 'foo=bar',
                ],
            ],
            'empty URL' => [
                '',
                [
                    'path' => '',
                ],
            ],
            'URL with dot in path but not extension' => [
                'https://example.com/my.folder/file',
                [
                    'scheme' => 'https',
                    'host'   => 'example.com',
                    'path'   => '/my.folder/file',
                ],
            ],
            'URL with dot in path and extension' => [
                'https://example.com/my.folder/file.js',
                [
                    'scheme'    => 'https',
                    'host'      => 'example.com',
                    'path'      => '/my.folder/file.js',
                    'extension' => 'js',
                ],
            ],
            'URL with filename only' => [
                'image.png',
                [
                    'path'      => 'image.png',
                    'extension' => 'png',
                ],
            ],
            'URL with no path but fragment' => [
                '#top',
                [
                    'fragment' => 'top',
                ],
            ],
            'URL with trailing slash' => [
                'http://example.com/path/',
                [
                    'scheme' => 'http',
                    'host'   => 'example.com',
                    'path'   => '/path/',
                ],
            ],
            'URL with multiple dots in filename' => [
                'http://example.com/archive.tar.gz',
                [
                    'scheme'    => 'http',
                    'host'      => 'example.com',
                    'path'      => '/archive.tar.gz',
                    'extension' => 'gz',
                ],
            ],
            'URL with query param as extension' => [
                'http://example.com/file?format=pdf',
                [
                    'scheme' => 'http',
                    'host'   => 'example.com',
                    'path'   => '/file',
                    'query'  => 'format=pdf',
                ],
            ],
        ];
    }
}
