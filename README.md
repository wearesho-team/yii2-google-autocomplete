# Yii2 Google Autocomplete Api Integrate
[![Build Status](https://travis-ci.org/wearesho-team/yii2-google-autocomplete.svg?branch=master)](https://travis-ci.org/wearesho-team/yii2-google-autocomplete)
[![codecov](https://codecov.io/gh/wearesho-team/yii2-google-autocomplete/branch/master/graph/badge.svg)](https://codecov.io/gh/wearesho-team/yii2-google-autocomplete)

## Installation

Use [composer](https://packagist.org) to install the package:

```bash
composer require wearesho-team/yii2-google-autocomplete
```

## Usage

### Bootstrap

Add to your `common/config/main.php`:

```php
<?php

use Wearesho\GoogleAutocomplete;

$config = [
    'bootstrap' => [
        'googleAutocomplete' => [
            'class' => GoogleAutocomplete\Yii\Bootstrap::class
        ],
    ]
];
```

### Controller

Add to your `api/config/main.php`:

```php
<?php

use Wearesho\GoogleAutocomplete;

$module = [
    'controllerMap' => [
        'autocomplete' => [
            'class' => GoogleAutocomplete\Yii\Controller::class,
        ],
    ]
];
```

## Contributors

- [Roman <KartaviK> Varkuta](mailto:roman.varkuta@gmail.com)

## License

Unlicensed
