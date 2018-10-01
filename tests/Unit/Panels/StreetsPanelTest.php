<?php

namespace Wearesho\GoogleAutocomplete\Yii\Tests\Unit\Panels;

use Wearesho\GoogleAutocomplete\Yii\Panels\StreetsPanel;
use Wearesho\GoogleAutocomplete\Yii\Tests\TestCase;
use Wearesho\Yii\Http\Request;

/**
 * Class StreetsPanelTest
 * @package Wearesho\GoogleAutocomplete\Yii\Tests\Unit\Panels
 * @coversDefaultClass StreetsPanel
 * @internal
 */
class StreetsPanelTest extends TestCase
{
    /** @var StreetsPanel */
    protected $fakeStreetsPanel;

    protected function setUp(): void
    {
        $this->fakeStreetsPanel = StreetsPanel::instance();
    }

    public function testInstance(): void
    {
        $this->assertInstanceOf(StreetsPanel::class, $this->fakeStreetsPanel);
    }
}
