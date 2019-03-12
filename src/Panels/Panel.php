<?php

namespace Wearesho\GoogleAutocomplete\Yii\Panels;

use Wearesho\GoogleAutocomplete;
use Wearesho\Yii\Http;
use yii\web\HttpException;
use Horat1us\Environment\MissingEnvironmentException;
use GuzzleHttp\Exception\GuzzleException;

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
                'attributes' => ['token', 'input', 'language'],
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
     * @throws HttpException
     */
    protected function generateResponse(): array
    {
        try {
            return $this->service
                ->load($this->getQuery())
                ->getResults()
                ->jsonSerialize();
        } catch (GuzzleException $exception) {
            throw new HttpException(
                503,
                'Google autocomplete API is unavailable',
                1,
                $exception
            );
        } catch (GoogleAutocomplete\Exceptions\QueryException $exception) {
            throw new HttpException(
                503,
                "Google autocomplete API query error: " . $exception->getStatus(),
                2,
                $exception
            );
        } catch (MissingEnvironmentException $exception) {
            throw new HttpException(
                503,
                "Google autocomplete API is not configured",
                3,
                $exception
            );
        }
    }

    protected function getSearchLanguage(): GoogleAutocomplete\Enums\SearchLanguage
    {
        return new GoogleAutocomplete\Enums\SearchLanguage($this->language);
    }

    abstract protected function getQuery(): GoogleAutocomplete\Queries\Interfaces\SearchQueryInterface;
}
