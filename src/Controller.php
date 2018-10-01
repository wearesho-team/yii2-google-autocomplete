<?php

namespace Wearesho\GoogleAutocomplete\Yii;

use Wearesho\GoogleAutocomplete\Yii\Panels\CitiesPanel;
use Wearesho\GoogleAutocomplete\Yii\Panels\StreetsPanel;
use Wearesho\Yii\Http;

/**
 * Class Controller
 * @package Wearesho\GoogleAutocomplete\Yii
 */
class Controller extends Http\Controller
{
    public function actions(): array
    {
        return [
            'streets' => [
                'get' => [
                    'class' => StreetsPanel::class,
                ],
            ],
            'cities' => [
                'get' => [
                    'class' => CitiesPanel::class,
                ],
            ],
        ];
    }
}
