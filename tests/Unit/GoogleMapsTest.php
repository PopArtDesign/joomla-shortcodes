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

    public function testInvokeWithAddress(): void
    {
        $attributes = ['address' => '1600 Amphitheatre Parkway, Mountain View, CA'];
        $result = ($this->shortcode)($attributes, '');

        $this->assertStringContainsString('src="https://maps.google.com/maps?output=embed&amp;q=1600+Amphitheatre+Parkway%2C+Mountain+View%2C+CA', $result);
        $this->assertStringContainsString('class="embed-container embed-map embed-googlemaps"', $result);
    }

    public function testInvokeWithZoomAndType(): void
    {
        $attributes = ['address' => 'Eiffel Tower', 'zoom' => '18', 'type' => 'satellite'];
        $result = ($this->shortcode)($attributes, '');

        $this->assertStringContainsString('z=18', $result);
        $this->assertStringContainsString('t=k', $result);
    }

    public function testInvokeWithCoordinates(): void
    {
        $attributes = ['coordinates' => '37.422,-122.084'];
        $result = ($this->shortcode)($attributes, '');

        $this->assertStringContainsString('src="https://maps.google.com/maps?output=embed&amp;q=37.422%2C-122.084', $result);
    }

    public function testInvokeReturnsErrorForNoLocation(): void
    {
        $attributes = [];
        $result = ($this->shortcode)($attributes, '');
        $this->assertStringContainsString('GoogleMaps: Address or coordinates attribute required.', $result);
    }

    public function testInvokeReturnsErrorForInvalidMapType(): void
    {
        $attributes = ['address' => 'Eiffel Tower', 'type' => 'invalid-type'];
        $result = ($this->shortcode)($attributes, '');
        $this->assertStringContainsString('GoogleMaps: Invalid map type specified. Available types: roadmap, satellite, hybrid, terrain', $result);
    }
}
