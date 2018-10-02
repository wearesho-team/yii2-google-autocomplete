<?php

namespace Wearesho\GoogleAutocomplete\Yii\Tests;

use GuzzleHttp;
use Wearesho\GoogleAutocomplete;
use yii\di\Container;
use yii\rbac\PhpManager;
use yii\web;

/**
 * Class PanelTestCase
 * @package Wearesho\GoogleAutocomplete\Yii\Tests
 */
class PanelTestCase extends \PHPUnit\Framework\TestCase
{
    protected const LANGUAGE = 'uk';
    protected const TESTED_PANEL = null;

    /** @var string */
    protected $token;

    /** @var array */
    protected $expectedRules;

    /** @var GoogleAutocomplete\Yii\Panels\Panel */
    protected $panel;

    /** @var GoogleAutocomplete\Service */
    protected static $autoCompleteService;

    public function testValidate(): void
    {
        $this->setQueryAttributes([
            'input' => 'input',
            'token' => $this->token,
        ]);
        $this->assertTrue($this->instancePanel()->validate());
    }

    public function testRules(): void
    {
        $this->assertEquals($this->expectedRules, $this->instancePanel()->rules());
    }

    protected function instancePanel(): GoogleAutocomplete\Yii\Panels\Panel
    {
        /** @var GoogleAutocomplete\Yii\Panels\Panel $panel */
        $panel = \Yii::$container->get(static::TESTED_PANEL);

        return $panel;
    }

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public static function setUpBeforeClass()
    {
        static::setEnvironments(['GOOGLE_SERVICE_AUTOCOMPLETE_KEY' =>'KEY']);
        \Yii::$container = new Container();
        \Yii::$container->set(GoogleAutocomplete\ConfigInterface::class, GoogleAutocomplete\EnvironmentConfig::class);
        \Yii::$container->set(GuzzleHttp\ClientInterface::class, GuzzleHttp\Client::class);
        \Yii::$container->set(GoogleAutocomplete\ServiceInterface::class, GoogleAutocomplete\Service::class);
        \Yii::$app = new web\Application([
            'id' => 'yii-register-confirmation-test',
            'basePath' => dirname(__DIR__),
            'components' => [
                'authManager' => [
                    'class' => PhpManager::class,
                    'itemFile' => '@output/items.php',
                    'assignmentFile' => '@output/assignment.php',
                    'ruleFile' => '@output/rule.php',
                ],
                'user' => [
                    'class' => web\User::class,
                    'identityClass' => web\User::class,
                    'enableSession' => false,
                ]
            ],
        ]);
    }

    /**
     * @throws \Exception
     */
    protected function setUp()
    {
        $this->token = base64_encode(random_bytes(mt_rand(1, 30)));
    }

    protected function getSuccessResponse(): GuzzleHttp\Psr7\Response
    {
        return new GuzzleHttp\Psr7\Response(200, [], json_encode([
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
        ]));
    }

    protected function mock($object): void
    {
        $stack = GuzzleHttp\HandlerStack::create(new GuzzleHttp\Handler\MockHandler([$object]));
        $client = new GuzzleHttp\Client(['handler' => $stack]);
        \Yii::$container->set(GuzzleHttp\ClientInterface::class, $client);
    }

    protected function setQueryAttributes(array $attributes): void
    {
        foreach ($attributes as $key => $attribute) {
            $_GET[$key] = $attribute;
        }
    }

    protected function unsetQueryAttributes(array $attributes): void
    {
        foreach ($attributes as $attribute) {
            unset($_GET[$attribute]);
        }
    }

    protected static function setEnvironments(array $environments): void
    {
        foreach ($environments as $key => $value) {
            putenv(implode('=', [$key, $value]));
        }
    }
}
