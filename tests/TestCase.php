<?php

namespace Wearesho\GoogleAutocomplete\Yii\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Wearesho\GoogleAutocomplete\ConfigInterface;
use Wearesho\GoogleAutocomplete\EnvironmentConfig;
use Wearesho\GoogleAutocomplete\Service;
use Wearesho\GoogleAutocomplete\ServiceInterface;
use yii\di\Container;
use yii\rbac\PhpManager;
use yii\web\Application;
use yii\web\User;

/**
 * Class TestCase
 * @package Wearesho\GoogleAutocomplete\Yii\Tests
 */
class TestCase extends \PHPUnit\Framework\TestCase
{
    /** @var string */
    protected $token;

    /** @var Service */
    protected static $autoCompleteService;

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public static function setUpBeforeClass()
    {
        \Yii::$container = new Container();

        \Yii::$container->set(ConfigInterface::class, EnvironmentConfig::class);
        \Yii::$container->set(ClientInterface::class, Client::class);
        \Yii::$container->set(ServiceInterface::class, Service::class);

        \Yii::$app = new Application([
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
                    'class' => User::class,
                    'identityClass' => User::class,
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
}
