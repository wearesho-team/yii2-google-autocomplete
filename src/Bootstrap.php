<?php

namespace Wearesho\GoogleAutocomplete\Yii;

use Wearesho\GoogleAutocomplete;

/**
 * Class Bootstrap
 * @package Wearesho\GoogleAutocomplete\Yii
 */
class Bootstrap implements \yii\base\BootstrapInterface
{
    /** @var GoogleAutocomplete\ConfigInterface  */
    public $config = GoogleAutocomplete\EnvironmentConfig::class;

    /** @var GoogleAutocomplete\ServiceInterface */
    public $service = GoogleAutocomplete\Service::class;

    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        $app->setAliases([
            '@Wearesho/GoogleAutocomplete' => '@vendor/wearesho-team/google-autocomplete/src',
        ]);

        \Yii::$container->set(GoogleAutocomplete\ConfigInterface::class, $this->config);
        \Yii::$container->set(GoogleAutocomplete\ServiceInterface::class, $this->service);
    }
}
