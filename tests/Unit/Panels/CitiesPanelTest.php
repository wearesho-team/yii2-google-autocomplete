<?php

namespace Wearesho\GoogleAutocomplete\Yii\Tests\Unit\Panels;

use Wearesho\GoogleAutocomplete\Yii\Panels\CitiesPanel;
use Wearesho\GoogleAutocomplete\Yii\Tests\PanelTestCase;

/**
 * Class CitiesPanelTest
 * @package Wearesho\GoogleAutocomplete\Yii\Tests\Unit\Panels
 * @coversDefaultClass CitiesPanel
 * @internal
 */
class CitiesPanelTest extends PanelTestCase
{
    protected const TESTED_PANEL = CitiesPanel::class;

    public function testFullResponse(): void
    {
        $this->mock($this->getSuccessResponse());
        $this->setQueryAttributes([
            static::INPUT => 'input',
            static::TOKEN => $this->token,
            static::LANGUAGE => static::RUSSIAN,
        ]);

        $this->assertEquals('needle', $this->instancePanel()->getResponse()->data[0]);
    }
}
