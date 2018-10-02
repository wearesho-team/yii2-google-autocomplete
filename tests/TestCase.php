<?php

namespace Wearesho\GoogleAutocomplete\Yii\Tests;

use GuzzleHttp;
use Wearesho\GoogleAutocomplete;
use yii\di\Container;
use yii\rbac\PhpManager;
use yii\web;

/**
 * Class TestCase
 * @package Wearesho\GoogleAutocomplete\Yii\Tests
 */
class TestCase extends \PHPUnit\Framework\TestCase
{
    protected const LANGUAGE = 'uk';

    /** @var string */
    protected $token;

    /** @var GoogleAutocomplete\Yii\Panels\Panel */
    protected $panel;

    /** @var GoogleAutocomplete\Service */
    protected static $autoCompleteService;

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public static function setUpBeforeClass()
    {
        putenv('GOOGLE_SERVICE_AUTOCOMPLETE_KEY=KEY');
        \Yii::$container = new Container();
        static::injectGoogleAutocompleteService();
        static::resetApplication();
    }

    protected static function injectGoogleAutocompleteService(): void
    {
        \Yii::$container->set(GoogleAutocomplete\ConfigInterface::class, GoogleAutocomplete\EnvironmentConfig::class);
        \Yii::$container->set(GuzzleHttp\ClientInterface::class, GuzzleHttp\Client::class);
        \Yii::$container->set(GoogleAutocomplete\ServiceInterface::class, GoogleAutocomplete\Service::class);
    }

    /**
     * @throws \yii\base\InvalidConfigException
     */
    protected static function resetApplication(): void
    {
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
        $this->token = base64_encode(random_bytes(mt_rand(0, 255)));
    }

    protected function setQueryAttributes(array $attributes): void
    {
        foreach ($attributes as $key => $attribute) {
            $_GET[$key] = $attribute;
        }
    }
}
