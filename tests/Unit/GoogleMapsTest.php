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

    public function testInvokeWithLatLon(): void
    {
        $attributes = ['lat' => '37.422', 'lon' => '-122.084'];
        $result = ($this->shortcode)($attributes, '');

        $this->assertStringContainsString('src="https://maps.google.com/maps?output=embed&amp;q=37.422%2C-122.084', $result);
    }

    public function testInvokeWithZoomAndType(): void
    {
        $attributes = ['address' => 'Eiffel Tower', 'zoom' => '18', 'type' => 'satellite'];
        $result = ($this->shortcode)($attributes, '');

        $this->assertStringContainsString('z=18', $result);
        $this->assertStringContainsString('t=k', $result);
    }

    public function testInvokeNoLocation(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Google Maps: Address or lat/lon attribute required.');

        $attributes = [];
        ($this->shortcode)($attributes, '');
    }
}
