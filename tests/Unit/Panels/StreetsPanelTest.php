<?php

namespace Wearesho\GoogleAutocomplete\Yii\Tests\Unit\Panels;

use GuzzleHttp;
use Horat1us\Environment\MissingEnvironmentException;
use PHPUnit\Framework\MockObject\MockObject;
use Wearesho\GoogleAutocomplete\Service;
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
     * @expectedExceptionMessage Google autocomplete API is unavailable
     * @expectedExceptionCode 1
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

    /**
     * @expectedException \yii\web\HttpException
     * @expectedExceptionMessage Google autocomplete API is not configured
     * @expectedExceptionCode 3
     */
    public function testMissingEnvironment(): void
    {
        /** @var StreetsPanel $panel */
        $panel = \Yii::$container->get(
            static::TESTED_PANEL,
            [
                0 => $service = $this->createMock(Service::class),
            ]
        );

        /** @var MockObject|Service $service */

        $service->expects($this->once())
            ->method('load')
            ->will($this->throwException(new MissingEnvironmentException('ENV_KEY')));

        $this->setQueryAttributes([
            static::INPUT => 'input',
            static::TOKEN => $this->token,
            static::LANGUAGE => static::RUSSIAN,
        ]);

        $panel->getResponse();
    }
}
