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

    /** @var StreetsPanel */
    protected $panel;

    protected function setUp()
    {
        parent::setUp();

        $this->panel = StreetsPanel::instance();
        $this->expectedRules = [
            [
                ['token', 'input',],
                'required',
            ],
            [
                ['token', 'input', 'language'],
                'string',
            ],
            ['language', 'string', 'length' => 2,],
            [
                'language',
                'default',
                'value' => function (): string {
                    return \Yii::$app->language;
                },
            ],
            [
                ['type', 'city',],
                'string'
            ],
            ['type', 'filter', 'filter' => 'preg_quote'],
        ];
    }

    public function testFullResponse(): void
    {
        $this->mock($this->getSuccessResponse());
        $this->setQueryAttributes([
            'input' => 'input',
            'token' => $this->token,
            'city' => 'city',
            'type' => 'type',
            'language' => 'uk',
        ]);
        $this->assertEquals('needle', $this->instancePanel()->getResponse()->data[0]);
    }

    public function testShortResponse(): void
    {
        $this->mock($this->getSuccessResponse());
        $this->setQueryAttributes([
            'input' => 'input',
            'token' => $this->token,
            'language' => 'uk',
        ]);
        $this->unsetQueryAttributes(['type', 'city']);
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
            'input' => 'input',
            'token' => $this->token,
            'language' => 'uk',
        ]);

        $this->instancePanel()->getResponse();
    }
}
