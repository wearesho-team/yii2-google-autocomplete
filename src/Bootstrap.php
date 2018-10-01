<?php

namespace Wearesho\GoogleAutocomplete\Yii;

use Wearesho\GoogleAutocomplete;
use yii;

/**
 * Class Bootstrap
 * @package Wearesho\GoogleAutocomplete\Yii
 */
class Bootstrap implements yii\base\BootstrapInterface
{
    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        $app->setAliases([
            '@Wearesho/GoogleAutocomplete' => '@vendor/wearesho-team/google-autocomplete/src',
        ]);

        \Yii::$container->set(
            GoogleAutocomplete\ConfigInterface::class,
            GoogleAutocomplete\EnvironmentConfig::class
        );
    }
}
