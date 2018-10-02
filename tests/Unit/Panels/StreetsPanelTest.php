<?php

namespace Wearesho\GoogleAutocomplete\Yii\Tests\Unit\Panels;

use GuzzleHttp;
use Wearesho\GoogleAutocomplete\Yii\Panels\StreetsPanel;
use Wearesho\GoogleAutocomplete\Yii\Tests\TestCase;
use yii\web\HttpException;

/**
 * Class StreetsPanelTest
 * @package Wearesho\GoogleAutocomplete\Yii\Tests\Unit\Panels
 * @coversDefaultClass StreetsPanel
 * @internal
 */
class StreetsPanelTest extends TestCase
{
    /** @var StreetsPanel */
    protected $panel;

    protected function setUp()
    {
        parent::setUp();

        $this->panel = StreetsPanel::instance();
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
                [
                    ['type', 'city',],
                    'string'
                ],
                ['type', 'filter', 'filter' => 'preg_quote'],
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
        $this->panel = \Yii::$container->get(StreetsPanel::class);

        $this->assertTrue($this->panel->validate());
        $this->assertEquals('needle', $this->panel->getResponse()->data[0]);
    }

    public function testShortResponse(): void
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
            'language' => 'uk',
        ]);
        unset($_GET['type']);
        unset($_GET['city']);
        $this->panel = \Yii::$container->get(StreetsPanel::class);

        $this->assertTrue($this->panel->validate());
        $this->assertEquals('needle', $this->panel->getResponse()->data[0]);
    }

    public function testUnavailableService(): void
    {
        $stack = GuzzleHttp\HandlerStack::create(new GuzzleHttp\Handler\MockHandler([
            new GuzzleHttp\Exception\RequestException(
                'Some exception',
                new GuzzleHttp\Psr7\Request('GET', 'google.com', [])
            )
        ]));
        $client = new GuzzleHttp\Client(['handler' => $stack]);
        \Yii::$container->set(GuzzleHttp\ClientInterface::class, $client);
        $this->setQueryAttributes([
            'input' => 'input',
            'token' => $this->token,
            'language' => 'uk',
        ]);
        $this->panel = \Yii::$container->get(StreetsPanel::class);

        $this->expectException(HttpException::class);
        $this->panel->getResponse();
    }
}
