<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes\Test\Unit;

use JoomlaShortcoder\Plugin\Content\Shortcodes\GoogleMaps;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \JoomlaShortcoder\Plugin\Content\Shortcodes\GoogleMaps
 */
class GoogleMapsTest extends TestCase
{
    /**
     * @var GoogleMaps
     */
    private $shortcode;

    protected function setUp(): void
    {
        $this->shortcode = new GoogleMaps();
    }

    public function testInvokeWithQueryAttribute(): void
    {
        $attributes = ['query' => '1600 Amphitheatre Parkway, Mountain View, CA'];
        $result = ($this->shortcode)($attributes, '');

        $this->assertStringContainsString('src="https://maps.google.com/maps?output=embed&amp;q=1600+Amphitheatre+Parkway%2C+Mountain+View%2C+CA', $result);
        $this->assertStringContainsString('class="embed-container embed-map embed-googlemaps"', $result);
    }

    public function testInvokeWithQueryContent(): void
    {
        $attributes = [];
        $content = '37.422,-122.084';
        $result = ($this->shortcode)($attributes, $content);

        $this->assertStringContainsString('src="https://maps.google.com/maps?output=embed&amp;q=37.422%2C-122.084', $result);
    }

    public function testInvokeWithQueryPositionalArgument(): void
    {
        $attributes = ['0' => 'Eiffel Tower', 'zoom' => '18', 'type' => 'satellite'];
        $content = '';
        $result = ($this->shortcode)($attributes, $content);

        $this->assertStringContainsString('src="https://maps.google.com/maps?output=embed&amp;q=Eiffel+Tower', $result);
        $this->assertStringContainsString('z=18', $result);
        $this->assertStringContainsString('t=k', $result);
    }

    public function testInvokeReturnsErrorForNoQuery(): void
    {
        $attributes = [];
        $content = '';
        $result = ($this->shortcode)($attributes, $content);
        $this->assertStringContainsString('<div class="shortcode-error"', $result);
        $this->assertStringContainsString('<b>GoogleMaps</b>: Query is required. It can be provided as a `query` attribute, content, or a positional argument.', $result);
    }

    public function testInvokeReturnsErrorForInvalidMapType(): void
    {
        $attributes = ['query' => 'Eiffel Tower', 'type' => 'invalid-type'];
        $result = ($this->shortcode)($attributes, '');
        $this->assertStringContainsString('<div class="shortcode-error"', $result);
        $this->assertStringContainsString('<b>GoogleMaps</b>: Invalid map type specified. Available types: roadmap, satellite, hybrid, terrain', $result);
    }
}
