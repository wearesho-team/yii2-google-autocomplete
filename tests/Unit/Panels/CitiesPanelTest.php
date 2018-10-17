<?php

namespace Wearesho\GoogleAutocomplete\Yii\Tests\Unit\Panels;

use GuzzleHttp;
use yii\web;
use Wearesho\GoogleAutocomplete\Enums\SearchStatus;
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

    public function testQueryException(): void
    {
        $this->mock(new GuzzleHttp\Psr7\Response(200, [], json_encode([
            'status' => (string)SearchStatus::OVER_QUERY_LIMIT(),
        ])));

        $this->setQueryAttributes([
            static::INPUT => 'input',
            static::TOKEN => $this->token,
            static::LANGUAGE => static::RUSSIAN,
        ]);


        $this->expectException(web\HttpException::class);
        $this->expectExceptionCode(2);
        $this->expectExceptionMessage('Google autocomplete API query error: OVER_QUERY_LIMIT');

        $this->instancePanel()->getResponse();
    }

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
