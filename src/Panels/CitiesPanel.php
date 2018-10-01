<?php

namespace Wearesho\GoogleAutocomplete\Yii\Panels;

use Wearesho\GoogleAutocomplete;

/**
 * Class CitiesPanel
 * @package Wearesho\GoogleAutocomplete\Yii\Panels
 */
class CitiesPanel extends Panel
{
    protected function getQuery(): GoogleAutocomplete\Queries\Interfaces\SearchQueryInterface
    {
        return new GoogleAutocomplete\Queries\CitySearch(
            $this->token,
            $this->input,
            $this->getSearchLanguage(),
            GoogleAutocomplete\Enums\SearchMode::SHORT()
        );
    }
}
