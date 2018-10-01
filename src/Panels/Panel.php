<?php

namespace Wearesho\GoogleAutocomplete\Yii\Panels;

use Wearesho\GoogleAutocomplete;
use Wearesho\Yii\Http;

/**
 * Class AbstractPanel
 * @package Wearesho\GoogleAutocomplete\Yii\Panels
 */
abstract class Panel extends Http\Panel
{
    /** @var string */
    public $token;

    /** @var string */
    public $input;

    /** @var GoogleAutocomplete\Enums\SearchLanguage */
    public $language;

    /** @var GoogleAutocomplete\ServiceInterface */
    protected $service;

    public function __construct(
        GoogleAutocomplete\ServiceInterface $service,
        Http\Request $request,
        Http\Response $response,
        array $config = []
    ) {
        parent::__construct($request, $response, $config);

        $this->service = $service;
    }

    public function behaviors(): array
    {
        return [
            'getBaseParams' => [
                'class' => Http\Behaviors\GetParamsBehavior::class,
                'attributes' => ['token', 'input',],
            ],
        ];
    }

    public function rules(): array
    {
        return [
            [
                ['token', 'input',],
                'required',
            ],
            [
                ['token', 'input', 'language'],
                'string',
            ],
            ['language', 'string', 'length' => 2,],
            [
                'language',
                'default',
                'value' => function (): string {
                    return \Yii::$app->language;
                },
            ],
        ];
    }

    /**
     * @return array
     * @throws GoogleAutocomplete\Exceptions\InvalidResponse
     * @throws GoogleAutocomplete\Exceptions\QueryException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function generateResponse(): array
    {
        return $this->service
            ->load($this->getQuery())
            ->getResults()
            ->jsonSerialize();
    }

    protected function getSearchLanguage(): GoogleAutocomplete\Enums\SearchLanguage
    {
        return new GoogleAutocomplete\Enums\SearchLanguage($this->language);
    }

    abstract protected function getQuery(): GoogleAutocomplete\Queries\Interfaces\SearchQueryInterface;
}
