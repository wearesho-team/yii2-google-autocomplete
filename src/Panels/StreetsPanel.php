<?php

namespace Wearesho\GoogleAutocomplete\Yii\Panels;

use Wearesho\GoogleAutocomplete;
use Wearesho\Yii\Http\Behaviors\GetParamsBehavior;

/**
 * Class StreetsPanel
 * @package Wearesho\GoogleAutocomplete\Yii\Panels
 */
class StreetsPanel extends Panel
{
    /** @var string|null */
    public $city;

    /** @var string|null */
    public $type;

    public function behaviors(): array
    {
        return array_merge(parent::behaviors(), [
            'getStreetsParams' => [
                'class' => GetParamsBehavior::class,
                'attributes' => ['city', 'type',],
            ],
        ]);
    }

    public function rules(): array
    {
        return array_merge(parent::rules(), [
            [
                ['type', 'city',],
                'string'
            ],
            ['type', 'filter', 'filter' => 'preg_quote'],
        ]);
    }

    protected function generateResponse(): array
    {
        $locations = parent::generateResponse();

        if (!empty($this->type)) {
            return preg_replace("/(\s*{$this->type}\s*)/iu", '', $locations);
        }

        return $locations;
    }

    protected function getQuery(): GoogleAutocomplete\Queries\Interfaces\SearchQueryInterface
    {
        return new GoogleAutocomplete\Queries\StreetSearch(
            $this->token,
            $this->input,
            $this->getSearchLanguage(),
            $this->city,
            $this->type,
            GoogleAutocomplete\Enums\SearchMode::SHORT()
        );
    }
}
