<?php

namespace Wearesho\GoogleAutocomplete\Yii;

use Wearesho\GoogleAutocomplete\Yii\Panels;
use Wearesho\Yii\Http;

/**
 * Class Controller
 * @package Wearesho\GoogleAutocomplete\Yii
 */
class Controller extends Http\Controller
{
    public $actions = [
        'streets' => [
            'get' => [
                'class' => Panels\StreetsPanel::class,
            ],
        ],
        'cities' => [
            'get' => [
                'class' => Panels\CitiesPanel::class,
            ],
        ],
    ];
}
