<?php

namespace Wearesho\GoogleAutocomplete\Yii\Tests\Unit\Panels;

use Wearesho\GoogleAutocomplete\Yii\Panels\CitiesPanel;
use Wearesho\GoogleAutocomplete\Yii\Tests\PanelTestCase;

/**
 * Class CitiesPanelTest
 * @package Wearesho\GoogleAutocomplete\Yii\Tests\Unit\Panels
 * @coversDefaultClass CitiesPanel
 * @internal
 */
class CitiesPanelTest extends PanelTestCase
{
    protected const TESTED_PANEL = CitiesPanel::class;

    protected function setUp(): void
    {
        parent::setUp();

        $this->panel = CitiesPanel::instance();
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
        ]);
        \Yii::$app->language = 'uk';

        $this->assertEquals('needle', $this->instancePanel()->getResponse()->data[0]);
    }
}
