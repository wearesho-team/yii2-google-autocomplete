<?php

namespace Wearesho\GoogleAutocomplete\Yii\Tests\Unit\Panels;

use GuzzleHttp;
use Wearesho\GoogleAutocomplete\Yii\Panels\StreetsPanel;
use Wearesho\GoogleAutocomplete\Yii\Tests\PanelTestCase;

/**
 * Class StreetsPanelTest
 * @package Wearesho\GoogleAutocomplete\Yii\Tests\Unit\Panels
 * @coversDefaultClass StreetsPanel
 * @internal
 */
class StreetsPanelTest extends PanelTestCase
{
    protected const TESTED_PANEL = StreetsPanel::class;

    protected const CITY = 'city';
    protected const TYPE = 'type';

    /** @var StreetsPanel */
    protected $panel;

    public function testFullResponse(): void
    {
        $this->mock($this->getSuccessResponse());
        $this->setQueryAttributes([
            static::INPUT => 'value',
            static::TOKEN => $this->token,
            static::CITY => 'city',
            static::TYPE => 'type',
            static::LANGUAGE => static::RUSSIAN,
        ]);
        $this->assertEquals('needle', $this->instancePanel()->getResponse()->data[0]);
    }

    public function testCityValidate(): void
    {
        $this->setQueryAttributes([static::CITY => 'city']);
        $this->assertTrue($this->instancePanel()->validate([static::CITY]));
        $this->unsetQueryAttributes([static::CITY]);
        $this->assertTrue($this->instancePanel()->validate([static::CITY]));
    }

    public function testTypeValidate(): void
    {
        $this->setQueryAttributes([static::TYPE => 'type']);
        $this->assertTrue($this->instancePanel()->validate([static::TYPE]));
        $this->unsetQueryAttributes([static::TYPE]);
        $this->assertTrue($this->instancePanel()->validate([static::TYPE]));
    }

    public function testShortResponse(): void
    {
        $this->mock($this->getSuccessResponse());
        $this->setQueryAttributes([
            static::INPUT => 'input',
            static::TOKEN => $this->token,
            static::LANGUAGE => static::RUSSIAN,
        ]);
        $this->unsetQueryAttributes([static::TYPE, static::CITY]);
        $this->assertEquals('needle', $this->instancePanel()->getResponse()->data[0]);
    }

    /**
     * @expectedException \yii\web\HttpException
     */
    public function testUnavailableService(): void
    {
        $this->mock(new GuzzleHttp\Exception\RequestException(
            'Some exception',
            new GuzzleHttp\Psr7\Request('GET', 'google.com', [])
        ));
        $this->setQueryAttributes([
            static::INPUT => 'input',
            static::TOKEN => $this->token,
            static::LANGUAGE => static::RUSSIAN,
        ]);

        $this->instancePanel()->getResponse();
    }
}
