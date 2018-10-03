<?php

namespace Wearesho\GoogleAutocomplete\Yii\Tests\Unit;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use PHPUnit\Framework\TestCase;
use Wearesho\GoogleAutocomplete;
use yii\web\Application;

/**
 * Class BootstrapTest
 * @package Wearesho\GoogleAutocomplete\Yii\Tests\Unit
 * @coversDefaultClass \Wearesho\GoogleAutocomplete\Yii\Bootstrap
 * @internal
 */
class BootstrapTest extends TestCase
{
    public function setUp()
    {
        $application = new Application([
            'id' => 'test',
            'basePath' => __DIR__,
        ]);
        $bootstrap = new GoogleAutocomplete\Yii\Bootstrap();
        $bootstrap->bootstrap($application);
    }

    public function testSetup(): void
    {
        $this->assertTrue(\Yii::$container->has(GoogleAutocomplete\ConfigInterface::class));
        $this->assertInstanceOf(
            GoogleAutocomplete\EnvironmentConfig::class,
            \Yii::$container->get(GoogleAutocomplete\ConfigInterface::class)
        );
        $this->assertStringEndsWith(
            'wearesho-team/google-autocomplete/src',
            \Yii::getAlias('@Wearesho/GoogleAutocomplete')
        );
    }

    public function testInstantiateService(): void
    {
        \Yii::$container->set(ClientInterface::class, Client::class);
        $this->assertInstanceOf(
            GoogleAutocomplete\Service::class,
            \Yii::$container->get(GoogleAutocomplete\ServiceInterface::class)
        );
    }
}
