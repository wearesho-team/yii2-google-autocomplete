<?php

namespace Wearesho\GoogleAutocomplete\Yii\Tests\Unit;

use PHPUnit\Framework\TestCase;

use Wearesho\GoogleAutocomplete\Yii\Bootstrap;
use Wearesho\GoogleAutocomplete;
use yii\web\Application;

/**
 * Class BootstrapTest
 * @package Wearesho\GoogleAutocomplete\Yii\Tests\Unit
 * @coversDefaultClass Bootstrap
 * @internal
 */
class BootstrapTest extends TestCase
{
    public function testSetup(): void
    {
        $this->assertFalse(\Yii::$container->has(GoogleAutocomplete\ConfigInterface::class));
        $application = new Application([
            'id' => 'test',
            'basePath' => __DIR__,
        ]);
        $bootstrap = new GoogleAutocomplete\Yii\Bootstrap();
        $bootstrap->bootstrap($application);
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
}
