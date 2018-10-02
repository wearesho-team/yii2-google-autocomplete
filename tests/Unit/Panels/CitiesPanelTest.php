<?php

namespace Wearesho\GoogleAutocomplete\Yii\Tests\Unit\Panels;

use GuzzleHttp;
use Wearesho\GoogleAutocomplete\Yii\Panels\CitiesPanel;
use Wearesho\GoogleAutocomplete\Yii\Tests\TestCase;

/**
 * Class CitiesPanelTest
 * @package Wearesho\GoogleAutocomplete\Yii\Tests\Unit\Panels
 * @coversDefaultClass CitiesPanel
 * @internal
 */
class CitiesPanelTest extends TestCase
{
    /** @var CitiesPanel */
    protected $panel;

    protected function setUp(): void
    {
        parent::setUp();

        $this->panel = CitiesPanel::instance();
    }

    public function testRulesArray(): void
    {
        $this->assertEquals(
            [
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
            ],
            $this->panel->rules()
        );
    }

    public function testValidate(): void
    {
        $this->setQueryAttributes([
            'input' => 'input',
            'token' => $this->token
        ]);
        $this->assertTrue($this->panel->validate());
    }

    public function testFullResponse(): void
    {
        $stack = GuzzleHttp\HandlerStack::create(new GuzzleHttp\Handler\MockHandler([
            new GuzzleHttp\Psr7\Response(200, [], json_encode([
                'status' => 'OK',
                'predictions' => [
                    [
                        'description' => 'description',
                        'structured_formatting' => [
                            'main_text' => 'needle',
                        ],
                        'terms' => []
                    ],
                    [],
                ],
            ])),
        ]));
        $client = new GuzzleHttp\Client(['handler' => $stack]);
        \Yii::$container->set(GuzzleHttp\ClientInterface::class, $client);
        $this->setQueryAttributes([
            'input' => 'input',
            'token' => $this->token,
            'city' => 'city',
            'type' => 'type',
            'language' => 'uk',
        ]);
        $this->panel = \Yii::$container->get(CitiesPanel::class);

        $this->assertTrue($this->panel->validate());
        $this->assertEquals('needle', $this->panel->getResponse()->data[0]);
    }
}
