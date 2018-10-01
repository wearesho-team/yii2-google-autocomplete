<?php

namespace Wearesho\GoogleAutocomplete\Yii\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Wearesho\GoogleAutocomplete;
use yii\base\Module;

/**
 * Class ControllerTest
 * @package Wearesho\GoogleAutocomplete\Yii\Tests\Unit
 * @coversDefaultClass GoogleAutocomplete\Yii\Controller
 * @internal
 */
class ControllerTest extends TestCase
{
    /** @var GoogleAutocomplete\Yii\Controller */
    protected $fakeController;

    protected function setUp(): void
    {
        $this->fakeController = new GoogleAutocomplete\Yii\Controller('id', new Module('id'));
    }

    public function testActions(): void
    {
        $this->assertArraySubset(
            [
                'streets' => [
                    'get' => [
                        'class' => GoogleAutocomplete\Yii\Panels\StreetsPanel::class,
                    ],
                ],
                'cities' => [
                    'get' => [
                        'class' => GoogleAutocomplete\Yii\Panels\CitiesPanel::class,
                    ],
                ],
            ],
            $this->fakeController->actions
        );
    }
}
